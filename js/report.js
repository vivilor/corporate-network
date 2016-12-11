function show_calendar(data)
{
    console.log(data['error']);
    console.log(data['data']);
    if(data['error'])
    {
        $("#pop-up-msg0").text(data['error']);
        $("#srv-msg0").slideDown({
                    duration: 400,
                    easing: "swing"
                });
        $("#pop-up-btn0").click(
            function() {
                $("#srv-msg0").slideUp({
                    duration: 400,
                    easing: "swing"
                });
            }
        );
    }
    else
        $('#calendar').empty().replaceWith(data['data']);
}

function retrieve_data(year) {
    $.ajax({
        url: '../../php/calendar_packer.php',
        type:'POST',
        dataType: 'json',
        data: {
            year: year
        },
        success: function(data)
        {
            show_calendar(data)
        }
    });
}

function report_events()
{
    retrieve_data($("select#years").val());
    $("#years").change(function () {
        var selected_year = $("select#years").val();
        retrieve_data(selected_year);
    });
}

$(document).ready(report_events);
