<?php

declare(strict_types = 1);

namespace Consistence\Sentry\SymfonyBundle\Annotation;

use ReflectionClass;
use ReflectionProperty;

class VarAnnotationProviderTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @return string[][]
	 */
	public function varAnnotationProvider(): array
	{
		return [
			['string'],
			['integer'],
			['null'],
			['string|null'],
			['int'],
			['string|integer'],
			['\Foo'],
			['\Foo\Bar'],
			['Foo'],
			['Foo'],
			['\Foo|integer'],
			['string[]'],
			['\Foo[]'],
			['Template<Foo>'],
			['integer:string'],
		];
	}

	/**
	 * @dataProvider varAnnotationProvider
	 *
	 * @param string $value
	 */
	public function testGetVarAnnotationValue(string $value)
	{
		$docComment = sprintf('/**
			 * @var %s
			 */', $value);

		$property = $this
			->getMockBuilder(ReflectionProperty::class)
			->disableOriginalConstructor()
			->getMock();

		$property
			->expects($this->once())
			->method('getDocComment')
			->willReturn($docComment);

		$varAnnotationProvider = new VarAnnotationProvider();

		$annotation = $varAnnotationProvider->getPropertyAnnotation($property, 'var');

		$this->assertSame('var', $annotation->getName());
		$this->assertSame($value, $annotation->getValue());
		$this->assertEmpty($annotation->getFields());
	}

	/**
	 * @dataProvider varAnnotationProvider
	 *
	 * @param string $value
	 */
	public function testGetVarAnnotationValueInline(string $value)
	{
		$docComment = sprintf('/** @var %s */', $value);

		$property = $this
			->getMockBuilder(ReflectionProperty::class)
			->disableOriginalConstructor()
			->getMock();

		$property
			->expects($this->once())
			->method('getDocComment')
			->willReturn($docComment);

		$varAnnotationProvider = new VarAnnotationProvider();

		$annotation = $varAnnotationProvider->getPropertyAnnotation($property, 'var');

		$this->assertSame('var', $annotation->getName());
		$this->assertSame($value, $annotation->getValue());
		$this->assertEmpty($annotation->getFields());
	}

	public function testVarAnnotationDoesNotExist()
	{
		try {
			$docComment = '/**
				 * @author
				 */';

			$property = $this
				->getMockBuilder(ReflectionProperty::class)
				->disableOriginalConstructor()
				->getMock();

			$property
				->expects($this->once())
				->method('getDocComment')
				->willReturn($docComment);

			$property
				->expects($this->any())
				->method('getDeclaringClass')
				->willReturn(new ReflectionClass(Foo::class));

			$property
				->expects($this->any())
				->method('getName')
				->willReturn('test');

			$varAnnotationProvider = new VarAnnotationProvider();

			$varAnnotationProvider->getPropertyAnnotation($property, 'var');

			$this->fail();

		} catch (\Consistence\Annotation\AnnotationNotFoundException $e) {
			$this->assertSame($property, $e->getProperty());
			$this->assertSame('var', $e->getAnnotationName());
		}
	}

	public function testMalformedVarAnnotation()
	{
		try {
			$docComment = '/**
				 * @var
				 */';

			$property = $this
				->getMockBuilder(ReflectionProperty::class)
				->disableOriginalConstructor()
				->getMock();

			$property
				->expects($this->once())
				->method('getDocComment')
				->willReturn($docComment);

			$property
				->expects($this->any())
				->method('getDeclaringClass')
				->willReturn(new ReflectionClass(Foo::class));

			$property
				->expects($this->any())
				->method('getName')
				->willReturn('test');

			$varAnnotationProvider = new VarAnnotationProvider();

			$varAnnotationProvider->getPropertyAnnotation($property, 'var');

			$this->fail();

		} catch (\Consistence\Annotation\AnnotationNotFoundException $e) {
			$this->assertSame($property, $e->getProperty());
			$this->assertSame('var', $e->getAnnotationName());
		}
	}

	public function testSupportsOnlyVarAnnotation()
	{
		try {
			$property = $this
				->getMockBuilder(ReflectionProperty::class)
				->disableOriginalConstructor()
				->getMock();

			$property
				->expects($this->any())
				->method('getDeclaringClass')
				->willReturn(new ReflectionClass(Foo::class));

			$property
				->expects($this->any())
				->method('getName')
				->willReturn('test');

			$varAnnotationProvider = new VarAnnotationProvider();

			$varAnnotationProvider->getPropertyAnnotation($property, 'author');

			$this->fail();

		} catch (\Consistence\Annotation\AnnotationNotFoundException $e) {
			$this->assertSame($property, $e->getProperty());
			$this->assertSame('author', $e->getAnnotationName());
		}
	}

	public function testDoesNotSupportGetAnnotations()
	{
		$property = $this
			->getMockBuilder(ReflectionProperty::class)
			->disableOriginalConstructor()
			->getMock();

		$property
			->expects($this->never())
			->method('getDocComment');

		$varAnnotationProvider = new VarAnnotationProvider();

		$this->assertEmpty($varAnnotationProvider->getPropertyAnnotations($property, 'var'));
	}

}
