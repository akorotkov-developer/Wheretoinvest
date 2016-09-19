$(document).ready(function () {
    $(".js-open-review-form").click(function () {
        $("#add_review").show("slow");
    });
    if($("font").is(".errortext")) {
        $("#add_review").show("slow");
    }
});