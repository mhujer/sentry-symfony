<?php

declare(strict_types = 1);

namespace Consistence\Sentry\SymfonyBundle\Annotation;

abstract class SentryMethod
{

	/** @var string */
	public $name;

	/** @var string */
	public $visibility;

}
