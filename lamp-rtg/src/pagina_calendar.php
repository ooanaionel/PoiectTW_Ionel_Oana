<?php
// Include fișierul de configurare și inițiază conexiunea la baza de date ($conn)
require_once 'config.php';

// --- LOGICĂ PENTRU MANIPULAREA LUNII ȘI ANULUI ---
// Preluare luna/an din URL sau setare la luna/an curent
$lunaCurenta = isset($_GET['luna']) ? intval($_GET['luna']) : date('n'); // 1-12
$anCurent = isset($_GET['an']) ? intval($_GET['an']) : date('Y');

// Ajustare pentru a asigura ca luna este intre 1 si 12
if ($lunaCurenta < 1) {
    $lunaCurenta = 12;
    $anCurent--;
} elseif ($lunaCurenta > 12) {
    $lunaCurenta = 1;
    $anCurent++;
}

// Data de început și sfârșit a lunii curente
$dataStart = date('Y-m-01', strtotime("$anCurent-$lunaCurenta-01"));
$dataSfarsit = date('Y-m-t', strtotime("$anCurent-$lunaCurenta-01"));

// --- 1. INTEROGAREA BAZELOR DE DATE ---
$meciuri = [];
$eroare_sql = null;

// Interogare pentru meciurile din luna curentă
$sql = "SELECT data_meci, ora_meci, adversar, locatie, competitie 
        FROM meciuri 
        WHERE data_meci BETWEEN ? AND ? 
        ORDER BY data_meci ASC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $dataStart, $dataSfarsit);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Stocăm meciul folosind data ca cheie pentru acces rapid în calendar
            $data_formatata = date('Y-m-d', strtotime($row['data_meci']));
            $meciuri[$data_formatata][] = $row;
        }
        mysqli_free_result($result);
    } else {
        $eroare_sql = "Eroare la executarea interogării: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    $eroare_sql = "Eroare la pregătirea interogării: " . mysqli_error($conn);
}

// 2. Închide conexiunea
if (isset($conn) && $conn) {
    mysqli_close($conn);
}

// Numele lunilor în română (pentru afișarea titlului)
$luni = [1 => "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"];

// Parametri pentru butoanele de navigare (luna următoare/anterioară)
$lunaAnterioara = ($lunaCurenta == 1) ? 12 : $lunaCurenta - 1;
$anAnterior = ($lunaCurenta == 1) ? $anCurent - 1 : $anCurent;

$lunaUrmatoare = ($lunaCurenta == 12) ? 1 : $lunaCurenta + 1;
$anUrmator = ($lunaCurenta == 12) ? $anCurent + 1 : $anCurent;

// --- Funcție PHP pentru a genera Calendarul ---
function genereazaCalendarHTML($luna, $an, $meciuri, $eroare_sql) {
    if ($eroare_sql) {
        return "<tr><td colspan='7' style='color:red; text-align:center;'>$eroare_sql</td></tr>";
    }

    $html = "";
    $zileInLuna = date('t', strtotime("$an-$luna-01"));
    $primaZiSaptamana = date('N', strtotime("$an-$luna-01")); // 1 (Luni) la 7 (Duminică)
    $ziCurenta = 1;
    
    // Rândul de început
    $html .= "<tr>";

    // Zile goale de la început
    for ($i = 1; $i < $primaZiSaptamana; $i++) {
        $html .= "<td></td>";
    }

    // Generarea zilelor din lună
    while ($ziCurenta <= $zileInLuna) {
        if (($ziCurenta + $primaZiSaptamana - 2) % 7 == 0) {
            $html .= "</tr><tr>";
        }

        $dataStr = "$an-" . str_pad($luna, 2, '0', STR_PAD_LEFT) . "-" . str_pad($ziCurenta, 2, '0', STR_PAD_LEFT);
        $clasaZi = (isset($meciuri[$dataStr])) ? "are-meci" : "";
        $html .= "<td class='$clasaZi'>";
        $html .= "<div>$ziCurenta</div>";

        // Afișează meciurile, dacă există
        if (isset($meciuri[$dataStr])) {
            foreach ($meciuri[$dataStr] as $m) {
                $locatie_icon = (strpos($m['locatie'], 'Pitești Arena') !== false) ? '⚪' : '🟣'; // ⚪ = Acasă, 🟣 = Deplasare
                $html .= "<span style='font-size: 10px; display: block;'>$locatie_icon $m[adversar] ($m[ora_meci])</span>";
            }
        }

        $html .= "</td>";
        $ziCurenta++;
    }

    // Zile goale la sfârșit
    while ((($ziCurenta + $primaZiSaptamana - 2) % 7) != 0) {
        $html .= "<td></td>";
        $ziCurenta++;
    }
    
    $html .= "</tr>";

    return $html;
}
?>

<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8">
  <title>Calendar Meciuri — FC Argeș Basketball</title>
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

  <div style="text-align:center; margin-bottom:10px;">
    <a href="?luna=<?php echo $lunaAnterioara; ?>&an=<?php echo $anAnterior; ?>" id="prevMonth" style="text-decoration: none; padding: 5px 10px; border: 1px solid #ccc; background-color: #eee; margin-right: 5px;">‹</a>
    
    <h2 id="calendarTitle" style="display:inline-block; margin:0 10px;"><?php echo $luni[$lunaCurenta] . ' ' . $anCurent; ?></h2>
    
    <a href="?luna=<?php echo $lunaUrmatoare; ?>&an=<?php echo $anUrmator; ?>" id="nextMonth" style="text-decoration: none; padding: 5px 10px; border: 1px solid #ccc; background-color: #eee; margin-left: 5px;">›</a>
  </div>

  <div style="text-align:left; color:gray; font-size:12px; margin-left:10px; margin-bottom:5px; padding: 0 20px;">
    ⚪ = Acasă &nbsp;&nbsp; 🟣 = Deplasare
  </div>

  <table id="calendar" cellspacing="0" cellpadding="10" style="width:100%; text-align:center;">
    <thead>
      <tr>
        <th>Luni</th><th>Marți</th><th>Miercuri</th><th>Joi</th><th>Vineri</th><th>Sâmbătă</th><th>Duminică</th>
      </tr>
    </thead>
    <tbody>
      <?php echo genereazaCalendarHTML($lunaCurenta, $anCurent, $meciuri, $eroare_sql); ?>
    </tbody>
  </table>

  <footer>© 2025 FC Argeș Basketball</footer>
</div>

</body>
</html>