<?php
session_start();

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

?>










<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - Perpustakaan Digital</title>
  <link rel="stylesheet" href="../../styles/auth/register.css">
</head>
<body>

  <div class="auth-wrapper">
    <div class="auth-card">
      <div class="auth-logo">
        <span class="logo-badge">PD</span>
        Perpustakaan Digital
      </div>
      <h1>Buat Akun Baru</h1>
      <p class="auth-subtitle">Daftar untuk mulai meminjam dan mengelola buku.</p>

      <form method="POST" action="../../actions/auth/register.php">
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
           <input type="text" id="name" name="name" value="<?= $old['name'] ?? '' ?>">
              
            <small><?= $errors['name'] ?? '' ?></small>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
           <input type="email" id="email" name="email" value="<?= $old['email'] ?? '' ?>">

            <small><?= $errors['email'] ?? '' ?></small>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter">
              <small><?= $errors['password'] ?? '' ?></small>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Ulangi Kata Sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" 
            
            placeholder="Ulangi kata sandi">
            <small><?= $errors['password_confirmation'] ?? '' ?></small>  

          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
      </form>

      <p class="form-footer-link">
        Sudah punya akun? <a href="login.php">Masuk di sini</a>
      </p>
    </div>
  </div>
</body>
</html>
