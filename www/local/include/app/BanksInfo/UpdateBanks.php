<?
namespace Wic\BanksInfo;

class UpdateBanks implements Interfaces\IUpdateBanks {
    protected $banks;
    protected $client;

    function __construct() {
        //Подключение к списку банков
        $this->banks = new \SimpleXMLElement(file_get_contents(Config::BANKS));
        //Создаем подключение к WSDL серверу
        $this->client = new \SoapClient(Config::CLIENT, array('exceptions' => false));
    }
    //Обновить пользователей
    public function updateUsers(Info $Cinfo, SiteOffers $siteOffers) {
        //Получаем список банков и для каждого банка обновляем либо создаем пользователя
        $bankList = $this->banks;

        $i = -1;
        foreach ($bankList->Record as $Record) {
            $i++;
            if ($i<1) {
                //Логин Пароль пользователя
                $login = Tools::translit($Record->ShortName, "Y") . Config::EMAIL_END;
                $password = Tools::translit($Record->ShortName, "Y").Config::PASSWORD_KEY;

                //Получаем все данные для банка

                //Получаем веб-сайт банка
                $website = $Cinfo->getWebSiteOrganization($Record->Bic);

                //Получаем дополнительную информацию об организации
                $info = $Cinfo->getOrgInfoByIntCode($Record->Bic);

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
                $h10 = $Cinfo->get135formData($regNumber);

                //Собственный капитал по форме 123
                $sobcapital = $Cinfo->get123formData($regNumber);

                //Активы
                if ($sobcapital["sobcapital"] > 0 and $h10 != 0) {
                    $active = ceil($sobcapital["sobcapital"] / ((float)$h10 / 100));
                } else {
                    $active = false;
                }

                //Смотрим есть ли уже пользователь для банка
                $arSpecUser = false;
                $filter = Array(
                    "UF_LICENSE" => $regNumber,
                );
                $rsUsers = \CUser::GetList(($by), ($order = "desc"), $filter);
                while ($arUser = $rsUsers->Fetch()) {
                    $arSpecUser = $arUser["LOGIN"];
                }
                if ($arSpecUser) {
                    $login = $arSpecUser;
                }
                $rsUser = \CUser::GetByLogin($login);
                $arUser = $rsUser->Fetch();
                //Если пользователь есть, то обновляем его данные, если нет, то создаем нового пользователя
                $user = new \CUser;

                //Поля пользователя
                $arFields = Array(
                    "ACTIVE" => "Y",
                    "GROUP_ID" => Config::PARTENRS_GROUP,
                    "WORK_COMPANY" => $info["ShortName"],
                    "UF_SHORT_WORK_EN" => Tools::translit($info["ShortName"], "N"),
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

                if ($arUser) {
                    //ADD USER
                    $userID = $arUser["ID"];
                    if (!$active) {
                        $arFields["UF_ASSETS"] = $arUser["UF_ASSETS"];
                    }
                    if ($arUser["UF_SITE"] != "") {
                        $arFields["UF_SITE"] = $arUser["UF_SITE"];
                    }

                    if ($user->Update($userID, $arFields)) {
                        AddMessage2Log("№" . $i . ": Пользователь: " . $login . " Обновлен", "");
                    } else {
                        AddMessage2Log($user->LAST_ERROR, "");
                    }
                } else {
                    $arFieldsNewUser = array(
                        "NAME" => Config::CLIENT_NAME,
                        "EMAIL" => $login,
                        "LOGIN" => $login,
                        "PASSWORD" => $password,
                        "CONFIRM_PASSWORD" => $password,
                    );

                    $arFields = array_merge($arFields, $arFieldsNewUser);

                    $userID = $user->Add($arFields);
                    if (intval($userID) > 0) {
                        AddMessage2Log("№" . $i . ":Пользователь: " . $login . " Успешно добавлен.", "");
                    } else {
                        AddMessage2Log($user->LAST_ERROR, "");
                    }
                }
                //Добавляем предлпжение и матрицу для банка
                $siteOffers->setOfferAndMAtrix($userID);
            }
        }
    }
}