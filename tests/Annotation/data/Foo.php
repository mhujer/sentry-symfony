<?php

declare(strict_types = 1);

namespace Consistence\Sentry\SymfonyBundle\Annotation;

use Consistence\Sentry\SymfonyBundle\Annotation as Sentry;

class Foo extends \Consistence\ObjectPrototype
{

	/**
	 * @Sentry\Get
	 * @var string
	 */
	private $noParams;

	/**
	 * @Sentry\Get(name="fooName", visibility="private")
	 */
	private $withFields;

	/**
	 * @Sentry\Get
	 * @Sentry\Get(name="fooName")
	 */
	private $multiple;

}
