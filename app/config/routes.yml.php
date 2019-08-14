<?php
/**
 * Created by PhpStorm.
 * User: armando
 * Date: 16/11/17
 * Time: 15:51
 */

use Symfony\Component\Yaml\Yaml;

$routes = Yaml::parse(file_get_contents(_APP . '/config/routes.yml'));

if(isset($routes['routes']) && is_array($routes['routes'])){ // Array of routes was setted
    foreach ($routes['routes'] as $route => $routeConf){ // reading routes
        foreach ($routeConf as $method => $conf){
            $mapping2 = $app->{$method}($route, explode('::', $conf['controller']));
            $mapping1 = $app->{$method}($route . '/', explode('::',$conf['controller']));

            if(isset($conf['middleware']) && is_array($conf['middleware'])) { // Array of routes was setted
                foreach ($conf['middleware'] as $middleware){
                    $mapping1->add(explode('::', $middleware));
                    $mapping2->add(explode('::', $middleware));
                }
            }
        }
    }
}


