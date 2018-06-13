<?php

namespace AI\ResponsiveBundle\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;
use Seven\RpcBundle\XmlRpc\Client;

class salesLeadsController extends Controller
{
    private $baseDir = "/var/SalesLeads";

    //Setting who should be emailed for what events
    public $BaseEmails_TradeshowMemo_Booth = array("pierre-nicolas.disser@asiainspection.com","mathieu.labasse@asiainspection.com","michael.mesarch@asiainspection.com","sharon.zhou@asiainspection.com","serena.zou@asiainspection.com","michael.bland@asiainspection.com","melvin.tang@asiainspection.com","vincent.macdonald@asiainspection.com","courtney.terrey@asiainspection.com","sebastian.habermehl@asiainspection.com","sebastien.breteau@asiainspection.com");
    public $BaseEmails_TradeshowMemo_NoBooth = array("pierre-nicolas.disser@asiainspection.com","mathieu.labasse@asiainspection.com","michael.mesarch@asiainspection.com","sharon.zhou@asiainspection.com","serena.zou@asiainspection.com","michael.bland@asiainspection.com","melvin.tang@asiainspection.com","vincent.macdonald@asiainspection.com","courtney.terrey@asiainspection.com","sebastian.habermehl@asiainspection.com");
    public $BaseEmails_LeadsUploaded = array("sharon.zhou@asiainspection.com","vincent.macdonald@asiainspection.com","serena.zou@asiainspection.com");

    //For Development Environment
    public $session;

    /**
     * [getSession description]
     * This is to get the active session and creates one if there isn't one
     * @return [type] [description]
     */
    public function getSession(){
        if( session_status() === PHP_SESSION_NONE ){
            $startedSession = new Session();
            $this->session = $startedSession->start();
            //$startedSession->migrate(true,60);
            //$this->session->migrate(true,86400);
        }else if( $this->getRequest() !== null ) {
            $this->session = $this->getRequest()->getSession();
        }
        return $this->session;
    }

    /**
     * @Route("/sales-leads",name="LeadsUpload")
     * @Template()
     * This is the page used to submit leads for upload to Zoho
     */
    public function indexAction() {
        $salesInfo['fullname'] = $this->getNameFromEmail($this->getUser());
        $salesInfo['salesInfo'] = $salesInfo['salesNames'] = $this->getSalesInfo();
        $salesInfo['tradeshowList'] = $this->getTradeshows("all");
        
        return $this->render('AIResponsiveBundle:salesLeads:salesLeads.html.twig',$salesInfo);
    }

    /**
     * @Route("/upload-non-opt-file",name="UpNonOptIn")
     * @Template()
     * This is the page used to submit leads for upload to Zoho
     */
    public function uploadNonOptInAction() {
        $salesInfo['salesInfo'] = $salesInfo['salesNames'] = $this->getSalesInfo();
        $salesInfo['fullname'] = $this->getNameFromEmail($this->getUser());

        return $this->render('AIResponsiveBundle:salesLeads:uploadNonOptIn.html.twig',$salesInfo);
    }

    /**
     * @Route("/check-non-opt-file",name="NonOptIn")
     * @Template()
     * This is the page used to submit leads for upload to Zoho
     */
    public function nonOptInAction() {
        $salesInfo['salesInfo'] = $salesInfo['salesNames'] = $this->getSalesInfo();
        $salesInfo['fullname'] = $this->getNameFromEmail($this->getUser());

        return $this->render('AIResponsiveBundle:salesLeads:checkNonOptIn.html.twig',$salesInfo);
    }

    /**
     * @Route("/check-contact-file",name="ContactUpload")
     * @Template()
     * This is the page used to submit leads for upload to Zoho
     */
    public function contactsAction() {
        $salesInfo['salesInfo'] = $salesInfo['salesNames'] = $this->getSalesInfo();
        $salesInfo['fullname'] = $this->getNameFromEmail($this->getUser());
        
        return $this->render('AIResponsiveBundle:salesLeads:checkContacts.html.twig',$salesInfo);
    }

    /**
     * @Route("/contact-lookup",name="ContactLookup")
     * @Template()
     * This is the page used to submit leads for upload to Zoho
     */
    public function contactLookupAction() {
        $salesInfo['salesInfo'] = $salesInfo['salesNames'] = $this->getSalesInfo();
        $salesInfo['fullname'] = $this->getNameFromEmail($this->getUser());
        
        return $this->render('AIResponsiveBundle:salesLeads:contactLookup.html.twig',$salesInfo);
    }

    /**
     * @Route("/leads-tool")
     * @Template()
     * This is the landing page
     */
    public function landingAction() {
        $name = $this->getNameFromEmail($this->getUser());
        return $this->render('AIResponsiveBundle:salesLeads:leadsToolLanding.html.twig',array("fullname" => $name));
    }

    /**
     * @Route("/approve-sales-leads")
     * @Template()
     * This page is for administrators to approve leads to be uploaded to Zoho
     */
    public function approveAction() {
        $session = $this->getSession();
        $result = $this->getDataFromDB();
        $arr_data = Array();
        $name = $this->getNameFromEmail($this->getUser());
        while ($data = $result->fetch_assoc()) {
            $data['reject_reason_json'] = "";
            if ($data['reject_reason'] != "") {
                $data['reject_reason_json'] = json_encode(($data['reject_reason']));
            }
            $arr_data[] = $data;
        }

        $link = "http://www.asiainspection.com/salesLeads/";

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        $newCompanies = 0;
        if( !$conn['error'] ) {
            $db = $conn['connection'];
            $sql = "SELECT COUNT(companyName) FROM zoho_CompaniesToAdd";
            if ($returnedval = $db->query($sql)) {
                $db->close();
                $newCompanies = $returnedval->fetch_row()[0];
            }
        }

        return $this->render('AIResponsiveBundle:salesLeads:approveSalesLeads.html.twig',array("data"=>$arr_data,"link"=>$link,"newCompanyCount"=>$newCompanies,"fullname"=>$name));
    }

    /**
     * @Route("/tradeshow-memo")
     * @Route("/tradeshow-memo/{type}")
     * @Template()
     */
    public function tradeshowMemoAction($type=null) {
        $session = $this->getSession();
        $salesInfo['salesInfo'] = $this->getSalesInfo();
        $salesInfo['fullname'] = $this->getNameFromEmail($this->getUser());
        $allowedMemoTypes = array("tradeshow-exhibiting", "tradeshow-visiting", "event-visiting", "event-speaking", "event-hosted", "webinar");

        if ( isset($type) && in_array($type, $allowedMemoTypes) ) {
            $salesInfo['tradeshowList'] = $this->getTradeshows($type, array("location"));
        } else {
            $salesInfo['tradeshowList'] = $this->getTradeshows("all", array("location"));
        }
        $salesInfo['salesNames'] = $this->getSalesInfo();
        $salesInfo['displayMails_Booth'] = $this->BaseEmails_TradeshowMemo_Booth;
        $salesInfo['displayMails_NoBooth'] = $this->BaseEmails_TradeshowMemo_NoBooth;

        if ( isset($type) && in_array($type, $allowedMemoTypes) ) {
            if( $type == "tradeshow-exhibiting" ) return $this->render('AIResponsiveBundle:salesLeads:memoTradeshowExhibiting.html.twig',$salesInfo);
            if( $type == "tradeshow-visiting" ) return $this->render('AIResponsiveBundle:salesLeads:memoTradeshowVisiting.html.twig',$salesInfo);
            if( $type == "event-visiting" ) return $this->render('AIResponsiveBundle:salesLeads:memoEventVisiting.html.twig',$salesInfo);
            if( $type == "event-speaking" ) return $this->render('AIResponsiveBundle:salesLeads:memoEventSpeaking.html.twig',$salesInfo);
            if( $type == "event-hosted" ) return $this->render('AIResponsiveBundle:salesLeads:memoEventHosted.html.twig',$salesInfo);
            if( $type == "webinar" ) return $this->render('AIResponsiveBundle:salesLeads:memoWebinar.html.twig',$salesInfo);
        }

        return $this->render('AIResponsiveBundle:salesLeads:tradeshowMemo.html.twig',$salesInfo);
    }

    /**
     * @Route("/CheckIfMemoExists")
     * @Method("POST")
     * 
     */
    public function checkIfMemoExistsAction()
    {  
        $output = array("errors" => false, "err_msg" => "", "Uploader" => ""); //Default Error Settings

        $show = $_POST['show'];
        $day = getd($_POST['day']);

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            $output['errors'] = true;
            $output['err_msg'] = $conn['msg'];
            return new Response( json_encode($output) );
        }else{ $db = $conn['connection']; }
        
        $sql = "SELECT day, salesName FROM eventMemos WHERE tradeshow='".$show."'";
        if (isset($day)) $sql .= " AND day='".$day."'";

        if (!$returnedval=$db->query($sql)) {
            $db->close();
            $output['errors'] = true;
            $output['err_msg'] = $db->error;
            return new Response( json_encode($output) );
        }

        if( $returnedval->num_rows == 0 ){
            $output['errors'] = false;
            $output['err_msg'] = "";
            $output['Uploader'] = "";
            return new Response( json_encode($output) );
        }else{
            $row = $returnedval->fetch_assoc();
            $output['errors'] = false;
            $output['err_msg'] = "";
            $output['Uploader'] = $row['salesName'];
            return new Response( json_encode($output) );
        }

    }

    /**
     * @Route("/viewMemoDetail/{id}")
     * @Method("GET")
     * 
     */
    public function viewMemoDetailAction($id,Request $request)
    {  
        $link = "http://www.asiainspection.com/tradeshowMemo/";

        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }
        
        $sql = "SELECT * FROM eventMemos WHERE id =".$id;
        if (!$returnedval=$db->query($sql)) {
            $db->close();
            die("Failed to get data from database!");
        }
                        
        $array['fullname'] = $this->getNameFromEmail($this->getUser());
        $array['salesInfo'] = $this->getSalesInfo();
        $array['memoID']=$id;
        $row = $returnedval->fetch_assoc();
        $array['row'] = $row;
        $photos = array();
        $numLeads = array();
        $results = json_decode($row['results']);
        $photos = json_decode($row['photos']);
        $array['photos'] = $photos; 
        $array['results']=$results;
        $array['link'] = $link;
        $array['ShowTitle'] = $row['tradeshow'] . " - " . $row['day'];
        
        $sql1 = "SELECT * FROM SalesUpload WHERE tradeshow ='".$row['tradeshow']."'";
        if (!$salesLeads=$db->query($sql1)) {
            $db->close();
            die("Failed to get data from database!");
        }
        $array['salesLeads'] = $salesLeads;
        $array['tradeshowList'] = $this->getTradeshows();
        $db->close();

        return $this->render('AIResponsiveBundle:salesLeads:viewMemoDetail.html.twig',$array);
    }

    /**
     * @Route("/leads-count")
     * 
     */
    public function leadsCountAction(Request $request)
    {  
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }


        $sql = "SELECT leadsCount.*, tradeshowList.End_Date FROM leadsCount LEFT JOIN tradeshowList ON (leadsCount.tradeshow = tradeshowList.tradeshow)";
        if (!$returnedval=$db->query($sql)) {
            $db->close();
            die("Failed to get data from database!");
        }

        $db->close();
        $data = array();
        $data['fullname'] = $this->getNameFromEmail($this->getUser());
        $data['salesNames'] = $this->getSalesInfo();
        $data['rows'] = $returnedval;
        $data['tradeshowList'] = $this->getTradeshows();

        return $this->render('AIResponsiveBundle:salesLeads:leadsCount.html.twig',$data);
    }

    /**
     * @Route("/view-tradeshow-memo")
     * 
     */
    public function displayTradeshowMemoAction(){
        $array = array();

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }

        $array['fullname'] = $this->getNameFromEmail($this->getUser());

        //Try the SQL query and redirect on failure
        if (!$tradeshow_exhibit = $db->query("SELECT * FROM eventMemos WHERE event_type = \"tradeshow-exhibiting\" ORDER BY tradeshow DESC, day, salesName")) {
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $db->error) );
        }
        if (!$tradeshow_visit = $db->query("SELECT * FROM eventMemos WHERE event_type = \"tradeshow-visiting\"  ORDER BY tradeshow DESC, salesName")) {
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $db->error) );
        }
        if (!$event_visit = $db->query("SELECT * FROM eventMemos WHERE event_type = \"event-visiting\"  ORDER BY tradeshow DESC, salesName")) {
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $db->error) );
        }
        if (!$event_hosted = $db->query("SELECT * FROM eventMemos WHERE event_type = \"event-hosted\"  ORDER BY tradeshow DESC, salesName")) {
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $db->error) );
        }
       if (!$event_speak = $db->query("SELECT * FROM eventMemos WHERE event_type = \"event-speaking\"  ORDER BY tradeshow DESC, salesName")) {
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $db->error) );
        }
        $db->close();

        $array['exhibitingTradeshows'] = array();
        foreach($tradeshow_exhibit as $memo) {
            $memo['do_next'] = ( $memo['do_next'] ? "Yes" : "No" );
            $memo['leadsdetails'] = $this->decodedetails($memo['results']);
            $array['exhibitingTradeshows'][] = $memo;
        }
        $array['visitingShows'] = array();
        foreach($tradeshow_visit as $memo) {
            $memo['do_next'] = ( $memo['do_next'] ? "Yes" : "No" );
            $memo['leadsdetails'] = $this->decodedetails($memo['results']);
            $array['visitingShows'][] = $memo;
        }

        $array['visitingEvents'] = array();
        foreach($event_visit as $memo) {
            $memo['do_next'] = ( $memo['do_next'] ? "Yes" : "No" );
            $memo['leadsdetails'] = $this->decodedetails($memo['results']);
            $array['visitingEvents'][] = $memo;
        }

        $array['hostedEvents'] = array();
        foreach($event_hosted as $memo) {
            $memo['leadsdetails'] = $this->decodedetails($memo['results']);
            $array['hostedEvents'][] = $memo;
        }

        $array['speakingEvents'] = array();
        foreach($event_speak as $memo) {
            $memo['do_next'] = ( $memo['do_next'] ? "Yes" : "No" );
            $memo['leadsdetails'] = $this->decodedetails($memo['results']);
            $array['speakingEvents'][] = $memo;
        }


        $array['tradeshowList'] = $this->getTradeshows("all");

        return  $this->render('AIResponsiveBundle:salesLeads:viewMemo.html.twig',$array);
    }
    private function decodedetails($json) {
        $details = json_decode($json, true);
        if(!empty($details)) {
            usort($details, function($a,$b){return (int)($b['num'])-(int)($a['num']);});
            $items = [];
            foreach($details as $item) {
                $items[] = $item['name'] . ' (' . $item['num'] . ')';
            }
            return join('<br>', $items);
        } else {
            return '&nbsp;';
        }
    }

    /**
     * @Route("/addTradeshow")
     */
    public function addTradeshowAction() {

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }

        //Try the SQL query and redirect on failure
        if (!$returnedval = $db->query("SELECT * FROM tradeshowList")) {
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $db->error) );
        }

        while($r = $returnedval->fetch_assoc()) {
            $tradeshowList[] = $r;
            }
        ksort( $tradeshowList, SORT_STRING );

        // Load countries and ISO3 codes to XML
        $countriesPath = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');

        $array = array();
        $array['countries'] = simplexml_load_file($countriesPath);
        $array['fullname'] = $this->getNameFromEmail($this->getUser());
        $array['tradeshowList'] = $tradeshowList;
        $db->close();
        return $this->render('AIResponsiveBundle:salesLeads:addTradeshow.html.twig',$array);
    }

    /**
     * @Route("/checkCSV")
     * @Method("POST")
     * 
     */
    // upload csv file
    public function checkCSV(Request $request){
        // $response = new Response(); $response->setStatusCode(504); return $response; // Simulating 504 Error

        date_default_timezone_set('Asia/Hong_Kong');
        $success = 0;
        
        $path = $this->baseDir . "/LeadsUploadFromInterface";
        $source = $_POST['source'];
        $nameOrg = $_FILES["leadsCSV"]['name'];
        $name = date('m-d-Y_H-i-s').".csv";
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','csv','application/octet-stream','text/comma-separated-values');
            
        if(in_array($_FILES['leadsCSV']['type'],$mimes)){
            if ($_FILES["leadsCSV"]["error"] == 0) {
                $jobTempFileName = $path . "/" . $name;
                move_uploaded_file($_FILES["leadsCSV"]["tmp_name"], $jobTempFileName);
                $salesInfo = $_POST['SalesID'];
                $tradeShow = $_POST['tradeshow'];

                $returnedval = $this->validate($this->getAIFields(), $this->getRequiredFields(), $path, $name);

                if(isset($returnedval['success']))  {
                    if( $source != "tradeshow" ) $tradeShow = "";
                    $array['success'] = $returnedval['success'];
                    $this->process_sales_upload_csv($path, $name, $salesInfo, $tradeShow, $_POST['source_specify'], $_POST['account']);
                    //return $this->uploadSuccess("salesLeads", $salesInfo);
                    return new Response(json_encode( array("success" => "true") ));
                } else  {
                    $array['errors']= $returnedval;
                    //return  $this->render('AIResponsiveBundle:salesLeads:salesLeads.html.twig',$array);
                    return new Response(json_encode( array("success" => "false", "errors" => $array['errors']) ));
                }
            }
        }
        $array['errors'][]= "Not a CSV file （文件不是csv格式）";

        //return  $this->render('AIResponsiveBundle:salesLeads:salesLeads.html.twig',$array);
        return new Response(json_encode( array("success" => "false", "errors" => $array['errors']) ));
    }

    /**
     * @Route("/lookup-contact")
     * 
     */
    // upload excel file to check against database
    public function lookupContact(Request $request){
        // $response = new Response(); $response->setStatusCode(504); return $response; // Simulating 504 Error
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        date_default_timezone_set('Asia/Hong_Kong');
        $success = 0;

        $salesconn = $this->container->get('DBConnector')->getDBconnection('Sales');
        $salesdb = null;

        if( $salesconn['error'] ) {
            return new Response(json_encode( array("success" => "false", "errors" => array("Database error: ".$salesconn['msg'])) ));
        } else {
            $salesdb = $salesconn['connection'];
        }
        if (isset($_POST['contactEmail']) && $_POST['contactEmail'] != "") {
            $sql = "select account_manager, person_status, client_status, login, company_name, client_name, country, zoho_id, oracle_id from newsletters WHERE Email = '".$salesdb->real_escape_string($_POST['contactEmail'])."'";
            $query = mysqli_query($salesdb,$sql);
            $rows = [];
            while($r = mysqli_fetch_assoc($query)) {
                $rows[] = $r;
            }
            if (count($rows) > 0) {
                $row = $rows[0];
                return new Response(json_encode(array('success' => 'true', 'data' => $row)));
            } else {
                return new Response(json_encode( array("success" => "false", "errors" => array("Email not found")) ));
            }
        }
        return new Response(json_encode( array("success" => "false", "errors" => array("Email is blank")) ));
    }

    /**
     * @Route("/checkXLSX")
     * 
     */
    // upload excel file to check against database
    public function checkXLSX(Request $request){
        // $response = new Response(); $response->setStatusCode(504); return $response; // Simulating 504 Error
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        date_default_timezone_set('Asia/Hong_Kong');
        $success = 0;

        $name = date('m-d-Y_H-i-s').$_FILES["leadsCSV"]['name'];
            
        if(preg_match('/\.xlsx$/i',$name)){
            if ($_FILES["leadsCSV"]["error"] == 0) {
                $salesEmail = $_POST['SalesID'];
                if ($salesEmail === false) $salesEmail = "";
                if ($salesEmail == "" or preg_match('/@asiainspection\.com/i', $salesEmail) and !preg_match('/@(?!asiainspection\.com)\b/i', $salesEmail)) {
                    $tmp = tmpfile();
                    $emailfileloc = stream_get_meta_data($tmp)['uri'];
                    fwrite($tmp, $salesEmail);
                    $Global = $this->get('global_functions');
                    $result = $Global->uploadToS3($this, $_FILES["leadsCSV"]["tmp_name"], "LeadsTool/contactChecks/".$name, 'private');
                    $result = $Global->uploadToS3($this, $emailfileloc, "LeadsTool/contactChecks/".$name.".email.txt", 'private');
                    return new Response(json_encode( array("success" => "true") ));
                } else {
                    $errors = "You may not send the file a non-AI email address.";
                }
            }
        } else {
            $errors = "Not an XLSX file （文件不是xlsx格式）";
        }

        return new Response(json_encode( array("success" => "false", "errors" => array($errors)) ));
    }

    /**
     * @Route("/checkNonOptIn")
     * 
     */
    // upload excel file to check against database
    public function checkNonOptIn(Request $request){
        // $response = new Response(); $response->setStatusCode(504); return $response; // Simulating 504 Error
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        date_default_timezone_set('Asia/Hong_Kong');
        $success = 0;

        $name = date('m-d-Y_H-i-s').$_FILES["leadsCSV"]['name'];

        if(preg_match('/\.xlsx$/i',$name)){
            if ($_FILES["leadsCSV"]["error"] == 0) {
                $salesEmail = $this->getUser();
                if ($salesEmail === false) $salesEmail = "";
                $tmp = tmpfile();
                $emailfileloc = stream_get_meta_data($tmp)['uri'];
                fwrite($tmp, $salesEmail);
                $Global = $this->get('global_functions');
                $result = $Global->uploadToS3($this, $_FILES["leadsCSV"]["tmp_name"], "LeadsTool/nonOptChecks/".$name, 'private');
                $result = $Global->uploadToS3($this, $emailfileloc, "LeadsTool/nonOptChecks/".$name.".email.txt", 'private');
                return new Response(json_encode( array("success" => "true") ));
            } else {
                return new Response(json_encode( array("success" => "false", "errors" => array($_FILES["leadsCSV"]["error"])) ));
            }
        } else {
             return new Response(json_encode( array("success" => "false", "errors" => array("Not an XLSX file （文件不是xlsx格式）")) ));
        }
    }

    /**
     * @Route("/uploadNonOptIn")
     * 
     */
    // upload excel file to insert in Zoho
    public function uploadNonOptIn(Request $request){
        // $response = new Response(); $response->setStatusCode(504); return $response; // Simulating 504 Error
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'This page is for admins only!');

        date_default_timezone_set('Asia/Hong_Kong');
        $success = 0;

        $name = date('m-d-Y_H-i-s').$_FILES["leadsCSV"]['name'];

        if(preg_match('/\.xlsx$/i',$name)){
            if ($_FILES["leadsCSV"]["error"] == 0) {
                $salesEmail = $this->getUser();
                if ($salesEmail === false) $salesEmail = "";
                $tmp = tmpfile();
                $emailfileloc = stream_get_meta_data($tmp)['uri'];
                fwrite($tmp, $salesEmail);
                $Global = $this->get('global_functions');
                $result = $Global->uploadToS3($this, $_FILES["leadsCSV"]["tmp_name"], "LeadsTool/nonOptUploads/".$name, 'private');
                $result = $Global->uploadToS3($this, $emailfileloc, "LeadsTool/nonOptUploads/".$name.".email.txt", 'private');
                return new Response(json_encode( array("success" => "true") ));
            } else {
                return new Response(json_encode( array("success" => "false", "errors" => array($errors)) ));
            }
        } else {
            $errors = "Not an XLSX file （文件不是xlsx格式）";
        }

        return new Response(json_encode( array("success" => "false", "errors" => array($_FILES["leadsCSV"]["error"])) ));
    }

    /**
     * @Route("/upload-failure")
     */
    public function uploadFailure($error = "") {
        return $this->render('AIResponsiveBundle:salesLeads:upload-failure.html.twig',array("error"=>$error));
    }

    /**
     * @Route("/upload-success")
     */
    public function uploadSuccess($type = "", $who = "A Team Member") {
        $salesLeads = 0;
        if($type=="salesLeads"){
            $salesLeads = 1 ;
            $this->sendEmail($type, $who);
        }

        return $this->render('AIResponsiveBundle:salesLeads:upload-success.html.twig',array("salesLeads"=>$salesLeads));
    }

    /**
     * @Route("/lead-upload-success")
     */
    public function leadUploadSuccess() {
        return $this->render('AIResponsiveBundle:salesLeads:upload-success.html.twig',array("salesLeads"=>1));
    }

    public function getCountsByName($name) {
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }

        if($name=="all"){
            $sth = mysqli_query($db,"SELECT * FROM leadsCount ");
        }else{
            $sth = mysqli_query($db,"SELECT * FROM leadsCount WHERE name='".$name."'");
        }
        $rows = array();
        while($r = mysqli_fetch_assoc($sth)) { $rows[] = $r; }
        $db->close();

        return $rows;
    }

    /**
     * @Route("/getLeadsByName")
     * @Method("POST")
     */
    public function getLeadsByNameAction(Request $request) {
        $name = $_POST['name'];
        $data = $this->getDataFromDBbyName($name);

        return new Response(json_encode($data));
    }
   /**
     * @Route("/getLeadsByTradeshow")
     * @Method("POST")
     *
     */
    public function getLeadsByTradeshowAction(Request $request) {
        $tradeshow = $_POST['tradeshow'];
        $data = $this->getDataFromDBbyTradeshow($tradeshow);

        return new Response(json_encode($data));
    }


    /**
     * @Route("/getLeadsByStatus")
     * @Method("POST")
     *
     */
    public function getLeadsByStatusAction(Request $request) {
        $status = $_POST['status'];
        switch ($status) {
            case 'All':
                $data = $this->getDataFromDBbyStatus("all");
                break;
            case 'Waiting':
                $data = $this->getDataFromDBbyStatus("waiting");
                break;
            case 'Approved':
                $data = $this->getDataFromDBbyStatus("approved");
                break;
            case 'Rejected':
                $data = $this->getDataFromDBbyStatus("rejected");
                break;
            default:
                $data = $this->getDataFromDBbyStatus("all");
                break;
        }

        return new Response(json_encode($data));
    }

    public function getDataFromDBbyStatus($status){
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        } else { $db = $conn['connection']; }

        if($status =="all"){
            $sth = mysqli_query($db,"SELECT * FROM SalesUpload");
        } else {
            $sth = mysqli_query($db,"SELECT * FROM SalesUpload WHERE status='".$status."'");
        }
        $rows = array();
        while($r = mysqli_fetch_assoc($sth)) {
            $rows[] = $r;
        }
        $db->close();
        return $rows;
    }

    public function getDataFromDBbyName($name){
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        } else { $db = $conn['connection']; }

        if($name =="all"){
            $sth = mysqli_query($db,"SELECT SalesUpload.*, leadsCount.memoLeads FROM SalesUpload LEFT JOIN leadsCount ON (SalesUpload.tradeshow = leadsCount.tradeshow AND SalesUpload.name = leadsCount.name)");
        }
        else
            $sth = mysqli_query($db,"SELECT SalesUpload.*, leadsCount.memoLeads FROM SalesUpload LEFT JOIN leadsCount ON (SalesUpload.tradeshow = leadsCount.tradeshow AND SalesUpload.name = leadsCount.name) WHERE SalesUpload.name='".$name."'");
        $rows = array();
        while($r = mysqli_fetch_assoc($sth)) {
            $rows[] = $r;
        }
        $db->close();
        return $rows;
    }

    public function getDataFromDBbyTradeshow($tradeshow){
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        } else { $db = $conn['connection']; }

        if($tradeshow =="all"){
            $sth = mysqli_query($db,"SELECT SalesUpload.*, leadsCount.memoLeads FROM SalesUpload LEFT JOIN leadsCount ON (SalesUpload.tradeshow = leadsCount.tradeshow AND SalesUpload.name = leadsCount.name)");
        } else {
            $sth = mysqli_query($db,"SELECT SalesUpload.*, leadsCount.memoLeads FROM SalesUpload LEFT JOIN leadsCount ON (SalesUpload.tradeshow = leadsCount.tradeshow AND SalesUpload.name = leadsCount.name) WHERE SalesUpload.tradeshow='".$tradeshow."'");
        }
        $rows = array();
        while($r = mysqli_fetch_assoc($sth)) {
            $rows[] = $r;
        }
        $db->close();
        return $rows;
    }

    public function getDataFromDB(){
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        } else { $db = $conn['connection']; }

        $sql = "SELECT SalesUpload.*, leadsCount.memoLeads FROM SalesUpload LEFT JOIN leadsCount ON (SalesUpload.tradeshow = leadsCount.tradeshow AND SalesUpload.name = leadsCount.name)";
        if (!$returnedval=$db->query($sql)) {
            $db->close();
            die("Failed to get data from database!");
        }
        $db->close();
        return $returnedval;
    }

    // get the zoho id
    public function getSalesDetail($salesname, $aiType){
        $salesInfo = $this->getSalesInfo();
        foreach ($salesInfo as $row) {
            if($row['name'] == $salesname) return $row['zohoID'];
        }
        return false;
    }

    // get the zoho id
    public function getIDfromEmail($salesemail, $aiType){
        $salesInfo = $this->getSalesInfo();
        foreach ($salesInfo as $row) {
            if($row['email'] == $salesemail) return $row['zohoID'];
        }
        return false;
    }

    // get the email
    public function getEmailFromName($salesname){
        $salesInfo = $this->getSalesInfo();
        foreach ($salesInfo as $row) {
            if($row['name'] == $salesname) return $row['email'];
        }
        return false;
    }

    // get the email
    public function getNameFromEmail($salesemail){
        $salesInfo = $this->getSalesInfo();
        foreach ($salesInfo as $row) {
            if($row['email'] == $salesemail) return $row['name'];
        }
        return false;
    }
    

    public function getAIFields(){
        $allowedAIFields = array(
                    "Contact Owner Id",
                    "First Name",
                    "Last Name",
                    "Company Name",
                    "Title",
                    "Contact Id",
                    "Contact Owner",
                    "Email",
                    "Phone",
                    "Mobile",
                    "Country",
                    "Fax",
                    "Face to face meeting",
                    "AI Client",
                    "Lead Source",
                    "Description",
                    "Salutation",
                    "TradeShows List",
                    "Qualification",
                    "Qualitfication",
                    "Industry",
                    "Mailing Country",
                    "Email Opt Out",
                    "Skype ID",
                    "Mailing Street",
                    "Mailing City",
                    "Mailing State",
                    "Mailing Zip",
                    "Secondary Email",
                    "Language",
                    "Webinar",
                    "LinkedIn Profile",
                    "Website"
        );
        return $allowedAIFields;
    }
    public function getRequiredFields() {
        return array(
            "Last Name",
            "Company Name",
            "Email",
            "Qualification",
        );
    }
    
   public function getSalesEmail($salesID){
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        } else { $db = $conn['connection']; }

        $sql = "SELECT email FROM salesInfo WHERE zohoID ='".$salesID."'";

        $result=$db->query($sql);

        $email= $result->fetch_assoc();
        $db->close();
        return $email['email'];
   }

    // Get Tradeshow Info
    public function getTradeshows($type = "all", $includeFields = array()) { // tradeshow-exhibiting, tradeshow-visiting, etc...
        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            die("Database Error: " . $conn['msg']); // maybe we should return error
        } else { $db = $conn['connection']; }

        // Select from the DB
        $showagelimit = date("Y-m-d", strtotime("-12 months")); // 12 Months
        $sql = "SELECT * FROM tradeshowList WHERE End_Date > '" . $showagelimit . "'"; // all
        if($type != "all") $sql .= " AND event_type = '" . $type ."'";
        $sql .= " ORDER BY tradeshow DESC";
        $sth = mysqli_query($db, $sql);

        $tradeshowList = array();
        if( !empty($includeFields) ) {
            while($r = mysqli_fetch_assoc($sth)) {
                $tradeshowList[$r['tradeshow']]['tradeshow'] = $r['tradeshow'];
                foreach ($includeFields as $field) $tradeshowList[$r['tradeshow']][$field] = $r[$field];
            }
        } else {
            while($r = mysqli_fetch_assoc($sth)) {
                $tradeshowList[$r['tradeshow']] = $r['tradeshow'];
            }
        }
        $db->close();
        return $tradeshowList;
    }

    // get sales person info
    public function getSalesInfo() {
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        } else { $db = $conn['connection']; }

        // Select only active users
        $sth = mysqli_query($db,"SELECT * FROM salesInfo WHERE active_status > 0");
        $salesInfo = array();
        while($r = mysqli_fetch_assoc($sth)) $salesInfo[] = $r;
        // Sort array by sales names in alphabetical order
        $keys = array();
        foreach ($salesInfo as $key => $row) {
            $keys[$key] = $row['name'];
        }
        array_multisort($keys, SORT_ASC, $salesInfo);        
        // Close the DB connection and return the array
        $db->close();
        return $salesInfo;
    }

    /**
     * @Route("/addTradeshowToList")
     * @Method("POST")
     *
     */
    public function addTradeshowToList(){
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            die("Connection failed: " . $conn['connection']->connect_error);
        } else { $db = $conn['connection']; }
        $sql1 ="INSERT INTO tradeshowList (tradeshow, friendlyName, Industry, Start_Date, End_Date, budget, event_type, organizer, location) VALUES ('".$_POST['show']."', '".$_POST['name']."', '".$_POST['ind']."', '".$_POST['start']."', '".$_POST['end']."', '".$_POST['bud']."', '".$_POST['event_type']."', '".$_POST['org']."', '".$_POST['loc']."');";
        $db->query($sql1);
        $error = $db->error;
        $db->close();
        if($error != "") {
            return new Response($error);
        } else {
            return new Response("success");
        }
    }

    /**
     * @Route("/deleteTradeshowFromList")
     * @Method("POST")
     *
    */
    public function deleteTradeshowFromList(){
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            die("Connection failed: " . $conn['connection']->connect_error);
        } else { $db = $conn['connection']; }

        $sql1 ="DELETE FROM tradeshowList WHERE tradeshow= '".$_POST['tradeshow']."';";
        $db->query($sql1);
        $db->close();
        return new Response("success");
    }

    /**
     * [process sales upload csv description]
     * @param [type] $path       [description]
     * @param [type] $name       [description]
     * @param [type] $salesName  [description]
     * @param [type] $tradeshow  [description]
     * @param [type] $leadsource [description]
     * @param [type] $AIType     [description]
     */
    public function process_sales_upload_csv($path, $name, $salesName, $tradeshow, $leadsource, $AIType){
        $i=0;

        // get zoho id
        $salesID = $this->getSalesDetail( $salesName, $AIType );
        $newName = str_replace(' ','_', $salesName)."_".$name;
        $resultCsv = fopen($path."/".$newName, 'w+');
        if(!$resultCsv) die("Can't create file");


        $arr_headers = Array();
        $filters_filename = dirname(__FILE__)."/../Resources/filters_data/sales_upload_filters.csv";
        $obj_filter_rules = $this->get_filter_rules_csv($filters_filename);

        // write new csv file
        if(($handle = fopen($path."/".$name, "r")) !== false && $resultCsv !== false) {
            while(($row = fgetcsv($handle, 1000, ",")) !== FALSE) {

                // handle header
                if( $i == 0 ) {
                    //altering field names and prepare for upload
                    $row = str_replace ("Company Name" , "Account Name" , $row);
                    $row = str_replace ("Qualitfication" , "Qualification" , $row);
                    $row[] = "SMOWNERID";
                    $row[] = "Lead Source";
                    if($tradeshow!=""){ $row[]="TradeShows List"; }
                    $row[]="Service Type";

                    // grab all the headers
                    for($n=0;$n<count($row);$n++) {
                        $arr_headers[] = $row[$n];
                    }

                // handle all other rows
                } else {
                    // this is the SMOWNERID zohoID
                    $row[] = $salesID;
                    if( $tradeshow != "" ){
                        $row[] = "Tradeshow";
                        $row[] = $tradeshow;
                    } else {
                        $row[] = $leadsource;
                    }
                    $row[] = $AIType;
                }


                // we need assoc array rather than regular indexed array
                $temp_data = Array();
                for($n=0;$n<count($row);$n++) {
                    $temp_data[$arr_headers[$n]] = $row[$n];
                }


                // filter our row
                $temp_data = $this->process_filter_rules($obj_filter_rules,$temp_data);
                

                // put back into array
                if(is_array($temp_data)) {
                    $n = 0;
                    foreach($temp_data as $key => $value) {
                        $row[$n] = $value;
                        $n++;
                    }
                }

                fputcsv($resultCsv, $row);
                $i++;
            }
            $i = $i-1;
            // save to SalesUpload
            $sales_upload_id = $this->add_sales_upload_record( $salesName, $salesID, $newName, $i, $AIType, $tradeshow, $leadsource );
        } // end write new csv file


        fclose($handle);
        fclose($resultCsv);


        // process new csv file and create companies that are not in the system
        $this->queue_companies_to_add_into_zoho($path."/".$newName, $sales_upload_id);
        unlink($path."/".$name);
    }


    // get filter rules from csv file
    function get_filter_rules_csv($filename) {
        $obj = new \Stdclass;
        $obj->arr_data = Array();
        if (($handle = fopen($filename, "r")) !== FALSE) {

            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
                $arr_data[]=$data;
            }

            $obj->arr_data = $arr_data;
            fclose($handle);
        }
        return $obj;
    }


    // process filter rules on our data
    function process_filter_rules($obj,$data){

        $arr_data = $obj->arr_data;
        for($n=0;$n<count($arr_data);$n++) {
            //print_r($arr_data[$n]);


            $field = $arr_data[$n][0];

            // if field contains value replace with
            if ($arr_data[$n][1] == "Contains" && $arr_data[$n][3] == "ReplaceFieldWith") {


                if (isset($data[$field])) {
                    if (preg_match("/".$arr_data[$n][2]."/u", $data[$field])) {
                        $data[$field] = $arr_data[$n][4];
                    }
            }

            // if our field equals value then
            } else if ($arr_data[$n][1] == "Equals" && $arr_data[$n][3] == "ReplaceFieldWith") {

                if (isset($data[$field])) {
                    if ($data[$field] == $arr_data[$n][2]) {
                        $data[$field] = $arr_data[$n][4];
                    }
                }
            }
        }
        return $data;
    }


    /**
     * @Route("/commentsPopup")
     * 
     */
    public function commentsAction(){
        $id = ( isset($_GET['id']) ? $_GET['id'] : 0 );
        return $this->render('AIResponsiveBundle:salesLeads:commentsPopup.html.twig',array("id"=>$id));
    }


    // grab data from SalesUpload
    public function get_sales_upload_by_id($id){
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            die("Connection failed: " . $conn['connection']->connect_error);
        } else { $db = $conn['connection']; }

        $sql = "SELECT * FROM SalesUpload WHERE id=".$id;
        if(!($info=$db->query($sql))){ die("Failed getting ZOHO ID!"); }
        $db->close();
        return $info->fetch_assoc();
    }

    public function sendEmail($type, $who = "A Team Member"){
        if ($this->is_prod_server()) {
        if($type=="salesLeads"){
            $email = $this->BaseEmails_LeadsUploaded;
            $subject = $who . " just uploaded some leads.";
                $message = "New leads have been uploaded. Please go to <a href='http://staging.asiainspection.com:99/approve-sales-leads'>Approve Sales Leads</a> page to check.";
        }
        
        
        $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom('marketing@asiainspection.com')
        ->setTo($email)
        ->setBody($this->renderView(
                'AIResponsiveBundle:salesLeads:emailUploaded.html.twig',
                array('message' => $message)
            ),
            'text/html'
        );

        $mailer = $this->get('mailer');
        $mailer->send($message);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);
    }
    }


    /**
     * @Route("/sales_uploads/reject_sales_leads_submission")
     * @Method("POST")
     */
    // when rejection button is hit in sales approval page
    public function reject_sales_leads_submission(){

        $comments = $_POST['commentsSales'];
        $id = $_POST['id'];
        $row = $this->get_sales_upload_by_id($id);
        $tradeshow = $row['tradeshow'];
        $date = $row['uploadDateTime'];
        $email  = $this->getSalesEmail($row['zohoID']);
        $path = $this->baseDir . "/LeadsUploadFromInterface";
        

        // only send email if on production
        if ($this->is_prod_server()) {
            $message = \Swift_Message::newInstance()
            ->setSubject('Your uploaded leads have been rejected.')
            ->setFrom('marketing@asiainspection.com')
            //->setTo($email)
            ->setTo(array('vincent.macdonald@asiainspection.com'))
            ->setBody($this->renderView(
                    'AIResponsiveBundle:salesLeads:emailFormat.html.twig',
                    array('name' => $row['name'],'tradeshow'=>$row['tradeshow'],"date"=>$date,"comments"=>$comments)
                ),
                'text/html'
            )
            ->attach(\Swift_Attachment::fromPath($path."/".$row['fileName']));
            $mailer = $this->get('mailer');
            $mailer->send($message);
            $spool = $mailer->getTransport()->getSpool();
            $transport = $this->get('swiftmailer.transport.real');
            $spool->flushQueue($transport);
        }    
        $file = ($path."/".$row['fileName']);
        if (!is_file($file)) {
            @unlink($path."/".$row['fileName']);
        }

        return $this->reject_csv($id, $comments);
    }


    // set status of csv to reject
    public function reject_csv($id, $reason_for_rejection){
        $success= false;
        $conn = $this->getDBconnection();
        if( $conn['error'] ) {
            die("Connection failed: " . $conn['connection']->connect_error);
        } else { $db = $conn['connection']; }

        $sql = "UPDATE SalesUpload SET status='rejected', reject_reason = '".$db->real_escape_string($reason_for_rejection)."' WHERE id=".$id;
        //print "sql is $sql\n";
        if($db->query($sql)){
            $success="success";
        } else{
            $success = "failed";
        }
        $db->close();
        return new Response($success);
    }
 
    /**
     * @Route("/uploadMemo")
     * 
     */
    public function uploadMemo(){
        $session = $this->getSession();
        $salesInfo['salesInfo'] = $this->getSalesInfo();
        $salesInfo['tradeshowList'] = $this->getTradeshows();

        //Keeping characters the need to be escaped from breaking everything
        // using prepared statements instead 
        // foreach ($_POST as $key => $value) { $_POST[$key] = addslashes($value); }

        if( empty($_POST) ) return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "No Data Recieved", "error" => "Something went wrong with the upload, please email your memo to vincent.macdonald@asiainspection.com (include any photos/attachments).") );
        //Get The Sales Name and Tradeshow Name
        $event_type = $_POST['event_type'];
        $salesName = $_POST['SalesID'];
        $tradeShow = $_POST['tradeshow'];
        $day = getd($_POST['day']);
        $booth_feedback = getd($_POST['booth_feedback']);
        $suggestions = getd($_POST['suggestions']);
        $leadQuality = getd($_POST['leadQuality']);
        $low_qual_reason = getd($_POST['low_qual_reason']);
        $clientsMet = getd($_POST['clientsMet']);
        $exhibitors = getd($_POST['exhibitors']);
        $exhibitor_countries = getd($_POST['exhibitor_countries']);
        $is_competitors = (getd($_POST['is_competitors']) == "yes" ? 1 : 0);
        $competitors = getd($_POST['competitors']);
        $traffic = getd($_POST['traffic']);
        $exhibitor_feedback = getd($_POST['exhibitor_feedback']);
        $visitors = getd($_POST['visitors']);
        $visitor_countries = getd($_POST['visitor_countries']);
        $visitor_poll = getd($_POST['visitor_poll']);
        $sponsor_details = getd($_POST['sponsor_details']);
        $do_next = (getd($_POST['do_next']) == "yes" ? 1 : 0 );
        $next_reasons = getd($_POST['next_reasons']);
        $concurrent = (getd($_POST['concurrent']) == "yes" ? 1 : 0 );
        $concurrent_reasons = getd($_POST['concurrent_reasons']);
        $attendee_industry = getd($_POST['attendee_industry']);
        $int_topic = getd($_POST['int_topic']);
        $sug_topic = getd($_POST['sug_topic']);
        $attendee_count = getd($_POST['attendee_count']);
        $attendee_opinion = getd($_POST['attendee_opinion']);
        $seminar_rating = getd($_POST['seminar_rating']);
        $how_heard = getd($_POST['how_heard']);
        $disableEmail = ( isset($_POST['adminNoSendEmail']) ? true : false );

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }

        //Loop through all the results and build an array
        $i = 0;
        $results = array();
        while( isset($_POST['result_'.$i.'_name']) !== FALSE ){
            $result = array();
            $result['name'] = $_POST['result_'.$i.'_name'];
            $result['num'] = $_POST['result_'.$i.'_num'];
            $result['h1'] = $_POST['result_'.$i.'_h1'];
            $result['h2'] = $_POST['result_'.$i.'_h2'];
            $result['h3'] = $_POST['result_'.$i.'_h3'];
            $result['h4'] = ( isset($_POST['result_'.$i.'_h4']) ? $_POST['result_'.$i.'_h4'] : 0);
            $result['w1'] = $_POST['result_'.$i.'_w1'];
            $result['w2'] = $_POST['result_'.$i.'_w2'];
            $result['w3'] = $_POST['result_'.$i.'_w3'];
            $result['w4'] = ( isset($_POST['result_'.$i.'_w4']) ? $_POST['result_'.$i.'_w4'] : 0);
            $result['c1'] = $_POST['result_'.$i.'_c1'];
            $result['c2'] = $_POST['result_'.$i.'_c2'];
            $result['c3'] = $_POST['result_'.$i.'_c3'];
            $result['c4'] = ( isset($_POST['result_'.$i.'_c4']) ? $_POST['result_'.$i.'_c4'] : 0);
            $results[] = $result;
            $i++;
        }

        //Adding Emails to list of people to email
        switch ($event_type) {
            case "tradeshow-exhibiting":
            case "event-speaking":
            case "event-hosted":
                $emails = $this->BaseEmails_TradeshowMemo_Booth;
                break;
            case "tradeshow-visiting":
            case "event-visiting":
                $emails = $this->BaseEmails_TradeshowMemo_NoBooth;
                break;
        }

        foreach ($_POST as $key => $value) {
            //Loop through all the extra emails given manually and add them to the list of people to email
            $prefix = substr($key, 0, 6);
            if( strtolower($prefix) == "email_" ){
                if(strpos($value,'@asiafoodinspection.com') !== false || strpos($value,'@asiainspection.com') !== false || strpos($value,'@ansecogroup.com') !== false) $emails[] = $value;
                if( strpos($value,'@gmail.com') !== false ) $emails[] = $value;
            }
            //Loop through all the extra emails given thru the dropdown menu and add them to the list of people to email
            $prefix = substr($key, 0, 10);
            if( strtolower($prefix) == "emaildrop_" ){
                $Atendee = mysqli_fetch_assoc($db->query("SELECT email FROM salesInfo WHERE name='".$value."'"));
                $emails[] = $Atendee['email'];
            }
        }

        //Add emails of person creating the memo and sales with leads included (Skip This Stage on Development Servers)
        $ShowManager = mysqli_fetch_assoc($db->query("SELECT email FROM salesInfo WHERE name='".$salesName."'"));
        $emails[] = $ShowManager['email'];
        foreach($results as $result){
            $Atendee = mysqli_fetch_assoc($db->query("SELECT email FROM salesInfo WHERE name='".$result['name']."'"));
            $emails[] = $Atendee['email'];
        }

        //Go through the results and update the leadsCount table in the database
        $totalNumLeads = 0; //Total Leads for the Show
        foreach($results as $result){
            $totalNumLeads = $totalNumLeads + $result['num'];
            $sql2 = "SELECT memoLeads FROM leadsCount WHERE name='".$result['name']."'AND tradeshow='".$tradeShow."'";
            $numberOfLeads = $db->query($sql2);
            if( mysqli_num_rows($numberOfLeads) > 0 ){
                $number= $numberOfLeads->fetch_assoc();
                $num = $number['memoLeads'] + $result['num'];
                $sql1 = "UPDATE leadsCount SET memoLeads=".$num." WHERE name='".$result['name']."'AND tradeshow='".$tradeShow."'";
            } else {
                $sql1 ="INSERT INTO leadsCount (name, tradeshow, memoLeads) VALUES ('".$result['name']."','".$tradeShow."',".$result['num'].");";
            }
            $db->query($sql1);
        }

        if (isset($_FILES['memoPhoto'])) {
            // Loop through the array of uploaded photos
            $num_files = count($_FILES['memoPhoto']['tmp_name']);
            $photos = array();
            if( file_exists($_FILES['memoPhoto']['tmp_name'][0]) ){
                for( $i=0; $i < $num_files; $i++ ) {
                    if(!exif_imagetype($_FILES["memoPhoto"]['tmp_name'][$i])){
                        $salesInfo['errors'][]= "One (or more) of the upload files is not an image ";
                        return $this->render('AIResponsiveBundle:salesLeads:tradeshowMemo.html.twig',$salesInfo);
                    }else{
                        // Upload to S3 
                        $Global = $this->get('global_functions');
                        $fileType = substr($_FILES['memoPhoto']['type'][$i], 6);
                        $newName = $day."_".$i.".".$fileType;
                        $newName = str_replace(' ','_', $newName);
                        $result = $Global->uploadToS3($this, $_FILES["memoPhoto"]["tmp_name"][$i], "LeadsTool/memoPhotos/".$tradeShow."/".$newName);
                        $photos[] = $newName;
                    }
                }
            }
            $photosJson = json_encode($photos);
        } else {
            $photosJson = '[]';
        }
        $resultsJson = json_encode($results);

        $stmt = $db->stmt_init();
        //Insert Tradeshow Memo Into Database
        $stmt->prepare("INSERT INTO eventMemos (event_type, tradeshow, salesName, day, booth_feedback, leadQuality, low_qual_reason, clientsMet, exhibitors, exhibitor_countries, competitors, traffic, next_reasons,  concurrent_reasons, photos, suggestions, exhibitor_feedback, visitors, visitor_countries, visitor_poll, sponsor_details, results, attendee_industry, attendee_opinion, int_topic, sug_topic, seminar_rating, how_heard, totalleads, is_competitors, do_next, concurrent, attendee_count)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssssssssssssssssssssssssiiiii", $event_type, $tradeShow, $salesName, $day, $booth_feedback, $leadQuality, $low_qual_reason, $clientsMet, $exhibitors, $exhibitor_countries, $competitors, $traffic, $next_reasons, $concurrent_reasons, $photosJson, $suggestions, $exhibitor_feedback, $visitors, $visitor_countries, $visitor_poll, $sponsor_details, $resultsJson, $attendee_industry, $attendee_opinion, $int_topic, $sug_topic, $seminar_rating, $how_heard, $totalNumLeads, $is_competitors, $do_next, $concurrent, $attendee_count);

        if( ! $stmt->execute() ){
            return $this->uploadFailure($db->error);
        } else {
            $row['salesName'] = $salesName;
            $row['tradeshow']= $tradeShow;
            $row['day'] = $day;
            $row['booth_feedback'] = $booth_feedback;
            $row['exhibitors'] = $exhibitors;
            $row['exhibitor_countries'] = $exhibitor_countries;
            $row['exhibitor_feedback'] = $exhibitor_feedback;
            $row['is_competitors'] = $is_competitors;
            $row['visitors'] = $visitors;
            $row['visitor_countries'] = $visitor_countries;
            $row['visitor_poll'] = $visitor_poll;
            $row['leadQuality'] = $leadQuality;
            $row['low_qual_reason'] = $low_qual_reason;
            $row['clientsMet']= $clientsMet;
            $row['competitors'] = $competitors;
            $row['traffic'] = $traffic;
            $row['sponsor_details'] = $sponsor_details;
            $row['do_next'] = $do_next;
            $row['next_reasons']= $next_reasons;
            $row['concurrent']= $concurrent;
            $row['concurrent_reasons']= $concurrent_reasons;
            $row['photos'] = $photosJson;
            $row['suggestions'] = $suggestions;
            $row['results'] = $resultsJson;
            $row['totalleads'] = $totalNumLeads;
            $row['attendee_industry'] = $attendee_industry;;
            $row['attendee_opinion'] = $attendee_opinion;;
            $row['int_topic'] = $int_topic;;
            $row['sug_topic'] = $sug_topic;;
            $row['attendee_count'] = $attendee_count;;
            $row['seminar_rating'] = $seminar_rating;;
            $row['how_heard'] = $how_heard;;
            //Send the emails if it's not disabled
            if( !$disableEmail ) {
                $this->sendTradeshowMemoEmail($row, $emails, $event_type);
            }
        }
        $db->close();

        return $this->uploadSuccess("memo", $row['salesName']);
    }

    public function sendTradeshowMemoEmail($row,$emails,$event_type){
        $link = "http://staging.asiainspection.com:99/tradeshowMemo/";              
        $results = array();
        $array = array();
        $array['row'] =$row;
        $photos = array();
        $results = json_decode($row['results']);
        $photos = json_decode($row['photos']);
        $array['photos'] = $photos; 
        $array['results']=$results;
        $array['link'] = $link;
        if ($this->is_prod_server()) {
            $emails = array_unique($emails);
        } else {
            // $emails = "daryl@273kelvin.ca";
            $emails = "vincent.macdonald@asiainspection.com";
            // $emails = "";
        }

        if ($event_type == "tradeshow-exhibiting") {
            $subject = "Tradeshow Memo: ".$row['tradeshow']." - ".$row['day'];
            $template = 'AIResponsiveBundle:salesLeads:emailMemo.html.twig';
        } elseif ($event_type == "tradeshow-visiting") {
            $subject = "Tradeshow Memo: ".$row['tradeshow']." - No Booth";
            $template = 'AIResponsiveBundle:salesLeads:emailMemoNoBooth.html.twig';
        } elseif ($event_type == "event-visiting") {
            $subject = "Event Memo: ".$row['tradeshow']." - Sponsoring / Visiting";
            $template = 'AIResponsiveBundle:salesLeads:emailMemoEventVisit.html.twig';
        } elseif ($event_type == "event-hosted") {
            $subject = "Event Memo: ".$row['tradeshow']." - Self-hosted";
            $template = 'AIResponsiveBundle:salesLeads:emailMemoEventHosted.html.twig';
        } elseif ($event_type == "event-speaking") {
            $subject = "Event Memo: ".$row['tradeshow']." - Speaking";
            $template = 'AIResponsiveBundle:salesLeads:emailMemoEventSpeaking.html.twig';
        }
        $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom('marketing@asiainspection.com')
        ->setTo($emails)
        ->setBody($this->renderView($template,$array),'text/html');
        $mailer = $this->get('mailer');
        $mailer->send($message);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);

        return new Response("success");
    }

    /**
      * @Route("/ResendTradeshowMemo/{id}/{type}")
      * @Method("GET")
      * @param  [int] $id    [Unique ID of Memo]
      * @param  [int] $type [Event type]
      */
    public function resendTradeshowMemoEmail($id, $type){
        //If Permissions aren't set, they shouldn't even be accessing this page
        if( ! $this->get('security.context')->isGranted('ROLE_ADMIN') ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Access Denied", "error" => "You do not have access rights for this page. Please login.") );
        }
        $allowedMemoTypes = array("tradeshow-exhibiting", "tradeshow-visiting", "event-visiting", "event-speaking", "event-hosted", "webinar");

        //Check the variables we will be using in our query for possible SQL injection attacks
        if( !is_numeric($id) || !( in_array($type, $allowedMemoTypes) ) ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Invalid Memo ID", "error" => "This tradeshow memo does not exist.") );
        }

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }
      
        $sql = "SELECT * FROM eventMemos WHERE id =".$id;
        //Try the SQL query and redirect on failure
        if (!$returnedval = $db->query($sql)) {
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $db->error) );
        }

        $row = mysqli_fetch_assoc($returnedval); //Get the Row with our data
        $output = array(); //Create array to hold output
        $output['row'] = $row;
        $output['photos'] = json_decode($row['photos']);
        $output['results'] = json_decode($row['results']);
        $output['link'] = "http://staging.asiainspection.com:99/tradeshowMemo/";

        //Check the event type to select the proper emails and template
        if ($type == "tradeshow-visiting") { 
            $subject = "Tradeshow Memo: ".$row['tradeshow']." - No Booth";
            $twigTemplate = "AIResponsiveBundle:salesLeads:emailMemoNoBooth.html.twig";
            $emails = $this->BaseEmails_TradeshowMemo_NoBooth;
        } elseif ($type == "tradeshow-exhibiting") {
            $subject = "Tradeshow Memo: ".$row['tradeshow']." - ".$row['day'];
            $twigTemplate = "AIResponsiveBundle:salesLeads:emailMemo.html.twig";
            $emails = $this->BaseEmails_TradeshowMemo_Booth;
        } elseif ($type == "event-visiting") {
            $subject = "Event Memo: ".$row['tradeshow']." - Sponsoring / Visiting";
            $twigTemplate = "AIResponsiveBundle:salesLeads:emailMemoEventVisit.html.twig";
            $emails = $this->BaseEmails_TradeshowMemo_NoBooth;
        } elseif ($type == "event-hosted") {
            $subject = "Event Memo: ".$row['tradeshow']." - Self-hosted";
            $twigTemplate = "AIResponsiveBundle:salesLeads:emailMemoEventHosted.html.twig";
            $emails = $this->BaseEmails_TradeshowMemo_Booth;
        } elseif ($type == "event-speaking") {
            $subject = "Event Memo: ".$row['tradeshow']." - Speaking";
            $twigTemplate = "AIResponsiveBundle:salesLeads:emailMemoEventSpeaking.html.twig";
            $emails = $this->BaseEmails_TradeshowMemo_Booth;
        }
        if (! $this->is_prod_server()) {
            // $emails = array("daryl@273kelvin.ca");
            $emails = array("vincent.macdonald@asiainspection.com");
            // $emails = array("");
        }
        //Try sending the tradeshow memo
        try {
            $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('marketing@asiainspection.com')
            ->setTo($emails)
            ->setBody($this->renderView($twigTemplate,$output),'text/html');
            $mailer = $this->get('mailer');
            $mailer->send($message);
            $spool = $mailer->getTransport()->getSpool();
            $transport = $this->get('swiftmailer.transport.real');
            $spool->flushQueue($transport);
        } catch (\Exception $e) {
            //Any errors sending the email? Go to the Error view with the error message
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Error Sending Email", "error" => "Exception: ".$e->getMessage()) );
        }

        //On Success
        $htmlmsg = "The tradeshow memo has been emailed to the following addresses:<br />";
        foreach($emails as $email) $htmlmsg .= "<p>$email</p>";
        return $this->render('AIResponsiveBundle:salesLeads:genericMessage.html.twig', array("title" => "Tradeshow Memo Sent Successfully", "message" => $htmlmsg, "link" => "/viewMemoDetail/".$id, "linkText" => "Back to Memo"));
    }


    /**
     * @Route("/sales_uploads/approve_upload")
     * @Method("POST")
     */
    // when approve button is hit it will upload to zoho and create new companies if not found
    public function approve_sales_leads_upload(){
        $success= "Failed";
        $id = $_POST['id'];

        
        // only upload on production
        if($this->upload_sales_leads_to_zoho($id)){
            //Try to get a connection to the database
            $conn = $this->getDBconnection();
            if( $conn['error'] ) {
                die("Connection failed: " . $conn['connection']->connect_error);
            } else { $db = $conn['connection']; }

            $sql = "UPDATE SalesUpload SET status='approved' WHERE id=".$id;
            if($reference = $db->query($sql)){
                $success="success";
            } else {
                $success = "Failed";
            }
            $db->close();
            //$this->sendSuccessEmail($id);
        }
        return new Response($success);
    }

    /**
     * @Route("/RebuildLeads")
     * Used by Administrators to rebuild the leadCount table using the tradeshow memos and SalesUpload tables
     * Only to be used when all the data is massively out of whack from a huge bug
     * 
     */
    public function rebuildLeadsTable(){
        //If Permissions aren't set, they shouldn't even be accessing this page
        if( ! $this->get('security.context')->isGranted('ROLE_ADMIN') ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Access Denied", "error" => "You do not have access rights for this page. Please login.") );
        }

        $newDB = array();

        //Try to get a connection to the database
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }

        $sql = "SELECT tradeshow, salesName, results FROM eventMemos";
        //Try the SQL query and redirect on failure
        if (!$TradeshowMemoResult = $db->query($sql)) {
            $dberror = $db->error;
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") ". $dberror) );
        }

        //Loop through the tradeshow memos and create an array of the leads
        while($row = $TradeshowMemoResult->fetch_assoc()){
            $leads = json_decode($row['results']);
            $show = $row['tradeshow'];

            //Add the leads to the array of values for the new database
            foreach($leads as $lead){
                $name = $lead->name;
                if( !isset($newDB[$show][$name]['memoLeads']) ) $newDB[$show][$name]['memoLeads'] = 0;
                $newDB[$show][$name]['memoLeads'] = $newDB[$show][$name]['memoLeads'] + $lead->num;
            }
        }

        $sql = "SELECT tradeshow, name, leadsUploaded, status FROM SalesUpload";
        //Try the SQL query and redirect on failure
        if (!$UploadLeadsResult = $db->query($sql)) {
            $dberror = $db->error;
            $db->close();
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $dberror) );
        }

        //Loop through the uploaded leads and add them to the array
        while($row = $UploadLeadsResult->fetch_assoc()){
            $show = $row['tradeshow'];
            $name = $row['name'];
            if( !isset($newDB[$show][$name]['uploadLeads']) ) $newDB[$show][$name]['uploadLeads'] = 0;
            if( $row['status'] == "approved" ) $newDB[$show][$name]['uploadLeads'] = $newDB[$show][$name]['uploadLeads'] + $row['leadsUploaded'];
        }

        //Delete all the records in the leadsCount Table
        $db->query("TRUNCATE TABLE leadsCount");
        //Reset the Auto Increment ID value in the database
        $db->query("ALTER TABLE leadsCount AUTO_INCREMENT = 1");

        //Build the SQL, minimize the number of hits to the server
        $i = 0;
        $sql = "";
        foreach( $newDB as $show => $sales) {
                foreach( $sales as $name => $leads){
                    if($i == 50){
                        //Run the Sql and return error if there's any issues
                        if (!$db->multi_query($sql)) {
                            $dberror = $db->error;
                            $db->close();
                            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $dberror) );
                        }
                        //Reset the variables and keep on going
                        $i = 0;
                        $sql = "";
                    }
                    //Add the SQL to insert this row
                    if( !isset($leads['memoLeads']) ) $leads['memoLeads'] = 0;
                    if( !isset($leads['uploadLeads']) ) $leads['uploadLeads'] = 0;
                $sql .= "INSERT INTO leadsCount (name, tradeshow, memoLeads, uploadLeads) VALUES ('".$name."', '".$show."', ".$leads['memoLeads'].", ".$leads['uploadLeads']."); ";
                    $i++;
                }
            }
        //This will catch the last remaining queries (or if there are less than 50 total)
        do {  $db->use_result();  } while ( $db->more_results() && $db->next_result() ); //Clearing the result set
        if($i < 50){
            if (!$db->multi_query($sql)) {
                $dberror = $db->error;
                $db->close();
                return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Query Error", "error" => "(".__LINE__.") " . $dberror) );
            }
        }

        return $this->render('AIResponsiveBundle:salesLeads:genericMessage.html.twig', array("title" => "Leads Count Successfully Rebuilt", "message" => "The table has been rebuilt using the information from the uploads and tradeshow memos.", "link" => "/sales-leads", "linkText" => "Back to Home"));
    } // End of rebuildLeadsTable




    // upload leads into zoho and update leadsCount table
    public function upload_sales_leads_to_zoho($id){
        // grab data from SalesUpload table
        $data = $this->get_sales_upload_by_id($id); //Does this work 100% of the time?
        $type = $data['account'];
        $name = $data['fileName'];
        $tradeshow = $data['tradeshow'];
        $sales = $data['name'];
        $line = $data['leadsUploaded'];

        // update leads to zoho will only run on production

        $this->create_companies_in_zoho($id);


        $returnedval = $this->RunZohoUpdate($name, $type,$line);

        if(isset($returnedval['success'])){
            if( $type != "Anseco" ){
                //Try to get a connection to the database
                $conn = $this->getDBconnection();
                if( $conn['error'] ){
                    die("Connection Failed: " . $conn['msg']);
                }else{ $db = $conn['connection']; }


                $sql2 = "SELECT uploadLeads FROM leadsCount WHERE name='".$sales."'AND tradeshow='".$tradeshow."'";
                $numberOfLeads=$db->query($sql2);
                if(mysqli_num_rows($numberOfLeads)>0){
                    $number= $numberOfLeads->fetch_assoc();
                    $num = $number['uploadLeads']+$line;
                    $sql1 = "UPDATE leadsCount SET uploadLeads=".$num." WHERE name='".$sales."'AND tradeshow='".$tradeshow."'";
                } else {
                    $sql1 ="INSERT INTO leadsCount (name, tradeshow,uploadLeads) VALUES ('".$sales."','".$tradeshow."',".$line.");";
                }
                $db->query($sql1);
                $db->close();
            }
            return true;
        } else {
            return false; 
        }
    }

    // determines whether we are on production server
    private function is_prod_server() {
        // Return false on Staging & Dev environment and true in Prod environment
        return false;
            }

    // update leads on zoho
    // will only run on production
    private function RunZohoUpdate($file, $type, $line){

        // if not production server then dont connect to zoho
        if (!$this->is_prod_server()) {
            return array("success"=>true);
        }

        $url = "https://crm.zoho.com/crm/private/xml/Contacts/insertRecords";
        $path = $this->baseDir . "/LeadsUploadFromInterface";
        $apiAuth = "8096cae1936ed6849ec0a6de6cdc043f";
        $apiKey = "787d87244c85ce8ee1a63ab6342b1dea";
        $fields = array();
        $xml = "";

        //Setting up CURL options
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER , array("Content-Type: application/x-www-form-urlencoded; charset=\"utf-8\"") );
        setlocale(LC_ALL, 'zh_CN.UTF-8');
        header("Content-Type: text/html; charset=utf-8");

        //The loop the does all the magic, it opens the CSV file
        if (($handle = fopen($path."/".$file,"r")) !== FALSE) {
            $row = 0;   //row counter
            $xmlloop = 0;
        
            while(($data = fgetcsv($handle, ",")) !== FALSE) {  //loop through each line of the CSV file
                if($row == 0) {
                    $xml = "<?xml version='1.0' encoding='utf-8'?><Contacts>";
                    foreach($data as $field){array_push($fields, $field);}  //create a list of field names
                    $xmlloop++;
                } else {
                    //Setup XML Data                
                    if($xmlloop == 0) {
                        $xmlloop++;
                        $xml = "<?xml version='1.0' encoding='utf-8'?><Contacts>";
                        $xml .= "<row no='".$xmlloop."'>";
                        $x = 0;
                        foreach($fields as $field) {
                            $xml .= "<FL val='".$field."'><![CDATA[". $data[$x]. "]]></FL>";
                            $x++;
                        }
                        $xml .= "<FL val='Service Type'><![CDATA[".$type."]]></FL>";
                        $xml .= "</row>";
                    } else if ($xmlloop == 99) {
                        $xml .= "</Contacts>";
                        $xmlloop = 0;
                        $returned = $this->SendToZoho($ch, $xml);
                        $this->AddToLog($file, $xml, $returned);
                    } else {
                        $xml .= "<row no='".($xmlloop + 1)."'>";
                        $x = 0;
                        foreach($fields as $field) {
                            $xml .= "<FL val='".$field."'><![CDATA[". $data[$x]. "]]></FL>";
                            $x++;
                        }
                        $xml .= "<FL val='Service Type'><![CDATA[".$type."]]></FL>";
                        $xml .= "</row>";
                        $xmlloop++;
                    }
                }//End Row
            
                //Test returned value
                if($returned === false) {
                    output("LIMIT REACHED! EXITING...");
                    fclose($handle); //close the file when complete 
                    curl_close($ch); //Closing CURL
                    return array("error"=>$output);         
                }
                $row++;
            }// End File Read

            if($xmlloop !== 99){
                $xml .= "</Contacts>";
                $returned = $this->SendToZoho($ch, $xml);
                $this->AddToLog($file, $xml, $returned);
            }

            fclose($handle); //close the file when complete
            curl_close($ch); //Closing CURL
            return array("success"=>true);
        } //End File Open Check
        curl_close($ch);

    } //End Function RunZohoUpdate
            
/**
 * [SendToZoho Send some XML to Zoho]
 * @param [handle] $ch       [Curl Handle]
 * @param [string] $xml2send [XML to send]
 */
function SendToZoho($ch, $xml2send){
            //Send the data to Zoho
    $query = array('authtoken'=>'8096cae1936ed6849ec0a6de6cdc043f','scope'=>'crmapi', 'version'=>'4','xmlData'=>$xml2send);
            $query = http_build_query($query);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            $returnedval = curl_exec($ch);

    //Check if api limit was hit
            if(strrpos(($returnedval->result->message == ""?$returnedval->error->message:$returnedval->result->message), "You crossed your license limit") !== FALSE){
        return false;
    }else{
        return simplexml_load_string($returnedval);
            }
        }
    // sends success email
    public function sendSuccessEmail($id){
        $row = $this->get_sales_upload_by_id($id);
        $tradeshow = $row['tradeshow'];
        $date = $row['uploadDateTime'];
        $email  = $this->getSalesEmail($row['zohoID']);
        $message = \Swift_Message::newInstance()
        ->setSubject('Your uploaded leads have been approved.')
        ->setFrom('marketing@asiainspection.com')
        ->setTo($email)
        ->setCC('vincent.macdonald@asiainspection.com')
        ->setBody($this->renderView(
                'AIResponsiveBundle:salesLeads:emailSuccessFormat.html.twig',
                array('name' => $row['name'],'tradeshow'=>$row['tradeshow'],"date"=>$date,"reference" => $id)
            ),
            'text/html'
        );
        $mailer = $this->get('mailer');
        $mailer->send($message);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);
    }


    /**
     * [AddToLog - For saving the details of the records inserted to a logfile]
     * @param [string] $file    [the name of the CSV file being uploaded]
     * @param [string] $xmlSent [The XML that was sent to Zoho (as a string)]
     * @param [xml] $xmlGot  [the XML response received from Zoho]
     */
    function AddToLog($file, $xmlSent, $xmlGot){
        $idlog = fopen( $this->baseDir."/logs/".$file, "a+" );
        $xmlSent = simplexml_load_string($xmlSent);
        //Get and Add Column Headers
        $headers = array("Row","Contact ID");
        foreach ($xmlSent->row[0]->FL as $col) $headers[] = (string)$col['val'];
        $headers[] = "Time";
        $headers[] = "Code";
        $headers[] = "Errors";
        //Output headers (every 100 lines)
        fputcsv($idlog, $headers);
        
        //Loop over the rows of sent items
        foreach ($xmlSent->row as $row){
            //first 2 fields are the row number and contact id
            $IDfields = array($row['no'], (string)$xmlGot->result->row[((int)$row['no'] - 1)]->success->details->FL[0]);
            //loop over the fields that were uploaded and include them
            foreach ($row->FL as $col) {
                $IDfields[] = (string)$col;
    }
            //add the time, success code and errors (if any)
            $IDfields[] = date("Y-m-d H:i:s");
            $IDfields[] = (string)$xmlGot->result->row[((int)$row['no'] - 1)]->success->code;
            $IDfields[] = (string)$xmlGot->result->row[((int)$row['no'] - 1)]->error->details;
            //Save the log
            fputcsv($idlog, $IDfields);
        }
    }


    // inserts into SalesUpload
    public function add_sales_upload_record($salesName, $salesID, $name, $num, $AIType, $tradeshow, $source){
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            return $this->render( 'AIResponsiveBundle:salesLeads:genericError.html.twig',array("title" => "Failed to Connect to Database", "error" => $conn['msg']) );
        }else{ $db = $conn['connection']; }

        if($tradeshow==""){
            $leadSource = $source;
        }else{
            $leadSource = $tradeshow;
        }
        $sql = "INSERT INTO SalesUpload (name, zohoID, account, tradeshow, status, fileName, leadsUploaded) VALUES ('".$salesName."','". $salesID."','". $AIType."','". $leadSource."', 'waiting','".$name."',".$num.");";
        if ($db->query($sql) === TRUE) {
            return $db->insert_id;
        } else {
            return 0;
        }
        $db->close();

        return 0;
    }

    public function validate($allowedFields,$requiredFields,$path,$name){
        $errors = array();
        $outputFields = array();
        $rows = array();
        $row = 0;
        $numberOfColumns = 0;

        $resultCsv = fopen($path."/".$name."1", 'w+');

        // Replace Mac line-endings because who cares about standards, right Apple?
        $MacGarbage = file_get_contents($path."/".$name);
        if( strpos($MacGarbage, chr(13)) !== FALSE ){
            $MacGarbage = str_replace(chr(13), chr(10), $MacGarbage);
            file_put_contents($path."/".$name, $MacGarbage);
        }
        
        if (($handle = fopen($path."/".$name,"r")) !== FALSE) {

            //Find the delimiter using a 'best guess' based on counting occurances
            $file = file_get_contents($path."/".$name);
            $delimiter = ",";
            $delimiters = array(",",";","|"); //array of possible delimiters
            $delimCount = 0;
            foreach ($delimiters as $key => $val) {
                $count = substr_count($file, $val);
                if ( $count > $delimCount ){
                    $delimCount = $count;
                    $delimiter = $val;
                }
            }
            $fields = array();
            while(($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                if($row == 0) {
                    $i = 0;
                    foreach($data as $field) {
                        if(!in_array($field, $allowedFields)){
                            $output = "Unknown  '" . $field . "' field found （在文件里发现未知的列名：" . $field . " ） ";
                            $errors[] = $output;
                        }
                        $fields[$field] = $i;
                        $i++;
                    }
                    $numberofColumns = $i ;
                    if(in_array ("Contact Owner Id", $data) || in_array ("SMOWNERID", $data) || in_array ("Contact Owner", $data) && in_array ("Lead Owner", $data)  || in_array ("Lead Owner ID", $data)){
                        $output = "Found one of the following fields in your csv file : Contact Owner ID/TradeShows List/Lead Source, please delete those columns and try again! (在你的文件里面发现以下列：Contact Owner ID或者TradeShows List或者Lead Source，请把这些列删除之后再次上传！)";
                        $errors[] = $output;
                    }
                    foreach($requiredFields as $i => $rf) {
                        if (! in_array($rf, $data)) {
                            $errors[] = "Your CSV file is missing the required column '". $rf ."', please add it and try again! (您的CSV文件缺少必需的列". $rf ."，请添加并重试！)";
                            unset($requiredFields[$i]);
                        }
                    }
        
        
                    //Check for missing company name field
                    if(!in_array ("Company Name", $data) && !in_array ("Company", $data)){
                        $output = "Company Name not found";
                        $errors[] = $output;
                    }
                    $outputFields = $data;
                    fputcsv($resultCsv, $data);
                } else {
                    $numberOfEmptyColumns = 0;
                    foreach($data as $key => $field){
                        if($field=="") $numberOfEmptyColumns++;
                    }
                    if($numberOfEmptyColumns < $numberofColumns ){
                        if(isset($data[0])) { // For some reason there are some null values for data when we get here, need to find out why
                            fputcsv($resultCsv, $data);
                            $rows[] = $data;
                        }
                    }
                }
                $row++;
            }
        } else {
            $errors[] = "Failed to open file.";
        }

        fclose($handle);
        fclose($resultCsv);
        unlink($path."/".$name);
        rename($path."/".$name."1",$path."/".$name);
        $row = 1;
        foreach ($rows as $r) {
            foreach($requiredFields as $rf) {
                if ($r[$fields[$rf]] == "") {
                    $errors[] = "The mandatory field '". $rf ."' is empty on row ". $row .". (在你的文件里面发现某些行的". $rf ."没有数据，请补上然后再次上传！) ";
                    $FileOK = false;
                }
            }
            $row++;
        }
        

        if(count($errors) > 0){
            unlink($path."/".$name);
            return  $errors;
        }
        else return array("success"=>"success");

    }

    /**
     * @Route("/ChangeDeleteMemo")
     * @Method("POST")
     */
    public function ChangeDeleteMemoAction(){

        $method = $_POST['method']; //Change or Delete Tradeshow?
        $output = array("errors" => false, "err_msg" => "", "showName" => ""); //Default Error Settings
        $showid = $_POST['id']; //The ID of the show in the database
        $leads = array(); //an array to hold the information for the leads to be transferred
        $tradeshowName = ( isset($_POST['show']) ? $_POST['show'] : "" ); //The tradeshow code

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ){
            $output['errors'] = true;
            $output['err_msg'] = $conn['msg'];
            return new Response( json_encode($output) );
        }else{ $db = $conn['connection']; }

        //Error checking for Posted variables, return error on invalid input

        //Change the show and correct the leads count in the database
        if(strtolower($method) == "change"){

            $sql = "SELECT tradeshow, salesName, results FROM eventMemos WHERE id =".$showid;
            //Check for errors running the query and return an error if so
            if (!$result_allMemos = $db->query($sql)) {
                $output['errors'] = true;
                $output['err_msg'] = "Query Error: " . $db->error;
                $db->close();
                return new Response( json_encode($output) );
            }

            //Build an array of the number of leads attributed to each sales for this show
            $r = $result_allMemos->fetch_assoc();
            $salesCounts = json_decode($r['results'], true);
            if (! is_array($salesCounts)) $salesCounts = array();
            foreach($salesCounts as $val) $leads[$val['name']] = $val['num'];

            //Update the leads count in the database
            $sql = "SELECT id, name, tradeshow, memoLeads FROM leadsCount";
            //Check for errors running the query and return an error if so
            if (!$result_allLeads = $db->query($sql)) {
                $output['errors'] = true;
                $output['err_msg'] = "Query Error: " . $db->error;
                $db->close();
                return new Response( json_encode($output) );
            }
            
            //Loop through and fix counts for new tradeshow
            foreach($leads as $name => $val){
                $sql = "SELECT id, tradeshow, name FROM leadsCount WHERE tradeshow='".$tradeshowName."' AND name='".$name."'";
                //Check for errors running the query and return an error if so
                if (!$result_leads = $db->query($sql)) {
                    $output['errors'] = true;
                    $output['err_msg'] = "Query Error: " . $db->error;
                    $db->close();
                    return new Response( json_encode($output) );
                }
                //Check if we need to update or insert
                if($result_leads->num_rows == 0){
                    //Insert
                    $sql = "INSERT INTO leadsCount (name, tradeshow, memoLeads) VALUES ('".$name."','".$tradeshowName."',".(int)$val.")";
                }else{
                    //Update
                    $row = $result_leads->fetch_assoc();
                    $sql = "UPDATE leadsCount SET memoLeads = memoLeads + ".(int)$val." WHERE id=".$row['id'];
                }

                //Check for errors running the query and return an error if so
                if (!$result_insert_update_newleads = $db->query($sql)) {
                    $output['errors'] = true;
                    $output['err_msg'] = "Query Error: " . $db->error;
                    $db->close();
                    return new Response( json_encode($output) );
                }
            }

            //Loop through and fix counts for current tradeshow
            while($row = $result_allLeads->fetch_assoc()){
                //In the current tradeshow, subtract the leads from the show and delete the row if it's less than 0
                if( isset($leads[$row['name']]) && $row["tradeshow"] == $r['tradeshow'] ){
                    $newCount = (int)$row['memoLeads'] - (int)$leads[$row['name']];
                    if($newCount < 1){
                        //Delete The Row
                        $sql = "DELETE FROM leadsCount WHERE id=".$row['id'];
                        //Check for errors running the query and return an error if so
                        if (!$delreturn = $db->query($sql)) {
                            $output['errors'] = true;
                            $output['err_msg'] = "Query Error: " . $db->error;
                            $db->close();
                            return new Response( json_encode($output) );
                        }
                    }else{
                        //Update the row with the new count
                        $sql = "UPDATE leadsCount SET memoLeads='".$newCount."' WHERE id=".$row['id'];
                        //Check for errors running the query and return an error if so
                        if (!$upreturn = $db->query($sql)) {
                            $output['errors'] = true;
                            $output['err_msg'] = "Query Error: " . $db->error;
                            $db->close();
                            return new Response( json_encode($output) );
                        }
                    }
                }
            }

            //Change the show to the new show in the database
            $sql = "UPDATE eventMemos SET tradeshow='".$tradeshowName."' WHERE id=".$showid;
            //Check for errors running the query and return an error if so
            if (!$result_update_memo = $db->query($sql)) {
                $output['errors'] = true;
                $output['err_msg'] = "Query Error: " . $db->error;
                $db->close();
                return new Response( json_encode($output) );
            } else {
                //succeeded
                $output['showName'] = $tradeshowName;
            }


        }else if(strtolower($method) == "delete"){ //Deleting a tradeshow

            $sql = "SELECT tradeshow, salesName, results FROM eventMemos WHERE id =".$showid;
            //Check for errors running the query and return an error if so
            if (!$result_allMemos = $db->query($sql)) {
                $output['errors'] = true;
                $output['err_msg'] = "Query Error: " . $db->error;
                $db->close();
                return new Response( json_encode($output) );
            }
            
            //Build an array of the number of leads attributed to each sales for this show
            $r = $result_allMemos->fetch_assoc();
            $salesCounts = json_decode($r['results'], true);
            foreach($salesCounts as $val) $leads[$val['name']] = $val['num'];

            //Update the leads count in the database
            $sql = "SELECT id, name, tradeshow, memoLeads FROM leadsCount";
            //Check for errors running the query and return an error if so
            if (!$result_allLeads = $db->query($sql)) {
                $output['errors'] = true;
                $output['err_msg'] = "Query Error: " . $db->error;
                $db->close();
                return new Response( json_encode($output) );
            }

            //Loop through and fix counts for current tradeshow
            while($row = $result_allLeads->fetch_assoc()){
                //In the current tradeshow, subtract the leads from the show and delete the row if it's less than 0
                if( isset($leads[$row['name']]) && $row["tradeshow"] == $r['tradeshow'] ){
                    $newCount = (int)$row['memoLeads'] - (int)$leads[$row['name']];
                    if($newCount < 1){
                        //Delete The Row
                        $sql = "DELETE FROM leadsCount WHERE id=".$row['id'];
                        //Check for errors running the query and return an error if so
                        if (!$delreturn = $db->query($sql)) {
                            $output['errors'] = true;
                            $output['err_msg'] = "Query Error: " . $db->error;
                            $db->close();
                            return new Response( json_encode($output) );
                        }
                    }else{
                        //Update the row with the new count
                        $sql = "UPDATE leadsCount SET memoLeads='".$newCount."' WHERE id=".$row['id'];
                        //Check for errors running the query and return an error if so
                        if (!$upreturn = $db->query($sql)) {
                            $output['errors'] = true;
                            $output['err_msg'] = "Query Error: " . $db->error;
                            $db->close();
                            return new Response( json_encode($output) );
                        }
                    }
                }
            }

            //Change the show to the new show in the database
            $sql = "DELETE FROM eventMemos WHERE id=".$showid;
            
            //Check for errors running the query and return an error if so
            if (!$result_delete_memo = $db->query($sql)) {
                $output['errors'] = true;
                $output['err_msg'] = "Query Error: " . $db->error;
                $db->close();
                return new Response( json_encode($output) );
            }

        //End Delete
        }else{
            //Invalid Method Given
            $output['errors'] = true;
            $output['err_msg'] = "Invalid Method Parameter.";
            return new Response( json_encode($output) );
        }

        return new Response( json_encode($output) );
    } // End ChangeDeleteMemoAction


    /**
     * [zoho_GetDataForTradeshow description]
     * @param  [string] $show [tradeshow code]
     * @return [array]       [Data for email campaign from Zoho]
     */
    function zoho_GetDataForTradeshow($show){
        $params = array();
        $output = array("errors"=>false,"err_msg"=>"","data"=>"");
        $url = "https://crm.zoho.com/crm/private/xml/Contacts/searchRecords";
        $params['authtoken'] = "8096cae1936ed6849ec0a6de6cdc043f";
        $params['scope'] = "crmapi";
        $params['criteria'] = "(TradeShows List:".$show.")";
        $params['selectColumns'] = "(TradeShows List,Contact Owner,Email,Last Name,First Name)";
        $params['fromIndex'] = 1;
        $params['toIndex'] = 200;
        
        //Setting up CURL options
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
    
        $i = 5; // Maximum calls to make (to prevent runaway loops nailing our API Limit) [25 * 200 = 5000 records]
        $loop = true;
        $results = array();
    
        while($loop){
            $query = http_build_query($params);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            $returnedval = curl_exec($ch);
            $returnedval = simplexml_load_string($returnedval);
            $results[] = $returnedval;
            $i--;
            $params['fromIndex'] = $params['fromIndex'] + 200;
            $params['toIndex'] = $params['toIndex'] + 200;
    
            //Check if api limit was hit
            if(strrpos(($returnedval->result->message == "" ? $returnedval->error->message : $returnedval->result->message), "You crossed your license limit") !== FALSE){
                //API Limit Reached
                $output["errors"] = true;
                $output["err_msg"] = "Zoho API Limit Reached.";
                curl_close($ch);
                return $output;
            }
    
            //Kill the loop if we have all the data or the limit is hit
            if(isset($returnedval->nodata->code) || $i <= 0) $loop = false;
            
        }
    
        curl_close($ch); //Closing CURL
    
        foreach ($results as $xml) {
            if(isset($xml->result->Contacts)){
                foreach ($xml->result->Contacts->row as $Contact) {
                    $ContactData = array();
                    foreach ($Contact->FL as $Attribute) {
                        $t = (string)$Attribute['val'];
                        $ContactData[$t] = (string)$Attribute;
                    }
                    $output["data"][] = $ContactData;
                }
                
            }
        }
    
        return $output;
    }


    /**
     * [getDBconnection Get a connection to the database]
     * @return [array] [array with the connection (if successful), error result (bool), and error message]
     */
    public function getDBconnection(){
        try {
            $db = mysqli_connect("54.251.119.26","aidata","byNdf9W44T","Staging");
        } catch (\Exception $e) {
            //Catch Exceptions and send back the errors
            return array(
                "connection" => false,
                "error" => true,
                "msg" => $e->getMessage()
            );
        }

        if(!$db){
            //Catch DB errors and send back the errors
            return array(
                "connection" => false,
                "error" => true,
                "msg" => $db->connect_error
            );
        }else{
            //No problems, set error to false and send back the connection
            return array(
                "connection" => $db,
                "error" => false,
                "msg" => ""
            );
        }
    }

    /**
     * @Route("/leads/updateShowData")
     * @Method("POST")
     */
    public function updateShowData(){
        $id = $_POST['id'];
        $field = $_POST['field'];
        $value = $_POST['val'];
        if( !is_numeric($id) ) return new Response("false");
        if( !in_array( $field, array("tradeshow","friendlyName","Industry","Start_Date","End_Date","email_campaign_status","email_template","budget","event_type","organizer") ) ) return new Response("false");
        if( in_array( $value, array(";") ) ) return new Response("false");

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection();
        if( $conn['error'] ){ return new Response("false"); } else { $db = $conn['connection']; }
        
        $sql = 'UPDATE tradeshowList SET '.$field.'="'.$value.'" WHERE id='.$id;
        if (!$returnedval=$db->query($sql)) {
            $db->close();
            return new Response("false");
        }
        return new Response("true");
    }

    /**
     * @Route("/leads-tool/mass-update")
     * @Method("GET")
     * Mass Update Tool
     */
    public function massUpdateAction() {
        $session = $this->getSession();
        $twigData['uploadPermission'] = ( $session->get('uploadPermission') == 1 ? 1 : 0 );
        $twigData['adminPermission'] = ( $session->get('salesLeadsPermission') == 1 ? 1 : 0 );
        $twigData['error'] = "";
        return $this->render('AIResponsiveBundle:salesLeads:massUpdate.html.twig', $twigData);
    }

    /**
     * @Route("/leads-tool/mass-update")
     * @Method("POST")
     * Mass Update Tool
     */
    // Send to s3 instead
    public function massUpdatePostAction() {
        $session = $this->getSession();
        $twigData['uploadPermission'] = ( $session->get('uploadPermission') == 1 ? 1 : 0 );
        $twigData['adminPermission'] = ( $session->get('salesLeadsPermission') == 1 ? 1 : 0 );
        $twigData['error'] = "";

        if( $_POST['massUploadType'] == "none" ) $twigData['error'] .= "Please choose an update type.<br />";
        if( $_POST['massUploadModule'] == "none" ) $twigData['error'] .= "Please choose a module.<br />";

        if( $twigData['error'] == "" ) {
            $newFileName = "Tempfile_".date("Y-m-d[H-i-s]").".csv";
            $Global = $this->get('global_functions');
            $url = $Global->uploadToS3($this, $_FILES["massUploadFile"]['tmp_name'], "LeadsTool/massUpdateFiles/".$newFileName, 'public-read');
            $twigData['fileLoc'] = $url;
            $twigData['massUploadType'] = $_POST['massUploadType'];
            $twigData['massUploadModule'] = $_POST['massUploadModule'];
            $rows = file($url, FILE_SKIP_EMPTY_LINES);
            $twigData['rowCount'] = count($rows) - 1;
            $fp = fopen($url, "r");
            $twigData['fields'] = fgetcsv($fp);
        }
        return $this->render('AIResponsiveBundle:salesLeads:massUpdate.html.twig', $twigData);
    }

    /**
     * @Route("/leads-tool/processmassupdate")
     * @Method("POST")
     * Mass Update Tool
     */
    public function processMassUpdateAction() {
        $Global = $this->get('global_functions');
        set_time_limit(0);
        $results = "";
        $pkg = json_decode($_POST['data']);

        $method = "";
        if($pkg->type == "insert") $method = "insertRecords";
        if($pkg->type == "update") $method = "updateRecords";

        error_reporting(0);
        
        //Our API keys from Zoho [Don't Change]
        $apiAuth = "8096cae1936ed6849ec0a6de6cdc043f";
        
        $xml = "";
        //Setting up CURL options
        $ch = curl_init("https://crm.zoho.com/crm/private/xml/".$pkg->module."/".$method);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //The loop the does all the magic, it opens the CSV file
        if (($handle = fopen($pkg->file,"r")) !== FALSE) {
            $row = 0;   //row counter
            $xmlloop = 0;
            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //loop through each line of the CSV file
                //Setup XML Data
                if($xmlloop == 0){
                    $xml = "<?xml version='1.0' encoding='utf-8'?><".$pkg->module.">";   
                    $xmlloop++;
                } else if ($xmlloop == 99) {
                    $xml .= "</".$pkg->module.">";
                    $xmlloop = 0;
                    //Send the data to Zoho
                    $query = array('authtoken'=>$apiAuth,'scope'=>'crmapi', 'version'=>'4','xmlData'=>$xml);
                    $query = http_build_query($query);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    $results .= $xml."\n";
                    $results .= curl_exec($ch) . "\n";
                } else {
                    $xml .= "<row no='".$xmlloop."'>";
                    foreach($pkg->fields as $key => $val){
                        if( substr($data[$key],0,5) == "zcrm_" ) $data[$key] = substr($data[$key],5);
                        $xml .= "<FL val='".$val."'><![CDATA[". $data[$key]. "]]></FL>";    
                    }
                    $xml .= "</row>";
                    $xmlloop++;
                }
                $row++;
            }
            
        } else { return new Response((string)$handle); }
        
        if ($xmlloop < 99){
            $xml .= "</".$pkg->module.">";
            //Send the data to Zoho
            $query = array('authtoken'=>$apiAuth,'scope'=>'crmapi', 'version'=>'4','xmlData'=>$xml);
            $query = http_build_query($query);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            $results .= $xml."\n";
            $results .= curl_exec($ch) . "\n";
        }

        //We reached the end of the file
        fclose($handle); // Close the file when complete 
        curl_close($ch); // Closing CURL
        $Global->deleteFromS3($this, substr($pkg->file, 29)); // Delete the file (substr cuts the https://s3.asiainspection.com/ off the front)
        
        return new Response($results);
    }

    /**
     * @Route("/leads-tool/affiliate-management")
     * @Template()
     * This page is for administrators to approve affiliates
     */
    public function affiliatesAction() {
        $twigData = array();
        //Try to get a connection to the database and redirect to error page on failure
        $conn = $this->getDBconnection("Data");
        if( !$conn['error'] ) {
            $db = $conn['connection'];
            $sql = "SELECT * FROM affiliates";
            if ($returnedval = $db->query($sql)) {
                $db->close();
                while($r = $returnedval->fetch_assoc()) {
                    $twigData['affiliates'][] = $r;
                }
            }
        }
        return $this->render('AIResponsiveBundle:salesLeads:affiliateManagement.html.twig', $twigData);
    }

    /**
     * @Route("/leads-tool/change-affiliate-status")
     * @Method("POST")
     */
    public function changeAffiliateStatus() {
        $id = $_POST['id'];
        $action = $_POST['action'];
        $sql = "";

        if( ($action != "approve" && $action != "reject") || !is_numeric($id) ) return new Response("false");

        $conn = $this->getDBconnection("Data");
        if( $conn['error'] ) {
            die("Connection failed: " . $conn['connection']->connect_error);
        } else { $db = $conn['connection']; }

        $x = $db->query("SELECT MAX(affid) FROM affiliates");
        $row = $x->fetch_array(MYSQLI_NUM);
        $nextAffId = $row[0] + 1;

        if($action == "approve") $sql = "UPDATE affiliates SET status='Approved', affid=".$nextAffId." WHERE id=".$id;
        if($action == "reject") $sql = "UPDATE affiliates SET status='Rejected' WHERE id=".$id;
        $result = $db->query($sql);
        if($result && $action == "approve") $this->sendAffiliateEmail($id);

        $db->close();
        return new Response("true");
    }

    public function sendAffiliateEmail($id) {
        $conn = $this->getDBconnection("Data");
        if( $conn['error'] ) {
            die("Connection failed: " . $conn['connection']->connect_error);
        } else { $db = $conn['connection']; }

        $result = $db->query("SELECT id, affid, affiliatename, email FROM affiliates WHERE id=".$id);
        $r = $result->fetch_assoc();
        $email = $r['email'];

        // In Dev Environment, just send it to this email
        if (!$this->is_prod_server()) $email = "vincent.macdonald@asiainspection.com";
        
        $message = \Swift_Message::newInstance()
        ->setSubject("Your AsiaInspection Affiliate Program Application Has Been Accepted!")
        ->setFrom('affiliate@asiainspection.com')
        ->setTo($email)
        ->addBcc("michael.mesarch@asiainspection.com")
        ->addBcc("vincent.macdonald@asiainspection.com")
        ->setBody($this->renderView('AIResponsiveBundle:salesLeads:emailAffiliateApprove.html.twig', array("name"=>$r['affiliatename'], "email"=>$r['email'], "id"=>$r['affid']) ), 'text/html');

        $mailer = $this->get('mailer');
        $mailer->send($message);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);
    }

    /**
     * [queue_companies_to_add_into_zoho description]
     * Check the CSV file for any new companies that may need to be added to zoho and add them to the list
     * queues companies to add into zoho
     * @param  [string] $file [the path to the CSV file to check]
     * @return [none]
     */
    public function queue_companies_to_add_into_zoho($file, $sales_upload_id = 0) {
        // Load the companies list into one massive array and trim the whitespace and convert to lowercase
        $companies = array(); 
        $fp = fopen($this->baseDir . "/Company_Names.csv", "r");
        while( $row = fgetcsv($fp) ) $companies[] = trim(strtolower($row[0]));
        fclose($fp);


        $file = fopen($file, "r");
    

        // Creating CSV of companies to be created and an array to keep track of what we have already added to avoid duplicates
        $createdCompanies = array();

        //Try to get a connection to the database and continue if it works
        $conn = $this->getDBconnection();
        if( !$conn['error'] ) {
            $db = $conn['connection'];


            // Load the companies already tagged in the database for creation and add them to the list so we don't make duplicates
            $sql = "SELECT companyName FROM zoho_CompaniesToAdd";
            $returnedval = $db->query($sql);
            // MYSQL_NUM
            while($row = $returnedval->fetch_array(MYSQLI_NUM)) $createdCompanies[] = strtolower(trim($row[0]));


            // Loop through each row in the CSV file to check each company against the master list
            $row = 0;
            while( ($data = fgetcsv($file)) !== FALSE ) {
                if( $row == 0 ){
                    // Find out what index in the CSV has the account name and zoho id of the owner
                    $accountKey = array_search ('Account Name', $data);

                    // zoho id column
                    $ownerKey = array_search ('SMOWNERID', $data);

                } else {
                    // Search for the company in the master list of companies in zoho
                    $val = array_search (strtolower(trim($data[$accountKey])), $companies);
                    if( $val === FALSE ) { // If the company doesn't exist in zoho...


                        // Check if we have already created this company
                        $x = array_search (strtolower(trim($data[$accountKey])), $createdCompanies);
                        if($x === FALSE && isset($data[$ownerKey]) && $data[$ownerKey] != ""){ // If we haven't created it and we have an id to attribute it to...

                            // Add this to the list of created companies and add it to the list to upload in zoho
                            // we want to set proper company owner (this is zohoID)
                            $createdCompanies[] = strtolower(trim($data[$accountKey]));
                            $sql = "INSERT INTO zoho_CompaniesToAdd (companyName,companyOwner, salesUploadID) VALUES ('".$db->real_escape_string($data[$accountKey])."','".$db->real_escape_string($data[$ownerKey])."', '".$db->real_escape_string($sales_upload_id)."')";
                            $returnedval = $db->query($sql);
                        }
                    }
                }
                $row++;
            }
            $db->close();
        }
    } // End of queue_companies_to_add_into_zoho method

    /**
     * creates companies in zoho
     */
    private function create_companies_in_zoho($sales_upload_id = 0) {

        // if not production then skip connecting to zoho
        if (!$this->is_prod_server()) {
            return true;
        }

        //Our API keys from Zoho [Don't Change]
        $apiAuth = "8096cae1936ed6849ec0a6de6cdc043f";
        $url = "https://crm.zoho.com/crm/private/xml/Accounts/insertRecords";
        $xml = "";

    
        //Setting up CURL options
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
    
        //Try to get a connection to the database and continue if it works
        $conn = $this->getDBconnection();
        if( !$conn['error'] ) {
            $db = $conn['connection'];



            $sql_where = "";
            // grab all companies from table to add to zoho
            if ($sales_upload_id > 0) {
                $sql_where .= "where salesUploadID = '".$sales_upload_id."'";
            }

            $sql = "SELECT * FROM zoho_CompaniesToAdd $sql_where";
            $returnedval = $db->query($sql);


            $row = 0;   // Row counter
            $xmlloop = 0; // Row ID in xml loop
            while($row = $returnedval->fetch_assoc()) {
                //Setup XML Data
                if($xmlloop == 0){
                    $xml = "<?xml version='1.0' encoding='utf-8'?><Accounts>";
                    $xml .= "<row no='".$xmlloop."'>";
                    $xml .= "<FL val='Account Name'><![CDATA[". $row['companyName']. "]]></FL>";
                    $xml .= "<FL val='SMOWNERID'><![CDATA[". $row['companyOwner']. "]]></FL>";
                    $xml .= "</row>";
                    $xmlloop++;
                }else if ($xmlloop == 99){
                    $xml .= "</".$module.">";
                    $xmlloop = 0;
                    $query = array('authtoken'=>$apiAuth,'scope'=>'crmapi', 'version'=>'4','xmlData'=>$xml);
                    $query = http_build_query($query);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_exec($ch);
                }else{
                    $xml .= "<row no='".$xmlloop."'>";
                    $xml .= "<FL val='Account Name'><![CDATA[". $row['companyName']. "]]></FL>";
                    $xml .= "<FL val='SMOWNERID'><![CDATA[". $row['companyOwner']. "]]></FL>";
                    $xml .= "</row>";
                    $xmlloop++;
                }
                $row++; 
            }


            //We reached the end of the list of companies to add
            if($xmlloop !== 99){
                $xml .= "</Accounts>";
                $query = array('authtoken'=>$apiAuth,'scope'=>'crmapi', 'version'=>'4','xmlData'=>$xml);
                $query = http_build_query($query);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                curl_setopt($ch, CURLOPT_ENCODING, "");
                curl_exec($ch);
            }
            curl_close($ch); //Closing CURL

            // we want to delete only 
            if ($sales_upload_id == 0) { 
                $sql = "TRUNCATE TABLE zoho_CompaniesToAdd";
                $returnedval = $db->query($sql);
            } else {
                // only delete with sales_upload_id
    
                $sql_where = "";
                if ($sales_upload_id > 0) {
                    $sql_where .= "where salesUploadID = '".$sales_upload_id."'";
                }

                $sql = "delete FROM zoho_CompaniesToAdd $sql_where";
                $returnedval = $db->query($sql);
            }
            return true;
        }
        return false;
    }

} //End SalesLeadsController

function getd(&$value, $default = '') {
    return isset($value) ? $value : $default;
}