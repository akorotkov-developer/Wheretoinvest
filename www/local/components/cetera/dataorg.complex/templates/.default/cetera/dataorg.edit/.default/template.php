<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

    <?if($arResult["IS_PARTNER"]){?>

        <?if(!empty($arResult["FIELDS"])){?>

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

                        if($arKey!="ID") {
                            ?>
                            <?if(!empty($arItem["TITLE"])){?>
                                <label><?= $arItem["TITLE"]; ?></label>
                            <?}?>

                            <?= $arItem["VALUE"]; ?>

                            <?
                        }

                    endswitch;
                ?>





            <?endforeach;?>
            <?if($arResult["HBLOCK_ID"] == 3){?>
                <?if(!empty($arResult["MATRIX"])){?>



                    <table>
                        <?$countRows = count($arResult["MATRIX"][0]);?>
                        <?$dates = array(
                            1 => "Дней",
                            2 => "Месяцев",
                            3 => "Лет"
                        );?>
                        <?foreach($arResult["MATRIX"] as $key=>$value){

                            echo "<tr>";

                            if($key==0)
                                echo "<td>&nbsp;</td>";

                            for ($i = 0; $i <= $countRows; $i++) {
                                if(!empty($arResult["MATRIX"][$key][$i])){
                                    echo "<td>";

                                    unset($valto);
                                    if($key == 0){ //если это даты
                                        echo $arResult["MATRIX"][$key][$i]["from"];

                                        echo "<input type='hidden' name='day[]' value='".$arResult["MATRIX"][$key][$i]["from"]."'>";

                                        if(!empty($arResult["MATRIX"][$key][$i]["to"])){

                                            echo " - ";

                                            echo $arResult["MATRIX"][$key][$i]["to"];

                                            $valto = $arResult["MATRIX"][$key][$i]["to"];
                                        }
                                        echo "<input type='hidden' name='day2[]' value='".$valto."'>";


                                        echo " " . $dates[$arResult["MATRIX"][$key][$i]["date"]];
                                        echo "<input type='hidden' name='day3[]' value='".$arResult["MATRIX"][$key][$i]["date"]."'>";


                                    }elseif($i==0){
                                        echo "от ";
                                        echo $arResult["MATRIX"][$key][$i]["from"];

                                        echo "<input type='hidden' name='sum[]' value='".$arResult["MATRIX"][$key][$i]["from"]."'>";


                                        if(!empty($arResult["MATRIX"][$key][$i]["to"])){

                                            echo " до ";


                                            echo $arResult["MATRIX"][$key][$i]["to"];

                                            $valto = $arResult["MATRIX"][$key][$i]["to"];
                                        }

                                        echo "<input type='hidden' name='sum2[]' value='".$valto."'>";
                                    } else {
                                        echo "<input type='text' name='percent[]' value='".$arResult["MATRIX"][$key][$i]."'>";
                                    }







                                    echo "</td>";
                                }elseif($key !=0) {
                                    echo "<td> <input type='text' name='percent[]'></td>";
                                }
                            }
                            echo "</tr>";
                        }
                        ?>

                    </table>
                <?}?>

                <div class="row js-table" >
                    <div class="column small-10 large-5 js-cols" >
                        <label>Сумма</label>
                        <div class="input-group">
                            <input type="text" name="sum[]" class="js-col1 input-group-field"><span class="input-group-label">—</span>
                            <input type="text" name="sum2[]" class="js-col2 input-group-field">
                            <div class="input-group-button">
                                <button type="button" class="button hollow success js-addcols">Добавить</button>
                            </div>
                        </div>
                    </div>
                    <div class="column small-10 large-6 large-offset-1 js-rows">
                        <label>Срок</label>
                        <div class="row collapse" >
                            <div class="column small-6">
                                <div class="input-group">
                                    <input type="text"  name="day[]"class="js-row1 input-group-field"><span class="input-group-label">—</span>
                                    <input type="text" name="day2[]" class="js-row2 input-group-field">
                                </div>
                            </div>
                            <div class="column small-4 large-3">
                                <select name="day3[]" class="js-row3">
                                    <option selected="" value="1">Дней</option>
                                    <option value="2">Месяцев</option>
                                    <option value="3">Лет</option>
                                </select>
                            </div>
                            <div class="column small-12 large-3">
                                <button type="button" class="button hollow success js-addrows">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?}?>
            <input type="submit" name="goorgreg" class="button" value="Сохранить">
        </form>
        <?}else{?>
            Ой ой, а прав то у вас нет.
        <?}?>

    <?}else {

        echo GetMessage("NOT_PARTNER");
    }?>