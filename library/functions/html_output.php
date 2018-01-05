<?php
function tep_show_msg($msg, $type = 'info', $output = true) {
	$html = '<div class="alert alert-info alert-' . $type . ' alert-dismissible fade in" role="alert">';
	$html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
	$html .= $msg;
	$html .= '</div>';
	if ($output) {
		echo $html;
	} else {
		return $html;
	}
}
?>