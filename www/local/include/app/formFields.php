<?

//Вывод формы
function getFormFields($data, $col = "", $row_class = "", $form_name = "FORM", $fromBlock = false, $depth = 1)
{
    global $APPLICATION;
    global $USER;

    $return = "";
    $vertical = defined("VERTICAL_FORMS") && VERTICAL_FORMS === true ? true : false;
    if (count($data)) {
        ob_start(); ?>

        <? foreach ($data as $arKey => $arItem): ?>
            <? $itemDepth = $depth; ?>
            <? if (empty($arItem["TYPE"])) continue; ?>
            <? if ($arItem["TYPE"] === "HIDDEN"): ?>
                <input type="hidden"
                       value="<?= htmlentities($arItem["VALUE"]) ?>"
                       name="<?= $arKey ?>"
                       id="FIELD_<?= $arKey ?>" <?
                       if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                       ?>

                       <? if (count($arItem["PARAMS"])):
                       foreach ($arItem["PARAMS"] as $k => $v): ?>
                       <?= $k ?>="<?= htmlentities($v) ?>"
                    <?endforeach;
                    endif; ?>/>
                <? continue; ?>
            <? endif; ?>
            <?
            $lcol = $col;
            $lcol = $col;
            if (empty($lcol))
                $lcol = 5;
            else
                $lcol = intval($lcol);

            if (intval($arItem["L_COL_SIZE"]) > 0 && intval($arItem["L_COL_SIZE"]) <= 12)
                $lcol = intval($arItem["L_COL_SIZE"]);
            ?>
            <?
            $arItem["ROW_CLASS"] .= " " . $row_class;
            ?>

            <?
            if ($vertical) {
                $arItem["NO_LABEL"] = "Y";
                if (empty($arItem["BLOCK_TITLE"]))
                    $arItem["BLOCK_TITLE"] = $arItem["TITLE"];
                $arItem["TITLE"] = "";
            }
            ?>

            <? if (!$fromBlock): ?>
                <div class="b-form__row<? if (!empty($arItem["ROW_CLASS"])): ?> <?= $arItem["ROW_CLASS"]; ?><? endif; ?><? if (!empty($arItem["DISABLED"])): ?> x-row-disabled<? endif; ?>"<? if ($arItem["HIDE"] === "Y"): ?> style="display: none;"<? endif; ?>>
                <div class="row">
            <? endif; ?>
            <?
            switch ($arItem["TYPE"]) {
                default: ?>
                    <? if (empty($arItem["NO_LABEL"])): ?>
                        <div class="column small-<?= $lcol ?>">
                            <div
                                class="b-form__title<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                <span
                                    class="b-form__title-text"><? if (!empty($arItem["TITLE"])): ?><?= $arItem["TITLE"] ?><? elseif (empty($arItem["LABEL_DESCRIPTION"]) && empty($arItem["LABEL_LINK"]) && empty($arItem["LABEL_LINK_MODAL"])): ?>&nbsp;<? endif; ?></span><? if ($arItem["REQUIRED"] && !empty($arItem["TITLE"])): ?>
                                    <sup
                                        class="b-form__required">*</sup><? endif; ?>
                                <? if (!empty($arItem["LABEL_DESCRIPTION"])): ?>
                                    <div class="b-form__title-desc">
                                        <?= is_array($arItem["LABEL_DESCRIPTION"]) ? implode("<br/>", $arItem["LABEL_DESCRIPTION"]) : $arItem["LABEL_DESCRIPTION"]; ?>
                                    </div>
                                <? endif; ?>
                                <? if (!empty($arItem["LABEL_LINK"])): ?>
                                    <? if (!empty($arItem["TITLE"])): ?>
                                        <br>
                                    <? endif; ?>
                                    <?= is_array($arItem["LABEL_LINK"]) ? implode("<br/>", $arItem["LABEL_LINK"]) : $arItem["LABEL_LINK"]; ?>
                                <? endif; ?>
                                <? if (!empty($arItem["LABEL_LINK_MODAL"])): ?>
                                    <? foreach ($arItem["LABEL_LINK_MODAL"] as $linkKey => $linkVal): ?>
                                        <? if (!empty($arItem["MODAL_BLOCK"][$linkKey])): ?>
                                        <? if (!empty($arItem["TITLE"])): ?>
                                    <br>
                                    <? endif; ?>
                                        <a href="#" data-reveal-id="<?= $linkKey ?>_<?= $arKey ?>"><?= $linkVal; ?></a>
                                        <div id="<?= $linkKey ?>_<?= $arKey ?>" class="reveal-modal b-gallery__reveal"
                                             data-reveal aria-labelledby="modalTitle"
                                             aria-hidden="true"
                                             role="dialog">
                                            <div class="row">
                                                <div class="column small-12">
                                                    <div class="b-main-block">
                                                        <div class="b-main-block__head clearfix">
                                                            <div class="b-main-block__title"><?= $linkVal ?></div>
                                                            <a href="#" class="b-gallery__close close-reveal-modal"
                                                               aria-label="Close"></a>
                                                        </div>
                                                        <div
                                                            class="b-main-block__body b-main-block__body_padding b-main-block__body_small-top-padding">
                                                            <a class="b-navigation_printpage right" href="#"
                                                               data-inline="modal_block_<?= $arKey ?>_<?= $linkKey ?>">Распечатать</a>

                                                            <div class="clearfix"></div>
                                                            <br>

                                                            <div class="modal_block_<?= $arKey ?>_<?= $linkKey ?>">
                                                                <?= $arItem["MODAL_BLOCK"][$linkKey]; ?>
                                                            </div>
                                                            <br>
                                                            <a class="b-navigation_printpage right" href="#"
                                                               data-inline="modal_block_<?= $arKey ?>_<?= $linkKey ?>">Распечатать</a>

                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <? endif; ?>
                                    <? if (in_array($linkKey, $arItem["SHOW_MODAL_ON_START"])): ?>
                                        <script>
                                            $(function () {
                                                $("#<?=$linkKey?>_<?=$arKey?>").foundation("reveal", "open");
                                            });
                                        </script>
                                    <? endif; ?>
                                    <? endforeach; ?>
                                <? endif; ?>
                            </div>
                        </div>
                    <? else: ?>
                        <? $lcol = 0; ?>
                    <? endif; ?>
                    <? break;
            }

            $rcol = 12 - $lcol;
            if (empty($rcol))
                $rcol = 12;

            if (!empty($arItem["COL_SIZE"])) {
                if (12 - $lcol - intval($arItem["COL_SIZE"]) >= 0 || $lcol === 12) {
                    $rcol = intval($arItem["COL_SIZE"]);
                }
            }

            $inputSize = !empty($arItem["INPUT_SIZE"]) && intval($arItem["INPUT_SIZE"]) > 0 && intval($arItem["INPUT_SIZE"]) < 13 ? intval($arItem["INPUT_SIZE"]) : 12;

            switch ($arItem["TYPE"]) {
                //Вывод input text
            case "TEXT":
                ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title <? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <input
                                class="b-form__input<? if (!empty($arItem["INPUT_CLASS"])): ?> <?= $arItem["INPUT_CLASS"] ?><? endif; ?>"
                                type="text"
                                value="<?= addslashes($arItem["VALUE"]) ?>"
                                name="<?= $arKey ?>"
                                id="FIELD_<?= $arKey ?>" <?
                                if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                ?><?
                            if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                <?endif;
                                ?><?
                                if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                ?><?
                            if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                            ?>

                                <? if (count($arItem["PARAMS"])):
                                foreach ($arItem["PARAMS"] as $k => $v): ?>
                                <?= $k ?>="<?= htmlentities($v) ?>"
                                <?endforeach;
                                endif; ?>/>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "TEXTAREA":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                                        <textarea
                                            class="b-form__textarea<? if (!empty($arItem["INPUT_CLASS"])): ?> <?= $arItem["INPUT_CLASS"] ?><? endif; ?>"
                                            name="<?= $arKey ?>"
                                            id="FIELD_<?= $arKey ?>" <?
                                            if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                            ?><?
                                        if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                            <?endif;
                                            ?><?
                                            if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                            ?><?
                                        if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                                        ?>

                                            <? if (count($arItem["PARAMS"])):
                                            foreach ($arItem["PARAMS"] as $k => $v): ?>
                                            <?= $k ?>="<?= htmlentities($v) ?>"
                                            <?endforeach;
                                            endif; ?>><?= $arItem["VALUE"] ?></textarea>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "TEXT_BLOCK":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <? foreach ($arItem["LIST"] as $arSubKey => $arSubItem): ?>
                            <? if (!empty($arItem["DISABLED"])) $arSubItem["DISABLED"] = "Y"; ?>
                            <?= getFormFields(Array($arSubKey => $arSubItem), $col, $row_class, $form_name, true, ++$itemDepth); ?>
                        <? endforeach; ?>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "EMAIL":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <input
                                class="b-form__input<? if (!empty($arItem["INPUT_CLASS"])): ?> <?= $arItem["INPUT_CLASS"] ?><? endif; ?>"
                                type="email"
                                value="<?= htmlentities($arItem["VALUE"]) ?>"
                                name="<?= $arKey ?>"
                                id="FIELD_<?= $arKey ?>" <?
                                if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                ?><?
                            if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                <?endif;
                                ?><?
                                if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                ?><?
                            if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                            ?>

                                <? if (count($arItem["PARAMS"])):
                                foreach ($arItem["PARAMS"] as $k => $v): ?>
                                <?= $k ?>="<?= htmlentities($v) ?>"
                                <?endforeach;
                                endif; ?>/>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "PASSWORD":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <div class="b-form__input-password-wrapper">
                                <input
                                    class="b-form__input<? if (!empty($arItem["INPUT_CLASS"])): ?> <?= $arItem["INPUT_CLASS"] ?><? endif; ?>"
                                    type="password"
                                    value="<?= htmlentities($arItem["VALUE"]) ?>"
                                    name="<?= $arKey ?>"
                                    id="FIELD_<?= $arKey ?>" <?
                                    if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                    ?><?
                                if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                    <?endif;
                                    ?><?
                                    if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                    ?><?
                                if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                                ?>

                                    <? if (count($arItem["PARAMS"])):
                                    foreach ($arItem["PARAMS"] as $k => $v): ?>
                                    <?= $k ?>="<?= htmlentities($v) ?>"
                                    <?endforeach;
                                    endif; ?>/>
                            </div>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "RADIO":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <input type="hidden" name="<?= $arKey ?>" value="">
                            <? if (count($arItem["LIST"])): ?>
                                <? foreach ($arItem["LIST"] as $key => $val): ?>
                                    <? if ($arItem["LIST_VIEW"] === "BLOCK"): ?>
                                        <div>
                                    <? endif; ?>
                                    <input type="radio"
                                           <? if (!empty($arItem["INPUT_CLASS"])): ?>class="<?= $arItem["INPUT_CLASS"] ?>"<? endif; ?>
                                           name="<?= $arKey ?>"
                                           value="<?= $key; ?>"<? if ((!is_array($arItem["VALUE"]) && (string)$arItem["VALUE"] === (string)$key) || (is_array($arItem["VALUE"]) && in_array($key, $arItem["VALUE"]))): ?> checked<? endif; ?><?
                                    if (!empty($arItem["REQUIRED"])): ?> required<?endif;
                                    ?><?
                                    if (!empty($arItem["DISABLED"])): ?> disabled <?endif;
                                    ?>
                                           <? if (count($arItem["PARAMS"])):
                                           foreach ($arItem["PARAMS"] as $k => $v): ?>
                                           <?= $k ?>="<?= htmlentities($v) ?>"
                                    <?endforeach;
                                    endif; ?>
                                           class="check__checkbox"
                                           id="<?= $arKey ?>_<?= $key; ?>">
                                    <label
                                        class="check__chck b-form__label <? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>"
                                        for="<?= $arKey ?>_<?= $key; ?>">
                                        <?= $val; ?>
                                    </label>
                                    <? if ($arItem["LIST_VIEW"] === "BLOCK"): ?>
                                        </div>
                                    <? endif; ?>
                                <? endforeach; ?>
                            <? endif; ?>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "CHECKBOX":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <? if (empty($arItem["NO_SKIP"])): ?><input type="hidden" name="<?= $arKey ?>"
                                                                        value=""><? endif; ?>
                            <? if (count($arItem["LIST"])): ?>
                                <? foreach ($arItem["LIST"] as $key => $val): ?>
                                    <? if ($arItem["LIST_VIEW"] === "BLOCK"): ?>
                                        <div>
                                    <? endif; ?>
                                    <input type="checkbox"
                                           <? if (!empty($arItem["INPUT_CLASS"])): ?>class="<?= $arItem["INPUT_CLASS"] ?>"<? endif; ?>
                                           name="<?= $arKey ?><? if (empty($arItem["SINGLE"])): ?>[]<? endif; ?>"
                                           value="<?= $key; ?>"<? if ((!is_array($arItem["VALUE"]) && (string)$arItem["VALUE"] === (string)$key) || (is_array($arItem["VALUE"]) && in_array($key, $arItem["VALUE"]))): ?> checked<? endif; ?><?
                                    if (!empty($arItem["DISABLED"])): ?> disabled <?endif;
                                    ?>
                                           <? if (count($arItem["PARAMS"])):
                                           foreach ($arItem["PARAMS"] as $k => $v): ?>
                                           <?= $k ?>="<?= htmlentities($v) ?>"
                                    <?endforeach;
                                    endif; ?><? if (count($arItem["LIST"]) === 1): ?>
                                        id="FIELD_<?= $arKey; ?>"
                                    <? endif; ?>
                                           class="modal__checkbox">
                                    <label
                                        class="modal__chck <? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>"
                                        for="FIELD_<?= $arKey; ?>">
                                        <?= $val; ?>
                                    </label>
                                    <? if ($arItem["LIST_VIEW"] === "BLOCK"): ?>
                                        </div>
                                    <? endif; ?>
                                <? endforeach; ?>
                            <? endif; ?>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "SELECT":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <? if (empty($arItem["PLACEHOLDER"])) $arItem["PLACEHOLDER"] = "Выбрать из списка"; ?>
                            <? $first = true; ?>
                            <? if (!empty($arItem["MORE_BTN"])): ?>
                                <div class="x-more-btn-container">
                                    <?
                                    if (!is_array($arItem["VALUE"]))
                                        $arItem["VALUE"] = Array($arItem["VALUE"]);
                                    ?>
                                    <? foreach ($arItem["VALUE"] as $listKey => $listVal): ?>
                                        <?
                                        $inputClass = $arItem["INPUT_CLASS"];
                                        if ($first) {
                                            $inputClass .= " x-more-btn-field";
                                        }
                                        $hasVal = false;
                                        ?>
                                        <? if (!$first): ?>
                                            <div class="x-more-btn-wrap">
                                            <a href="#"
                                               class="b-yellow b-button_remove has-tip tip-top x-more-btn-remove"
                                               data-tooltip title="Удалить"></a>
                                        <? endif; ?>
                                        <select
                                            name="<?= $arKey ?><? if (!empty($arItem["MULTIPLE"]) || !empty($arItem["MORE_BTN"])): ?>[]<? endif; ?>"
                                            <? if (!empty($inputClass)): ?>class="<?= $inputClass ?>"<? endif; ?>
                                            id="FIELD_<?= $arKey ?>"
                                            <? if (count($arItem["LIST_DESC"])): ?> data-desc=""<? endif; ?> <?
                                            if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                            ?><?
                                        if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                                            ?><?
                                        if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                            <?endif;
                                            ?><?
                                            if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                            ?><? if (!empty($arItem["MULTIPLE"])): ?>multiple <? endif; ?>
                                            <? if (count($arItem["PARAMS"])):
                                            foreach ($arItem["PARAMS"] as $k => $v): ?>
                                            <?= $k ?>="<?= htmlentities($v) ?>"
                                        <?endforeach;
                                        endif; ?>>
                                            <? if (empty($arItem["REQUIRED"])): ?>
                                                <option value="" data-desc=""></option>
                                            <? endif; ?>
                                            <? foreach ($arItem["LIST"] as $key => $val): ?>
                                                <option
                                                    value="<?= $key ?>"
                                                    <? if (!empty($arItem["LIST_DESC"][$key])): ?>
                                                        data-desc="<?= $arItem["LIST_DESC"][$key] ?>"
                                                    <? endif; ?>
                                                    <? if (is_array($arItem["VALUE"]) && in_array($key, $arItem["VALUE"]) && !$hasVal): ?> selected<?
                                                        $hasVal = true;
                                                        foreach ($arItem["VALUE"] as $vKey => $vVal):
                                                            if ($key == $vVal):
                                                                unset($arItem["VALUE"][$vKey]);
                                                                break;
                                                            endif;
                                                        endforeach;
                                                    endif; ?>><?= $val; ?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <? if (!$first): ?>
                                            </div>
                                        <? endif; ?>
                                        <? if ($first)
                                            $first = false; ?>
                                    <? endforeach; ?>
                                </div>
                            <? else: ?>
                                <select
                                    name="<?= $arKey ?><? if (!empty($arItem["MULTIPLE"])): ?>[]<? endif; ?>"
                                    <? if (!empty($arItem["INPUT_CLASS"])): ?>class="<?= $arItem["INPUT_CLASS"] ?>"<? endif; ?>
                                    id="FIELD_<?= $arKey ?>"
                                    <? if (count($arItem["LIST_DESC"])): ?> data-desc=""<? endif; ?> <?
                                    if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                    ?><?
                                if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                                    ?><?
                                if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                    <?endif;
                                    ?><?
                                    if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                    ?><? if (!empty($arItem["MULTIPLE"])): ?>multiple <? endif; ?>
                                    <? if (count($arItem["PARAMS"])):
                                    foreach ($arItem["PARAMS"] as $k => $v): ?>
                                    <?= $k ?>="<?= htmlentities($v) ?>"
                                <?endforeach;
                                endif; ?>>
                                    <? if (empty($arItem["REQUIRED"])): ?>
                                        <option value="" data-desc=""></option>
                                    <? endif; ?>
                                    <? foreach ($arItem["LIST"] as $key => $val): ?>
                                        <option
                                            value="<?= $key ?>"
                                            <? if (!empty($arItem["LIST_DESC"][$key])): ?>
                                                data-desc="<?= $arItem["LIST_DESC"][$key] ?>"
                                            <? endif; ?><? if ((!is_array($arItem["VALUE"]) && (string)$arItem["VALUE"] === (string)$key) || (is_array($arItem["VALUE"]) && in_array($key, $arItem["VALUE"]))): ?> selected<? endif; ?>><?= $val; ?></option>
                                    <? endforeach; ?>
                                </select>
                            <? endif; ?>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                            <? if (!empty($arItem["MORE_BTN"])): ?>
                                <div class="row">
                                    <div class="column small-4">
                                        <a href="#" class="b-btn x-more-btn">Добавить</a>
                                    </div>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "STATIC":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title b-form__title_margin-bottom b-form__title_no-help<? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12">
                            <?= $arItem["TEXT"]; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "DATE":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title <? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <input
                                class="b-form__input<? if (!empty($arItem["INPUT_CLASS"])): ?> <?= $arItem["INPUT_CLASS"] ?><? endif; ?>"
                                type="date"
                                value="<?= htmlentities($arItem["VALUE"]) ?>"
                                name="<?= $arKey ?>"
                                id="FIELD_<?= $arKey ?>" <?
                                if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                ?><?
                            if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                <?endif;
                                ?><?
                                if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                ?><?
                            if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                            ?>

                                <? if (count($arItem["PARAMS"])):
                                foreach ($arItem["PARAMS"] as $k => $v): ?>
                                <?= $k ?>="<?= htmlentities($v) ?>"
                                <?endforeach;
                                endif; ?>/>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?
            break;
            case "IMAGE":
            ?>
                <div
                    class="column small-12 medium-<?= $rcol ?><? if (empty($arItem["IN_TEXT_BLOCK"])): ?> end<? endif; ?><? if (!empty($arItem["COL_CLASS"])): ?> <?= $arItem["COL_CLASS"] ?><? endif; ?>">
                    <div class="row">
                        <? if (!empty($arItem["BLOCK_TITLE"])): ?>
                            <div class="column small-12">
                                <div
                                    class="b-form__title <? if (!empty($arItem["LABEL_CLASS"])): ?> <?= $arItem["LABEL_CLASS"] ?><? endif; ?>">
                                    <span
                                        class="b-form__title-text"><?= $arItem["BLOCK_TITLE"] ?></span><? if ($arItem["REQUIRED"] && empty($arItem["TITLE"])): ?>
                                        <sup
                                            class="b-form__required">*</sup><? endif; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <div class="column small-12 medium-<?= $inputSize; ?> end">
                            <div class="content__logo">
                                <? if (!empty($arItem["VALUE"])): ?>
                                    <img src="<?= CFile::GetPath($arItem["VALUE"]) ?>"
                                         alt="<?= $arItem["BLOCK_TITLE"] ?>" class="content__logo-img"/>
                                    <?
                                else:?>
                                    <img src=""
                                         alt="<?= $arItem["BLOCK_TITLE"] ?>"
                                         class="content__logo-img content__logo-img_hide"/>
                                <? endif; ?>
                            </div>
                            <div class="fileform">
                                <label class="fileform__sel"
                                       for="FIELD_<?= $arKey ?>">Загрузить <?= $arItem["BLOCK_TITLE"] ?></label>
                                <input
                                    class="fileform__file <? if (!empty($arItem["INPUT_CLASS"])): ?> <?= $arItem["INPUT_CLASS"] ?><? endif; ?>"
                                    type="file"
                                    value="<?= htmlentities($arItem["VALUE"]) ?>"
                                    name="<?= $arKey ?>"
                                    id="FIELD_<?= $arKey ?>" <?
                                    if (!empty($arItem["REQUIRED"])): ?>required <?endif;
                                    ?><?
                                if (!empty($arItem["PLACEHOLDER"])): ?>placeholder="<?= $arItem["PLACEHOLDER"] ?>"
                                    <?endif;
                                    ?><?
                                    if (!empty($arItem["READONLY"])): ?>readonly <?endif;
                                    ?><?
                                if (!empty($arItem["DISABLED"])): ?>disabled <?endif;
                                ?>

                                    <? if (count($arItem["PARAMS"])):
                                    foreach ($arItem["PARAMS"] as $k => $v): ?>
                                    <?= $k ?>="<?= htmlentities($v) ?>"
                                    <?endforeach;
                                    endif; ?>/>
                                <? if (!empty($arItem["BUTTON_DESCRIPTION"])): ?>
                                    <div class="b-form__title-desc">
                                        <?= is_array($arItem["BUTTON_DESCRIPTION"]) ? implode("<br/>", $arItem["BUTTON_DESCRIPTION"]) : $arItem["BUTTON_DESCRIPTION"]; ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                        <div class="column small-12">
                            <? if (!empty($arItem["ERROR"])): ?>
                                <div
                                    class="b-form__error"><?= is_array($arItem["ERROR"]) ? implode("<br>", $arItem["ERROR"]) : $arItem["ERROR"]; ?></div>
                            <? endif; ?>
                            <? if (!empty($arItem["DESCRIPTION"])): ?>
                                <div class="b-form__title-desc">
                                    <?= is_array($arItem["DESCRIPTION"]) ? implode("<br/>", $arItem["DESCRIPTION"]) : $arItem["DESCRIPTION"]; ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function () {
                        window.URL = window.URL || window.webkitURL;
                        var elBrowse = $("#FIELD_<?= $arKey ?>"),
                            elPreview = elBrowse.closest(".column").find(".content__logo"),
                            useBlob = false && window.URL; // `true` to use Blob instead of Data-URL

                        function readImage(file) {
                            var reader = new FileReader();
                            reader.addEventListener("load", function () {
                                var image = new Image();
                                image.addEventListener("load", function () {
                                    $(this).addClass("content__logo-img");
                                    elPreview.html(this);
                                });
                                image.src = useBlob ? window.URL.createObjectURL(file) : reader.result;
                                if (useBlob) {
                                    window.URL.revokeObjectURL(file); // Free memory
                                }
                            });
                            reader.readAsDataURL(file);
                        }

                        elBrowse.on("change", function () {
                            var files = this.files;
                            var errors = "";
                            if (!files) {
                                errors += "File upload not supported by your browser.";
                            }
                            if (files && files[0]) {
                                for (var i = 0; i < files.length; i++) {
                                    var file = files[i];
                                    if ((/\.(png|jpeg|jpg|gif)$/i).test(file.name)) {
                                        readImage(file);
                                    } else {
                                        errors += file.name + " Unsupported Image extension\n";
                                    }
                                }
                            }
                            if (errors) {
                                alert(errors);
                            }
                        });
                    })
                </script>
                <?
                break;
            }
            ?>
            <? if (!$fromBlock): ?>
                </div>
                </div>
            <? endif; ?>
        <? endforeach; ?>
        <? $return .= ob_get_contents();
        ob_end_clean();
    }
    return $return;
}

?>