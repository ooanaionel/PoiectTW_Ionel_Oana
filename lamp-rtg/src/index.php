<?php
// Se include fișierul de configurare. 
// Acest lucru va iniția conexiunea la baza de date prin variabila $conn.
require_once 'config.php'; 
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Acasă - FC Argeș Basketball</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
        <div class="brand">
            <img src="fc_arges.png" style="width:90px;border-radius:5px;margin-bottom:5px;">
            <div>
                <div style="font-weight:700">FC Argeș Basketball</div>
                <div class="muted" style="font-size:13px">Echipa oficială</div>
            </div>
        </div>
        <nav>
            <a href="index.php">Acasă</a>
            <a href="pagina_jucatori.php">Jucători</a>
            <a href="pagina_staff.php">Staff</a> 
            <a href="pagina_calendar.php">Calendar</a>
            <a href="pagina_contact.php">Contact</a>
        </nav>
    </header>

    <section class="hero">
        <h1>Bine ați venit la FC Argeș Basketball</h1>
        
        <?php
        // Aici puteți adăuga un test rapid al conexiunii (vizibil doar în dezvoltare)
        if (isset($conn) && $conn) {
            echo '<p style="color:green; font-weight:bold;">Conexiunea la baza de date a reușit!</p>';
        } else {
            echo '<p style="color:red; font-weight:bold;">Conexiunea la baza de date a eșuat. Verificați config.php.</p>';
        }

        // Puteți începe să interogați baza de date pentru a afișa știri, statistici etc.
        ?>

        <p>Echipa oficială — rezultate, meciuri și noutăți. Susține-ne la următorul meci!</p>
        <a class="cta" href="pagina_calendar.php">Următorul meci: Sâmbătă • 19:00</a>

        <div class="stats">
            <div class="stat"><small>Sezon</small><b>2025/26</b></div>
            <div class="stat"><small>Loc</small><b>3</b></div>
            <div class="stat"><small>Ultimul meci</small><b>V</b></div>
        </div>
    </section>
    
    <footer>© 2025 FC Argeș Basketball — Toate drepturile rezervate.</footer>
</div>
</body>
</html>

<?php
// 4. Închide conexiunea la sfârșitul paginii
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>