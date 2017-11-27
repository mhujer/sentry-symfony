<?php

declare(strict_types = 1);

namespace Consistence\Sentry\SymfonyBundle\Type;

class CollectionOfStringsIntegrationTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @return \Consistence\Sentry\SymfonyBundle\Type\Foo[][]
	 */
	public function fooProvider()
	{
		$generator = new SentryDataGenerator();
		$generator->generate('Foo');

		return [
			[new FooGenerated()],
		];
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testGetEmpty(Foo $foo)
	{
		$this->assertEmpty($foo->getAuthors());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testSetAndGet(Foo $foo)
	{
		$authors = [
			'Me',
			'Myself',
		];
		$foo->setAuthors($authors);
		$this->assertEquals($authors, $foo->getAuthors());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testAdd(Foo $foo)
	{
		$foo->addAuthor('Irene');
		$this->assertContains('Irene', $foo->getAuthors());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testContains(Foo $foo)
	{
		$authors = [
			'Me',
			'Myself',
		];
		$foo->setAuthors($authors);

		$this->assertTrue($foo->containsAuthor('Me'));
		$this->assertTrue($foo->containsAuthor('Myself'));
		$this->assertFalse($foo->containsAuthor('Irene'));
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testRemove(Foo $foo)
	{
		$authors = [
			'Me',
			'Myself',
		];
		$foo->setAuthors($authors);

		$foo->removeAuthor('Me');

		$this->assertFalse($foo->containsAuthor('Me'));
		$this->assertTrue($foo->containsAuthor('Myself'));
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testSetInvalidCollectionType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('array expected');

		$foo->setAuthors('Me');
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testSetInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('string expected');

		$foo->setAuthors(['Me', 1]);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testSetNullValue(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('string expected');

		$foo->setAuthors(['Me', null]);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testAddInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('string expected');

		$foo->addAuthor(1);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testAddNull(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('string expected');

		$foo->addAuthor(null);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testContainsInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('string expected');

		$foo->containsAuthor(1);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testRemoveInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('string expected');

		$foo->removeAuthor(1);
	}

}
