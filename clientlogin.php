<?php
require "db.php";
error_reporting(0);
$data = $_POST;

if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($data['do_login'])){
    $errors = array();	
	if(htmlspecialchars(trim($data['card'])) == '' )
    {
        $errors[] = 'Введите номер карты!';
    }
    if(htmlspecialchars(trim($data['password'])) == '' )
    {
        $errors[] = 'Введите пароль!';
    }
        $begin = '0001';
     	//$query ="SELECT code_client FROM pos.barcode_client WHERE barcode='".$data['card']."'";
        $query ="SELECT code_client FROM pos.barcode_client WHERE barcode LIKE '".$begin . $data['card'] ."_'";
        echo $query;
        $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
        if(!$result){
            $errors[] = 'Пользователя с такой картой не найдено!';
        }
	    /*
	if(pg_affected_rows($result) == 0) {
			 $errors[] = 'Пользователя с такой картой не найдено!';
	}
    */
    $code_client = pg_fetch_array($result, null,  PGSQL_NUM);
    $cod = $code_client[0];
	    //echo $cod;
    $sql = "SELECT password FROM pos.client WHERE code_client='".$cod."'";
	    echo $sql;
	    
        $res = pg_query($sql);//or die('Ошибка запроса: ' . pg_last_error());
	    $pass = pg_fetch_array($res, null,  PGSQL_NUM);
	    $password = $pass[0];

	if(!password_verify($data['password'],$password)) {      
	            $errors[] = 'Вы ввели неправильную комбинацию логин/пароль!';
	}

	if(password_verify($data['password'],$password)){
	     	$_SESSION['user_auth'] = true;
            $_SESSION['code'] = $cod;
            header("Location: clientcabinet.php");
            exit();
    }
    
     if(!empty($errors)){
     	echo '<div style="color:red;">'.array_shift($errors).'</div><hr>'; 
             
    }
}
?>


<form action="" method="POST">

    <p>
    <p><strong>Номер карты</strong>:</p>
    <input type="number" name="card" value="<?php echo @$data['card']; ?>">
    </p>
    <p>
    <p><strong>Пароль</strong>:</p>
    <input type="password" name="password" value="<?php echo @$data['password']; ?>">
    </p>

    <p>
        <button type="submit" name="do_login">Войти</button>
    </p>
    <a href="menu_pass.php">Забыли пароль?</a>&nbsp
    <a href="clientreg.php">Регистрация</a>
</form>