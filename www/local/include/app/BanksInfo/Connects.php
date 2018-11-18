<?
namespace Wic\BanksInfo;

class Connects {
    protected $client;
    protected $websites;
    protected $banks;

    function __construct() {
        //Создаем подключение к WSDL серверу
        $this->client = new \SoapClient("http://cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL", array('exceptions' => false));
        //Подключение к списку сайтов банков
        $this->websites = new \SimpleXMLElement(file_get_contents("http://www.cbr.ru/credit/GetAsXML.asp"));
        //Подключение к списку банков
        $this->banks = new \SimpleXMLElement(file_get_contents("http://cbr.ru/scripts/XML_bic2.asp"));
    }
}