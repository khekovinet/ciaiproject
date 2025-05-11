<?php
session_start();
include_once '../models/store.php';


$title = $_POST['title'] ?? null;
$author = $_POST['author'] ?? null;
$price = $_POST['price'] ?? null;
$stock_quantity = $_POST['stock_quantity'] ?? null;


if(empty($title) || empty($author) || empty($price) || empty($stock_quantity)) {
    $_SESSION['error'] = 'Заполните все обязательные поля (название, автор, цена, количество)';
    header('Location: ../add_book.php');
    exit();
}

$description = $_POST['description'] ?? null;
$publication_year = !empty($_POST['publication_year']) ? (int)$_POST['publication_year'] : null;
$pages = !empty($_POST['pages']) ? (int)$_POST['pages'] : null;
$publisher = $_POST['publisher'] ?? null;
$language = $_POST['language'] ?? null;
$genre_id = !empty($_POST['genre_id']) ? (int)$_POST['genre_id'] : null;


$cover_image = null;
if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
    $cover_image = uploadImage($_FILES['cover_image']);
    if(!$cover_image) {
        $_SESSION['error'] = 'Ошибка при загрузке изображения обложки';
        header('Location: ../add_book.php');
        exit();
    }
}

try {
    $result = addBook(
        $title,
        $author,
        (float)$price,
        (int)$stock_quantity,
        $description,
        $publication_year,
        $pages,
        $publisher,
        $language,
        $genre_id,
        $cover_image
    );
    
    if($result) {
        $_SESSION['success'] = 'Книга успешно добавлена!';
        header('Location: ../book.php');
    } else {
        $_SESSION['error'] = 'Ошибка при добавлении книги';
        header('Location: ../add_book.php');
    }
} catch(PDOException $e) {
    $_SESSION['error'] = 'Ошибка базы данных: ' . $e->getMessage();
    header('Location: ../add_book.php');
}

exit();