<?php

require '../../runtime.php';
require '../../func.php';

$user = M('User')->getUserByToken($_GET['token']);
if ($user->exists()) {
	$uid = $user['id'];
	$file = '../../uploads/avatar/'.$uid.'.png';
	$content = file_get_contents("php://input");

	file_put_contents($file, $content);

	M('User')->updateById(array(
		'avatar' => str_replace('../..', '', $file),
	), $uid);

	echo json_encode(array(
		'code' => 200,
		'url' => $file
	));
} else {
	echo json_encode(array(
		'code' => 201,
		'url' => 'error'
	));
}

