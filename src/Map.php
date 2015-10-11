<?php
namespace Lorenum\SimpleRouter;

use Exception;

class Map{
    const METHOD_GET = "GET";
    const METHOD_POST = "POST";
    const METHOD_PUT = "PUT";
    const METHOD_DELETE = "DELETE";

    protected $nodes;

    function __construct(){
        $this->nodes = new Node();
    }

    public function get($route, $resource){
        $this->add(self::METHOD_GET, $route, $resource);
    }

    public function post($route, $resource){
        $this->add(self::METHOD_POST, $route, $resource);
    }

    public function put($route, $resource){
        $this->add(self::METHOD_PUT, $route, $resource);
    }

    public function delete($route, $resource){
        $this->add(self::METHOD_DELETE, $route, $resource);
    }

    public function add($method, $route, $resource){
        if(!in_array($method, [self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE]))
            throw new Exception("Method not supported. Use 'METHOD_' class constants.");

        $route = trim($route, "/");
        $fragments = explode("/", $route);
        $parent = $this->nodes;

        foreach($fragments as $piece){
            if($node = $parent->getChild($piece)){
                $parent = $node;
            }
            else{
                $node = new Node();
                $node->setFragment($piece);
                $parent->addChild($node);
                $parent = $node;
            }
        }

        $parent->addRoute($method, $resource);
    }

    public function match($method, $route){
        if(!in_array($method, [self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE]))
            throw new Exception("Method not supported. Use 'METHOD_' class constants.");

        if(!is_array($route)){
            $route = trim($route, "/");
            $route = explode("/", $route);
        }

        $node = $this->nodes;
        $params = [];

        foreach($route as $fragment){
            //Search for fragment as pre-defined piece of the URI
            $child = $node->getChild($fragment);
            if($child === false){

                //Search for fragment as a parameter of the URI
                $child = $node->getChild(":");
                if($child === false){
                    return false;
                }

                $params[] = $fragment;
            }

            $node = $child;
        }

        $route = $node->getRoute($method);

        if($route !== false){
            $route = explode("@", $route);
            $route = [
                "controller" => $route[0],
                "action" => $route[1],
                "params" => $params
            ];
        }

        return $route;
    }
}