<?
namespace Wic\BanksInfo;

class UpdateBanks implements Interfaces\IUpdateBanks {
    protected $banks;
    protected $client;

    function __construct() {
        //����������� � ������ ������
        //$xmlstr = utf8_encode(file_get_contents(Config::BANKS));
/*        $xmlstr = utf8_encode(file_get_contents(Config::BANKS));
        settype($xmlstr, "string");
        $this->banks = new \SimpleXMLElement($xmlstr);
        //������� ����������� � WSDL �������
        $this->client = new \SoapClient(Config::CLIENT, array('exceptions' => false));*/
    }
    //�������� �������������
    public function updateUsers(Info $Cinfo, SiteOffers $siteOffers) {
        //�������� ������ ������ � ��� ������� ����� ��������� ���� ������� ������������
        $xmlstr = file_get_contents(Config::BANKS);
        echo "<pre>";
        var_dump($xmlstr);
        echo "</pre>";

        /*$bankList = $this->banks;

        $i=0;
        foreach ($bankList->Record as $Record) {
            if ($i < 2) {
                $i++;

                //����� ������ ������������
                $login = Tools::translit($Record->ShortName, "Y") . Config::EMAIL_END;
                $password = Tools::translit($Record->ShortName, "Y") . Config::PASSWORD_KEY;

                //�������� ��� ������ ��� �����

                //�������� ���-���� �����
                //TODO �� ����� http://cbr.ru/development/WSCO/ �� �������� ���� ����������
                //$website = $Cinfo->getWebSiteOrganization($Record->Bic);
                $website = '';

                //�������� �������������� ���������� �� �����������
                $info = $Cinfo->getOrgInfoByIntCode($Record->Bic);

                //���. �����
                $regNumber = $this->client->BicToRegNumber(array('BicCode' => (string)$Record->Bic));
                $regNumber = $regNumber->BicToRegNumberResult;

                //������� � ������� ����������� �������
                if ($info["SSV_Date"] > 0) {
                    $insurance = 25;
                } else {
                    $insurance = 0;
                }

                //�1.0 �� ����� 135
                $h10 = $Cinfo->get135formData($regNumber);

                //����������� ������� �� ����� 123
                $sobcapital = $Cinfo->get123formData($regNumber);

                //������
                if ($sobcapital["sobcapital"] > 0 and $h10 != 0) {
                    $active = ceil($sobcapital["sobcapital"] / ((float)$h10 / 100));
                } else {
                    $active = false;
                }

                //������� ���� �� ��� ������������ ��� �����
                $arSpecUser = false;
                $filter = Array(
                    "UF_OGRN" => $Record->RegNum,
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
                //���� ������������ ����, �� ��������� ��� ������, ���� ���, �� ������� ������ ������������
                $user = new \CUser;

                //���� ������������
                $arFields = Array(
                    "ACTIVE" => "Y",
                    "GROUP_ID" => Config::PARTENRS_GROUP,
                    "UF_BANK_INFO" => $info["ShortName"],
                    "UF_FULL_WORK_NAME" => $Record->ShortName,
                    "UF_SITE" => $website,
                    "UF_LICENSE" => $regNumber,
                    "UF_OGRN" => $Record->RegNum,
                    "UF_CAPITAL_ASSETS" => $h10,
                    "UF_CAPITAL" => $sobcapital["sobcapital"],
                    "UF_ASSETS" => $active,
                    "UF_NOTE" => $info["OrgStatus"],
                    "UF_SIT_CB" => 1,
                    "UF_BANK_PARTICIP" => $insurance,
                    "UF_CAPITAL_A_DATE" => date("1.m.Y"),
                    "UF_CAPITAL_DATE" => date("1.m.Y"),
                    "UF_ASSETS_DATE" => date("1.m.Y"),
                    "UF_LASTUPDATE" => date("d.m.Y H:i:s"),

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
                        AddMessage2Log("�" . $i . ": ������������: " . $login . " ��������", "");
                    } else {
                        AddMessage2Log($user->LAST_ERROR, "");
                    }
                } else {
                    $arFieldsNewUser = array(
                        "NAME" => Config::CLIENT_NAME,
                        "EMAIL" => $login,
                        "LOGIN" => $login,
                        "UF_BIK" => $Record->Bic,
                        "PASSWORD" => $password,
                        "CONFIRM_PASSWORD" => $password,
                    );

                    $arFields = array_merge($arFields, $arFieldsNewUser);

                    $userID = $user->Add($arFields);
                    if (intval($userID) > 0) {
                        AddMessage2Log("�" . $i . ":������������: " . $login . " ������� ��������.", "main");
                    } else {
                        AddMessage2Log($user->LAST_ERROR, "main");
                    }
                }
                echo "�" . $i . ":������������: " . $login . " ������� ��������.<br>";
                //��������� ����������� � ������� ��� �����
                $siteOffers->setOfferAndMAtrix($userID);
            }
        }*/
    }
}