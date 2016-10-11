<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="hide-for-small-only">
    <a href="#" class="js-open-mistake i-mistake__btn b-form__btn">Сообщить об
        ошибке<span class="i-mistake__btn-help_wrapper"><span
                class="i-mistake__btn-help has-tip" data-tooltip="" aria-haspopup="true"
                title="Нашли ошибку? Выделите ее мышью и нажмите на эту кнопку!">?</span></span></a>

    <div id="mistake" class="reveal-modal tiny modal" data-reveal aria-labelledby="modalTitle"
         aria-hidden="true"
         role="dialog">
        <div class="row">
            <div class="column small-12">
                <form action="<?= $templateFolder ?>/ajax.php" class="b-form x-save-form" enctype="multipart/form-data"
                      method="post">
                    <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
                    <input type="hidden" name="ajax" value="Y">

                    <h2 class="content__title">Сообщение об ошибке</h2>

                    <div class="b-main-block__body"></div>

                    <?= getFormFields($arResult["FORM_FIELDS"], 12, "b-form__ro-small-margin"); ?>
                    <br>

                    <div class="row">
                        <div class="column small-12 medium-5 small-centered">
                            <button class="b-form__btn" type="submit">Сообщить об ошибке</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <a class="close-reveal-modal modal__close" aria-label="Close">×</a>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        function sl() {
            var w = window;
            var selection = false;
            var mistake = $("#FIELD_MISTAKE");
            if (w.getSelection) {
                text = w.getSelection();
            }
            else if (w.document.getSelection) {
                text = w.document.getSelection();
            }
            else {
                selection = w.document.selection;
            }

            if (selection) {
                var r = selection.createRange();
                if (!r)
                    return;
                text = r.text;
            }

            if (text) {
                mistake.val(text);

                if (mistake.val().trim() !== "") {
                    text = 'Обнаружена ошибка на странице по адресу:\n' + window.location.href + '\n==================================\nТекст, содержащий ошибку:\n==================================\n\n' + text + '\n\n==================================';
                    mistake.val(text);
                }
                else {
                    mistake.val("");
                }
            }
            else {
                mistake.val("");
            }
        }

        $(".js-open-mistake")
            .on("mouseover", sl)
            .on("click", function () {
                var mistake = $("#FIELD_MISTAKE");

                if (mistake.val().trim() == "") {
                    alert("Необходимо выделить текст до нажатия на эту кнопку.");
                    return false;
                }

                $("#mistake").foundation('reveal', 'open');

                return false;
            });

        $(".x-save-form").on("submit", function () {
            var data = $(this).serialize(),
                _this = $(this),
                url = _this.attr("action") !== "" ? _this.attr("action") : "<?=$templateFolder?>/ajax.php";
            _this.find(".b-form__error").detach();
            _this.find(".alert-box").detach();
            $(".js-alert").html("");

            $.ajax({
                url: url,
                data: data,
                method: "POST",
                dataType: "json",
                success: function (response) {
                    if (response.ERRORS !== undefined) {
                        $.each(response.ERRORS, function (i, item) {
                            _this.find('input[name="' + i + '"]').after('<div class="b-form__error">' + item + '</div>');
                        });
                    }
                    else if (response.ERROR !== undefined) {
                        _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box alert radius">' + response.ERROR + '<a href="#" class="close">&times;</a></div>');
                    }
                    else if (response.SUCCESS !== undefined) {
                        _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box success radius">' + response.SUCCESS + '<a href="#" class="close">&times;</a></div>');
                    }

                    $(document).foundation("alert", "reflow");
                }
            });
            return false;
        });

    });
</script>