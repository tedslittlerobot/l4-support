<!--     ///// HEADER SCRIPTS /////     -->
@foreach( Asset::getJs('header') as $asset )
	{{ HTML::element(
		'script',
		array_merge( [ 'src' => str_replace('{version}', array_get($asset->options, 'version'), $asset->url) ], $asset->attributes ),
		array_get( $asset->options, 'content', '' )
	) }}

@endforeach
