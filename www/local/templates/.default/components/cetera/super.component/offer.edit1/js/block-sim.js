$(document).on('change', '#FIELD_UF_NAME', function () {
    var fieldName__count_vars = $('#FIELD_UF_NAME').val().length;
    if (fieldName__count_vars > 70) {
        alert('не более 70-ти символов для этого поля!');
        document.getElementById('submit').disabled = true;

    } else {
        document.getElementById('submit').disabled = false;
    }

});
