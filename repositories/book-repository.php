<?php

require_once __DIR__ . "/../config/database.php";

function getBooks()
{
  global $pdo;

  $query = "select b.id, b.title, b.stock, c.name as category, a.name as author
            from books b
            left join categories c on b.category_id = c.id
            left join book_author ba on b.id = ba.book_id
            left join authors a on ba.author_id = a.id
            order by b.id";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $books = [];
  foreach ($stmt->fetchAll() as $row) {
    $bookId = $row['id'];
    if (!isset($books[$bookId])) {
      $books[$bookId] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'stock' => $row['stock'],
        'category' => $row['category'],
        'authors' => []
      ];
    }
    if ($row['author'] !== null) {
      $books[$bookId]['authors'][] = $row['author'];
    }
  }

  return array_values($books);
}

function isIsbnTaken($isbn, $ignoreBookId)
{
    global $pdo;

    $bookId = intval($ignoreBookId);

    $query = "select count(id) from books where isbn = :isbn and id != :book_id";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'isbn'    => $isbn,
        'book_id' => $bookId
    ]);

    $isIsbnTaken = $stmt->fetchColumn() > 0;

    return $isIsbnTaken;
}

function getBook($id)
{
  global $pdo;

  $query = "select b.id, b.title, b.isbn, b.year, b.stock, b.category_id,
                   b.description, c.name as category
            from books b
            left join categories c on b.category_id = c.id
            where b.id = :id";
  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'id' => $id
  ]);
  $book = $stmt->fetch();

  if (!$book) {
    return null;
  }

  $query = "select a.id, a.name
            from book_author ba
            left join authors a on ba.author_id = a.id
            where ba.book_id = :book_id";
  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'book_id' => $book['id']
  ]);

  $authors = $stmt->fetchAll();
  $book['author_ids'] = array_column($authors, 'id');
  $book['authors'] = array_column($authors, 'name');

  return $book;
}