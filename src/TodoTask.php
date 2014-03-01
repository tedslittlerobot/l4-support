<?php namespace Tlr\Auth;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TodoCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'todo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scan the project for todo tags';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		//
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(

		);
	}

}
