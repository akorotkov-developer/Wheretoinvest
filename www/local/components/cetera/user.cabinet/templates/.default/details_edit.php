<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle("Редактировать реквизиты организации");
$prevPage = preg_replace("#/edit/.*?$#is", "/", $APPLICATION->GetCurPage());
$APPLICATION->AddChainItem("Реквизиты организации", $prevPage);
$APPLICATION->AddChainItem("Редактировать реквизиты организации");

$userInfo = getContainer("User");

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
        "TYPE" => "IMAGE",
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

<form action="" class="b-form x-save-form" enctype="multipart/form-data">
    <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
    <input type="hidden" name="ajax" value="Y">
    <input type="hidden" name="action" value="editDetails">

    <div class="b-main-block__body"></div>

    <?= getFormFields($arResult["FORM_FIELDS"], 4, "b-form__row_small-margin"); ?>

    <div class="row">
        <div class="column small-12">
            <button type="submit" class="content__submit">Сохранить</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        $(".x-save-form").on("submit", function () {
            var data = new FormData($(this)[0]),
                _this = $(this);
            _this.find(".b-form__error").detach();
            _this.find(".alert-box").detach();

            $.ajax({
                url: "/local/ajax/editProfile.php",
                data: data,
                method: "POST",
                dataType: "json",
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success: function (response) {
                    if (response.ERRORS !== undefined) {
                        $.each(response.ERRORS, function (i, item) {
                            _this.find('input[name="' + i + '"]').after('<div class="b-form__error">' + item + '</div>');
                        });
                    }
                    if (response.ERROR !== undefined) {
                        _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box alert radius">' + response.ERROR + '<a href="#" class="close">&times;</a></div>');
                        $(document).foundation('alert', 'reflow');
                    }
                    if (response.SUCCESS !== undefined) {
                        var prevPage = "<?=$prevPage?>";
                        if (prevPage !== "")
                            window.location.href = prevPage;
                        else {
                            _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box success radius">' + response.SUCCESS + '<a href="#" class="close">&times;</a></div>');
                            $(document).foundation('alert', 'reflow');
                        }
                    }

                    var alert = $("[data-alert]:visible");
                    if (alert.length) {
                        $('html, body').animate({
                            scrollTop: alert.eq(0).offset().top - 80
                        }, 500);
                    }
                }
            });
            return false;
        });
    });
</script>
