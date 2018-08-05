<?php
	require('db.php');
	$errors = [];
	if(isset($_POST['submit'])){
		if(htmlspecialchars(trim($_POST['card'])) == '' )
	    {
	        $errors[]='Введите номер карты!';
	    }

	    $card = htmlspecialchars(trim($_POST['card']));

		//$sql = "SELECT code_client FROM pos.client_property WHERE code_property = '4' AND value_property='".$email."'";
		$begin = '0001';
     	//$query ="SELECT code_client FROM pos.barcode_client WHERE barcode='".$data['card']."'";
        $query ="SELECT code_client FROM pos.barcode_client WHERE barcode LIKE '".$begin . $_POST['card'] ."_'";
        $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
        if(pg_num_rows($result) == 0){
            $errors[] = 'Пользователя с такой картой не найдено!';
        }
		//$result = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
				//echo $sql;
		//if(pg_num_rows($result) == 0){
			//$errors[] = 'Указанного почтового ящика не существует!';
		//}

				

	    if(empty($errors)){
	    		
				//echo $code;
	    		$code_client = pg_fetch_array($result, null,  PGSQL_NUM);
				$cod = $code_client[0];

				$sql = "SELECT value_property FROM pos.client_property WHERE code_property = '4' AND code_client='".$cod."'";
				$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());

				$arr = pg_fetch_array($res, null,  PGSQL_NUM);
				$email = $arr[0];
				$gen_pass = substr(md5(time()), 0, 6);
				//echo $new_pass;
				$password = password_hash($gen_pass,  PASSWORD_DEFAULT);
				$sql1 = "UPDATE pos.client SET password='$password'  WHERE code_client=$cod";
				//echo $sql1;
				pg_query($dbconn, $sql1);

				$to = $email;
				$from = "vadim123544@gmail.com";

				$subject = "Новый пароль";
				//$subject = iconv('CP1251', 'UTF-8', $subject);
				$subject = '=?utf-8?B?'.base64_encode($subject).'?=';
				$headers = "From: $from\r\nReply-To: $from\r\nContent-Type: text/plain; charset = utf-8\r\n";
				$message = "Ваш новый пароль: $gen_pass";
				//$message = iconv('CP1251', 'UTF-8', $message);
				mail($to, $subject, $message, $headers);

				echo '<div style="color: green;">'.'Новый пароль сгенерирован и отправлен вам на e-mail!'.'</div><hr />';
	    }else{
	    	echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
	    }
	

	}
?>
<title>Восстановление пароля</title>
<form method="post" action="">
	<p>
		<label>Ваш номер карты:</label>
		<input type="number" name="card" />
	</p>
	<p>
		<input type="submit" name="submit" value="Восстановить">
	</p>
	<p>
		<a href="clientlogin.php">Перейти в личный кабинет</a>
	</p>
</form>
