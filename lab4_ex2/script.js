document.addEventListener('DOMContentLoaded', () => {
    // 1. Obținerea referințelor la elementele din DOM
    const detaliiDiv = document.getElementById('detalii');
    const btnDetalii = document.getElementById('btnDetalii');
    const dataProdusSpan = document.getElementById('dataProdus');

    // Tabloul cu numele lunilor în limba română
    const luniInRomana = [
        "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
        "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
    ];

    // --- LOGICA DE INIȚIALIZARE (La încărcarea paginii) ---

    // a) Ascunderea secțiunii de detalii inițial
    detaliiDiv.classList.add('ascuns');

    // b) Obținerea și formatarea datei curente
    const dataCurenta = new Date();
    const zi = dataCurenta.getDate();
    const lunaIndex = dataCurenta.getMonth();
    const an = dataCurenta.getFullYear();
    
    // Formatarea datei ca șir de caractere: "18 Noiembrie 2025"
    const dataFormatata = `${zi} ${luniInRomana[lunaIndex]} ${an}`;

    // c) Injectarea datei formatate în elementul span
    dataProdusSpan.textContent = dataFormatata;


    // --- LOGICA DINAMICĂ (La click pe buton) ---

    btnDetalii.addEventListener('click', () => {
        // 2. Comutarea vizibilității prin clasa .ascuns
        detaliiDiv.classList.toggle('ascuns');

        // 3. Modificarea textului butonului în funcție de starea vizibilității
        
        // Verificăm dacă detaliile sunt vizibile (dacă clasa 'ascuns' NU există)
        if (!detaliiDiv.classList.contains('ascuns')) {
            // Detaliile sunt vizibile
            btnDetalii.textContent = 'Ascunde detalii';
        } else {
            // Detaliile sunt ascunse
            btnDetalii.textContent = 'Afișează detalii';
        }
    });
});