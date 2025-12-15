<?php
// Include fișierul de configurare și inițiază conexiunea la baza de date ($conn)
require_once 'config.php'; 

// 1. Definiți interogarea SQL pentru a obține TOATE datele necesare pentru card
// Adăugăm 'pozitie' și 'poza' pentru a afișa cardul complet, ca în original.
$sql = "SELECT jucator_id, nume, prenume, numar, pozitie, poza FROM jucatori ORDER BY numar ASC";

$result = mysqli_query($conn, $sql);

$jucatori = [];

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $jucatori[] = $row;
        }
    }
    mysqli_free_result($result);
} else {
    $eroare_sql = "EROARE la interogarea bazei de date: " . mysqli_error($conn);
}

if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8">
  <title>Lotul Echipei — FC Argeș Basketball</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body>
<div class="container">
  <header>
    <div class="brand">
      <img src="fc_arges.png" style="width:90px;border-radius:5px;margin-bottom:5px;">
      <div><strong>FC Argeș</strong></div>
    </div>
    <nav>
      <a href="index.php">Acasă</a>
      <a href="pagina_jucatori.php">Jucători</a>
      <a href="pagina_staff.php">Staff</a>
      <a href="pagina_calendar.php">Calendar</a>
      <a href="pagina_contact.php">Contact</a>
    </nav>
  </header>

  <h2>Lotul echipei</h2>
  
  <?php if (isset($eroare_sql)): ?>
      <p style="color:red; text-align:center; padding: 20px;"><?php echo $eroare_sql; ?></p>
  <?php elseif (empty($jucatori)): ?>
      <p style="text-align:center; padding: 20px;">Nu există jucători în baza de date.</p>
  <?php else: ?>
  
    <div class="roster">
        
        <?php foreach ($jucatori as $jucator): ?>
            <?php
            $nume_complet = htmlspecialchars($jucator['nume'] . ' ' . $jucator['prenume']);
            $pozitie_numar = htmlspecialchars($jucator['pozitie'] . ' • #' . $jucator['numar']);
            $link_profil = 'jucator_profil.php?id=' . htmlspecialchars($jucator['jucator_id']);
            ?>
            
            <a class="card" href="<?php echo $link_profil; ?>">
              <img src="<?php echo htmlspecialchars($jucator['poza']); ?>" alt="<?php echo $nume_complet; ?>">
              <h4><?php echo $nume_complet; ?></h4>
              <small><?php echo $pozitie_numar; ?></small>
            </a>
        <?php endforeach; ?>
        
    </div>
    <?php endif; ?>

  <footer>© 2025 FC Argeș Basketball</footer>
</div>
</body>
</html>