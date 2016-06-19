<?php
declare(strict_types = 1);

namespace App\Container;

use App\ServiceMustBeObjectException;
use App\ServiceNotFoundException;

trait ContainerTrait
{
    protected $parameters = [];
    protected $services = [];

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function setParameter(string $key, $value) : self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function getParameter(string $key, $default = null)
    {
        if (isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }

        return $default;
    }

    public function setService(string $name, $service)
    {
        if (!is_object($service)) {
            throw new ServiceMustBeObjectException(sprintf('Service factory must return object, got "%s"!', gettype($service)));
        }

        return $this->services[$name] = $service;
    }

    public function getService(string $name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        if (!method_exists($this, $name . 'Factory')) {
            throw new ServiceNotFoundException(sprintf('Service "%s" not found!', $name));
        }

        $service = $this->{$name . 'Factory'}();

        if (!is_object($service)) {
            throw new ServiceMustBeObjectException(sprintf('Service factory must return object, got "%s"!', gettype($service)));
        }

        return $this->services[$name] = $service;
    }
}
