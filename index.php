<?php
session_start();
include_once("config/Configuration.php");

$module = isset($_GET["module"]) ? $_GET["module"] : "LoginController";
$action = isset($_GET["action"]) ? $_GET["action"] : "show";

$configuration = new Configuration();
$router = $configuration->createRouter("createLoginController", "show");

$router->executeActionFromModule($module, $action);