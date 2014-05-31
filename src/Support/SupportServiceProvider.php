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
		 *
		 * @errors('key')
		 *   <li> {{$message}} </li>
		 * @enderrors
		 */
		$blade->extend(function($view, $compiler)
		{
			// @firsterror('key')
			$view = preg_replace(
				$compiler->createMatcher('firsterror'),
				'$1<?php if($errors->has($2)): ; $message = $errors->first($2); ?>',
				$view
			);

			// @endfirsterror
			$view = preg_replace(
				$compiler->createPlainMatcher('endfirsterror'),
				'$1<?php endif; ?>',
				$view
			);

			// @errors('key')
			$view = preg_replace(
				$compiler->createMatcher('errors'),
				'$1<?php foreach($errors->get($2) as $message): ?>',
				$view
			);

			// @enderrors
			$view = preg_replace(
				$compiler->createPlainMatcher('enderrors'),
				'$1<?php endforeach; ?>',
				$view
			);

			return $view;
		});

		/**
		 * @notice
		 *   {{ $message }}
		 * @endnotice
		 *
		 * @notices
		 *   <li> {{$message}} </li>
		 * @endnotices
		 */
		$blade->extend(function($view, $compiler)
		{
			// @notice
			$view = preg_replace(
				$compiler->createPlainMatcher('firstnotice'),
				'$1<?php if(count((array)Session::get("notices", array())) > 0): ; list($message) = (array)Session::get("notices", array()); ?>',
				$view
			);

			// @endnotice
			$view = preg_replace(
				$compiler->createPlainMatcher('endfirstnotice'),
				'$1<?php endif; ?>',
				$view
			);

			// @notices
			$view = preg_replace(
				$compiler->createPlainMatcher('notices'),
				'$1<?php foreach((array)Session::get("notices", array()) as $message): ?>',
				$view
			);

			// @endnotices
			$view = preg_replace(
				$compiler->createPlainMatcher('endnotices'),
				'$1<?php endforeach; ?>',
				$view
			);

			return $view;
		});

		/**
		 * @switch($value)
		 * 	@case(1)
		 * 	@default
		 * 	@break
		 * @endswitch
		 */
		$blade->extend(function($view, $compiler)
		{
			foreach (['switch', 'case'] as $tag)
			{
				$view = preg_replace(
					$compiler->createMatcher( $tag ),
					'$1<?php ' . $tag . '($2): ?>',
					$view
				);
			}

			foreach (['break', 'default', 'endswitch'] as $tag)
			{
				$view = preg_replace(
					$compiler->createPlainMatcher( $tag ),
					'$1<?php ' . $tag . '; ?>',
					$view
				);
			}

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
