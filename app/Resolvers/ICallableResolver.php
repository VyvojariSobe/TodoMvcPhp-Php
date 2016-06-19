<?php
declare(strict_types = 1);

namespace App\Resolvers;

use Symfony\Component\HttpFoundation\Request;

interface ICallableResolver
{
    public function resolve(Request $request);
}
