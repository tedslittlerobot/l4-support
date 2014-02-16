<?php namespace Tlr\Support;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider {

	/**
	 * Boot this module
	 */
	public function boot()
	{
		$this->htmlMacros();
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
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {}

}
