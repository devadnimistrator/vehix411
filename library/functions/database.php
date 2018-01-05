<?php

function check_reset_password($endpoint_id, $new_password) {
  global $wpdb;
  $passwords = $wpdb->get_results("select * from " . TABLE_OLD_PASSWORD . " where endpoint_id='" . $endpoint_id . "' order by registed desc limit 3");

  foreach ($passwords as $password) {
    if ($password->password == $new_password) {
      return false;
    }
  }

  $wpdb->insert(TABLE_OLD_PASSWORD, array('endpoint_id' => $endpoint_id, 'password' => $new_password, 'registed' => date('Y-m-d H:i:s')));
  return true;
}

function __($text, $param1 = "", $param2 = "", $param3 = "", $param4 = "", $param5 = "") {
  return sprintf($text, $param1, $param2, $param3, $param4, $param5);
}

function tep_db_input($string) {
  global $wpdb;
  return $wpdb->_real_escape($string);
}

function tep_db_prepare_input($string) {
	if (is_string($string)) {
		return trim(tep_sanitize_string(stripslashes($string)));
	} elseif (is_array($string)) {
		reset($string);
		while (list($key, $value) = each($string)) {
			$string[$key] = tep_db_prepare_input($value);
		}
		return $string;
	} else {
		return $string;
	}
}

function teb_get_query($table_name, $where = "1=1", $query="*", $order_by = "") {
  $sql = "select " . $query . " from " . $table_name;
	if (is_array($where)) {
		$sql.= (" where 1=1");
		while (list($columns, $value) = each($where)) {
			$sql .= (" and `".$columns."`='".tep_db_input($value)."'");
		}
	} else {
		$sql.= (" where " . $where); 
	}
	
	if ($order_by != '') {
		$sql.= " order by ". $order_by;
	}
  
  return $sql;
}

function teb_one_query($table_name, $where = "1=1", $query="*", $order_by = "") {
	global $wpdb;
  $sql = teb_get_query($table_name, $where, $query, $order_by);
	
	return $wpdb->get_row($sql);
}

function teb_multi_query($table_name, $where = "1=1", $query="*", $order_by = "") {
	global $wpdb;
  $sql = teb_get_query($table_name, $where, $query, $order_by);
	
	return $wpdb->get_results($sql);
}

function tep_db_insert_id() {
  global $wpdb;
  
  return $wpdb->insert_id;
}

function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
	global $wpdb;
  
  reset($data);
	if ($action == 'insert') {
		return $wpdb->insert($table, $data);
	} elseif ($action == 'update') {
		$query = 'update ' . $table . ' set ';
		while (list($columns, $value) = each($data)) {
			switch ((string)$value) {
				case 'now()':
					$query .= '`'.$columns . '` = now(), ';
					break;
				case 'null':
					$query .= '`'.$columns .= '` = null, ';
					break;
				default:
					$query .= '`'.$columns . '` = \'' . tep_db_input($value) . '\', ';
					break;
			}
		}
		$query = substr($query, 0, -2) . ' where ' . $parameters;
	}

	return $wpdb->query($query);
}
?>