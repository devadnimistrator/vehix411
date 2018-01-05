<?php 
if (!isset($table_headers)) die('error page');

global $table_headers;
$curent_url = $_SERVER['SCRIPT_NAME'];

$params = "";
foreach ($_POST as $p_name => $p_value) {
	if ($p_name != 'sort_column' && $p_name != 'sort_order') {
		if ($params == "") 	$params .= "?";
		else				$params .= "&";
		
		$params.= ($p_name."=".$p_value);
	}
}

foreach ($_GET as $p_name => $p_value) {
	if ($p_name != 'sort_column' && $p_name != 'sort_order') {
		if ($params == "") 	$params .= "?";
		else				$params .= "&";
		
		$params.= ($p_name."=".$p_value);
	}
}
?>

<thead>
	<tr class="headerRow">
<?php 
for ($i = 0; $i < count($table_headers); $i ++) {
?>
	<th width="<?= $table_headers[$i]['width']?>">
	<?php if ($table_headers[$i]['id'] == '') : ?>
		<?= $table_headers[$i]['title']?>
	<?php elseif ($sort_column == $table_headers[$i]['id']) : ?>
		<a href="<?= $curent_url.$params.($params == '' ? '?' : '&').'sort_column='.$table_headers[$i]['id'].'&sort_order='.($sort_order == 'ASC' ? 'DESC' : 'ASC')?>" title="click <?php ($sort_order == 'ASC' ? 'de' : '')?>sort">
			<?= $table_headers[$i]['title']?>
			<?= ($sort_order == 'ASC' ? '&#9650;' : '&#9660;')?>
		</a>
	<?php else : ?>
		<a href="<?= $curent_url.$params.($params == '' ? '?' : '&').'sort_column='.$table_headers[$i]['id']?>" title="click sort">
			<?= $table_headers[$i]['title']?>
		</a>
	<?php endif; ?>
	</th>
<?php 
}
?>
	</tr>
	
	<tr>
    	<td colspan="<?= $column_count?>"  height=1 ></td>
	</tr>
</thead>