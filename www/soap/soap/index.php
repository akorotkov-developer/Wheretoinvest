<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("SOAP Рабочий варинат");

use Wic\BanksInfo\Banks;
?>

<?
    $banks = new Banks;

    //Обновляем список пользователей
    $banks->updateUsers();

    //Удаляем объект класса
    unset($banks);
?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>