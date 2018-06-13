<?php

    namespace AI\ResponsiveBundle\Translation;

    use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;
    use Symfony\Component\Translation\MessageSelector;
    use Symfony\Component\DependencyInjection\ContainerInterface;

    class Translator extends BaseTranslator {
        public function trans($id, array $parameters = array(), $domain = null, $locale = null) {
            if (null === $domain)  $domain = 'messages';

            $globals = $this->container->get('twig')->getGlobals();
            unset($globals['app']);
            unset($globals['assetic']);
            $globalvars = array();
            foreach ($globals as $key => $val) {
                $globalvars["%".$key."%"] = $val;
            }
            $parameters = array_merge($globalvars, $parameters);
            return strtr($this->getCatalogue($locale)->get((string) $id, $domain), $parameters);
        }
    }

?>