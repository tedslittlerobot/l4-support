<!--     ///// STYLES /////     -->
@foreach( Asset::getStyles() as $asset )
	{{ HTML::element(
		'link',
		array_merge( [
			'href' => str_replace('{version}', array_get($asset->options, 'version'), $asset->url),
			'rel' => 'stylesheet',
			'type' => 'text/css'
			], $asset->attributes )
	) }}

@endforeach
