<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AI\ResponsiveBundle\Model\Data;
use Symfony\Component\HttpFoundation\Response;

class servicesController extends Controller
{
        /**
     * @Route("/quality-control-services",name="inspections_Services" )
     * @Template()
     */
    public function indexAction(Request $request) {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/quality-control-services-china-india-asia.xml');
       $array = $this->getXMLAndParameters($path,$request);

       $labTabLink=array();
       foreach($array['xml']->labSlide->leftList->li as $list){
        $labTabLink[]=$list['link'];
       }
       foreach($array['xml']->labSlide->rightList->li as $list){
        $labTabLink[]=$list['link'];
       }

       foreach($array['xml']->auditSlide->listTwo->li as $list) $array['auditLink'][] = array("link"=>$list['link'],"title"=>$list['title']);

       $productTitle=array();
       $productLink=array();
       foreach($array['xml']->productSlide->listitem as $list){
         $productTitle[]=$list['title'];
         $productLink[]=$list['link'];
       }

       $price_Inspect_Low = $this->container->getParameter('price_Inspect_Low');
       $price_Inspect_High = $this->container->getParameter('price_Inspect_High');
       $price_Inspect_Food = $this->container->getParameter('price_Inspect_Food');
       $i=0;
       foreach($array['xml']->productTab->li as $list){
           $array['xml']->productTab->li[$i] = str_replace("{{price_Inspect_Low}}", $price_Inspect_Low, $list);
           $array['xml']->productTab->li[$i] = str_replace("{{price_Inspect_High}}", $price_Inspect_High, $list);
           $array['xml']->productTab->li[$i] = str_replace("{{price_Inspect_Food}}", $price_Inspect_Food, $list);
           $i++;
       }

       $array['labTabLink']=$labTabLink;
       $array['productTitle']=$productTitle;
       $array['productLink']=$productLink;

      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('service-landing', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/serviceLanding.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:serviceLanding.html.twig',$array);
      }
    }
    /**
     * @Route("/quality-control-services/product-and-manufacturing-inspections",name="inspection_services_single" )
     * @Template()
     */
    public function inspectionservicesAction(Request $request)
    {
        $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/inspection-services.xml');
       $array=$this->getXMLAndParameters($path,$request);
       $array['type']="inspection_services";
       $array['headerbox']="inspectionservices";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('inspectionservices', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/inspection-services.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:inspection-services.html.twig',$array);
      }
    }
    /**
     * @Route("/quality-control-services/supplier-audit-programs",name="supplier_audit_programs" )
     * @Template()
     */
    public function supplierauditprogramsAction(Request $request)
    {
        $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/supplier-audit-programs.xml');
       $array=$this->getXMLAndParameters($path,$request);
       $array['type']="supplier_audit_programs";
       $array['headerbox']="supplierauditprograms";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('supplierauditprograms', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/inspection-services.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:inspection-services.html.twig',$array);
      }
    }
   /**
   * @Route("/pre-shipment-inspection",name="inspections_Pre-Shipment Inspection" )
   * @Template()
   */
    public function psiAction(Request $request)
    {
      //$browser['useragent'] = $_SERVER['HTTP_USER_AGENT'];
      //$user_agent = strtolower($browser['useragent']);
      if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') && !strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
          $isSafari = true;
        }
      else
        $isSafari = false;
        $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/pre-shipment-inspection.xml');
       $array=$this->getXMLAndParameters($path,$request);
       $serviceTab = $this->getServiceTab($request);
       $whatis = $serviceTab->Inspections->Benefits->PSI->whatIs;
       $array['serviceTab'] = $serviceTab;
       $array['whatis']= $whatis;
       $array['type']="PSI";
       $array['isSafari']=$isSafari;
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('psi', $locale);
      $locale = $request->getLocale();
      //include Tracking data [End]
        $price_Inspect_Low = $this->container->getParameter('price_Inspect_Low');
        $price_Inspect_High = $this->container->getParameter('price_Inspect_High');
        $array['price_Inspect_Low'] = $price_Inspect_Low;
        $array['price_Inspect_High'] = $price_Inspect_High;
        $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/inspection.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:inspection.html.twig',$array);
      }
    }
  /**
   * @Route("/production-monitoring",name="inspections_Production Monitoring" )
   * @Template()
   */
    public function pmAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/production-monitoring.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $whatis = $serviceTab->Inspections->Benefits->PM->whatIs;
       $array['serviceTab'] = $serviceTab;
       $array['whatis']= $whatis;
       $array['type']="PM";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('promon', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/inspection.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:inspection.html.twig',$array);
      }
    }
    /**
   * @Route("/during-production-inspection",name="inspections_During Production Check" )
   * @Template()
   */
    public function dpiAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/during-production-inspection.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $whatis = $serviceTab->Inspections->Benefits->DUPRO->whatIs;
       $array['serviceTab'] = $serviceTab;
       $array['whatis']= $whatis;
       $array['type']="DUPRO";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('dupro', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/inspection.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:inspection.html.twig',$array);
      }
    }
     /**
   * @Route("/initial-production-check",name="inspections_Initial Production Check" )
   * @Template()
   */
    public function ipcAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/initial-production-check.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $whatis = $serviceTab->Inspections->Benefits->IPC->whatIs;
       $array['serviceTab'] = $serviceTab;
       $array['whatis']= $whatis;
       $array['type']="IPC";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('ipc', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/inspection.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:inspection.html.twig',$array);
      }
    }
    /**
   * @Route("/container-loading-check",name="inspections_Container Loading Check" )
   * @Template()
   */
    public function clcAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/container-loading-check.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $whatis = $serviceTab->Inspections->Benefits->CLC->whatIs;
       $array['serviceTab'] = $serviceTab;
       $array['whatis']= $whatis;
       $array['type']="CLC";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('clc', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/inspection.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:inspection.html.twig',$array);
      }
    }

    /**
     * @Route("/testing/food-inspection-services",name="inspections_Food")
     * @Template()
     */
    public function fpsiAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/food-inspections.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $serviceTab = $this->getServiceTab($request);
        $whatis = $serviceTab->Inspections->Benefits->FPSI->whatIs;
        $array['serviceTab'] = $serviceTab;
        $array['whatis'] = $whatis;
        $array['type'] = "FPSI";
        $array['isSafari'] = !!(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') && !strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'));

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // Include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $request->getLocale();
        $array['tracking'] = $tracking->getTrackingCode('fpsi', $locale);
        //include Tracking data [End]

        $array['locale'] = $locale;
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/inspection.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:inspection.html.twig', $array);
        }
    }

    /**
   * @Route("/quality-control-vietnam",name="inspections_Inspections and Audits in Vietnam" )
   * @Template()
   */
    public function qcvietnamAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/quality-control-vietnam.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $array['type1'] = "vietnam";
       $array['type']="PSI";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('quality-control-vietnam', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/vietnam.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:vietnam.html.twig',$array);
      }
    }
     /**
   * @Route("/quality-control-bangladesh",name="inspections_Inspections and Audits in Bangladesh" )
   * @Template()
   */
    public function qcbangladeshAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/quality-control-bangladesh.xml');
       $array=$this->getXMLAndParameters($path,$request);
       $array['type1']="bangladesh";
       $array['type']="PSI";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('quality-control-bangladesh', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/bangladesh.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:bangladesh.html.twig',$array);
      }
    }
  /**
   * @Route("/quality-control-india",name="inspections_Inspections and Audits in India" )
   * @Template()
   */
    public function qcindiaAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/quality-control-india.xml');
       $array=$this->getXMLAndParameters($path,$request);
       $array['type1'] = "india";
       $array['type']="PSI";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('quality-control-india', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/india.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:india.html.twig',$array);
      }
    }
    /**
   * @Route("/manufacturing-audit",name="audits_Manufacturing Audits" )
   * @Template()
   */
    public function faAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/factory-audit.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $content = $serviceTab->Audits->Benefits->FA;
       $array['serviceTab'] = $serviceTab;
       $array['content']= $content;
       $array['type']="FA";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('manufacturing-audit', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      $array['locale'] = $locale;
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
      }
    }
    /**
   * @Route("/c-tpat-compliance",name="audits_C-TPAT Audits" )
   * @Template()
   */
    public function ctpatAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/c-tpat-compliance.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $content = $serviceTab->Audits->Benefits->CTPAT;
       $array['serviceTab'] = $serviceTab;
       $array['content']= $content;
       $array['type']="CTPAT";
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('ctpat', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
      }
    }

  /**
   * @Route("/environmental-audit",name="audits_Environmental Audit" )
   * @Template()
   */
    public function enviroAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/environmental-audit.xml');
       $array=$this->getXMLAndParameters($path,$request);
       $serviceTab = $this->getServiceTab($request);
       $content = $serviceTab->Audits->Benefits->ENVIRO;
       $array['serviceTab'] = $serviceTab;
       $array['content']= $content;
       $array['type']="ENVIRO";
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('environmental-audit', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
      }
    }

  /**
   * @Route("/bsci-supplier-audits",name="audits_BSCI Supplier Audits" )
   * @Template()
   */
    public function bsciAuditsAction(Request $request)
    {
        $this->checkLocale();
        //$path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/bsci-audit.xml');
        //$twigData=$this->getXMLAndParameters($path,$request);

        $twigData['pageTitle'] = "bsci-meta-pagetitle";
        $twigData['pageDesc'] = "bsci-meta-pagedesc";
        $twigData['pageKeywords'] = "bsci-meta-pagekeywords";
        $twigData['xml'] = array(
            "title" => "bsci-header-title",
            "subtext" => "bsci-header-subheading"
        );
        $twigData['type']="BSCI";

        //Need More Information Contents
        $Global = $this->get('global_functions');
        $twigData['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $twigData['tracking'] = $tracking->getTrackingCode('bsci-audit', $locale);
        //include Tracking data [End]

        $serviceTab = $this->getServiceTab($request);
        $twigData['serviceTab'] = $serviceTab;
        $content = $serviceTab->Audits->Benefits->ENVIRO;
        $twigData['content']= $content;

        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig',$twigData);
        } else {
            return $this->render('AIResponsiveBundle:services:audits.html.twig',$twigData);
        }
    }

    /**
   * @Route("/ethical-audit",name="audits_Ethical Audits" )
   * @Template()
   */
    public function eaAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/social-audit.xml');
       $array=$this->getXMLAndParameters($path,$request);

        if($request->getLocale() == "zh") {
          $assetsDomain = $this->container->getParameter('assets_china_domain');
        } else {
          $assetsDomain = $this->container->getParameter('assets_domain');
        }

      $serviceTab = $this->getServiceTab($request);
       $content = $serviceTab->Audits->Benefits->SA;
       $array['serviceTab'] = $serviceTab;
       $array['content']= $content;
       $array['imageURL'] =  $assetsDomain."/images/responsive/headerImages/Services-Audits-2-Ethical-Audits.jpg";
       $array['type']="SA";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('ethical-audit', $locale);
        //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/ethical-audit.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:ethical-audit.html.twig',$array);
      }
    }

    /**
   * @Route("/structural-audits",name="audits_Structural_Audits" )
   * @Template()
   */
    public function structuralAuditAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/structural-audits.xml');
        $array = $this->getXMLAndParameters($path,$request);
        $array['type'] = "structuralAudit";
        $serviceTab = $this->getServiceTab($request);
        $content = $serviceTab->Audits->Benefits->SA;
        unset($serviceTab->Audits->Benefits->whyUse->reason[3]); //Removing a section for Structural Audits page (Quick Dirty Fix) --Vince [Nov.9 2015]
        $array['serviceTab'] = $serviceTab;
        $array['content']= $content;
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('structural-audit', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig',$array);
        } else {
            return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
        }
    }

    /**
     * @Route("/third-party-food-safety-audits", name="audits_Food Home")
     * @Template()
     */
    public function foodAuditAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/food-audit.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $serviceTab = $this->getServiceTab($request);
        $content = $serviceTab->Audits->Benefits->FoodA;
        $array['serviceTab'] = $serviceTab;
        $array['content'] = $content;
        $array['type'] = "FoodA";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // Include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $request->getLocale();
        $array['tracking'] = $tracking->getTrackingCode('food-audit', $locale);
        // Include Tracking data [End]

        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
        }
    }

    /**
     * @Route("/ghp-food-hygiene-audits", name="audits_Food Hygiene")
     * @Template()
     */
    public function foodHygieneAuditAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/food-hygiene-audit.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $serviceTab = $this->getServiceTab($request);
        $content = $serviceTab->Audits->Benefits->GHP;
        $array['serviceTab'] = $serviceTab;
        $array['content'] = $content;
        $array['type'] = "GHP";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // Include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $request->getLocale();
        $array['tracking'] = $tracking->getTrackingCode('ghp', $locale);
        // Include Tracking data [End]

        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
        }
    }

    /**
     * @Route("/gmp-food-audits-and-compliance", name="audits_Food Good Manufacturing Practice")
     * @Template()
     */
    public function foodGoodManufacturingPracticeAuditAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/food-good-manufacturing-practice-audit.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $serviceTab = $this->getServiceTab($request);
        $content = $serviceTab->Audits->Benefits->GMP;
        $array['serviceTab'] = $serviceTab;
        $array['content'] = $content;
        $array['type'] = "GMP";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // Include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $request->getLocale();
        $array['tracking'] = $tracking->getTrackingCode('gmp', $locale);
        // Include Tracking data [End]

        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
        }
    }

     /**
   * @Route("/lab-testing")
   * @Template()
   */
    public function labTestingTrainadsAction(Request $request)
    {
    //  if(isset($_GET['xtor']) && $_GET['xtor']=="trainads"){
         //  $this->checkLocale();
           $this->get('request')->setLocale('zh');
           $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/laboratory-testing-trainads.xml');
           $array=$this->getXMLAndParameters($path,$request);
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
           //include Tracking data [Begin]
          $tracking = new Tracking();
          $locale = $this->get('session')->get('_locale');
          $array['tracking'] = $tracking->getTrackingCode('labtestLanding', $locale);
          //include Tracking data [End]
       return $this->render('AIResponsiveBundle:services:labTestTrainads.html.twig',$array);
    }
    /**
   * @Route("/laboratory-testing",name="labtest_Laboratory_Testing_Old" )
   * @Template()
   */
    public function labTestingActionOld(Request $request)
    {
        return $this->redirect('/quality-control-services/laboratory-testing',301);
    }

  /**
   * @Route("/quality-control-services/laboratory-testing",name="labtest_Laboratory Testing" )
   * @Template()
   */
    public function labTestingAction(Request $request)
    {
       $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/laboratory-testing.xml');
       $array = $this->getXMLAndParameters($path,$request);

       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/ProductExpertiseBox.xml');
       $locale = $request->getLocale();
       $pe =simplexml_load_file($path);
       if($pe->$locale!="")
         $pe = $pe->$locale;
       else
        $pe = $pe->en;
       $array['pe']=$pe;
       $array['labType']="LT";
       $array['type']="LAB";
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $array['tracking'] = $tracking->getTrackingCode('labtest', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
        return $this->render('AIResponsiveBundle:RTL:services/labTest.html.twig',$array);
      } else{
        return $this->render('AIResponsiveBundle:services:labTest.html.twig',$array);
      }
    }

  /**
   * @Route("/january-testing-promotion")
   * @Template()
   */
    public function januaryLabPromoAction(Request $request)
    {
       $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/january-labtest-promo.xml');
       $array = $this->getXMLAndParameters($path,$request);

       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/ProductExpertiseBox.xml');
       $locale = $request->getLocale();
       $pe =simplexml_load_file($path);
       if($pe->$locale!="")
         $pe = $pe->$locale;
       else
        $pe = $pe->en;
       $array['pe'] = $pe;
       $array['labType'] = "LT";
       $array['type'] = "LAB";
       $array['ctaNMI'] = true;
       $array['hidePricingBox'] = true;
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $array['nmi']['customType'] = "AnsecoLabPromo";
        $array['nmi']['data']->subtitle = "Call us at <a href='tel:+17166351180' style='color:black;'>+1-716-635-1180</a> or fill in the form below:";
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $array['tracking'] = $tracking->getTrackingCode('labtestjanpromo', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
        return $this->render('AIResponsiveBundle:RTL:services/labTest.html.twig',$array);
      } else{
        return $this->render('AIResponsiveBundle:services:labTest.html.twig',$array);
      }
    }

    /**
     * @Route("/food-testing-and-certification-lab", name="labtest_Food")
     * @Template()
     */
    public function foodTestingAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/food-testing.xml');
        $array = $this->getXMLAndParameters($path, $request);

        $array['labType'] = "FT";
        $array['type'] = "LAB";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('food-testing', $locale);
        // include Tracking data [End]
        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/labTest.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:labTest.html.twig', $array);
        }
    }

   /**
   * @Route("/reach-testing",name="labtest_REACH Testing" )
   * @Template()
   */
    public function reachAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/reach-testing.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $content = $serviceTab->LabTests->Benefits->REACH;
        $sectionUrl=array();
        $locale = $this->get('session')->get('_locale');
        if ($locale == "en")
          foreach($content->updates->section as $section){
                   $sectionUrl[]=$section->link['url'];
              }
       $array['serviceTab'] = $serviceTab;
       $array['content']= $content;
       $array['labType']="REACH";
       $array['type']="LAB";
       $array['sectionUrl']=$sectionUrl;
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $request->getLocale();
      $array['tracking'] = $tracking->getTrackingCode('reach', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
        return $this->render('AIResponsiveBundle:RTL:services/labTest.html.twig',$array);
      } else{
        return $this->render('AIResponsiveBundle:services:labTest.html.twig',$array);
      }
    }
    /**
   * @Route("/cpsia-testing",name="labtest_CPSIA Testing" )
   * @Template()
   */
    public function cpasiaAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/cpsia-testing.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $content = $serviceTab->LabTests->Benefits->CPSIA;

      if($request->getLocale() == "zh") {
        $assetsDomain = $this->container->getParameter('assets_china_domain');
      } else {
        $assetsDomain = $this->container->getParameter('assets_domain');
      }

       $array['serviceTab'] = $serviceTab;
       $array['content']= $content;
       $array['imageURL'] =  $assetsDomain."/images/responsive/headerImages/Services-Lab-Testing-2.jpg";
       $array['labType']="CPSIA";
       $array['type']="LAB";
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('cpsia', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
        return $this->render('AIResponsiveBundle:RTL:services/labTest.html.twig',$array);
      } else{
        return $this->render('AIResponsiveBundle:services:labTest.html.twig',$array);
      }
    }
    /**
   * @Route("/rohs-testing",name="labtest_RoHS Testing" )
   * @Template()
   */
    public function rohsAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/rohs-testing.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $serviceTab = $this->getServiceTab($request);
       $content = $serviceTab->LabTests->Benefits->ROHS;

       $array['serviceTab'] = $serviceTab;
       $array['content']= $content;
       $array['labType']="ROHS";
       $array['type']="LAB";
        //Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('rohs', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
        return $this->render('AIResponsiveBundle:RTL:services/labTest.html.twig',$array);
      } else{
        return $this->render('AIResponsiveBundle:services:labTest.html.twig',$array);
      }
    }

   /**
   * @Route("/smeta-audit",name="audits_SMETA Audit" )
   * @Template()
   */
    public function smetaAction(Request $request) {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/smeta-audit.xml');
        $array = $this->getXMLAndParameters($path,$request);
        $serviceTab = $this->getServiceTab($request);
        $array['serviceTab'] = $serviceTab;
        $array['whatis']= $serviceTab->Inspections->Benefits->PSI->whatIs;
        $array['type']="SMETAAudit";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('smetaaudit', $locale);
        //include Tracking data [End]
        $locale = $request->getLocale();
        if($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/audits.html.twig',$array);
        } else {
            return $this->render('AIResponsiveBundle:services:audits.html.twig',$array);
        }
    }

  /**
   * @Route("/quality-control-services-by-industry",name="industries_Industries" )
   * @Template()
   */
    public function industriesAction(Request $request)
    {
      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/by-industry-landing.xml');
       $array=$this->getXMLAndParameters($path,$request);
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('industry-landing', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/yourIndustry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:yourIndustry.html.twig',$array);
      }
    }
   /**
   * @Route("/industry-softline",name="industries_Softlines" )
   * @Template()
   */
    public function softlinesAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/industry-softline.xml');
       $array=$this->getXMLAndParameters($path,$request);
        $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->softline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->softline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="softline";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('softline', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }
   /**
   * @Route("/industry-hardline",name="industries_Hardlines" )
   * @Template()
   */
    public function hardlinesAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/industry-hardline.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->hardline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->hardline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="hardline";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('hardline', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }
       /**
   * @Route("/industry-techparts",name="industries_Technical Parts" )
   * @Template()
   */
    public function techpartsAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/industry-techparts.xml');
       $array=$this->getXMLAndParameters($path,$request);

       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->TechParts->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->TechParts->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="techpart";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('techparts', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }

    /**
     * @Route("/food-safety-testing", name="industries_Food")
     * @Template()
     */
    public function foodAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/industry-food.xml');
        $array = $this->getXMLAndParameters($path, $request);

        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->TechParts->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->TechParts->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;
        $array['type'] = "food";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // Include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $request->getLocale();
        $array['tracking'] = $tracking->getTrackingCode('food', $locale);
        // Include Tracking data [End]

        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

  /**
   * @Route("/testing/footwear",name="industries_Footwear" )
   * @Template()
   */
    public function footwearAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/footwear.xml');

       $array=$this->getXMLAndParameters($path,$request);
       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->softline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->softline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="footwear";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('footwear', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }
 /**
   * @Route("/testing/garments-apparel",name="industries_Garments-Apparel" )
   * @Template()
   */
    public function garmentsApparelAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/garmentAndApparel.xml');

       $array=$this->getXMLAndParameters($path,$request);
       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->softline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->softline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="apparel";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('garments-apparel', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }
 /**
   * @Route("/testing/electrical",name="industries_Electrical" )
   * @Template()
   */
    public function electricalAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/electrical.xml');

       $array=$this->getXMLAndParameters($path,$request);
       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->hardline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->hardline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="electrical";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('electrical', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }
    /**
   * @Route("/testing/jewelry",name="industries_Jewelry" )
   * @Template()
   */
    public function jewelryAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/jewelry.xml');

       $array=$this->getXMLAndParameters($path,$request);
       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->hardline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->hardline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="jewelry";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('jewelry', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }
    /**
   * @Route("/testing/toy-safety-and-lab-testing",name="industries_Toys" )
   * @Template()
   */
    public function toysAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/toys.xml');

       $array=$this->getXMLAndParameters($path,$request);
       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->hardline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->hardline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="toys";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('toys', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }
    /**
   * @Route("/testing/eyewear",name="industries_Eyewear" )
   * @Template()
   */
    public function eyewearAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/eyewear.xml');

       $array=$this->getXMLAndParameters($path,$request);
       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->hardline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->hardline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="eyewear";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('eyewear', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }

    /**
     * @Route("/testing/spectacle-frames", name="industries_Spectacle Frames")
     * @Template()
     */
    public function spectacleFramesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/spectacle-frames.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->hardline->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->hardline->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;

        $array['type'] = "spectacle-frames";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('spectacle-frames', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/sunglasses", name="industries_Sunglasses")
     * @Template()
     */
    public function sunglassesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/sunglasses.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->hardline->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->hardline->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;

        $array['type'] = "sunglasses";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('sunglasses', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/optical-lenses", name="industries_Optical Lenses")
     * @Template()
     */
    public function opticalLensesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/optical-lenses.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->hardline->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->hardline->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;

        $array['type'] = "optical-lenses";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('optical-lenses', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/reading-glasses", name="industries_Reading Glasses")
     * @Template()
     */
    public function readingGlassesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/reading-glasses.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->hardline->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->hardline->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;

        $array['type'] = "reading-glasses";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('reading-glasses', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/eye-protectors", name="industries_Eye Protectors")
     * @Template()
     */
    public function eyeProtectorsAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/eye-protectors.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->hardline->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->hardline->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;

        $array['type'] = "eye-protectors";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('eye-protectors', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/sports-eyewear-goggles", name="industries_Sports Eyewear and Goggles")
     * @Template()
     */
    public function sportsEyewearGogglesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/sports-eyewear-goggles.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->hardline->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->hardline->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;

        $array['type'] = "sports-eyewear-goggles";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('sports-eyewear-goggles', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

        /**
   * @Route("/testing/cosmetics",name="industries_Cosmetics" )
   * @Template()
   */
    public function cosmeticsAction(Request $request)
    {

      $this->checkLocale();
       $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/cosmetics.xml');

       $array=$this->getXMLAndParameters($path,$request);
       $sidebox = $this->servicesSideBox($request);
       $array['reports']=$sidebox->ByIndustry->hardline->report;
       $reportID=array();
       foreach($sidebox->ByIndustry->hardline->report as $report){
          $reportID[]=$report['id'];
       }
       $array['reportID']=$reportID;
       $array['sidebox']=$sidebox;
       $array['type']="cosmetics";
       //Need More Information Contents
       $Global = $this->get('global_functions');
       $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
      //include Tracking data [Begin]
      $tracking = new Tracking();
      $locale = $this->get('session')->get('_locale');
      $array['tracking'] = $tracking->getTrackingCode('cosmetics', $locale);
      //include Tracking data [End]
      $locale = $request->getLocale();
      if($locale == "ar") {
          return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
      } else {
          return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
      }
    }

    /**
     * @Route("/testing/promotional-products", name="industries_Promotional Products")
     * @Template()
     */
    public function promotionalProductsAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/promotional-products.xml');

        $array = $this->getXMLAndParameters($path,$request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports'] = $sidebox->ByIndustry->hardline->report;
        // $reportID = array();
        // foreach ($sidebox->ByIndustry->hardline->report as $report) {
        //     $reportID[] = $report['id'];
        // }
        // $array['reportID'] = $reportID;
        // $array['sidebox'] = $sidebox;
        $array['type'] = "promotional-products";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('promotional-products', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig',$array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig',$array);
        }
    }

    /**
     * @Route("/testing/food-packaging-and-containers", name="industries_Food Containers")
     * @Template()
     */
    public function foodContainersAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/food-containers.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "food-containers";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $request->getLocale();
        $array['tracking'] = $tracking->getTrackingCode('food-containers', $locale);
        // include Tracking data [End]

        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/produce-inspections-and-quality-control", name="industries_Food Produce")
     * @Template()
     */
    public function foodProduceAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/food-produce.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "food-produce";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $array['nmi']['customType'] = "ProduceInquiry";

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('food-produce', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/berries", name="industries_Fresh Berries")
     * @Template()
     */
    public function berriesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/berries.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "berries";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);
        $array['nmi']['customType'] = "BerryInquiry";

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('berries', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/seafood", name="industries_Seafood")
     * @Template()
     */
    public function seafoodAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/seafood.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "seafood";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('seafood', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/meat-poultry", name="industries_Meat and Poultry")
     * @Template()
     */
    public function meatAndPoultryAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/meat-poultry.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "meat-poultry";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('meat-poultry', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/beverage-quality-control", name="industries_Beverages")
     * @Template()
     */
    public function beveragesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/beverages.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "beverages";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('beverages', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/quality-control-for-processed-food", name="industries_Processed Food")
     * @Template()
     */
    public function processedFoodAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/processed-food.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "processed-food";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('processed-food', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

    /**
     * @Route("/testing/textile-fabric-quality-control", name="industries_Textiles")
     * @Template()
     */
    public function textilesAction(Request $request)
    {
        $this->checkLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/industries/textiles.xml');

        $array = $this->getXMLAndParameters($path, $request);
        // $sidebox = $this->servicesSideBox($request);
        // $array['reports']=$sidebox->ByIndustry->hardline->report;
        // $reportID=array();
        // foreach($sidebox->ByIndustry->hardline->report as $report){
        // $reportID[]=$report['id'];
        // }
        // $array['reportID']=$reportID;
        // $array['sidebox']=$sidebox;
        $array['type'] = "textiles";

        // Need More Information Contents
        $Global = $this->get('global_functions');
        $array['nmi'] = $Global->NeedMoreInfoBox($this, $request);

        // include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('textiles', $locale);
        // include Tracking data [End]

        $locale = $request->getLocale();
        if ($locale == "ar") {
            return $this->render('AIResponsiveBundle:RTL:services/industry.html.twig', $array);
        } else {
            return $this->render('AIResponsiveBundle:services:industry.html.twig', $array);
        }
    }

   /**
   * @Route("/submitEmailPopup")
   *
   */
    public function submitEmailPopUpAction(Request $request){
        if(!isset($_GET['report'])) return $this->redirect('/',301);
        $report = $_GET['report'];
        $type = ( isset($_GET['type']) ? $_GET['type'] : "" );
        $popup = $this->getPopUp($request);
        if($type == "whitepaper"){
            return $this->render('AIResponsiveBundle:services:getEmailWhitepaperPopup.html.twig',array('report'=>$report));
        } else {
            return $this->render('AIResponsiveBundle:services:submitEmailPopup.html.twig', array('popup'=>$popup,'report'=>$report));
        }
    } // End of submitEmailPopUpAction


   /**
   * @Route("/marketoEmailPopup/{type}/{id}")
   *
   */
    public function marketoEmailPopUpAction(Request $request, $type="default", $id=null){
        return $this->render('AIResponsiveBundle:Default:marketoEmailPopup.html.twig', array('type'=>strtolower($type), 'report'=>strtolower($id)));
    } // End of submitEmailPopUpAction


   /**
   * @Route("/uploadConsultForm")
   * @Method("POST")
   */
    public function uploadConsultFormAction(){
      $data = new Data();
      $RESTdata = array(
          'userName' => $_POST['name'],
          'companyName' => '',
          'industry' => '',
          'isFactory' => '',
          'emailAddress' => $_POST['email'],
          'telNumber' => $_POST['phone'],
          'country' => '',
          'hearFrom' => '',
          'tradeshowCountry' => '',
          'tradeshow' => '',
          'recommendation' => '',
          'advertising' => '',
          'sillikerOffice' => '',
          'sillikerContact' => '',
          'inquiryAbout' => '',
          'inquiryAboutOther' => '',
          'leadType' => '',
          'message' => '',
          'isManual' => '',
          'status' => 'Not Replied',
          'searchEngine' => '',
          'queryUsed' => '',
          'url' => '',
          'newsletter' => '',
          'domain' => '',
          'isCHB' => 'Yes',
        );

      $returnedval = $data->CallRest("need-more-information", "dev", "post", $RESTdata, true);
      if($returnedval){
        $this->sendEmailToCHB($_POST['name'],$_POST['email'],$_POST['phone']);
      }
      return new Response($returnedval);


    }


    /**
     * @Route("/serviceCoverage")
     */
    public function serviceCoverageAction (Request $request)
    {
        $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/GlobalNetworkBox.xml');
        $global = simplexml_load_file($path);
        $global = ( $global->$locale != "" ? $global = $global->$locale : $global = $global->en );
        $twigData =  array('global'=>$global);

        return $this->render('AIResponsiveBundle:services:ServiceCoverage.html.twig',$twigData);
    }

    /**
     * @Route("/benefits")
     * @Route("/benefits/{type}")
     */
    public function benefitsAction (Request $request, $type=null)
    {
        $locale = $this->get('session')->get('_locale');
        $twigData = array("type" => $type);
        $serviceTab = $this->getServiceTab($request);

        if($request->getLocale() == "zh") {
            $twigData['assetsDomain'] = $this->container->getParameter('assets_china_domain');
        } else {
            $twigData['assetsDomain'] = $this->container->getParameter('assets_domain');
        }

        if( isset($type) && $type == "inspections" ) {
            $twigData['Benefits'] = $serviceTab->Inspections->Benefits;
        } else if ( isset($type) && $type == "audits" ) {
            $twigData['Benefits'] = $serviceTab->Audits->Benefits;
        } else if ( isset($type) && $type == "labtests" ) {
            $twigData['Benefits'] = $serviceTab->LabTests->Benefits;
        } else {

        }

        return $this->render('AIResponsiveBundle:services:serviceBenefits.html.twig',$twigData);

    }

    public function servicesSideBox($request){
          $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/ServiceLinksBox.xml');
          $locale = $request->getLocale();
          $sidebox =simplexml_load_file($path);
          if($sidebox->$locale!="")
             $sidebox = $sidebox->$locale;
          else
             $sidebox = $sidebox->en;

    return $sidebox;
    }

    public function getServiceTab($request)
    {
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/ServiceTabs.xml');
        $locale = $request->getLocale();
        $serviceTab = simplexml_load_file($path);
        $serviceTab = ( $serviceTab->$locale != "" ? $serviceTab->$locale : $serviceTab->en );
        return $serviceTab;
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

    public function getPopUp($request){
       $locale = $request->getLocale();
        $path = $this->get('kernel')->locateResource('@AIResponsiveBundle/Resources/public/xml/services/Popups.xml');
        $popup =simplexml_load_file($path);
        $popup = $popup->SampleReport->$locale;
        return $popup;
    }


   public function checkLocale(){
    if (isset($_GET['_locale'])) {
      $locale = $_GET["_locale"];
      $this->get('request')->setLocale($locale);

    }
  }

    /**
     * @Route("/tapwater")
     * @Template()
     */
    public function tapwaterAction()
    {
        //include Tracking data [Begin]
        $tracking = new Tracking();
        $locale = $this->get('session')->get('_locale');
        $array['tracking'] = $tracking->getTrackingCode('tapwater', $locale);
        //include Tracking data [End]
        return $this->render('AIResponsiveBundle:services:tapwater.html.twig', $array);
    }


  public function sendEmailToCHB($name,$email,$phone){

    $message = \Swift_Message::newInstance()
        ->setSubject('一名用户已通过高铁广告二维码/在线查询申请技术咨询服务。')
        ->setFrom('marketing@asiainspection.com')
        ->setTo('CHBSales@ASIAINSPECTION.COM')
        ->setCC('sharon.zhou@asiainspection.com')
        ->setBody($this->renderView(
                'AIResponsiveBundle:services:emailCHBFormat.html.twig',
                array('name' => $name,'email'=>$email,'phone'=>$phone)
            ),
            'text/html'
        );

    $mailer = $this->get('mailer');

    $mailer->send($message);

    $spool = $mailer->getTransport()->getSpool();
    $transport = $this->get('swiftmailer.transport.real');

    $spool->flushQueue($transport);

    return true;


  }


}
