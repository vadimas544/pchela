<?php
require_once('db.php');

$pwd = $_POST['pwd'];
if(empty($pwd)){
	echo 'Введите новый пароль!';
}else{
	$code = $_SESSION['code'];
	$password = password_hash($pwd, PASSWORD_DEFAULT);
	$sql = "UPDATE pos.client SET password='$password' WHERE code_client='".$code."'";
	$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
	echo 'Вы успешно изменили пароль!';
}