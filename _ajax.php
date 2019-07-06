<?php

session_start();

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/function.php');
require_once(__DIR__ . '/Todo.php');

$taskApp = new \MyApp\Task();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $res = $taskApp->post();
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
  } catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' , true, 500);
    echo $e->getMessage();
    exit;
  }
}
