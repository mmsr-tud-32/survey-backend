<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author G.J.W. Oolbekkink <g.j.w.oolbekkink@gmail.com>
 * @since 24/06/2019
 */
class IndexController extends BaseController {
    /**
     * Return code 200 on / for checking if the service is up.
     *
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */
    public function index() {
        return new Response();
    }
}
