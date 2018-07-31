<?php

$is_error = false;
// Соединение, выбор базы данных
$dbconn = pg_connect("host=localhost dbname=dbmain user=sysdba password=masterkey")
or die('Could not connect: ' . pg_last_error());

session_start();