<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();



$hblockID = $arParams["HIGHLOAD_ID"];
$usr = new Wic\User\User($USER);
    $USERID = $usr->getID();
    if($usr->isPartner()) { // Проверяем, является ли пользователь партнером

        $hblock = new Cetera\HBlock\SimpleHblockObject($hblockID); // Подцепляем нужный хайблок
        $rowNames = $hblock->getHblockEntityFields();
        $filter = array(
            "UF_USER_ID" => $USERID,

        );



        $list = $hblock->getList(Array("filter" => $filter));
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




        foreach($fields as $name=>$row){
            if($name!= "ID" && $name != "UF_USER_ID")
            {
                $arResult["FIELDS"][$name]["TITLE"] = $rowNames[$name]["EDIT_FORM_LABEL"];
                $type = $rowNames[$name]["USER_TYPE_ID"];
                $arResult["FIELDS"][$name]["TYPE"] = $type;
                if($type=="file"){
                    $arResult["FIELDS"][$name]["VALUE"] = CFile::GetPath($row);
                }else{
                    $arResult["FIELDS"][$name]["VALUE"] = $row;
                }


            }
        }


        $arResult["IS_PARTNER"] = true;
    }
  $this->IncludeComponentTemplate();

