<?
namespace Wic\BanksInfo;

class UpdateBanks extends Connects implements Interfaces\IUpdateBanks {
    //Обновить пользователей
    public function updateUsers(Tools $tools, Info $Cinfo, SiteOffers $siteOffers) {
        //Получаем список банков и для каждого банка обновляем либо создаем пользователя
        $bankList = $this->banks;

        $i=0;
        foreach ($bankList->Record as $Record) {
            if ($i > -1) {
                //Смотрим есть ли уже пользователь для банка
                $login = $tools->translit($Record->ShortName, "Y") . "@wheretoinvest.ru";
                $password = $tools->translit($Record->ShortName);
                $rsUser = \CUser::GetByLogin($login);
                $arUser = $rsUser->Fetch();

                //Получаем все данные для банка

                //Информация по филиально сети
                $filialInfo = $Cinfo->getFilialInfoByBic($Record->Bic);

                //Получаем веб-сайт банка
                $website = $Cinfo->getWebSiteOrganization($Record->Bic);

                //Получаем дополнительную информацию об организации
                $info = $Cinfo->getOrgInfoByIntCode($Record->Bic, $tools);

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
                        "UF_SHORT_WORK_EN" => $tools->translit($info["ShortName"], "N"),
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
                    $siteOffers->setOfferAndMAtrix($arUser["ID"]);
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
                        "UF_SHORT_WORK_EN" => $tools->translit($info["ShortName"]),
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
                    $siteOffers->setOfferAndMAtrix($arUser["ID"]);
                }


            }
            $i++;
            echo "<br><b>" . $i . "</b><br>";
            if ($i > 3) {
                break;
            }
        }
    }
}