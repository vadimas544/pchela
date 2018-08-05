<?php
require('db.php');

$errors = [];

if(isset($_POST['submit'])){
		if(htmlspecialchars(trim($_POST['card'])) == '' )
	    {
	        $errors[]='Введите номер карты!';
	    }
	    /*
	    $pos = strstr($_POST['phone'], '8', true);
	    if($pos != '3'){
	        $errors[] = 'Неправильный формат номера, нужно 380*********!';
	    }
	    */
	    /*
	    $card = htmlspecialchars(trim($_POST['card']));
		$sql = "SELECT code_client FROM pos.client_property WHERE code_property = '2' AND value_property='".$phone."'";
		$result = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
				//echo $sql;
		if(pg_num_rows($result) == 0){
			$errors[] = 'Указанного номера не существует!';
		}

				
		*/
		$begin = '0001';
     	//$query ="SELECT code_client FROM pos.barcode_client WHERE barcode='".$data['card']."'";
        $query ="SELECT code_client FROM pos.barcode_client WHERE barcode LIKE '".$begin . $_POST['card'] ."_'";
        $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
        if(pg_num_rows($result) == 0){
            $errors[] = 'Пользователя с такой картой не найдено!';
        }

	    if(empty($errors)){
	    		
	    		$code_client = pg_fetch_array($result, null,  PGSQL_NUM);
				$cod = $code_client[0];

				$sql = "SELECT value_property FROM pos.client_property WHERE code_property = '2' AND code_client='".$cod."'";
				$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());

				$arr = pg_fetch_array($res, null,  PGSQL_NUM);
				$phone = $arr[0];
	    		$user = 'vadim123544@gmail.com';
				$password = 'Memfis123';
				$send_sms_url = 'https://esputnik.com/api/v1/message/sms';

				$from = 'reklama';
				$gen_pass = substr(md5(time()), 0, 6);

				$text = "Ваш новый пароль: $gen_pass";
				$password1 = password_hash($gen_pass,  PASSWORD_DEFAULT);
				$sql1 = "UPDATE pos.client SET password='$password1'  WHERE code_client=$cod";
				//echo $sql1;
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


					echo '<div style="color: green;">'.'Новый пароль сгенерирован и отправлен вам на телефон!'.'</div><hr />';
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
		<label>Ваш номер карты:</label>
		<input type="text" name="card" />
	</p>
	<p>
		<input type="submit" name="submit" value="Восстановить">
	</p>
	<p>
		<a href="clientlogin.php">Перейти в личный кабинет</a>
	</p>
</form>