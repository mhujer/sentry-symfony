<?php

declare(strict_types = 1);

namespace Consistence\Sentry\SymfonyBundle\Type;

use DateTimeImmutable;
use stdClass;

class CollectionOfObjectsIntegrationTest extends \PHPUnit\Framework\TestCase
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
		$this->assertEmpty($foo->getEventDates());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testSetAndGet(Foo $foo)
	{
		$eventDates = [
			new DateTimeImmutable(),
			new DateTimeImmutable('tomorrow'),
		];
		$foo->setEventDates($eventDates);
		$this->assertEquals($eventDates, $foo->getEventDates());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testAdd(Foo $foo)
	{
		$date = new DateTimeImmutable();
		$foo->addEventDate($date);
		$this->assertContains($date, $foo->getEventDates());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testContains(Foo $foo)
	{
		$today = new DateTimeImmutable();
		$tomorrow = new DateTimeImmutable('tomorrow');
		$dates = [
			$today,
			$tomorrow,
		];
		$foo->setEventDates($dates);

		$this->assertTrue($foo->containsEventDate($today));
		$this->assertTrue($foo->containsEventDate($tomorrow));
		$this->assertFalse($foo->containsEventDate(new DateTimeImmutable()));
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testRemove(Foo $foo)
	{
		$today = new DateTimeImmutable();
		$tomorrow = new DateTimeImmutable('tomorrow');
		$dates = [
			$today,
			$tomorrow,
		];
		$foo->setEventDates($dates);

		$foo->removeEventDate($today);

		$this->assertFalse($foo->containsEventDate($today));
		$this->assertTrue($foo->containsEventDate($tomorrow));
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

		$foo->setEventDates(new DateTimeImmutable());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testSetInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('DateTimeInterface expected');

		$foo->setEventDates([new DateTimeImmutable(), new stdClass()]);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testSetNullValue(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('DateTimeInterface expected');

		$foo->setEventDates([new DateTimeImmutable(), null]);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testAddInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('DateTimeInterface expected');

		$foo->addEventDate(new stdClass());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testAddNull(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('DateTimeInterface expected');

		$foo->addEventDate(null);
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testContainsInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('DateTimeInterface expected');

		$foo->containsEventDate(new stdClass());
	}

	/**
	 * @dataProvider fooProvider
	 *
	 * @param \Consistence\Sentry\SymfonyBundle\Type\Foo $foo
	 */
	public function testRemoveInvalidItemType(Foo $foo)
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('DateTimeInterface expected');

		$foo->removeEventDate(new stdClass());
	}

}
