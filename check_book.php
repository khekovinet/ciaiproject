<?php
// Импорт моделей
require './models/connection.php';
require './models/authentication.php';
include_once './models/store.php';

$pdo = Connection::get()->connect();
$auth = new Authentication($pdo);
$user = $auth->getCurrentUser();

// Получаем ID книги из GET-параметра
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Неверный идентификатор книги");
}

$bookId = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM public.books WHERE id = ?");
    $stmt->execute([$bookId]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        die("Книга не найдена");
    }
} catch (PDOException $e) {
    die("Ошибка при загрузке данных: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']) ?> | Си Ай Проект</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <style>
        .card-img-top {
            width: 100%;
            height: auto;
            object-fit: contain;
            background-color: #f8f9fa;
        }

        footer {
            background-color: #FFD700;
        }

        footer a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>

<!-- Шапка -->
<nav class="navbar navbar-expand-lg bg-warning fixed-top py-4">
    <div class="container-fluid">
        <a class="navbar-brand me-4 ms-3" href="mane.php">
            <img src="logo.png" alt="Логотип" width="60" height="auto" class="h-auto">
        </a>
        <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0 ms-3 d-flex align-items-center">
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php">Главная</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="#">О компании</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php#team">Преимущества</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php#contacts">Контакты</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<br><br><br><br>

<!-- Пространство под шапкой -->
<div style="height: 90px;"></div>

<!-- Кнопка назад -->
<div class="container mt-4">
    <a href="book.php" class="btn btn-outline-dark rounded-pill px-3 py-2 mb-4">
        ← К проектам
    </a>
</div>

<!-- Контент страницы -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Изображение проекта -->
            <div class="col-md-6">
                <img src="assets/img/<?= htmlspecialchars($book['cover_image']) ?>" 
                     class="card-img-top rounded shadow-sm" 
                     alt="<?= htmlspecialchars($book['title']) ?>"
                     onerror="this.src='assets/img/placeholder.jpg'">
            </div>

            <!-- Информация о проекте -->
            <div class="col-md-6">
                <h2><?= htmlspecialchars($book['title']) ?></h2>
                <hr>
                    <?= htmlspecialchars($book['description']) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Подвал сайта -->
<footer class="bg-warning py-4 text-center text-black">
    <div class="container">
        <p class="mb-0">
            <a href="login.php" class="btn btn-light rounded-pill px-4">Для сотрудников</a>
        </p>
        <p class="mt-3 small">
            &copy; 2025 Си Ай Проект. Все права защищены.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

</body>
</html>