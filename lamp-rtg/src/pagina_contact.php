<?php
// Deși pagina de contact nu folosește date din BD, o convertim la PHP
// pentru uniformitate și pentru a putea include footer-ul, antetul etc.,
// dacă ar fi nevoie pe viitor.
// Includem config.php pentru a avea un mediu PHP complet (și pentru a închide conexiunea dacă e deschisă)
require_once 'config.php'; 
?>

<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8">
  <title>Contact — FC Argeș Basketball</title>
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

  <h2>Contact</h2>
  <div class="contact-info">
    <p><strong>Adresă sediu:</strong> Strada Exemplu 123, Pitești, România</p>
    <p><strong>Telefon:</strong> +40 123 456 789</p>
    <p><strong>Email:</strong> contact@fcargesbasketball.ro</p>
    <p><strong>Urmărește-ne pe social media:</strong></p>
    <ul>
      <li><a href="https://www.facebook.com/fcargesbasketball/">Facebook</a></li>
      <li><a href="https://www.instagram.com/fcargesbasketball/?hl=ro">Instagram</a></li>
    </ul>
    <p><a href="https://maps.google.com/" target="_blank">Vezi pe Google Maps</a></p>
  </div>

  <footer>© 2025 FC Argeș Basketball</footer>
</div>
</body>
</html>

<?php
// Închide conexiunea la sfârșitul paginii (din config.php)
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>