<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

if ($arParams["USE_FILTER"] == "Y") {
    if (strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
        $arParams["FILTER_NAME"] = "arrFilter";
    }
} else {
    $arParams["FILTER_NAME"] = "";
}

$arDefaultUrlTemplates404 = array(
    "index" => "",
    "info" => "info/",
    "method" => "method/",
    "region" => "region/",
    "details" => "details/",
    "details_edit" => "details/edit/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
    "USER_ID",
    "ID",
    "CODE",
    "action",
);

if ($arParams["SEF_MODE"] == "Y") {
    $arVariables = array();

    $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

    $componentPage = CComponentEngine::ParseComponentPath(
        $arParams["SEF_FOLDER"],
        $arUrlTemplates,
        $arVariables
    );

    $b404 = false;
    if (!$componentPage) {
        $componentPage = "index";
    }

    if (!$USER->IsAuthorized())
        LocalRedirect("/");

    switch ($componentPage) {
        case "test":
            if (!CSite::InGroup(Array())) {
                $b404 = true;
            }
            break;
            break;
    }

    if ($b404) {
        LocalRedirect("/");
    }

    CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
    $arResult = array(
        "FOLDER" => $arParams["SEF_FOLDER"],
        "URL_TEMPLATES" => $arUrlTemplates,
        "VARIABLES" => $arVariables,
        "ALIASES" => $arVariableAliases,
    );
} else {
    $arVariables = array();

    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
    CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

    $componentPage = "index";

    switch ($_GET["action"]) {
        case "info":
            $componentPage = "info";
            break;
        case "method":
            $componentPage = "method";
            break;
        case "region":
            $componentPage = "region";
            break;
        case "details":
            $componentPage = "details";
            break;
        case "details_edit":
            $componentPage = "details_edit";
            break;
        default:
            $componentPage = "index";
    }

    $arResult = array(
        "FOLDER" => "",
        "URL_TEMPLATES" => Array(
            "section" => htmlspecialchars($APPLICATION->GetCurPage()) . "?" . $arVariableAliases["SECTION_ID"] . "=#SECTION_ID#",
            "element" => htmlspecialchars($APPLICATION->GetCurPage()) . "?" . $arVariableAliases["SECTION_ID"] . "=#SECTION_ID#" . "&" . $arVariableAliases["ELEMENT_ID"] . "=#ELEMENT_ID#",
        ),
        "VARIABLES" => $arVariables,
        "ALIASES" => $arVariableAliases,
    );
}

$this->IncludeComponentTemplate($componentPage);