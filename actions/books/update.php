<?php
session_start();
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../validations/book-validations.php";

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1) {
        header("Location: ../../pages/books/index.php");
        exit;
    }

    $request = getBookRequest($_POST);
    $errors = validateBook($request, $id);
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $request;

        header('Location: ../../pages/books/edit.php?id=' . $id);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $query = "update books set title = :title, isbn = :isbn,
                  year = :year, stock = :stock, category_id = :category_id,
                  description = :description where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $request['title'],
            'isbn' => $request['isbn'],
            'year' => $request['year'],
            'stock' => $request['stock'],
            'category_id' => $request['category_id'],
            'description' => $request['description'],
            'id' => $id
        ]);

        $query = "delete from book_author where book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['book_id' => $id]);

        $query = "insert into book_author (book_id, author_id) values (:book_id, :author_id)";
        $stmt = $pdo->prepare($query);
        foreach ($request['author_ids'] as $authorId) {
            $stmt->execute([
                'book_id' => $id,
                'author_id' => $authorId
            ]);
        }

        $pdo->commit();

        header("Location: ../../pages/books/index.php");
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
