<?php
require_once('db.php');


$phone = $_POST['phone'];
$property = $_POST['property'];
if(empty($phone)){
	echo 'В новом телефоне должен быть хотя бы один символ!';
}else{
	$code = $_SESSION['code'];
	$sql = "UPDATE pos.client_property SET value_property='$phone' WHERE code_client='$code' AND code_property='$property'";
	$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
	echo 'Вы успешно изменили телефон!';
}
