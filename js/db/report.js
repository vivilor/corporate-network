
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
            retrieve_report($(this).val(), is_created($(this)))
        });
    }
}


function show_report(data, year, is_created)
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
        $('#report-view')
        .empty()
        .replaceWith(data['data']);
        $('#report-view').slideDown({
                duration: 400,
                easing: "swing"
        });
        if(!is_created)
        {
            retrieve_data(year);
        }
    }
}


function retrieve_data(year) {
    $('#month-select').slideUp({
                duration: 400,
                easing: "swing"
    });
    $.ajax({
        url: '../../php/db/calendar.php',
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


function is_created($selected)
{
    var button_div = $selected.parent();
    var cell_div = button_div.parent();
    var cell_caption = cell_div.find(".small-text");
    if(cell_caption.hasClass("turned-on"))
    {
        return 1;
    }
    return 0;
}

function retrieve_report(chosen_month, is_created)
{
    $('#report-view').slideUp({
                duration: 400,
                easing: "swing"
        });
    var chosen_year = $("select[name='years']").val();
    console.log("Ajax started");
    $.ajax({
        url: '/php/db/report.php',
        type:'POST',
        dataType: 'json',
        data: {
            year: chosen_year,
            month: chosen_month
        },
        success: function(data)
        {
            show_report(data, chosen_year, is_created)
        }
    });
}


function report_events()
{
    retrieve_data($("select[name='years']").val());

    $("#years").change(function () {
        var selected_year = $("select[name='years']").val();
        retrieve_data(selected_year);
    });

}

$(document).ready(report_events);
