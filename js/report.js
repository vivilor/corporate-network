
function show_calendar(data)
{
    //console.log(data['error']);
    //console.log(data['data']);
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
    {
        $('#month-select')
            .empty()
            .replaceWith(data['data']);
        $('#month-select').slideDown({
                duration: 400,
                easing: "swing"
        });
        $("input[name='month']").click(function () {
            retrieve_report($(this).val())
        });
    }
}


function show_report(data)
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
    {
        var report_view = $('#report-view');
        report_view.empty();
        report_view.replaceWith(data['data']);
        report_view.slideDown({
                duration: 400,
                easing: "swing"
        });
    }
}


function retrieve_data(year) {
    $('#month-select').slideUp({
                duration: 400,
                easing: "swing"
    });
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


function retrieve_report(chosen_month)
{
    $('#report-view').slideUp({
                duration: 400,
                easing: "swing"
        });
    var chosen_year = $("#years").val();
    console.log("Ajax started");
    $.ajax({
        url: '../../php/db/report.php',
        type:'POST',
        dataType: 'json',
        data: {
            year: chosen_year,
            month: chosen_month
        },
        success: function(data)
        {
            show_report(data)
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
