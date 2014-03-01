<?php

use Mockery as m;

class TodoTaskTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->todo = new Tlr\Support\TodoTask;
	}

	public function testSomething()
	{
		dd($this->todo->getTodos());
	}

}
