<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

// component text here

global $USER;
if (!empty($_REQUEST["user"]) && !empty($_REQUEST["CONFIRM_CODE"])):
    $rsUser = \CUser::GetByLogin(trim($_REQUEST["user"]));
    if ($arUser = $rsUser->GetNext()) {
        if ($arUser["UF_CHECKWORD"] === $_REQUEST["CONFIRM_CODE"]) {
            $cUser = new \CUser();
            $cUser->Delete($arUser["ID"]);

            $hblock = new \Cetera\HBlock\SimpleHblockObject(6);
            $list = $hblock->getList(Array("filter" => Array("UF_USER" => $arUser["ID"])));
            while ($el = $list->fetch()) {
                $hblock->delete($el["ID"]);
            }

            $hblock = new \Cetera\HBlock\SimpleHblockObject(7);
            $list = $hblock->getList(Array("filter" => Array("UF_USER" => $arUser["ID"])));
            while ($el = $list->fetch()) {
                $hblock->delete($el["ID"]);
            }

            $USER->Logout();
            $arResult["SUCCESS"][] = "Аккаунт успешно удален";

        } else {
            $arResult["ERROR"][] = "Неверно указан код подтверждения.";
        }
    } else {
        $arResult["ERROR"][] = "Неверно указан логин.";
    }
else:
    $arResult["ERROR"][] = "Неверно указан логин или код подтверждения.";
endif;

// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult;