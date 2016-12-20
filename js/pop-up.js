function pop_up_events() {
    if($('#pop-up-msg0').text() != "")
    {
        $("#srv-msg0").slideDown({
            duration: 800,
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
}

$(document).ready(pop_up_events);
