<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $USER;
global $APPLICATION;
$arResult = Array();
if (intval($_REQUEST["id"]) > 0 && $_REQUEST["ajax"] == "Y" && $USER->IsAuthorized() && getContainer("User")->isPartner()) {
    if ($_REQUEST["action"] == "add") {
        $hblock = new \Cetera\HBlock\SimpleHblockObject(12);

        $file = \CFile::GetFileArray(intval($_REQUEST["id"]));

        if (!empty($file)) {
            $fileType = explode(".", $file["FILE_NAME"]);
            $fileType = $fileType[count($fileType) - 1];
            if (preg_match("#image#is", $file["CONTENT_TYPE"])) {
                $thumbnail = \CFile::ResizeImageGet($file["ID"], array('width' => 150, 'height' => 150), BX_RESIZE_IMAGE_PROPORTIONAL, true);
            } else {
                $filePath = WIC_TEMPLATE_PATH . "/images/file_type/" . $fileType . ".png";
                if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $filePath)) {
                    $filePath = WIC_TEMPLATE_PATH . "/images/file_type/default.png";
                }
                $thumbnail["src"] = $filePath;
            }

            $time = time();
            $file["DATE"] = strtolower(CIBlockFormatProperties::DateFormat("d M Y", $time));
            $file["LINK"] = $thumbnail["src"];
            $file["FILE_SIZE"] = ConvertBytes($file["FILE_SIZE"]);

            $hblock->add(Array("UF_USER" => $USER->GetID(), "UF_FILE" => intval($file["ID"]), "UF_DATE" => date("d.m.Y H:i:s", $time)));
            $arResult["SUCCESS"] = "Y";
            $arResult["FILE"] = $file;
        }
    } elseif ($_REQUEST["action"] == "del") {
        $hblock = new \Cetera\HBlock\SimpleHblockObject(12);
        $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID(), "UF_FILE" => intval($_REQUEST["id"]))));
        if ($el = $list->fetch()) {
            $hblock->delete($el["ID"]);
            \CFile::Delete(intval($_REQUEST["id"]));
            $arResult["SUCCESS"] = "Y";
        } else {
            $arResult["ERROR"] = "Доступ запрещен";
        }
    }
} else {
    $arResult["ERROR"] = "Доступ запрещен";
}

echo json_encode($arResult);
die();
?>