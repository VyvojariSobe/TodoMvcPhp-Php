<?php
declare(strict_types = 1);

namespace App;

use App\Container\ContainerTrait;
use App\Container\IContainer;
use App\Model\TodosManager;
use App\Resolvers\RegexpResolver;

class Container implements IContainer
{
    use ContainerTrait;

    public function callableResolverFactory()
    {
        $service = new RegexpResolver();

        foreach ($this->getParameter('routes') as $pattern => $target) {
            $service->addRoute($pattern, $target);
        }

        return $service;
    }

    public function pdoFactory()
    {
        $service = new \PDO('sqlite:' . $this->getParameter('database', ':memory:'));

        return $service;
    }

    public function todosManagerFactory()
    {
        /** @var \PDO $pdo */
        $pdo = $this->getService('pdo');
        $service = new TodosManager($pdo);

        return $service;
    }
}
