<?
//подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Wic\BanksInfo\UpdateBanks,
    Wic\BanksInfo\Tools,
    Wic\BanksInfo\Info,
    Wic\BanksInfo\SiteOffers;
?>

<?
phpinfo();

$banks = new UpdateBanks;
$banks->updateUsers($Cinfo = new Info ,   $siteOffers = new SiteOffers);
unset($banks);
?>