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
            <div class="req__name medium-4 small-5 columns">Логотип:</div>
            <div class="req__value medium-8 small-7 columns js-detail-work_logo">
                <? if (!empty($userInfo["WORK_LOGO"])): ?>
                    <img src="<?= CFile::GetPath($userInfo["WORK_LOGO"]); ?>" alt="<?= $userInfo["WORK_COMPANY"] ?>">
                <? else: ?>
                    <span class='req__name'>—</span>
                <? endif; ?>
            </div>
        </div>
    </div>
    <div class="columns req">
        <br>
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Сокращенное наименование (согласно Уставу):</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-work_company"><?= !empty($userInfo["WORK_COMPANY"]) ? $userInfo["WORK_COMPANY"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Сокращенное наименование на английском языке (согласно Уставу):</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_short_work_en"><?= !empty($userInfo["UF_SHORT_WORK_EN"]) ? $userInfo["UF_SHORT_WORK_EN"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Наименование (для отображения на главной странице):</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_full_work_name"><?= !empty($userInfo["UF_FULL_WORK_NAME"]) ? $userInfo["UF_FULL_WORK_NAME"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <br>
        <div class="row">
            <div class="req__name medium-4 small-5 columns">ОГРН:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_ogrn"><?= !empty($userInfo["UF_OGRN"]) ? $userInfo["UF_OGRN"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Лицензия ЦБ РФ:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_license"><?= !empty($userInfo["UF_LICENSE"]) ? $userInfo["UF_LICENSE"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">ИНН:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_inn"><?= !empty($userInfo["UF_INN"]) ? $userInfo["UF_INN"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">КПП:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_kpp"><?= !empty($userInfo["UF_KPP"]) ? $userInfo["UF_KPP"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <br>
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Сайт:</div>
            <div
                class="req__value medium-8 small-7 columns"><?= !empty($userInfo["UF_SITE"]) ? '<a href="' . $userInfo["UF_SITE"] . '" class="req__link js-detail-uf_site" target="_blank">' . $userInfo["UF_SITE"] . '</a>' : "<span class='req__name'>—</span>"; ?>
            </div>
        </div>
    </div>
    <div class="columns req">
        <br>
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Расчетный счет:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_account"><?= !empty($userInfo["UF_ACCOUNT"]) ? $userInfo["UF_ACCOUNT"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Банк-получатель:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_bank_info"><?= !empty($userInfo["UF_BANK_INFO"]) ? $userInfo["UF_BANK_INFO"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">БИК:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_bik"><?= !empty($userInfo["UF_BIK"]) ? $userInfo["UF_BIK"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns">Корр. счет:</div>
            <div
                class="req__value medium-8 small-7 columns js-detail-uf_c_account"><?= !empty($userInfo["UF_C_ACCOUNT"]) ? $userInfo["UF_C_ACCOUNT"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
</div>
<a class="content__change" href="edit/">Изменить</a>