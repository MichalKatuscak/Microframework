<?php

namespace App\Controller;

use Katuscak\Component\Response\Response;

final class HomepageController
{
    /**
     * @Granted: ROLE_USER
     */
    public function index()
    {
        $text = "Ahoj";

        return new Response($text);
    }
}