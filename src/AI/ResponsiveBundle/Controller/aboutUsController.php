<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;
use AI\ResponsiveBundle\Entity\WebsiteDownload;
use AI\ResponsiveBundle\Entity\WhitepaperDownload;
use AI\ResponsiveBundle\Model\Data;

class aboutUsController extends Controller {

    /**
     * @Route("/who-we-are", name="aboutus_Who We Are")
     * @Template()
     */
    public function indexAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/whoweare.xml');
        $array = $this->getXMLAndParameters($path, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('who-we-are', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/index.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:index.html.twig', $array);
        }
    }

    /**
     * @Route("/corporate-values", name ="aboutus_Company Values")
     * @Template()
     */
    public function companyValuesAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/companyvalues.xml');
        $array = $this->getXMLAndParameters($path, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('values', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/companyValues.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:companyValues.html.twig', $array);
        }
    }

    /**
     * @Route("/dashboard-demo", name ="Dashboard_Demo")
     * @Template()
     */
    public function dashboardDemoAction(Request $request) {
        $array = array();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('dashboarddemo', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:dashboardDemo.html.twig', $array);
    }

    /**
     * @Route("/cny-2018", name ="CNY_2018")
     * @Template()
     */
    public function cny2018Action(Request $request) {
        return $this->render('AIResponsiveBundle:extra:cny2018.html.twig');
    }

    /**
     * @Route("/corporate-demo", name ="Corporate_Demo")
     * @Template()
     */
    public function corporateDemoAction(Request $request) {
        $array = array();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('corporatedemo', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:corporateDemo.html.twig', $array);
    }

    /**
     * @Route("/quality-control-pricing", name ="serviceDetail_Pricing")
     * @Template()
     */
    public function pricingAction(Request $request) {
        $isSafari = false;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') && !strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) $isSafari = true;
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/pricing.xml');
        $array = $this->getXMLAndParameters($path, $request);
        $array['isSafari'] = $isSafari;
        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');
        $array['countries'] = simplexml_load_file($path2);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('pricing', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/pricing.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:pricing.html.twig', $array);
        }
    }
    /**
     * @Route("/quality-control-coverage", name ="serviceDetail_Service Coverage")
     * @Template()
     */
    public function serviceCoverageAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/servicecoverage.xml');
        $xml = simplexml_load_file($path);
        $pageDesc = $xml->pageMeta->pageDesc->$locale;
        if($xml->pageMeta->pageTitle->$locale!="")
            $pageTitle = $xml->pageMeta->pageTitle->$locale;
        else
            $pageTitle = $xml->pageMeta->pageTitle->en;
        if($xml->$locale!="")
            $xml1 = $xml->$locale;
        else
            $xml1 = $xml->en;

        $twigData = array('xml' => $xml1, 'pageTitle' => $pageTitle, 'pageDesc' => $pageDesc);

        $twigData['asia'] = array();
        $twigData['europe'] = array();
        $twigData['africa'] = array();
        $twigData['latinAmerica'] = array();
        $twigData['mideast'] = array();

        foreach ($xml->en->countries->Asia->area as $area) $twigData['asia'][] = Intl::getRegionBundle()->getCountryName((string)$area['code'],$locale);
        foreach ($xml->en->countries->MiddleEast->area as $area) $twigData['mideast'][] = Intl::getRegionBundle()->getCountryName((string)$area['code'],$locale);
        foreach ($xml->en->countries->Europe->area as $area) $twigData['europe'][] = Intl::getRegionBundle()->getCountryName((string)$area['code'],$locale);
        foreach ($xml->en->countries->LatinAmerica->area as $area) $twigData['latinAmerica'][] = Intl::getRegionBundle()->getCountryName((string)$area['code'],$locale);
        foreach ($xml->en->countries->Africa->area as $area) $twigData['africa'][] = Intl::getRegionBundle()->getCountryName((string)$area['code'],$locale);

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('service-coverage', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:serviceCoverage.html.twig', $twigData);
    }

    /**
     * @Route("/fun-photos", name ="Fun Photos")
     * @Template()
     */
    public function funPhotosAction(Request $request) {
        if($request->getLocale() == "zh") {
            $assetsDomain = $this->container->getParameter('assets_china_domain');
        } else {
            $assetsDomain = $this->container->getParameter('assets_domain');
        }
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/FunPhotos.xml');
        $twigData['xml'] = simplexml_load_file($path);
        $twigData['photoPath'] = $assetsDomain."/images/funPhotos/";
        if (isset($_GET['pp']))
            $twigData['pp'] = $_GET['pp'];
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('fun-photos', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:fun-photos.html.twig', $twigData);
    }


        /**
     * @Route("/contact-us", name ="others_Contact Us")
     * @Template()
     */
    public function contactAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/ContactUs.xml');
        $array = $this->getXMLAndParameters($path, $request);

        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        $Phonexml = simplexml_load_file($this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/ContactUsByPhoneBox.xml'));

        $array['contacts'] = $Phonexml;

        if (isset($_SERVER['HTTP_CLIENT_IP'])){
            $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $real_ip_adress = $_SERVER['REMOTE_ADDR'];
        }

        $countryList = array();
        foreach($Phonexml->Locations->region as $region){
            foreach($region->office as $o){
                $countryList[]=$o->countryCode;
            }
        }
        $cip = $real_ip_adress;
        $url = "http://ipinfo.io/".$cip."/country";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $userCountry = trim(curl_exec($curl));
        if(!in_array($userCountry,$countryList)){
            $userCountry = "HK";
        }

        $array['userCountry'] = $userCountry;

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('contact', $locale);
        //include Tracking data [End]
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:contact:contact.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:contact:contact.html.twig', $array);
        }
    }


   /**
   * @Route("/submitInquiry")
   * @Method("POST")
   */
    public function submitInquiryAction(Request $request){
        $data = new Data();
        $type = (isset($_POST['type']) ? $_POST['type'] : "");
        $RESTdata = array(
            'userName' => $_POST['name'],
            'companyName' => $_POST['company'],
            'industry' => '',
            'isFactory' => '',
            'emailAddress' => $_POST['email'],
            'telNumber' => $_POST['phone'],
            'country' => $_POST['country'],
            'hearFrom' => '',
            'tradeshowCountry' => '',
            'tradeshow' => '',
            'recommendation' => '',
            'advertising' => '',
            'sillikerOffice' => '',
            'sillikerContact' => '',
            'inquiryAbout' => $_POST['question'],
            'inquiryAboutOther' => '',
            'leadType' => '',
            'message' => $_POST['message'],
            'isManual' => '',
            'status' => 'Not Replied',
            'searchEngine' => '',
            'queryUsed' => '',
            'url' => '',
            'newsletter' => '',
            'domain' => '',
            'isCHB' => 'No',
            'sendEmail' => true
        );

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $data->getDBconnection();
        if( !$conn['error'] ) {
            $db = $conn['connection'];
            mysqli_set_charset($db, "utf8");
            $paramsCookie = ( isset($_COOKIE['paramsCookie']) ? $_COOKIE['paramsCookie'] : "" );
            $sql = "INSERT INTO inquiries (Name, Company, Email, Country, Phone, Message, URL_Params) VALUES ('".$RESTdata['userName']."', '".$RESTdata['companyName']."', '".$RESTdata['emailAddress']."', '".$RESTdata['country']."', '".$RESTdata['telNumber']."', '".$RESTdata['message']."','".$paramsCookie."')";
            if(strtoupper($RESTdata['userName']) != "GARGAMEL") $db->query($sql);
            // Custom Email Lists
            if( $type != "") {
                $RESTdata['status'] = "Input Manually";
                $emailList = array();
                $sql = "SELECT * FROM inquiryEmailListMembers WHERE list = '".$type."' GROUP BY email";
                $r = $db->query($sql);
                while ($row = $r->fetch_assoc()) {
                    $emailList[] = trim($row['email']);
                }
                // Email
                if(strtoupper($m['name']) == "GARGAMEL") $emailList = array("honeymacd@yahoo.com");
                if( !empty($emailList) ) {
                    $RESTdata['sendEmail'] = false;
                    $template = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/emails/NewInquiry.html');
                    $body = file_get_contents($template);

                    // Replace Variables in Template
                    $body = str_replace("[[NAME]]", $_POST['name'], $body);
                    $body = str_replace("[[COMPANY]]", $_POST['company'], $body);
                    $body = str_replace("[[EMAIL]]", $_POST['email'], $body);
                    $body = str_replace("[[COUNTRY]]", $_POST['country'], $body);
                    $body = str_replace("[[PHONE]]", $_POST['phone'], $body);
                    $body = str_replace("[[MESSAGE]]", $_POST['message'], $body);
                    $body = str_replace("[[PAGEURL]]", $_SERVER['HTTP_REFERER'], $body);

                    $message = \Swift_Message::newInstance()
                    ->setSubject("New Website Inquiry - ".date("M j"))
                    ->setFrom('marketing@asiainspection.com')
                    ->setTo($emailList)
                    ->setBody($body,'text/html');
                    $mailer = $this->get('mailer');
                    $mailer->send($message);
                    $spool = $mailer->getTransport()->getSpool();
                    $transport = $this->get('swiftmailer.transport.real');
                    $spool->flushQueue($transport);
                }
            }
        }

        // Save Inquiry
        if(strtoupper($RESTdata['userName']) != "GARGAMEL") $returnedval = $data->CallRest("need-more-information", "dev", "post", $RESTdata, true);

        $contentDownloadExtra = array(
            "name" => $RESTdata['userName'],
            "company" => $RESTdata['companyName'],
            "country" => $RESTdata['country'],
            "phone" => $RESTdata['telNumber'],
            "message" => $RESTdata['message'],
            "domain" => $request->getHost(),
            "requestfrom" => $request->headers->get('referer')
        );

        $Global = $this->get('global_functions');
        if(strtoupper($RESTdata['userName']) != "GARGAMEL") {
            $Global->saveContentDownload($RESTdata['emailAddress'], "Inquiry", "", $request, $contentDownloadExtra);
        } else { $returnedval = true; }

        if($returnedval && $returnedval > 0) {
            return new Response("true");
        } else { return new Response("false"); }
        return new Response("false");
    }

    /**
     * @Route("/asia-inspection-partners", name ="aboutus_Our Partners")
     * @Template()
     */
    public function partnersAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/asia-inspection-partners.xml');
        $array = $this->getXMLAndParameters($path, $request);
        $xml = $array['xml'];

        $name = array();
        $logo = array();
        $link = array();

        foreach ($xml->partner as $partner) {
            $name[] = $partner['name'];
            $logo[] = $partner['logo'];
            $link[] = $partner['link'];
        }

        $array['name'] = $name;
        $array['logo'] = $logo;
        $array['link'] = $link;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('partners', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/ourPartners.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:ourPartners.html.twig', $array);
        }
    }
    /**
     * @Route("/asiainspection-testimonials", name ="aboutus_Testimonials")
     * @Template()
     */
    public function testimonialsAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/testimonials.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/SubscribeNewsletterBox.xml');

        $xml = simplexml_load_file($path2);
        if($xml->title->$locale !="")
            $boxTitle = $xml->title->$locale;
        else
            $boxTitle = $xml->title->en;
        if($xml->placeholderEmail->$locale!="")
            $boxPlaceholderEmail = $xml->placeholderEmail->$locale;
        else
            $boxPlaceholderEmail = $xml->placeholderEmail->en;
        if($xml->submitText->$locale!="")
            $boxSubmitText = $xml->submitText->$locale;
        else
            $boxSubmitText = $xml->submitText->en;
        $array['boxTitle'] = $boxTitle;
        $array['boxPlaceholderEmail'] = $boxPlaceholderEmail;
        $array['boxSubmitText'] = $boxSubmitText;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('testimonials', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/testimonials.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:testimonials.html.twig', $array);
        }
    }


    /**
     * @Route("/accreditations", name ="aboutus_Accreditations")
     * @Template()
     */
    public function accreditationsAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/SubscribeNewsletterBox.xml');

        $xml = simplexml_load_file($path2);
        if($xml->title->$locale !="")
            $boxTitle = $xml->title->$locale;
        else
            $boxTitle = $xml->title->en;
        if($xml->placeholderEmail->$locale!="")
            $boxPlaceholderEmail = $xml->placeholderEmail->$locale;
        else
            $boxPlaceholderEmail = $xml->placeholderEmail->en;
        if($xml->submitText->$locale!="")
            $boxSubmitText = $xml->submitText->$locale;
        else
            $boxSubmitText = $xml->submitText->en;
        $array['boxTitle'] = $boxTitle;
        $array['boxPlaceholderEmail'] = $boxPlaceholderEmail;
        $array['boxSubmitText'] = $boxSubmitText;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('accreditations', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/accreditations.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:accreditations.html.twig', $array);
        }
    }
    /**
     * @Route("/samplereports", name ="newsAndContent_Sample Reports")
     * @Template()
     */
    public function sampleReportsAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/sampleReports.xml');
        $array = $this->getXMLAndParameters($path, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('sample-reports', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:sampleReports.html.twig', $array);
    }
    /**
     * @Route("/404", name ="Page Not Found")
     * @Template()
     */
    public function pageNotFoundAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/pageNotFound.xml');
        $array = $this->getXMLAndParameters($path, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('page-not-found', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:pageNotFound.html.twig', $array);
    }

    /**
     * @Route("/affiliate-program")
     * @Template()
     */
    public function affiliateProgramAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/affiliate-program.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $price_Inspect_Low = $this->container->getParameter('price_Inspect_Low');
        $price_Audit_High = $this->container->getParameter('price_Audit_High');
        $array['xml']->programDetailsText = str_replace("{{price_Inspect_Low}}", $price_Inspect_Low, $array['xml']->programDetailsText);
        $array['xml']->programDetailsText = str_replace("{{price_Audit_High}}", $price_Audit_High, $array['xml']->programDetailsText);

        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/affiliateTerms.xml');

        $xml = simplexml_load_file($path2);
        $xml= $xml->en;
        $array['termsAndConditions'] =$xml;
        $array['url'] = $array['xml']->joinButton['url'];
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('affiliate-program', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:affiliate-program.html.twig', $array);
    }

    /**
     * @Route("/affiliate-registration")
     * @Template()
     */
    public function affiliateRegistrationAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/affiliate-registration.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');

        $xml = simplexml_load_file($path2);
        $array['countries'] =$xml;
        $path3 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/affiliateTerms.xml');
        $countryName = array();
        foreach ($xml as $country) {
            $countryName[]= Intl::getRegionBundle()->getCountryName((string)$country->iso2,$locale);
        }
        $array['countryNames']=$countryName;


        $xml1 = simplexml_load_file($path3);
        $xml1= $xml1->en;
        $array['termsAndConditions'] =$xml1;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('affiliate-registration', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:affiliate-registration.html.twig', $array);
    }

    /**
     * @Route("/affiliate-success")
     * @Method("POST")
     * @Template()
     */
    public function affiliateSuccessAction(Request $request) {

        $success = false;
        $params = $this->getRequest()->request->all();
        $data = new Data();
        $conn = $data->getDBconnection();

        if( !$conn['error'] && $params['affCompany'] != "" && $params['affFirstName'] != "" && $params['affLastName'] != "" && $params['affEmail'] != "" ) {
            $success = true;
            $db = $conn['connection'];
            mysqli_set_charset($db, "utf8");
            if($sql = mysqli_prepare($db, "INSERT INTO affiliates (registrationdate, company, affiliatename, email, paypal, bankname, url) VALUES ('".date("Y-m-d")."', '".$params['affCompany']."', '".$params['affFirstName']." ".$params['affLastName']."', '".$params['affEmail']."', '".$params['affPaypalEmail']."', '".$params['affBankName']."', '".$params['affBannerURL']."');")) {
                $returnedval = mysqli_stmt_execute($sql);
                if ($returnedval) {
                    $success = true;
                    // Notification email about waiting request
                    $body = "A new affiliate has applied, you can accept or reject them on the <a href='https://www.asiainspection.com/leads-tool/affiliate-management'>Affiliate Management Page</a>.<br /><br /><b>Name:</b> ".$params['affFirstName']." ".$params['affLastName']."<br /><br /><b>Company:</b> ".$params['affCompany']."<br /><br /><b>Email:</b> ".$params['affEmail']."<br /><br />";
                    $emails = array("michael.mesarch@asiainspection.com");
                    $message = \Swift_Message::newInstance()
                    ->setSubject("An Affiliate is Waiting to be Approved.")
                    ->setFrom('marketing@asiainspection.com')
                    ->setTo($emails)
                    ->setBody($body,'text/html');
                    $mailer = $this->get('mailer');
                    $mailer->send($message);
                    $spool = $mailer->getTransport()->getSpool();
                    $transport = $this->get('swiftmailer.transport.real');
                    $spool->flushQueue($transport);
                    // End Email
                }
                $db->close();
            }   
        }
        
        return $this->render('AIResponsiveBundle:aboutUs:affiliate-success.html.twig', array("success" => $success));
    }

    /**
     * @Route("/affiliate/get_started")
     * @Template()
     */
    public function affiliateGetStartedAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/affiliate-get-started.xml');
        $array = $this->getXMLAndParameters($path, $request);

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('affiliate-getstarted', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:affiliate-getstarted.html.twig', $array);
    }

    /**
     * @Route("/how-to-approve-reject-reports")
     * @Template()
     */
    public function approveRejectReportsAction(Request $request) {
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('approve-reject-reports', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:approve-reject-reports.html.twig', $array);
    }

    /**
     * @Route("/aql-acceptable-quality-limit", name ="serviceDetail_AQL Acceptable Quality Limit")
     * @Template()
     */
    public function qualityLimitAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/aql-acceptable-quality-limit.xml');
        $array = $this->getXMLAndParameters($path, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $array['tracking'] = $tracking->getTrackingCode('aql-acceptable-quality-limit', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/aql-acceptable-quality-limit.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:aql-acceptable-quality-limit.html.twig', $array);
        }
    }
    /**
     * @Route("/career-opportunities", name ="careers_Career Opportunities")
     * @Template()
     */
    public function careersAction(Request $request) {
        $this->checkLocale();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('career-ops', $locale);
        //include Tracking data [End]
        $array['pageTitle'] = "Careers | AsiaInspection.com";
        $array['pageKeywords'] = "";
        $array['pageDesc'] = "Take your career to the next level with AsiaInspection! Work with professionals from all over the globe as part of a growing company, in a quickly-evolving industry. View opportunities and apply online now.";
        $locale = $request->getLocale();
        return $this->render('AIResponsiveBundle:aboutUs:avature.html.twig', $array);
    }

    /**
     * @Route("/toy-safety-laboratory-testing")
     * @Template()
     */
    public function toyLabTestAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/toy-safety-laboratory-testing-2010.xml');
        $array = $this->getXMLAndParameters($path, $request);
        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/SafeToysBox.xml');
        $xml = simplexml_load_file($path2);
        if($xml->$locale !="")
            $toybox = $xml->$locale;
        else
            $toybox = $xml->en;
        $array['toybox']=$toybox;
        $array['type']="toyLabTest";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('toy-safety-laboratory-testing', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/toyLabTest.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:toyLabTest.html.twig', $array);
        }
    }
    /**
     * @Route("/phthalate-toys-laboratory-testing",name="Phthalate Toys Testing")
     * @Template()
     */
    public function phthalateAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/phthalate-toys-laboratory-testing.xml');
        $array = $this->getXMLAndParameters($path, $request);
        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/SafeToysBox.xml');

        $xml = simplexml_load_file($path2);
        if($xml->$locale !="")
            $toybox = $xml->$locale;
        else
            $toybox = $xml->en;
        $array['toybox']=$toybox;
        $array['type']="phthalate";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('phthalate-testing', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/toyLabTest.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:toyLabTest.html.twig', $array);
        }
    }

    /**
     * @Route("/job-application", name ="careers_Job Application")
     *
     */
    public function jobAppAction(Request $request) {
        $this->checkLocale();
        $locale=$request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/jobapp.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');
        $countries = simplexml_load_file($path2);

        $path3 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/careerops.xml');
        $locale = $request->getLocale();
        $sidebox = simplexml_load_file($path3);
        if($sidebox->$locale!="")
            $sidebox = $sidebox->$locale;
        else
            $sidebox = $sidebox->en;
        $array['sidebox'] = $sidebox;
        $countryName = array();
        foreach ($countries as $country) {
            $countryName[]= Intl::getRegionBundle()->getCountryName((string)$country->iso2,$locale);
        }
        $array['countryNames']=$countryName;

        if(isset($_GET["title"])){
            $title = $_GET["title"];
            $array['title'] = $title;
        }
        if(isset($_GET["id"])){

            $jobid = $_GET["id"];
            $array['jobid'] = $jobid;

            $array['jobDescription'] = $this->getJobDescription($jobid);
        }

        $array['countries'] = $countries;

        $tag = array();
        $xml = $array['xml'];
        foreach ($xml->WhereHear->opt as $Heard){
            $tag[] = $Heard['tag'];
        }
        $array['current_url'] = $_SERVER['SCRIPT_NAME'];
        $array['tag'] = $tag;
        $array['followus'] = $followus = $this->getFollowUs()->$locale;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('job-app', $locale);
        //include Tracking data [End]
        //[Begin] If Applying from linkedIn
        if(isset($_GET['code'])){

            $linkedInCode = $_GET['code'];
            $url = "https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&code=".$linkedInCode."&redirect_uri=http%3A%2F%2Fstaging.asiainspection.com:99%2Fjob-application%3Fid=".$jobid."&client_id=at9xr9dbgd9d&client_secret=xjAExqgxDoWRSeoT";
            $curl = curl_init();
            $header = array("Connection: Keep-Alive","Content-Type: application/x-www-form-urlencoded","Authorization: Bearer " . $linkedInCode);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $returnval = json_decode(curl_exec($curl), true);
            $array['linkedInAuthError'] = curl_error($curl);
            curl_close($curl);
            $array['linkedInAuth'] = $returnval;
            if(isset($returnval['access_token'])){
                $accessToken= $returnval['access_token'];
                $array['userData'] = $this->getUserDataLinkedIn($accessToken);
            }
        }
        //[End] If Applying from linkedIn
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/jobapp.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:jobapp.html.twig', $array);
        }
        
    }
    public function getUserDataLinkedIn($accessToken){
        //$service_url = "https://api.linkedin.com/v1/people/~:(first-name,last-name,email-address,location)?oauth2_access_token=".$accessToken."&format=json";
        $service_url = "https://api.linkedin.com/v1/people/~:(first-name,last-name,date-of-birth,phone-numbers,main-address,email-address,location,summary,specialties,skills)?oauth2_access_token=".$accessToken."&format=json";
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            echo curl_errno($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        return $decoded;

    }

    /**
     * @Route("/conditions-of-service", name ="serviceDetail_Terms And Conditions")
     * @Template()
     */
    public function termsAndConditionsAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/conditions-of-service.xml');
        $array = $this->getXMLAndParameters($path, $request);
        $shortname = array();
        foreach ($array['xml']->section as $section) {
            $shortname[] = $section['shortname'];
        }
        $array['shortname']=$shortname;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('conditions-of-service', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:aboutUs:conditions-of-service.html.twig', $array);
    }

    /**
     * @Route("/reference-samples", name ="serviceDetail_Reference Samples")
     * @Template()
     */
    public function referenceSamplesAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $twigData = array();

        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/referencesamples.xml');

        $path1 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/InspectionSamplePolicyBox.xml');
        $box1 = simplexml_load_file($path1);
        $twigData['box1'] = ($box1->$locale != "" ? $box1->$locale : $box1->en);

        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/SendSamplesBox.xml');
        $box2 = simplexml_load_file($path2);
        $twigData['box2'] = ($box2->$locale != "" ? $box2->$locale : $box2->en);

        $path3 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/SamplesStorageBox.xml');
        $box3 = simplexml_load_file($path3);
        $twigData['box3'] = ($box3->$locale != "" ? $box3->$locale : $box3->en);

        $xml = simplexml_load_file($path);

        //get coutry title list from the current user language
        $countrypath = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/servicecoverage.xml');
        $tmpCover = simplexml_load_file($countrypath);
        $coverxml = $tmpCover->en;
        $coverxml->title = $tmpCover->$locale->title;
        $coverxml->subtext = $tmpCover->$locale->subtext;

        foreach ($coverxml->countries->MiddleEast->area as $area)  $coverxml->countries->Africa->area[] = $area;
        $countries = $coverxml->countries;

        $twigData['countryTitles'] = array($countries->Asia['title'], $countries->Africa['title'], $countries->Europe['title'], $countries->LatinAmerica['title']);
        $twigData['countryList'] = $coverxml->countries;
        $countryListEn = $this->getCountryNameEn();

        $twigData['asia'] = $countryListEn['asia'];
        $twigData['latinAmerica'] = $countryListEn['latinAmerica'];
        $twigData['africa'] = $countryListEn['africa'];
        $twigData['europe'] = $countryListEn['europe'];

        //get the xml from the current user language
        $xmlold = $xml;
        $xml = $xml->$locale;

        $twigData['offices'] = $xmlold->en->offices;

        $regionNames = array();
        foreach ($xmlold->en->offices->Region as $region) {
            $regionNames[] = str_replace(" ", "", $region['name']);
        }
        $twigData['regionNames'] = $regionNames;

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('reference-samples', $locale);
        //include Tracking data [End]

        $twigData['pageTitle'] = ( isset($xmlold->pageMeta->pageTitle->$locale) ? $xmlold->pageMeta->pageTitle->$locale : $xmlold->pageMeta->pageTitle->en );
        $twigData['pageDesc'] = ( isset($xmlold->pageMeta->pageDesc->$locale) ? $xmlold->pageMeta->pageDesc->$locale : $xmlold->pageMeta->pageDesc->en );
        $twigData['xml'] = $xml;
        $twigData['coverxml'] = $coverxml;
        
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs/referenceSamples.html.twig', $twigData);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:referenceSamples.html.twig', $twigData);
        }
    }

    /**
     * @Route("/regulatory-updates", name ="newsAndContent_Regulatory Updates")
     * @Template()
     */
    public function regulatoryUpdatesAction(Request $request) {
        $this->checkLocale();

        //Find the regulatory updates folder

        $locale = $request->getLocale();
        try {
            // Try the localized folder
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/views/news/regulatoryupdates/'.$locale.'/');
        } catch(\Exception $e) {
            // Fallback to English
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/views/news/regulatoryupdates/en');
        }

        $regUpdates = scandir($path);
        //Foreach file, get the first line and json decode it to get the title and description
        foreach($regUpdates as $file){
            if( strpos($file, ".html") > -1 ){
                $line = fgets(fopen($path."/".$file, 'r'));
                $start = strpos($line,"{");
                $end = strrpos($line,"}");
                $line = substr($line, $start, (($end - $start)+1));
                $line = json_decode($line);
                $line->id = substr($file, 0, strrpos($file,".html"));
                $twigData['regUpdates'][] = $line;
            }
        }
        //Sorting the posts by date
        usort($twigData['regUpdates'], function($a, $b){
            if ($a->PublishDate == $b->PublishDate) {
                return 0;
            }
            return ($a->PublishDate < $b->PublishDate) ? 1 : -1;
        });

        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        $Phonexml = simplexml_load_file($this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/ContactUsByPhoneBox.xml'));
        $twigData['contacts'] = $Phonexml;

        if (isset($_SERVER['HTTP_CLIENT_IP'])){
            $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $real_ip_adress = $_SERVER['REMOTE_ADDR'];
        }

        $countryList = array();
        foreach($Phonexml->Locations->region as $region){
            foreach($region->office as $o){
                $countryList[]=$o->countryCode;
            }
        }
        $cip = $real_ip_adress;
        $url = "http://ipinfo.io/".$cip."/country";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $userCountry = trim(curl_exec($curl));
        if(!in_array($userCountry,$countryList)){
            $userCountry = "HK";
        }
        $twigData['userCountry'] = $userCountry;

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('regulatoryupdates', $locale);
        //include Tracking data [End]
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:aboutUs:regulatoryUpdates.html.twig', $twigData);
        } else {
            return $this->render('AIResponsiveBundle:aboutUs:regulatoryUpdates.html.twig', $twigData);
        }
    }

    /**
     * @Route("/regulatory-updates/{id}")
     * @Route("/regulatory-updates/{id}/{section}")
     * @Template()
     */
    public function regulatoryUpdateAction(Request $request, $id, $section=null) {
        $this->checkLocale();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('regulatoryupdates', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();

        try {
            // Try the localized folder
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/views/news/regulatoryupdates/'.$locale.'/');
        } catch(\Exception $e) {
            // Fallback to English
            $locale = "en";
        }

        $section = strtolower($section);
        $SEOtitle = "";
        $SEOkeywords = "";
        $SEOdesc = "";
        $SEOanchor = "";

        $customSEOpath = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/views/news/regulatoryupdates/'.$locale.'/metadata.xml');
        $customSEO = simplexml_load_file($customSEOpath);
        $SEOdesc = $customSEO->$id->desc;
        $SEOtitle = $customSEO->$id->title;

        if( isset($section) ) {
            try {               
                foreach( $customSEO->$id->section as $opt ){
                    if( $opt->attributes()->url == $section ){
                        $SEOtitle = $opt->title;
                        $SEOkeywords = $opt->keywords;
                        $SEOdesc = $opt->desc;
                        $SEOanchor = $opt->anchor;
                    }
                }
            } catch(\Exception $e) {
                // Do nothing
            }
        }

        $twigData['id'] = $id;
        $twigData['locale'] = $locale;
        $twigData['pageTitle'] = $SEOtitle;
        $twigData['pageKeywords'] = $SEOkeywords;
        $twigData['pageDesc'] = $SEOdesc;
        $twigData['pageAnchor'] = $SEOanchor;
        
        return $this->render('AIResponsiveBundle:news:regulatoryUpdate.html.twig', $twigData);
    }


    /**
     * @Route("/mobile-regulatory-updates/{id}")
     * @Template()
     */
    public function regulatoryUpdateMobileAction(Request $request, $id) {
        $this->checkLocale();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('mobile-regulatory-update', $locale);
        //include Tracking data [End]
        $twigData['id'] = $id;
        $twigData['path'] = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/views/news/regulatoryupdates/en/'.$id.'.html.twig');
        $twigData['type'] = 'reg-update';
        return $this->render('AIResponsiveBundle:news:mobileStory.html.twig', $twigData);
    }


    /**
     * @Route("/supply-chain-insights", name ="newsAndContent_Supply Chain Insights")
     * @Template()
     */
    public function supplyChainInsightsAction(Request $request) {
        $this->checkLocale();
        $twigData = $this->getWhitePaperData($request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('whitepaper-landing', $locale);
        //include Tracking data [End]

        return $this->render('AIResponsiveBundle:aboutUs:whitepapersLanding.html.twig', $twigData);
    }

    public function getWhitePaperData($request){
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/white-paper.xml');
        $xml = simplexml_load_file($path);
        $locale = $request->getLocale();
        if($xml->Landing->$locale->section!="")
            $sections = $xml->Landing->$locale->section;
        else
            $sections = $xml->Landing->en->section;
        $papers = $xml->whitePaper->item;
        /**see if we output this paper**/
        $id = array();
        foreach ($sections as $section) {
            $id[] = $section['id'];
        }
        $twigData = array();
        if($xml->$locale!="")
            $twigData['xml'] = $xml->$locale;
        else
            $twigData['xml'] = $xml->en;
        if($xml->Landing->$locale!=""){
            $twigData['landing'] = $xml->Landing->$locale;
        }
        else
            $twigData['landing'] = $xml->Landing->en;
        $twigData['id'] = $id;
        return $twigData;
    }
    /**
     * @Route("/whitepaper/{id}")
     */
    public function whitePaperAction(Request $request, $id) {
        $this->checkLocale();
        $existingLead = false;
        if ( isset($_COOKIE['email']) && $_COOKIE['email'] != "" ) $existingLead = true;
        if ( isset($_GET['existingLead']) && $_GET['existingLead'] == "true" ) $existingLead = true;

        $twigData = $this->downloadWhitePaperData($request,$id);

        $valid = false;
        $content = null;
        $whitepapers = $twigData['whitepaper'];
        foreach ($whitepapers->item as $whitePaper) {
            if ($whitePaper->id == $id) {
                $valid = true;
                // We save the information of the specified content
                $twigData['content'] = $whitePaper;
                break;
            }
        }

        // in case some parts are missing from dif languages
        foreach ($twigData['whitepaperEng']->item as $w) {
            if($w->id == $id) $twigData['englishContent'] = $w;
        }

        if (!$valid) return header('Location: /404');

        
        $twigData['existingLead'] = $existingLead;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('whitepaper', $locale, $id);
        //include Tracking data [End]

        if(count($_GET)){
            if(isset($_GET['_locale'])){
                if(count($_GET)>1)
                    $twigData['param'] =1;
            }
            else
                $twigData['param'] =1;
        }

        //uses the new layout for selected whitepapers only
        if($twigData['content']->layout == "new"){
            return $this->render('AIResponsiveBundle:aboutUs:whitepapersnew.html.twig', $twigData);
        }else{
            return $this->render('AIResponsiveBundle:aboutUs:whitepapers.html.twig', $twigData);
        }
    }
    public function downloadWhitePaperData($request,$id) {
        $locale = $request->getLocale();
        $twigData = array();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/white-paper.xml');
        $xml = simplexml_load_file($path);
        if($xml->general->$locale!="") {
            $general = $xml->general->$locale;
        } else {
            $general = $xml->general->en;
        }
        $valid = false;
        if($xml->$locale->whitePaper != "") {
            foreach ($xml->$locale->whitePaper->item as $whitePaper) {
                if ($whitePaper->id == $id) {
                    $valid = true;
                    break;
                }
            }
        }
        $whitepapers = ( $valid ? $xml->$locale->whitePaper : $xml->en->whitePaper );

        $twigData['id'] = $id;
        $twigData['whitepaper'] = $whitepapers;
        $twigData['whitepaperEng'] = $xml->en->whitePaper;
        $twigData['general']=$general;

        return $twigData;

    }


    public function getCountryNameEn() {
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/servicecoverage.xml');
        $xml = simplexml_load_file($path);
        foreach ($xml->en->countries->Asia->area as $area) {
                $asia[] = $area;
            }
        foreach ($xml->en->countries->Europe->area as $area)  $europe[] = $area;
        foreach ($xml->en->countries->LatinAmerica->area as $area) $latinAmerica[] = $area;
        foreach ($xml->en->countries->Africa->area as $area) $africa[] = $area;
        foreach ($xml->en->countries->MiddleEast->area as $area) $africa[] = $area;

        return array("asia"=>$asia,"europe"=>$europe,"latinAmerica"=>$latinAmerica,"africa"=>$africa);
    }

    /**returns the coverxml**/
    public function getCoverxml($request) {
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/servicecoverage.xml');
        $coverxml = simplexml_load_file($path);
        return $coverxml->$locale;
    }

    public function getXMLAndParameters($path, $request) {
        $locale = $request->getLocale();
        $xml = simplexml_load_file($path);

        if( isset($xml->pageMeta->pageTitle->$locale) && $xml->pageMeta->pageTitle->$locale != ""){
            $pageTitle = $xml->pageMeta->pageTitle->$locale;
        }else{
            $pageTitle = $xml->pageMeta->pageTitle->en;
        }

        $pageDesc = ($xml->pageMeta->pageDesc->$locale != "" ? $xml->pageMeta->pageDesc->$locale : $xml->pageMeta->pageDesc->en );
        $pageKeywords = ($xml->pageMeta->pageKeywords->$locale != "" ? $xml->pageMeta->pageKeywords->$locale : $xml->pageMeta->pageKeywords->en );

        $xml = ( $xml->$locale != "" ? $xml->$locale : $xml->en );

        return array('xml' => $xml, 'pageTitle' => $pageTitle, 'pageDesc' => $pageDesc, 'pageKeywords'=>$pageKeywords);
    }

    public function getFollowUs() {
        $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/FollowAIBox.xml');
        $followus = simplexml_load_file($path2);
        return $followus->title;
    }

    /**
     * @Route("/termsAndConditionsAlliaffiliate")
     *
     */
    public function termsAndConditionsAlliaffiliate(Request $request) {
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/affiliateTerms.xml');

        $xml = simplexml_load_file($path);
        $xml= $xml->en;
        return $this->render('AIResponsiveBundle:aboutUs:affiliateTerms.html.twig', array('xml'=>$xml));
    }

    /**
     * @Route("/surveyThanks")
     *
     */
    public function surveyThanksAction(Request $request) {
        $Global = $this->get('global_functions');
        $locale = $request->getLocale();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $twigData['tracking'] = $tracking->getTrackingCode('survey-thanks', $locale);
        //include Tracking data [End]
        
        $Phonexml = simplexml_load_file($this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/ContactUsByPhoneBox.xml'));
        $twigData['contacts'] = $Phonexml;
        if (isset($_SERVER['HTTP_CLIENT_IP'])){
            $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $real_ip_adress = $_SERVER['REMOTE_ADDR'];
        }

        $countryList = array();
        foreach($Phonexml->Locations->region as $region){
            foreach($region->office as $o){
                $countryList[]=$o->countryCode;
            }
        }
        $cip = $real_ip_adress;
        $url = "http://ipinfo.io/".$cip."/country";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $userCountry = trim(curl_exec($curl));
        if(!in_array($userCountry,$countryList)){
            $userCountry = "HK";
        }
        $twigData['userCountry'] = $userCountry;

        $data = new Data();

        if( isset($_POST['surveyComments']) ) {
            $twigData['feedbackGiven'] = true;
            if (isset($_POST['recordID']) && isset($_POST['token']) && $this->isCsrfTokenValid('thanks', $_POST['token'])) {
                $recordID = $_POST['recordID'];
                $comments = $_POST['surveyComments'];
                //Try to get a connection to the database and redirect to error page on failure
                $conn = $data->getDBconnection();
                if( !$conn['error'] && is_numeric($recordID) ){
                    $db = $conn['connection'];
                    mysqli_set_charset($db, "utf8");
                    $sql = "UPDATE SurveyResults SET survey_comments='".$db->real_escape_string($comments)."' WHERE id=".$recordID;
                    $returnedval = $db->query($sql);
                    $db->close();
                }
            }
            
        } else {

            $conn = $data->getDBconnection();
            $db = $conn['connection'];
            mysqli_set_charset($db, "utf8");

            $responses = array(
                'no_shipments'=>'no_shipments',
                'noshipments'=>'no_shipments',
                'unhappy_with_price' => 'unhappy',
                'prices' => 'unhappy',
                'unhappy' => 'unhappy',
                'unhappy_with_service' => 'unhappy',
                'service' => 'unhappy',
                'unhappy_with_manager' => 'unhappy',
                'am' => 'unhappy',
                'performed_internally' => 'performed_internally', 
                'other_problem' => 'other_problem',
                'other' => 'other_problem',
                'website' => 'other_problem',
                'website_problems' => 'other_problem',
                );
            $sql = "SELECT * FROM SurveyResponses";
            $returnedval = $db->query($sql);
            while ($row = $returnedval->fetch_array()) {
                $responses[$row['shortname']] = $row['shortname'];
            }
            $survey = ( isset($_GET['survey']) ? $_GET['survey'] : "" );
            $email = ( isset($_GET['email']) ? $_GET['email'] : "" );
            $response = ( isset($_GET['answer']) ? $_GET['answer'] : "" );
            if ( strpos($survey,";") === FALSE && strpos($email,";") === FALSE && array_key_exists($response, $responses) ) {
                //Try to get a connection to the database and redirect to error page on failure
                $conn = $data->getDBconnection();
                if( !$conn['error'] ){
                    $db = $conn['connection'];
                    mysqli_set_charset($db, "utf8");
                    $sql = "INSERT INTO SurveyResults (survey_id, email, response) VALUES ('".$survey."', '".$email."', '".$responses[$response]."')";
                    $returnedval = $db->query($sql);
                    $twigData['recordID'] = $db->insert_id;
                    $db->close();
                }
            }
        }

        // Send Alert to Sales via Marketo
        $survey = ( isset($_GET['survey']) ? $_GET['survey'] : "" );
        $email = ( isset($_GET['email']) ? $_GET['email'] : "" );
        $response = ( isset($_GET['answer']) ? $_GET['answer'] : "" );
        $survey = str_replace ("_", " ", $survey);
        $response = str_replace ("_", " ", $response);
        $comments = ( isset($_POST['surveyComments']) ? $_POST['surveyComments'] : "" );
        $surveyTitle = ( isset($_POST['surveyComments']) ? "A Client of Yours has Just Left Some Feedback" : "A Client of Yours Just Answered a Survey" );

        $customVars = array(
            "{{my.surveyEmailTitle}}" => $surveyTitle,
            "{{my.surveyName}}" => $survey,
            "{{my.surveyResponse}}" => $response,
            "{{my.surveyComments}}" => $comments
        );
        $marketoTriggers = array(
            "inactive client survey" => "1609"
            //"registered with no order" => "",
            //"sic lost client" => "",
        );

        if( in_array($survey, array_keys($marketoTriggers)) ) {
            $Global->sendMarketoAlert($marketoTriggers[$survey], $customVars, $email);    
        }

        return $this->render('AIResponsiveBundle:aboutUs:surveyThanks.html.twig', $twigData);
    }

    /**
     * @Route("/internal-survey")
     *
     */
    public function internalSurveyAction(Request $request) {
        $Global = $this->get('global_functions');
        
        $Phonexml = simplexml_load_file($this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/ContactUsByPhoneBox.xml'));
        $twigData['contacts'] = $Phonexml;
        if (isset($_SERVER['HTTP_CLIENT_IP'])){
            $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $real_ip_adress = $_SERVER['REMOTE_ADDR'];
        }

        $countryList = array();
        foreach($Phonexml->Locations->region as $region){
            foreach($region->office as $o){
                $countryList[]=$o->countryCode;
            }
        }
        $cip = $real_ip_adress;
        $url = "http://ipinfo.io/".$cip."/country";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $userCountry = trim(curl_exec($curl));
        if(!in_array($userCountry,$countryList)){
            $userCountry = "HK";
        }
        $twigData['userCountry'] = $userCountry;

        $data = new Data();

        if( isset($_POST['surveyComments']) ) {
            $twigData['feedbackGiven'] = true;
            if (isset($_POST['recordID']) && is_numeric($_POST['recordID'])) {
                $recordID = $_POST['recordID'];
                $comments = $_POST['surveyComments'];
                //Try to get a connection to the database and redirect to error page on failure
                $conn = $data->getDBconnection();
                if( !$conn['error'] && is_numeric($recordID) ){
                    $db = $conn['connection'];
                    mysqli_set_charset($db, "utf8");
                    $sql = "UPDATE InternalSurveyResults SET survey_comments='".$db->real_escape_string($comments)."' WHERE id=".$recordID;
                    $returnedval = $db->query($sql);
                    $db->close();
                }
            }
            
        } else {

            $conn = $data->getDBconnection();
            $db = $conn['connection'];
            mysqli_set_charset($db, "utf8");

            $responses = array(
                'no_shipments'=>'no_shipments',
                'noshipments'=>'no_shipments',
                'unhappy_with_price' => 'unhappy',
                'prices' => 'unhappy',
                'unhappy' => 'unhappy',
                'unhappy_with_service' => 'unhappy',
                'service' => 'unhappy',
                'unhappy_with_manager' => 'unhappy',
                'am' => 'unhappy',
                'performed_internally' => 'performed_internally', 
                'other_problem' => 'other_problem',
                'other' => 'other_problem',
                'website' => 'other_problem',
                'website_problems' => 'other_problem',
                );
            $sql = "SELECT * FROM InternalSurveyResults";
            $returnedval = $db->query($sql);
            while ($row = $returnedval->fetch_array()) {
                $responses[$row['shortname']] = $row['shortname'];
            }
            $survey = ( isset($_GET['survey']) ? $_GET['survey'] : "" );
            $email = ( isset($_GET['email']) ? $_GET['email'] : "" );
            $response = ( isset($_GET['answer']) ? $_GET['answer'] : "" );
            if ( strpos($survey,";") === FALSE && strpos($email,";") === FALSE && array_key_exists($response, $responses) ) {
                //Try to get a connection to the database and redirect to error page on failure
                $conn = $data->getDBconnection();
                if( !$conn['error'] ){
                    $db = $conn['connection'];
                    mysqli_set_charset($db, "utf8");
                    $sql = "INSERT INTO SurveyResults (survey_id, email, response) VALUES ('".$survey."', '".$email."', '".$responses[$response]."')";
                    $returnedval = $db->query($sql);
                    $twigData['recordID'] = $db->insert_id;
                    $db->close();
                }
            }
        }

        // Send Alert to Sales via Marketo
        $survey = ( isset($_GET['survey']) ? $_GET['survey'] : "" );
        if($survey != "sic_lost_client") {
            //$survey = ( isset($_GET['survey']) ? $_GET['survey'] : "" );
            $email = ( isset($_GET['email']) ? $_GET['email'] : "" );
            $response = ( isset($_GET['answer']) ? $_GET['answer'] : "" );
            $survey = str_replace ("_", " ", $survey);
            $response = str_replace ("_", " ", $response);
            $comments = ( isset($_POST['surveyComments']) ? $_POST['surveyComments'] : "" );
            $surveyTitle = ( isset($_POST['surveyComments']) ? "A Client of Yours has Just Left Some Feedback" : "A Client of Yours Just Answered a Survey" );
    
            $customVars = array(
                "{{my.surveyEmailTitle}}" => $surveyTitle,
                "{{my.surveyName}}" => $survey,
                "{{my.surveyResponse}}" => $response,
                "{{my.surveyComments}}" => $comments
            );
            $Global->sendMarketoAlert(2154, $customVars, $email);
        }

        return $this->render('AIResponsiveBundle:aboutUs:surveyThanks.html.twig', $twigData);
    }

    /**
     * @Route("/spamCheckAffiliateRegForm")
     * @Method("POST")
     */
    public function spamCheckAffiliateRegForm(Request $request) {
        $spam = false;
        $spamDomains = array("0815.ru", "0clickemail.com", "0-mail.com", "0wnd.net", "0wnd.org", "10minutemail.com", "20minutemail.com", "2prong.com", "30minutemail.com", "33mail.com", "357merry.com", "3d-painting.com", "4warding.com", "4warding.net", "4warding.org", "60minutemail.com", "675hosting.com", "675hosting.net", "675hosting.org", "6url.com", "75hosting.com", "75hosting.net", "75hosting.org", "7tags.com", "9ox.net", "a-bc.net", "acupuncturenews.org", "afrobacon.com", "ajaxapp.net", "amilegit.com", "amiri.net", "amiriindustries.com", "anonbox.net", "anonymbox.com", "antichef.com", "antichef.net", "antispam.de", "baxomale.ht.cx", "beefmilk.com", "binkmail.com", "bio-muesli.net", "bobmail.info", "bodhi.lawlita.com", "bofthew.com", "brefmail.com", "broadbandninja.com", "bsnow.net", "bugmenot.com", "bumpymail.com", "casualdx.com", "centermail.com", "centermail.net", "chogmail.com", "choicemail1.com", "cool.fr.nf", "correo.blogos.net", "cosmorph.com", "courriel.fr.nf", "courrieltemporaire.com", "cubiclink.com", "curryworld.de", "cust.in", "cuvox.de", "dacoolest.com", "dandikmail.com", "dayrep.com", "deadaddress.com", "deadspam.com", "despam.it", "despammed.com", "devnullmail.com", "dfgh.net", "digitalsanctuary.com", "discardmail.com", "discardmail.de", "disposableaddress.com", "Disposableemailaddresses:emailmiser.com", "disposeamail.com", "disposemail.com", "dispostable.com", "dm.w3internet.co.ukexample.com", "dodgeit.com", "dodgit.com", "dodgit.org", "donemail.ru", "dontreg.com", "dontsendmespam.de", "dumpandjunk.com", "dump-email.info", "dumpmail.de", "dumpyemail.com", "e4ward.com", "einrot.com", "email60.com", "emaildienst.de", "emailias.com", "emailigo.de", "emailinfive.com", "emailmiser.com", "emailsensei.com", "emailtemporario.com.br", "emailto.de", "emailwarden.com", "emailx.at.hm", "emailxfer.com", "emz.net", "enterto.com", "ephemail.net", "espritblog.org", "etranquil.com", "etranquil.net", "etranquil.org", "explodemail.com", "fakeinbox.com", "fakeinformation.com", "fastacura.com", "fastchevy.com", "fastchrysler.com", "fastkawasaki.com", "fastmazda.com", "fastmitsubishi.com", "fastnissan.com", "fastsubaru.com", "fastsuzuki.com", "fasttoyota.com", "fastyamaha.com", "filzmail.com", "fizmail.com", "fr33mail.info", "frapmail.com", "front14.org", "fux0ringduh.com", "garliclife.com", "get1mail.com", "get2mail.fr", "getonemail.com", "getonemail.net", "ghosttexter.de", "girlsundertheinfluence.com", "gishpuppy.com", "gowikibooks.com", "gowikicampus.com", "gowikicars.com", "gowikifilms.com", "gowikigames.com", "gowikimusic.com", "gowikinetwork.com", "gowikitravel.com", "gowikitv.com", "great-host.in", "greensloth.com", "groupd-mail.netpd-mail.net", "gsrv.co.uk", "guerillamail.biz", "guerillamail.com", "guerillamail.net", "guerillamail.org", "guerrillamail.biz", "guerrillamail.com", "guerrillamail.de", "guerrillamail.net", "guerrillamail.org", "guerrillamailblock.com", "gustr.com", "h.mintemail.com", "h8s.org", "haltospam.com", "hatespam.org", "hidemail.de", "hochsitze.com", "hotpop.com", "hulapla.de", "ieatspam.eu", "ieatspam.info", "ihateyoualot.info", "iheartspam.org", "imails.info", "inboxalias.com", "inboxclean.com", "inboxclean.org", "incognitomail.com", "incognitomail.net", "incognitomail.org", "insorg-mail.info", "ipoo.org", "irish2me.com", "iwi.net", "jetable.com", "jetable.fr.nf", "jetable.net", "jetable.org", "jnxjn.com", "junk1e.com", "kasmail.com", "kaspop.com", "keepmymail.com", "killmail.com", "killmail.net", "kir.ch.tc", "klassmaster.com", "klassmaster.net", "klzlk.com", "kulturbetrieb.info", "kurzepost.de", "letthemeatspam.com", "lhsdv.com", "lifebyfood.com", "link2mail.net", "litedrop.com", "lol.ovpn.to", "lookugly.com", "lopl.co.cc", "lortemail.dk", "louisvuittondesignerbags.info", "louisvuittonhandbagsprices.info", "louisvuittonmenwallet.info", "louisvuittonwholesale.info", "lr78.com", "lvbag.info", "lvhandbags.info", "m4ilweb.info", "maboard.com", "mail.by", "mail.mezimages.net", "mail2rss.org", "mail333.com", "mail4trash.com", "mailbidon.com", "mailblocks.com", "mailcatch.com", "maildrop.cc", "maileater.com", "mailexpire.com", "mailfreeonline.com", "mailin8r.com", "mailinater.com", "mailinator.com", "mailinator.net", "mailinator2.com", "mailincubator.com", "mailme.ir", "mailme.lv", "mailmetrash.com", "mailmoat.com", "mailnator.com", "mailnesia.com", "mailnull.com", "mailondandan.com", "mailshell.com", "mailsiphon.com", "mailslite.com", "mail-temporaire.fr", "mailzilla.com", "mailzilla.org", "mbx.cc", "mega.zik.dj", "meinspamschutz.de", "meltmail.com", "messagebeamer.de", "mierdamail.com", "mintemail.com", "moburl.com", "moncourrier.fr.nf", "monemail.fr.nf", "monmail.fr.nf", "msa.minsmail.com", "mt2009.com", "mx0.wwwnew.eu", "mycleaninbox.net", "mypartyclip.de", "myphantomemail.com", "myspaceinc.com", "myspaceinc.net", "myspaceinc.org", "myspacepimpedup.com", "myspamless.com", "mytrashmail.com", "neomailbox.com", "nepwk.com", "nervmich.net", "nervtmich.net", "netmails.com", "netmails.net", "netzidiot.de", "neverbox.com", "nobulk.com", "noclickemail.com", "nogmailspam.info", "nomail.xl.cx", "nomail2me.com", "nomorespamemails.com", "no-spam.ws", "nospam.ze.tc", "nospam4.us", "nospamfor.us", "nospamthanks.info", "notmailinator.com", "nowmymail.com", "nurfuerspam.de", "nus.edu.sg", "nwldx.com", "objectmail.com", "obobbo.com", "oneoffemail.com", "onewaymail.com", "online.ms", "oopi.org", "ordinaryamerican.net", "otherinbox.com", "ourklips.com", "outlawspam.com", "ovpn.to", "owlpic.com", "pancakemail.com", "pimpedupmyspace.com", "pjjkp.com", "pleasegoheretofinish.com", "politikerclub.de", "poofy.org", "pookmail", "privacy.net", "proxymail.eu", "prtnx.com", "punkass.com", "PutThisInYourSpamDatabase.com", "quickinbox.com", "rcpt.at", "recode.me", "recursor.net", "regbypass.com", "regbypass.comsafe-mail.net", "rejectmail.com", "rhyta.com", "rklips.com", "rmqkr.net", "rppkn.com", "rtrtr.com", "s0ny.net", "safe-mail.net", "safersignup.de", "safetymail.info", "safetypost.de", "sandelf.de", "saynotospams.com", "selfdestructingmail.com", "SendSpamHere.com", "sharklasers.com", "shiftmail.com", "shitmail.me", "shortmail.net", "sibmail.com", "skeefmail.com", "slaskpost.se", "slopsbox.com", "smellfear.com", "snakemail.com", "sneakemail.com", "sofimail.com", "sofort-mail.de", "sogetthis.com", "soodonims.com", "spam.la", "spam.su", "spamavert.com", "spambob.com", "spambob.net", "spambob.org", "spambog.com", "spambog.de", "spambog.ru", "spambox.info", "spambox.irishspringrealty.com", "spambox.us", "spamcannon.com", "spamcannon.net", "spamcero.com", "spamcon.org", "spamcorptastic.com", "spamcowboy.com", "spamcowboy.net", "spamcowboy.org", "spamday.com", "spamex.com", "spamfree24.com", "spamfree24.de", "spamfree24.eu", "spamfree24.info", "spamfree24.net", "spamfree24.org", "spamgourmet.com", "spamgourmet.net", "spamgourmet.org", "SpamHereLots.com", "SpamHerePlease.com", "spamhole.com", "spamify.com", "spaminator.de", "spamkill.info", "spaml.com", "spaml.de", "spammotel.com", "spamobox.com", "spamoff.de", "spamslicer.com", "spamspot.com", "spamthis.co.uk", "spamthisplease.com", "spamtrail.com", "speed.1s.fr", "supergreatmail.com", "supermailer.jp", "superrito.com", "suremail.info", "teewars.org", "teleworm.com", "teleworm.us", "tempalias.com", "tempemail.biz", "tempemail.com", "tempe-mail.com", "TempEMail.net", "tempinbox.co.uk", "tempinbox.com", "tempmail.it", "tempmail2.com", "tempomail.fr", "temporarily.de", "temporarioemail.com.br", "temporaryemail.net", "temporaryforwarding.com", "temporaryinbox.com", "thanksnospam.info", "thankyou2010.com", "thisisnotmyrealemail.com", "throwawayemailaddress.com", "tilien.com", "tmailinator.com", "tradermail.info", "trash2009.com", "trash-amil.com", "trashemail.de", "trashmail.at", "trash-mail.at", "trashmail.com", "trash-mail.com", "trashmail.de", "trash-mail.de", "trashmail.me", "trashmail.net", "trashmail.org", "trashmail.ws", "trashmailer.com", "trashymail.com", "trashymail.net", "trillianpro.com", "turual.com", "twinmail.de", "tyldd.com", "uggsrock.com", "upliftnow.com", "uplipht.com", "venompen.com", "veryrealemail.com", "viditag.com", "viewcastmedia.com", "viewcastmedia.net", "viewcastmedia.org", "webm4il.info", "wegwerfadresse.de", "wegwerfemail.de", "wegwerfmail.de", "wegwerfmail.net", "wegwerfmail.org", "wetrainbayarea.com", "wetrainbayarea.org", "wh4f.org", "whyspam.me", "willselfdestruct.com", "winemaven.info", "wronghead.com", "wuzup.net", "wuzupmail.net", "www.e4ward.com", "www.gishpuppy.com", "www.mailinator.com", "wwwnew.eu", "xagloo.com", "xemaps.com", "xents.com", "xmaily.com", "xoxy.net", "yep.it", "yogamaven.com", "yopmail.com", "yopmail.fr", "yopmail.net", "ypmail.webarnak.fr.eu.org", "yuurok.com", "zehnminutenmail.de", "zippymail.info", "zoaxe.com", "zoemail.org", "armyspy.com", "pookmail", "fleckens.hu", "jourrapide.com");
        $gibberish = array("qw", "dsf", "sdf", "qs", "qz", "xcd", "xcz", "zp", "dx", "vf", "kf", "wq", "gfs", "gfd", "dgf", "fdh", "fdg", "gfh", "gft", "hgf", "hgj", "fgd", "fgh", "fgk", "fgj", "sfg", "dfs", "dfd", "dfg", "dfj", "gdf", "dfh", "hdf", "dfb", "dfx", "df5", "dff", "dfv", "dgs", "sdg", "sdaf", "wss", "gjh", "rhj", "hjk", "fwe", "hfg", "hft", "hfs", "wzx", "czx", "dzx", "xzd", "zzd", "czd");

        $spamReason = array();
        //Check for blacklisted domains in the email address
        $emailDomain = substr($_POST['affEmail'], (strpos($_POST['affEmail'], "@") + 1)); //Get The part of the email after the @
        if (in_array($emailDomain, $spamDomains)) {
            $spam = true;
            $spamReason['affEmail'] = "The domain of your provided email address is on our blacklist.";
        }

        //Check for Gibberish in the company name
        $gibber = false;
        foreach ($gibberish as $gibtest) {
            if (strpos(strtolower($_POST['affCompany']), $gibtest) !== false) {
                $spam = true;
                $spamReason['affCompany'] = "The company name you provided has triggered our spam filter.";
            }
        }
        //Remove any company names with 3+ characters repeating (with some exceptions)
        if (preg_match('/(\w)\1{2,}/', $_POST['affCompany'])) {
            if (
                strpos(strtolower($_POST['affCompany']), "www") !== false ||
                strpos(strtolower($_POST['affCompany']), "aaa") !== false ||
                strpos(strtolower($_POST['affCompany']), "ooo") !== false ||
                strpos(strtolower($_POST['affCompany']), "xxx") !== false ||
                strpos(strtolower($_POST['affCompany']), "000") !== false
            ) {
                //This is an exception, ignore it
            } else {
                $spam = true;
                $spamReason['affCompany'] = "The company name you provided has triggered our spam filter.";
            }
        }

        if (strpos(strtolower($_POST['affEmail']), "%20") !== false) {
            $spam = true;
            $spamReason['affEmail'] = "The email address you provided has a space in it";
        }

        if (strpos(strtolower($_POST['affEmail']), " ") !== false) {
            $spam = true;
            $spamReason['affEmail'] = "The email address you provided has a space in it.";
        }

        return new Response(json_encode($spamReason));

    }


    public function checkLocale() {
        if (isset($_GET['_locale'])) {
            $locale = $_GET["_locale"];
            $this->get('request')->setLocale($locale);

        }

    }


    /**
     * @Route("/samplereport/{id}")
     * @Template()
     */
    public function sampleReportLandingAction(Request $request, $id) {
        $this->checkLocale();
        $id = strtoupper($id);
        $twigData['formTypeId'] = 1080; // Default to inspection report

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('samprep-'.$id, $locale);
        //include Tracking data [End]

        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/sampleReportLanding.xml');
        $xml = simplexml_load_file($path);

        if( isset($xml->$id) ){
            $twigData['xml'] = $xml->$id;
        } else {
            $twigData['xml'] = $xml->DEFAULT;
        }

        // Audits
        if( in_array($id, array("SMETAAUDIT","BANGLADESH-AUDIT-REPORT","FACTORY-AUDIT-REPORT","ENVIRONMENTALAUDIT","ETHICAL-AUDIT-REPORT","STRUCTURAL-AUDIT","CTPAT")) ) $twigData['formTypeId'] = 1078;
        // Lab Testing
        if( in_array($id, array("LAB","REACH","ROHS","CPSIA")) ) $twigData['formTypeId'] = 1083;
        // Inspections is the default so we don't need to test for it

        $twigData['id'] = $id;
        $twigData['locale'] = $locale;

        return $this->render('AIResponsiveBundle:aboutUs:sampleReportLanding.html.twig', $twigData);
    }

}
