<?php
/**
 * Информация о КО
 */
namespace Wic\BanksInfo;


class Banks
{
    private $client;
    private $websites;
    private $banks;

    function __construct()
    {
        //Создаем подключение к WSDL серверу
        $this->client = new \SoapClient("http://cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL");
        //Подключение к списку сайтов банков
        $this->websites = new \SimpleXMLElement(file_get_contents("http://www.cbr.ru/credit/GetAsXML.asp"));
        //Подключение к списку банков
        $this->banks = new \SimpleXMLElement(file_get_contents("http://cbr.ru/scripts/XML_bic2.asp"));
    }

    //Перевод строки в транслит
    private function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }

    //Получить сайт организации по BIC
    private function getWebSiteOrganization($BIC) {
        $rgn = $this->client->BicToRegNumber(array('BicCode' => (string)$BIC));
        $rgn = $rgn->BicToRegNumberResult;

        $xml = $this->websites;

        $xpath = $xml->xpath("/BnkSites/Bnk[@rgn='${rgn}']");
        foreach ($xpath as $site) {
            $website = $site->Itm->attributes()->title;
        }

        return $website;
    }

    //Получаем данные по формы 135
    private function get135formData($regNumber) {
        //Получаем последнюю дату для формы 135
        $response = $this->client->GetDatesForF135(array("CredprgNumber" => $regNumber));
        $response = $response->GetDatesForF135Result->dateTime;
        $response = $response[count($response)-1];

        //Данные по форме 135
        $data = $this->client->Data135FormFullXML(array("CredorgNumber" => $regNumber, "OnDate" => $response));
        $data = $data->Data135FormFullXMLResult->any;

        $xml = new \SimpleXMLElement($data);

        $elements= $xml->xpath("//F135_3[contains(C3,'Н1.0')]");
        foreach($elements as $element) {
            $h10 = $element->V3;
        }

        return $h10;
    }

    //Получаем данные по форме 123
    private function get123formData($regNumber) {
        //Получаем последнюю дату для формы 135
        $response = $this->client->GetDatesForF123(array("CredprgNumber" => $regNumber));
        $response = $response->GetDatesForF123Result->dateTime;
        $response = $response[count($response)-1];

        //Данные по форме 123
        $data = $this->client->Data123FormFullXML(array("CredorgNumber" => $regNumber, "OnDate" => $response));
        $data = $data->Data123FormFullXMLResult->any;

        $xml = new \SimpleXMLElement($data);
        $xml->saveXML("123.xml");

        $elements = $xml->xpath("//F123[contains(CODE,'000')]");
        foreach($elements as $element) {
            $sobcapital = $element->VALUE;
        }

        return $sobcapital;
    }

    //Обновить пользователей
    public function updateUsers() {
        //Получаем список банков и для каждого банка обновляем либо создаем пользователя
        $bankList = $this->banks;

        $i=0;
        foreach ($bankList->Record as $Record) {

            //Смотрим есть ли уже пользователь для банка
            $login = $this->translit($Record->ShortName)."@wheretoinvest.ru";
            $rsUser = \CUser::GetByLogin($login);
            $arUser = $rsUser->Fetch();

            //Получаем все данные для банка

            //Получаем веб-сайт банка
            $website = $this->getWebSiteOrganization($Record->Bic);

            //Рег. Номер
            $regNumber = $this->client->BicToRegNumber(array('BicCode' => (string)$Record->Bic));
            $regNumber = $regNumber->BicToRegNumberResult;

            //Н1.0 по форме 135
            $h10 = $this->get135formData($regNumber);

            //Собственный капитал по форме 123
            $sobcapital = $this->get123formData($regNumber);

            //Активы
            if ($sobcapital > 0 and $h10 != 0) {
                $active = $sobcapital / $h10;
            } else {
                $active = false;
            }

            //Если пользователь есть, то обновляем его данные, если нет, то создаем нового пользователя
            $user = new \CUser;

            if ($arUser) {
                //ADD USER
                if (!$active) {
                    $active = $arUser["UF_ASSETS"];
                }

                $arFields = Array(
                    "ACTIVE"            => "Y",
                    "GROUP_ID"          => array(3,4,6),
                    "WORK_COMPANY"  => $Record->ShortName,
                    "UF_SHORT_WORK_EN"  => $this->translit($Record->ShortName),
                    "UF_FULL_WORK_NAME"  => $Record->ShortName,
                    "UF_BIK"  => $Record->Bic,
                    "UF_SITE"  => $website,
                    "UF_LICENSE"  => $regNumber,
                    "UF_OGRN"  => $Record->RegNum,
                    "UF_CAPITAL_ASSETS" => $h10,
                    "UF_CAPITAL" => $sobcapital,
                    "UF_ASSETS" => $active,
                );

                if ($user->Update($arUser["ID"], $arFields)) {
                    echo "Пользователь: " . $login . " Обновлен";
                    echo "<br>";
                }
            } else {
                $arFields = Array(
                    "NAME"              => "Богдан",
                    "EMAIL"             => $login,
                    "LOGIN"             => $login,
                    "ACTIVE"            => "Y",
                    "GROUP_ID"          => array(3,4,6),
                    "PASSWORD"          => "123456",
                    "CONFIRM_PASSWORD"  => "123456",
                    "WORK_COMPANY"  => $Record->ShortName,
                    "UF_SHORT_WORK_EN"  => $this->translit($Record->ShortName),
                    "UF_FULL_WORK_NAME"  => $Record->ShortName,
                    "UF_BIK"  => $Record->Bic,
                    "UF_SITE"  => $website,
                    "UF_LICENSE"  => $regNumber,
                    "UF_OGRN"  => $Record->RegNum,
                    "UF_CAPITAL_ASSETS" => $h10,
                    "UF_CAPITAL" => $sobcapital,
                    "UF_ASSETS" => $active,
                );

                $ID = $user->Add($arFields);
                if (intval($ID) > 0) {
                    echo "Пользователь: " . $login . " Успешно добавлен.";
                    echo "<br>";
                } else {
                    echo $user->LAST_ERROR;
                }
            }

            $i++;
            if ($i > 5) {
                break;
            }
        }
    }
}