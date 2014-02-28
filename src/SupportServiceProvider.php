<?php namespace Tlr\Support;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SupportServiceProvider extends ServiceProvider {

	/**
	 * Boot this module
	 */
	public function boot()
	{
		$this->htmlMacros();
		$this->validations();
	}

	/**
	 * Register some HTML macros
	 */
	public function htmlMacros()
	{
		/**
		 * Element
		 *
		 * Constructs an element from scratch, with the given element (defaults to div), and attributes
		 *
		 * If content is provided, it will be inserted, and a matching closing tag generated
		 *
		 * @var string
		 */
		$this->app['html']->macro('element', function ( $element = 'div', $attributes = array(), $content = null )
		{
			foreach ($attributes as $attribute => $values)
			{
				$attributes[$attribute] = implode(' ', (array)$values);
			}

			$html = "<{$element}" . $this->app['html']->attributes( $attributes ) . ">";

			if ( ! is_null($content) )
			{
				$html .= "{$content}</{$element}>";
			}

			return $html;
		});
	}

	/**
	 * Add some validations
	 * @todo move somewhere more sensible
	 */
	public function validations()
	{
		$this->app['validator']->extend('slug', function($attribute, $value, $parameters)
		{
			return $value == Str::slug($value);
		});

		$this->app['validator']->extend('json', function($attribute, $value, $parameters)
		{
			json_decode($value);
			return (json_last_error() == JSON_ERROR_NONE);
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {}

}
