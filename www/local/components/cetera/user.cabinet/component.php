<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

if (isset($_REQUEST['Login']) && $_REQUEST['Login'] == 'Y' && $APPLICATION->GetCurPage() != '/cabinet/')
    LocalRedirect("/cabinet/");

if ($arParams["USE_FILTER"] == "Y") {
    if (strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
        $arParams["FILTER_NAME"] = "arrFilter";
    }
} else {
    $arParams["FILTER_NAME"] = "";
}

$arDefaultUrlTemplates404 = array(
    "index" => "",
    "method" => "method/",
    "region" => "region/",
    "details" => "details/",
    "details_edit" => "details/edit/",
    "gov" => "gov/",
    "rating" => "rating/",
    "rating_edit" => "rating/edit/",
    "assets" => "assets/",
    "offers" => "offers/#type#/",
    "offers_edit" => "offers/#type#/edit/#ID#/",
    "offers_add" => "offers/#type#/add/",
    "subscribe" => "subscribe/",
    "contract_offer" => "contract_offer/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
    "USER_ID",
    "ID",
    "CODE",
    "action",
    "type",
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
    if (empty($componentPage)) {
        $componentPage = "index";
        $b404 = true;
    }

    if (!$USER->IsAuthorized())
        LocalRedirect("/");

    switch ($componentPage) {
        case "method":
        case "region":
        case "subscribe":
            if (getContainer('User')->isPartner()) {
                $b404 = true;
            }
            break;
        case "details":
        case "details_edit":
        case "gov":
        case "rating":
        case "rating_edit":
        case "assets":
        case "offers_add":
        case "offers_edit":
        case "offers":
        case "contract_offer":
            if (!getContainer('User')->isPartner() && !getContainer('User')->isAdmin()) {
                $b404 = true;
            }
            break;
    }

    if ($b404 && $arParams["SET_STATUS_404"] == "Y") {
        $folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
        if ($folder404 != "/") {
            $folder404 = "/" . trim($folder404, "/ \t\n\r\0\x0B") . "/";
        }
        if (substr($folder404, -1) == "/") {
            $folder404 .= "index.php";
        }

        if ($folder404 != $APPLICATION->GetCurPage(true)) {
            $componentPage = "404";
            CHTTP::SetStatus("404 Not Found");
        }
    } elseif ($b404) {
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
        case "gov":
            $componentPage = "gov";
            break;
        case "rating":
            $componentPage = "rating";
            break;
        case "rating_edit":
            $componentPage = "rating_edit";
            break;
        case "assets":
            $componentPage = "assets";
            break;
        case "offers":
            $componentPage = "assets";
            break;
        case "offers_edit":
            $componentPage = "assets";
            break;
        case "offers_add":
            $componentPage = "assets";
            break;
        case "subscribe":
            $componentPage = "subscribe";
            break;
        case "contract_offer":
            $componentPage = "contract_offer";
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

$APPLICATION->AddChainItem("Личный кабинет", "/cabinet/");
$this->IncludeComponentTemplate($componentPage);