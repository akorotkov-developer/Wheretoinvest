<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();



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

        if($hblockID == 3){
            $filter["UF_METHOD"] = $arParams["METHOD"];

        }


        $list = $hblock->getList(Array("filter" => $filter));

        $fields = $list->fetchAll();

        foreach($fields as $key=>$val){

            foreach($val as $name=>$row){



                if( $name != "UF_USER_ID" && $name !="UF_METHOD")
                {
                    $arResult["FIELDS"][$key][$name]["TITLE"] = $rowNames[$name]["EDIT_FORM_LABEL"];
                    $type = $rowNames[$item]["USER_TYPE_ID"];
                    $arResult["FIELDS"][$key][$name]["TYPE"] = $type;
                    if($type=="file"){
                        $arResult["FIELDS"][$key][$name]["VALUE"] = CFile::GetPath($row);
                    }elseif($name == "UF_REGION") {

                        foreach($row as $reg){

                            $RegionsAr[] = $newRegions[$reg];
                        }


                        $arResult["FIELDS"][$key][$name]["VALUE"] = implode(", ", $RegionsAr);
                        unset($RegionsAr);

                    }elseif($name == "ID") {
                        $arResult["FIELDS"][$key][$name]["VALUE"] = $row;
                    }else{

                        $arResult["FIELDS"][$key][$name]["VALUE"]  = call_user_func_array(
                            array($rowNames[$name]["USER_TYPE"]["CLASS_NAME"], "getadminlistviewhtml"),
                            array(
                                $rowNames[$name],
                                array(
                                    "NAME" => "FIELDS[".$val["ID"]."][".$rowNames[$name]["FIELD_NAME"]."]",
                                    "VALUE" => htmlspecialcharsbx($row)
                                )
                            )
                        );
                    }





                }

            }

        }










        $arResult["IS_PARTNER"] = true;
    }
  $this->IncludeComponentTemplate();

