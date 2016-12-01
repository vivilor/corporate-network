<?php

/*
 * <input ...>
 * btn['type'] = 'button', 'submit'
 * btn['value'] = '...'
 * btn['name'] = '...'
 * 
 * 
 * 	  +---------button
 *    |
 *    V
 * 	input,
 * 	button
 *    +
 *    |
 * 	  |
 *    V
 *  type
 *  value
 * 	name
 * 	id
 * 
 */
/*
if(!isset($btn_data))
{
	echo '<div>Button</div>';
	exit();
}
if(isset($btn_data['type']))
{
	if ($btn_data['type'] == 'link')
		$btn_data['type'] = 'a';
	elseif ($btn_data['type'] == 'button')
		true;
	else
		true;
}*/
if(!isset($btn_ref)) $btn_ref = "";
if(!isset($btn_style)) $btn_style = "";
if(!isset($btn_caption)) $btn_caption = "";
if(isset($btn_disabled) && $btn_disabled == 1)
{
	$flag = 1;	$block = 'div';	$attr = ' ';
}
else
{
	$flag = 0;	$block = 'a';	$attr = ' href=' . $btn_ref;
}
?>
<<?=$block . $attr?> class="btn inline" <?=$btn_style?> >
	<span class="btn-caption inline relative">
			<? echo $btn_caption; ?>
	</span>
	<?if($flag) {?><div class="btn-disabled relative"></div><?}?>
</<?=$block?>>
