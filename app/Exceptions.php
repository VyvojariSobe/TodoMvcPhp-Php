<?php
declare(strict_types = 1);

namespace App;

class ServiceNotFoundException extends \Exception
{
}

class ServiceMustBeObjectException extends \Exception
{
}

class ControllerMustReturnsResponseException extends \Exception
{
}

class FileNotFoundException extends \Exception
{
}

class HttpException extends \Exception
{
    protected $code = 400;
}

class NoRouteFoundException extends HttpException
{
}

class ControllerNotFoundException extends HttpException
{
}

class ActionNotFoundException extends ControllerNotFoundException
{
}
