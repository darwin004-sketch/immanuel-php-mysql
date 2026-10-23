<?php
require_once __DIR__ . "/../../config/database.php";

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1) {
        header("Location: ../../pages/categories/index.php");
        exit;
    }

    $name = is_scalar($_POST['name'] ?? null) ? trim((string) $_POST['name']) : '';
    $description = is_scalar($_POST['description'] ?? null) ? trim((string) $_POST['description']) : '';

    $query = "update categories set name = :name, description = :description where id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'id' => $id
    ]);

    header("Location: ../../pages/categories/index.php");
    exit;
}
