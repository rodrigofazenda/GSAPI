<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$sheetId = (isset($_GET["id"]) && $_GET["id"] != "" ? $_GET["id"] : "");
$table = (isset($_GET["table"]) && $_GET["table"] != "" ? $_GET["table"] : "");
$row = (isset($_GET["row"]) && $_GET["row"] != "" ? $_GET["row"] : "");

require "GSAPI.php";

new GSAPI($sheetId, $table, $row);