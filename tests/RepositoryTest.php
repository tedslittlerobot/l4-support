<?php

use Mockery as m;
use Illuminate\Support\Facades\Validator;

class RepositoryTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->repo = new Tlr\Support\Repository;
	}

	public function tearDown()
	{
		m::close();
	}

	public function testInput()
	{
		$this->repo->setInput( array('foo') );

		$this->assertEquals( array('foo'), $this->repo->getInput() );
	}

	public function testModel()
	{
		$this->repo->setModel( $model = m::mock('Illuminate\Database\Eloquent\Model') );

		$this->assertSame( $model, $this->repo->getModel() );
	}

	public function testRules()
	{
		$this->repo->setRules( array('foo') );

		$this->assertEquals( array('foo'), $this->repo->getRules() );
	}

	public function testAddRule()
	{
		$this->repo->addRule('foo', 'bar');

		$this->assertEquals(array('foo' => 'bar'), $this->repo->getRules());
	}

	public function testData()
	{
		$data = array('foo' => 'bar', 'baz' => 'woop');
		$this->repo->setData( array('foo' => 'bar', 'baz' => 'woop') );

		$this->assertEquals( $data, $this->repo->data() );
		$this->assertEquals( 'bar', $this->repo->data('foo') );
		$this->assertEquals( 'woop', $this->repo->data('baz') );
		$this->assertNull( $this->repo->data('ship') );
		$this->assertEquals( 'default', $this->repo->data('ship', 'default') );
	}
	public function testFiles()
	{
		$files = array('foo' => 'bar', 'baz' => 'woop');
		$this->repo->setFiles( array('foo' => 'bar', 'baz' => 'woop') );

		$this->assertEquals( $files, $this->repo->file() );
		$this->assertEquals( 'bar', $this->repo->file('foo') );
		$this->assertEquals( 'woop', $this->repo->file('baz') );
		$this->assertNull( $this->repo->file('ship') );
		$this->assertEquals( 'default', $this->repo->file('ship', 'default') );
	}

	public function testValidate()
	{
		$this->repo->setInput(array('foo'));
		$this->repo->setRules(array('bar'));

		Validator::shouldReceive('make')
			->with(array('foo'), array('bar'))
			->once()
			->andReturn( $val = m::mock() );

		$val->shouldReceive('getData')->once()->andReturn(array('data'));
		$val->shouldReceive('getFiles')->once()->andReturn(array('files'));
		$val->shouldReceive('passes')->once()->andReturn( true );
		$val->shouldReceive('getMessageBag')->once()->andReturn( array('errors') );

		$passes = $this->repo->validate();

		$this->assertEquals( array('data'), $this->repo->data() );
		$this->assertEquals( array('files'), $this->repo->file() );
		$this->assertTrue( $passes );
		$this->assertEquals( array('errors'), $this->repo->getErrors() );
	}

	public function testFill()
	{
		$this->repo->setData( array('foo') );
		$this->repo->setModel( $model = m::mock('Illuminate\Database\Eloquent\Model') );
		$model->shouldReceive('fill')->once()->with(array('foo'));

		$this->repo->fill();
	}


	public function testAssign()
	{
		$this->repo->setData( array('foo' => 'bar', 'baz' => 'woop', 'three' => 'four') );
		$this->repo->testModel = (object)array();

		$this->repo->assign(['foo', 'baz', 't-rex'], 'testModel');

		$this->assertEquals( (object)array('foo' => 'bar', 'baz' => 'woop'), $this->repo->testModel );
	}
}
