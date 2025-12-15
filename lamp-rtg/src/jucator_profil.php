<?php
// 1. Incarcă setările de conexiune
require_once "config.php";

$jucator = null;
$eroare = "";

// 2. Verifică dacă ID-ul jucătorului a fost furnizat în URL
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    
    // Pregătirea interogării SQL
    // Se folosește o interogare pregătită (prepared statement) pentru a preveni atacurile de tip SQL Injection!
    $sql = "SELECT nume, prenume, pozitie, numar, poza, istoric FROM jucatori WHERE jucator_id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Leagă variabila de tip întreg (i) la placeholder (?)
        $param_id = trim($_GET["id"]);
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Execută interogarea
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                // Extrage rândul de date în variabila $jucator
                $jucator = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                $eroare = "Nu s-a găsit niciun jucător cu ID-ul specificat.";
            }
        } else {
            $eroare = "A apărut o eroare la executarea interogării.";
        }

        // Închide statement-ul
        mysqli_stmt_close($stmt);
    }
} else {
    // Dacă nu a fost furnizat niciun ID
    $eroare = "ID-ul jucătorului nu a fost specificat în URL.";
}

// 3. Închide conexiunea
mysqli_close($link);
?>

<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8">
  <title><?php echo $jucator ? htmlspecialchars($jucator['nume'] . ' ' . $jucator['prenume']) : 'Jucător'; ?> — FC Argeș Basketball</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <header>
    <div class="brand"><img src="fc_arges.png" style="width:90px;border-radius:5px;margin-bottom:5px;"></img><div><strong>FC Argeș</strong></div></div>
    <nav>
      <a href="index.html">Acasă</a>
      <a href="pagina_jucatori.php">Jucători</a>
      <a href="pagina_staff.php">Staff</a>
      <a href="pagina_calendar.php">Calendar</a>
      <a href="pagina_contact.php">Contact</a>
    </nav>
  </header>

  <section class="hero">
    <?php if ($jucator): ?>
        <img src="<?php echo htmlspecialchars($jucator['poza']); ?>" alt="<?php echo htmlspecialchars($jucator['nume'] . ' ' . $jucator['prenume']); ?>" style="width:180px;border-radius:10px;margin-bottom:10px;">
        
        <h2><?php echo htmlspecialchars($jucator['nume'] . ' ' . $jucator['prenume']); ?></h2>
        
        <p class="muted"><?php echo htmlspecialchars($jucator['pozitie']); ?> • #<?php echo htmlspecialchars($jucator['numar']); ?></p>
        
        <p><?php echo nl2br(htmlspecialchars($jucator['istoric'])); ?></p>
        
    <?php else: ?>
        <h2>Eroare la încărcarea profilului</h2>
        <p><?php echo $eroare; ?></p>
    <?php endif; ?>
  </section>

  <footer>© 2025 FC Argeș Basketball</footer>
</div>
</body>
</html>