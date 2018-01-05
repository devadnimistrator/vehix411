<?php
require_once ("library/classes/simple_html_dom.php");
require_once ('config/database_tables.php');
require_once ("library/classes/wp_db.php");
$wpdb = new wpdb(DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, DB_SERVER);

define('AUTOCODES_URL', "https://www.autocodes.com/");

$all_code_count = $wpdb -> get_var("SELECT COUNT(*) FROM " . TABLE_PRODUCTS . " WHERE status=0 AND autocodes_link IS NOT NULL");
$page_num = ceil($all_code_count / 1000);

for ($page = 0; $page < $page_num; $page ++) {
	$start_index =  $page * 1000;
	$codes = $wpdb->get_results("SELECT * FROM " . TABLE_PRODUCTS . " WHERE status=0 AND autocodes_link IS NOT NULL LIMIT 0, 1000");
	
	$completed_index = 0;
	foreach ($codes as $code) {
		$completed_index ++;
		$html = file_get_html($code->autocodes_link);
		if ($html === false) {
			die('ERROR: ' . AUTOCODES_URL);
		}
		
		$h2_tags = array();
		$tag_index = 0;
		$possible_causes = 0;
		foreach ($html->find("#content h2.code") as $h2_tag) {
			$h2_tags[] = $h2_tag->innertext;
			
			if (strtolower($h2_tag->innertext) == 'possible causes') {
				$possible_causes = $tag_index;
			} 
			
			$tag_index ++;
		}
		
		$code_infos = array();
		$tag_index = -1;
		foreach ($html->find("#content div.info_code") as $div_tag) {
			$tag_index ++;
			if ($tag_index == $possible_causes) {
				$possible_causes = strip_tags($div_tag->firstChild()->innertext);
				
				break;
			}
		}
		
		$wpdb->update(TABLE_PRODUCTS, array("possible_causes" => $possible_causes, "status" => 1), array("ID" => $code->ID));
		
		echo ($start_index + $completed_index) . " / " . $all_code_count;
		echo "\n";
	}
} 
