<?php
require_once __DIR__ . "/../repositories/book-repository.php";
require_once __DIR__ . "/../repositories/category-repository.php";
require_once __DIR__ . "/../repositories/author-repository.php";

function getBookRequest($request)
{
    $authorIds = $request['author_ids'] ?? [];

    return [
        'title'       => is_scalar($request['title'] ?? null) ? trim((string) $request['title']) : '',
        'isbn'        => is_scalar($request['isbn'] ?? null) ? trim((string) $request['isbn']) : '',
        'year'        => is_scalar($request['year'] ?? null) ? trim((string) $request['year']) : '',
        'stock'       => is_scalar($request['stock'] ?? null) ? trim((string) $request['stock']) : '',
        'description' => is_scalar($request['description'] ?? null) ? trim((string) $request['description']) : '',
        'category_id' => is_scalar($request['category_id'] ?? null) ? trim((string) $request['category_id']) : '',
        'author_ids'  => is_array($authorIds) ? $authorIds : [],
    ];
}

function validateBook($request, $ignoreBookId = 0)
{
    $errors = [];

    // Validate title: required and max 100 character
    if ($request['title'] === '') {
        $errors['title'] = "Judul buku wajib diisi";
    } elseif (mb_strlen($request['title']) > 100) {
        $errors['title'] = "Judul buku tidak bole melebihi dari 100 karakter";
    }

    // Validate isbn: required, isTaken?
    if ($request['isbn'] === '') {
        $errors['isbn'] = "ISBN wajib diisi";
    } elseif (isIsbnTaken($request['isbn'], $ignoreBookId)) {
        $errors['isbn'] = "ISBN sudah terdaftar sebelumnya";
    }

    // Validate year: required, isNumber
    if ($request['year'] === '') {
        $errors['year'] = "Tahun terbit wajib diisi";
    } elseif (filter_var($request['year'], FILTER_VALIDATE_INT) === false) {
        $errors['year'] = "Tahun terbit wajib diisi dengan angka";
    }

    // Validate stock: required, isNumber
    if ($request['stock'] === '') {
        $errors['stock'] = "Stok wajib diisi";
    } elseif (filter_var($request['stock'], FILTER_VALIDATE_INT) === false) {
        $errors['stock'] = "Stock wajib diisi dengan angka";
    }

    // Validate category: required, isExists
    if ($request['category_id'] === '') {
        $errors['category_id'] = "Kategory wajib dipilih";
    } elseif (!isCategoryExists($request['category_id'])) {
        $errors['category_id'] = "Kategory yang dipili tidak valid";
    }

    // Validate description: required, max 200 character
    if ($request['description'] === '') {
        $errors['description'] = "Deskripsi buku wajib diisi";
    } elseif (mb_strlen($request['description']) > 100) {
        $errors['description'] = "Deskripsi buku tidak bole melebihi dari 100 karakter";
    }

    // Validate Authors: required, isExists
    if (count($request['author_ids']) < 1) {
        $errors['author_ids'] = "Penulis buku wajib dipilih";
    } else {
        $authors = getAuthors();
        $validAuthorIds = array_map('intval', array_column($authors, 'id'));
        foreach ($request['author_ids'] as $authorId) {
            $validatedAuthorId = is_scalar($authorId) ? filter_var($authorId, FILTER_VALIDATE_INT) : false;
            if ($validatedAuthorId === false || !in_array($validatedAuthorId, $validAuthorIds, true)) {
                $errors['author_ids'] = "Penulis buku yang dipilih tidak valid";
            }
        }
    }

    return $errors;
}

?>