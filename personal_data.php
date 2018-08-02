<?php
require "db.php";
$code = $_SESSION['code'];
$query = "SELECT * FROM pos.client WHERE code_client = '".$code."'";
//echo $query;
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$data = pg_fetch_array($result, null, PGSQL_ASSOC);

$sql ="SELECT * FROM pos.barcode_client WHERE code_client='".$code."'";
$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
$barcode = pg_fetch_array($res, null, PGSQL_ASSOC);

$sql1 = "SELECT code_property FROM pos.client_property WHERE code_client = '".$code."'";
$res1 = pg_query($sql1) or die('Ошибка запроса: ' . pg_last_error());

/*while($prop = pg_fetch_array($res1, null, PGSQL_ASSOC)){
	$code_prop[] = $prop;
}

while ($prop = pg_fetch_array($res1, null, PGSQL_ASSOC)) {
		        foreach ($prop as $code_prop) {
		        //echo "$code<br />";
		        }
		        $property_code[] = $code_prop;
		    }
*/

$sql2 = "SELECT value_property FROM pos.client_property WHERE code_client = '".$code."'";
$res2 = pg_query($sql2) or die('Ошибка запроса: ' . pg_last_error());
while ($prop1 = pg_fetch_array($res2, null, PGSQL_ASSOC)) {
		        foreach ($prop1 as $property) {
		        }
		        $properties[] = $property;
		    }
?>
					<div class="info">
						
					</div>

					<form method="post" action="">
					<div class="pers_data">
						<div class="row">
							<h2>Личные данные №&nbsp<?php echo $barcode['barcode']; ?></h2>
						</div>
					</div>
					
					<div class="row">
						<table class="table">
							<tr>
								<td>Ім'я</td>
								<td><?php echo $data['name']; ?></td>
							</tr>
							<tr>
								<td>Прізвище</td>
								<td><?php echo $data['surname']; ?></td>
							</tr>
							<tr>
								<td>Новая Фамилия</td>
								<td>
									<input type="text" id="surname" name="surname">
									<button id="change_surname" class="btn-md">Изменить фамилию</button>
								</td>
							</tr>
							<tr>
								<td>По-батькові</td>
								<td><?php echo $data['patronymic']; ?></td>
							</tr>
							<tr>
								<td>*Стать</td>
								<td>
									<?php
										if(!empty($properties[2])){
											if($properties[2] == 1){
												echo 'Мужской';
											}else{
												echo 'Женский';
											}
										} else{
											echo '-';
										}
									 ?>
								</td>
							</tr>
							<tr>
								<td>*Дата народження</td>
								<td><?php echo $data['date_birth']; ?></td>
							</tr>
							<tr>
								<td>*Телефон</td>
								<td>
									<?php
										if(!empty($properties[1])){
											echo $properties[1];
										} else{
											echo '-';
										}
									?>
								</td>
							</tr>
							<tr>
								<td>Новый номер телефона</td>
								<td>
									<input name='phone' id="phone" type='text' maxlength='20' size='20'>
									<button id="change_phone" class="btn-md">Изменить телефон</button>
								</td>
							</tr>
							<tr>
								<td>*E-mail</td>
								<td>
									<?php
										if(!empty($properties[3])){
											echo $properties[3];
										} else{
											echo '-';
										}
									?>
								</td>
							</tr>
							<tr>
								<td>Новый E-mail</td>
								<td>
									<input name='email' id="email" type='text'>
									<button id="change_email" class="btn-md">Изменить e-mail</button>
								</td>
							</tr>
							<tr>
								<td>Новий пароль</td>
								<td>
									<input name='pwd' type='pwd' id="pwd" maxlength='20'>
									<button id="change_pwd" class="btn-md">Изменить пароль</button>
								</td>
							</tr>
						</table>
					</div>
				</form>
				<script>
					$(document).ready(function(){
						$('#change_surname').click(function(){
								$.ajax({
									url: 'changesurname.php',
									method: 'post',
									data: {"surname": $('#surname'). val()},
									cache: false,
									success: function(data){
										alert(data);
								    }
								});

								return false;
						});
						$('#change_phone').click(function(){
								$.ajax({
									url: 'changephone.php',
									method: 'post',
									data: {"phone": $('#phone'). val(),
											"property": 2},
									cache: false,
									success: function(data){
										alert(data);
								    }
								});

								return false;
						});
						$('#change_email').click(function(){
								$.ajax({
									url: 'changeemail.php',
									method: 'post',
									data: {"email": $('#email'). val(),
											"property": 4},
									cache: false,
									success: function(data){
										alert(data);
								    }
								});

								return false;
						});
						$('#change_pwd').click(function(){
								$.ajax({
									url: 'changepwd.php',
									method: 'post',
									data: {"pwd": $('#pwd'). val()},
									cache: false,
									success: function(data){
										alert(data);
								    }
								});

								return false;
						});
						
					});
				</script>
	
						
					