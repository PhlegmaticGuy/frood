<?php
/**
 * Test the parameters class for The Frood.
 *
 * PHP version 5
 *
 * @category Test
 * @package  Frood
 * @author   Jens Riisom Schultz <jers@fynskemedier.dk>
 * @since    2011-06-09
 */

/**
 * FroodParameters - Testing it!
 *
 * @category   Test
 * @package    Frood
 * @subpackage Class
 * @author     Jens Riisom Schultz <jers@fynskemedier.dk>
 */
class FroodParametersTest extends PHPUnit_Framework_TestCase {
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp() {
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown() {
	}

	/**
	 * Test parameter get's.
	 *
	 * @return void
	 */
	public function testGetParameter() {
		$params = new FroodParameters(
			array(
				'on_your_face'   => 'bunched',
				'on_your_face2'  => 'The munchies',
				'IAmYourFather'  => 'Luke',
				'iAmYour_mother' => 'yo YO!',
				'a'              => 'A',
				'B'              => 'b',
			)
		);

		$this->assertEquals('bunched', $params->getOnYourFace());
		$this->assertEquals('The munchies', $params->getOnYourFace2());
		$this->assertEquals('Luke', $params->getIAmYourFather());
		$this->assertEquals('yo YO!', $params->getIAmYourMother());
		$this->assertEquals('A', $params->getA());
		$this->assertEquals('b', $params->getB());
	}

	/**
	 * Test parameter has's.
	 *
	 * @return void
	 */
	public function testHasParameter() {
		$params = new FroodParameters(
			array(
				'on_your_face'   => 'bunched',
				'on_your_face2'  => 'The munchies',
				'IAmYourFather'  => 'Luke',
				'iAmYour_mother' => 'yo YO!',
				'a'              => 'A',
				'B'              => 'b',
			)
		);

		$this->assertTrue($params->hasOnYourFace());
		$this->assertTrue($params->hasOnYourFace2());
		$this->assertTrue($params->hasIAmYourFather());
		$this->assertTrue($params->hasIAmYourMother());
		$this->assertTrue($params->hasA());
		$this->assertTrue($params->hasB());
		$this->assertFalse($params->hasOnyourFace());
		$this->assertFalse($params->hasNop());
		$this->assertFalse($params->hasStuff());
	}

	/**
	 * Test parameter default values.
	 *
	 * @return void
	 */
	public function testDefaultValues() {
		$params = new FroodParameters(
			array(
				'a' => 'A',
				'B' => 'b',
			)
		);

		$this->assertEquals('A', $params->getA('d'));
		$this->assertEquals('b', $params->getB('e'));
		$this->assertEquals('c', $params->getC('c'));
	}

	/**
	 * Test parameter values from GET/POST merging.
	 *
	 * @return void
	 */
	public function testGetPostMerging() {
		$_GET['lam']    = 'hej';
		$_GET['hest']   = 'not this';
		$_POST['lagen'] = 'strut';
		$_POST['hest']  = 'this';

		$params = new FroodParameters();

		$this->assertEquals('hej', $params->getLam());
		$this->assertEquals('strut', $params->getLagen());
		$this->assertEquals('this', $params->getHest());
	}

	/**
	 * Test parameter toString method.
	 *
	 * @return void
	 */
	public function testToString() {
		$params = new FroodParameters(
			array(
				'a' => 'A',
				'B' => 'b',
				's_m' => 'hej',
				'Nitrat' => 'uetUHet',
			)
		);

		$this->assertEquals('A=A, B=b, SM=hej, Nitrat=uetUHet', '' . $params);
	}

	/**
	 * Test parameter get with too many arguments.
	 *
	 * @expectedException RuntimeException
	 *
	 * @return void
	 */
	public function testTooManyArgumentsToGet() {
		$params = new FroodParameters();

		$params->getMechaSalmon('default', 'too many parameters');
	}

	/**
	 * Test parameter has with too many arguments.
	 *
	 * @expectedException RuntimeException
	 *
	 * @return void
	 */
	public function testTooManyArgumentsToHas() {
		$params = new FroodParameters();

		$params->hasMechaSalmon('too many parameters');
	}


	/**
	 * Test call to missing method.
	 *
	 * @expectedException RuntimeException
	 *
	 * @return void
	 */
	public function testMissingMethod() {
		$params = new FroodParameters();

		$params->superMechaSalmon('too many parameters');
	}

	/**
	 * Test parameter get with undefined parameter and no default.
	 *
	 * @expectedException RuntimeException
	 *
	 * @return void
	 */
	public function testNoDefaultValue() {
		$params = new FroodParameters();

		$params->getMechaSalmon();
	}
}
