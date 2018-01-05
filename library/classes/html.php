<?php

define('BSFORM_HIDDEN', 'hidden');
define('BSFORM_TEXT', 'text');
define('BSFORM_URL', 'url');
define('BSFORM_PASSWORD', 'password');
define('BSFORM_FILE', 'file');
define('BSFORM_SELECT', 'select');
define('BSFORM_RADIO', 'radio');
define('BSFORM_CHECKBOX', 'checkbox');
define('BSFORM_TEXTAREA', 'textarea');
define('BSFORM_HTML', 'html');
define('BSFORM_DATE', 'date');

class bs_FORM {
	var $name = "";
	var $action = "";
	var $method = "";
	var $autocomplete = true;
	var $target = "";
	var $is_fileupload = false;

	var $elements = array();
	var $buttons = array(
		array(
			"type" => "reset",
			"value" => "Reset"
		),
		array(
			"type" => "submit",
			"value" => "Submit"
		)
	);

	function __construct($name, $action, $method = 'post', $autocomplete = true, $emelents = array(), $buttons = array()) {
		$this -> name = $name;
		$this -> action = $action;
		$this -> method = $method;
		$this -> autocomplete = $autocomplete;

		$this -> elements = $emelents;
		if (count($buttons) > 0) {
			$this -> buttons = $buttons;
		}
	}

	function set_is_fileupload() {
		$this -> is_fileupload = true;
	}

	function set_target($target) {
		$this -> target = $target;
	}

	function add_element($name, $type, $value = "", $label = "", $required = true, $options = array(), $isMulty = false) {
		$this -> elements[] = array(
			"label" => $label,
			"name" => $name,
			"type" => $type,
			"value" => $value,
			"required" => $required,
			"options" => $options,
			"isMulty" => $isMulty
		);
	}

	function add_buttons($type, $value) {
		$this -> buttons[] = array(
			"type" => $type,
			"value" => $value
		);
	}

	function form_start($print = false) {
		$html = '<form';
		if ($this -> name) {
			$html .= ' id="form-' . $this -> name . '"';
			$html .= ' name="' . $this -> name . '"';
		}
		if ($this -> action) {
			$html .= ' action="' . $this -> action . '"';
		}
		if ($this -> target) {
			$html .= ' target="' . $this -> target . '"';
		}
		$html .= ' class="form-horizontal form-label-left validateform" novalidate method="' . $this -> method . '" autocomplete="' . ($this -> autocomplete ? 'on' : 'off') . '"';
		if ($this -> is_fileupload) {
			$html .= ' enctype="multipart/form-data"';
		}
		$html .= ' >';
		$html .= "\n";

		if ($print === TRUE) {
			echo $html;
		} else {
			return $html;
		}
	}

	function form_elements($print = false) {
		$html = "";
		foreach ($this->elements as $element) {
			if ($element['type'] == BSFORM_HIDDEN || $element['type'] == BSFORM_HTML) {
				$html .= $this -> {'generate_' . $element['type']}($element);
			} else {
				$html .= '<div class="item form-group" id="form-group-' . $element['name'] . '">' . "\n";
				$html .= '<label class="control-label col-md-2 col-sm-3 col-xs-12" for="old_password">';
				$html .= $element['label'] != '' ? $element['label'] : ucfirst($element['name']);
				if ($element['required']) {
					$html .= ' <span class="required">*</span>';
				}
				$html .= '</label>' . "\n";
				$html .= '<div class="col-md-10 col-sm-9 col-xs-12 control-input">' . "\n";
				$html .= $this -> {'generate_' . $element['type']}($element);
				$html .= "\n";
				$html .= '</div>';
				$html .= "\n";
				$html .= '</div>';
			}
			$html .= "\n";
		}

		if ($print === TRUE) {
			$this -> elements = array();

			echo $html;
		} else {
			$this -> elements = array();

			return $html;
		}
	}

	function form_buttons($print = false) {
		$html = '<div class="form-group">' . "\n";
		$html .= '<div class="col-md-6 col-md-offset-3">' . "\n";

		foreach ($this->buttons as $button) {
			$html .= '<button type="' . $button['type'] . '" class="btn btn-' . ($button['type'] == 'submit' ? 'success' : 'primary') . '">' . $button['value'] . '</button>' . "\n";
		}
		$html .= '</div>';
		$html .= "\n";
		$html .= '</div>';

		if ($print === TRUE) {
			echo $html;
		} else {
			return $html;
		}
	}

	function form_end($print = false) {
		$html = "</form>";

		if ($print === TRUE) {
			echo $html;
		} else {
			return $html;
		}
	}

	function generate($print = false) {
		if ($print === TRUE) {
			$this -> form_start($print);

			$this -> form_elements($print);

			$this -> form_buttons($print);

			$this -> form_end($print);
		} else {
			$html = $this -> form_start($print);

			$html .= $this -> form_elements($print);

			$html .= $this -> form_buttons($print);

			$html .= $this -> form_end($print);

			return $html;
		}
	}

	function generate_hidden($element) {
		$html = '<input type="hidden" id="em-' . $element['name'] . '" name="' . $element['name'] . '" value="' . $element['value'] . '" />';
		return $html;
	}

	function generate_password($element) {
		$html = '<input type="password" id="em-' . $element['name'] . '"' . ' name="' . $element['name'] . '"' . ' value="' . $element['value'] . '"' . ($element['required'] ? ' required="required"' : '') . ' class="form-control col-md-7 col-xs-12" />';
		return $html;
	}

	function generate_text($element) {
		$html = '<input type="text" id="em-' . $element['name'] . '"' . ' name="' . $element['name'] . '"' . ' value="' . $element['value'] . '"' . ($element['required'] ? ' required="required"' : '') . ' class="form-control col-md-7 col-xs-12" />';
		return $html;
	}

	function generate_url($element) {
		$html = '<input type="url" id="em-' . $element['name'] . '"' . ' name="' . $element['name'] . '"' . ' value="' . $element['value'] . '"' . ($element['required'] ? ' required="required"' : '') . ' class="form-control col-md-7 col-xs-12" />';
		return $html;
	}

	function generate_html($element) {
		$html = $element['value'];
		return $html;
	}

	function generate_radio($element) {
		$html = '<div class="radio-groups">' . "\n";
		$i = 0;
		foreach ($element['options'] as $value => $label) {
			$i++;
			$id = "em-" . $element['name'] . "-" . $i;
			$html .= '  <label for="' . $id . '">' . $label . '&nbsp;</label>';
			$html .= '<input type="radio" name="' . $element['name'] . '" id="' . $id . '" value="' . $value . '"' . ($element['required'] ? ' required="required"' : '') . ' ' . ($element['value'] == $value ? ' checked' : '') . ' class="flat">&nbsp;&nbsp;' . "\n";
		}
		$html .= '</div>';
		return $html;
	}

	function generate_select($element) {
		$html = '<select id="em-' . $element['name'] . '"' . ' name="' . $element['name'] . '"' . ($element['required'] ? ' required="required"' : '') . ($element['isMulty'] ? ' size=5 multiple' : '') . ' class="form-control col-md-7 col-xs-12">' . "\n";
		foreach ($element['options'] as $value => $label) {
			$html .= '  <option value="' . $value . '" ' . ($element['value'] == $value ? 'selected' : '') . '>' . $label . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}

	function generate_textarea($element) {
		$html = '<textarea id="em-' . $element['name'] . '"' . ' name="' . $element['name'] . '"' . ($element['required'] ? ' required="required"' : '') . ' class="form-control col-md-7 col-xs-12" rows=5>' . $element['value'] . '</textarea>';
		return $html;
	}

	function generate_date($element) {
		$html = '<input type="text" id="em-' . $element['name'] . '"' . ' name="' . $element['name'] . '"' . ' value="' . $element['value'] . '"' . ($element['required'] ? ' required="required"' : '') . ' class="date-picker form-control col-md-7 col-xs-12" />';
		return $html;
	}

	function generate_checkbox($element) {
		if (is_array($element['value'])) {

		} else {
			$element['value'] = array();
		}

		if ($element['isMulty'] === false && count($element['options']) == 1) {
			$name = $element['name'];
			foreach ($element['options'] as $value => $label) {
				$html = '<input type="checkbox" name="' . $name . '" id="em-' . $name . '" value="' . $value . '"' . ($element['required'] ? ' required="required"' : '') . ' ' . (in_array($value, $element['value']) ? ' checked' : '') . ' class="flat">';
				return $html;
			}
		}

		$html = "";
		$selected_labels = array();
		if ($element['isMulty']) {
			$all_checked = false;
			if (count($element['value']) > 0) {
				$all_values = implode(",", array_keys($element['options']));
				if ($all_values == implode(",", $element['value'])) $all_checked = true;
			}
			$html .= '<div class="radio-groups-allcheck" radio-groups="em-' . $element['name'] . '"><input type="checkbox" id="em-' . $element['name'] . '-allcheck" class="flat" radio-groups="em-' . $element['name'] . '" ' . ($all_checked ? ' checked' : '') . '>';
			$html .= '&nbsp;&nbsp;<label for="em-' . $element['name'] . '-allcheck">Select All</label></div>';
		}
		
		$html .= '<div id="em-' . $element['name'] . '" class="radio-groups">' . "\n";
		$i = 0;
		foreach ($element['options'] as $value => $label) {
			if (!$value) continue;
			$i++;
			$id = "em-" . $element['name'] . "-" . $i;
			$name = $element['name'];
			$name .= "[]";

			$html .= '<div class="checkbox-input-field">';
			$html .= '<input type="checkbox" name="' . $name . '" id="' . $id . '" value="' . $value . '"' . ($element['required'] ? ' required="required"' : '') . ' ' . (in_array($value, $element['value']) ? ' checked' : '') . ' class="flat">&nbsp;&nbsp;';
			$html .= '<label for="' . $id . '">' . $label . '&nbsp;</label>';
			$html .= "</div>\n";

			if (in_array($value, $element['value'])) {
				$selected_labels[] = $label;
			}
		}
		$html .= '</div>';
		//if (count($element['options']) > 5) 
		{
			$html = '<div class="long-radio-groups"><div id="em-' . $element['name'] . '-labels">' . implode(", ", $selected_labels) . '</div>' . $html . '</div>';
		}

		return $html;
	}

	function generate_file($element) {
		$name = $element['name'];
		$html = '<div class="input-group input-file-group">' . "\n";
		if ($element['value']) {
			$html .= '  <span class="input-group-btn">' . "\n";
			$html .= '    <a target="_blank" href="' . $element['value'] . '" class="btn btn-dark" data-toggle="tooltip" title="View old file"><i class="fa fa-eye"></i></a>' . "\n";
			$html .= '  </span>' . "\n";
		}
		$html .= '  <input type="text" class="form-control" id="em-' . $name . '"' . ($element['required'] ? ' required="required"' : '') . '>' . "\n";
		$html .= '  <input type="file" name="' . $name . '" >' . "\n";
		$html .= '  <span class="input-group-btn">' . "\n";
		$html .= '    <button type="button" class="btn btn-primary" data-toggle="tooltip" title="Choise file"><i class="fa fa-upload"></i></button>' . "\n";
		$html .= '  </span>' . "\n";
		$html .= '</div>';
		return $html;
	}

}
