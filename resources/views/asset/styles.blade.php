
@foreach( Asset::getStyles() as $asset )
	<link rel="stylesheet" type="text/css" href="{{ $asset->url }}">
@endforeach
