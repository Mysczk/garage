<?php
session_start();
require_once 'db.php';

// Přístup pouze pro přihlášené uživatele
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Odhlášení
if (isset($_GET['logout'])) {
    logout();
    header('Location: login.php');
    exit;
}

// Smazání auta
if (isset($_GET['delete'])) {
    $carId = (int)$_GET['delete'];
    deleteCar($_SESSION['user_id'], $carId);
    header('Location: index.php');
    exit;
}

// Načti všechna auta přihlášeného uživatele
$carsData = getUserCars($_SESSION['user_id']);

// Vytvoř XML a validuj vůči XSD
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;
$carsElem = $dom->createElement('cars');

foreach ($carsData as $car) {
    $carElem = $dom->createElement('car');
    $carElem->appendChild($dom->createElement('brand', htmlspecialchars($car['brand'])));
    $carElem->appendChild($dom->createElement('model', htmlspecialchars($car['model'])));
    $carElem->appendChild($dom->createElement('year', $car['year']));
    $carsElem->appendChild($carElem);
}

$dom->appendChild($carsElem);
libxml_use_internal_errors(true);
$isValid = $dom->schemaValidate('../data/car_schema.xsd');
libxml_clear_errors();

$validCars = $isValid ? $carsData : [];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Moje auta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="header">
    <h1>Moje auta</h1>
    <div>
        <a href="add_car.php" class="button">+ Přidat auto</a>
        <a href="import_xml.php" class="button">📥 Import z XML</a>
        <a href="?logout=1" class="button logout">Odhlásit se (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
    </div>
</div>

<?php if (!empty($validCars)): ?>
    <table>
        <thead>
            <tr>
                <th>Značka</th>
                <th>Model</th>
                <th>Rok</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($validCars as $car): ?>
                <tr>
                    <td><?= htmlspecialchars($car['brand']) ?></td>
                    <td><?= htmlspecialchars($car['model']) ?></td>
                    <td><?= htmlspecialchars($car['year']) ?></td>
                    <td>
                        <a href="?delete=<?= $car['id'] ?>">🗑️ Smazat</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="no-cars">Nemáte žádná validní auta k zobrazení.</p>
<?php endif; ?>

</body>
</html>
