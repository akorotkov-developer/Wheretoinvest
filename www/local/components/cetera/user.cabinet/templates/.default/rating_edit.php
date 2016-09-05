<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$prevPage = preg_replace("#/edit/.*?$#is", "/", $APPLICATION->GetCurPage());

$APPLICATION->SetTitle("Редактировать рейтинги");
$APPLICATION->AddChainItem("Рейтинги организации", $prevPage);
$APPLICATION->AddChainItem("Редактировать рейтинги");

$ratingList = Array();
$hblock = new \Cetera\HBlock\SimpleHblockObject(8);
$list = $hblock->getList();
while ($el = $list->fetch()) {
    $ratingList[$el["ID"]] = $el;
}

$rating = Array();
$hblock = new \Cetera\HBlock\SimpleHblockObject(7);
$list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
while ($el = $list->fetch()) {
    $rating[$el["UF_AGENCY"]] = $el;
}
?>
<form action="" method="post" enctype="multipart/form-data" class="b-form js-form">
    <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
    <input type="hidden" name="ajax" value="Y">
    <input type="hidden" name="action" value="changeRating">

    <div class="b-main-block__body"></div>
    <? $first = true; ?>
    <div class="js-container">
        <? $i = 1; ?>
        <? foreach ($ratingList as $key => $item): ?>
            <div class="row edit js-item">
                <div class="edit__first medium-3 small-4 columns">
                    <input type="hidden"
                           name="rating[<?= !empty($rating[$key]["ID"]) ? $rating[$key]["ID"] : "new" . $i ?>][agency]"
                           value="<?= $item["ID"] ?>">
                    <? if ($first): ?>
                        <label for="#" class="edit__label">
                            Агентство
                        </label>
                    <? endif; ?>
                    <div class="req__name i-req__name_middle"><?= $item["UF_NAME"] ?></div>
                </div>
                <div class="edit__second medium-5 small-8 columns end">
                    <? if ($first): ?>
                        <label for="#" class="edit__label">
                            Рейтинг
                        </label>
                    <? endif; ?>
                    <select
                        name="rating[<?= !empty($rating[$key]["ID"]) ? $rating[$key]["ID"] : "new" . $i ?>][rating]">
                        <option value="">Не выбрано</option>
                        <? foreach ($item["UF_SCALE"] as $scale): ?>
                            <option
                                value="<?= $scale["VALUE"] ?>"<? if ($scale["VALUE"] == $rating[$key]["UF_RATING"]): ?> selected<? endif; ?>><?= $scale["VALUE"] ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
            </div>
            <? $first = false; ?>
            <? ++$i; ?>
        <? endforeach; ?>
    </div>
    <div class="row">
        <div class="columns">
            <button type="submit" class="content__submit">Сохранить</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        $(".js-form").on("submit", function () {
            var data = $(this).serialize(),
                _this = $(this);
            _this.find(".b-form__error").detach();
            _this.find(".alert-box").detach();

            $.ajax({
                url: "/local/ajax/editRating.php",
                data: data,
                method: "POST",
                dataType: "json",
                success: function (response) {
                    if (response.ERROR !== undefined) {
                        _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box alert radius">' + response.ERROR + '<a href="#" class="close">&times;</a></div>');
                        $(document).foundation('alert', 'reflow');
                    }
                    else if (response.SUCCESS !== undefined) {
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