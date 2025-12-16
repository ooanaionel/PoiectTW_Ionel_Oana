<?php
require_once 'config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // 1. Afișarea listei de studenți
    $sql = "SELECT nume, anul, media FROM studenti ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $studenti = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($studenti);

} elseif ($method === 'POST') {
    // 2. Adăugarea unui student nou
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nume = $input['nume'];
    $anul = intval($input['anul']);
    $media = floatval($input['media']);

    $sql = "INSERT INTO studenti (nume, anul, media) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sid", $nume, $anul, $media);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>