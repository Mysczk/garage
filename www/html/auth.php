<?php
// auth.php

function login(int $id, string $username, string $email): void {
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
}

function logout(): void {
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email']);
    session_destroy();
}
