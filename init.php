<?php
if (!defined("95495036eb814b2fb6b1bc61dee0884c5570b46c0b1166c3cd7c6b24922fb3ce")) {
    die("...");
}
ob_start();
session_start();
date_default_timezone_set('Europe/Istanbul');
define('URL', "https://selcuksahintest.de/cryptomessenger");
define('PATH', $_SERVER["DOCUMENT_ROOT"] . '/cryptomessenger');

require_once(PATH . '/library/collector.php');
$db = new database\mysql;
