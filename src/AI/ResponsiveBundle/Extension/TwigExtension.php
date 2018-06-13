<?php
namespace AI\ResponsiveBundle\Extension;

class TwigExtension extends \Twig_Extension {
	public function getFilters() {
		return array(
			new \Twig_SimpleFilter('json_decode', array($this, 'jsonDecoder')),
		);
	}

	public function jsonDecoder($encodedString) {
		return json_decode($encodedString);
	}

	public function getName() {
		return 'twig_extension';
	}
}
