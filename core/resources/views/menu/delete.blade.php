<form method="POST" action="{{ route('pet.deleteAction') }}">
    @csrf
    @method('DELETE')
    <label for="id">Pet id:</label><br>
    <input type="text" id="id" name="id"><br>
    <button type="submit">Send</button>
</form> 