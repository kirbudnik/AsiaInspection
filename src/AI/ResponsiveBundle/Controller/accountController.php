<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use AI\ResponsiveBundle\Entity\Login;
use AI\ResponsiveBundle\Entity\Register;
use AI\ResponsiveBundle\Form\loginType;
use AI\ResponsiveBundle\Form\registerType;
use AI\ResponsiveBundle\Model\Data;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SunCat\MobileDetectBundle\DeviceDetector\MobileDetector;

class accountController extends Controller {

    /**
     * Creates a new Register entity.
     *
     * @Route("/register")
     * @Route("/ios-register")
     * @Route("/android-register")
     * @Method("POST")
     */
    public function createAction(Request $request) {

        $this->checkLocale();
        $locale = $request->getLocale();
        $entity = new Register();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        // to handle the different views used to register
        $registerUrl = $this->get('session')->get("registerURL");
        if($registerUrl == "/register") $viewTwig = "new";
        if($registerUrl == "/ios-register") $viewTwig = "iosRegister";
        if($registerUrl == "/android-register") $viewTwig = "androidRegister";

        //Trimming the countrycode from the front of the country
        $entity->setCountry(substr($entity->getCountry(),3));

        //Form Custom Validation [Begin]
        $errors = array();
        $filter_Profanity = include $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/profanity.php');
        $filter_Gibberish = include $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/gibberish.php');
        $filter_Domains = include $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/spam_domains.php');

        //Elements to Check
        $elements = array();
        $elements["First name"] = strtolower($entity->getFirstName());
        $elements["Last name"] = strtolower($entity->getLastName());
        $elements["Company"] = strtolower($entity->getCompany());
        $elements["Email"] = strtolower($entity->getEmail());

        //Custom Validation Loop
        foreach ($elements as $key => $element) {
            //Check for Profanity
            foreach($filter_Profanity as $filter){
                if( (strrpos($element, " ".$filter." ") !== false) || trim($element) == $filter ) $errors[] = "Your ".strtolower($key)." appears to contain unapproved wording or profanity.<br />Please ensure your name is correctly typed or <a href='mailto:customerservice@asiainspection.com'>contact us</a> for further assistance.<br />";
            }
            //Check for Gibberish
            foreach($filter_Gibberish as $filter){
                if( strrpos($element, $filter) !== false ){
                    $errors[] = "Invalid characters '$filter' found in ".strtolower($key).".<br />";
                }
            }
            //Check for Spam Domains if this is the Email Field
            if($key == "Email"){
                foreach($filter_Domains as $filter){
                    if( strrpos($element, $filter) !== false ){
                        $errors[] = "Your email appears to be on a domain block list.<br />Please use another email address or <a href='mailto:customerservice@asiainspection.com'>contact us</a> for further assistance.<br />";
                    }
                }
            }
        } // End of Custom Validation Loop

        //If the main fields are all identical
        if( ($elements["First name"] == $elements["Last name"]) && ($elements["Last name"] == $elements["Company"]) ) $errors[] = "First, Last and Company name are all identical.<br />";
        //To prevent 'google' spam
        if( ($elements["First name"] == $elements["Last name"]) && $elements["Company"] == "google" ) $errors[] = "There was an error with your registration, please <a href='mailto:customerservice@asiainspection.com'>contact us</a> for further assistance.<br />";

        //Return Errors if they Exist
        if(!empty($errors)){
            $errors = array_unique($errors);
            if ($locale == "ar") {
                return $this->render('AIResponsiveBundle:RTL:account/new.html.twig', $this->newAction(implode("<br />", $errors), $entity->getFirstName(), $entity->getLastName(), $entity->getCompany(), $entity->getTelephone(), $entity->getEmail(), $entity->getUsername(), $entity->getPassword()));
            } else {
                return $this->render('AIResponsiveBundle:account:'.$viewTwig.'.html.twig', $this->newAction(implode("<br />", $errors), $entity->getFirstName(), $entity->getLastName(), $entity->getCompany(), $entity->getTelephone(), $entity->getEmail(), $entity->getUsername(), $entity->getPassword()));
            }
            //return $this->newAction(implode("<br />", $errors), $entity->getFirstName(), $entity->getLastName(), $entity->getCompany(), $entity->getTelephone(), $entity->getEmail(), $entity->getUsername(), $entity->getPassword());
        }
        //Form Custom Validation [End]
        
        if ($form->isValid()) {

            //For creating an account on the client account
            $domain = $_SERVER['HTTP_HOST'];
            if($viewTwig == "iosRegister" || $viewTwig == "androidRegister") $domain = "m.asiainspection.com";
            $data = new Data();
            $RESTdata = array(
                'activity' => '',
                'affiliate-id' => (string)$entity->getAffid(),
                'address' => '',
                'billing-contact-name' => '',
                'billing-email' => '',
                'billing-salutation' => '',
                'city' => '',
                'company-name' => $entity->getCompany(),
                'country' => $entity->getCountry(),
                'emails' => $entity->getEmail(),
                'fax' => '',
                'firstname' => $entity->getFirstName(),
                'industry' => $entity->getIndustry(),
                'lastname' => $entity->getLastName(),
                'mobile' => '',
                'position' => '',
                'post-code' => '',
                'salutation' => '',
                'supplier-company-name' => '',
                'telephone' => (string) $entity->getTelephone(),
                'domain-name' => $domain,
                'home-page' => '',
                'introduced-by' => '',
                'introduced-by-other-type' => '',
                'introduced-by-tradeshow' => '',
                'is-test-account' => false,
                'login' => $entity->getUsername(),
                'password' => $entity->getPassword(),
                'refer' => (string)$entity->getReferrer(),
                'turnover' => '0',
                'user-ip' => $_SERVER['REMOTE_ADDR'],
                'is-food-inspection' => false,
                'is-chb' => $entity->getChb(),
                'isMobile' => $entity->getIsMobile(),
                'deviceInfo' => $entity->getDeviceInfo()
            );

            if(strtoupper($RESTdata['firstname']) != "GARGAMEL") $returnedval = $data->CallRest("create-new-account", "dev", "postget", $RESTdata, true);
            
            // [Begin] Save Registration to Our Local SQL
            $db = mysqli_connect("54.251.119.26","aidata","byNdf9W44T","Staging");
            if(strtoupper($RESTdata['firstname']) == "GARGAMEL") $db = false;
            if($db) {
                mysqli_set_charset($db,"utf8");
                $experienceCookie = ( isset($_COOKIE['mobExperience']) ? $_COOKIE['mobExperience'] : "" );
                $paramsCookie = ( isset($_COOKIE['paramsCookie']) ? $_COOKIE['paramsCookie'] : "" );
                $clientCookie = "UNKNOWN";
                if( isset($_COOKIE['isClient']) ) $clientCookie = "CLIENT";
                if( isset($_COOKIE['isActiveClient']) ) $clientCookie = "ACTIVE";

                if( is_string($returnedval) && strpos($returnedval, "Forbidden - You are not allowed to access the resource") ) {
                    if ($locale == "ar") {
                        return $this->render('AIResponsiveBundle:RTL:account/new.html.twig', $this->newAction("This server is not allowed to use the API.", $entity->getFirstName(), $entity->getLastName(), $entity->getCompany(), $entity->getTelephone(), $entity->getEmail(), $entity->getUsername(), $entity->getPassword(), "account_new"));
                    } else {
                        return $this->render('AIResponsiveBundle:account:'.$viewTwig.'.html.twig', $this->newAction("This server is not allowed to use the API.", $entity->getFirstName(), $entity->getLastName(), $entity->getCompany(), $entity->getTelephone(), $entity->getEmail(), $entity->getUsername(), $entity->getPassword(), "account_new"));
                    }
                    //return $this->newAction("This server is not allowed to use the API.", $entity->getFirstName(), $entity->getLastName(), $entity->getCompany(), $entity->getTelephone(), $entity->getEmail(), $entity->getUsername(), $entity->getPassword(), "account_new");
                }

                if($returnedval=="true"||$returnedval==1) {
                    $passfailreason = "Success";
                    $sql = "INSERT INTO registrations (Login, Email, First_Name, Last_Name, Company, Domain, Country, Phone, Affiliate_Id, IP, AFI, CHB, Test_Account, Referrer, Experience, Result, URL_Params, isClient, isMobile, deviceInfo) VALUES ('".$RESTdata['login']."', '".$RESTdata['emails']."', '".$RESTdata['firstname']."', '".$RESTdata['lastname']."', '".$RESTdata['company-name']."', '".$RESTdata['domain-name']."', '".$RESTdata['country']."', '".$RESTdata['telephone']."', '".$RESTdata['affiliate-id']."', '".$RESTdata['user-ip']."', '".$RESTdata['is-food-inspection']."', '".$RESTdata['is-chb']."', '".$RESTdata['is-test-account']."', '".$RESTdata['refer']."','".$experienceCookie."','".$passfailreason."','".$paramsCookie."','".$clientCookie."', ".$RESTdata['isMobile'].", '".$RESTdata['deviceInfo']."')";
                } elseif (array_key_exists ( "error", $returnedval )) {
                    $sql = "INSERT INTO registrations (Login, Email, First_Name, Last_Name, Company, Domain, Country, Phone, Affiliate_Id, IP, AFI, CHB, Test_Account, Referrer, Experience, Result, URL_Params, isClient, isMobile, deviceInfo) VALUES ('".$RESTdata['login']."', '".$RESTdata['emails']."', '".$RESTdata['firstname']."', '".$RESTdata['lastname']."', '".$RESTdata['company-name']."', '".$RESTdata['domain-name']."', '".$RESTdata['country']."', '".$RESTdata['telephone']."', '".$RESTdata['affiliate-id']."', '".$RESTdata['user-ip']."', '".$RESTdata['is-food-inspection']."', '".$RESTdata['is-chb']."', '".$RESTdata['is-test-account']."', '".$RESTdata['refer']."','".$experienceCookie."','".$returnedval['error']."','".$paramsCookie."','".$clientCookie."', ".$RESTdata['isMobile'].", '".$RESTdata['deviceInfo']."')";
                } else {
                    $sql = "INSERT INTO registrations (Login, Email, First_Name, Last_Name, Company, Domain, Country, Phone, Affiliate_Id, IP, AFI, CHB, Test_Account, Referrer, Experience, Result, URL_Params, isClient, isMobile, deviceInfo) VALUES ('".$RESTdata['login']."', '".$RESTdata['emails']."', '".$RESTdata['firstname']."', '".$RESTdata['lastname']."', '".$RESTdata['company-name']."', '".$RESTdata['domain-name']."', '".$RESTdata['country']."', '".$RESTdata['telephone']."', '".$RESTdata['affiliate-id']."', '".$RESTdata['user-ip']."', '".$RESTdata['is-food-inspection']."', '".$RESTdata['is-chb']."', '".$RESTdata['is-test-account']."', '".$RESTdata['refer']."','".$experienceCookie."','".$returnedval."','".$paramsCookie."','".$clientCookie."', ".$RESTdata['isMobile'].", '".$RESTdata['deviceInfo']."')";
                }
                $db->query($sql);
                // Error Log
                if($db->error != ""){
                    $errLog = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? "C:\\AI-ErrorLog.txt" : "/var/log/AI-ErrorLog.txt");
                    file_put_contents($errLog, $sql."\n[".date("Y-m-d G:i:s")."] ".$db->error."\n", FILE_APPEND);
                }
                // Save Invalid Phones
                $Global = $this->get('global_functions');
                $UserPhone = $Global->twilioVerifyPhoneNumber($entity->getTelephone());
                if($UserPhone == false) {
                    // Save to DB
                    $last_id = $db->insert_id;
                    $db->query("INSERT INTO RegistrationsWithFailedTwilioNumbers (register_id, phoneNumber, email) VALUES (".$last_id.", '".$RESTdata['telephone']."', '".$RESTdata['emails']."')");
                }
            }
            $deviceInfo = json_decode($RESTdata['deviceInfo']);
            $contentDownloadExtra = array(
                "login" => $RESTdata['login'],
                "name" => $RESTdata['firstname'] . " " . $RESTdata['lastname'],
                "firstname" => $RESTdata['firstname'],
                "lastname" => $RESTdata['lastname'],
                "company" => $RESTdata['company-name'],
                "country" => $RESTdata['country'],
                "phone" => $RESTdata['telephone'],
                "domain" => $RESTdata['domain-name'],
                "affiliateid" => $RESTdata['affiliate-id'],
                "result" => ($returnedval == "true" || $returnedval == "Success" || $returnedval == 1 ? "Success" : "Failure"),
                "mobiledevice" => ($RESTdata['isMobile'] ? "true" : "false"),
                "mobiletype" => ( isset($deviceInfo->Type) ? $deviceInfo->Type : "" ),
                "mobilebrand" => ( isset($deviceInfo->Brand) ? $deviceInfo->Brand : "" ),
                "mobileos" => ( isset($deviceInfo->OS) ? $deviceInfo->OS : "" ),
                "mobilebrowser" => ( isset($deviceInfo->Browser) ? $deviceInfo->Browser : "" ),
                "experience" => $registerUrl
            );

            $Global = $this->get('global_functions');
            if(strtoupper($RESTdata['firstname']) != "GARGAMEL") {
                $Global->saveContentDownload($RESTdata['emails'], "Registration", "", $request, $contentDownloadExtra);
            }

            // [End] Save Registration to Our Local SQL
            if(strtoupper($RESTdata['firstname']) == "GARGAMEL") $returnedval['error'] = "Test Registration Successful";
            if ($returnedval == "true" || $returnedval == "Success" || $returnedval == 1) {
                //Success ([AF] "true")
                if($viewTwig == "iosRegister" || $viewTwig == "androidRegister") {
                    return $this->redirect($this->generateUrl('register_success_app'));
                } else {
                    return $this->redirect($this->generateUrl('register_success'));
                }
            } else {
                //Failure
                if ($locale == "ar") {
                    return $this->render('AIResponsiveBundle:RTL:account/new.html.twig', $this->newAction($returnedval['error']));
                } else {
                    return $this->render('AIResponsiveBundle:account:'.$viewTwig.'.html.twig', $this->newAction($returnedval['error']));
                }
                //return $this->newAction($returnedval['error']);
            }

        }

        // Default
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:account/new.html.twig', array('entity' => $entity, 'form' => $form->createView()) );
        } else {
            return $this->render('AIResponsiveBundle:account:'.$viewTwig.'.html.twig', array('entity' => $entity, 'form' => $form->createView()) );
        }

    }


    /**
     * Creates a form to create a register entity.
     *
     * @param register $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Register $entity) {
        $request = $this->get('request');
        $locale = $this->get('session')->get('_locale');
        $cookies = $request->cookies;
        $formType= new registerType();
        $formType->setLocale($locale);

        // to handle the different views used to register
        $registerUrl = $this->get('session')->get("registerURL");
        $registerUrl = ( $registerUrl != "" ? $registerUrl : "/register" );

        $form = $this->createForm($formType, $entity, array(
            'attr' => array('class' => 'validate-form'),
            'action' => $registerUrl,
            'method' => 'POST'
        ));

        $isMobile = false;
        $deviceInfo = array("Type" => "", "Brand" => "", "OS" => "", "Browser" => "");
        try {
            $mobileDetector = new MobileDetector();
            $isPhone = $mobileDetector->isMobile();
            $isTablet = $mobileDetector->isTablet();
            
            if( $isPhone || $isTablet ){
                $isMobile = true;

                if($isPhone){
                    $deviceInfo['Type'] = "Phone";
                    // Check phone
                    if( $mobileDetector->isIphone() ) $deviceInfo['Brand'] = "iPhone";
                    if( $mobileDetector->isHTC() ) $deviceInfo['Brand'] = "HTC";
                    if( $mobileDetector->isNexus() ) $deviceInfo['Brand'] = "Nexus";
                    if( $mobileDetector->isDell() ) $deviceInfo['Brand'] = "Dell";
                    if( $mobileDetector->isMotorola() ) $deviceInfo['Brand'] = "Motorola";
                    if( $mobileDetector->isSamsung() ) $deviceInfo['Brand'] = "Samsung";
                    if( $mobileDetector->isSony() ) $deviceInfo['Brand'] = "Sony";
                    if( $mobileDetector->isAsus() ) $deviceInfo['Brand'] = "Asus";
                    if( $mobileDetector->isPalm() ) $deviceInfo['Brand'] = "Palm";
                    if( $mobileDetector->isVertu() ) $deviceInfo['Brand'] = "Vertu";
                    if( $mobileDetector->isGenericPhone() ) $deviceInfo['Brand'] = "Generic";
                } 

                if($isTablet) {
                    $deviceInfo['Type'] = "Tablet";
                    // Check tablet
                    if( $mobileDetector->isBlackBerryTablet() ) $deviceInfo['Brand'] = "BlackBerry";
                    if( $mobileDetector->isIpad() ) $deviceInfo['Brand'] = "iPad";
                    if( $mobileDetector->isKindle() ) $deviceInfo['Brand'] = "Kindle";
                    if( $mobileDetector->isSamsungTablet() ) $deviceInfo['Brand'] = "Samsung";
                    if( $mobileDetector->isHTCtablet() ) $deviceInfo['Brand'] = "HTC";
                    if( $mobileDetector->isMotorolaTablet() ) $deviceInfo['Brand'] = "Motorola";
                    if( $mobileDetector->isAsusTablet() ) $deviceInfo['Brand'] = "Asus";
                    if( $mobileDetector->isNookTablet() ) $deviceInfo['Brand'] = "Nook";
                    if( $mobileDetector->isAcerTablet() ) $deviceInfo['Brand'] = "Acer";
                    if( $mobileDetector->isYarvikTablet() ) $deviceInfo['Brand'] = "Yarvik";
                    if( $mobileDetector->isGenericTablet() ) $deviceInfo['Brand'] = "Generic";
                }

                // Check mobile OS
                if( $mobileDetector->isAndroidOS() ) $deviceInfo['OS'] = "Android";
                if( $mobileDetector->isBlackBerryOS() ) $deviceInfo['OS'] = "BlackBerry";
                if( $mobileDetector->isPalmOS() ) $deviceInfo['OS'] = "PalmOS";
                if( $mobileDetector->isSymbianOS() ) $deviceInfo['OS'] = "Symbian";
                if( $mobileDetector->isWindowsMobileOS() ) $deviceInfo['OS'] = "Windows Mobile";
                if( $mobileDetector->isIOS() ) $deviceInfo['OS'] = "iOS";
                if( $mobileDetector->isBadaOS() ) $deviceInfo['OS'] = "Bada";

                // Check mobile browser
                if( $mobileDetector->isChrome() ) $deviceInfo['Browser'] = "Chrome";
                if( $mobileDetector->isDolfin() ) $deviceInfo['Browser'] = "Dolfin";
                if( $mobileDetector->isOpera() ) $deviceInfo['Browser'] = "Opera";
                if( $mobileDetector->isSkyfire() ) $deviceInfo['Browser'] = "Skyfire";
                if( $mobileDetector->isIE() ) $deviceInfo['Browser'] = "Internet Explorer";
                if( $mobileDetector->isFirefox() ) $deviceInfo['Browser'] = "Firefox";
                if( $mobileDetector->isBolt() ) $deviceInfo['Browser'] = "Bolt";
                if( $mobileDetector->isTeaShark() ) $deviceInfo['Browser'] = "TeaShark";
                if( $mobileDetector->isBlazer() ) $deviceInfo['Browser'] = "Blazer";
                if( $mobileDetector->isSafari() ) $deviceInfo['Browser'] = "Safari";
                if( $mobileDetector->isMidori() ) $deviceInfo['Browser'] = "Midori";
                if( $mobileDetector->isGenericBrowser() ) $deviceInfo['Browser'] = "Generic";
            }

        } catch(\Exception $e) {
            // Do nothing, just don't break :-)
        }

        $deviceInfo = ( !array_filter($deviceInfo) ? "" : json_encode($deviceInfo) );

        $form->add('affid', 'hidden', array('data' => $cookies->get('affid')));
        $form->add('referrer', 'hidden', array('data' => $cookies->get('http_referer')));
        $form->add('chb', 'hidden', array('data' => 'false'));
        $form->add('isMobile', 'hidden', array('data' => $isMobile));
        $form->add('deviceInfo', 'hidden', array('data' => $deviceInfo));
        $form->add('submit', 'submit', array('label' => 'Create my Account', 'attr' => array('class' => 'one-btn btn btn-primary btn-big submitButton', 'style' => 'clear:both; margin-top:1em; display:inline-block;','onClick'=>'return validateRegistrationForm();')));

        return $form;
    }

    /**
     * Displays a form to create a new Register entity.
     * @Route("/register", name="account_new")
     * This is for the ABC Test
     */
    public function registerAction(Request $request) {
        $this->get('session')->set('registerURL', "/register");
        $locale = ( isset($_GET['_locale']) ? $_GET['_locale'] : $this->get('session')->get('_locale') );
        $Global = $this->get('global_functions');
        $UserCountry = $Global->checkUserCountry();
        if( $locale == "" && $request != null) $locale = $request->getLocale();

        $bypass = ( isset($_GET['bypass']) ? true : false );

        if( ($UserCountry == "IN" || $locale == "zh" ) && !$bypass ){
            $twigData = array();
            $xml = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/Popups.xml');
            $xml = simplexml_load_file($xml);
            $twigData['xml'] = ($locale == "zh" ? $xml->RegLookFor->zh : $xml->RegLookFor->en );
            return $this->render('AIResponsiveBundle:account:regLookingFor.html.twig', $twigData);
        } else if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:account/new.html.twig', $this->newAction());
        } else {
            return $this->render('AIResponsiveBundle:account:new.html.twig', $this->newAction());
        }
    }

    /**
     * Displays a form to create a new Register entity.
     * @Route("/ios-register")
     */
    public function iosRegisterAccountAction(Request $request) {
        $this->get('session')->set('registerURL', "/ios-register");
        $locale = ( isset($_GET['_locale']) ? $_GET['_locale'] : $this->get('session')->get('_locale') );
        if( $locale == "" && $request != null) $locale = $request->getLocale();
        return $this->render('AIResponsiveBundle:account:iosRegister.html.twig', $this->newAction());
    }

    /**
     * Displays a form to create a new Register entity.
     * @Route("/android-register")
     */
    public function androidRegisterAccountAction(Request $request) {
        $this->get('session')->set('registerURL', "/android-register");
        $locale = ( isset($_GET['_locale']) ? $_GET['_locale'] : $this->get('session')->get('_locale') );
        if( $locale == "" && $request != null) $locale = $request->getLocale();
        return $this->render('AIResponsiveBundle:account:androidRegister.html.twig', $this->newAction());
    }

    /**
     * Displays a form to create a new Register entity.
     *
     * @Route("/account")
     * @Method("GET")
     * @Template()
     */
    public function newAction($error="", $field_first_name="", $field_last_name="", $field_company="", $field_phone="", $field_email="", $field_username="", $field_password="") {
        $entity = new Register();
        $form = $this->createCreateForm($entity);
        
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $countries = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');

        //Random background image
        if($locale == "zh") {
            $assetsDomain = $this->container->getParameter('assets_china_domain');
        } else {
            $assetsDomain = $this->container->getParameter('assets_domain');
        }
        $rndImg = rand (1,4);
        $backgroundImage = $assetsDomain."/images/register/register_BG".$rndImg.".jpg";

        //include Tracking data [End]
        return array(
            'error' => $error,
            'field_first_name' => $field_first_name,
            'field_last_name' => $field_last_name,
            'field_company' => $field_company,
            'field_industry' => '',
            'field_phone' => $field_phone,
            'field_email' => $field_email,
            'field_username' => $field_username,
            'field_password' => $field_password,
            'entity' => $entity,
            'form' => $form->createView(),
            'countries' => simplexml_load_file($countries),
            'tracking' => $tracking->getTrackingCode('registration', $locale),
            'backgroundImage' => $backgroundImage
        );

    }

    /**
     * Registration Success Page.
     *
     * @Route("/registrationsuccess", name="register_success")
     * @Method("GET")
     * @Template("AIResponsiveBundle:account:registration_success.html.twig")
     */
    public function registrationSuccessAction(Request $request) {
        if(isset($_GET['spam']) && $_GET['spam'] == "true")
            $spamswitch = 1;
        else
            $spamswitch = 0;
        if(isset($_GET['confirm']) && $_GET['confirm'] == "true")
            $confirm = 1;
        else
            $confirm = 0;

        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/account/registersuc.xml');
        $twigData = $this->getXMLAndParameters($path, $request);
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');
        $twigData['countries'] = simplexml_load_file($path);
        
        // Sort Countries List
        $countriesArray =  (array) $twigData['countries'];
        usort($countriesArray, function($a, $b){
            return strcmp($a->name, $b->name);
        });
        $twigData['countries'] = $countriesArray;

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('regsuccess', $locale);
        //include Tracking data [End]
        $twigData['spamswitch']=$spamswitch;
        $twigData['confirm']=$confirm;
        if(isset($_GET['login'])) $twigData['login'] = $_GET['login'];
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:account/registration_success.html.twig', $twigData);
        } else {
            return $this->render('AIResponsiveBundle:account:registration_success.html.twig', $twigData);
        }
    }

    /**
     * Registration Success Page.
     *
     * @Route("/appregistrationsuccess", name="register_success_app")
     */
    public function registrationSuccessAppAction(Request $request) {
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('regsuccess', $locale);
        //include Tracking data [End]
        $twigData['chromebrowser'] = false;
        if(stripos($_SERVER['HTTP_USER_AGENT'],"Chrome")) $twigData['chromebrowser'] = true;
        return $this->render('AIResponsiveBundle:account:registration_success_app.html.twig', $twigData);
    }

    public function checkLocale() {
        if (isset($_GET['_locale'])) {
            $locale = $_GET["_locale"];
            $this->get('request')->setLocale($locale);
        }
    }


    public function getXMLAndParameters($path, $request) {
        $locale = $request->getLocale();
        $xml = simplexml_load_file($path);
        $pageDesc = $xml->pageDesc->$locale;
        if($xml->pageMeta->pageTitle->$locale!="")
            $pageTitle = $xml->pageMeta->pageTitle->$locale;
        else
            $pageTitle = $xml->pageMeta->pageTitle->en;
        if($xml->$locale!="")
            $xml = $xml->$locale;
        else
            $xml = $xml->en;
        return array('xml' => $xml, 'pageTitle' => $pageTitle);
    }

    /**
     * @Route("/checkUsername")
     * @Method("POST")
     *
     **/
    public function checkUsernameAction(Request $request) {
        $data = new Data();
        $returnedval = $data->CallRest("is-login-exist", "dev", "get", array('login' => $_POST['username']));
        if((strpos($_POST['username'],'@') !== false)&& !$returnedval){
            $returnedval = $data->CallRest("is-email-exist", "dev", "get", array('email' => $_POST['username']));
        }
        return new Response($returnedval);
    }
    /**
     * @Route("/checkEmail")
     * @Method("POST")
     *
     **/
    public function checkEmailAction(Request $request) {
        $data = new Data();
        if(strpos($_POST['email'],'@') !== false)
            $returnedval = $data->CallRest("is-email-exist", "dev", "get", array('email' => $_POST['email']));
        else
            $returnedval = 1;
        return new Response($returnedval);
    }


    /**
     * @Route("/checkEmoji")
     * @Method("POST")
     */
    public function checkEmoji() {
        $Regex =  "/\A(?!.*@[:;]-\))[ -~][0-9A-Za-z]{1,}\z/i";
        $result = preg_match ( $Regex, $_POST['username'] );
        return new Response($result);

    }


}
