<form method="POST" action="{{ route('pet.updateAction', ['id' => $item['id']]) }}">
  @csrf
  <label for="id">Pet id:</label><br>
  <input type="text" id="id" name="id" value="{{ $item['id'] }}" readonly><br>
  @if ($errors->has('id'))
  <div style="color: red;" class="error">{{ $errors->first('id') }}</div>
  @endif
  <label for="category_name">Category name:</label><br>
  <input type="text" id="category_name" name="category_name" value="{{ $item['category']['name'] }}"><br>
  @if ($errors->has('category_name'))
  <div style="color: red;" class="error">{{ $errors->first('category_name') }}</div>
  @endif
  <label for="category_id">Category ID:</label><br>
  <input type="text" id="category_id" name="category_id" value="{{ $item['category']['id'] }}"><br>
  @if ($errors->has('category_id'))
  <div style="color: red;" class="error">{{ $errors->first('category_id') }}</div>
  @endif
  <label for="pet_name">Pet name:</label><br>
  <input type="text" id="pet_name" name="pet_name" value="{{ $item['name'] }}"><br>
  @if ($errors->has('pet_name'))
  <div style="color: red;" class="error">{{ $errors->first('pet_name') }}</div>
  @endif
  <label for="status">Status name:</label><br>
  <input type="text" id="status" name="status" value="{{ $item['status'] }}"><br>
  @if ($errors->has('status'))
  <div style="color: red;" class="error">{{ $errors->first('status') }}</div>
  @endif
  <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
        </tr>
    </thead>
    <tbody id="tableBody">
            @foreach ($item['tags'] as $tag)
            <tr>
                <td class="id"><input type="text" name="{{ 'id_' . $loop->iteration }}" placeholder="Wpisz ID" value="{{ $tag['id'] }}"></td>
                <td><input type="text" name="{{ 'name_' . $loop->iteration }}" placeholder="Wpisz nazwę" value ="{{ $tag['name'] }}"></td>
            </tr>
            @endforeach
    </tbody>
</table>
    <button type="button" onclick="addRow()">Dodaj wiersz</button>
  <button type="submit">Send</button>
</form>
@if($errors->any())
    @foreach($errors->keys() as $key)
        @if(str_starts_with($key, 'id_'))
            <p>{{ $key }} ma błąd: {{ $errors->first($key) }}</p>
        @elseif(str_starts_with($key, 'name_'))
            <p>{{ $key }} ma błąd: {{ $errors->first($key) }}</p>
        @endif
    @endforeach
@endif
<script>
    let counter = document.querySelectorAll('.id').length;
    function addRow() {
        counter++;
        const tableBody = document.getElementById('tableBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="id"><input type="text" name="id.${counter}" placeholder="Wpisz ID"></td>
            <td><input type="text" name="name.${counter}" placeholder="Wpisz nazwę"></td>
        `;
        tableBody.appendChild(newRow);
    }
</script> 
