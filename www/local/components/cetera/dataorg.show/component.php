<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();



$arComponentVariables = array('offer_id');


$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams['VARIABLE_ALIASES']);
CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);



$arResult = array('VARIABLES' => $arVariables, 'ALIASES' => $arVariableAliases);
$arVarAliaces = $arParams['VARIABLE_ALIASES'];


$hblockID = $arParams["HIGHLOAD_ID"];
$usr = new Wic\User\User($USER);
    $USERID = $usr->getID();
    if($usr->isPartner()) { // Проверяем, является ли пользователь партнером

        $hblock = new Cetera\HBlock\SimpleHblockObject($hblockID); // Подцепляем нужный хайблок
        $rowNames = $hblock->getHblockEntityFields();
        $regionBlock = new Cetera\HBlock\SimpleHblockObject(5); // Подцепляем блок с регионами

        $reglol = $regionBlock->getArray();

        $regList = $regionBlock->getList();

        $Regions = $regList->fetchAll();


        foreach($Regions as $region){
            $newRegions[$region["ID"]] = $region["UF_REGION_NAME"];
        }

        $filter = array(
            "UF_USER_ID" => $USERID,

        );

        $list = $hblock->getList(Array("filter" => $filter));


        //под определенный проект//
        if ($fields = $list->fetch()) {
            //TODO if necessary
        } else { // если запись не создана - создаем
            if($hblockID == 1) { // Если это данные об организации - заполняем их из профиля пользователя
                $user = CUser::GetList(
                    $by = "",
                    $order = "desc",
                    array("ID" => $USERID),
                    array()
                 );
                $user = $user->fetch();

                $addInfo = array(
                    "UF_USER_ID" => $USERID
                );

                    $addInfo["UF_ORGNAME"] = $user["WORK_COMPANY"];
                    $addInfo["UF_PHONE"] = $user["PERSONAL_MOBILE"];
                    $addInfo["UF_NAME"] = $user["NAME"];

                $hblock->add($addInfo);

                $list = $hblock->getList(Array("filter" => $filter));//получаем заново всю инфу о блоке
                $fields = $list->fetch();
            }


        }
        //под определенный проект//

        foreach($fields as $name=>$row){





            if( $name != "UF_USER_ID")
            {   $arResult["FIELDS"][$name] = $rowNames[$name];
                $arResult["FIELDS"][$name]["TITLE"] = $rowNames[$name]["EDIT_FORM_LABEL"];
                $type = $rowNames[$name]["USER_TYPE_ID"];
                $arResult["FIELDS"][$name]["TYPE"] = $type;
                if($type=="file"){
                    $arResult["FIELDS"][$name]["VALUE"] = CFile::GetPath($row);

                }elseif($name == "UF_REGION") {
                    print_r($rowNames[$name]);
                    foreach($row as $reg){

                        $RegionsAr[] = $newRegions[$reg];
                    }


                    $arResult["FIELDS"][$name]["VALUE"] = implode(", ", $RegionsAr);
                    unset($RegionsAr);

                }elseif($name == "ID") {
                    $arResult["FIELDS"][$name]["VALUE"] = $row;
                }else{

                    if(!is_array($row)){

                        $arResult["FIELDS"][$name]["VALUE"]  = call_user_func_array(
                            array($rowNames[$name]["USER_TYPE"]["CLASS_NAME"], "GetAdminListViewHTML"),
                            array(
                                $rowNames[$name],
                                array(
                                    "NAME" => $rowNames[$name]["FIELD_NAME"],
                                    "VALUE" => htmlspecialcharsbx($row)
                                )
                            )

                        );
                    } else {

                        foreach($row as $valRow){
                            $newArr[] = call_user_func_array(
                                array($rowNames[$name]["USER_TYPE"]["CLASS_NAME"], "GetAdminListViewHTML"),
                                array(
                                    $rowNames[$name],
                                    array(
                                        "NAME" => $rowNames[$name]["FIELD_NAME"],
                                        "VALUE" => htmlspecialcharsbx($valRow)
                                    )
                                )

                            );
                        }
                        $arResult["FIELDS"][$name]["VALUE"] = implode(", ", $newArr);
                        unset($newArr);

                        //TODO надо решить как цеплять привязанные поля




                    }
                }





            }



        }





        $arResult["IS_PARTNER"] = true;
    }
  $this->IncludeComponentTemplate();

