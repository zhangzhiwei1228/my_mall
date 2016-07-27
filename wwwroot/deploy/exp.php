<?php

require_once '../runtime.php';
require_once '../func.php';


try {
	$user = M('User')->getById(1000012);
	$user->credit(5, '测试加积分');
} catch(exception $e) {
	echo $e->getMessage();
}

echo $user->stat['credit'];

echo '<pre>';
print_r($user);