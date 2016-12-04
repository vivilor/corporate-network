<?php

global $menu_list, $menu;
$menu_list = array(
	1 => "УПРАВЛЕНИЕ",
	2 => "ОТЧЕТЫ",
	3 => "ЗАКАЗЫ",
	4 => "СТАТИСТИКА",
	5 => "ВЫХОД"
);

$menu = array(
	1 => "management",
	2 => "reports",
	3 => "orders",
	4 => "stat",
	5 => "/"
);

include_once 'packer.php';

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
				"id" => $menu[$i] . "-icon",
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

function pack_button_bar($exit_btn_only)
{
	global $menu_list, $menu;
	$btns = "";
	if ($exit_btn_only)
	{
		$btns .= pack_in_paired_tag(
			"a",
			array(
				"href" => $menu[count($menu)],
				"class" => "btn inline relative rightfloat"
			),
			$menu_list[count($menu)]
		);
	}
	else
	{
		for ( $i = 1; $i <= count($menu); $i++ )
		{
			$btns .= pack_in_paired_tag(
				"a",
				array(
					"href" => $i == count($menu) ? "/" : '/cp/'. $menu[$i],
					"class" => "btn inline relative" . (
						$i == count($menu) ? "leftfloat" : "")
				),
				$menu_list[$i]
			);
		}
	}
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