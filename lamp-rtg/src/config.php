<?php
// Detalii de conexiune la baza de date (Preluate din fișierul furnizat)
define('DB_HOST', 'db'); 
define('DB_USER', 'admin'); 
define('DB_PASS', 'oana'); 
define('DB_NAME', 'proiecttw'); 

// 1. Crearea conexiunii
// Se folosește mysqli_connect pentru a stabili conexiunea la baza de date
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 2. Verificarea conexiunii
if ($conn === false) {
    // În caz de eroare, se oprește execuția și se afișează mesajul de eroare
    die("EROARE: Nu s-a putut conecta la baza de date. " . mysqli_connect_error());
}

// 3. Setează setul de caractere la UTF-8 (esențial pentru diacritice)
if (!mysqli_set_charset($conn, "utf8")) {
    // Afișează eroare dacă setarea charset-ului eșuează
    printf("Eroare la încărcarea setului de caractere utf8: %s\n", mysqli_error($conn));
    exit();
}

// De acum, variabila $conn va fi folosită în alte fișiere PHP (ex: index.php, jucator_profil.php)
?>