<?php

require_once 'packer.php';
require_once 'menu.php';
require_once 'form.php';


function pack_orders_search($content)
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "orders-search",
            "class" => "orders-search"
        ),
        pack_order_view($content)
    );
}

function pack_order_info($info)
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "order-info",
            "class" => "order-info"
        ),
        pack_in_paired_tag(
            "div",
            array(
                "id" => "order-id",
                "class" => "order-id large bold segoe-ui"
            ),
            $info['orderID']
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "id" => "order-client",
                "class" => "text small segoe-ui"
            ),
            "Заказчик: " .
            $info['clientName'] . " " .
            $info['clientSurname'] .
            " (ID: " .$info['orderClientID'] . ")"
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "id" => "order-item",
                "class" => "text small segoe-ui"
            ),
            ($info['orderType'] ?
                "Оборудование:  " :
                "Услуга: ") .
            $info['itemDesc'] .
            " (ID: " . $info['orderItemID'] . ")"
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "id" => "order-date",
                "class" => "text small segoe-ui"
            ),
            "Дата заказа: " .
            $info['DAY'] . "." .
            $info['MONTH'] . "." .
            $info['YEAR']
        ) .
        (isset($info['technitianID']) ?
            pack_in_paired_tag(
                "div",
                array(
                    "id" => "order-executor",
                    "class" => "text small segoe-ui"
                ),
                "Назначенный монтажник: " .
                $info['technitianName'] .
                " (ID: " . $info['orderTechnitianID'] . ")"
            ) :
            ""
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "id" => "order-cost",
                "class" => "text big medium segoe-ui"
            ),
            "Стоимость: " .
            $info['orderCost'] . " руб."
        )
    );
}


function pack_order_type_selection()
{
    return pack_form_row(
        pack_text("Тип: ", "", "small h-space") .
        pack_select_field(array(
            "Услуга",
            "Оборудование"
            ),
            "",
            "order-type small order-select-box")
    );
}

function pack_order_client_search()
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "order-client-search",
            "class" => "order-client-search hidden",
        ),
        pack_form_row(
            pack_text(
                "Поиск клиента (только по паспорту)", "",
                "h-space"
            )
        ) .
        pack_form_row(
            pack_text_field(
                "passport-serial",
                "Серия",
                "inline v-center"
            ) .
            pack_text_field(
                "passport-number",
                "Номер",
                "inline v-center"
            ) .
            pack_side_bar(array(
                "search"
                ),
                "inline v-center"
            ),
            "h-space"
        )
    );
}

function pack_order_client_info($client_info)
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "order-client-info",
            "class" => "order-client-info hidden",
        ),
        pack_form_row(
            pack_text(
                "Клиент-заказчик: ", "", "h-space"
            ) .
            pack_text(
                $client_info['clientName'] . " " .
                $client_info['clientSurname'],
                "", " regular"
            ) .
            pack_upper_text(
                $client_info['clientID'], 1, "", "absolute turned-on order-client-id"
            )
        )
    );
}

function pack_order_client($content="")
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "order-client",
            "class" => "order-client",
        ),
        $content
    );
}


function pack_order_position_fields($content=array())
{
    if(!isset($content['type']))
        return "";
    $data = "";
    if($content['type'] == '1')
    {
        $data = pack_form_row(
            pack_text("Выберите из списка", "", "small") .
            pack_select_field(
                $content['options'],
                "item-select",
                "order-select-box") .
                ($content['type'] == '1' ?
                    pack_text_field(
                        "item-quantity", "",
                        "order-item-quantity",
                        "Кол-во") :
                    ""
                ),
            "h-space"
            ) .
            ($content['type'] == '1' ?
                pack_form_row(
                    pack_text_field(
                        "technitian-id",
                        'Введите ID',
                        "order-ids-fields",
                        "ID Монтажника: "
                ),
                "h-space") :
                ""
        );
    }
    else
    {
        $data = pack_form_row(
            pack_text("Выберите из списка", "", "small") .
            pack_select_field($content['options'], "item-select", "order-select-box"),
            "h-space"
        );
    }
    return pack_in_paired_tag(
        "div",
        array(
            "class" => "order-position-fields hidden"
        ),
        $data
    );
}


function pack_order_position($content="")
{
    return pack_in_paired_tag(
        "li",
        array(
            "class" => "order-position hidden",
        ),
        pack_side_bar(array(
                "delete-position"
            ),
            "rightfloat order-position-btn"
            ) .
        pack_order_type_selection() .
        $content
    );
}

/* Packers for global blocks of page "Orders" */

function pack_order_entities($content)
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "order-entities",
            "class" => "order-entities relative inline v-top",
        ),
        $content
    );
}

function pack_order_view($content)
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "order-view",
            "class" => "order-view relative inline v-top",
        ),
        $content
    );
}

function pack_order_positions_list()
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "orders-list-container",
            "class" => "orders-list-container hidden relative"
        ),
        pack_form_row(
            pack_text("Список заказа", "", "h-space center") .
            pack_side_bar(array(
                    "book-order",
                    "add-position",
                    "remove-all"
                ),
                "absolute order-list-btns"
            ),
            "light"
        ) .
        pack_in_paired_tag(
            "ul",
            array(
                "id" => "orders-list",
                "class" => "orders-list",
            ),
            ""
        )
    );
}

function pack_receipt_view($content="")
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "receipt-view",
            "class" => "receipt-view relative inline v-top",
        ),
        $content
    );
}

?>
