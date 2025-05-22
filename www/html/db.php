<?php
require_once 'auth.php';

function getPdo(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $host = 'database';
        $user = 'admin';
        $pass = 'heslo';
        $db   = 'garage';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB error: " . $e->getMessage());
        }
    }

    return $pdo;
}

function dbLogin(string $email, string $pwd): bool|string {
    $pdo = getPdo();

    $sql = 'SELECT id, username, password FROM users WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pwd, $user['password'])) {
        login($user['id'], $user['username'], $email);
        return true;
    }

    return 'Invalid login';
}

function registerUser(string $email, string $username, string $password): bool|string {
    $pdo = getPdo();

    $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $check->execute([':email' => $email]);

    if ($check->fetch()) {
        return 'Uživatel s tímto e-mailem už existuje.';
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
    return $stmt->execute([
        ':email' => $email,
        ':username' => $username,
        ':password' => $hash
    ]);
}

function getUserCars(int $userId): array {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT id, brand, model, year FROM cars WHERE user_id = :uid");
    $stmt->execute([':uid' => $userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addCar(int $userId, string $brand, string $model, int $year): bool {
    $pdo = getPdo();

    $stmt = $pdo->prepare("INSERT INTO cars (user_id, brand, model, year) VALUES (:uid, :brand, :model, :year)");
    return $stmt->execute([
        ':uid' => $userId,
        ':brand' => $brand,
        ':model' => $model,
        ':year' => $year
    ]);
}

function deleteCar(int $userId, int $carId): bool {
    $pdo = getPdo();

    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = :id AND user_id = :uid");
    return $stmt->execute([
        ':id' => $carId,
        ':uid' => $userId
    ]);
}
