<?php
session_start();
require_once 'db.php';
require_once 'validate_xml.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['importData'])) {
    $xmlString = $_POST['importData'];
    $xsdPath = '../data/car_schema.xsd';

    $validation = validateXmlAgainstXsd($xmlString, $xsdPath);

    if ($validation['success']) {
        $xml = simplexml_load_string($xmlString);
        $pdo = getPdo();
        $userId = $_SESSION['user_id'] ?? 1;
        $count = 0;

        foreach ($xml->car as $car) {
            $brand = (string)$car->brand;
            $model = (string)$car->model;
            $year  = (int)$car->year;

            $stmt = $pdo->prepare("INSERT INTO cars (user_id, brand, model, year) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$userId, $brand, $model, $year])) {
                $count++;
            }
        }

        $message = "✅ Importováno $count aut do databáze.";
    } else {
        $message = $validation['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Import XML – náhled</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/import.js" defer></script>
</head>
<body>

<h1>Import XML – náhled před uložením</h1>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- Výběr nebo přetažení XML -->
<form id="xmlForm" enctype="multipart/form-data" method="post">
    <div id="dropzone" class="dropzone">
        Přetáhni XML soubor sem nebo klikni pro výběr.
        <input type="file" id="xmlFile" accept=".xml" style="display:none;">
    </div>

    <div id="fileInfo" class="message success" style="display:none;"></div>

    <!-- Výpis náhledu -->
    <div id="xmlPreviewContainer"></div>

    <!-- Skrytý input pro odeslání XML -->
    <textarea name="importData" id="importData" style="display:none;"></textarea>

    <!-- Tlačítko na odeslání -->
    <div id="importButtonWrap" style="display:none; text-align:center; margin-top:20px;">
        <button type="submit" class="button">📤 Importovat do databáze</button>
    </div>
</form>

<div style="text-align:center; margin-top: 30px;">
    <a class="back" href="index.php">← Zpět na seznam aut</a>
</div>

</body>
</html>
