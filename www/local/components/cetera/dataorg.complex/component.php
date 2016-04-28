<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arVariables = array();
$arComponentVariables = array('offer_id');


$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams['VARIABLE_ALIASES']);
CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);



$arResult = array('VARIABLES' => $arVariables, 'ALIASES' => $arVariableAliases);
$arVarAliaces = $arParams['VARIABLE_ALIASES'];

  switch($arParams["TYPEPAGE"]){
    case 1:
    case 2:
    case 4:
          if(!empty($_REQUEST["EDIT"]) && $_REQUEST["EDIT"]=="Y")
              $componentPage = "edit";
          else
              $componentPage = "show";
          $arParams["HIGHLOAD_ID"] = $arParams["TYPEPAGE"];
          break;
    case 3:

          if(!empty($arVariables["offer_id"])){
              $componentPage="edit";
          }else{
              $componentPage = "list";
          }
          $arParams["HIGHLOAD_ID"] = $arParams["TYPEPAGE"];
          break;
  }

  $this->IncludeComponentTemplate($componentPage);

