<?php

declare(strict_types = 1);

use Consistence\Sentry\Factory\Simple\SimpleSentryFactory;
use Consistence\Sentry\MetadataSource\Annotation\AnnotationMetadataSource;
use Consistence\Sentry\Runtime\RuntimeHelper;
use Consistence\Sentry\Runtime\RuntimeHelperBridge;
use Consistence\Sentry\SentryIdentificatorParser\SentryIdentificatorParser;
use Consistence\Sentry\SymfonyBundle\Annotation\Add;
use Consistence\Sentry\SymfonyBundle\Annotation\Contains;
use Consistence\Sentry\SymfonyBundle\Annotation\DoctrineSentryAnnotationProvider;
use Consistence\Sentry\SymfonyBundle\Annotation\Get;
use Consistence\Sentry\SymfonyBundle\Annotation\Remove;
use Consistence\Sentry\SymfonyBundle\Annotation\Set;
use Consistence\Sentry\SymfonyBundle\Annotation\VarAnnotationProvider;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

error_reporting(E_ALL);

$loader = require __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$annotationProvider = new DoctrineSentryAnnotationProvider(
	new AnnotationReader(),
	[
		Add::class => 'add',
		Contains::class => 'contains',
		Get::class => 'get',
		Remove::class => 'remove',
		Set::class => 'set',
	],
	new VarAnnotationProvider()
);

$sentryIdentificatorParser = new SentryIdentificatorParser();

$sentryFactory = new SimpleSentryFactory($sentryIdentificatorParser);

$metadataSource = new AnnotationMetadataSource(
	$sentryFactory,
	$sentryIdentificatorParser,
	$annotationProvider
);

RuntimeHelperBridge::setHelper(new RuntimeHelper(
	$metadataSource,
	$sentryFactory
));
