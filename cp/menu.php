<!--
<ul class="menu-panel relative">
-->
    <?php
    $menu_list = array( 1 => "КЛИЕНТЫ",
                        2 => "УСЛУГИ",
                        3 => "ОБОРУДОВАНИЕ",
                        4 => "ОТЧЕТЫ",
                        5 => "СТАТИСТИКА",
                        6 => "ВЫХОД");
    $menu = array(	1 => "clients",
					2 => "services",
					3 => "equip",
					4 => "reports",
					5 => "stat",
					6 => "exit"	);
    for ($i = 1; $i <= 6; $i++)
    {?>
    <div class="menu-item v-top inline relative"
		 style=<?=($i == 6 ? "float:right" : "")?>>
        <a href="<?=$menu[$i]?>index.php" class="btn inline relative">
            <!--<div id="<?=$menu[$i]?>-btn-icon"
                 class="btn-icon v-top inline relative" ></div >-->
            <span class="segoe-ui h14pt btn-caption inline relative">
                <?echo $menu_list[$i];?>
            </span>
        </a>
    </div>
    <?php
    }
    ?>
<!--
</ul>
-->