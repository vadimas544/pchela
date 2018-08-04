<?php
require_once('db.php');


$phone = $_POST['phone'];
$pos = strstr($_POST['phone'], '8', true);
$property = $_POST['property'];
if(empty($phone)){
	echo 'В новом телефоне должен быть хотя бы один символ!';
}elseif($pos != '3'){
	echo 'Неправильный формат номера, нужно 380*********!';
}else{
	$code = $_SESSION['code'];
	$sql = "UPDATE pos.client_property SET value_property='$phone' WHERE code_client='$code' AND code_property='$property'";
	$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
	echo 'Вы успешно изменили телефон!';
}
