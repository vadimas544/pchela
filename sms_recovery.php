<?php
require('db.php');

$errors = [];

if(isset($_POST['submit'])){
		if(htmlspecialchars(trim($_POST['phone'])) == '' )
	    {
	        $errors[]='Введите телефон!';
	    }

	    $phone = htmlspecialchars(trim($_POST['phone']));
		$sql = "SELECT code_client FROM pos.client_property WHERE code_property = '2' AND value_property='".$phone."'";
		$result = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
				//echo $sql;
		if(pg_num_rows($result) == 0){
			$errors[] = 'Указанного номера не существует!';
		}

				$code_client = pg_fetch_array($result, null,  PGSQL_NUM);
				$cod = $code_client[0];

	    if(empty($errors)){
	    		
				//echo $code;
	    		$user = 'vadim123544@gmail.com';
				$password = 'Memfis123';
				$send_sms_url = 'https://esputnik.com/api/v1/message/sms';

				$from = 'reklama';
				$gen_pass = substr(md5(time()), 0, 6);

				$text = "Ваш новый пароль: $gen_pass";
				$password1 = password_hash($gen_pass,  PASSWORD_DEFAULT);
				$sql1 = "UPDATE pos.client SET password='$password1'  WHERE code_client=$cod";
				echo $sql1;
				pg_query($dbconn, $sql1);
				$number = $phone;

				$json_value = new stdClass();
				$json_value->text = $text;
				$json_value->from = $from;
				$json_value->phoneNumbers = array($number);
				function send_request($url, $json_value, $user, $password) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_value));
					curl_setopt($ch, CURLOPT_HEADER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch,CURLOPT_USERPWD, $user.':'.$password);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);
					curl_close($ch);
					//echo($output);
					}
				send_request($send_sms_url, $json_value, $user, $password);


					echo "Новый пароль сгенерирован и отправлен вам на телефон!<hr />";
				}
				else{
	    	echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
	    	}
	

		}
				
				
				/*
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
*/
?>
<title>Восстановление пароля</title>
<form method="post" action="">
	<p>
		<label>Ваш номер телефона:</label>
		<input type="text" name="phone" placeholder="067*******" />
	</p>
	<p>
		<input type="submit" name="submit" value="Восстановить">
	</p>
	<p>
		<a href="clientlogin.php">Перейти в личный кабинет</a>
	</p>
</form>