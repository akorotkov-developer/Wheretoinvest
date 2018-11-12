<?
namespace Wic\BanksInfo;

use Wic\BanksInfo\Tools;

class Info extends Connects implements Interfaces\IInfo
{
    public function getFilialInfoByBic($BIC) {
        $intCode = $this->client->BicToIntCode(array("BicCode" => (string)$BIC))->BicToIntCodeResult;

        $filialInfo = $this->client->GetOfficesXML(array("IntCode" => $intCode));

        return $filialInfo;
    }

    //Получить сайт организации по BIC
    public function getWebSiteOrganization($BIC) {
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
    public function get135formData($regNumber) {
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
    public function get123formData($regNumber) {
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
    public function getOrgInfoByIntCode($BIC, Tools $tools) {
        $info = array();

        $intCode = $this->client->BicToIntCode(array('BicCode' => $BIC));

        $obj = $this->client->CreditInfoByIntCodeXML(array('InternalCode' => $intCode->BicToIntCodeResult));
        $xml =  new \SimpleXMLElement($obj->CreditInfoByIntCodeXMLResult->any);

        $FullName = $xml->CO->OrgFullName;
        $ShortName = $tools->getShortNameByFullName($FullName);

        $info["ShortName"] = $ShortName;
        $info["OrgStatus"] = $xml->CO->OrgStatus;
        $info["SSV_Date"] = $xml->CO->SSV_Date;

        return $info;
    }
}