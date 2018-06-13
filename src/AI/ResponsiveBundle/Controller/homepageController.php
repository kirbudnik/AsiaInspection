<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class homepageController extends Controller {

	/**
	* @Route("/", name="Home Page")
	* @Template()
	*/
	public function indexAction(Request $request) {
		if (isset($_GET['_locale'])) {
			$locale = $_GET['_locale'];
			$this->get('request')->setLocale($locale);
		}

		$marketoCookie = json_decode($request->cookies->get("marketo"))[0];
		$clientCookie = $request->cookies->get("isClient");

		// Redirect clients to custom landing page
		if( isset($marketoCookie->clientStatus) || isset($clientCookie) ) {
			if(isset($marketoCookie->clientStatus)) $status = $marketoCookie->clientStatus;
			if(isset($clientCookie)) $status = "Client (Active)";
			$bypass = ( isset($_GET['client']) ? true : false );
			if( ($status == "Registration (Active)" || $status == "Client (Active)") && !$bypass ) return $this->redirect('/client-landing');
		}

		$locale = $request->getLocale();
		$path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/homepage.xml');
		$twigData = $this->getXMLAndParameters($path, $request);
		$path1 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/testimonials.xml');
		$xml1 = simplexml_load_file($path1);

		if($xml1->$locale!=""){
			foreach ($xml1->$locale->testimonial as $testimony) $twigData['testimonial'][] = $testimony;
		} else {
			foreach ($xml1->en->testimonial as $testimony) $twigData['testimonial'][] = $testimony;
		}

		//include Tracking data [Begin]
		$tracking = new Tracking();
		$locale = $this->get('session')->get('_locale');
		$twigData['tracking'] = $tracking->getTrackingCode('homepage', $locale);
		//include Tracking data [End]
		$locale = $request->getLocale();

		$Global = $this ->get('global_functions');
		$twigData['contentCards']["baro"] = $Global->latestContentAction("baro", $this);
		$twigData['contentCards']["insight"] = $Global->latestContentAction("insight", $this);
		$twigData['contentCards']["recap"] = $Global->latestContentAction("recap", $this);

		if($locale == "ar") {
			return $this->render('AIResponsiveBundle:RTL:homepage/homepage.html.twig', $twigData);
		} else {
			return $this->render('AIResponsiveBundle:homepage:homepage.html.twig', $twigData);
		}
	}

	/**
	* @Route("/popup")
	* @Template("AIResponsiveBundle:Default:SubTemplates/popup.html.twig")
	*/
	public function popupAction(Request $request) {

		$locale = $request->getLocale();
		$array = array();
		$path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/Popups.xml');
		$xml = simplexml_load_file($path);

		$smallScreen = 0;
		if(isset($_GET["smallScreen"])){
			if($_GET["smallScreen"]==true){
				$smallScreen = 1;
			}
			else
			$smallScreen = 0 ;
		}

		if (isset($_GET["type"])) {
			$type = $_GET["type"];
			$array['type'] = $type;
			if($type == "factoryauditphotos" || $type == "auditphotos"){

				if($xml->FactoryAuditPhotos->$locale != "" || $xml->AuditPhotos->$locale != "") {
					if($type == "factoryauditphotos")
					$xml = $xml->FactoryAuditPhotos->$locale;
					else
					$xml = $xml->AuditPhotos->$locale;
				} else {
					if($type == "factoryauditphotos")
					$xml = $xml->FactoryAuditPhotos->en;
					else
					$xml = $xml->AuditPhotos->en;
				}
				$thumbs = array();
				$images = array();
				$texts = array();
				foreach ($xml->img as $img) {
					$images[] = $img["image"];
					$thumbs[] = $img['thumb'];
					$texts[] = $img['text'];
				}
				$array['thumbs'] = $thumbs;
				$array['images'] = $images;
				$array['texts'] = $texts;
			}
		}
		$array['smallScreen'] = $smallScreen;
		return $this->render('AIResponsiveBundle:Default:SubTemplates/popup.html.twig', $array);
	}

	public function getXMLAndParameters($path, $request) {
		$locale = $request->getLocale();
		$xml = simplexml_load_file($path);
		$pageKeywords = ($xml->pageMeta->pageKeywords->$locale != "" ? $xml->pageMeta->pageKeywords->$locale : $xml->pageMeta->pageKeywords->en );

		$pageDesc = $xml->pageMeta->pageDesc->$locale;
		if($xml->pageMeta->pageTitle->$locale!="")
		$pageTitle = $xml->pageMeta->pageTitle->$locale;
		else
		$pageTitle = $xml->pageMeta->pageTitle->en;
		if($xml->$locale!="")
		$xml = $xml->$locale;
		else
		$xml = $xml->en;

		return array('xml' => $xml, 'pageTitle' => $pageTitle, 'pageDesc' => $pageDesc, 'pageKeywords'=>$pageKeywords);
	}


}
