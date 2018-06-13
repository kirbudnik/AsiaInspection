<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/logout")
     * @Template()
     */


    public function logoutAction(Request $request)
    {
        $locale = $this->get('session')->get('_locale');
        $twigData = array('lang'=>$locale);
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');
        $twigData['countries'] = simplexml_load_file($path);
        $countriesArray =  (array) $twigData['countries'];

        // Get user country for phone dropdowns
        $Global = $this ->get('global_functions');
        $twigData['UserCountry'] = $Global->checkUserCountry();

        // Sort Countries List
        usort($countriesArray, function($a, $b){
            return strcmp($a->name, $b->name);
        });
        $twigData['countries'] = $countriesArray;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $twigData['tracking'] = $tracking->getTrackingCode('logout', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:Default:logout.html.twig', $twigData);
    }

}
