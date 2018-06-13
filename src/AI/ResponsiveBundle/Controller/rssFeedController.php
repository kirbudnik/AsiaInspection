<?php

namespace AI\ResponsiveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

ini_set('max_execution_time', 600);

class rssFeedController extends Controller
{
     /**
     * @Route("/content/newsRssFeed.xml")
     * 
     */

    public function newsRssFeedAction(Request $request){
        $request->setRequestFormat('xml');
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/news/news/en_Announce.xml');
        $news=$this->getXML($path);
        
        $url = array();
        $imageData = array();

        foreach($news->post as $key => $post) {
            $url[] = (String) $post->link['url'];
            $post->datetime =  date(DATE_RSS, strtotime($post->datetime));

            $img = array(0 => "", 1 => "", "mime" => "", "url" => "", "size" => "");
            if( isset($post->image) ){
                try{
                    //$img = getimagesize("https://s3.asiainspection.com/images/news/".$post->image);
                    $head = array_change_key_case(get_headers("https://s3.asiainspection.com/images/news/".$post->image, TRUE));
                    $img['url'] = "https://s3.asiainspection.com/images/news/".$post->image;
                    $img['size'] = $head['content-length'];
                    $img['mime'] = $head['content-type'];
                } catch (\Exception $e) {}
            }

            $imageData[] = array(
                "url" => $img['url'],
                "size" => $img['size'],
                "type" => $img['mime']
            );
        }
       
        $twigData = array();
        $twigData['news'] = $news;
        $twigData['url'] = $url;
        $twigData['newsImage'] = $imageData;

        return $this->render('AIResponsiveBundle:RSSFeed:newsRSSFeed.xml.twig', $twigData);
    }


    public function getXML($path){
        $xml = simplexml_load_file($path);
        return $xml;
    }

}
