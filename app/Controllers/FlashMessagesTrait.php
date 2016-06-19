<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

trait FlashMessagesTrait
{
    protected $flashMessages = [];

    public function addFlashMessage(string $type, string $message)
    {
        $this->flashMessages[$type][] = $message;
    }

    public function createFlashMessagesCookie() : Cookie
    {
        return new Cookie('flashMessages', json_encode($this->flashMessages), '+3 seconds');
    }

    public function getFlashMessages(Request $request) : array
    {
        $messagesJson = $request->cookies->get('flashMessages');
        $request->cookies->remove('flashMessages');
        $messages = json_decode($messagesJson, true);

        return $messages ?: [];
    }
}
