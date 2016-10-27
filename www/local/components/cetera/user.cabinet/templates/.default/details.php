<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle("Реквизиты");
$APPLICATION->AddChainItem("Реквизиты");

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
            <div class="req__name medium-5 small-5 columns">Логотип</div>
            <div class="req__value medium-7 small-7 columns js-detail-work_logo">
                <? if (!empty($userInfo["WORK_LOGO"])): ?>
                    <?
                    $file = \CFile::ResizeImageGet($userInfo["WORK_LOGO"], Array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL);
                    ?>
                    <img src="<?= $file["src"]; ?>" alt="<?= $userInfo["WORK_COMPANY"] ?>">
                <? else: ?>
                    <span class='req__name'>—</span>
                <? endif; ?>
            </div>
        </div>
    </div>
    <div class="columns req">
        <br>

        <div class="row">
            <div class="req__name medium-5 small-5 columns">Сокращенное наименование </br>(согласно Уставу)</div>
            <div class="req__value medium-7 small-7 columns js-detail-work_company"><?= !empty($userInfo["WORK_COMPANY"]) ? $userInfo["WORK_COMPANY"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-5 small-5 columns">Сокращенное наименование </br>на английском языке </br>(согласно Уставу)</div>
            <div class="req__value medium-7 small-7 columns js-detail-uf_short_work_en"><?= !empty($userInfo["UF_SHORT_WORK_EN"]) ? $userInfo["UF_SHORT_WORK_EN"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-5 small-5 columns">Наименование для отображения </br>на главной странице</div>
            <div class="req__value medium-7 small-7 columns js-detail-uf_full_work_name"><?= !empty($userInfo["UF_FULL_WORK_NAME"]) ? $userInfo["UF_FULL_WORK_NAME"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <br>

        <div class="row">
            <div class="req__name medium-5 small-5 columns">ОГРН</div>
            <div class="req__value medium-7 small-7 columns js-detail-uf_ogrn"><?= !empty($userInfo["UF_OGRN"]) ? $userInfo["UF_OGRN"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-5 small-5 columns">ИНН</div>
            <div class="req__value medium-7 small-7 columns js-detail-uf_inn"><?= !empty($userInfo["UF_INN"]) ? $userInfo["UF_INN"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns">
        <div class="row">
            <div class="req__name medium-5 small-5 columns">Лицензия ЦБ РФ</div>
            <div class="req__value medium-7 small-7 columns js-detail-uf_license"><?= !empty($userInfo["UF_LICENSE"]) ? $userInfo["UF_LICENSE"] : "<span class='req__name'>—</span>"; ?></div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-5 small-5 columns">&nbsp;</div>
            <div class="req__value medium-7 small-7 columns">
                <a class="content__change" href="edit/">Изменить</a>
            </div>
        </div>
    </div>
</div>
