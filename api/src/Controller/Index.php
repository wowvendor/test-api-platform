<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Index
{
    /**
     * @Route( path="/", methods={ "GET", "POST", "PUT", "DELETE" } )
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse();
    }
}
