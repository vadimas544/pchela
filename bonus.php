<?php
require "db.php";
$code = $_SESSION['code'];
$sql ="SELECT * FROM pos.barcode_client WHERE code_client='".$code."'";
$res = pg_query($sql) or die('Ошибка запроса: ' . pg_last_error());
$barcode = pg_fetch_array($res, null, PGSQL_ASSOC);

$sql1 = "SELECT code_account FROM pos.client_account WHERE code_client='".$code."'";
$res1 = pg_query($sql1) or die('Ошибка запроса: ' . pg_last_error());
$data1 = pg_fetch_array($res1, null, PGSQL_NUM);
//echo $data1[0];

$sql2 = "SELECT sum_account FROM pos.account WHERE code_account='".$data1[0]."'";
$res2 = pg_query($sql2) or die('Ошибка запроса: ' . pg_last_error());
$data = pg_fetch_array($res2, null, PGSQL_NUM);
//echo $data[0];
?>
<div class="row">
	<div class="text-center">
		№&nbspВашої карти&nbsp:&nbsp<?php echo $barcode['barcode'];?>
	</div>
</div>
<div class="bonus">
	<div class="row3">
		<div class="row">
			<div class="col-lg-6">
				<div class="text-center">
					<h3>Бонусных гривен</h3>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="text-center">
					<h3>
						<?php
							echo $data[0] . 'грн.';
						?>
					</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="row4">
		<div class="row">
			<div class="col-lg-6">
				<div class="text-center">
					<h3>Получаемый бонус при покупке</h3>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="text-center">
					<h3>3%</h3>
				</div>
			</div>
		</div>	
	</div>
	
</div>
