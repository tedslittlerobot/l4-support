<?php namespace Tlr\Support\I18n;

use View;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lang:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import csv langs to php array files.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Lang Import');

		$data = $this->hydrateArrays( $this->reformArrays( $this->parseFile( $this->argument('input') ) ) );

		$this->writeLangs( $data, $this->argument('output') );

		$this->info('Totally Done.');
	}

	/**
	 * Parse the input file
	 * @param  string $path
	 * @return array
	 */
	public function parseFile( $path )
	{
		$file = $this->laravel['files']->get(base_path( $path ));

		$rows = explode(PHP_EOL, $file);

		$output = array();

		foreach ($rows as $key => $row)
		{
			if ($row)
			{
				$output[$key] = str_getcsv($row);
			}
		}

		return $output;
	}

	/**
	 * Separate the one dimentional, n-length arrays into n flattened arrays of rows
	 * @param  array $rows
	 * @return array
	 */
	public function reformArrays( $rows )
	{
		$output = array();

		$headers = array_splice($rows, 0, 1)[0];

		for ($xi=1; $xi < count($headers); $xi++)
		{
			$lang = array();

			foreach ($rows as $key => $value)
			{
				$lang[$value[0]] = array_get($value, $xi);
			}

			$output[$headers[$xi]] = $lang;
		}

		return $output;
	}

	/**
	 * hydrate a collection of arrays
	 * @param  array $langs
	 * @return array
	 */
	public function hydrateArrays( $langs )
	{
		$output = array();

		foreach ($langs as $key => $array)
		{
			$output[$key] = $this->hydrateArray( $array );
		}

		return $output;
	}

	/**
	 * Hydrate a single array from flattened dot notation
	 * @param  array $array
	 * @return array
	 */
	public function hydrateArray( $array )
	{
		$output = array();

		foreach ($array as $key => $value)
		{
			$hydratedValue = $this->unFlatten( explode('.', $key), $value );

			$output = array_merge_recursive( $output, $hydratedValue );
		}

		return $output;
	}

	/**
	 * Unflatten a single key value pair
	 * @param  array $keys
	 * @param  mixed $value
	 * @return array
	 */
	public function unFlatten( $keys, $value )
	{
		$key = array_shift($keys);
		if ( empty($keys) )
		{
			return array( $key => $value );
		}

		return array( $key => $this->unFlatten($keys, $value) );
	}

	/**
	 * Write the lang files to the given directory
	 * @param  array $langs
	 * @param  string $output
	 */
	public function writeLangs($langs, $output)
	{
		$path = base_path($output);
		$files = $this->laravel['files'];

		$files->makeDirectory($path, 0777, true, true);
		$this->info('Creating Directories...');

		foreach ($langs as $slug => $data) {
			$this->info("Unpacking Language: $slug");

			$files->makeDirectory( "{$path}/{$slug}" );

			foreach ($data as $key => $items)
			{
				$this->comment("  - Writing Lang Namespace: $key");
				$files->put( "{$path}/{$slug}/{$key}.php", View::make('support.lang', ['items' => $items])->render() );
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
			[ 'input', InputArgument::REQUIRED, 'The input file - relative to the project dir.' ],
			[ 'output', InputArgument::OPTIONAL, 'The directory to output to - relative to the project dir.', 'lang' ],
		);
	}
}
