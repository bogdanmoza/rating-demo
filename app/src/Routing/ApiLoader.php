<?php

declare(strict_types=1);

namespace App\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ApiLoader implements RouteLoaderInterface
{
    const PREFIX = '/api';
    private array $routes = [
        'listAll' => [
            'controller'    => '%s',
            'name'          => '%s_list_all',
            'methods'       => 'GET',
            'path'          => '/%s'
        ],
        'list' => [
            'controller'    => '%s',
            'name'          => '%s_list',
            'methods'       => 'GET',
            'path'          => '/%s/{id}'
        ],
        'create' => [
            'controller'    => '%s',
            'name'          => '%s_create',
            'methods'       => 'POST',
            'path'          => '/%s'
        ],
        'update' => [
            'controller'    => '%s',
            'name'          => '%s_update',
            'methods'       => 'PATCH',
            'path'          => '/%s/{id}'
        ],
        'replace' => [
            'controller'    => '%s',
            'name'          => '%s_replace',
            'methods'       => 'PUT',
            'path'          => '/%s/{id}'
        ],
        'delete' => [
            'controller'    => '%s',
            'name'          => '%s_delete',
            'methods'       => 'DELETE',
            'path'          => '/%s/{id}'
        ]
    ];

    public function loadRoutes(): RouteCollection
    {
        $finder     = new Finder();
        $collection = new RouteCollection();
        $files      = $finder->files()->in(__DIR__.'/../Entity');
        $classes    = $this->extractClassAndNamespace($files);

        foreach ($classes as $class) {
            if (method_exists($class['full_path'], 'enableLoader')) {
                foreach ($this->routes as $action => $params) {
                    $route = new Route(
                        sprintf(self::PREFIX . $params['path'], strtolower($class['class_name'])),
                        [
                            '_controller' => 'App\Controller\\' . $class['class_name'] . 'Controller::' . $action
                        ],
                        [],
                        [],
                        '',
                        [],
                        [
                            $params['methods']
                        ]
                    );
                    $collection->add(sprintf($params['name'], strtolower($class['class_name'])), $route);
                }
            }
        }

        return $collection;
    }

    private function extractClassAndNamespace($files): array
    {
        $classes = [];
        foreach ($files as $file) {
            $lines  = file($file->getRealpath());
            $tmp    = preg_grep('/^namespace /', $lines);
            if ($tmp) {
                $namespaceLine  = array_shift($tmp);
                $match          = array();
                preg_match('/^namespace (.*);$/', $namespaceLine, $match);
                if ($match) {
                    $class = $this->getClassName($file->getRealpath());
                    $classes[] = [
                        'full_path'     => trim(array_pop($match)) . '\\' . $class,
                        'class_name'    => $class
                    ];
                }
            }
        }

        return $classes;
    }

    private function getClassName($filename): string
    {
        $directoriesAndFilename = explode('/', $filename);
        $filename               = array_pop($directoriesAndFilename);
        $nameAndExtension       = explode('.', $filename);

        return array_shift($nameAndExtension);
    }
}
