<?php
declare(strict_types = 1);

namespace App\Container;

interface IContainer
{
    public function getService(string $name);

    public function getParameter(string $key, $default = null);
}
