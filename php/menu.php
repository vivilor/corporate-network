<?php

global $menu_list, $menu;
$menu_list = array(
	1 => "Управление",
	2 => "Отчеты",
	3 => "Заказы",
	4 => "Статистика",
	5 => "Выход"
);

$menu = array(
	1 => "management",
	2 => "reports",
	3 => "orders",
	4 => "stat",
	5 => "/"
);

$buttons_list = array(
	'add-report' => 'Создать отчет',
	'add',
	'view' => 'Просмотр отчета',
	'clients',
	'equip',
	'asjuster',
	'delete',
	'change'
);

include_once 'packer.php';

function pack_content_title($content)
{
	return pack_in_paired_tag(
		"div",
		array(
			"class" => "relative content-title segoe-ui big"
		),
		$content
	);
}

function pack_tile_bar()
{
	global $menu_list, $menu;
	$tiles = "";
	$tiles_count = count($menu)-1;

	for ( $i = 1; $i <= $tiles_count; $i++ )
	{
		$tile_caption = pack_in_paired_tag(
			"span",
			array(
				"class" => "tile-caption absolute"
			),
			$menu_list[$i]
		);
		$tile_icon = pack_in_paired_tag(
			"div",
			array(
				"id" => "btn-" . $menu[$i] . "-icon",
				"class" => "tile-icon relative",
			),
			""
		);
		$tiles .= pack_in_paired_tag(
			"a",
			array(
				"href" => ($i == $tiles_count ? "/" : $menu[$i]),
				"class" => "tile inline relative left"
			),
			$tile_icon . $tile_caption
		);
	}
	$tile_bar = pack_in_paired_tag(
		"div",
		array("class" => "tile-bar relative center"),
		$tiles
	);
	return $tile_bar;
}


function pack_button_with_image($link, $title = "", $id = "", $style="")
{
	return pack_in_paired_tag(
		"a",
		array(
			"href" => $link,
			"class" => "btn inline relative " . $style,
			"title" => $title
		),
		pack_in_paired_tag(
			"div",
			array(
				"id" => $id,
				"class" => "btn-icon relative"
			),
			""
		)
	);
}


function pack_side_bar($buttons, $class="")
{
	global $buttons_list;
	$btns = "";
	foreach($buttons as $btn):
		$btns .= pack_in_paired_tag(
			"div",
			array(
				"id" => "btn-" . $btn,
				"class" => "btn side-btn relative" . $class,
				"title" => $buttons_list[$btn]
			),
			pack_in_paired_tag(
				"div",
				array(
					"id" => "btn-" . $btn . "-icon",
					"class" => "btn-icon relative"
				),
				""
			)
		);
	endforeach;
	return pack_in_paired_tag(
		"div",
		array(
			"class" => "side-bar"
		),
		$btns
	);
}


function pack_button_bar($exit_btn_only=0, $back_button_show=0)
{
	global $menu_list, $menu;
	$btns = "";

	if($back_button_show)
	{
		$btns .= pack_in_paired_tag(
			"a",
			array(
				"href" => '/cp/',
				"class" => "btn inline relative ",
				"title" => "Вернуться в панель управления"
			),
			pack_in_paired_tag(
				"div",
				array(
					"id" => "btn-back-icon",
					"class" => "btn-icon relative"
				),
				""
			)/*$menu_list[$i]*/
		);
	}
	if(!$exit_btn_only)
	{
		for ( $i = 1; $i <= count($menu)-1; $i++ )
		{
			if($_SESSION['priv'][$menu[$i]] == '0')
				continue;
			$btns .= pack_in_paired_tag(
				"a",
				array(
					"href" => '/cp/'. $menu[$i],
					"class" => "btn inline relative ",
					"title" => $menu_list[$i]
				),
				pack_in_paired_tag(
					"div",
					array(
						"id" =>
							"btn-" .
							($menu[$i] == "/" ? "logout" : $menu[$i]) .
							"-icon",
						"class" => "btn-icon relative"
					),
					""
				)/*$menu_list[$i]*/
			);
		}
	}

	$btns .= pack_in_paired_tag(
		"a",
		array(
			"href" => "/?exit=1",
			"class" => "btn inline relative rightfloat",
			"title" => $menu_list[count($menu)]
		),
		pack_in_paired_tag(
			"div",
			array(
				"id" => "btn-logout-icon",
				"class" => "btn-icon relative"
			),
			""
		)/*$menu_list[$i]*/ /*$menu_list[count($menu)]/*/
	);
	$btn_bar = pack_in_paired_tag(
		"div",
		array(
			"class" => "btn-bar relative" .
				($exit_btn_only ? "" : ""),
		),
		$btns
	);
	return $btn_bar;
}
