<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle("Реквизиты организации");
$APPLICATION->AddChainItem("Реквизиты организации");

$userInfo = getContainer("User");
?>