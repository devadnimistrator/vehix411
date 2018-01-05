<?php
require_once ("library/classes/simple_html_dom.php");
require_once ('config/database_tables.php');
require_once ("library/classes/wp_db.php");
$wpdb = new wpdb(DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, DB_SERVER);

define('AUTOCODES_URL', "https://www.autocodes.com/");

$model_years_html = '<details class="">
			<summary>Ford F150</summary>
				<a href="https://www.autocodes.com/year/1998/ford/f150" title="1998 Ford F150 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1998 Ford F150</a>
				<br><a href="https://www.autocodes.com/year/1999/ford/f150" title="1999 Ford F150 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1999 Ford F150</a>
				<br><a href="https://www.autocodes.com/year/2000/ford/f150" title="2000 Ford F150 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2000 Ford F150</a>
				<br><a href="https://www.autocodes.com/year/2001/ford/f150" title="2001 Ford F150 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2001 Ford F150</a>
				<br><a href="https://www.autocodes.com/year/2002/ford/f150" title="2002 Ford F150 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2002 Ford F150</a>
				<br><a href="https://www.autocodes.com/year/2003/ford/f150" title="2003 Ford F150 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2003 Ford F150</a>
		</details>
		<details class="">
			<summary>Ford Fusion</summary>
				<a href="https://www.autocodes.com/year/2006/ford/fusion" title="2006 Ford Fusion OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Ford Fusion</a>
				<br><a href="https://www.autocodes.com/year/2007/ford/fusion" title="2007 Ford Fusion OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Ford Fusion</a>
				<br><a href="https://www.autocodes.com/year/2008/ford/fusion" title="2008 Ford Fusion OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Ford Fusion</a>
				<br><a href="https://www.autocodes.com/year/2009/ford/fusion" title="2009 Ford Fusion OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Ford Fusion</a>
				<br><a href="https://www.autocodes.com/year/2010/ford/fusion" title="2010 Ford Fusion OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2010 Ford Fusion</a>
				<br><a href="https://www.autocodes.com/year/2011/ford/fusion" title="2011 Ford Fusion OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2011 Ford Fusion</a>
				<br><a href="https://www.autocodes.com/year/2012/ford/fusion" title="2012 Ford Fusion OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2012 Ford Fusion</a>
		</details>
		<details class="">
			<summary>Honda Accord</summary>
				<a href="https://www.autocodes.com/year/2008/honda/accord" title="2008 Honda Accord OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Honda Accord</a>
				<br><a href="https://www.autocodes.com/year/2009/honda/accord" title="2009 Honda Accord OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Honda Accord</a>
				<br><a href="https://www.autocodes.com/year/2010/honda/accord" title="2010 Honda Accord OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2010 Honda Accord</a>
		</details>
		<details class="">
			<summary>Honda Civic</summary>
				<a href="https://www.autocodes.com/year/1996/honda/civic" title="1996 Honda Civic OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1996 Honda Civic</a>
				<br><a href="https://www.autocodes.com/year/1997/honda/civic" title="1997 Honda Civic OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1997 Honda Civic</a>
				<br><a href="https://www.autocodes.com/year/1998/honda/civic" title="1998 Honda Civic OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1998 Honda Civic</a>
				<br><a href="https://www.autocodes.com/year/1999/honda/civic" title="1999 Honda Civic OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1999 Honda Civic</a>
				<br><a href="https://www.autocodes.com/year/2000/honda/civic" title="2000 Honda Civic OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2000 Honda Civic</a>
		
		</details>
		<details class="">
			<summary>Infiniti G35</summary>
				<a href="https://www.autocodes.com/year/2003/infiniti/g35" title="2003 Infiniti G35 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2003 Infiniti G35</a>
				<br><a href="https://www.autocodes.com/year/2004/infiniti/g35" title="2004 Infiniti G35 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2004 Infiniti G35</a>
				<br><a href="https://www.autocodes.com/year/2005/infiniti/g35" title="2005 Infiniti G35 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Infiniti G35</a>
				<br><a href="https://www.autocodes.com/year/2006/infiniti/g35" title="2006 Infiniti G35 OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Infiniti G35</a>
		</details>
		<details class="">
			<summary>Nissan Altima</summary>
				<a href="https://www.autocodes.com/year/2002/nissan/altima_sedan" title="2002 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2002 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2003/nissan/altima_sedan" title="2003 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2003 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2004/nissan/altima_sedan" title="2004 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2004 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2005/nissan/altima_sedan" title="2005 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2006/nissan/altima_sedan" title="2006 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2008/nissan/altima_sedan" title="2008 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2009/nissan/altima_sedan" title="2009 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2010/nissan/altima_sedan" title="2010 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2010 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2011/nissan/altima_sedan" title="2011 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2011 Nissan Altima</a>
				<br><a href="https://www.autocodes.com/year/2012/nissan/altima_sedan" title="2012 Nissan Altima Sedan OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2012 Nissan Altima</a>
		
		</details>
		<details class="">
			<summary>Nissan Maxima</summary>
				<a href="https://www.autocodes.com/year/2005/nissan/maxima" title="2005 Nissan Maxima OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Nissan Maxima</a>
				<br><a href="https://www.autocodes.com/year/2006/nissan/maxima" title="2006 Nissan Maxima OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Nissan Maxima</a>
				<br><a href="https://www.autocodes.com/year/2007/nissan/maxima" title="2007 Nissan Maxima OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Nissan Maxima</a>
				<br><a href="https://www.autocodes.com/year/2008/nissan/maxima" title="2008 Nissan Maxima OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Nissan Maxima</a>
		
		</details>
		<details class="">
			<summary>Nissan Pathfinder</summary>
				<a href="https://www.autocodes.com/year/2005/nissan/pathfinder" title="2005 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Nissan Pathfinder</a>
				<br><a href="https://www.autocodes.com/year/2006/nissan/pathfinder" title="2006 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Nissan Pathfinder</a>
				<br><a href="https://www.autocodes.com/year/2007/nissan/pathfinder" title="2007 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Nissan Pathfinder</a>
				<br><a href="https://www.autocodes.com/year/2008/nissan/pathfinder" title="2008 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Nissan Pathfinder</a>
				<br><a href="https://www.autocodes.com/year/2009/nissan/pathfinder" title="2009 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Nissan Pathfinder</a>
				<br><a href="https://www.autocodes.com/year/2010/nissan/pathfinder" title="2010 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2010 Nissan Pathfinder</a>
				<br><a href="https://www.autocodes.com/year/2011/nissan/pathfinder" title="2011 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2011 Nissan Pathfinder</a>
				<br><a href="https://www.autocodes.com/year/2012/nissan/pathfinder" title="2012 Nissan Pathfinder OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2012 Nissan Pathfinder</a>
		</details>
		<details class="">
			<summary>Nissan Rogue</summary>
				<a href="https://www.autocodes.com/year/2008/nissan/rogue" title="2008 Nissan Rogue OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Nissan Rogue</a>
				<br><a href="https://www.autocodes.com/year/2009/nissan/rogue" title="2009 Nissan Rogue OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Nissan Rogue</a>
				<br><a href="https://www.autocodes.com/year/2010/nissan/rogue" title="2010 Nissan Rogue OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2010 Nissan Rogue</a>
				<br><a href="https://www.autocodes.com/year/2011/nissan/rogue" title="2011 Nissan Rogue OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2011 Nissan Rogue</a>
				<br><a href="https://www.autocodes.com/year/2012/nissan/rogue" title="2012 Nissan Rogue OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2012 Nissan Rogue</a>
				<br><a href="https://www.autocodes.com/year/2013/nissan/rogue" title="2013 Nissan Rogue OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2013 Nissan Rogue</a>
		</details>
		<details class="">
			<summary>Nissan Sentra</summary>
				<a href="https://www.autocodes.com/year/2002/nissan/sentra" title="2002 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2002 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2003/nissan/sentra" title="2003 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2003 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2004/nissan/sentra" title="2004 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2004 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2005/nissan/sentra" title="2005 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2006/nissan/sentra" title="2006 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2007/nissan/sentra" title="2007 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2008/nissan/sentra" title="2008 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2009/nissan/sentra" title="2009 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2010/nissan/sentra" title="2010 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2010 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2011/nissan/sentra" title="2011 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2011 Nissan Sentra</a>
				<br><a href="https://www.autocodes.com/year/2012/nissan/sentra" title="2012 Nissan Sentra OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2012 Nissan Sentra</a>
		</details>
		<details class="">
			<summary>Toyota Camry</summary>
				<a href="https://www.autocodes.com/year/1997/toyota/camry" title="1997 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1997 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/1998/toyota/camry" title="1998 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1998 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/1999/toyota/camry" title="1999 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">1999 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2000/toyota/camry" title="2000 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2000 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2001/toyota/camry" title="2001 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2001 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2006/toyota/camry" title="2006 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2007/toyota/camry" title="2007 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2008/toyota/camry" title="2008 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2009/toyota/camry" title="2009 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2010/toyota/camry" title="2010 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2010 Toyota Camry</a>
				<br><a href="https://www.autocodes.com/year/2011/toyota/camry" title="2011 Toyota Camry OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2011 Toyota Camry</a>
		</details>
		<details class="">
			<summary>Toyota Corolla</summary>
				<a href="https://www.autocodes.com/year/2005/toyota/corolla" title="2005 Toyota Corolla OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Toyota Corolla</a>
		</details>
		<details class="">
			<summary>Toyota Echo</summary>
				<a href="https://www.autocodes.com/year/2000/toyota/echo" title="2000 Toyota Echo OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2000 Toyota Echo</a>
				<br><a href="https://www.autocodes.com/year/2001/toyota/echo" title="2001 Toyota Echo OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2001 Toyota Echo</a>
				<br><a href="https://www.autocodes.com/year/2002/toyota/echo" title="2002 Toyota Echo OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2002 Toyota Echo</a>
		</details>
		<details class="">
			<summary>Toyota Highlander</summary>
				<a href="https://www.autocodes.com/year/2001/toyota/highlander" title="2001 Toyota Highlander OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2001 Toyota Highlander</a>
				<br><a href="https://www.autocodes.com/year/2002/toyota/highlander" title="2002 Toyota Highlander OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2002 Toyota Highlander</a>
				<br><a href="https://www.autocodes.com/year/2003/toyota/highlander" title="2003 Toyota Highlander OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2003 Toyota Highlander</a>
				<br><a href="https://www.autocodes.com/year/2004/toyota/highlander" title="2004 Toyota Highlander OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2004 Toyota Highlander</a>
				<br><a href="https://www.autocodes.com/year/2005/toyota/highlander" title="2005 Toyota Highlander OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Toyota Highlander</a>
				<br><a href="https://www.autocodes.com/year/2006/toyota/highlander" title="2006 Toyota Highlander OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Toyota Highlander</a>
				<br><a href="https://www.autocodes.com/year/2007/toyota/highlander" title="2007 Toyota Highlander OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Toyota Highlander</a>
		</details>
		<details class="">
			<summary>Toyota Prius</summary>
				<a href="https://www.autocodes.com/year/2004/toyota/prius" title="2004 Toyota Prius OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2004 Toyota Prius</a>
				<br><a href="https://www.autocodes.com/year/2005/toyota/prius" title="2005 Toyota Prius OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Toyota Prius</a>
				<br><a href="https://www.autocodes.com/year/2006/toyota/prius" title="2006 Toyota Prius OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Toyota Prius</a>
				<br><a href="https://www.autocodes.com/year/2007/toyota/prius" title="2007 Toyota Prius OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Toyota Prius</a>
				<br><a href="https://www.autocodes.com/year/2008/toyota/prius" title="2008 Toyota Prius OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2008 Toyota Prius</a>
				<br><a href="https://www.autocodes.com/year/2009/toyota/prius" title="2009 Toyota Prius OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2009 Toyota Prius</a>
		</details>
		<details class="">
			<summary>Scion xB</summary>
				<a href="https://www.autocodes.com/year/2005/scion/xb" title="2005 Scion xB OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2005 Scion xB</a>
				<br><a href="https://www.autocodes.com/year/2006/scion/xb" title="2006 Scion xB OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2006 Scion xB</a>
				<br><a href="https://www.autocodes.com/year/2007/scion/xb" title="2007 Scion xB OBD2-OBDII Codes Definition, Description and Repair Information  | AutoCodes.com">2007 Scion xB</a>
		</details>';

$html = str_get_html($model_years_html);
$page_links = array();
foreach ($html->find("a") as $link_tag) {
	$make_model = explode(" ", $link_tag -> innertext);
	$year = $make_model[0];
	$make = $make_model[1];
	$model = $make_model[2];

	$make_id = $wpdb -> get_var("SELECT `ID` FROM " . TABLE_MAKES . " WHERE `name` = '" . $make . "'");
	$model_id = $wpdb -> get_var("SELECT `ID` FROM " . TABLE_MODELS . " WHERE `name` = '" . $model . "' and make_id=" . $make_id);

	$page_links[] = array(
		'text' => $link_tag -> innertext,
		'href' => $link_tag -> href,
		'make_id' => $make_id,
		'model_id' => $model_id,
		'year' => $year
	);
}

foreach ($page_links as $page_link) {
	echo $page_link['text'] . "\n";

	$html = file_get_html($page_link['href']);
	if ($html === false) {
		die('ERROR: ' . $page_link['href']);
	}

	$last_page = 1;
	// get pages
	foreach ($html->find("#pag a") as $link_tag) {
		if ($link_tag -> innertext == 'Last Page') {
			$last_page_link = parse_url($link_tag -> href);
			$params = explode("/", $last_page_link['path']);
			$last_page = $params[5];
		}
	}

	for ($page = 1; $page <= $last_page; $page++) {
		echo "Page: " . $page . " / " . $last_page . "\n";

		//$codes = array();
		if ($page > 1) {
			$html = file_get_html($page_link['href'] . "/" . $page);

			if ($html === false) {
				die('ERROR: ' . $page_link['href'] . "/" . $page);
			}
		}

		foreach ($html->find("#scroller ul li") as $code_item) {
			$code_link = $code_item -> firstChild();
			$code_title = $code_item -> lastChild();

			$href = $code_link -> href;
			$temp = parse_url($code_link -> href);
			$path = substr($temp['path'], 1);
			$temp = explode(".", $path);
			$temp = explode("_", $temp[0]);

			$code = strtolower($temp[0]);
			$codes = $wpdb -> get_results("SELECT * FROM " . TABLE_PRODUCTS . " WHERE `code` = '" . $code . "' and CONCAT(',', `make_id`, ',') LIKE '%," . $page_link['make_id'] . ",%'");
			foreach ($codes as $code) {
				$years = $code->year == '' ? array() : explode(",", $code->year);
				$models = $code->model_id == '' ? array() : explode(",", $code->model_id);
				
				if (in_array($page_link['year'], $years)) {
					
				} else {
					$years[] = $page_link['year'];
					$wpdb -> update(TABLE_PRODUCTS, array("year" => implode(",", $years)), array("ID" => $code->ID));
				}
				
				if (in_array($page_link['model_id'], $models)) {
					
				} else {
					$models[] = $page_link['model_id'];
					$wpdb -> update(TABLE_PRODUCTS, array("model_id" => implode(",", $models)), array("ID" => $code->ID));
				}
			}
		}
	}
}
