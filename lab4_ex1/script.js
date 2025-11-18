document.addEventListener('DOMContentLoaded', () => {
    // 1. Obținerea referințelor la elementele din DOM
    const inputActivitate = document.getElementById('inputActivitate');
    const btnAdauga = document.getElementById('btnAdauga');
    const listaActivitati = document.getElementById('listaActivitati');

    // Tabloul cu numele lunilor în limba română
    const luniInRomana = [
        "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
        "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
    ];

    // 2. Adăugarea evenimentului 'click' pe buton
    btnAdauga.addEventListener('click', adaugaActivitate);

    // Permite adăugarea și la apăsarea tastei Enter în câmpul de input
    inputActivitate.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            adaugaActivitate();
        }
    });

    /**
     * Funcția principală pentru adăugarea unei activități.
     */
    function adaugaActivitate() {
        // Citirea textului introdus și eliminarea spațiilor albe de la capete
        const textActivitate = inputActivitate.value.trim();

        // 3. Verificarea dacă textul nu este gol
        if (textActivitate === "") {
            alert("Vă rugăm introduceți o activitate!");
            return; // Oprește execuția dacă inputul este gol
        }

        // 4. Crearea unui nou element <li>
        const nouElementLi = document.createElement('li');

        // 5. Obținerea și formatarea datei curente
        const dataCurenta = new Date();
        const zi = dataCurenta.getDate(); // Ziua din lună (număr)
        const lunaIndex = dataCurenta.getMonth(); // Indexul lunii (0-11)
        const an = dataCurenta.getFullYear(); // Anul

        // Obținerea numelui lunii în format text
        const lunaText = luniInRomana[lunaIndex];
        
        // 6. Formatarea textului final
        // Exemplu: Activitate – adăugată la: 16 Noiembrie 2025
        nouElementLi.innerHTML = `
            <strong>${textActivitate}</strong> – adăugată la: ${zi} ${lunaText} ${an}
        `;

        // 7. Adăugarea elementului <li> în lista <ul>
        listaActivitati.appendChild(nouElementLi);

        // 8. Golirea câmpului de input
        inputActivitate.value = '';
        inputActivitate.focus(); // Mută focusul înapoi pe input
    }
});