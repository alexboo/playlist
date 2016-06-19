<?php
/**
 * Created by PhpStorm.
 * User: alexboo
 * Date: 6/18/16
 * Time: 12:10 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Singer;
use AppBundle\Request\SongFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class SongController extends RestController {

    /**
     * @Route("/songs", name="songs")
     * @Method("get")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function songsAction(Request $request)
    {
        $filter = new SongFilter($request);

        $data = $this->getDoctrine()
            ->getRepository('AppBundle:Song')
            ->findAllByFilters($filter);

        return $this->jsonResponse($data);
    }
}