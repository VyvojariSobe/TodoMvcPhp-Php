<?php
declare(strict_types = 1);

namespace App\Container;

trait ContainerAwareTrait
{
    /** @var IContainer */
    protected $container;

    public function __construct(IContainer $container)
    {
        $this->container = $container;
    }
}
