<?php

require_once __DIR__ . "/../config/database.php";

function getBooks($search, $catergoryId, $currentPage, $perPage)
{
  global $pdo;

  $query = "select b.id, b.title, b.stock, c.name as category, a.name as author
            from books b
            left join categories c on b.category_id = c.id
            where b.title like :search
            and (:category_filter = '' or b.category_id = :category_id)
            limit offset :offset
            ";
            
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  'search' => "%$search",
  'category_filter' => $catergoryId,
  'category_id' => $catergoryId,
  'limit' => $perPage
  'offset' => ($currentPage - 1) * $perPage
  ]);


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

  $bookId = intval ($ignoreBookId)

  $query = "select count(id) from books where isbn = :isbn and id != :book_id";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'isbn' => $isbn,
    'book_id' => $bookId
  ]);

 $isIsbnTaken = $stmt->fetchColumn() > 0;
 
return $isIsbnTaken;
}



function countOfBooks($search, $categoryId)
{
    global $pdo;

$query = select count(id) from books
         where title like: title and 
         (:category_filter = '' or category_id = :category_id)
         ";
 
$stmt = $pdo->prepare($query);      
 $stmt->execute([
 'title' => "%$search",
    'category_filter' => $categoryId,
    'category_id' => $categoryId,
    ]);

    $totalBooks = $stmt->fetchColumn();

    return $totalBooks;

 }   