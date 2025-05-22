<?php
session_start();
require_once 'auth.php';
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($email === '' || $username === '' || $password === '' || $confirm === '') {
        $message = 'Vyplňte všechna pole.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Neplatný e-mail.';
    } elseif ($password !== $confirm) {
        $message = 'Hesla se neshodují.';
    } else {
        $result = registerUser($email, $username, $password);

        if ($result === true) {
            // Registrace proběhla, přihlásíme uživatele
            dbLogin($email, $password);
            header('Location: index.php');
            exit;
        } else {
            $message = $result; // zpráva z funkce
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Registrace</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<form method="post">
    <h2>Registrace nového uživatele</h2>

    <input type="email" name="email" placeholder="E-mail" required>
    <input type="text" name="username" placeholder="Uživatelské jméno" required>
    <input type="password" name="password" placeholder="Heslo" required>
    <input type="password" name="confirm" placeholder="Potvrzení hesla" required>

    <button type="submit">Registrovat</button>

    <?php if ($message): ?>
        <div class="message error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</form>

<a class="back" href="login.php">← Zpět na přihlášení</a>

</body>
</html>
