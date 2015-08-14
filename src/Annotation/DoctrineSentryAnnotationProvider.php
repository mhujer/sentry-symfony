<?php

declare(strict_types = 1);

namespace Consistence\Sentry\SymfonyBundle\Annotation;

use Consistence\Annotation\Annotation;
use Consistence\Annotation\AnnotationField;
use Doctrine\Common\Annotations\Reader;
use ReflectionProperty;

class DoctrineSentryAnnotationProvider extends \Consistence\ObjectPrototype implements \Consistence\Annotation\AnnotationProvider
{

	/** @var \Doctrine\Common\Annotations\Reader */
	private $annotationReader;

	/** @var string[] format: annotation class (string) => sentry annotation name (string) */
	private $annotationMap;

	/**
	 * @param \Doctrine\Common\Annotations\Reader $annotationReader
	 * @param string[] $annotationMap
	 */
	public function __construct(Reader $annotationReader, array $annotationMap)
	{
		$this->annotationReader = $annotationReader;
		$this->annotationMap = $annotationMap;
	}

	public function getPropertyAnnotation(ReflectionProperty $property, string $annotationName): Annotation
	{
		$annotation = $this->findAnnotation($property, $annotationName);

		return $this->convertAnnotation($annotationName, $annotation);
	}

	/**
	 * @param \ReflectionProperty $property
	 * @param string $annotationName
	 * @return \Consistence\Annotation\Annotation[]
	 */
	public function getPropertyAnnotations(ReflectionProperty $property, string $annotationName)
	{
		$annotations = $this->annotationReader->getPropertyAnnotations($property);

		$convertedAnnotations = [];
		foreach ($annotations as $annotation) {
			$class = get_class($annotation);
			if (!isset($this->annotationMap[$class]) || $this->annotationMap[$class] !== $annotationName) {
				continue;
			}
			$convertedAnnotations[] = $this->convertAnnotation($annotationName, $annotation);
		}

		return $convertedAnnotations;
	}

	/**
	 * @param \ReflectionProperty $property
	 * @param string $annotationName
	 * @return object
	 */
	private function findAnnotation(ReflectionProperty $property, string $annotationName)
	{
		foreach ($this->annotationMap as $class => $name) {
			if ($name !== $annotationName) {
				continue;
			}
			$annotation = $this->annotationReader->getPropertyAnnotation($property, $class);
			if ($annotation !== null) {
				return $annotation;
			}
		}

		throw new \Consistence\Annotation\AnnotationNotFoundException($annotationName, $property);
	}

	/**
	 * @param string $annotationName
	 * @param object $annotation
	 * @return \Consistence\Annotation\Annotation
	 */
	private function convertAnnotation(string $annotationName, $annotation): Annotation
	{
		$fields = [];
		foreach ($annotation as $name => $value) {
			if ($value === null) {
				continue;
			}
			$fields[] = new AnnotationField($name, $value);
		}

		return Annotation::createAnnotationWithFields($annotationName, $fields);
	}

}
