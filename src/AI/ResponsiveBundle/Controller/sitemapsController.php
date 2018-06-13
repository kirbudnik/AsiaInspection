<?php
namespace AI\ResponsiveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use AI\ResponsiveBundle\Model\Tracking;
use Vresh\TwilioBundle;
use Symfony\Component\DomCrawler\Crawler;

use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class sitemapsController extends Controller {

  /**
     * @Route("/asia-inspection-site-map", name = "AI Site Map")
     * @Template("AIResponsiveBundle:sitemaps:sitemap.html.twig")
     */
    public function indexAction() {
        if (isset($_GET['_locale'])) {
            $locale = $_GET["_locale"];
            $this->get('request')->setLocale($locale);
        }

        $names = array();
        $urls = array();
        $industries = array();
        $aboutus = array();
        $inspections = array();
        $audits = array();
        $labtests = array();
        $serviceDetails = array();
        $news = array();
        $careers = array();

        //$variables = array();
        foreach ($this->get('router')->getRouteCollection()->all() as $name => $route) {
            $route = $route->compile();
            if( strpos($name, "ai_") !== 0  && substr($name, 0, 1) != '_'){
                $emptyVars = [];
                foreach( $route->getVariables() as $v ){
                    $emptyVars[ $v ] = $v;
                }
               if(strpos($name, "aboutus_") ===0 ){
                    $len = strlen($name)-1;
                    $key = $this->get('translator')->trans(substr($name,8,$len));
                    $aboutus[$key] = $this->generateUrl( $name, $emptyVars );
               } elseif(strpos($name, "inspections_") ===0 ){
                    $len = strlen($name)-1;
                    $key = $this->get('translator')->trans(substr($name,12,$len));
                    $inspections[$key] = $this->generateUrl( $name, $emptyVars );
               } elseif(strpos($name, "audits_") ===0 ){
                    $len = strlen($name)-1;
                    $key = $this->get('translator')->trans(substr($name,7,$len));
                    $audits[$key] = $this->generateUrl( $name, $emptyVars );
               } elseif(strpos($name, "labtest_") ===0 ){
                    $len = strlen($name)-1;
                    $key = $this->get('translator')->trans(substr($name,8,$len));
                    $labtests[$key] = $this->generateUrl( $name, $emptyVars );
               } elseif(strpos($name, "industries_") ===0 ){
                    $len = strlen($name)-1;
                    $key = $this->get('translator')->trans(substr($name,11,$len));;
                    $industries[$key] = $this->generateUrl( $name, $emptyVars );
               } elseif(strpos($name, "serviceDetail_") ===0 ){
                    $len = strlen($name)-1;
                    $key = $this->get('translator')->trans(substr($name,14,$len));
                    $serviceDetails[$key] = $this->generateUrl( $name, $emptyVars );
               } elseif(strpos($name, "newsAndContent_") ===0 ){
                    $len = strlen($name)-1;
                    $key = $this->get('translator')->trans(substr($name,15,$len));
                    $news[$key] = $this->generateUrl( $name, $emptyVars );
               } elseif(strpos($name, "careers_") ===0 ){
                    $len = strlen($name)-1;
                    $key =  $this->get('translator')->trans(substr($name,8,$len));
                    $careers[$key] = $this->generateUrl( $name, $emptyVars );
               } else {
                    if($name != "remove_trailing_slash") {
                        $names[]=$name;
                        $urls[] = $this->generateUrl( $name, $emptyVars );
                    }
                }
            }
        }

        // Additional URLs
        $industries["Eyeweartesting.com"] = "http://www.eyeweartesting.com";

        ksort($industries);
        ksort($aboutus);
        ksort($inspections);
        ksort($audits);
        ksort($labtests);
        ksort($serviceDetails);
        ksort($news);
        ksort($careers);

        $twigData = array(
            'name'=>$names,
            'urls'=>$urls,
            'industries' => $industries,
            'aboutus' => $aboutus,
            'inspections' => $inspections,
            'audits' => $audits,
            'labtests' => $labtests,
            'serviceDetails' => $serviceDetails,
            'news' => $news,
            'careers' => $careers
        );

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('sitemap', $locale);
        //include Tracking data [End]

        return $twigData;
    }


  /**
     * @Route("/content/lists.xml")
     *
     */

    public function listsAction(){
        // Following code by contractor Sam Coll, questions: samcoll@gmail.com
        $Global = $this ->get('global_functions');
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/lists.xml');
        
        // Load countries and ISO3 codes to XML
        $countriesPath = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');
        $countries = simplexml_load_file($countriesPath);
        
        // Grab the serialzed data from the database
        $xml = $Global->serializeDatabaseTable("SELECT * FROM  tradeshowList GROUP BY friendlyName");

        // Deserialize the data
        $xmlDeser = simplexml_load_string($xml);

        // Load the existing Tradeshow XML file, assign the Tradeshow node to local var
        $doc = simplexml_load_file($path);
        $tradeshows = $doc->en->Tradeshows;
        $tradeShowCountries = $doc->en->TradeShowCountries;

        // Delete all current <option> nodes from the Tradeshow node
        foreach($tradeshows->xpath('./option') as $child) {
            unset($child[0]);
        }

        // Delete all current <option> nodes from the TradeShowCountries node
        foreach($tradeShowCountries->xpath('./country') as $child) {
            unset($child[0]);
        }

        // Save the cleared XML file
        $doc->asXML($path); 

        // Parse the data from db, turn each Tradeshow instance into an XML node
        $countriesListed = array();
        $startingFromDate = strtotime("-12 months");
        foreach($xmlDeser->item as $item)
        {
            $loc = (string)$item->location;
            $name = str_replace(' ', '', (string)$item->tradeshow);
            $showDate = strtotime((string)$item->Start_Date);
            // Add each tradeshow to the XML document node list
            if($showDate > $startingFromDate) {
                $newElement = $tradeshows->addChild("option");
                $newElement->addAttribute('name', $name);
                $newElement->addAttribute('value', (string)$item->tradeshow);
                $newElement->addAttribute('location', $loc);
                $newElement[0] = (string)$item->friendlyName;
            }

            // Add each tradeshow country to the XML document node list
            if( !in_array($loc, $countriesListed) ) {
                $countryName = "";
                foreach ($countries as $country) {
                    if($country->iso3 == $loc) $countryName = $country->name;
                }
                $newCountry = $tradeShowCountries->addChild("country");
                $newCountry->addAttribute('code', $loc);
                $newCountry[0] = $countryName;
                $countriesListed[] = $loc;
            }
        }

        // Save the file again, this time with updated XML from db
        $doc->asXML($path);

        return new BinaryFileResponse($path);
    }

      /**
     * @Route("/link")
     *
     */

    public function linksAction(){
            /*In-App Links Guide
                ReportsList: asiainspection://?page=reportlist&username={username}
                Dashboard: asiainspection://?page=dashboard&username={username}
                New order: asiainspection://?page=neworder&username={username}
                Payment: asiainspection://?page=payment&username={username}
                ReportDetails: asiainspection://?page=reportdetails&id={report ID}&username={username}
                Auto Login Mobile Site: http://".$CustomerMobileServer."/login/user/aidemoaii/password/4309b92d67f4b0f055e3795ea2e9f6e0/protected/report/F0F2EE9DDAA4251D48257A4C003A18B8
                [JIRA] https://asiainspection.atlassian.net/browse/AI-1139
            */
            //$CustomerServer = "202.66.128.138:82";                //Development
            $CustomerMobileServer = "m.asiainspection.com";         //Prod
            $CustomerServer = "customer.asiainspection.com";        //Prod
            $device = "Desktop";
            $browser = "";
            $applink = "";
            $weblink = "http://www.asiainspection.com";
            $type = $_GET['type'];
            $querystring = $_SERVER['QUERY_STRING'];
            $userid = $_GET['user'];
            $password = (isset($_GET['pass']) ? $_GET['pass'] : "" );

            if(stripos($_SERVER['HTTP_USER_AGENT'],"iPod")) $device = "iPod";
            if(stripos($_SERVER['HTTP_USER_AGENT'],"iPhone")) $device = "iPhone";
            if(stripos($_SERVER['HTTP_USER_AGENT'],"iPad")) $device = "iPad";
            if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")) $device = "Android";
            if(stripos($_SERVER['HTTP_USER_AGENT'],"Chrome")) $browser = "Chrome";

            //Reports List
            if($type == "allreports"){
                $applink = "?page=reportlist&username=".$userid;
                $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=report";
                if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/reports";
            }

            //Individual Report
            if($type == "report"){
                $reportid = $_GET['rid'];
                $applink = "?page=reportdetails&id=".$reportid."&username=".$userid;
                $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=report";
                if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/report/".$reportid;
            }

            //View or update your order
            if($type == "vieworder"){
                $orderid = $_GET['oid'];
                $applink = "?page=neworder&username=".$userid;
                $desktopURL = "/booking/product-inspections/isOrder/1/orderId/".$orderid."#step=4";
                //If Product or Audit inspection, URL is Different
                if(isset($_GET['cat'])){
                    if( $_GET['cat'] == "inspection" ) $desktopURL = "/booking/product-inspections/isOrder/1/orderId/".$orderid."#step=4";
                    if( $_GET['cat'] == "labtest" ) $desktopURL = "/booking-laboratory-testing/index/isOrder/1/orderId/".$orderid."#step=3";
                    if( $_GET['cat'] == "audit" ) $desktopURL = "/booking-audits/audits/isOrder/1/orderId/".$orderid."#step=5";
                }
                $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=".$desktopURL;
                if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/order_preview/".$orderid;
            }

            //Pay online
            if($type == "pay"){
                $applink = "?page=payment&username=".$userid;
                $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=payment";
                if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/payments/";
            }

            //Sign in to your account
            if($type == "login"){
                $applink = "";
                $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=report";
                if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/reports";
            }

            //Dashboard
            if($type == "dashboard"){
                $applink = "";
                $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=dashboard";
                if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/dashboard/";
            }

            //Profile Settings
            if($type == "profilesettings"){
                $applink = "";
                $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=settings";
                if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://m.asiainspection.com/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/manage_account/";
            }

            //Approve Report
            if($type == "approve"){
                $reportid = $_GET['rid'];
                $orderid = $_GET['oid'];
                $prodid = $_GET['pid'];
                if(!isset($_GET['pid']) || $_GET['pid'] == "" ) $prodid = 1;
                if(!isset($_GET['pass'])){
                    $applink = "?page=reportdetails&id=".$reportid."&username=".$userid."&action=approve";
                    $weblink = "http://".$CustomerServer."/login?ref=report/reports-to-approve/orderNo/".$orderid."/productSeq/".$prodid."/reportUid/".$reportid;
                    if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid."?ref=protected/report/".$reportid;
                }else{
                    $applink = "?page=reportdetails&id=".$reportid."&username=".$userid."&action=approve";
                    $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=report/reports-to-approve/orderNo/".$orderid."/productSeq/".$prodid."/reportUid/".$reportid;
                    if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/report/".$reportid;
                }
            }

            //Reject Report
            if($type == "reject"){
                $reportid = $_GET['rid'];
                $orderid = $_GET['oid'];
                $prodid = $_GET['pid'];
                if(!isset($_GET['pid']) || $_GET['pid'] == "" || !is_numeric($_GET['pid']) ) $prodid = 1;
                if(!isset($_GET['pass'])){
                    $applink = "?page=reportdetails&id=".$reportid."&username=".$userid."&action=reject";
                    $weblink = "http://".$CustomerServer."/login?ref=report/reports-to-reject/orderNo/".$orderid."/productSeq/".$prodid."/reportUid/".$reportid;
                    if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid."?ref=protected/report/".$reportid;
                }else{
                    $applink = "?page=reportdetails&id=".$reportid."&username=".$userid."&action=reject";
                    $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=report/reports-to-reject/orderNo/".$orderid."/productSeq/".$prodid."/reportUid/".$reportid;
                    if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/report/".$reportid;
                }
            }

            //Re-order inspection
            if($type == "reorder"){
                $orderid = $_GET['oid'];
                if(!isset($_GET['pass'])){
                    $applink = "?page=neworder&username=".$userid;
                    $weblink = "http://".$CustomerServer."/login?ref=order/re-order/orderId/".$orderid."/fromEmail/1";
                    //If Product or Audit inspection, URL is Different
                    if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid."?ref=protected/re_order";
                }else{
                    $applink = "?page=neworder&username=".$userid;
                    $weblink = "http://".$CustomerServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."?ref=order/re-order/orderId/".$orderid."/fromEmail/1";
                    //If Product or Audit inspection, URL is Different
                    if($device == "iPod" || $device == "iPhone" || $device == "iPad" || $device == "Android") $weblink = "http://".$CustomerMobileServer."/login/user/".$userid.( $password == "" ? "" : "/password/".$password )."/protected/re_order";
                }
            }

            $twigData=array();
            $twigData['weblink'] = $weblink;
            $twigData['applink'] = $applink;
            $twigData['browser'] = $browser;
            $twigData['device'] = $device;
            return $this->render('AIResponsiveBundle:sitemaps:links.html.twig', $twigData);
        }



    /**
     * @Route("/app")
     *
     */

    public function appAction(){
        $ifMobile = 0;
        if (preg_match("/Mobile|Android|BlackBerry|iPod|iPhone|iPad|Windows Phone/", $_SERVER['HTTP_USER_AGENT'])) {
            $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
            $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
            $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
                if( $iPod || $iPhone ){
                    $url = 'itms://itunes.apple.com/us/app/asiainspection/id589783645?mt=8&uo=4';
                }else if($iPad){
                    $url = 'itms://itunes.apple.com/us/app/asiainspection/id589783645?mt=8&uo=4';
                }else if($Android){
                    $url = 'market://details?id=com.asiainspection';
                }else{
                    $url = 'http://www.asiainspection.com';
                }
                $ifMobile = 1;
        }else{
            $url = '/mobile';
            $ifMobile = 0;
        }
        return $this->render('AIResponsiveBundle:sitemaps:app.html.twig', array('url'=>$url,'ifMobile'=>$ifMobile));
    }

    /**
     * @Route("/mobile")
     *
     */

    public function mobileAction(Request $request){
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
        $twigData['tracking'] = $tracking->getTrackingCode('mobile-landing', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:sitemaps:micromobile.html.twig', $twigData);
    }

    /**
     * @Route("/emailMobileLink")
     *
     */
    public function emailMobileLinkAction(){
        try{
            $agent = "aiSendMobileEmail";
            $agentLoc = "qi/aiweb.nsf";
            $emailAddress = $_POST["email"];
            // TODO validate email address
            if( strpos($emailAddress, "@") >= 1 ) {
                $kv = array();
                $url = "http://aius3.asiainspection.com/";
                $path = $agentLoc . "/" . $agent . "?OpenAgent";
                foreach ($_POST as $key => $value) {
                    if (is_array($value)) {
                        $subarray = array();
                        foreach ($value as $subkey => $subvalue) {
                            $subarray[] = $subvalue;
                        }
                        $sub = join(",", $subarray);
                        $kv[] = "$key=" . rawurlencode($sub);
                    } else {
                        $kv[] = "$key=" . rawurlencode($value);
                    }
                }
                $params = join("&", $kv);
                $returnedval = file_get_contents($url . $path . "&" . $params);
                return new Response($returnedval);
            } else {
                return new Response("Invalid email address.");
            }
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }


    /**
    * @Route("/smsMobileLink")
    * @Method("POST")
    */
    public function smsMobileLinkAction()
    {
        $countryCode = $_POST["countryCode"];
        $toNumber = $_POST["phoneNumber"];
        $fromNumber = '16468768887';
        if (strlen($countryCode) > 10
            || strlen($toNumber) > 30
            || ! preg_match('/^\d\d\d\d\d+$/',$toNumber)
            || ! preg_match('/^\d*$/', $countryCode)) {
            return new Response("Bad phone number, please check your phone number.");
        }
        $requests = 1;
        $resettime = true;
        $conn = $this->container->get('DBConnector')->getDBconnection('Data');
        if( $conn['error'] ) {
            error_log("DBConnector error:" . $conn['msg']);
        } else {
            $db = $conn['connection'];
            $sql = "select UNIX_TIMESTAMP(lastsend) as lastsend, requests from twilio_numbers where phone = '$countryCode$toNumber'";
            $query = mysqli_query($db,$sql);
            $rows = [];
            while($r = mysqli_fetch_assoc($query)) {
                $rows[] = $r;
            }
            if (count($rows) > 0) {
                $row = $rows[0];
                if (time() - $row['lastsend'] < 24*60*60) {
                    $resettime = false;
                    if ($row['requests'] > 4) {
                        return new Response('Too many requests, please try again tomorrow.');
                    } else {
                        $requests += $row['requests'];
                    }
                }
            }
        }
        // TODO: validate phone number
        try {
            $twilio = $this->get('twilio.api');
            // TODO handle message
            $message = $twilio->account->messages->sendMessage(
                $fromNumber,
                "+".$countryCode.$toNumber,
                "Click the link to get the AI App now!  http://www.asiainspection.com/app"
            );
            if (strpos($message, '"status":"queued"') !== false) {
                $message = 'Sending';
            } else {
                error_log($message);
                $message = 'Error sending message';
            }
            if (!$conn['error']) {
                if ($resettime) {
                    $sql = "REPLACE INTO `twilio_numbers`(`phone`, `lastsend`, `requests`) VALUES ('$countryCode$toNumber',now(),'$requests')";
                } else {
                    $sql = "INSERT INTO `twilio_numbers` (`phone`, `lastsend`, `requests`) VALUES ('$countryCode$toNumber',now(),'$requests') ON DUPLICATE KEY UPDATE requests = $requests";
                }
                $db->query($sql);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            error_log($message);
        }
        return new Response($message);
    }

    /**
     * @Route("/employee-leads")
     * @Template()
     */
    public function employeeLeadsAction(Request $request) {
        return $this->render('AIResponsiveBundle:sitemaps:employeeleads.html.twig');
    }
}
