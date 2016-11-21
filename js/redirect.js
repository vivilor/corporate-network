function redirect() {
    window.location = "../cp/start.php";
}

function set_value(obj, value) {
    obj.innerHTML = value;
}

function show_countdown()
{
    var show_div = document.getElementById("clock");
    var i = 3;
    set_value(show_div, i);
    var tick = setInterval(function() {
        i--;
        set_value(show_div, i);
        if(i == 0) {
            clearInterval(tick);
            redirect();
        }
    }, 1000);
}
