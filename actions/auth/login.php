<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../validations/auth-validation.php";
require_once __DIR__ . "/../../repositories/user-repository.php";
 
session_start();
 
if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $request = getLoginRequest($_POST);
        $errors = validateLogin($request);
 
        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $request;
 
            header('Location: ../../pages/auth/login.php');
            exit;
        }
 
        $user = getUserByEmail($request['email']);
 
        if (!$user || !password_verify($request['password'], $user['password'])) {
            $errors['login'] = "Email atau Kata Sandi salah";
 
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $request;
 
            header('Location: ../../pages/auth/login.php');
            exit;
        }
 
        session_regenerate_id(true);
 
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
 
        header('Location: ../../index.php'); 
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}