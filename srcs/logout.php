<?php
require_once 'includes/init.php';

$_SESSION = array();
session_destroy();

header("Location: index.php");
exit;
