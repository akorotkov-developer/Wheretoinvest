<?
//подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Wic\BanksInfo\UpdateBanks,
    Wic\BanksInfo\Tools,
    Wic\BanksInfo\Info,
    Wic\BanksInfo\SiteOffers;
?>

<?
$banks = new UpdateBanks;

$banks->updateUsers($tools = new Tools, $Cinfo = new Info,   $siteOffers = new SiteOffers);

//Удаляем объект класса
unset($banks);
?>