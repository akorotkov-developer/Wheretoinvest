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

        $entity = $hblock->getHblockEntityFields();

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
        if($hblockID == 2) {
            if ($prop = $list->fetch()) {
                //TODO if necessary
            } else {// если запись не создана - создаем

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
                if ($hblockID == 1) { // Если это данные об организации - заполняем их из профиля пользователя
                    $addInfo["UF_ORGNAME"] = $user["WORK_COMPANY"];
                    $addInfo["UF_PHONE"] = $user["PERSONAL_MOBILE"];
                    $addInfo["UF_NAME"] = $user["NAME"];


                }
                $hblock->add($addInfo);
            }
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
                    if(empty($_REQUEST["NEW"]) && $_REQUEST["NEW"]!="Y"){
                        $itemID = $el["ID"];

                    }
                }


            $updateInfo = array();
            foreach($_REQUEST as $keyR=>$valR){ // перебираем данные с формы (чтобы не было левых, проверяем существование в хайлоаде)
                if(key_exists($keyR,$entity) && $keyR!="UF_USER_ID" && $keyR!="ID"){
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

            if($hblockID==3){

                foreach($_REQUEST["day"] as $key=>$value){
                    if(!empty($value)){
                        $matrixArray[0][$key]["from"] = $value;
                        if(!empty($_REQUEST["day2"][$key])) {
                            $matrixArray[0][$key]["to"] = $_REQUEST["day2"][$key];
                        }else {
                            $matrixArray[0][$key]["to"] = "";
                        }

                        if(!empty($_REQUEST["day3"][$key])) {
                            $matrixArray[0][$key]["date"] = $_REQUEST["day3"][$key];
                        }else {
                            $matrixArray[0][$key]["date"] = 1;
                        }


                    }

                }

                /*foreach($_REQUEST["day2"] as $key=>$value){

                }
                foreach($_REQUEST["day3"] as $key=>$value){
                    $matrixArray[0][$key]["date"] = $value;
                }
                */
                foreach($_REQUEST["sum"] as $key=>$value){
                    if(!empty($value)){
                        $matrixArray[$key+1][0]["from"] = $value;

                        if(!empty($_REQUEST["sum2"][$key])) {
                            $matrixArray[$key+1][0]["to"] = $_REQUEST["sum2"][$key];
                        }else {
                            $matrixArray[$key+1][0]["to"] = "";
                        }
                    }


                }

                $countRows = count($matrixArray[0]);
                $i=1;
                $row = 1;
                $cell = 1;

                foreach($_REQUEST["percent"] as $key=>$val){





                    $matrixArray[$row][$cell] = $val;

                    if($i==$countRows){
                        echo "<br>";
                        $i=1;
                        $cell=1;
                        $row++;
                    }else{
                        $cell++;
                        $i++;
                    }

                }


                $json = json_encode($matrixArray);

                if(!empty($matrixArray[0][0]["from"]) && !empty($matrixArray[1][0]["from"])){
                    $updateInfo["UF_MATRIX"] = $json;
                }
                //$countRows = count($matrixArray[0]);
                //echo "<table>";
                /*foreach($matrixArray as $key=>$value){



                    for ($i = 0; $i < $countRows; $i++) {
                        if(!empty($matrixArray[$key][$i])){
                            echo "1";
                        }else {
                            echo "0";
                        }
                    }
                    echo "<br>";
                }
                //echo "</table>";
                */
            }
            if (!empty($itemID)) {
                $hblock->update($itemID, $updateInfo);

            }else {

                $updateInfo["UF_USER_ID"] = $USERID;

                $hblock->add($updateInfo);
                if($hblockID == 3){
                    header("Location: /cabinet/partner/offers/"); /* Перенаправление броузера */
                }

            }


        }

        if($_REQUEST["NEW"]=="Y"){
            $fields = $entity;
        }else {
            $list = $hblock->getList(Array("filter" => $filter)); // получаем инфо
            $fields = $list->fetch();
        }



        foreach($fields as $name=>$row){
            if( $name != "UF_USER_ID" && $name != "UF_MATRIX")
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
                }elseif($name == "UF_INFO"){
                    $arResult["FIELDS"][$name]["VALUE"] = '<textarea name="'.$name.'">'.htmlspecialcharsbx($row).'</textarea>';
                /*}elseif($name=="UF_METHOD"){

                    $arResult["FIELDS"][$name]["TITLE"] = '';
                    $arResult["FIELDS"][$name]["VALUE"] = '<input type="hidden" value="'.$arParams["METHOD"].'" name="UF_METHOD">';
                */}
                else{
                    if($name=="UF_METHOD" && $_REQUEST["NEW"]=="Y"){
                        $row = $arParams["METHOD"];

                    }

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





            }elseif($name=="UF_MATRIX"){
                $arResult["MATRIX"] = json_decode($row,true);
            }
        }

        $arResult["HBLOCK_ID"] = $hblockID;
        $arResult["IS_PARTNER"] = true;
    }
  $this->IncludeComponentTemplate();

