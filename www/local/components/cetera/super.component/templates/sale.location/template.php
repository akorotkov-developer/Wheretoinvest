<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<span class="b-header__firstline-iconreg"></span><!-- не убирать этот коммент
            --><span data-reveal-id="<?= $arParams["MODAL_ID"] ?>"
                     class="b-header__firstline-linkreg b-header__firstline-linkreg_js"><?= !empty($arResult["CURRENT_LOC_NAME"]) ? $arResult["CURRENT_LOC_NAME"] : $arParams["EMPTY_NAME"] ?></span>

<div id="<?= $arParams["MODAL_ID"] ?>" class="reveal-modal modal modal_js-top" data-reveal aria-labelledby="modalTitle"
     aria-hidden="true" role="dialog">
    <div class="column small-12">
        <h3>Выберите регион:</h3>
        <?
        $i = 0;
        $nextCol = false;
        $rowCount = 1;
        ?>
        <ul class="small-block-grid-1 medium-block-grid-<?= $arParams["COL_CNT"] ?>">
            <li>
                <? foreach ($arResult["REGIONS"] as $char => $list): ?>
                <? if ($nextCol): ?>
                <?
                $nextCol = false;
                $rowCount++;
                ?>
            </li>
            <li>
                <? endif; ?>
                <div class="modal__title"><?= $char ?></div>
                <? foreach ($list as $value): ?>
                    <div class="modal__wrp">
                        <input type="checkbox" name="address" id="address_<?= $value["ID"] ?>" class="modal__checkbox"
                               value="<?= $value["ID"] ?>" <? if ($value["ID"] == $arResult["CURRENT_LOC_ID"]): ?> disabled checked<? endif; ?>>
                        <label for="address_<?= $value["ID"] ?>" class="modal__chck"><?= $value["NAME"] ?></label>
                    </div>
                    <? if ($i !== 0 && $i % (ceil($arResult["TOTAL_COUNT"] / $arParams["COL_CNT"]) - 2) == 0 && $rowCount < $arParams["COL_CNT"]) $nextCol = true; ?>
                    <? ++$i; ?>
                <? endforeach; ?>
                <? endforeach; ?>
            </li>
        </ul>
    </div>
    <a class="close-reveal-modal modal__close" aria-label="Close">&#215;</a>
</div>
<script type="text/javascript">
    $(function () {
        (function () {
            $('#<?=$arParams["MODAL_ID"]?> label').unbind().on("click", function () {
                $('#<?=$arParams["MODAL_ID"]?> label').each(function () {
                    $(this).prev('input')
                        .prop("disabled", false)
                        .prop("checked", false);
                });

                var name = $(this).text(),
                    input = $(this).prev('input'),
                    id = input.val();

                $.ajax({
                    url: "<?=$templateFolder?>/ajax.php",
                    data: {
                        id: id,
                        name: name
                    },
                    method: "post",
                    success: function () {
                        window.location.href = window.location.href;
                    }
                });

                $('.b-header__firstline-linkreg_js').text(name);
                input.prop('disabled', true).prop("checked", true);
                $('#<?=$arParams["MODAL_ID"]?>').foundation('reveal', 'close');
            });

            <? if(empty($arResult["CURRENT_LOC_ID"]) && empty($_SESSION["CURRENT_LOC_MODAL"])): ?>
            $('#<?=$arParams["MODAL_ID"]?>').foundation('reveal', 'open');
            <?$_SESSION["CURRENT_LOC_MODAL"] = "Y";?>
            <? endif; ?>
        })();
    });
</script>