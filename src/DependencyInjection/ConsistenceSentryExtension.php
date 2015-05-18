<?php

declare(strict_types = 1);

namespace Consistence\Sentry\SymfonyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConsistenceSentryExtension extends \Symfony\Component\HttpKernel\DependencyInjection\Extension
{

	const ALIAS = 'consistence_sentry';

	/**
	 * @param mixed[][] $configs
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		// ...
	}

	public function getAlias(): string
	{
		return self::ALIAS;
	}

}
