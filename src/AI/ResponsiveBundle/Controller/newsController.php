<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class newsController extends Controller {

    /**
    * @Route("/mobile-news")
    * @Template()
    */
    public function mobileNewsAction(Request $request) {
        $this->checkLocale();
        $posts = array();

        // Collecting announcements
        // (XML elements: title, datetime, type, image, blurb, link)
        $locale=$request->getLocale();
        try{
            if($locale=="zh") {
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/cn_Announce.xml');
            } else if($locale=="ar") {
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/ar_Announce.xml');
            } else {
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/'.$locale.'_Announce.xml');
            }
        }catch(\InvalidArgumentException $e){
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/en_Announce.xml');
        }
        $news=$this->getXML($path);
        foreach($news->post as $newsPost) {
            // temporarily reformat date for sortability
            $date = \DateTime::createFromFormat('M d, Y', (string) $newsPost->datetime);
            if (! $date) {
                $date = \DateTime::createFromFormat('M d Y', (string) $newsPost->datetime);
                if (! $date) {
                    $date = \DateTime::createFromFormat('d M, Y', (string) $newsPost->datetime);
                    if (! $date) {
                        $date = \DateTime::createFromFormat('d M Y', (string) $newsPost->datetime);
                    }
                }
            }
            // we have a couple of malformed dates in 2011, 2010, and existing news pages only go back to 2013
            if ($date->format('Y') < "2012") break;
            $url = (string) $newsPost->link['url'];
            if (substr($url, 0, 4) != "http" && substr($url, 0, 1) != "/") $url = "/mobile-asia-inspection-news/".$url;
            $posts[] = (object) [
                'type' => 'general-news',
                'title' => (string) $newsPost->title,
                'datetime' => $date->format('Y-m-d'),
                'blurb' => (string) $newsPost->blurb,
                'image' => (string) $newsPost->image,
                'link' => (string) $newsPost->link,
                'url' => $url
            ];
        }

        // Collecting regulatory updates and standardizing format/structure to match announcements
        // (JSON Properties: Title, PublishDate, Description)
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/views/news/regulatoryupdates/'.$locale);
        $regUpdates = scandir($path);
        //Foreach file, get the first line and json decode it to get the title and description
        foreach($regUpdates as $file) {
            if( strpos($file, ".html") > -1 ) {
                $line = fgets(fopen($path."/".$file, 'r'));
                $start = strpos($line,"{");
                $end = strrpos($line,"}");
                $line = substr($line, $start, (($end - $start)+1));
                $regUpdate = json_decode($line);
                $posts[] = (object) [
                    'type' => 'reg-update',
                    'title' => $regUpdate->Title,
                    'datetime' => $regUpdate->PublishDate,
                    'blurb' => $regUpdate->Description,
                    'image' => '',
                    'link' => 'Read the full article',
                    'url' => "/mobile-regulatory-updates/".substr($file, 0, strrpos($file,".html"))
                ];
            }
        }

        // Sorting the posts by date
        usort($posts, function($a, $b){
            if ($a->datetime == $b->datetime)
            return 0;
            else
            return ($a->datetime < $b->datetime) ? 1 : -1;
        });

        // Shortening blurbs and reformatting dates as more human-friendly
        foreach($posts as $post) {
            $date = \DateTime::createFromFormat('Y-m-d', $post->datetime);
            $post->datetime = $date->format('M d, Y');
            if (strlen($post->blurb) > 310) {
                $blurb = substr($post->blurb, 0, 310);
                $i = strrpos($blurb, " ");
                $post->blurb = substr($blurb, 0, $i) . "...";
            }
        }

        // Pass to twig renderer
        $twigData = array();
        $twigData['posts'] = $posts;
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('mobile-news', $locale);
        //include Tracking data [End]
        if($locale=="ar"){
            return $this->render('AIResponsiveBundle:RTL:news:mobileNews.html.twig', $twigData);
        }
        else{
            return $this->render('AIResponsiveBundle:news:mobileNews.html.twig', $twigData);
        }
    }


    /**
    * @Route("/mobile-asia-inspection-news/{id}")
    * @Method("GET")
    * @Template()
    */
    public function viewNewsMobileAction($id,Request $request) {
        $this->checkLocale();
        $locale=$request->getLocale();
        if (stripos($id,'-') == 4) {
            $id=strtoupper(str_replace('-','',substr($id, 0,7)));
        }
        try{
            if($locale=="zh"){
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/cn/'.$id.'.php');
            }
            else
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/'.$locale.'/'.$id.'.php');
        }catch(\InvalidArgumentException $e){
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/en/'.$id.'.php');
        }
        $twigData = array();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $twigData['tracking'] = $tracking->getTrackingCode('mobile-press-release', $locale, $id);
        //include Tracking data [End]
        $twigData['path'] = $path;

        try {
            $path3 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/'.$locale.'/meta.xml');
            foreach($this->getXML($path3)->meta as $pageData){
                if(strtolower($pageData['page']) == strtolower($id)) $twigData['pageTitle'] = $pageData->title;
            }
        }catch(\Exception $e){}

        if(preg_match('/[A-Z]+[0-9]+/', $id)&& ($id!="2013Q3-MEA")){
            $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/pageHeader.xml');
            if($this->getXML($path2)->pageTitle->$locale!="")
            $pageHeader = $this->getXML($path2)->pageTitle->$locale;
            else
            $pageHeader = $this->getXML($path2)->pageTitle->en;
            $qauter = substr($id, -1);
            $pageHeader= str_replace('*',$qauter,$pageHeader);
            $pageHeader= str_replace('#',substr($id, 0,4),$pageHeader);
            $twigData['headerTitle']=$pageHeader;
        }
        $twigData['type'] = 'general-news';
        return $this->render('AIResponsiveBundle:news:mobileStory.html.twig',$twigData);
    }


    /**
    * @Route("/asia-inspection-news", name ="newsAndContent_AsiaInspection News")
    * @Template()
    */
    public function indexAction(Request $request) {
        $this->checkLocale();
        $locale=$request->getLocale();
        try{
            if($locale=="zh"){
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/cn_Announce.xml');
                $path2= $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/cn_News.xml');
            }
            elseif($locale=="ar"){
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/ar_Announce.xml');
                $path2= $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/ar_News.xml');
            }
            else{
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/'.$locale.'_Announce.xml');
                $path2= $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/'.$locale.'_News.xml');
            }
        }catch(\InvalidArgumentException $e){
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/en_Announce.xml');
            $path2= $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/en_News.xml');
        }

        $news=$this->getXML($path);
        $news2=$this->getXML($path2);


        $tradeshows=$this->getComingEvents("en");

        $url = array();
        foreach($news->post as $post) {
            if(strlen($post->blurb)>310){
                $blurb = substr($post->blurb,0,310);
                $i=strrpos ( $blurb," ");
                $post->blurb = substr($blurb,0,$i)."...";
            }
            $url[] = (String) $post->link['url'];
        }

        $url2 = array();
        foreach($news2->post as $post) {
            $url2[] = (String) $post->link['url'];
        }

        $tsLinks = array();
        foreach ($tradeshows as $tradeshow) {
            if(isset($tradeshow->link['url'])) $tsLinks[] = $tradeshow->link['url'];
        }
        $twigData = array();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('news', $locale);
        //include Tracking data [End]
        $twigData['news'] = $news;
        $twigData['url'] = $url;
        $twigData['tradeshows'] = $tradeshows;
        $twigData['tsLinks'] = $tsLinks;
        $twigData['news2'] = $news2;
        $twigData['url2'] = $url2;
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:news/index.html.twig', $twigData);
        } else {
            return $this->render('AIResponsiveBundle:news:index.html.twig', $twigData);
        }
    }

    /**
    * @Route("/ethical-webinar")
    * @Template()
    */
    public function ethicalWebinarAction(Request $request) {
        return $this->render('AIResponsiveBundle:news:ethical-webinar.html.twig');
    }


    /**
    * @Route("/asia-inspection-news/{id}", name="asia-inspection-news")
    * @Method("GET")
    * @Template()
    */
    public function viewNewsAction($id, Request $request) {
        $this->checkLocale();
        $locale=$request->getLocale();
        if (stripos($id,'-') == 4) {
            $id=strtoupper(str_replace('-','',substr($id, 0,7)));
        }
        try{
            if($locale=="zh"){
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/cn/'.$id.'.php');
            }
            elseif($locale=="ar"){
                $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/ar/'.$id.'.php');
            }
            else
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/'.$locale.'/'.$id.'.php');
        }catch(\InvalidArgumentException $e){
            $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/en/'.$id.'.php');
        }
        $twigData = array();
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $twigData['tracking'] = $tracking->getTrackingCode('press-release', $locale, $id);
        //include Tracking data [End]
        $twigData['path'] = $path;

        try {
            $path3 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/pressrelease/'.$locale.'/meta.xml');
            foreach($this->getXML($path3)->meta as $pageData){
                if(strtolower($pageData['page']) == strtolower($id)){
                    /* Added the missing meta data: description & keywords,
                    * existing loop is inefficient and will lead to slowdown: needs rewrite.
                    */
                    $twigData['pageTitle'] = $pageData->title;
                    $twigData['pageDesc'] = $pageData->desc;
                    $twigData['pageKeys'] = $pageData->key;
                }
            }
        }catch(\Exception $e){}

        if(preg_match('/[A-Z]+[0-9]+/', $id)&& ($id!="2013Q3-MEA")){
            $path2 = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/pageHeader.xml');
            if($this->getXML($path2)->pageTitle->$locale!="")
            $pageHeader = $this->getXML($path2)->pageTitle->$locale;
            else
            $pageHeader = $this->getXML($path2)->pageTitle->en;
            $qauter = substr($id, -1);
            $pageHeader= str_replace('*',$qauter,$pageHeader);
            $pageHeader= str_replace('#',substr($id, 0,4),$pageHeader);
            $twigData['headerTitle']=$pageHeader;
        }

        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:news/story.html.twig',$twigData);
        } else {
            return $this->render('AIResponsiveBundle:news:story.html.twig',$twigData);
        }
    }

    public function getComingEvents($locale) {
        $Global = $this->get('global_functions');

        //Try to get a connection to the database and redirect to error page on failure
        $conn = $Global->getDBconnection();
        if( $conn['error'] ){
            $output['errors'] = true;
            $output['err_msg'] = $conn['msg'];
            return new Response( json_encode($output) );
        }else{ $db = $conn['connection']; }
        
        $eventboxDate_today = date("Y-m-d", time()); //Today
        $eventboxDate_ahead = date("Y-m-d", time() + (56 * 24 * 60 * 60)); //8 weeks ahead of today
        $tradeshows = array();  //Array to hold tradeshow data

        //Select all events in date range and sort by start date
        $sql = "SELECT * FROM Events WHERE end_date >= '".$eventboxDate_today."' AND start_date <= '".$eventboxDate_ahead."' ORDER BY start_date ASC";

        $returnedval = $db->query($sql);
        if( $returnedval ){
            while ( $row = $returnedval->fetch_assoc() ) {
                $x = '<tradeshow startdate="'.$row['start_date'].'" enddate="'.$row['end_date'].'" industry="'.$row['industry'].'">
                <link url="'.$row['link_url'].'"><![CDATA['.$row['link_text'].']]></link>
                <booth>'.$row['booth'].'</booth>
                <location>'.$row['location'].'</location>
                <details><![CDATA['.$row['details'].']]></details></tradeshow>';
                $tradeshows[] = simplexml_load_string($x);
            }
        }
        return $tradeshows;
    }

    public function getXML($path){
        $xml =simplexml_load_file($path);
        return $xml;
    }

    public function checkLocale() {
        if (isset($_GET['_locale'])) {
            $locale = $_GET["_locale"];
            $this->get('request')->setLocale($locale);
        }
    }

    /**
    * @Route("/barometer")
    * @Template()
    */
    public function BarometerAction(Request $request) {
        $quarter = 0; // Initialize variable
        $year = date("Y");
        $n = date('n'); // Get the current month (numeric)
        if ($n < 4) { $quarter = 1; } elseif ($n > 3 && $n <7) { $quarter = 2; } elseif ($n >6 && $n < 10) { $quarter = 3; } elseif ($n >9) { $quarter = 4; } // Get the current quarter
        $curBarometer = $year."-q".$quarter; // The path to the current barometer
        $file_headers = @get_headers("http://".$_SERVER['SERVER_NAME']."/asia-inspection-news/".$curBarometer); // Get the headers for the barometer for the current quarter
        $barotest = ( strpos($file_headers[0], '200 OK') > 0 || strpos($file_headers[0], '301') > 0 || strpos($file_headers[0], '302') > 0 ? true : false); // Check if the barometer for this quarter exists
        if (!$barotest) {
            // Redirect to prev baromoter
            if ($quarter == 1){
                $quarter = 4;
                $year = $year - 1;
            } else {
                $quarter = $quarter - 1;
            }
            $curBarometer = $year."-q".$quarter; // The path to the current barometer
        }

        // Generate the url and forwards it to the news controller (not redirect, so we can keep the current /barometer url)
        $url = $this->generateUrl("asia-inspection-news", array("id" => $curBarometer));
        return $this->forward('AIResponsiveBundle:news:viewNews',array('request' => $request, "id" => $curBarometer));
    }

    /**
     * @Route("/regulatory/{id}")
     * @Route("/regulatory/{id}/{section}")
     * @Template()
     */
    public function regulatoryAction(Request $request, $id=null, $section=null) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $twigData = array();
        $id = strtolower($id);
        $twigData['Stories'] = array();

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $twigData['tracking'] = $tracking->getTrackingCode('regulatory-updates', $locale);
        //include Tracking data [End]

        $Global = $this->get('global_functions');
        //Try to get a connection to the database and redirect to error page on failure
        $conn = $Global->getDBconnection();
        if( $conn['error'] ) {
            $output['errors'] = true;
            $output['err_msg'] = $conn['msg'];
            return new Response( json_encode($output) );
        } else { $db = $conn['connection']; }
        $db->set_charset("utf8");

        if(strtolower($id) == "post" && $section != null) {
            $stories = $db->query("SELECT * FROM RegRecap_Content WHERE Unique_Url = '".$section."'");
            $story = $stories->fetch_assoc();
            $twigData['Story'] = $story;
            $twigData['pageTitle'] = $story['Meta_Title'];
            $twigData['pageDescription'] = $story['Meta_Desc'];
            $twigData['pageKeywords'] = $story['Meta_Keywords'];
            return $this->render('AIResponsiveBundle:news:recapPostView.html.twig', $twigData);
        }

        // Get the Pages
        $returnedval = $db->query("SELECT * FROM RegRecap_Pages ORDER BY TimeDate DESC");

        // if no ID is given, show the list of recaps
        if($id == null || $id == "") {
            $twigData['Items'] = array();
            while ( $row = $returnedval->fetch_assoc() ) $twigData['Items'][] = $row;
            $twigData['contacts'] = simplexml_load_file($this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/ContactUsByPhoneBox.xml'));
            $twigData['userCountry'] = $Global->checkUserCountry();
            $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);
            return $this->render('AIResponsiveBundle:news:recapIndex.html.twig', $twigData);
        }

        while ( $row = $returnedval->fetch_assoc() ) {
            if($id == strtolower($row['URL'])) {
                $twigData['pageTitle'] = $row['Meta_Title'];
                $twigData['pageDescription'] = $row['Meta_Desc'];
                $twigData['pageKeywords'] = $row['Meta_Keywords'];
                $twigData['pageURL'] = $row['URL'];
                $Sections = json_decode($row['Sections']);
                $twigData['sections'] = $Sections->Sections;

                $tags = explode(",", $row['Tags']);
                $sql = "SELECT * FROM RegRecap_Content WHERE ";
                foreach ($tags as $tag) $sql .= "Tags LIKE '%".$tag."%' OR ";
                $sql = substr($sql, 0,-4) . " ORDER BY Date ASC";

                // TODO: Confirm the tags are correct using explode since using SQL LIKE could possibly return some false positives
                
                $stories = $db->query($sql);
                while ( $story = $stories->fetch_assoc() ) {
                    $story["Tags"] = explode(",", $story["Tags"]);
                    $twigData['Stories'][] = $story;
                }
            }
        }

        return $this->render('AIResponsiveBundle:news:recapView.html.twig', $twigData);
    }


    /**
     * @Route("/internal/create-regulatory")
     * @Template()
     */
    public function createRegulatoryAction(Request $request) {
        $this->checkLocale();
        $locale = $request->getLocale();
        $twigData = array();

        $Global = $this->get('global_functions');
        //Try to get a connection to the database and redirect to error page on failure
        $conn = $Global->getDBconnection();
        if( $conn['error'] ) {
            $output['errors'] = true;
            $output['err_msg'] = $conn['msg'];
            return new Response( json_encode($output) );
        } else { $db = $conn['connection']; }
        $db->set_charset("utf8");

        $sql = "SELECT * FROM RegRecap_Content ORDER BY Date DESC";
        $stories = $db->query($sql);
        $twigData["existingTags"] = array();
        while ( $story = $stories->fetch_assoc() ) {
            $story["Tags"] = explode(",", $story["Tags"]);
            $story["Date"] = date("Y-m-d", strtotime($story["Date"]));
            foreach ($story["Tags"] as $tag) {
                if(!in_array($tag, $twigData["existingTags"])) $twigData["existingTags"][] = $tag;
            }
            $twigData['Stories'][] = $story;
        }

        return $this->render('AIResponsiveBundle:news:createRecapView.html.twig', $twigData);
    }

    /**
    * @Route("/internal/updatestory")
    * @Method("POST")
    */
    public function updateStoryAction() {
        // INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
        $Global = $this->get('global_functions');
        //Try to get a connection to the database and redirect to error page on failure
        $conn = $Global->getDBconnection();
        if( $conn['error'] ) {
            $output['errors'] = true;
            $output['err_msg'] = $conn['msg'];
            return new Response(json_encode($output));
        } else { $db = $conn['connection']; }
        $db->set_charset("utf8");

        //foreach ($_POST as $key => $value) $_POST[$key] = addslashes($value);

        $sql = "INSERT INTO RegRecap_Content (Unique_Url, Title, Abstract, Content, Industry, Country, Date, Meta_Title, Meta_Desc, Meta_Keywords, Tags) VALUES('".$_POST['uniqueURL'].", '".$_POST['Title']."', \"".$_POST['Abstract']."\", \"".$_POST['Content']."\", '".$_POST['Industry']."', '".$_POST['Country']."', '".$_POST['postDate']."', '".$_POST['Meta_Title']."', '".$_POST['Meta_Desc']."', '".$_POST['Meta_Keywords']."', '".$_POST['Tags']."') ON DUPLICATE KEY UPDATE Unique_Url='".$_POST['uniqueURL']."', Title='".$_POST['Title']."', Abstract='".$_POST['Abstract']."', Content='".$_POST['Content']."', Industry='".$_POST['Industry']."', Country='".$_POST['Country']."', Date='".$_POST['postDate']."', Meta_Title='".$_POST['Meta_Title']."', Meta_Desc='".$_POST['Meta_Desc']."', Meta_Keywords='".$_POST['Meta_Keywords']."', Tags='".$_POST['Tags']."'";
        $result = $db->query($sql);
        if( !$result ){
            return new Response($db->error);
        } else {
            return new Response(json_encode($result));    
        }
        
    }

}