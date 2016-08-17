<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle("Мой регион");
$APPLICATION->AddChainItem("Мой регион");

$arResult["CURRENT_LOC_NAME"] = $APPLICATION->get_cookie("CURRENT_LOC_NAME");
?>
<div class="row">
    <div class="column small-12">
        <div class="req req_value b-header__firstline-linkreg_js"><?= $arResult["CURRENT_LOC_NAME"] ?></div>
    </div>
</div>
<br>
<div class="row">
    <div class="column small-12">
        <a href="#" class="content__change" data-reveal-id="saleLocation">Изменить</a>
    </div>
</div>