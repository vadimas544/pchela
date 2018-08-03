<?php
	require('db.php');
	$errors = [];
	if(isset($_POST['submit'])){
		if(htmlspecialchars(trim($_POST['email'])) == '' )
	    {
	        $errors[]='Введите email!';
	    }

	    $email = htmlspecialchars(trim($_POST['email']));
		$sql = "SELECT code_client FROM pos.client_property WHERE code_property = '4' AND value_property='".$email."'";
		$result = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
				//echo $sql;
		if(pg_num_rows($result) == 0){
			$errors[] = 'Указанного почтового ящика не существует!';
		}

				$code_client = pg_fetch_array($result, null,  PGSQL_NUM);
				$cod = $code_client[0];

	    if(empty($errors)){
	    		
				//echo $code;

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

				echo "Новый пароль сгенерирован и отправлен вам на e-mail!<hr />";
	    }else{
	    	echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
	    }
	

	}
?>
<title>Восстановление пароля</title>
<form method="post" action="">
	<p>
		<label>Ваш e-mail:</label>
		<input type="text" name="email" />
	</p>
	<p>
		<input type="submit" name="submit" value="Восстановить">
	</p>
	<p>
		<a href="clientlogin.php">Перейти в личный кабинет</a>
	</p>
</form>
