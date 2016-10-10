<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->SetTitle("Договор-оферта");
$APPLICATION->AddChainItem("Договор-оферта");
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
                        <?/*?><a href="#" data-id="<?= $file["ID"] ?>" class="js-remove-file">Удалить</a><?*/?>
                    </div>
                </div>
            </div>
        <? endforeach ?>
    <? endif; ?>
</div>
<?/*?>
<script type="text/javascript">
    $(function () {
        $(".js-remove-file").unbind().on("click", function () {
            if (confirm("Вы действительно хотите удалить данный файл?")) {
                var id = $(this).data("id"),
                    parent = $(this).closest(".i-file-item");

                if (id !== "") {
                    $.ajax({
                        url: "/local/ajax/addOfferFile.php",
                        data: {
                            id: id,
                            ajax: "Y",
                            action: "del"
                        },
                        method: "post",
                        dataType: "json",
                        success: function (response) {
                            if (response.SUCCESS !== undefined) {
                                parent.detach();
                            }
                            else if (response.ERROR !== undefined) {
                                alert(response.ERROR);
                            }
                        }
                    });
                }
            }
            return false;
        });
    });
</script>

<? $APPLICATION->IncludeComponent("bitrix:main.file.input", "drag_n_drop",
    array(
        "INPUT_NAME" => "TEST_NAME_INPUT",
        "MULTIPLE" => "N",
        "MODULE_ID" => "main",
        "MAX_FILE_SIZE" => "",
        "ALLOW_UPLOAD" => "F",
        "ALLOW_UPLOAD_EXT" => "doc,docx,pdf,xls,xlsx,ppt,pptx"
    ),
    false
); ?>
<?*/?>
