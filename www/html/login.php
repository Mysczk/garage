<?php
session_start();
require_once 'db.php'; // obsahuje funkci dbLogin a případně login()
require_once 'auth.php';
require_once 'db.php';

// Zpracování POST požadavku
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = 'Vyplňte e-mail i heslo.';
    } else {
        $result = dbLogin($email, $password);
        if ($result === true) {
            header('Location: index.php');
            exit;
        } else {
            $message = $result; // "DB error" nebo "Invalid login"
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přihlášení</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<form method="post" action="">
    <h2>Přihlášení</h2>
    <label for="email">E-mail:</label>
    <input type="email" name="email" required>

    <label for="password">Heslo:</label>
    <input type="password" name="password" required>

    <button type="submit">Přihlásit se</button>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</form>
<a class="back" href="register.php">Nemáš účet? Zaregistruj se</a>


</body>
</html>
