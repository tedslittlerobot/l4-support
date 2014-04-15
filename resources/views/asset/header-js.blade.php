
@foreach( Asset::getJs('header') as $asset )
	<script src="{{ $asset->url }}"></script>
@endforeach
