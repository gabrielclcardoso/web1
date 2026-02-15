<?php
require_once '../srcs/includes/init.php';

$_SESSION = array();
session_destroy();

header("Location: index.php");
exit;
