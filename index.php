
<?php
require __DIR__  . "/vendor/autoload.php";

// 🔥 Add this for pretty JSON globally
header("Content-Type: application/json; charset=utf-8");
define("PRETTY_JSON", JSON_PRETTY_PRINT);

require __DIR__ . "/src/Routes/Api.php";
