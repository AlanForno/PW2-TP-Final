<?php

class Router{
    private $configuration;
    private $defaultController ;
    private $defaultAction;

    public function __construct($configuration, $defaultController , $defaultAction ){
        $this->configuration = $configuration;
        $this->defaultController = $defaultController;
        $this->defaultAction = $defaultAction;
    }

    public function executeActionFromModule($module, $action){
        $controller = $this->getControllerFrom($module);
        $this->executeMethodFromController($controller,$action);
    }

    private function getControllerFrom($module){
        $controllerName = "create" . ucfirst($module) . "Controller";
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController;
        return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method){
        $validMethod = method_exists($controller, $method) ?$method : $this->defaultAction;
        call_user_func(array($controller, $validMethod));
    }
}