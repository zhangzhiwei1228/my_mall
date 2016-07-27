<?php

require_once '../runtime.php';
require_once '../func.php';


class Suco_Form 
{
	protected $_method = 'get';
	protected $_class;
	protected $_action;
	protected $_enctype;

	public function __construct($method = 'get', $action = '', $enctype = '', $class = '')
	{
		$this->_method = $method;
		$this->_action = $action;
		$this->_class = $class;
		$this->_enctype = $enctype;
	}

	public function createInput($type, $params)
	{
		switch ($type) {
			case 'text':
			case 'password':
			case 'hidden':
				$str = '<input type="'.$type.'"';
				if ($params['name']) {
					$str.=' name="'.$params['name'].'"';
				}
				if ($params['value']) {
					$str.=' value="'.$params['value'].'"';
				}
				if ($params['class']) {
					$str.=' class="'.$params['class'].'"';
				}
				$str.= '/>';
				$this->_inputs[] = $str;
				break;
			default:
				# code...
				break;
		}
	}

	public function __toString()
	{
		$string = '<form method="'.$this->_method.'"';
		if ($this->_action) {
			$string.= ' action="'.$this->_action.'"';
		}
		if ($this->_enctype) {
			$string.= ' enctype="'.$this->_enctype.'"';
		}
		if ($this->_class) {
			$string.= ' class="'.$this->_class.'"';
		}
		$string.=">";

		foreach($this->_inputs as $html) {
			$string.= $html;
		}

		$string.="</form>";
		return $string;
	}
}


$form = new Suco_Form('post', '/ts.php');
$form->createInput('text',array(
	'name' => 'username',
	'value' => 'test',
	'class' => 'form-control'
));
$form->createInput('password',array(
	'name' => 'password',
	'value' => 'test',
	'class' => 'form-control'
));
//$form->createButton('提交');
echo $form;