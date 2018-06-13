<?php
namespace AI\ResponsiveBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;



    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;

    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

       // get domain name and port
        $host = $request->getHttpHost();
        $port = $_SERVER['SERVER_PORT'];
        $locale = $this->defaultLocale;

        //Use the language set by the session
        if($request->getSession()->get('_locale') != null) $locale = $request->getSession()->get('_locale');

        // English
        if( $host == "www.asiainspection.com" || $host == "asiainspection.com" || $host == "qima.en" || $host == "resp.en" || $port == "99" ) $locale = "en";
        // Chinese
        if( $host == "www.chinainspection.com" || $host == "chinainspection.com" || $host == "qima.zh" || $host == "resp.zh" || $host == "dev.chinainspection.com" || $port == "86" )  $locale = "zh";
        // French
        if( $host == "fr.asiainspection.com" || $host == "www.asiainspection.fr" || $host == "asiainspection.fr" || $host == "qima.fr" || $host == "resp.fr" || $port == "88" )  $locale = "fr";
        // German
        if( $host == "de.asiainspection.com" || $host == "www.asiainspection.de" || $host == "asiainspection.de" || $host == "qima.de" || $host == "resp.de" || $port == "89" )  $locale = "de";
        // Spanish
        if( $host == "resp.es" || $host == "qima.es" || $port == "84" ) $locale = "es";
        // Arabic
        if( $host == "qima.ar" || $host == "resp.ar" || $port == "85" ) $locale = "ar";
        // Italian
        if( $host == "qima.it" || $host == "resp.it" || $port == "90" ) $locale = "it";
        // Portuguese
        if( $host == "www.asiainspection.com.br" || $host == "asiainspection.com.br" || $host == "qima.pt" || $host == "resp.pt" || $port == "91" ) $locale = "pt";
        // Russian
        if( $host == "qima.ru" || $host == "resp.ru" || $port == "92" ) $locale = "ru";
        // Turkish
        if( $host == "www.asiainspection.com.tr" || $host == "asiainspection.com.tr" || $host == "qima.tr" || $host == "resp.tr" || $port == "93" ) $locale = "tr";

        // try to see if the locale has been set as a _locale routing parameter
        if($request->get('_locale') != null) $locale = $request->get('_locale');

        //Set the locale
        $request->setLocale($locale);
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}
?>
