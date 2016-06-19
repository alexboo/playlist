<?php
/**
 * Created by PhpStorm.
 * User: alexboo
 * Date: 6/18/16
 * Time: 3:36 PM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RestController extends Controller {

    /**
     * Serialize data to json string and make Response with application/json content type
     * @param $data
     * @return Response
     */
    protected function jsonResponse($data)
    {
        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($data, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}