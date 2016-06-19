<?php
declare(strict_types = 1);

namespace App;

use App\Container\IContainer;
use App\Resolvers\ICallableResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    /** @var IContainer */
    protected $container;

    public function __construct()
    {
        $parameters = require __DIR__ . '/config.php';
        $parameters['appDir'] = __DIR__;
        $this->container = new Container($parameters);
    }

    public function handle(Request $request)
    {
        try {
            /** @var ICallableResolver $resolver */
            $resolver = $this->container->getService('callableResolver');

            list($controller, $action, $params) = $resolver->resolve($request);
            array_unshift($params, $request);

            $controller = new $controller($this->container);
            $response = call_user_func_array([$controller, $action], $params);

            if (!$response instanceof Response) {
                throw new ControllerMustReturnsResponseException(
                  sprintf('Expected "%s" got "%s"!', Response::class, gettype($response))
                );
            }

            $response->prepare($request);

            return $response;
        } catch (HttpException $e) {
            $code = $e->getCode();
        } catch (\Exception $e) {
            $code = 500;
        }

        return new Response($e->getMessage(), $code);
    }
}
