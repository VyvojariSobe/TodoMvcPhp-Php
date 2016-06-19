<?php

namespace App\Controllers;

use App\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;

trait ViewResponseTrait
{
    public function createViewResponse(string $templateName, array $data = []) : Response
    {
        $file = __DIR__ . "/../Views/$templateName.phtml";

        if (!is_file($file)) {
            throw new FileNotFoundException(sprintf('File "%s" not found', $file));
        }

        ob_start();
        require $file;
        $content = ob_get_clean();

        return Response::create($content);
    }
}
