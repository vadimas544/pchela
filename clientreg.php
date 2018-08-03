<?php

require "db.php";

$errors = array();

$data = $_POST;
if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($data['do_signup'])){
	if(htmlspecialchars(trim($data['card'])) == '' )
    {
        $errors[] = 'Введите номер карты!';
    }
    if(htmlspecialchars(trim($data['name'])) == '' )
    {
        $errors[] = 'Введите Имя!';
    }
    if(htmlspecialchars(trim($data['surname'])) == '' )
    {
        $errors[] = 'Введите Фамилию!';
    }
    if(htmlspecialchars(trim($data['patronymic'])) == '' )
    {
        $errors[] = 'Введите Отчество!';
    }
    if(htmlspecialchars(trim($data['phone'])) == '' )
    {
        $errors[] = 'Введите номер телефона!';
    }
    if(htmlspecialchars(trim($data['birthday'])) == '' )
    {
        $errors[] = 'Введите дату рождения!';
    }
    if(htmlspecialchars(trim($data['email'])) == '' )
    {
        $errors[] = 'Введите email!';
    }
    if(htmlspecialchars(trim($data['password'])) == '' )
    {
        $errors[] = 'Введите пароль!';
    }
    if(htmlspecialchars(trim($data['password_2'])) == '' )
    {
        $errors[] = 'Повторите пароль!';
    }
    if(!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $_POST['email'])){
		$error[] = "Укажите корректный E-mail";
	}
    
    $phone = trim(pg_escape_string($data['phone']));
	$email = trim(pg_escape_string($data['email']));
	$name = trim(pg_escape_string($data['name']));
	$surname = trim(pg_escape_string($data['surname']));
	$patronymic = trim(pg_escape_string($data['patronymic']));
	$gender = $data['gender'];
	$date = trim(pg_escape_string($data['birthday']));
    //Проверяем, есть ли пользователь с таким номером карты
    $begin = '0001';
    $query ="SELECT code_client FROM pos.barcode_client WHERE barcode LIKE '".$begin . $data['card'] ."_'";
    
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    $code_client = pg_fetch_array($result, null,  PGSQL_NUM);
    $cod = $code_client[0];

    if(pg_num_rows($result) == 0) {
		 $errors[] = 'Пользователя с такой картой не найдено!';
	}else{
		$sql = "SELECT password FROM pos.client WHERE code_client = '".$cod."'";
		//echo $sql;
		$result = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
		//$int = pg_affected_rows($result);
		//echo $int;
		$pass = pg_fetch_array($result, null, PGSQL_NUM);
		if(!empty($pass[0])){
            $errors[] = "Эта карточка уже зарегистрирована!";
        }
	}

	//Если пустой массив ошибок, то записываем данные в БД
	if(empty($errors)){


		$password = password_hash($data['password'], PASSWORD_DEFAULT);
	
		$query = "SELECT code_client FROM pos.client";
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    	$code_client_new = [];

		while ($line = pg_fetch_array($result, null, PGSQL_NUM)) {
		        foreach ($line as $code) {
		        //echo "$code<br />";
		        }
		        $code_client_new[] = $code;
		    }
       
		
        $sql1 = "SELECT code_property FROM pos.client_property WHERE code_client = '".$cod."'";
        //echo $sql1;
        $res1 = pg_query($sql1) or die('Ошибка запроса: ' . pg_last_error());
        while ($prop = pg_fetch_array($res1, null, PGSQL_ASSOC)) {
                foreach ($prop as $code_prop) {
                }
                $property_code[] = $code_prop;
            }
        $two = 2;$three = 3; $four = 4;
        
		if(in_array($code, $code_client_new)){
		
			//Если клиент есть в таблице то ей просто добавляются поля пароль, телефон, почта
			$query = "UPDATE pos.client SET surname='$surname', name='$name', patronymic='$patronymic', date_birth='$date', password='$password'  WHERE code_client=$cod;";
			pg_query($dbconn, $query);
                if(in_array(2, $property_code) && in_array(3, $property_code) && in_array(4, $property_code)){
                    
                    $sql2 = "UPDATE pos.client_property SET value_property=$phone  WHERE code_client = $cod AND code_property = $two";
                 	$res2 = pg_query($sql2) or die('Ошибка запроса: ' . pg_last_error());
                 	$sql6 = "UPDATE pos.client_property SET value_property=$gender  WHERE code_client = $cod AND code_property = $three";
                 	$res6 = pg_query($sql6) or die('Ошибка запроса: ' . pg_last_error());
                    
                    $sql4 = "UPDATE pos.client_property SET value_property='$email'  WHERE code_client = $cod AND code_property = $four";
                 $res4 = pg_query($sql4) or die('Ошибка запроса: ' . pg_last_error());
                 }else{
                    $sql3 = "INSERT INTO pos.client_property (code_client, code_property, value_property) values ('$cod', '2', '$phone')";
                    $res3 = pg_query($sql3) or die('Ошибка запроса: ' . pg_last_error());
                    $sql7 = "INSERT INTO pos.client_property (code_client, code_property, value_property) values ('$cod', '3', '$gender')";
                    $res7 = pg_query($sql7) or die('Ошибка запроса: ' . pg_last_error());
                    $sql5 = "INSERT INTO pos.client_property (code_client, code_property, value_property) values ('$cod', '4', '$email')";
                    $res5 = pg_query($sql5) or die('Ошибка запроса: ' . pg_last_error());
                 }


		}
    
		echo '<div style="color: green;">Вы успешно зарегистрированы!</div><hr>';
		echo '<a href="clientlogin.php">Перейти в личный кабинет</a>';
	}else{
		echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
		
	}
}
?>
<form action="" method="POST">
    <p>
    <p><strong>Номер карты</strong></p>
        <input type="number" name="card" value="<?php echo @$data['card']; ?>">
    </p>
    <p>
    <p><strong>Ім'я</strong></p>
    <input type="text" name="name" value="<?php echo @$data['name']; ?>">
    </p>
    <p>
    <p><strong>Прізвище</strong></p>
    <input type="text" name="surname" value="<?php echo @$data['surname']; ?>">
    </p>
    <p>
    <p><strong>По батькові</strong></p>
    <input type="text" name="patronymic" value="<?php echo @$data['patronymic']; ?>">
    </p>
    <p>
    <p><strong>Пол</strong></p>
    <label>
        <input type="radio" name="gender" value="1" required>
        <span>Чоловік</span>
    </label>
    <label>
        <input type="radio" name="gender" value="2" required>
        <span>Жінка</span>
    </label>

    </p>
    <p>
    <p><strong>Номер телефона</strong></p>
    <input type="number" name="phone" value="<?php echo @$data['phone']; ?>" placeholder="067*******">
    </p>
    <p>
    <p><strong>Дата народження</strong></p>
    <input type="date" name="birthday" value="<?php echo @$data['birthday']; ?>">
    </p>
    <p>
    <p><strong>Email</strong></p>
        <input type="email" name="email" value="<?php echo @$data['email']; ?>">
    </p>
    <p>
    <p><strong>Пароль</strong></p>
        <input type="password" name="password">
    </p>
    <p>
    <p><strong>Повторите пароль</strong></p>
        <input type="password" name="password_2">
    </p>

    <div>
        <div>
            <div data-validation="checked">
                <input  type="checkbox" name="personalDataAgreement" value="1" required>
                <span>Ви даєте згоду на обробку персональних даних</span>
            </div>
            <div class="c-form__field" data-validation="checked">
                <input type="checkbox" name="loyaltyTermsAgreement" value="1" required>
                <span>
                                Ви ознайомлені з                                <a href="http://main-domain.ml/правила-програми/" target="_blank">
                                    Правилами програми лояльності                                </a>
                            </span>
            </div>
            <span data-error="checked">
                            <p>Згода з правилами та обробка даних - обов'язкова</p>
                        </span>
        </div>
    </div>

    <p>
        <button type="submit" name="do_signup">Зарегистрироваться</button>
    </p>
    <script src="js/common.js"></script>

</form>
