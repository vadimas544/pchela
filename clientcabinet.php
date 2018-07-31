
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
//print_r($barcode);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Bootstrap test</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<div class="cabinet">
			<div class="row">
				<div class="col-lg-6">
					<div class="menu">
						<div class="row text-left">
							<div class="col-lg-3">
								<span>Личный кабинет</span>
							</div>
							<div class="col-lg-3">
								<i class="fa fa-lock"></i> 	
							</div>
						</div>
						<br>
						<div class="row1">
							<div class="row">
								<div class="col-lg-6">
									<div class="text-left">
										<h2>Личные данные</h2>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="text-center align-middle">
										<button id="personal_data" class="btn-md">Просмотр</button>
									</div>	
								</div>
							</div>
						</div>
						<div class="row2">
							<div class="row">
								<div class="col-lg-6">
									<div class="text-left">
										<h2>Бонусный счет</h2>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="text-center align-middle">
										<button id="bonus" class="btn-md">Просмотр</button>
									</div>	
								</div>
							</div>
						</div>
					</div>	
				</div>
				<div class="col-lg-6">
					<div class="row logout">
						<div class="text-right">
							<a href="logout.php" class="btn btn-default" role="button">Выйти</a>
						</div>
					</div>
					<div class="data">
						<div class="row">
							<div id="content">
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function(){
		
			$('#personal_data').click(function(){
				$.ajax({
					url: "personal_data.php",
					cache: false,
					success: function(html){
						$("#content").html(html);
					}
				});
			});
			
			$('#bonus').click(function(){
				$.ajax({
					url: "bonus.php",
					cache: false,
					success: function(html){
						$("#content").html(html);
					}
				});
			});
		});
	</script>
</body>
</html>