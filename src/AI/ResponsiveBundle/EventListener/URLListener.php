<?php
namespace AI\ResponsiveBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;

class URLListener implements EventSubscriberInterface
{
    private $url;

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        $response  = $event->getResponse();

        //Save the HTTP Referrer
        $currentReferrer = $request->cookies->get("http_referer");
        if( isset($_SERVER['HTTP_REFERER']) && is_null($currentReferrer) ) {
            $httprefer = $_SERVER['HTTP_REFERER'];
            $httpreferhost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if(strrpos($httpreferhost, "asiainspection") === false && strrpos($httpreferhost, "chinainspection") === false) {
                setcookie("http_referer", $httprefer, (time()+60*60*24*365), "/");
            }
        }

        //Set a cookie for the experience for url's coming from paid search and the 'exp' url param (to be saved during registration)
        if( $request->query->get('exp') != null ) {
            setcookie("mobExperience", $request->query->get('exp'), (time()+60*60*24*365), "/");
        }

        // Get params cookie to use below
        $paramsCookie = json_decode($request->cookies->get("paramsCookie"), TRUE);

        //Save the xtor param to a cookie
        if($request->query->get('xtor')!= null) {
            setcookie("xtor", $request->query->get('xtor'), (time()+60*60*24*365), "/");
        } else {
            if( isset($_SERVER['HTTP_REFERER']) ){
                $ref = strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST));
                if(strrpos($ref, "asiainspection") === false && strrpos($ref, "chinainspection") === false) {
                    $refCookie = "";
                    if( strpos($ref, "google.") !== FALSE ) $refCookie = "Organic Search (Google)";
                    if( strpos($ref, "bing.") !== FALSE ) $refCookie = "Organic Search (Bing)";
                    if( strpos($ref, "baidu.") !== FALSE ) $refCookie = "Organic Search (Baidu)";
                    if( strpos($ref, "yahoo.") !== FALSE ) $refCookie = "Organic Search (Yahoo)";
                    //setcookie("xtor", $refCookie, (time()+60*60*24*365), "/");
                    $paramsCookie["xtor"] = $paramsCookie["XTOR LTP"] = $refCookie;
                    if( !isset($paramsCookie["XTOR FTP"]) || $paramsCookie["XTOR FTP"] == "" ) $paramsCookie["XTOR FTP"] = $refCookie;
                    setcookie("paramsCookie", json_encode($paramsCookie), (time()+60*60*24*365), "/");
                }
            }
        }

        //Saving All URL Params to a cookie to be saved with the registration
        if($request->query->all() != null) {
            //Get all the params as an array and decode the paramsCookie as an array
            //$URLparams = $request->query->all(); //This is not working on Prod for some reason so I'm using the method below
            $tempParams = explode("&", $_SERVER['QUERY_STRING']);
            $URLparams = array();
            if($tempParams != "") {
                foreach($tempParams as $param) {
                    $x = explode("=", $param);
                    if( isset($x[0]) && isset($x[1]) ) $URLparams[$x[0]] = $x[1];
                }
            }
            
            //Loop through the params and set them to their correct values based on the latest one given in the URL
            foreach($URLparams as $key => $param) {
                $paramsCookie[$key] = $param;
                if( strtolower($key) == "xtor" ) {
                    if( !isset($paramsCookie["XTOR FTP"]) || $paramsCookie["XTOR FTP"] == "" ) $paramsCookie["XTOR FTP"] = $param;
                    $paramsCookie["XTOR LTP"] = $param;
                }
            }
            //Encode the Params as JSON and save it to the cookie
            setcookie("paramsCookie", json_encode($paramsCookie), (time()+60*60*24*365), "/");
        }

        // Get Marketo info and save it in the 'marketo' cookie as a JSON encoded object
        $marketoCookie = json_decode($request->cookies->get("marketo"), TRUE);
        // Urls to Skip
        $marketoSkip = false;
        $url = strtolower($_SERVER['REQUEST_URI']);
        $urlExcludeListMarketo = array("/android-register","/ios-register","/appregistrationsuccess");
        $urlWildcards = array("/mobile-regulatory-updates");
        if( in_array($url, $urlExcludeListMarketo)) $marketoSkip = true;
        foreach ($urlWildcards as $wild) if( strpos($url, $wild) !== FALSE ) $marketoSkip = true;

        if( is_null($marketoCookie) && !$marketoSkip) {
            // Set Cookie
            $munchkin = $request->cookies->get("_mkto_trk");
            if( !is_null($munchkin) ) {

                $marketoSoapEndPoint     = "https://944-QDO-384.mktoapi.com/soap/mktows/3_1";
                $marketoUserId           = "MKTOWS_944-QDO-384_1";
                $marketoSecretKey        = "60984012872550005566FFCC992244CDBB554582BF94";
                $marketoNameSpace        = "http://www.marketo.com/mktows/";
                $fields                  = array("Id","Email","FirstName","LastName","clientStatus","leadStatus","isEU","activeConsentDate");
        
                // Create Signature
                $dtObj  = date_create('now');
                $timeStamp = $dtObj->format(DATE_W3C);
                $encryptString = $timeStamp . $marketoUserId;
                $signature = hash_hmac('sha1', $encryptString, $marketoSecretKey);
                
                // Create SOAP Header
                $attrs = new \stdClass();
                $attrs->mktowsUserId = $marketoUserId;
                $attrs->requestSignature = $signature;
                $attrs->requestTimestamp = $timeStamp;
                $authHdr = new \SoapHeader($marketoNameSpace, 'AuthenticationHeader', $attrs);
                $options = array("connection_timeout" => 20, "location" => $marketoSoapEndPoint);
                
                // Create Request
                $leadKey = array("keyType" => "COOKIE", "keyValue" => $munchkin);
                $leadKeyParams = array("leadKey" => $leadKey);
                $params = array("paramsGetLead" => $leadKeyParams);
                $soapClient = new \SoapClient($marketoSoapEndPoint ."?WSDL", $options);
                try {
                    $lead = $soapClient->__soapCall('getLead', $params, $options, $authHdr);
                } catch(\Exception $e) {
                    // return new Response($e->getMessage());
                }
                $obj = $lead->result;
                $output = array();
                foreach ($obj->leadRecordList as $user) {
                    $userAttr = array();
                    if( in_array("Id", $fields) ) $userAttr["Id"] = $user->Id;
                    if( in_array("Email", $fields) ) $userAttr["Email"] = $user->Email;
                    foreach ($user->leadAttributeList->attribute as $attr) {
                        if( in_array($attr->attrName, $fields) ) $userAttr[$attr->attrName] = $attr->attrValue;
                    }
                    $output[] = $userAttr; 
                }
                $output = json_encode($output);
                // Cookie lasts for one week so it will be updated every so often
                setcookie("marketo", $output, (time()+60*60*24*7), "/");
                
            }

        }

    }

    public function onKernelResponse(FilterResponseEvent $event) {}

    public static function getSubscribedEvents() {
        return array( KernelEvents::REQUEST => array(array('onKernelRequest', 17)) );
    }
}
?>