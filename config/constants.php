<?php


$host = env('APP_ENV');
$constants = [];

if ($host == "test") {
    require('constants/test_constants.php');
    $constants = $test_constants;
}

if ($host == "production") {
    require('constants/production_constants.php');
    $constants = $production_constants;
}

if ($host == "development" || $host == "local") {
    require('constants/dev_constants.php');
    $constants = $dev_constants;
}
return $constants;
