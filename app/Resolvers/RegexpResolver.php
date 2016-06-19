<?php
declare(strict_types = 1);

namespace App\Resolvers;

use App\ActionNotFoundException;
use App\ControllerNotFoundException;
use App\NoRouteFoundException;
use Symfony\Component\HttpFoundation\Request;

class RegexpResolver implements ICallableResolver
{
    protected $routes = [];

    public function addRoute(string $pattern, string $target)
    {
        $this->routes[$pattern] = $target;
    }

    public function resolve(Request $request) : array
    {
        $path = trim($request->getPathInfo(), '/');

        foreach ($this->routes as $pattern => $target) {
            if (preg_match("~^$pattern$~", $path, $matches)) {
                array_shift($matches);

                list($controller, $action) = explode(':', $target);
                $controllerName = ucfirst(strtolower($controller)) ?: 'Default';

                $controllerClass = "\\App\\Controllers\\{$controllerName}Controller";

                if (!class_exists($controllerClass)) {
                    throw new ControllerNotFoundException(sprintf('Controller "%s" not found', $controllerName));
                }

                $actionName = 'action' . ucfirst($action);

                if (!method_exists($controllerClass, $actionName)) {
                    throw new ActionNotFoundException(sprintf('Action "%s" not found in controller "%s"', $actionName, $controllerName));
                }

                return [$controllerClass, $actionName, $matches];
            }
        }

        throw new NoRouteFoundException(sprintf('No route found for path "%s"', $path));
    }
}
