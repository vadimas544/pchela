<?php
require_once('db.php');

$email = $_POST['email'];
$property = $_POST['property'];
if(empty($email)){
	echo 'В новом e-mail должен быть хотя бы один символ!';
}elseif(!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $_POST['email'])){
	echo "Укажите корректный E-mail";
}else{
	$code = $_SESSION['code'];
$sql = "UPDATE pos.client_property SET value_property='$email' WHERE code_client='$code' AND code_property='$property'";
$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
echo 'Вы успешно изменили e-mail!';
}

