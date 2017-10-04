<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

global $USER;

// component text here
$arResult["FORM_FIELDS"] = Array(
    "MISTAKE" => Array(
        "TYPE" => "TEXTAREA",
        "VALUE" => "",
        "NO_LABEL" => "Y",
        "REQUIRED" => "Y",
        "INPUT_CLASS" => "i-form__textarea_large"
    ),
    "COMMENT" => Array(
        "TYPE" => "TEXTAREA",
        "TITLE" => "Опишите проблему",
        "REQUIRED" => "Y"
    ),
    "EMAIL" => Array(
        "TYPE" => "EMAIL",
        "TITLE" => "Email для связи",
        "REQUIRED" => "Y",
        "VALUE" => is_object($USER) && $USER->IsAuthorized() ? $USER->GetEmail() : ""
    ),
    Array(
        "TYPE" => "TEXT_BLOCK",
        "TITLE" => "Код с картинки",
        "REQUIRED" => "Y",
        "LIST" => Array(
            Array(
                "TYPE" => "STATIC",
                "NO_LABEL" => "Y",
                "TEXT" => '
                    <input name="captcha_code" value="" type="hidden" id="captcha_code">
                    <img src="" id="captcha_img" class="captcha_img">
                    <span class="captcha_reload" id="captcha_reload"></span>
                    ',
                "IN_TEXT_BLOCK" => "Y",
                "COL_SIZE" => 6
            ),
            "captcha_word" => Array(
                "TYPE" => "TEXT",
                "NO_LABEL" => "Y",
                "REQUIRED" => "Y",
                "IN_TEXT_BLOCK" => "Y",
                "COL_SIZE" => 6,
                "PLACEHOLDER" => "Введите код с картинки"
            )
        )
    )
);

// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult;