<?php
session_start();
require_once __DIR__ . "/../../repositories/book-repository.php";
require_once __DIR__ . "/../../repositories/category-repository.php";
require_once __DIR__ . "/../../repositories/author-repository.php";

$id = $_GET['id'];
$book = getBook($id);
if (!$book) {
  header("Location: index.php");
  exit;
}
$categories = getCategories();
$authors = getAuthors();

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

$book = array_merge($book, $old);

$pageTitle = "Edit Buku";
$pageSubtitle = "Perbarui data buku, kategori, dan penulis";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Buku - Perpustakaan Digital</title>
  <link rel="stylesheet" href="../../styles/books/edit.css">
</head>
<body>
  <div class="app-shell">
  <?php require_once __DIR__ . "/../../components/admin/sidebar.php" ?>

    <main class="app-main">
    <?php require_once __DIR__ . "/../../components/admin/topbar.php" ?>

      <div class="app-content">
        <form method="POST" action="../../actions/books/update.php">
          <input type="hidden" name="id" value="<?= $book['id'] ?>">
          <div class="form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Data Buku</div>
            <div class="form-group">
              <label for="title">Judul Buku</label>
              <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>">
              <?php if (isset($errors['title'])): ?>
                <p style="font-size:12px; margin-top: 4px; color: red;"><?= htmlspecialchars($errors['title'], ENT_QUOTES, 'UTF-8') ?></p>
              <?php endif; ?>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'], ENT_QUOTES, 'UTF-8') ?>">
                <?php if (isset($errors['isbn'])): ?>
                  <p style="font-size:12px; margin-top: 4px; color: red;"><?= htmlspecialchars($errors['isbn'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
              </div>
              <div class="form-group">
                <label for="year">Tahun Terbit</label>
                <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year'], ENT_QUOTES, 'UTF-8') ?>">
                <?php if (isset($errors['year'])): ?>
                  <p style="font-size:12px; margin-top: 4px; color: red;"><?= htmlspecialchars($errors['year'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="stock">Jumlah Stok</label>
                <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($book['stock'], ENT_QUOTES, 'UTF-8') ?>">
                <?php if (isset($errors['stock'])): ?>
                  <p style="font-size:12px; margin-top: 4px; color: red;"><?= htmlspecialchars($errors['stock'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
              </div>
              <div class="form-group">
                <label for="category_id">Kategori</label>
                <select id="category_id" name="category_id">
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= (string) $category['id'] === (string) $book['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if (isset($errors['category_id'])): ?>
                  <p style="font-size:12px; margin-top: 4px; color: red;"><?= htmlspecialchars($errors['category_id'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label for="description">Deskripsi</label>
              <textarea id="description" name="description" rows="3"><?= htmlspecialchars($book['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
              <?php if (isset($errors['description'])): ?>
                <p style="font-size:12px; margin-top: 4px; color: red;"><?= htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8') ?></p>
              <?php endif; ?>
            </div>
          </div>

          <div class="form-card">
            <div class="form-section-title">Penulis Buku</div>
            <div class="form-group">
              <label>Pilih Penulis (bisa lebih dari satu)</label>
              <div class="checkbox-grid">
                <?php foreach ($authors as $author): ?>
                  <label class="checkbox-item">
                    <input type="checkbox" name="author_ids[]" value="<?= $author['id'] ?>" <?= in_array($author['id'], $book['author_ids'], false) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8') ?>
                  </label>
                <?php endforeach; ?>
              </div>
              <?php if (isset($errors['author_ids'])): ?>
                <p style="font-size:12px; margin-top: 4px; color: red;"><?= htmlspecialchars($errors['author_ids'], ENT_QUOTES, 'UTF-8') ?></p>
              <?php endif; ?>
            </div>

            <div class="form-actions">
              <a href="index.php" class="btn btn-outline">Batal</a>
              <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
