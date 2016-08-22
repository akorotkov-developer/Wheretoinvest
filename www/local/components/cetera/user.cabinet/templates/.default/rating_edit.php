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
<div class="hide js-clone">
    <div class="row edit js-item">
        <div class="edit__first medium-4 small-12 columns">
            <label for="#" class="edit__label">
                Агентство
            </label>
            <input type="text" name="rating[#new][agency]" class="edit__input" value="" required>
        </div>
        <div class="edit__second medium-3 small-12 columns">
            <label for="#" class="edit__label">
                Рейтинг
            </label>
            <input type="text" name="rating[#new][rating]" class="edit__input" value="" required>
        </div>
        <div class="edit__third medium-4 small-10 columns">
            <label for="#" class="edit__label">
                Дата получения
            </label>
            <input type="date" name="rating[#new][date]" class="edit__input" value="" required>
        </div>
        <div class="edit__fourth medium-1 small-2 columns">
            <a href="#" class="edit__close edit__close_first js-delete" title="Удалить"></a>
        </div>
    </div>
</div>

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
                <div class="edit__first medium-4 small-12 columns">
                    <input type="hidden"
                           name="rating[<?= !empty($rating[$key]["ID"]) ? $rating[$key]["ID"] : "new" . $i ?>][agency]" value="<?=$item["ID"]?>">
                    <label for="#" class="edit__label<? if (!$first): ?> show-for-small-only<? endif; ?>">
                        Агентство
                    </label>
                    <input type="text" class="edit__input"
                           value="<?= $item["UF_NAME"] ?>" disabled readonly>
                </div>
                <div class="edit__second medium-3 small-12 columns">
                    <label for="#" class="edit__label<? if (!$first): ?> show-for-small-only<? endif; ?>">
                        Рейтинг
                    </label>
                    <select
                        name="rating[<?= !empty($rating[$key]["ID"]) ? $rating[$key]["ID"] : "new" . $i ?>][rating]">
                        <option value="">Не выбрано</option>
                        <? foreach ($item["UF_SCALE"] as $scale): ?>
                            <option
                                value="<?= $scale ?>"<? if ($scale == $rating[$key]["UF_RATING"]): ?> selected<? endif; ?>><?= $scale ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
                <div class="edit__third medium-4 small-10 columns">
                    <label for="#" class="edit__label<? if (!$first): ?> show-for-small-only<? endif; ?>">
                        Дата получения
                    </label>
                    <input type="date"
                           name="rating[<?= !empty($rating[$key]["ID"]) ? $rating[$key]["ID"] : "new" . $i ?>][date]"
                           class="edit__input"
                           value="<?= !empty($rating[$key]["UF_DATE"]) ? date("Y-m-d", strtotime($rating[$key]["UF_DATE"])) : "" ?>"
                    >
                </div>
                <div class="edit__fourth medium-1 small-2 columns">
                    <?/*?>
                    <a href="#" class="edit__close <? if ($first): ?>edit__close_first <? endif; ?>js-delete"
                       title="Удалить"></a>
                    <?*/?>
                </div>
            </div>
            <? $first = false; ?>
            <? ++$i; ?>
        <? endforeach; ?>
    </div>
    <div class="row">
        <div class="columns">
            <? /*?><button type="button" class="content__add js-add">добавить рейтинг</button><?*/ ?>
            <button type="submit" class="content__submit">Сохранить</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        <?/*?>
        $(".js-delete").on("click", function () {
            $(this).closest(".js-item").detach();
            return false;
        });

        $(".js-add").on("click", function () {
            var clone = $(".js-clone .js-item").clone(true),
                container = $(".js-container");

            clone.find("input").val("");
            if ($(".js-container .js-item").length) {
                clone.find(".edit__label").addClass("show-for-small-only");
                clone.find(".edit__close").removeClass("edit__close_first");
            }

            var cnt = $(".js-container .js-item").length || 1;
            clone.find("input").each(function () {
                $(this).attr("name", $(this).attr("name").replace("#new", "new" + cnt));
            });
            container.append(clone);

            $(".js-form").find("[type='submit']").show();
        });
        <?*/?>

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