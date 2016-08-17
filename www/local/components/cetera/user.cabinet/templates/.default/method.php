<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle("Моя методика определения надежности");
$APPLICATION->AddChainItem("Моя методика определения надежности");
?>

<? $APPLICATION->IncludeComponent("cetera:super.component", "user.method", Array()); ?>