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
		$this->bladeTags();
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

		$this->app['validator']->extend('false', function($attribute, $value, $parameters)
		{
			return false;
		});
	}

	/**
	 * Register some general blade template tags
	 */
	public function bladeTags()
	{
		$blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

		/**
		 * @firsterror('key')
		 *   {{ $message }}
		 * @endfirsterror
		 */
		$blade->extend(function($view, $compiler)
		{
			$pattern = $compiler->createMatcher('firsterror');
			$closingPattern = $compiler->createPlainMatcher('endfirsterror');

			$view = preg_replace($pattern, '$1<?php if($errors->has($2)): ; $message = $errors->first($2); ?>', $view);

			$view = preg_replace($closingPattern, '$1<?php endif; ?>', $view);

			return $view;
		});

		/**
		 * @errors('key')
		 *   <li> {{$message}} </li>
		 * @enderrors
		 */
		$blade->extend(function($view, $compiler)
		{
			$pattern = $compiler->createMatcher('errors');
			$closingPattern = $compiler->createPlainMatcher('enderrors');

			$view = preg_replace($pattern, '$1<?php foreach($errors->get($2) as $message): ?>', $view);

			$view = preg_replace($closingPattern, '$1<?php endforeach; ?>', $view);

			return $view;
		});

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {}

}
