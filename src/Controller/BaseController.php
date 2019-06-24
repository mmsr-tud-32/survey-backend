<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author G.J.W. Oolbekkink <g.j.w.oolbekkink@gmail.com>
 * @since 21/06/2019
 */
class BaseController extends AbstractController {
    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse {
        $json = $this->container->get('jms_serializer')->serialize($data, 'json', null);

        return new JsonResponse($json, $status, $headers, true);
    }

    public static function getSubscribedServices() {
        return array_merge(parent::getSubscribedServices(), ['jms_serializer']);
    }
}
