<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//***********************************
//setting section
//***********************************
?>
<form action="<?= $arResult["FORM_ACTION"] ?>" method="post">
    <div class="row">
        <div class="column small-12 medium-12 large-12">
            <span class="subscribe__title"><? echo GetMessage("subscr_title_settings") ?></span>

            <div class="subscribe">
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <? echo bitrix_sessid_post(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="column small-9 medium-9 large-7">
                        <div class="row">
                            <div class="column small-12">
                                <div class="b-form__title b-form__title_margin-bottom subscribe__title-fields">
                                    <span class="b-form__title-text"><? echo GetMessage("subscr_email") ?></span>
                                    <sup class="b-form__required">*</sup>
                                </div>
                            </div>
                            <div class="column small-12 medium-12 end">
                                <input class="b-form__input" type="email" name="EMAIL" maxlength="255" required=""
                                       value="<?= $arResult["SUBSCRIPTION"]["EMAIL"] != "" ? $arResult["SUBSCRIPTION"]["EMAIL"] : $arResult["REQUEST"]["EMAIL"]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="column small-3 medium-3 large-5">
                        <div class="row">
                            <div class="column small-12">
                                <div class="b-form__title b-form__title_margin-bottom">
                                    <br/>
                                </div>
                            </div>
                            <div class="column small-12 medium-12 end">
                                <span data-tooltip aria-haspopup="true"
                                      class="has-tip subscribe__hint subscribe__hint-mt"
                                      title="<? echo GetMessage("subscr_settings_note1") ?><br><? echo GetMessage("subscr_settings_note2") ?>"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="b-form__row"></div>
                <div class="row">
                    <div class="column small-9 medium-9 large-7">
                        <div class="row">
                            <div class="column small-12">
                                <div class="b-form__title b-form__title_margin-bottom subscribe__title-fields">
                                    <span class="b-form__title-text"><? echo GetMessage("subscr_rub") ?></span>
                                    <sup class="b-form__required">*</sup>
                                </div>
                            </div>
                            <div class="column small-12 medium-12 end">
                                <? foreach ($arResult["RUBRICS"] as $itemID => $itemValue): ?>
                                    <div class="subscribe__wrp">
                                        <input type="checkbox" name="RUB_ID[]" id="RUB_ID_<?= $itemValue["ID"] ?>"
                                               class="subscribe__checkbox"
                                               value="<?= $itemValue["ID"] ?>" <? if ($itemValue["CHECKED"]) echo " checked" ?> />
                                        <label for="RUB_ID_<?= $itemValue["ID"] ?>"
                                               class="subscribe__chck"><?= $itemValue["NAME"] ?></label>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="b-form__row"></div>

                <div class="row">
                    <div class="column small-9 medium-9 large-7">
                        <div class="row">
                            <div class="column small-12">
                                <div class="b-form__title b-form__title_margin-bottom subscribe__title-fields">
                                    <span class="b-form__title-text"><? echo GetMessage("subscr_fmt") ?></span>
                                </div>
                            </div>
                            <div class="column small-12 medium-12 end">
                                <div class="row">

                                    <div class="column small-6 medium-5 large-4">
                                        <input type="radio" class="subscribe__radio" name="FORMAT" id="FORMAT_text"
                                               value="text" <? if ($arResult["SUBSCRIPTION"]["FORMAT"] == "text") echo " checked" ?> />
                                        <label class="subscribe__rdo"
                                               for="FORMAT_text"><? echo GetMessage("subscr_text") ?> </label>
                                    </div>
                                    <div class="column small-6 medium-5 large-4 end">
                                        <input type="radio" class="subscribe__radio" id="FORMAT_html" name="FORMAT"
                                               value="html" <? if ($arResult["SUBSCRIPTION"]["FORMAT"] != "text") echo " checked" ?> />
                                        <label class="subscribe__rdo" for="FORMAT_html">HTML</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="b-form__row"></div>
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <button type="submit" name="Save"
                                class="content__submit"><? echo($arResult["ID"] > 0 ? GetMessage("subscr_upd") : GetMessage("subscr_add")) ?></button>
                    </div>
                </div>
                <input type="hidden" name="PostAction" value="<? echo($arResult["ID"] > 0 ? "Update" : "Add") ?>"/>
                <input type="hidden" name="ID" value="<? echo $arResult["SUBSCRIPTION"]["ID"]; ?>"/>
            </div>
        </div>
    </div>
</form>