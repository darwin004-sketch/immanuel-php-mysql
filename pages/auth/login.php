<?php 
session_start();

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);







<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Perpustakaan Digital</title>
  <link rel="stylesheet" href="../../styles/auth/login.css">
</head>
<body>

  <div class="auth-wrapper">
    <div class="auth-card">
      <div class="auth-logo">
        <span class="logo-badge">PD</span>
        Perpustakaan Digital
      </div>
      <h1>Selamat Datang Kembali</h1>
      <p class="auth-subtitle">Masuk untuk mengelola koleksi buku perpustakaan.</p>
    
       <?php if ():?>
       <p style="color: green; font-size: 12px; margin-bottom: 12px;"
         <?= $success ?>
       </p>
       
       <?php endif?>


      <form method="" action="../../actions/auth/login.php">
        <p class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="nama@sekolah.sch.id">
          <?php if (isset($errors['password_confirmation'])): ?>
                <p style="font-size:12px; margin-top: 4px; color: red;"><?= 
          </p>
          <?php endif ?>
        </div>
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" placeholder="Masukkan kata sandi">
          placehorder="Ulangi kata sandi">
          
          <?php if(isset($errors['email'])): ?>
             <p style="font-size:12px; margin-top: 4px; color: red;">
             <?= $errors['emails'] ?>
           </p>
           <?php endif ?>  

        </div>
        <button name="login" type="submit" class="btn btn-primary btn-block">Masuk</button>
      </form>

      <p class="form-footer-link">
        Belum punya akun? <a href="register.php">Daftar di sini</a>
      </p>
    </div>
  </div>
</body>
</html>
