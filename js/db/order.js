function replace_form(data) {
    $("#order-form").replaceWith(data['data']);
    $("#order-form").slideDown({
        duration: 400,
        easing: "swing"
    });
}

/*
$('div.btn-add').click(function(){



		$("<li><div class=\"btn-close\">M</div></li>").insertAfter(
    	  $("li:last"));



   	$("ul").append($("<li><div class=\"btn-close\">M</div></li>"));
});
$('div.btn-remove').click(function(){
		$('ul').empty();
});
 */

function delete_position()
{

}

function setWrong(element) {
    element
        .removeClass("clear")
        .removeClass("correct")
        .addClass("wrong")
        .attr("style", "outline: none");
}

function setClear(element) {
    element
        .removeClass("correct")
        .removeClass("wrong")
        .addClass("clear")
        .attr("style", "outline: none");
}

function setCorrect(element) {
    element
        .removeClass("wrong")
        .removeClass("clear")
        .addClass("correct")
        .attr("style", "outline: none");
}


function check_field()
{
    var element = $(this);
    console.log(element.val());
    if(element.val() == "")
    {
        console.log(element.val());
        setClear(element);
    }
    else
    {
        if(element.attr('id') == 'passport-serial')
        {
            if(/^[0-9]{4}$/i.exec(element.val()))
            {
                console.log('Set correct');
                setCorrect(element);
                return;
            }
            else
            {
                console.log('Set wrong');
                setWrong(element);
            }
        }
        else if(element.attr('id') == 'passport-number')
        {
            if(/^[0-9]{6}$/i.exec(element.val()))
            {
                console.log('Set correct');
                setCorrect(element);
                return;
            }
            else
            {
                console.log('Set wrong');
                setWrong(element);
            }
        }
        else
        {
            console.log('Set wrong');
            setWrong(element);
        }
    }
}


function show_pop_up_error(text)
{
    $("#srv-msg0").slideUp({
                duration: 400,
                easing: "swing"
            });
    $('#pop-up-msg0').text(text);
    $("#srv-msg0")
        .removeClass("pop-up-warning")
        .removeClass("pop-up-success")
        .removeClass("pop-up-info")
        .addClass("pop-up-error");
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


function show_pop_up_warning(text)
{
    $("#srv-msg0").slideUp({
            duration: 400,
            easing: "swing"
        });
    $('#pop-up-msg0').text(text);
    $("#srv-msg0")
        .removeClass("pop-up-error")
        .removeClass("pop-up-success")
        .removeClass("pop-up-info")
        .addClass("pop-up-warning");
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


function show_pop_up_success(text)
{
    $("#srv-msg0").slideUp({
            duration: 400,
            easing: "swing"
        });
    $('#pop-up-msg0').text(text);
    $("#srv-msg0")
        .removeClass("pop-up-error")
        .removeClass("pop-up-warning")
        .removeClass("pop-up-info")
        .addClass("pop-up-success");
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

function set_order_confirm_button(enabled) {
    var btn = $("#btn-book-order");
    if(enabled)
        btn.slideDown();
    else
        btn.slideUp();
}

function get_position_fields(type, pointer)
{
    console.log(pointer);
    fields = pointer.children("div.order-position-fields");
    console.log(fields);
    fields.slideUp();
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "form_type": type
        },
        success: function(data) {
            if(data['error'])
            {
                show_pop_up_error(data['error']);
                return;
            }
            var current_index = pointer.index();

            fields.remove();
            pointer.append(data['data']);

            console.log("Sliding down fields with index: " + current_index);
            console.log("Length: " + pointer.length);

            fields = $(
                "li.order-position:eq(" +
                    current_index  +
                ") div.order-position-fields"
            );
            fields.slideDown();
            /* code here for catching changes in blocks */
        }
    });
}

function remove_receipt_position(index)
{
    $("li.receipt-position:eq(" + index + ")").remove();
    var $i = 1;
    jQuery.each($("div.receipt-position-index"), function () {
        $(this).text($i);
        $i++;
    });
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "removed_index": index
        },
        success: function(data)
        {
            $("#receipt-summary-cost").text(data['order_cost']);
        }
    });
}

function remove_position(eventHandler) {
    var position = $(this).parent().parent();
    remove_receipt_position(position.index());
    position.remove();
    if($("li.order-position").length == '0')
        set_order_confirm_button(0);
    eventHandler.stopImmediatePropagation();
}

function select_fields_type(eventHandler) {
    var pointer = $(this).parent().parent().parent();
    if($(this).val() == 'Услуга')
        get_position_fields(0, pointer);
    else
        get_position_fields(1, pointer);
    eventHandler.stopImmediatePropagation();
}

function order_list_events(data)
{
    $("ul#orders-list")
        .append(data['order_data']);
    $("ul#receipt-list")
        .append(data['receipt_data']);
    $("#receipt-summary-cost")
        .text(data['order_cost']);
    $("li div#btn-delete-position")
        .on("click", remove_position );
    $(".order-type")
        .on("change", select_fields_type);

    pointer = $("li.order-position:last");

    console.log("Length: " + pointer.length);

    pointer.slideDown();
    set_order_confirm_button(1);
    get_position_fields(0, pointer);
}

function add_position() {
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "add_position": "true"
        },
        success: function(data)
        {
            order_list_events(data);
        }
    });
}

function remove_all() {
    set_order_confirm_button(0);
    $("ul#orders-list").empty();
    $("ul#receipt-list").empty();
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "remove_all": "true"
        },
        success: function (data)
        {
            show_pop_up_success("Корзина успешно очищена");
        }
    });
}

function start_ordering(data) {
    console.log('Ordering started');
    if(data['error'])
    {
        show_pop_up_error(
            data['error'] + '. Пожалуйста, перезагрузите страницу.'
        );
        return;
    }
    $("#order-client").empty().append(data['data']);
    console.log("Disabling order confirmation first time");
    $("#btn-book-order").attr("style", "display: none;");

    $("#order-client-info").slideDown({
                duration: 400,
                easing: 'swing'
            });
    $("#orders-list-container").slideDown({
                duration: 400,
                easing: 'swing'
            });
    $("#receipt-client-name").text(
        data['clientName'] + ' ' + data['clientSurname']
    );

    $("#receipt-client-id").text(data['clientID']);
    $("#receipt-client-sex").text(data['clientSex']);
    $("#receipt-client-funds").text(data['clientFunds']);
    $("#receipt-client-e-mail").text(data['clientEMail']);
    $("#receipt-passport-serial").text(data['clientPassportSerial']);
    $("#receipt-passport-number").text(data['clientPassportNumber']);
    $("#receipt-client-phone-number").text(data['clientPhoneNumber']);
    
    
    //show_pop_up_success("Клиент найден. Доступно оформление заказа");
    $("#btn-add-position").click(add_position);
    $("#btn-remove-all").click(remove_all);
}


function remember_client()
{
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "remember_order_client": "true"
        },
        success: function(data)
        {
            start_ordering(data);
        }
    });
}


function search_client() {
    $("#order-client-search").slideUp({
                duration: 400,
                easing: 'swing'
            });

    passport_serial = $("#passport-serial").val();
    passport_number = $("#passport-number").val();

    console.log(
        'Start searching client with passport: ' +
        passport_serial + ' ' +
        passport_number);
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "passport_serial": passport_serial,
            "passport_number": passport_number
        },
        success: function(data)
        {
            start_ordering(data);
        }
    });
}

function search_events() {
    $("#passport-serial, #passport-number").keyup(check_field);
    $("#btn-search").click(search_client);
}

function show_order_client_search()
{
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            retrieve_client_search_form: 'true'
        },
        success: function(data)
        {
            $("#order-client").append(data['data']);
            $("#order-client-search").slideDown({
                duration: 400,
                easing: 'swing'
            });
            search_events();
        }
    });
}


function prepare_ordering()
{
    console.log('Previously selected client not found');
    show_order_client_search();
    search_events();
}

function allow_ordering(data)
{
    console.log('Found previously selected client with ID = ' + data);
    remember_client();
}

function check_previous_selection()
{
    $.ajax({
        url: '/php/db/order.php',
        type: 'POST',
        dataType: 'text',
        data: {
            'check': 'true'
        },
        success: function(data)
        {
            console.log(data);
            if(data.toString() == 0)
                prepare_ordering();
            else
                allow_ordering(data);
        }
    });
}

$('document').ready(check_previous_selection);
