<?php

require_once __DIR__ . "/../config/database.php";

function getCategories()
{
  global $pdo;

  $query = "select c.id, c.name, c.description, count(b.id) as total_books
            from categories c
            left join books b on b.category_id = c.id
            group by c.id, c.name, c.description
            order by c.id";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  return $stmt->fetchAll();
}

function isCategoryExists($categoryId)
{
  global $pdo;

  $query = "select count(id) from categories where id = :category_id";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['category_id' => $categoryId]);

  return $stmt->fetchColumn() > 0;
}

function getCategory($id = 1)
{
  global $pdo;

  $query = "select c.id, c.name, c.description, count(b.id) as total_books
            from categories c
            left join books b on b.category_id = c.id
            where c.id = :id
            group by c.id, c.name, c.description";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['id' => $id]);

  return $stmt->fetch() ?: null;
}
