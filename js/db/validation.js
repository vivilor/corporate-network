function setWrong() {
    $("#usr-name")
        .removeClass("clear")
        .removeClass("correct")
        .addClass("wrong")
}

function setClear() {
    $("#usr-name")
        .removeClass("correct")
        .removeClass("wrong")
        .addClass("clear");
}

function setCorrect() {
    $("#usr-name")
        .removeClass("wrong")
        .removeClass("clear")
        .addClass("correct");
}

function validate() {
    var field_val = $('#usr-name').val();
    if(field_val == '' &&  $("#usr-pswd").val() == '')
    {
        setClear();
        $("#btn-submit").attr("disabled", "disabled");
    }
    else
        $("#btn-submit").removeAttr("disabled");
        $.ajax({
            url: '../php/db/validate.php',
            type: 'GET',
            data: {username:field_val},
            dataType: 'text',
            success: function(data) {
                console.log(data);
                if (data == '1')
                { setCorrect();}
                if (data == '0')
                { setWrong(); }
            }
        }
    );
}

$(document).ready(
    function(){
        $("form").slideDown({
            duration: 600,
            easing: "swing"
        });
                    console.log('PENIS');

        $("#srv-msg0").slideDown({
            duration: 800,
            easing: "swing"
        });
            console.log('PENIS');

        $("#usr-name").blur(validate);


        $("#btn-reset").click(
            function() {

                $("#btn-submit").attr("disabled", "disabled");
                setClear();
            }
        );
        $("#pop-up-btn0").click(
            function() {
                console.log('PENIS');
                $("#srv-msg0").slideUp({
                    duration: 400,
                    easing: "swing"
                });
            }
        );
        $("#usr-pswd").blur(
            function() {
                if($('#usr-name').val() == '' && $("#usr-pswd").val() == '')
                {
                    setClear();
                    $("#btn-submit").attr("disabled", "disabled");
                }
                else
                    $("#btn-submit").removeAttr("disabled");
            }
        );
    }
);
