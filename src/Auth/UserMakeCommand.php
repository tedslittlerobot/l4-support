<?php namespace Tlr\Auth;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Tlr\Auth\UserRepository;

class UserMakeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'user:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Make a new roche user';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct( UserRepository $repo )
	{
		parent::__construct();

		$this->repo = $repo;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		/**
		 * Spin through the arguments and options defined in getArguments and
		 * getOptions, and pass those through to the repository
		 */
		// @todo: there's probably a more efficient / nicer way to do this...!
		$args = array();
		foreach ($this->getArguments() as $arg) {
			$args[] = $arg[0];
		}

		$opts = array();
		foreach ($this->getOptions() as $opt) {
			$opts[] = $opt[0];
		}

		$this->repo->setInput(
			array_merge(
				array_only( $this->argument(), $args ),
				[ 'password_confirmation' => $this->argument('password') ],
				array_only( $this->option(), $opts )
			)
		);

		if ( $user = $this->repo->create() )
		{
			$this->info("User created successfully: {$user->name}");
		}
		else
		{
			foreach ($this->repo->getErrors()->all() as $error)
			{
				$this->error( $error );
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			[ 'firstname', InputArgument::REQUIRED, 'You know what a first name is.' ],
			[ 'lastname', InputArgument::REQUIRED, 'You know what a last name is.' ],
			[ 'email', InputArgument::REQUIRED, 'You should really know what an email address is.' ],
			[ 'password', InputArgument::REQUIRED, 'The carrot.' ],
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('permissions', 'p', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Permissions to give the new user.', null),
		);
	}

}
