<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AI\ResponsiveBundle\Model\Data;

class extraPagesController extends Controller
{
    /**
     * @Route("/quality-control-in-china")
     * @Template()
     */
    public function QCinChinaAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('quality-control-china', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:qualitycontrolinchina.html.twig', $twigData);
    }

    /**
     * @Route("/qca-member-discount", name="labtest_QCA Member Page")
     * @Method({"GET", "POST"})
     */
    public function qcaMemberAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $twigData['nmi']['customType'] = "QCA-Member-Program";

        // Check the password
        $pass = $request->request->get('password');
        if( isset($pass) ) {
            if( $pass == "qca2016" ) {
                $twigData['userlogin'] = true;
            } else {
                $twigData['userlogin'] = false;
                $twigData['error'] = "Wrong password given.";
            }
        }

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('qca-member', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:qcamember.html.twig', $twigData);
    }

    /**
     * @Route("/quality-assurance-services",name="Quality Assurance Testing")
     * @Template()
     */
    public function qualityassuranceAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('qualityassurance', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:quality-assurance-services.html.twig', $twigData);
    }

    /**
     * @Route("/global-sources-supplier-search",name="Global Supplier Search")
     * @Template()
     */
    public function globalSupplierSearchAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');
        $twigData['countries'] = simplexml_load_file($path);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('globalsuppliersearch', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:global-supplier-search.html.twig', $twigData);
    }

    /**
     * @Route("/global-sources-supplier-search/search")
     * @Method("POST")
     */
    public function supplierSearchAction() {
        $country = strtoupper($_POST['c']);
        $search = strtolower($_POST['s']);
        $search = preg_replace("/[^A-Za-z0-9 ]/", '', $search);
        $rows = array();
        $data = new Data();
        $conn = $data->getDBconnection();
        if( !$conn['error'] ) {
            $db = $conn['connection'];
            mysqli_set_charset($db, "utf8");
            $query = $db->query("SELECT Supplier,Country,Last_Inspection FROM Factories WHERE Supplier LIKE '%".$search."%' AND iso2 = '".$country."';");
            while($r = mysqli_fetch_assoc($query)) {
                foreach ($r as $k => $v) {
                    $r[$k] = trim($v);
                    if($k == "Last_Inspection") $r[$k] = date("M j, Y", strtotime($v));
                }
                $rows[] = $r;
            }
        }
        $rows["count"] = number_format(count($rows));
        return new Response(json_encode($rows));
    } // End of supplierSearch

    /**
     * @Route("/astm-f963-16-toy-safety-standard")
     * @Template()
     */
    public function astmToysAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('astmtoys', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:astmtoys.html.twig', $twigData);
    }

    /**
     * @Route("/testing/saso-certificate-of-conformity",name="SASO Testing")
     * @Template()
     */
    public function sasoCertAction(Request $request) {
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/extra/saso-certification.xml');
        $twigData = $this->getXMLAndParameters($path, $request);
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $request->getLocale();
        $twigData['tracking'] = $tracking->getTrackingCode('sasocert', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:saso-certificate.html.twig', $twigData);
    }

    /**
     * @Route("/cal-prop-65-compliance-testing",name="calprop65")
     * @Template()
     */
    public function caliProp65Action(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('caliprop65', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:cali-prop65.html.twig', $twigData);
    }

    /**
     * @Route("/importacion-previo-en-origen-en-china")
     * @Template()
     */
    public function previoEnOrigenAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData = array();
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $twigData['type']="PSI";
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('previo-en-origen', $locale);
        //include Tracking data [End]
        $twigData['global']['title'] = "Red Global, Conocimientos Locales";
        $twigData['global']['pgph'] = "Con más de 900 inspectores formados y titulados, le garantizamos estar en cualquier fábrica de Asia en menos de 48 horas. Seguimos los estándares más estrictos de contratación, empleando únicamente a licenciados universitarios con una experiencia mínima de 5 años en control de calidad. Nativos del lugar donde inspeccionarán y con unos conocimientos sin parangón en las prácticas de negocio de su región.";
        $twigData['global']['clickMap'] = "Mapa de cobertura de servicio";
        return $this->render('AIResponsiveBundle:services:previo-en-origen.html.twig', $twigData);
    }

    /**
     * @Route("/eu-reach-compliance-eyewear-ppe")
     * @Template()
     */
    public function compTuesdayAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('comptuesday', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:comptuesday.html.twig', $twigData);
    }

    /**
     * @Route("/aiecc-2017")
     * @Template()
     */
    public function AIECCAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('AIECC', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:AIECC.html.twig', $twigData);
    }

    /**
     * @Route("/trade-protection-forum-2017")
     * @Template()
     */
    public function tradeProtection2017Action(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('tradeProtectionForum2017', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:tradeProtection2017.html.twig', $twigData);
    }

    /**
     * @Route("/modern-slavery-infographic")
     * @Template()
     */
    public function ILOinfographicAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('ILOinfographic', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:ILOinfographic.html.twig', $twigData);
    }

    /**
     * @Route("/global-sourcing-survey-2018")
     * @Template()
     */
    public function globalSourcingSurvey2018Action(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('globalSourcingSurvey2018', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:globalSourcingSurvey2018.html.twig', $twigData);
    }

    public function getXMLAndParameters($path,$request){
        $locale = $request->getLocale();
        $xml = simplexml_load_file($path);

        $pageTitle = ($xml->pageMeta->pageTitle->$locale != "" ? $xml->pageMeta->pageTitle->$locale : $xml->pageMeta->pageTitle->en );
        $pageDesc = ($xml->pageMeta->pageDesc->$locale != "" ? $xml->pageMeta->pageDesc->$locale : $xml->pageMeta->pageDesc->en );
        $pageKeywords = ($xml->pageMeta->pageKeywords->$locale != "" ? $xml->pageMeta->pageKeywords->$locale : $xml->pageMeta->pageKeywords->en );
        $xml = ( $xml->$locale != "" ? $xml = $xml->$locale : $xml = $xml->en );

        $path1 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/GlobalNetworkBox.xml');
        $global = simplexml_load_file($path1);
        $global = ( $global->$locale != "" ? $global = $global->$locale : $global = $global->en );

        return array('xml' =>$xml, 'pageTitle'=>$pageTitle, 'pageDesc'=>$pageDesc, 'pageKeywords'=>$pageKeywords, 'global'=>$global);
    }

  /**
   * @Route("/our-labs")
   * @Template()
   */
    public function view360Action(Request $request)
    {
        $locale = $request->getLocale();
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $array['type'] = "360labs";
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $array['tracking'] = $tracking->getTrackingCode('360labs', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:view360.html.twig',$array);
    }

    /**
     * @Route("/your-eyes-in-the-factory")
     * @Template()
     */
    public function yourEyesInTheFactoryAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $locale = $request->getLocale();
        $tracking = new Tracking();
        $twigData['tracking'] = $tracking->getTrackingCode('yourEyesInTheFactory', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:youreyesinthefactory.html.twig', $twigData);
    }

    /**
     * @Route("/eyewear-testing")
     * @Template()
     */
    public function eyewearTestingAction(Request $request) {
        // $twigData = array();
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $locale = $request->getLocale();
        $tracking = new Tracking();
        $twigData['tracking'] = $tracking->getTrackingCode('eyewearTesting', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:extra:eyeweartesting.html.twig', $twigData);
    }

    /**
     * @Route("/en")
     * @Template()
     */
    public function ChinainspectionAction(Request $request) {
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        return $this->render('AIResponsiveBundle:extra:chinainspection.html.twig', $twigData);
    }

    /**
     * @Route("/client-landing")
     * @Template()
     */
    public function clientLandingAction(Request $request) {
        $locale = $request->getLocale();
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
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('clientlanding', $locale);

        $twigData['contentCards']["baro"] = $Global->latestContentAction("baro", $this);
        $twigData['contentCards']["insight"] = $Global->latestContentAction("insight", $this);
        $twigData['contentCards']["recap"] = $Global->latestContentAction("recap", $this);

        return $this->render('AIResponsiveBundle:extra:clientlanding.html.twig', $twigData);
    }

    /**
     * @Route("/africainspection")
     * @Template()
     */
    public function africaInspectionAction(Request $request) {
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/extra/africainspection.xml');
        $twigData['xml'] = simplexml_load_file($path);

        // $twigData = array();
        $Global = $this ->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        $twigData['contentCards']["baro"] = $Global->latestContentAction("baro", $this);
        $twigData['contentCards']["insight"] = $Global->latestContentAction("insight", $this);
        $twigData['contentCards']["recap"] = $Global->latestContentAction("recap", $this);
                
        return $this->render('AIResponsiveBundle:homepage:africainspection.html.twig', $twigData);
    }

   /**
   * @Route("/popup/gdpr-consent/{email}")
   */
    public function GDPRconsentPopUpAction(Request $request, $email=""){
        return $this->render('AIResponsiveBundle:extra:popupGDPRconsent.html.twig',array("email" => $email));
    } // End of submitEmailPopUpAction

}
