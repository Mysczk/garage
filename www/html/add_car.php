<?php
session_start();
require_once 'auth.php';
require_once 'db.php';

// ✅ Uživatel musí být přihlášen
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = intval($_POST['year'] ?? 0);

    if ($brand === '' || $model === '' || $year <= 1900) {
        $message = 'Vyplňte všechny údaje správně.';
    } else {
        $result = addCar($_SESSION['user_id'],$brand, $model, $year);
        if ($result === true) {
            $message = 'Auto bylo úspěšně přidáno.';
        } else {
            $message = 'Chyba při ukládání do databáze.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat auto</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<form method="post">
    <h2>Přidat nové auto</h2>

    <input type="text" name="brand" placeholder="Značka (např. Škoda)" required>
    <input type="text" name="model" placeholder="Model (např. Octavia)" required>
    <input type="number" name="year" placeholder="Rok výroby (např. 2020)" required>

    <button type="submit">Přidat</button>

    <?php if ($message): ?>
        <div class="msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</form>

<a class="back" href="import_xml.php">📥 Importovat auta z XML</a>
<a class="back" href="index.php">← Zpět na seznam aut</a>

</body>
</html>
