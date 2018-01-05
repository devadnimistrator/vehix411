<?php
require_once ("library/classes/simple_html_dom.php");
require_once ('config/database_tables.php');
require_once ("library/classes/wp_db.php");
$wpdb = new wpdb(DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, DB_SERVER);

define('AUTOCODES_URL', "https://www.autocodes.com/");

$code_list = array(
	"obd-code-list/powertrain",
	"obd-code-list/network",
	"obd-code-list/body",
	"obd-code-list/chassis"
);

foreach ($code_list as $code_page) {
	get_postcodes(AUTOCODES_URL . $code_page);
}

// get postcodes
function get_postcodes($code_page) {
	global $wpdb;

	echo "\n\n";
	echo "--------------------------------------------------\n";
	echo $code_page . "\n";

	$html = file_get_html($code_page);
	if ($html === false) {
		die('ERROR: ' . $code_page);
	}

	$last_page = 1;
	// get pages
	foreach ($html->find("#pag a") as $page_link) {
		if ($page_link -> innertext == 'Last Page') {
			$last_page_link = parse_url($page_link -> href);
			$params = explode("/", $last_page_link['path']);
			$last_page = $params[3];
		}
	}
	
	for ($page = 1; $page <= $last_page; $page++) {
		echo "Page: " . $page . " / " . $last_page . "\n";

		//$codes = array();
		if ($page > 1) {
			$html = file_get_html($code_page . "/" . $page);

			if ($html === false) {
				die('ERROR: ' . $code_page . "/" . $page);
			}
		}

		$codes_count = 0;
		foreach ($html->find("#scroller ul li") as $code_item) {
			$codes_count++;
			$code_link = $code_item -> firstChild();
			$code_title = $code_item -> lastChild();

			$href = $code_link -> href;
			$temp = parse_url($code_link -> href);
			$path = substr($temp['path'], 1);
			$temp = explode(".", $path);
			$temp = explode("_", $temp[0]);
			$code = array(
				"code" => strtolower($temp[0]),
				"description" => $code_title -> innertext,
				"autocodes_link" => $code_link -> href,
				"status" => 0
			);

			$code_info = $wpdb -> get_row("SELECT * FROM " . TABLE_PRODUCTS . " WHERE `code` = '" . $code['code'] . "' and `description` = \"" . $code['description'] . "\"");
			if ($code_info) {
				
			} else {
				$wpdb -> insert(TABLE_PRODUCTS, $code);
			}
			

			//$codes[] = $code;
		}
	}
}

echo "\n\n";
echo "Finished : " . $wpdb -> get_var("SELECT COUNT(*) FROM " . TABLE_PRODUCTS);
