function setWrong() {
    $("#usr-login")
        .removeClass("clear")
        .removeClass("correct")
        .addClass("wrong")
}

function setClear() {
    $("#usr-login")
        .removeClass("correct")
        .removeClass("wrong")
        .addClass("clear");
}

function setCorrect() {
    $("#usr-login")
        .removeClass("wrong")
        .removeClass("clear")
        .addClass("correct");
}

function validate() {
    var field_val = $('#usr-login').val();
    if(field_val == '')
        setClear();
    else
        $.ajax({
            url: 'cp/utils/validate.php',
            type: 'GET',
            data: {username:field_val},
            dataType: 'text',
            success: function(data) {
                console.log(data);
                if (data == '1')
                { setCorrect();}
                if (data == '0')
                { setWrong(); }
            }});
}

$(function(){
    $("#usr-login")
        .keyup(validate);
});