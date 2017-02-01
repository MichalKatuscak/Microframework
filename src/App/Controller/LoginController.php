<?php

namespace App\Controller;

use Katuscak\Component\Authorization;
use Katuscak\Component\Configuration;
use Katuscak\Component\Request;
use Katuscak\Component\Response\Response;
use Katuscak\Component\Response\ResponseTemplate;
use Katuscak\Component\Routing\Router;

final class LoginController
{
    public function index(Request $request, Authorization $authorization, Router $router)
    {
        $bad_credential = false;
        if ($request->post("username") && $request->post("password")) {
            $status = $authorization->login($request->post("username"), $request->post("password"));

            if ($status) {
                $router->redirectToRoute("homepage");
            } else {
                $bad_credential = true;
            }
        }
        return new ResponseTemplate("login.php", [
            "bad_credential" => $bad_credential
        ]);
    }
}