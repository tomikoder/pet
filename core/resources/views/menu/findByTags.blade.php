<form action="{{ route('pet.SearchByTags') }}" method="GET">
    <div id="dynamic-fields">
        <div class="form-group">
            <label for="input-1">Wartość 1:</label>
            <input type="text" name="tags[]" id="input-1" class="form-control" placeholder="Wpisz wartość">
        </div>
    </div>

    <button type="button" id="add-field">Dodaj kolejne pole</button>
    <br><br>
    <button type="submit">Wyślij formularz</button>
</form>

<script>
    let fieldCount = 1; // Licznik pól

    document.getElementById('add-field').addEventListener('click', () => {
        fieldCount++;
        const dynamicFields = document.getElementById('dynamic-fields');

        const newField = document.createElement('div');
        newField.classList.add('form-group');
        newField.innerHTML = `
            <label for="input-${fieldCount}">Wartość ${fieldCount}:</label>
            <input type="text" name="tags[]" id="input-${fieldCount}" class="form-control" placeholder="Wpisz wartość">
        `;
        dynamicFields.appendChild(newField);
    });
</script>