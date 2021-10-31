<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
session_start();
include_once("config/Configuration.php");

<<<<<<< HEAD
$module = isset($_GET["module"]) ? $_GET["module"] : "turnos";
$action = isset($_GET["action"]) ? $_GET["action"] : "show";

$configuration = new Configuration();
$router = $configuration->createRouter("createTurnosController", "show");
=======
$module = isset($_GET["module"]) ? $_GET["module"] : "home";
$action = isset($_GET["action"]) ? $_GET["action"] : "show";

$configuration = new Configuration();
$router = $configuration->createRouter("createHomeController", "show");
>>>>>>> 429ed1cebce5835e75830ec474f924ce4e9bcdf0

$router->executeActionFromModule($module, $action);