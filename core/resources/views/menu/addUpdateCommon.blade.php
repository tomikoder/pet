@if($errors->any())
@foreach($errors->keys() as $key)
    @if(str_starts_with($key, 'id_'))
        <p>Wiersz {{ substr($key, 3) }} ma błąd: {{ $errors->first($key) }}</p>
    @elseif(str_starts_with($key, 'name_'))
        <p>Wiersz {{ substr($key, 5) }} ma błąd: {{ $errors->first($key) }}</p>
    @endif
@endforeach
@endif
@include('menu.scriptToAddCells')