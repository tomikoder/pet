<script>
    let counter = document.querySelectorAll('.id').length;

    function addRow() {
        counter++;
        const tableBody = document.getElementById('tableBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${counter}</td> <!-- Numer wiersza -->
            <td class="id"><input type="text" name="id.${counter}" placeholder="Wpisz ID"></td>
            <td><input type="text" name="name.${counter}" placeholder="Wpisz nazwę"></td>
            <td><button type="button" onclick="removeRow(this)">Usuń</button></td> <!-- Przycisk usuwania -->
        `;
        tableBody.appendChild(newRow);
    }

    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
        // Zaktualizuj numerację wierszy po usunięciu
        updateRowNumbers();
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
        });
        counter = rows.length; // Zaktualizuj licznik wierszy
    }
</script>