<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Правовые документы");
$APPLICATION->AddHeadString("<link href=\"style.css\" type=\"text/css\" rel=\"stylesheet\">", true);
?>

<?
$hblock = new \Cetera\HBlock\SimpleHblockObject(12);
$list = $hblock->getList();
$fileList = Array();
while ($el = $list->fetch()) {
    if (!empty($el["UF_FILE"])) {
        $file = \CFile::GetFileArray($el["UF_FILE"]);
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

            $file["DATE"] = $el["UF_DATE"];
            $file["LINK"] = $thumbnail["src"];
            $fileList[] = $file;
        }
    }
}
?>
    <div id="fileList">
        <? if (count($fileList)): ?>
            <? foreach ($fileList as $file): ?>
                <div class="row i-file-item">
                    <div class="column small-2 medium-1 large-1">
                        <img src="<?= $file["LINK"] ?>" alt="<?= $file["ORIGINAL_NAME"] ?>" class="i-file__img">
                    </div>
                    <div class="column small-10 medium-11 large-11">
                        <a href="<?= $file["SRC"] ?>" target="_blank" class="i-file__link"><?= $file["ORIGINAL_NAME"] ?></a>

                        <div class="i-file__desc">
                            (<?= ConvertBytes($file["FILE_SIZE"]); ?>
                            , <?= strtolower(CIBlockFormatProperties::DateFormat("d M Y", $file["DATE"]->getTimestamp())) ?>
                            ) <br>
                        </div>
                    </div>
                </div>
            <? endforeach ?>
        <? endif; ?>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>