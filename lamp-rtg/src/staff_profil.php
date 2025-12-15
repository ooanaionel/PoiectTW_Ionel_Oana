<?php
// Include fișierul de configurare și inițiază conexiunea la baza de date ($conn)
require_once "config.php";

$membru_staff = null;
$eroare = "";

// 1. Verifică dacă ID-ul a fost furnizat în URL
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    
    // Interogare SQL: Se folosește o interogare pregătită (prepared statement) pentru securitate
    $sql = "SELECT nume, prenume, post, varsta, data_nasterii, poza, istoric FROM staff_tehnic WHERE staff_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $param_id = trim($_GET["id"]);
        mysqli_stmt_bind_param($stmt, "i", $param_id); // "i" = integer
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                $membru_staff = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                $eroare = "Nu s-a găsit niciun membru al staff-ului cu ID-ul specificat.";
            }
        } else {
            $eroare = "A apărut o eroare la executarea interogării.";
        }

        mysqli_stmt_close($stmt);
    }
} else {
    $eroare = "ID-ul membrului staff-ului nu a fost specificat în URL.";
}

// 2. Închide conexiunea
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8">
  <title id="pageTitle"><?php echo $membru_staff ? htmlspecialchars($membru_staff['nume'] . ' ' . $membru_staff['prenume']) . ' (' . htmlspecialchars($membru_staff['post']) . ')' : 'Staff Member'; ?> — FC Argeș Basketball</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <header>
    <div class="brand"><img src="fc_arges.png" style="width:90px;border-radius:5px;margin-bottom:5px;"></img><div><strong>FC Argeș</strong></div></div>
    <nav>
      <a href="index.php">Acasă</a>
      <a href="pagina_jucatori.php">Jucători</a>
      <a href="pagina_staff.php">Staff</a>
      <a href="pagina_calendar.php">Calendar</a>
      <a href="pagina_contact.php">Contact</a>
    </nav>
  </header>

  <section class="hero">
    <?php if ($membru_staff): ?>
        <img src="<?php echo htmlspecialchars($membru_staff['poza']); ?>" alt="<?php echo htmlspecialchars($membru_staff['nume'] . ' ' . $membru_staff['prenume']); ?>" style="width:180px;border-radius:10px;margin-bottom:10px;">
        
        <h2><?php echo htmlspecialchars($membru_staff['nume'] . ' ' . $membru_staff['prenume']); ?></h2>
        
        <p class="muted"><?php echo htmlspecialchars($membru_staff['post']); ?> 
            <?php 
                // Afișează Vârsta sau Data Nașterii, dacă sunt disponibile
                if (!empty($membru_staff['varsta'])) {
                    echo ' • ' . htmlspecialchars($membru_staff['varsta']) . ' ani';
                } elseif (!empty($membru_staff['data_nasterii'])) {
                    echo ' • Născut la: ' . htmlspecialchars(date('d.m.Y', strtotime($membru_staff['data_nasterii'])));
                }
            ?>
        </p>
        
        <h3>Istoric și Realizări</h3>
        <p style="text-align: left; max-width: 600px; margin: 0 auto;"><?php echo nl2br(htmlspecialchars($membru_staff['istoric'])); ?></p>
        
    <?php else: ?>
        <h2>Eroare la încărcarea profilului</h2>
        <p><?php echo $eroare; ?></p>
    <?php endif; ?>
  </section>

  <footer>© 2025 FC Argeș Basketball</footer>
</div>
</body>
</html>