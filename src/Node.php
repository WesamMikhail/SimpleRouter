<?php
namespace Lorenum\SimpleRouter;

class Node{
    protected $fragment;
    protected $routes = [];
    protected $children = [];

    public function getRoutes() {
        return $this->routes;
    }

    public function addRoute($method, $target){
        $this->routes[$method] = $target;
    }

    public function getRoute($method){
        if(isset($this->routes[$method]))
            return $this->routes[$method];
        return false;
    }

    public function setFragment($fragment){
        $this->fragment = $fragment;
    }

    public function getFragment(){
        return $this->fragment;
    }

    public function getChildren() {
        return $this->children;
    }

    public function addChild(Node $child){
        $this->children[$child->getFragment()] = $child;
    }

    public function getChild($child){
        if(isset($this->children[$child]))
            return $this->children[$child];
        return false;
    }
}