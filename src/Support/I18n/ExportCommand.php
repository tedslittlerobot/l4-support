<?php namespace Tlr\Support\I18n;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lang:export';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Export and format translatable lines.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$data = $this->getData( $this->getLanguages() );

		$this->{'format' . ucfirst( $this->option('format') )}( $this->compress( $data ) );
	}

	/**
	 * Get the given languages' translation data
	 * @param  array $languages
	 * @return array
	 */
	public function getData( $languages )
	{
		$data = array();

		foreach ((array)$languages as $language)
		{
			$data[$language] = array_dot( $this->getTranslations( $this->getLanguageNamespaces( $language ), $language ) );
		}

		return $data;
	}

	/**
	 * Get all language slugs
	 * @return array
	 */
	public function getLanguages()
	{
		$languages = array();

		foreach ($this->laravel['files']->directories(app_path( 'lang' )) as $folder)
		{
			$languages[] = basename($folder);
		}

		return $languages;
	}

	/**
	 * List the contents of the en directory
	 * @author Stef Horner       (shorner@wearearchitect.com)
	 * @return array
	 */
	public function getLanguageNamespaces( $language = 'en' )
	{
		$namespaces = array();

		foreach( $this->laravel['files']->files(app_path( "lang/{$language}" )) as $file )
		{
			$namespaces[] = preg_replace('/\.php$/', '', basename($file));
		}

		return $namespaces;
	}

	/**
	 * Get translations for the given namespace(s)
	 * @author Stef Horner     (shorner@wearearchitect.com)
	 * @param  array   $namespaces
	 * @return array
	 */
	public function getTranslations( $namespaces, $language = 'en' )
	{
		$translations = array();

		foreach ((array) $namespaces as $namespace)
		{
			$translations[$namespace] = $this->laravel['translator']->get($namespace, array(), $language);
		}

		return $translations;
	}

	/**
	 * Get the header columns for the export
	 * @return array
	 */
	public function getHeaders()
	{
		return array_merge(['key'], $this->getLanguages());
	}

	/**
	 * Compress the array into [ 'key', 'value', 'value'... ] format
	 * @author Stef Horner (shorner@wearearchitect.com)
	 * @param  array   $data
	 * @return array
	 */
	public function compress( $data )
	{
		$compressed = array();

		$xi = 1;

		foreach($data as $languageKey => $translations)
		{
			foreach ($translations as $key => $value)
			{
				if ( !isset($compressed[$key]) )
				{
					$compressed[$key] = array($key);
				}

				$compressed[$key][$xi] = $value;
			}

			$xi++;
		}

		return $compressed;
	}

	/**
	 * Format the output as CSV
	 * @author Stef Horner (shorner@wearearchitect.com)
	 * @param  array   $data
	 */
	public function formatCsv( $data )
	{
		$output = '';

		/// HEADERS ///

		$headers = $this->getHeaders();

		$cols = count( $this->getHeaders() );

		foreach( $headers as $key => $value )
		{
			$headers[$key] = "\"{$value}\"";
		}

		$this->line( implode(',', $headers) );

		/// VALUES ///

		foreach ($data as $row)
		{
			$rowData = array();

			for ($xi = 0; $xi < $cols; $xi++)
			{
				$cell = array_get( $row, $xi );
				$rowData[] = "\"$cell\"";
			}

			$this->line( implode(', ', $rowData) );
		}
	}

	/**
	 * Format the data as a table
	 * @author Stef Horner (shorner@wearearchitect.com)
	 * @param  array   $data
	 */
	public function formatTable( $data )
	{
		$table = $this->getHelperSet()->get('table');

		$table->setHeaders( $this->getHeaders() );
		$table->setRows( $data );

		$table->render( $this->getOutput() );
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('format', 'f', InputOption::VALUE_OPTIONAL, 'The format to display. table | csv', 'table'),
		);
	}

}
