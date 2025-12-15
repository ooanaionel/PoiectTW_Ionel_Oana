<?php
// Include fișierul de configurare și inițiază conexiunea la baza de date ($conn)
require_once 'config.php'; 

// 1. Definiți interogarea SQL pentru a obține membrii staff-ului
$sql = "SELECT staff_id, nume, prenume, post, poza FROM staff_tehnic ORDER BY staff_id ASC";

$result = mysqli_query($conn, $sql);

$staff = [];
$eroare_sql = null;

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $staff[] = $row;
        }
    }
    mysqli_free_result($result);
} else {
    $eroare_sql = "EROARE la interogarea bazei de date: " . mysqli_error($conn);
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
  <title>Staff Tehnic — FC Argeș Basketball</title>
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

  <h2>Staff Tehnic</h2>
  
  <?php if (isset($eroare_sql)): ?>
      <p style="color:red; text-align:center; padding: 20px;"><?php echo $eroare_sql; ?></p>
  <?php elseif (empty($staff)): ?>
      <p style="text-align:center; padding: 20px;">Nu există membri ai staff-ului în baza de date.</p>
  <?php else: ?>
  
    <div class="staff-list">
        
        <?php foreach ($staff as $membru): ?>
            <?php
            $nume_complet = htmlspecialchars($membru['nume'] . ' ' . $membru['prenume']);
            $post = htmlspecialchars($membru['post']);
            // Link dinamic către pagina de profil detaliată
            $link_profil = 'staff_profil.php?id=' . htmlspecialchars($membru['staff_id']);
            ?>
            
            <a class="card" href="<?php echo $link_profil; ?>">
              <img src="<?php echo htmlspecialchars($membru['poza']); ?>" alt="<?php echo $nume_complet; ?>">
              <h4><?php echo $nume_complet; ?></h4>
              <small><?php echo $post; ?></small>
            </a>
        <?php endforeach; ?>
        
    </div>

  <?php endif; ?>

  <footer>© 2025 FC Argeș Basketball</footer>
</div>
</body>
</html>