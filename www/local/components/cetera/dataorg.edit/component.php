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

        $regionBlock = new Cetera\HBlock\SimpleHblockObject(5); // Подцепляем блок с регионами

        $reglol = $regionBlock->getArray();

        $regList = $regionBlock->getList();

        $Regions = $regList->fetchAll();


        foreach($Regions as $region){
            $newRegions[$region["ID"]] = $region["UF_REGION_NAME"];
        }


        $fp = fopen($_SERVER["DOCUMENT_ROOT"]."/regions.json", "w");


        fwrite($fp, json_encode($newRegions));


        fclose($fp);

        $rowNames = $hblock->getHblockEntityFields();
        $filter = array(
            "UF_USER_ID" => $USERID,

        );
        if(!empty($arVariables["offer_id"]))
            $filter["ID"] = $arVariables["offer_id"];


        $list = $hblock->getList(Array("filter" => $filter));
        if ($prop = $list->fetch()) {
            //TODO if necessary
        } else { // если запись не создана - создаем

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
            if($hblockID == 1) { // Если это данные об организации - заполняем их из профиля пользователя
                $addInfo["UF_ORGNAME"] = $user["WORK_COMPANY"];
                $addInfo["UF_PHONE"] = $user["PERSONAL_MOBILE"];
                $addInfo["UF_NAME"] = $user["NAME"];


            }
            $hblock->add($addInfo);

        }

        if (!empty($_REQUEST["goorgreg"])) { // если отправлена форма
            $filter = array(
                "UF_USER_ID" => $USERID,

            );
            if(!empty($arVariables["offer_id"]))
                $filter["ID"] = $arVariables["offer_id"];
            $list = $hblock->getList(Array("filter" => $filter)); //ищем ячейку с организацией

            $itemID = "";// ID ячейки
            if ($el = $list->fetch()) { // собираем массив
                $itemID = $el["ID"];

            }

            $updateInfo = array();
            foreach($_REQUEST as $keyR=>$valR){ // перебираем данные с формы (чтобы не было левых, проверяем существование в хайлоаде)
                if(key_exists($keyR,$el) && $keyR!="UF_USER_ID" && $keyR!="ID"){
                    $updateInfo[$keyR] = $valR;
                }
            }

            foreach($_FILES as $filename=>$file){ // загрузка изображений
                if(!empty($file["tmp_name"])){
                    $imageinfo = getimagesize($file["tmp_name"]);
                    if($imageinfo["mime"] != "image/gif" && $imageinfo["mime"] != "image/jpeg" && $imageinfo["mime"] !="image/png") {
                       $arResult["ERRORS"][$filename] = "Недопустимый формат файла";

                    }else {
                        //Сохранение загруженного изображения с расширением, которое возвращает функция getimagesize()
                        //Расширение изображения
                        $mime=explode("/",$imageinfo["mime"]);
                        //Имя файла
                        $namefile=explode(".",$file["name"]);
                        //Полный путь к директории
                        $uploaddir = $_SERVER['DOCUMENT_ROOT']."/upload/logos/";
                        if(!is_dir($uploaddir))
                            mkdir($uploaddir, 0777);
                        //Функция, перемещает файл из временной, в указанную вами папку
                        $pathnewfile = $uploaddir.$namefile[0].rand(0,1000).".".$mime[1];
                        if (!move_uploaded_file($file["tmp_name"], $pathnewfile)) {
                            $arResult["ERRORS"][$filename] = "Файл не был загружен";
                        }else {
                            $makefile = CFile::MakeFileArray($pathnewfile);
                            $updateInfo[$filename] = $makefile;
                            unset($makefile);
                        }
                    }
                }


            }

            if (!empty($itemID)) {
                $hblock->update($itemID, $updateInfo);

            }


        }

        $list = $hblock->getList(Array("filter" => $filter)); // получаем инфо
        $fields = $list->fetch();
        foreach($fields as $name=>$row){
            if( $name != "UF_USER_ID")
            {
                $arResult["FIELDS"][$name]["TITLE"] = $rowNames[$name]["EDIT_FORM_LABEL"];
                $type = $rowNames["USER_TYPE_ID"];
                $arResult["FIELDS"][$name]["TYPE"] = $type;
                if($type=="file"){
                    $arResult["FIELDS"][$name]["VALUE"] = CFile::GetPath($row);

                }elseif($name == "ID") {
                    $arResult["FIELDS"][$name]["VALUE"] = $row;
                }elseif($name == "UF_REGION") {

                   // $arResult["FIELDS"][$name]["VALUE"] = '<div contenteditable="true" style="border: 1px solid #cccccc;height: 40px;padding: 5px;" class="js-regions regions">'.implode(" ", $row).'</div>';
                    $arResult["FIELDS"][$name]["VALUE"] = '<ul id="myTags"><li>fdf</li></ul>';
                }
                else{

                    $arResult["FIELDS"][$name]["VALUE"]  = call_user_func_array(
                        array($rowNames[$name]["USER_TYPE"]["CLASS_NAME"], "getadminlistedithtml"),
                        array(
                            $rowNames[$name],
                            array(
                                "NAME" => $rowNames[$name]["FIELD_NAME"],
                                "VALUE" => htmlspecialcharsbx($row)
                            )
                        )
                    );
                }





            }
        }


        $arResult["IS_PARTNER"] = true;
    }
  $this->IncludeComponentTemplate();

