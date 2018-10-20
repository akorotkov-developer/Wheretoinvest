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
    private $regions;

    function __construct() {
        //Создаем подключение к WSDL серверу
        $this->client = new \SoapClient("http://cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL", array('exceptions' => false));
        //Подключение к списку сайтов банков
        $this->websites = new \SimpleXMLElement(file_get_contents("http://www.cbr.ru/credit/GetAsXML.asp"));
        //Подключение к списку банков
        $this->banks = new \SimpleXMLElement(file_get_contents("http://cbr.ru/scripts/XML_bic2.asp"));
    }

    private function getFilialInfoByBic($BIC) {
        $intCode = $this->client->BicToIntCode(array("BicCode" => (string)$BIC))->BicToIntCodeResult;

        $filialInfo = $this->client->GetOfficesXML(array("IntCode" => $intCode));

        return $filialInfo;
    }

    //Перевод строки в транслит
    private function translit($s, $param) {
        if ($param == "Y") {
            $s = (string)$s; // преобразуем в строковое значение
            $s = strip_tags($s); // убираем HTML-теги
            $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
            $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
            $s = trim($s); // убираем пробелы в начале и конце строки
            $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
            $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
            $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
            $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
            return $s; // возвращаем результат
        } else {
            $translit = array(
                'а' => 'a',   'б' => 'b',   'в' => 'v',

                'г' => 'g',   'д' => 'd',   'е' => 'e',

                'ё' => 'yo',   'ж' => 'zh',  'з' => 'z',

                'и' => 'i',   'й' => 'j',   'к' => 'k',

                'л' => 'l',   'м' => 'm',   'н' => 'n',

                'о' => 'o',   'п' => 'p',   'р' => 'r',

                'с' => 's',   'т' => 't',   'у' => 'u',

                'ф' => 'f',   'х' => 'x',   'ц' => 'c',

                'ч' => 'ch',  'ш' => 'sh',  'щ' => 'shh',

                'ь' => '\'',  'ы' => 'y',   'ъ' => '\'\'',

                'э' => 'e\'',   'ю' => 'yu',  'я' => 'ya',


                'А' => 'A',   'Б' => 'B',   'В' => 'V',

                'Г' => 'G',   'Д' => 'D',   'Е' => 'E',

                'Ё' => 'YO',   'Ж' => 'Zh',  'З' => 'Z',

                'И' => 'I',   'Й' => 'J',   'К' => 'K',

                'Л' => 'L',   'М' => 'M',   'Н' => 'N',

                'О' => 'O',   'П' => 'P',   'Р' => 'R',

                'С' => 'S',   'Т' => 'T',   'У' => 'U',

                'Ф' => 'F',   'Х' => 'X',   'Ц' => 'C',

                'Ч' => 'CH',  'Ш' => 'SH',  'Щ' => 'SHH',

                'Ь' => '\'',  'Ы' => 'Y\'',   'Ъ' => '\'\'',

                'Э' => 'E\'',   'Ю' => 'YU',  'Я' => 'YA',
            );

            $string = strtr($s, $translit);
            return $string;
        }
    }

    //Составление короткого названия из полного
    private function getShortNameByFullName($FullName) {
        $name = $FullName;
        $ShortName = "";
        $needle = '"';
        $lastPos = 0;
        $positions = array();

        while (($lastPos = strpos($name, $needle, $lastPos))!== false) {
            $positions[] = $lastPos;
            $lastPos = $lastPos + strlen($needle);
        }

        $cuurentN = substr($name, $positions[0], $positions[count($positions)-1]-$positions[0]+1);

        if ($positions[0] == 0) {
            $firstN = false;
        } else {
            $firstN = substr($name, 0, $positions[0] - 1);
        }
        $lastN = substr($name, $positions[count($positions)-1]+2, strlen($name)-1);


        if ($firstN) {
            $firstN = explode(' ', $firstN);
            if (count($firstN) > 1) {
                foreach ($firstN as $i => $element) {
                    if (strlen($firstN[$i]) > 1) {
                        $fSymb = substr($firstN[$i], 0, 1);
                        if ($fSymb != '"') {
                            if ($fSymb == '(') {
                                $fSymb = substr($firstN[$i], 1, 1);
                                $firstN[$i] = '(' . strtoupper($fSymb);
                            } elseif (substr($firstN[$i], strlen($firstN[$i]) - 1, 1) == ')') {
                                $fSymb = substr($firstN[$i], 0, 1);
                                $firstN[$i] = strtoupper($fSymb) . ')';
                            } else {
                                $fSymb = substr($firstN[$i], 0, 1);
                                $firstN[$i] = strtoupper($fSymb);
                            }
                        }
                        $ShortName .= $firstN[$i];
                    }
                }
                $ShortName .= ' ';
            } else {
                $ShortName .= $firstN[0].' ';
            }
        }

        $ShortName .= $cuurentN;

        if ($lastN != '') {
            $ShortName .= ' ';
            $lastN = explode(' ', $lastN);
            foreach ($lastN as $i => $element) {
                if (strlen($lastN[$i]) > 1) {
                    $fSymb = substr($lastN[$i], 0, 1);
                    if ($fSymb != '"') {
                        if ($fSymb == '(') {
                            $fSymb = substr($lastN[$i], 1, 1);
                            $lastN[$i] = '(' . strtoupper($fSymb);
                        } elseif (substr($lastN[$i], strlen($lastN[$i]) - 1, 1) == ')') {
                            $fSymb = substr($lastN[$i], 0, 1);
                            $lastN[$i] = strtoupper($fSymb) . ')';
                        } else {
                            $fSymb = substr($lastN[$i], 0, 1);
                            $lastN[$i] = strtoupper($fSymb);
                        }
                    }
                    $ShortName .= $lastN[$i];
                }
            }
        }

        return $ShortName;
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
        try {
            $data = $this->client->Data135FormFullXML(array("CredorgNumber" => $regNumber, "OnDate" => $response));
        } catch (SoapFault $e) {
            return;
        }
        if ($data) {
            if (get_class($data) != "SoapFault") {

                $data = $data->Data135FormFullXMLResult->any;

                $xml = new \SimpleXMLElement($data);

                $elements = $xml->xpath("//F135_3[contains(C3,'Н1.0')]");
                foreach ($elements as $element) {
                    $h10 = $element->V3;
                }
            } else {$h10 = "";}
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
        if ($data) {
            if (get_class($data) != "SoapFault") {
                $data = $data->Data123FormFullXMLResult->any;

                $xml = new \SimpleXMLElement($data);

                $elements = $xml->xpath("//F123[contains(CODE,'000')]");
                foreach($elements as $element) {
                    $sobcapital = $element->VALUE;
                }

                $result["sobcapital"] = $sobcapital;
                $result["date"] = strtotime($response); // переводит из строки в дату
                $result["date"] = date("d.m.Y", $result["date"]); // переводит в новый формат
            } else {$result = "";}
        }


        return $result;
    }

    //Дополнительная информация по организации организации по Bic коду
    private function getOrgInfoByIntCode($BIC) {
       $info = array();

       $intCode = $this->client->BicToIntCode(array('BicCode' => $BIC));

       $obj = $this->client->CreditInfoByIntCodeXML(array('InternalCode' => $intCode->BicToIntCodeResult));
       $xml =  new \SimpleXMLElement($obj->CreditInfoByIntCodeXMLResult->any);

       $FullName = $xml->CO->OrgFullName;
       $ShortName = $this->getShortNameByFullName($FullName);

       $info["ShortName"] = $ShortName;
       $info["OrgStatus"] = $xml->CO->OrgStatus;
       $info["SSV_Date"] = $xml->CO->SSV_Date;

       return $info;
    }

    //Добавлить предложение и Матрицу для банка
    private function setOfferAndMAtrix($userID) {
        $itemHblockOffer = new \Cetera\HBlock\SimpleHblockObject(3);
        $itemHblockMatrix = new \Cetera\HBlock\SimpleHblockObject(9);

        //Проверяем есть ли предлжение с таким ID
        $idElem = array();
        $filter["UF_USER_ID"] = $userID;
        $query["filter"] = $filter;
        $list = $itemHblockOffer->getList($query);
        while ($el = $list->fetch()) {
            $idElem[] = $el;
        }

        //TODO пока клиент не придумал что делать с полученными данными берем первое попавшееся предложение
        if ($idElem[0]["ID"]) {
            //Если предложение есть, то ищем Матрицу в HBlock Matrix создаем Матрицу в HBlock Matrix
            $filter = array();
            $filter["UF_OFFER"] = $idElem[0]["ID"];
            $query["filter"] = $filter;
            $list = $itemHblockMatrix->getList($query);
            $Elem = false;
            while ($el = $list->fetch()) {
                $Elem[] = $el;
            }
            //TODO Если нашли Матрицу в HBlock Matrix то..
            if ($Elem) {
                //В процессе
            } else {
                $item = array(
                    "UF_OFFER" => $idElem[0]["ID"],
                    "UF_SUMM"  => 10000,
                    "UF_DATE_START" => 369,
                    "UF_CURRENCY" => 28,
                    "UF_PERCENT" => 0.1
                );
                $itemHblockMatrix->add($item);
            }
        } else {
            //Если нет создаем предложение
            $date = "12.10.2019";
            $date = strtotime($date); // переводит из строки в дату
            $dateEnd = date("d.m.Y", $date);

            $item = array(
                "UF_USER_ID"=>$userID,
                "UF_METHOD"=>3,
                "UF_NAME"=>"-",
                "UF_TYPE"=>26,
                "UF_REGIONS"=>array(71,77,24,34,3,16,18,32,23,17,83,66,4,64,38,26,5,78,42,67,47,6,31,65,85,60,7,20,8,79,19,2,25,29,48,28,68,69,54,9,53,52,81,27,35,75,55,72,37,41,36,22,21,46,44,80,39,45,74,73,33,10,51,30,57,82,59,4913,11,40,12,13,70,14,58,49,56,76,62,63,43,50,84,61,15),
                "UF_UPDATED"=>date("d.m.Y H:i:s"),
                "UF_SITE"=>"",
                "UF_ACTIVE_START"=>array(date("d.m.Y")),
                "UF_ACTIVE_END"=>array($dateEnd)
            );
            $itemHblockOffer->add($item);

            //Ищем предложение
            $idElem =array();
            $filter["UF_USER_ID"] = $userID;
            $query["filter"] = $filter;
            $list = $itemHblockOffer->getList($query);
            while ($el = $list->fetch()) {
                $idElem[] = $el;
            }
            //Затем создаем матрицу для этого предложения
            if ($idElem[0]["ID"]) {
                $item = array(
                    "UF_OFFER" => $idElem[0]["ID"],
                    "UF_SUMM"  => 10000,
                    "UF_DATE_START" => 369,
                    "UF_CURRENCY" => 28,
                    "UF_PERCENT" => 0.1
                );
                $itemHblockMatrix->add($item);
            }
        }
    }

    //Обновить пользователей
    public function updateUsers() {
        //Получаем список банков и для каждого банка обновляем либо создаем пользователя
        $bankList = $this->banks;

        $i=0;
        foreach ($bankList->Record as $Record) {
            if ($i > -1) {
                //Смотрим есть ли уже пользователь для банка
                $login = $this->translit($Record->ShortName, "Y") . "@wheretoinvest.ru";
                $password = $this->translit($Record->ShortName);
                $rsUser = \CUser::GetByLogin($login);
                $arUser = $rsUser->Fetch();

                //Получаем все данные для банка

                //Информация по филиально сети
                $filialInfo = $this->getFilialInfoByBic($Record->Bic);

                //Получаем веб-сайт банка
                $website = $this->getWebSiteOrganization($Record->Bic);

                //Получаем дополнительную информацию об организации
                $info = $this->getOrgInfoByIntCode($Record->Bic);

                //Рег. Номер
                $regNumber = $this->client->BicToRegNumber(array('BicCode' => (string)$Record->Bic));
                $regNumber = $regNumber->BicToRegNumberResult;

                //Участие в системе страхования вкладов
                if ($info["SSV_Date"] > 0) {
                    $insurance = 25;
                } else {
                    $insurance = 0;
                }

                //Н1.0 по форме 135
                echo "H!";
                echo "<pre>";
                var_dump($regNumber);
                echo "</pre>";
                $h10 = $this->get135formData($regNumber);

                //Собственный капитал по форме 123
                $sobcapital = $this->get123formData($regNumber);

                //Активы
                if ($sobcapital["sobcapital"] > 0 and $h10 != 0) {
                    $active = ceil($sobcapital["sobcapital"] / ((float)$h10 / 100));
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
                        "ACTIVE" => "Y",
                        "GROUP_ID" => array(3, 4, 6),
                        "WORK_COMPANY" => $info["ShortName"],
                        "UF_SHORT_WORK_EN" => $this->translit($info["ShortName"], "N"),
                        "UF_FULL_WORK_NAME" => $Record->ShortName,
                        "UF_BIK" => $Record->Bic,
                        "UF_SITE" => $website,
                        "UF_LICENSE" => $regNumber,
                        "UF_OGRN" => $Record->RegNum,
                        "UF_CAPITAL_ASSETS" => $h10,
                        "UF_CAPITAL" => $sobcapital["sobcapital"],
                        "UF_ASSETS" => $active,
                        "UF_NOTE" => $info["OrgStatus"],
                        "UF_ASSETS_DATE" => $sobcapital["date"],
                        "UF_SIT_CB" => 1,
                        "UF_BANK_PARTICIP" => $insurance,
                    );

                    if ($user->Update($arUser["ID"], $arFields)) {
                        echo "Пользователь: " . $login . " Обновлен";
                        echo "<br>";
                    }

                    //Добавляем предлпжение и матрицу для банка
                    $this->setOfferAndMAtrix($arUser["ID"]);
                } else {
                    $arFields = Array(
                        "NAME" => "Богдан",
                        "EMAIL" => $login,
                        "LOGIN" => $login,
                        "ACTIVE" => "Y",
                        "GROUP_ID" => array(3, 4, 6),
                        "PASSWORD" => $password,
                        "CONFIRM_PASSWORD" => $password,
                        "WORK_COMPANY" => $info["ShortName"],
                        "UF_SHORT_WORK_EN" => $this->translit($info["ShortName"]),
                        "UF_FULL_WORK_NAME" => $Record->ShortName,
                        "UF_BIK" => $Record->Bic,
                        "UF_SITE" => $website,
                        "UF_LICENSE" => $regNumber,
                        "UF_OGRN" => $Record->RegNum,
                        "UF_CAPITAL_ASSETS" => $h10,
                        "UF_CAPITAL" => $sobcapital["sobcapital"],
                        "UF_ASSETS" => $active,
                        "UF_NOTE" => $info["OrgStatus"],
                        "UF_ASSETS_DATE" => $sobcapital["date"],
                        "UF_SIT_CB" => 1,
                        "UF_BANK_PARTICIP" => $insurance,
                    );

                    $ID = $user->Add($arFields);
                    if (intval($ID) > 0) {
                        echo "Пользователь: " . $login . " Успешно добавлен.";
                        echo "<br>";
                    } else {
                        echo $user->LAST_ERROR;
                    }

                    //Добавляем предлпжение и матрицу для банка
                    $this->setOfferAndMAtrix($arUser["ID"]);
                }


            }
            $i++;
            echo "<br><b>" . $i . "</b><br>";
            if ($i > 100) {
                break;
            }
        }
    }
}