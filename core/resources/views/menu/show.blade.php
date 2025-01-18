<p>Id: {{ $item['id'] ?? "" }}</p>
<p>Category: {{ $item['category']['name'] ?? "" }}</p>
<p>Name: {{ $item['name'] ?? "" }}</p>
<p>Tags</p>
<ul> 
@foreach ($item['tags'] as $tag)
 <li>{{ $tag['name'] }}</li>
@endforeach
</ul>
<p>Status: {{ $item['status'] }} </p>
