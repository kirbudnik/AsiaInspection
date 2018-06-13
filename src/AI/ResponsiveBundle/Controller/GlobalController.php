<?php
namespace AI\ResponsiveBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AI\ResponsiveBundle\Model\Data;
use Seferov\AwsBundle;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpKernel\Config\FileLocator;

class GlobalController extends Controller
{

    public function removeTrailingSlashAction(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();
        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
        return $this->redirect($url, 301);
    }

    // Getting Data for the Need More Information Form
    public function NeedMoreInfoBox($container, $request)
    {
        $countries = $container->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/countries.xml');
        $output['countries'] = simplexml_load_file($countries);
        $nmibox = $container->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/contact/NeedMoreInfoBox.xml');
        $nmi = simplexml_load_file($nmibox);
        $locale = $request->getLocale();
        $output['data'] = $nmi->$locale;
         
        return $output;
    } // End of NeedMoreInfoBox

    //method for determining the Users Country via IP.
    public function checkUserCountry($type="code-only")
    {
        $ip = $this->getRealIpAddr();
        $url = "http://freegeoip.net/json/".$ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $userCountry = trim(curl_exec($curl));
        curl_close($curl);
        $userCountry = json_decode($userCountry, true);
        if($type == "code-only") return $userCountry['country_code'];
        if($type == "full") return $userCountry;
    } // End of checkUserCountry

    // This a method to grab an email
    public function saveContentDownload($email, $category, $content, $request="", $extra="") {
        // Categories: Registration, Inquiry, Sample Report, Whitepaper, Newsletter, 8 Tips
        $referer = ( $request == "" ? "" : $request->cookies->get("http_referer") );
        $data = new Data();
        $conn = $data->getDBconnection();
        if( !$conn['error'] ) {
            $db = $conn['connection'];
            mysqli_set_charset($db, "utf8");
            $paramsCookie = ( isset($_COOKIE['paramsCookie']) ? $_COOKIE['paramsCookie'] : "" );
            //Save Content Downloads in Seperate Table
            if( $category == "Sample Report" || $category == "Whitepaper" || $category == "8 Tips" ){
                $sql = "INSERT INTO contentDownloads (email, category, content, Url_Params, URL) VALUES ('".$email."', '".$category."', '".$content."', '".$paramsCookie."', '".$referer."')";
                $db->query($sql);
            }
            // Save All Emails in Emails Table
            $sql = "INSERT INTO Emails (email, category, content, Url_Params) VALUES ('".$email."', '".$category."', '".$content."', '".$paramsCookie."')";
            $db->query($sql);
        }

        //Save the Cookie
        setcookie("email", $email, strtotime("+1 year"), "/");

        //Save to Oracle
        $urlParams = $_COOKIE["paramsCookie"];
        $params = array("xtor" => "", "sitecatalyst" => "", "language" => "", "campaign" => "", "xtorFtp" => "", "xtorLtp" => "");
        $result = json_decode($urlParams, true);
        if( is_array($result) ){
            $result = array_change_key_case ( $result, CASE_LOWER );
            if( isset($result["xtor"]) ) $params["xtor"] = $result["xtor"];
            if( isset($result["sc"]) ) $params["sitecatalyst"] = $result["sc"];
            if( isset($result["lang"]) ) $params["language"] = $result["lang"];
            if( isset($result["campaign"]) ) $params["campaign"] = $result["campaign"];
            if( isset($result["xtor ftp"]) ) $params["xtorFtp"] = $result["xtor ftp"];
            if( isset($result["xtor ltp"]) ) $params["xtorLtp"] = $result["xtor ltp"];
        }

        $datatoSend = array(
            "email" => $email,
            "category" => $category,
            "content" => $content,
            "downloadDate" => date("d-M-Y g:i:s"),
            "XTOR" => $params["xtor"],
            "sitecatalyst" => $params["sitecatalyst"],
            "language" => $params["language"],
            "campaign" => $params["campaign"],
            "xtorFtp" => $params["xtorFtp"],
            "xtorLtp" => $params["xtorLtp"],
            "urlParams" => $urlParams,
            "referer" => $referer,
            "ip" => $this->getRealIpAddr()
        );

        if($extra != "") {
            foreach ($extra as $key => $val) { $datatoSend[$key] = $val; }
        }
        $result = $data->CallRest("content-download", "dev", "postform", $datatoSend);

        if( is_array($result) && isset($result['error']) ) {
            // Error saving to database
        }

    } // End of saveContentDownload

    /**
     * @Route("/getEmail")
     * @Method("POST")
     */
    // This is for Ajax calls to grab an email
    public function getEmailAction(Request $request) {
        $target = $_POST['target'];
        $category = $_POST['category'];
        $email = $_POST['email'];
        $countryData = $this->checkUserCountry("full");
        $contentDownloadExtra = array(
            "domain" => $request->getHost(),
            "country" => $countryData['country_name']
        );
        $this->saveContentDownload($email, $category, $target, $request, $contentDownloadExtra);
        return new Response("success");
    } // End of getEmailAction

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
     * [uploadToS3 - Upload a file to Amazon S3]
     * @param  [string] $file   [where is the file to be uploaded]
     * @param  [string] $s3path [where should it go in the S3 file structure]
     * @return [string]           [url to file or null on fail]
     */
    public function uploadToS3($container, $file, $s3path, $acl = 'public-read') {
        // http://docs.aws.amazon.com/aws-sdk-php/v2/guide/service-s3.html
        // http://docs.aws.amazon.com/aws-sdk-php/v2/guide/index.html
        $s3 = $container->get('aws.s3');
        // Upload an object to Amazon S3
        $result = $s3->putObject(array(
            'Bucket' => 's3.asiainspection.com',
            'Key'    => $s3path,
            'SourceFile'   => $file,
            'ACL'        => $acl
        ));
        if( !isset($result['ObjectURL']) || $result['ObjectURL'] == "" ){
            return "";
        } else {
            $url = str_replace("https://s3.amazonaws.com/", "http://", $result['ObjectURL']);
            return $url;
        }
    } // End uploadToS3 function

    /**
     * [deleteFromS3 - Upload a file to Amazon S3]
     * @param  [string] $s3path [file path S3 file structure]
     */
    public function deleteFromS3($container, $s3path) {
        $s3 = $container->get('aws.s3');
        // Delete an object from Amazon S3
        $result = $s3->deleteObject(array(
            'Bucket' => 's3.asiainspection.com',
            'Key'    => $s3path
        ));
        return true;
    } // End deleteFromS3 function

    public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function sendMarketoAlert($campaign, $myvars, $email) {
        $token = $this->getMarketoToken();
        $leadid = $this->marketoGetLeadFromEmail($email, $token);
        if($leadid) {
            
            $tokenVars = array();
            foreach ($myvars as $k => $v) $tokenVars[] = array("name" => $k, "value" => $v);
        
            $query = array("input" => 
                array(
                    "leads" => array(array("id" => $leadid)),
                    "tokens" => $tokenVars
                )
            );
            $query = json_encode($query);
        
            $ch = curl_init("https://944-QDO-384.mktorest.com/rest/v1/campaigns/".$campaign."/trigger.json?access_token=".$token."");
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HEADER, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json'                                                                   
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            $returnedval = curl_exec( $ch );
            curl_close($ch);
            $r = json_decode($returnedval);
            if( isset($r->success) && $r->success == true ) {
                return true;
            } else {
                return false;
            }
        }
        return false; // if lead not found in marketo
    } // End 'sendMarketoAlert'

    public function getMarketoToken() {
        $ch = curl_init("https://944-QDO-384.mktorest.com/identity/oauth/token?grant_type=client_credentials&client_id=4743d1f5-c71d-44e3-a2f4-be38a4841979&client_secret=SEZRDLVADZybdlj9ujgBknSC2H2OH3FE");
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $returnedval = curl_exec( $ch );
        curl_close($ch);
        $r = json_decode($returnedval);
        if( isset($r->success) && $r->success == false ) return false;
        return  $r->access_token;
    } // End 'getMarketoToken'

    public function marketoGetLeadFromEmail($email, $token = null) {
        if($token == null) $token = $this->getMarketoToken();
        $query = array(
            "access_token" => $token,
            "filterType" => "email",
            "filterValues" => $email,
            "fields " => "id"
        );
        $query = http_build_query($query);

        $ch = curl_init("https://944-QDO-384.mktorest.com/rest/v1/leads.json?" . $query);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json'                                                                   
        ));
        $returnedval = curl_exec( $ch );
        curl_close($ch);
        $r = json_decode($returnedval);
        if( isset($r->success) && $r->success == true ) {
            $results = $r->result;
            return $results[0]->id;
        } else {
            return false;
        }
    } // End 'marketoGetLeadFromEmail'

    public function serializeDatabaseTable($sql) {
        $data = new Data();
        $conn = $data->getDBconnection();
        if( !$conn['error'] ) {
            $db = $conn['connection'];
            mysqli_set_charset($db, "utf8");
            $result = $db->query($sql);

            $encoders = array(new XmlEncoder());
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $xml = $serializer->serialize($result, 'xml');

        }
        return $xml ? $xml : null; 
    }

    /**
     * @Route("/saveScreen")
     * @Method("POST")
     */
    public function saveScreenAction() {
        $base = "/var/SalesLeads/uploadScreens/";
        $img = $_POST['img'];
        $name = $_POST['name'];
        $filename = date('Y-m-d_H-i-s')."[".$name."].jpg";
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $imageData = base64_decode($img);

        file_put_contents($base.$filename, $imageData);
        return new Response("success");
    } // End of 'saveScreenAction'


    public function latestContentAction($type, $container) {
        $r = array("thumb" => "", "title" => "", "link" => "");
        // TODO: images need to be automated

        // Barometer
        if($type == "baro") {
            $r["thumb"] = "https://s3.asiainspection.com/images/content-cards/barometer_thumb.png";
            $r["link"] = "/barometer";

            $newsAnnouncements = $container->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/en_Announce.xml');
            $x = simplexml_load_file($newsAnnouncements);
            $quarter = 0; // Initialize variable
            $year = date("Y");
            $n = date('n'); // Get the current month (numeric)
            if ($n < 4) { $quarter = 1; } elseif ($n > 3 && $n <7) { $quarter = 2; } elseif ($n >6 && $n < 10) { $quarter = 3; } elseif ($n >9) { $quarter = 4; } // Get the current quarter
            $curBarometer = $year."-q".$quarter; // The current barometer id

            $barometer = false;
            foreach ($x->post as $post) {
                if (0 === @strpos($post->link->attributes()->url, $curBarometer)) $barometer = $post;
            }
            if(!$barometer) {
                // use the prev baromoter
                if ($quarter == 1) {
                    $quarter = 4;
                    $year = $year - 1;
                } else {
                    $quarter = $quarter - 1;
                }
                $curBarometer = $year."-q".$quarter; // The path to the current barometer
                foreach ($x->post as $post) {
                    if (0 === @strpos($post->link->attributes()->url, $curBarometer)) $barometer = $post;
                }
            }
            $r["title"] = $barometer->title;

            // $h = get_headers("https://s3.asiainspection.com/images/content-cards/barometer_thumb.png", 1);
            // $lastModTime = strtotime($h['Last-Modified']);

            // Generate Thumbnail
            $img = "https://s3.asiainspection.com/images/news/".$barometer->image;
            $dest = imagecreatefrompng("https://s3.asiainspection.com/images/content-cards/template_barometer_thumb.png");
            $src = imagecreatefromjpeg($img);
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            list($width, $height) = getimagesize($img);
            imagecopyresampled($dest, $src, 0, 12, 0, 0, 225, 180, $width, $height);
            ob_start();
            imagepng($dest, null, 0);
            $contents =  ob_get_contents();
            ob_end_clean();
            imagedestroy($dest);
            imagedestroy($src);
            $r["thumb"] = "data:image/png;base64,".base64_encode($contents);

        }

        // Whitepaper
        if($type == "insight") {
            $insights = $container->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/aboutUs/white-paper.xml');
            $x = simplexml_load_file($insights);
            $insight = $x->en->whitePaper->item[0];

            $r["title"] = (string)$insight->title;
            $r["link"] = ( $insight->type == "page" ? (string)$insight->link : "/whitepaper/".(string)$insight->id );

            // Generate Thumbnail
            $img = "https://s3.asiainspection.com/images/".$insight->image;
            $dest = imagecreatefrompng("https://s3.asiainspection.com/images/content-cards/template_whitepaper_thumb.png");
            $src = imagecreatefromjpeg($img);
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            list($width, $height) = getimagesize($img);
            imagecopyresampled($dest, $src, 10, 10, 0, 0, 130, 180, $width, $height);
            ob_start();
            imagepng($dest, null, 0);
            $contents =  ob_get_contents();
            ob_end_clean();
            imagedestroy($dest);
            imagedestroy($src);
            $r["thumb"] = "data:image/png;base64,".base64_encode($contents);
        }

        // RegRecap
        if($type == "recap") {
            $r["thumb"] = "https://s3.asiainspection.com/images/content-cards/regulatory_recap_thumb.png";

            $path = $container->get('kernel')->locateResource('@AIResponsiveBundle/Resources/views/news/regulatoryupdates/en');
            $regUpdates = scandir($path);
            $data = array();
            //Foreach file, get the first line and json decode it to get the title and description
            foreach($regUpdates as $file) {
                if( strpos($file, ".html") > -1 ){
                    $line = fgets(fopen($path."/".$file, 'r'));
                    $start = strpos($line,"{");
                    $end = strrpos($line,"}");
                    $line = substr($line, $start, (($end - $start)+1));
                    $line = json_decode($line);
                    $line->id = substr($file, 0, strrpos($file,".html"));
                    $data[] = $line;
                }
            }

            //Sorting the posts by date
            usort($data, function($a, $b){
                if ($a->PublishDate == $b->PublishDate) {
                    return 0;
                }
                return ($a->PublishDate < $b->PublishDate) ? 1 : -1;
            });
            // Set the data to return
            $r["title"] = (string)$data[0]->Title;
            $r["link"] = "/regulatory-updates/".(string)$data[0]->id;
        }

        return $r;
    } // End of 'latestContentAction'

    /**
     * @Route("/twilioVerify")
     * @Method("POST")
     */
    public function twilioVerifyAction(Request $request) {
        $number = $_POST['number'];
        $x = $this->twilioVerifyPhoneNumber($number);
        if(!$x) $x = "false";
        return new Response($x);
    }

    // Verify a phone number using Twilio and return either the properly formatted number or false
    public function twilioVerifyPhoneNumber($number) {
        $encoded = rawurlencode($number);
        $ch = curl_init();
        $url = "https://lookups.twilio.com/v1/PhoneNumbers/".$encoded."?Type=carrier&Type=caller-name";
        $headers = array('Content-Type: application/json; charset=UTF-8');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, "AC586386c72c4ba66978d416e2e169bb09:6d3936c796ca2950cd700c94fb5bd375");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST, 0);
        $returnedval = curl_exec($ch);
        curl_close($ch);
        $r = json_decode($returnedval);
        if( isset($r->code) ) {
            return false;
        } else {
            return $r->phone_number;
        }
    }

}
