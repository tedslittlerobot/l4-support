<?php

class ImportCommandTest extends \PHPUnit_Framework_TestCase {

	public function setup()
	{
		$this->cmd = new Tlr\Support\I18n\ImportCommand;
	}

	public function testArrayHydrateArray()
	{
		$input = [ 'one' => 'two' ];
		$result = $this->cmd->hydrateArray( $input );

		$this->assertEquals($input, $result);

		$input = [ 'one.two.three' => 'woop' ];
		$value = ['one' => ['two' => ['three' => 'woop']]];

		$result = $this->cmd->hydrateArray( $input );

		$this->assertEquals( $value, $result );

		$input = [ 'one.two.three' => 'woop', 'one.four' => 'foo' ];
		$value = ['one' => ['two' => ['three' => 'woop'], 'four' => 'foo']];

		$result = $this->cmd->hydrateArray( $input );

		$this->assertEquals( $value, $result );
	}


	public function testArrayHydrateArrays()
	{
		$input = [
			'one' => ['test' => 'foo'],
			'two' => ['test' => 'bar'],
		];
		$result = $this->cmd->hydrateArrays( $input );

		$this->assertEquals($input, $result);
	}

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testDescartes()
	{
		$this->assertTrue( true );
	}

}
