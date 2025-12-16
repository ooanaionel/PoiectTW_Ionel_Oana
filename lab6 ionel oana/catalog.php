<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Catalog Studenți</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 600px; margin: auto; }
        form { background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        input, select, button { display: block; width: 100%; margin: 10px 0; padding: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    </style>
</head>
<body>
<div class="container">
    <h2>Adaugă Student Nou</h2>
    <form id="studentForm">
        <input type="text" id="nume" placeholder="Nume complet" required>
        <select id="anul" required>
            <option value="">Alege anul (1-4)</option>
            <option value="1">Anul 1</option>
            <option value="2">Anul 2</option>
            <option value="3">Anul 3</option>
            <option value="4">Anul 4</option>
        </select>
        <input type="number" id="media" step="0.01" min="1" max="10" placeholder="Media generală" required>
        <button type="submit">Salvează Student</button>
    </form>

    <h2>Listă Studenți</h2>
    <table>
        <thead>
            <tr><th>Nume</th><th>An</th><th>Medie</th></tr>
        </thead>
        <tbody id="studentTableBody">
            </tbody>
    </table>
</div>

<script>
    // 3. Funcție pentru a încărca lista de studenți
    async function incarcaStudenti() {
        const response = await fetch('api_studenti.php');
        const studenti = await response.json();
        const tbody = document.getElementById('studentTableBody');
        tbody.innerHTML = '';

        studenti.forEach(s => {
            tbody.innerHTML += `<tr>
                <td>${s.nume}</td>
                <td>${s.anul}</td>
                <td>${s.media}</td>
            </tr>`;
        });
    }

    // 4. Trimiterea formularului prin fetch POST
    document.getElementById('studentForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            nume: document.getElementById('nume').value,
            anul: document.getElementById('anul').value,
            media: document.getElementById('media').value
        };

        const response = await fetch('api_studenti.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            document.getElementById('studentForm').reset();
            incarcaStudenti(); // 5. Actualizare în timp real
        } else {
            alert('Eroare: ' + result.error);
        }
    });

    // Încărcare inițială
    incarcaStudenti();
</script>
</body>
</html>