if (typeof($) !== 'undefined') {
    $(document).ready(function () {
        $('#asd_subscribe_submit').click(function(e){
            e.preventDefault();
            if ($('input[name="CONFIRM_S"]').is(':checked')) {
                $('#asd_subscribe_capcha').foundation("reveal", "open");
            }
        });

        function SubmittForm() {
            $('input[name="captcha_code"]').val($('input[name="captcha_code_popup"]').val());
            $('#captcha_word').val($('#captcha_word_popup').val());

            $('#asd_subscribe_form').submit();
        }
        $('.subscribe_quick_form').click(function(e){
                SubmittForm();
        });

/*        $('.subscribe_quick_form').keypress(function(e) {
            if(e.which == 13) {
                SubmittForm();
            }
        });*/

        $('#asd_subscribe_form').unbind().on("submit", function () {

            $('#asd_subscribe_capcha').foundation("reveal", "open");

            if (!$.trim($('#asd_subscribe_form input[name$="asd_email"]').val()).length) {
                return false;
            }
            var id = $(this).find("[data-confirm-input]");

            if ($(id).length) {
                if (!$(id).is(":checked")) {
                    alert("Для продолжения Вам необходимо согласиться с условиями.");
                    return false;
                }
            }

            var arPost = {};
            arPost.asd_rub = [];
            $.each($('#asd_subscribe_form input'), function () {
                if ($(this).attr('type') != 'checkbox') {
                    arPost[$(this).attr('name')] = $(this).val();
                }
                else if ($(this).attr('type') == 'checkbox' && $(this).is(':checked')) {
                    arPost.asd_rub.push($(this).val());
                }
            });
            $('#asd_subscribe_res').hide();
            $('#asd_subscribe_submit').attr('disabled', 'disabled');
            $.post('/local/templates/.default/components/asd/subscribe.quick.form/.default/action.php', arPost,
                function (data) {
                    $('#asd_subscribe_submit').removeAttr('disabled');
                    if (data.status == 'error') {
                        $('#asd_subscribe_res .mess').addClass('error');
                    } else {
                        $("#asd_subscribe_form input[name='asd_email']").val("");
                        $('#asd_subscribe_res .mess').removeClass('error');
                    }
                    $('#asd_subscribe_res .mess').html(data.message);
                    $('#asd_subscribe_res').foundation("reveal", "open");
                }, 'json');
            return false;
        });
    });
}