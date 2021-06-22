<?php
use Benjamin\Log\CLogFileHandler;
use Benjamin\Log\Log;
include_once "vendor/autoload.php";
$logHandler= new CLogFileHandler("a12345");
$log = Log::Init($logHandler, 15);
$log->DEBUG('begin debug');
$log->ERROR("THIS IS ERROR");
$log->INFO("INFO");