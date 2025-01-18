<form method="GET" action="{{ route('pet.SearchByStatus') }}">
  <label for="status">Choose a status:</label>
  <select id="status" name="status[]" size="4" multiple>
    @foreach ($statuses as $status)
    <option value="{{ $status }}">{{ $status }}</option>
    @endforeach
  </select><br><br>
  <input type="submit">
</form>