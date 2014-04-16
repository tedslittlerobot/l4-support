<!--     ///// STYLES /////     -->
@foreach( Asset::getStyles() as $asset )
	{{ HTML::element(
		'link',
		array_merge( [
			'href' => preg_replace('{version}', array_get($asset->options, 'version'), $asset->url),
			'rel' => 'stylesheet',
			'type' => 'text/css'
			], $asset->attributes )
	) }}

@endforeach
