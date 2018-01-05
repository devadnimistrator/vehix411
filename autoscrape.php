<?php
require_once ("library/classes/simple_html_dom.php");
require_once ('config/database_tables.php');
require_once ("library/classes/wp_db.php");
$wpdb = new wpdb(DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, DB_SERVER);

define('AUTOCODES_URL', "https://www.autocodes.com/");

// get makes
$html = file_get_html(AUTOCODES_URL);
if ($html === false) {
	die('ERROR: ' . AUTOCODES_URL);
}
$makes = array();
foreach ($html->find("#container table tbody tr td.index-list a") as $tag_link) {
	$href = $tag_link -> href;
	if (strpos($href, AUTOCODES_URL . "make") === 0) {
		$temp = explode(",", $tag_link -> title);
		$make = array(
			"name" => $tag_link -> innertext,
			"code" => substr($href, strlen(AUTOCODES_URL . "make") + 1),
			"description" => $temp[0],
			"scrapped_page" => 0,
			"status" => 0
		);

		$old_make = $wpdb -> get_row("SELECT * FROM " . TABLE_MAKES . " WHERE `name` = \"" . $make['name'] . "\"");
		if ($old_make) {
			$make['id'] = $old_make->ID;
			$make['status'] = $old_make->status;
			$make['scrapped_page'] = $old_make->scrapped_page;
		} else {
			$wpdb -> insert(TABLE_MAKES, $make);
			$make['id'] = $wpdb -> insert_id;
		}
		$make['href'] = $tag_link -> href;

		$makes[] = $make;
	}
}

foreach ($makes as $make) {
	if ($make['status'] == 1)
		continue;

	get_postcodes($make);
}

// get postcodes
function get_postcodes($make) {
	global $wpdb;

	echo "\n\n";
	echo "--------------------------------------------------\n";
	echo $make['description'] . "\n";

	$html = file_get_html($make['href']);
	if ($html === false) {
		die('ERROR: ' . $make['href']);
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

		if ($make['scrapped_page'] >= $page) {
			continue;
		}

		//$codes = array();
		if ($page > 1) {
			$html = file_get_html($make['href'] . "/" . $page);

			if ($html === false) {
				die('ERROR: ' . $make['href'] . "/" . $page);
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
				"make_id" => $make['id'],
				"code" => strtolower($temp[0]),
				"description" => $code_title -> innertext,
				"autocodes_link" => $code_link -> href,
				"status" => 0
			);

			$code_info = $wpdb -> get_row("SELECT * FROM " . TABLE_PRODUCTS . " WHERE `code` = '" . $code['code'] . "' and `description` = \"" . $code['description'] . "\"");
			if ($code_info) {
				$code['id'] = $code_info -> ID;

				$make_ids = explode(",", $code_info -> make_id);
				if (in_array($code['make_id'], $make_ids)) {

				} else {
					$make_ids[] = $code['make_id'];
					$wpdb -> update(TABLE_PRODUCTS, array("make_id" => implode(",", $make_ids)), array("ID" => $code['id']));
				}
			} else {
				$wpdb -> insert(TABLE_PRODUCTS, $code);
				$code['id'] = $wpdb -> insert_id;
			}

			//$codes[] = $code;
		}

		if ($codes_count > 0) {
			$make['scrapped_page'] = $page;
			$wpdb -> update(TABLE_MAKES, array("scrapped_page" => $page), array("ID" => $make['id']));
		}
	}

	if ($make['scrapped_page'] == $last_page) {
		$wpdb -> update(TABLE_MAKES, array("status" => 1), array("ID" => $make['id']));
	}
}

echo "\n\n";
echo "Finished : " . $wpdb -> get_var("SELECT COUNT(*) FROM " . TABLE_PRODUCTS);
