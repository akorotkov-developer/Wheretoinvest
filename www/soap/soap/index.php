<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("SOAP Рабочий варинат");

use Wic\BanksInfo\UpdateBanks, Wic\BanksInfo\Tools, Wic\BanksInfo\Info, Wic\BanksInfo\SiteOffers;
?>

<?
    $banks = new UpdateBanks;

    $banks->updateUsers($tools = new Tools, $Cinfo = new Info,   $siteOffers = new SiteOffers);


    //Удаляем объект класса
    unset($banks);
?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>