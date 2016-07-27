<?php

class Setting extends Suco_Config_Php
{
	public function __construct()
	{
		$this->load(CONF_DIR.'setting.conf.php');
	}

	public function getUserExtFields()
	{
		$setting = $this->get('user_fields');
		$extFields = explode("\n", $setting);
		$fields = array();

		foreach($extFields as $field) {
			list($name, $label, $type, $params) = explode('|', $field);

			switch(trim($type)) {
				case 'text':
					$html = '<input type="text" class="form-control" name="extend['.$name.']">';
					break;
				case 'textarea':
					$html = '<textarea></textarea>';
			}

			$fields[$name] = array(
				'label' => $label,
				'html' => $html
			);
		}

		echo '<pre>';
		print_r($fields);
	}
}