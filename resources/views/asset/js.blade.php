
@foreach( Asset::getJs() as $asset )
	<script src="{{ $asset->url }}"></script>
@endforeach
