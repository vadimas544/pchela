<?php
require_once('db.php');
$surname = $_POST['surname'];
if(empty($surname)){
	echo 'В фамилии должен быть хотя бы один символ!';
}else{
	$code = $_SESSION['code'];
	$sql = "UPDATE pos.client SET surname='$surname' WHERE code_client='".$code."'";
	$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
	echo 'Вы успешно изменили фамилию!';
}


