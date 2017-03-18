<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Track;
use AppBundle\Entity\TrackPoint;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RouteController extends Controller
{
    /**
     * @Route("/route", name="post_route", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $routeJson = $request->getContent();
        $route = json_decode($routeJson);

        $track = new Track();
        $track
            ->setIdent($route->ident)
            ->setStart(\DateTime::createFromFormat(\DateTime::ISO8601, $route->start))
            ->setEnd(\DateTime::createFromFormat(\DateTime::ISO8601, $route->end));

        $em = $this->getDoctrine()->getManager();
        $em->persist($track);

        foreach ($route->points as $point) {
            $tp = new TrackPoint();
            $tp
                ->setLat($point->lat)
                ->setLon($point->lon)
                ->setTimestamp(\DateTime::createFromFormat(\DateTime::ISO8601, $point->timestamp));
            $em->persist($tp);
        }

        $em->flush();

        return new Response();
    }
}
