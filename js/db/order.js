function set_receipt_client_data(data) {
    $("#receipt-client-id").text(data['clientID']);
    $("#receipt-client-sex").text(data['clientSex']);
    $("#receipt-client-funds").text(data['clientFunds']);
    $("#receipt-client-e-mail").text(data['clientEMail']);
    $("#receipt-passport-serial").text(data['clientPassportSerial']);
    $("#receipt-passport-number").text(data['clientPassportNumber']);
    $("#receipt-client-phone-number").text(data['clientPhoneNumber']);
}

function get_position_type(index) {
    return $("li.order-position:eq(" + index + ")")
        .find("select.order-type").val();
}

function get_position_type_index(type) {
    switch (type) {
        case "Услуга":          return 0;
        case "Оборудование":    return 1;
    }
}

function get_position_quantity(index) {
    return $("li.order-position:eq(" + index + ") input[name='item-quantity']")
        .val();
}

function set_receipt_position_cost(index, cost) {
    $("li.receipt-position:eq(" + index + ") div.receipt-position-cost").text(cost);
}

function set_receipt_position_type(index) {
    $("li.receipt-position:eq(" + index + ") div.receipt-position-type").text(
        $("li.order-position:eq(" + index + ") select.order-type").val()
    );
}

function set_receipt_position_name(index) {
    $("li.receipt-position:eq(" + index + ") div.receipt-position-name").text(
        $("li.order-position:eq(" + index + ") select[name='item-select']").val()
    );
}

function set_receipt_position_quantity(index) {
    var quantity = 1;
    if( get_position_type_index(get_position_type(index)) )
        quantity = get_position_quantity();
    $("li.receipt-position:eq(" + index + ") div.receipt-position-quantity")
        .text(quantity);
}

function set_receipt_position_index(index) {
    console.log(index);
    $("li.receipt-position:eq(" + index + ") div.receipt-position-index").text(index+1);
}

function show_order_position_fields(index, fields) {
    var position = $("li.order-position:eq(" + index + ")");
    $("li.order-position:eq(" + index + ") div.order-position-fields")
        .remove();
    position.append(fields);
    position.find("div.order-position-fields").slideDown();
}

function hide_order_position_fields(index) {
    var fields = $("li.order-position:eq(" + index + ") div.order-position-fields");
    fields.slideUp();

}

function set_receipt_summary_cost(cost) { $("#receipt-summary-cost").text(cost); }

function set_wrong(element) {
    element
        .removeClass("clear")
        .removeClass("correct")
        .addClass("wrong")
        .attr("style", "outline: none");
}

function set_clear(element) {
    element
        .removeClass("correct")
        .removeClass("wrong")
        .addClass("clear")
        .attr("style", "outline: none");
}

function set_correct(element) {
    element
        .removeClass("wrong")
        .removeClass("clear")
        .addClass("correct")
        .attr("style", "outline: none");
}

function check_field() {
    var element = $(this);
    console.log(element.val());
    if(element.val() == "")
    {
        console.log(element.val());
        set_clear(element);
    }
    else
    {
        if(element.attr('id') == 'passport-serial')
        {
            if(/^[0-9]{4}$/i.exec(element.val()))
            {
                console.log('Set correct');
                set_correct(element);
                return;
            }
            else
            {
                console.log('Set wrong');
                set_wrong(element);
            }
        }
        else if(element.attr('id') == 'passport-number')
        {
            if(/^[0-9]{6}$/i.exec(element.val()))
            {
                console.log('Set correct');
                set_correct(element);
                return;
            }
            else
            {
                console.log('Set wrong');
                set_wrong(element);
            }
        }
        else
        {
            console.log('Set wrong');
            set_wrong(element);
        }
    }
}

function yes_no_buttons() {
    return '<div   id="btn-accept-order"' +
        '       class="btn-icon inline center v-bottom">' +
        'Да' +
        '</div>' +
        '<div   id="btn-decline-order"' +
        '       class="btn-icon inline center v-bottom ">' +
        'Нет' +
        '</div>';
}

function hide_confirmation_pop_up() {
    $("#btn-accept-order, #btn-decline-order").remove();
    $("#srv-msg0").slideUp();
    $("#btn-book-order").attr("style", "display: block");
    $("#pop-up-btn0").attr("style", "display: block");
}

function process_order_confirmation() {
    hide_confirmation_pop_up();
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "confirm_order": "true"
        },
        success: function(data) {
            show_order_set(data);
        }
    });
}

function confirm_order() {
    $("#btn-book-order").attr("style", "display: none");
    $("#pop-up-btn0").attr("style", "display: none");
    $("#pop-up-msg0").after(yes_no_buttons());
    $("#btn-accept-order").on("click", process_order_confirmation);
    $("#btn-decline-order").on("click", hide_confirmation_pop_up);
    show_pop_up_success("Оформить заказ?");
}

function show_pop_up_error(text) {
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

function show_pop_up_warning(text) {
    var srv_msg = $("#srv-msg0");
    srv_msg.slideUp();
    $('#pop-up-msg0').text(text);
    srv_msg
        .removeClass("pop-up-error")
        .removeClass("pop-up-success")
        .removeClass("pop-up-info")
        .addClass("pop-up-warning");
    srv_msg.slideDown({ duration: 800, easing: "swing" });

    $("#pop-up-btn0").click(
        function() {
            $("#srv-msg0").slideUp({
                duration: 400,
                easing: "swing"
            });
        }
    );
}

function show_pop_up_success(text) {
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

function on_name_changed(eventHandler) {
    var pointer     = $(this).parent().parent().parent().parent();
    var index       = pointer.index();
    var quantity    = 1;
    if(get_position_type_index(get_position_type(index)))
        quantity = get_position_quantity(index);
    set_receipt_position_name(index);

    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "changed_index": index,
            "changed_name": $(this).val(),
            "changed_quantity": quantity
        },
        success: function(data) {
            set_receipt_position_cost(index, data['item_cost']);
            set_receipt_summary_cost(data['order_cost']);
        }
    });
    eventHandler.stopImmediatePropagation();
}

function set_order_position_fields(index, data) {
    if(data['error']) {
        show_pop_up_error(data['error']);
        return;
    }

    show_order_position_fields(index, data['data']);
    if(get_position_type_index(get_position_type(index)))
    {
        console.log("Attaching event");
        $("input[name='item-quantity']").on("change", on_quantity_changed);
    }
    var type        = get_position_type_index(get_position_type(index));
    var name        = $("li.order-position:eq(" + index + ") " +
                        "select[name='item-select']").val();
    var quantity    = get_position_quantity(index);
    if(!quantity)
        quantity = 1;

    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "changed_type": type,
            "changed_name": name,
            "changed_index": index,
            "changed_quantity": quantity
        },
        success: function (data) {
            console.log("Item cost = " + data['order_cost']);
            console.log("Order cost = " + data['item_cost']);
            set_receipt_position_cost(index, data['item_cost']);
            set_receipt_summary_cost(data['order_cost']);
        }
    });
    set_receipt_position_name(index);
    set_receipt_position_quantity(index);
    add_receipt_position();
    $("select[name='item-select']").change(on_name_changed);
    /* code here for catching changes in blocks */
}

function get_position_fields(type, index) {

    hide_order_position_fields(index);

    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "form_type": type
        },
        success: function(data) {
            set_order_position_fields(index, data);
        }
    });
}

function remove_receipt_position(index) {
    console.log("Removing " + index);
    $("li.receipt-position:eq(" + index + ")").remove();
    var $i = 1;
    jQuery.each($("div.receipt-position-index"), function () {
        $(this).text($i);
        $i++;
    });
    console.log("Removing " + index);
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "removed_index": index
        },
        success: function(data) {
            set_receipt_summary_cost(data['order_cost']);
        }
    });
}

function on_quantity_changed(event_handler) {
    var pointer     = $(this).parent().parent().parent().parent();
    var index       = pointer.index();
    var quantity    = get_position_quantity(index);
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "changed_index": index,
            "changed_quantity": quantity
        },
        success: function (data) {
            console.log("Item cost = " + data['order_cost']);
            console.log("Order cost = " + data['item_cost']);
            set_receipt_position_cost(index, data['item_cost']);
            set_receipt_summary_cost(data['order_cost']);
        }
    });
    if(event_handler)
        event_handler.stopImmediatePropagation();
}

function add_receipt_position() {
    console.log("Receipt part starts here");
    var pointer     = $("li.order-position:last");
    var index       = $("li.order-position").length-1;
    var type        = get_position_type_index(get_position_type(index));
    var name        = pointer.find("select[name='item-select']").val();
    var quantity    = get_position_quantity(index);
    if(!quantity)
        quantity = 1;

    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "changed_type": type,
            "changed_name": name,
            "changed_index": index,
            "changed_quantity": quantity
        },
        success: function (data) {
            console.log("Item cost = " + data['order_cost']);
            console.log("Order cost = " + data['item_cost']);
            set_receipt_position_cost(index, data['item_cost']);
            set_receipt_summary_cost(data['order_cost']);
        }
    });
}

function remove_order_position(position) {
    position.remove();
    if( $("li.order-position").length == '0' )
        set_order_confirm_button(0);
}

function remove_position(eventHandler) {
    var position = $(this).parent().parent();
    console.log(">>>>> Clicked button on position " + position.index());

    remove_receipt_position( position.index() );
    remove_order_position( position );

    eventHandler.stopImmediatePropagation();
}

function remove_all_positions() {
    set_order_confirm_button(0);
    $("ul#orders-list").empty();
    $("ul#receipt-list").empty();
    set_receipt_summary_cost(0);
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "text",
        data: {
            "remove_all": "true"
        },
        success: function (data)
        {
            show_pop_up_success("Корзина успешно очищена");
        }
    });
}

function select_fields_type( eventHandler ) {
    var index = $(this).parent().parent().parent().index();
    set_receipt_position_type(index);
    get_position_fields(
        get_position_type_index($(this).val()),
        index
    );

    eventHandler.stopImmediatePropagation();
}

function add_order_position() {
    console.log("Order part starts here");
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "add_order_position": "true"
        },
        success: function(data)
        {
            order_position_events(data);
        }
    });
}

function order_position_events(data) {
    $("ul#orders-list")
        .append(data['order_data']);
    $("li div#btn-delete-position")
        .on("click", remove_position );
    $("select.order-type")
        .on("change", select_fields_type);


    var new_position = $("li.order-position:last");
    var index = new_position.index();

    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "add_receipt_position": "true"
        },
        success: function (data) {
            $("ul#receipt-list").append(data['receipt_data']);
            set_receipt_position_type(index);
            set_receipt_position_index(index);
        }
    });

    set_order_confirm_button(1);
    get_position_fields(
        get_position_type_index(
            get_position_type(index)
        ),
        index
    );
    new_position.slideDown();
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
    $("#order-client")
        .empty()
        .append(data['data']);
    $("#btn-book-order")
        .attr("style", "display: none;")
        .on("click", confirm_order);
    $("#order-client-info").slideDown();
    $("#orders-list-container").slideDown();
    $("#receipt-client-name").text(
        data['clientName'] + ' ' + data['clientSurname']
    );

    set_receipt_client_data(data);
    
    //show_pop_up_success("Клиент найден. Доступно оформление заказа");
    
    $("#btn-add-position").click(add_order_position);
    $("#btn-remove-all").click(remove_all_positions);
}

function remember_client() {
    $.ajax({
        url: "/php/db/order.php",
        type: "POST",
        dataType: "json",
        data: {
            "remember_order_client": "true"
        },
        success: function(data)
        {
            if(data['error'])
            {
                show_pop_up_error(
                    "Клиент с такими паспортными данными не найден");
                return;
            }
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
            if(data['error'])
            {
                show_pop_up_error(
                    "Клиент с такими паспортными данными не найден");
                $("#order-client-search").slideDown();
                return;
            }
            start_ordering(data);
        }
    });
}

function search_events() {
    $("#passport-serial, #passport-number").keyup(check_field);
    $("#btn-search").click(search_client);
}

function show_order_client_search() {
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

function prepare_ordering() {
    console.log('Previously selected client not found');
    show_order_client_search();
    search_events();
}

function allow_ordering(data) {
    console.log('Found previously selected client with ID = ' + data);
    remember_client();
}

function check_previous_selection() {
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

function search_order_events() {
    $("#btn-search-order").on("click", function(){
        var order_id = $("#order-id-input").val();
        var output_div = $("#order-search-output");
        if(order_id == "")
        {
            show_pop_up_warning(
                "Поле 'ID заказа' пустое!"
            );
            return;
        }
        output_div.slideUp();
        $.ajax({
            url: '/php/db/order.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'get_order_by_that_id': order_id
            },
            success: function (data) {
                output_div.empty().append(data['data']);
                output_div.slideDown();
            }
        });
    });
}

function page_events() {
    check_previous_selection();
    $("#order-search-entities").slideUp();
    $("#order-entities").slideDown().attr("style", "display: inline-block");
    $("#receipt-view").slideDown().attr("style", "display: inline-block");

    $("#btn-add-order").on("click", function(){
        $("#order-search-entities").slideUp();
        $("#order-entities").slideDown().attr("style", "display: inline-block");
        $("#receipt-view").slideDown().attr("style", "display: inline-block");
    });

    $("#btn-view-order").on("click", function(){
        $("#order-search-entities").slideDown();
        $("#order-entities").slideUp();
        search_order_events();
    });
}

$('document').ready(page_events);
