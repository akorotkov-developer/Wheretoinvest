<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle("Реквизиты организации");
$APPLICATION->AddChainItem("Реквизиты организации");

$userInfo = getContainer("User");
?>

<? if (!empty($_SESSION["SUCCESS"])): ?>
    <div data-alert class="alert-box success radius"><?= $_SESSION["SUCCESS"] ?><a href="#" class="close">&times;</a>
    </div>
    <? unset($_SESSION["SUCCESS"]); ?>
<? endif; ?>

<div class="row">
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Логотип:</div>
            <div class="req__value medium-6 small-7 columns js-detail-work_logo">
                <? if (!empty($userInfo["WORK_LOGO"])): ?>
                    <img src="<?= CFile::GetPath($userInfo["WORK_LOGO"]); ?>" alt="<?= $userInfo["WORK_COMPANY"] ?>">
                <? else: ?>
                    Не задан
                <? endif; ?>
            </div>
        </div>
    </div>
    <div class="columns req req_p">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Краткое наименование:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-work_company"><?= !empty($userInfo["WORK_COMPANY"]) ? $userInfo["WORK_COMPANY"] : "Не задано"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Полное наименование:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_full_work_name"><?= !empty($userInfo["UF_FULL_WORK_NAME"]) ? $userInfo["UF_FULL_WORK_NAME"] : "Не задано"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Краткое наименование на английском языке:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_short_work_en"><?= !empty($userInfo["UF_SHORT_WORK_EN"]) ? $userInfo["UF_SHORT_WORK_EN"] : "Не задано"; ?></div>
        </div>
    </div>
    <div class="columns req req_p">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">ОГРН:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_ogrn"><?= !empty($userInfo["UF_OGRN"]) ? $userInfo["UF_OGRN"] : "Не задан"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Лицензия ЦБ РФ:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_license"><?= !empty($userInfo["UF_LICENSE"]) ? $userInfo["UF_LICENSE"] : "Не задана"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">ИНН:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_inn"><?= !empty($userInfo["UF_INN"]) ? $userInfo["UF_INN"] : "Не задан"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">КПП:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_kpp"><?= !empty($userInfo["UF_KPP"]) ? $userInfo["UF_KPP"] : "Не задан"; ?></div>
        </div>
    </div>
    <div class="columns req req_p">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Расчётный счёт:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_account"><?= !empty($userInfo["UF_ACCOUNT"]) ? $userInfo["UF_ACCOUNT"] : "Не задан"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Банк-получатель:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_bank_info"><?= !empty($userInfo["UF_BANK_INFO"]) ? $userInfo["UF_BANK_INFO"] : "Не задан"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">БИК:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_bik"><?= !empty($userInfo["UF_BIK"]) ? $userInfo["UF_BIK"] : "Не задан"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Корр. счёт:</div>
            <div
                class="req__value medium-6 small-7 columns js-detail-uf_c_account"><?= !empty($userInfo["UF_C_ACCOUNT"]) ? $userInfo["UF_C_ACCOUNT"] : "Не задан"; ?></div>
        </div>
    </div>
    <div class="columns req req_p req_last">
        <div class="row">
            <div class="req__name medium-6 small-5 columns">Сайт:</div>
            <div
                class="req__value medium-6 small-7 columns"><?= !empty($userInfo["UF_SITE"]) ? '<a href="' . $userInfo["UF_SITE"] . '" class="req__link js-detail-uf_site" target="_blank">' . $userInfo["UF_SITE"] . '</a>' : "Не задан"; ?>
            </div>
        </div>
    </div>
</div>
<a class="content__change" href="edit/">Изменить</a>

<?
$arResult["FORM_FIELDS"] = Array(
    "WORK_COMPANY" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Краткое наименование",
        "VALUE" => $userInfo["WORK_COMPANY"]
    ),
    "UF_FULL_WORK_NAME" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Полное наименование",
        "VALUE" => $userInfo["UF_FULL_WORK_NAME"]
    ),
    "UF_SHORT_WORK_EN" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Краткое наименование на английском языке",
        "VALUE" => $userInfo["UF_SHORT_WORK_EN"]
    ),
    "WORK_LOGO" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Логотип",
        "VALUE" => $userInfo["WORK_LOGO"]
    ),
    "UF_SITE" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Сайт",
        "VALUE" => $userInfo["UF_SITE"]
    ),
    "UF_LICENSE" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Лицензия ЦБ РФ",
        "VALUE" => $userInfo["UF_LICENSE"]
    ),
    "UF_OGRN" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "ОГРН",
        "VALUE" => $userInfo["UF_OGRN"]
    ),
    "UF_INN" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "ИНН",
        "VALUE" => $userInfo["UF_INN"]
    ),
    "UF_KPP" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "КПП",
        "VALUE" => $userInfo["UF_KPP"]
    ),
    "UF_ACCOUNT" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Расчётный счёт",
        "VALUE" => $userInfo["UF_ACCOUNT"]
    ),
    "UF_BANK_INFO" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Банк-получатель",
        "VALUE" => $userInfo["UF_BANK_INFO"]
    ),
    "UF_BIK" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "БИК",
        "VALUE" => $userInfo["UF_BIK"]
    ),
    "UF_C_ACCOUNT" => Array(
        "TYPE" => "TEXT",
        "TITLE" => "Корр.счёт",
        "VALUE" => $userInfo["UF_C_ACCOUNT"]
    ),
);
?>
