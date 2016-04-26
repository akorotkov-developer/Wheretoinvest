<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

    <?if($arResult["IS_PARTNER"]){?>

        <form name="orginfo" method="post"  enctype="multipart/form-data">

            <?foreach($arResult["FIELDS"] as $arKey=>$arItem):?>

                <?
                if(!empty($arResult["ERRORS"][$arKey]))
                    echo $arResult["ERRORS"][$arKey];
                switch ($arItem["TYPE"]):
                    case "file":
                    ?>
                        <img src="<?=$arItem["VALUE"];?>">
                        <label for="logoFileUpload" class="button hollow">Загрузить <?=$arItem["TITLE"];?></label>
                        <input type="file" name="<?=$arKey;?>" id="logoFileUpload" class="show-for-sr">
                    <?
                      break;
                    default:
                    ?>
                        <label><?=$arItem["TITLE"];?></label>
                        <input type="text" name="<?=$arKey;?>" value="<?=$arItem["VALUE"];?>">
                    <?
                    endswitch;
                ?>





            <?endforeach;?>
            <input type="submit" name="goorgreg" class="button" value="Сохранить">
        </form>


    <?}else {

        echo GetMessage("NOT_PARTNER");
    }?>