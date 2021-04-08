<?php
/**
 * ClassNames test
 *
 * @package micropackage/classnames
 * @license GPL-3.0-or-later
 */

namespace Micropackage\ClassNames\Tests;

use Micropackage\ClassNames\ClassNames;
use PHPUnit\Framework\TestCase;

final class ClassNamesTest extends TestCase {
	/**
	 * ClassNames instance.
	 *
	 * @var ClassNames
	 */
	private $classnames;

	/**
	 * Set up test.
	 */
	public function setUp() : void {
		$this->classnames = new ClassNames(
			'class-1',
			'class-2',
			[
				'class-2' => true,
				'class-3' => false,
			]
		);
	}
	/**
	 * @covers Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanCreateInstance() : void {
		$this->assertInstanceOf(ClassNames::class, new ClassNames());
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::__toString
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanBeUsedAsString() : void {
    $this->assertIsString((string) new ClassNames( 'example-class' ));
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::parse
	 * @covers Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanParseClassNames() : void {
		$result = $this->classnames->add(
			'class-2',
			'class-removed',
			[
				'class-4' => true,
				'class-5',
				'class-removed' => false,
			],
			'class-6'
		);

		$this->assertIsArray( $result );
		$this->assertEquals(
			[
				'class-1',
				'class-2',
				'class-4',
				'class-5',
				'class-6',
			],
			array_values( $result )
		);
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::remove
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 */
	public function testCanRemoveClassname() : void {
		$this->classnames->add(
			[
				'class-3',
				'class-4',
			]
		);
		$result = $this->classnames->remove(
			[
				'class-1',
				'class-4',
			],
			'class-n'
		);

		$this->assertIsArray( $result );
		$this->assertEquals(
			[
				'class-2',
				'class-3',
			],
			array_values( $result )
		);
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanCreateClassnameString() : void {
		$this->classnames->add( 'class-4' );

		$result = $this->classnames->build();

		$this->assertIsString( $result );
		$this->assertEquals( 'class-1 class-2 class-4', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testReturnsEmptyStringIfNoArguments() : void {
		$classnames = new ClassNames();

		$result = $classnames->build();

		$this->assertIsString( $result );
		$this->assertEquals( '', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testReturnsEmptyStringIfConditionsFail() : void {
		$classnames = new ClassNames(
			[
				'class-1' => is_int( 'string' ),
				'class-2' => is_string( 2 )
			]
		);

		$result = $classnames->build();

		$this->assertIsString( $result );
		$this->assertEquals( '', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::buildAttribute
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
 	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanCreateClassAttributeString() : void {
		$result = $this->classnames->buildAttribute();

		$this->assertIsString( $result );
		$this->assertEquals( 'class="class-1 class-2"', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::buildAttribute
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanCreateClassAttributeStringWithPrefixAndAppendix() : void {
		$result = $this->classnames->buildAttribute( ' ', ' tabindex="-1"' );

		$this->assertIsString( $result );
		$this->assertEquals( ' class="class-1 class-2" tabindex="-1"', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::buildAttribute
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testReturnsEmptyAttributeStringIfNoArguments() : void {
		$classnames = new ClassNames();

		$result = $classnames->buildAttribute( ' ', ' tabindex="-1"' );

		$this->assertIsString( $result );
		$this->assertEquals( '', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::get
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanGetClassnameString() : void {
		$result = ClassNames::get( 'class-1', 'class-2' );

		$this->assertIsString( $result );
		$this->assertEquals( 'class-1 class-2', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::getAttribute
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::buildAttribute
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 */
	public function testCanGetClassAttributeString() : void {
		$result = ClassNames::getAttribute(
			'class-1',
			'class-2',
			[
				'before' => ' ',
				'after'  => ' ',
			]
		);

		$this->assertIsString( $result );
		$this->assertEquals( ' class="class-1 class-2" ', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::print
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 * @uses Micropackage\ClassNames\ClassNames::get
	 */
	public function testCanPrintClassnameString() : void {
		ob_start();

		ClassNames::print( 'class-1', 'class-2' );

		$result = ob_get_clean();

		$this->assertIsString( $result );
		$this->assertEquals( 'class-1 class-2', $result );
	}

	/**
	 * @covers Micropackage\ClassNames\ClassNames::printAttribute
	 * @uses Micropackage\ClassNames\ClassNames::__construct
	 * @uses Micropackage\ClassNames\ClassNames::add
	 * @uses Micropackage\ClassNames\ClassNames::parse
	 * @uses Micropackage\ClassNames\ClassNames::build
	 * @uses Micropackage\ClassNames\ClassNames::buildAttribute
	 * @uses Micropackage\ClassNames\ClassNames::remove
	 * @uses Micropackage\ClassNames\ClassNames::getAttribute
	 */
	public function testCanPrintClassAttributeString() : void {
		ob_start();

		ClassNames::printAttribute(
			'class-1',
			'class-2',
			[
				'before' => ' ',
				'after'  => ' ',
			]
		);

		$result = ob_get_clean();

		$this->assertIsString( $result );
		$this->assertEquals( ' class="class-1 class-2" ', $result );
	}
}
