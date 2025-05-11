<?php
require './models/connection.php';
require './models/authentication.php';
include_once './models/store.php';

$pdo = Connection::get()->connect();
$auth = new Authentication($pdo);
$user = $auth->getCurrentUser();

try {
    $stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'books' AND table_schema = 'public'");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Ошибка при проверке структуры таблицы: " . $e->getMessage());
}

// Пока используем ту же логику, но можно переименовать поля позже
$yearColumn = 'publication_year'; 
if (!in_array($yearColumn, $columns)) {
    $possibleYearColumns = ['publish_year', 'release_year', 'year_published'];
    foreach ($possibleYearColumns as $col) {
        if (in_array($col, $columns)) {
            $yearColumn = $col;
            break;
        }
    }
}

// Поиск проектов
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $searchParams = [
        'query' => trim($_GET['query'] ?? ''),
        'genre_id' => isset($_GET['genre_id']) ? (int)$_GET['genre_id'] : null,
    ];

    $sql = "SELECT * FROM public.books WHERE 1=1";
    $params = [];
    
    if (!empty($searchParams['query'])) {
        $sql .= " AND (title ILIKE :query OR author ILIKE :query)";
        $params[':query'] = '%'.$searchParams['query'].'%';
    }
    
    if (!empty($searchParams['genre_id'])) {
        $sql .= " AND genre_id = :genre_id";
        $params[':genre_id'] = $searchParams['genre_id'];
    }
    
    $sql .= " ORDER BY title LIMIT 12";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $filteredBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $selectedGenre = $searchParams['genre_id'];
        $searchQuery = htmlspecialchars($searchParams['query']);
    } catch (PDOException $e) {
        die("Ошибка при загрузке данных: " . $e->getMessage());
    }
} else {
    try {
        $stmt = $pdo->query("SELECT * FROM public.books ORDER BY title LIMIT 12");
        $filteredBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $selectedGenre = '';
        $searchQuery = '';
    } catch (PDOException $e) {
        die("Ошибка при загрузке данных: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Наши проекты | Си Ай Проект</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    <style>
        .nav-button {
            background-color: white;
            color: black !important;
            transition: transform 0.3s ease;
            border: none;
            border-radius: 50px;
        }

        .nav-button:hover {
            transform: scale(1.05);
            background-color: white !important;
            color: black !important;
        }

        footer {
            background-color: #FFD700; /* Жёлтый цвет */
        }

        footer a {
            text-decoration: none;
            color: black;
        }

        .hover-shadow {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-img-top {
            transition: transform 0.5s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Шапка с навигацией -->
<nav class="navbar navbar-expand-lg bg-warning fixed-top py-4">
    <div class="container-fluid">

        <!-- Логотип слева + ссылка на главную -->
        <a class="navbar-brand me-4 ms-3" href="mane.php">
            <img src="logo.png" alt="Логотип" width="60" height="auto" class="h-auto">
        </a>

        <!-- Меню навигации -->
        <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0 ms-3 d-flex align-items-center">
                <!-- Кнопка Главная -->
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php">Главная</a>
                </li>
                <!-- О компании -->
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php#about">О компании</a>
                </li>
                <!-- Преимущества -->
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php#team">Преимущества</a>
                </li>
                <!-- Контакты -->
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php#contacts">Контакты</a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<!-- Пространство под шапкой -->
<div style="height: 90px;"></div>

<!-- Заголовок секции -->
<section class="py-5 text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Наши проекты</h1>
        <p class="lead text-muted">Ознакомьтесь с нашими реализованными и текущими проектами.</p>
    </div>
</section>

<!-- Форма поиска и фильтрации -->
<div class="container" style="margin-bottom: 40px;">
    <form id="searchForm" method="get" class="p-4 bg-light rounded-3 shadow mx-auto text-center" style="max-width: 800px;">
        <div class="input-group mb-3">
            <input type="text" name="query" class="form-control form-control-lg" placeholder="Поиск проекта по названию" value="<?= $searchQuery ?>">
            <button class="btn btn-warning" type="submit">Найти</button>
        </div>

        <div class="row g-3">
            <div class="col-md-6 offset-md-3">
                <select class="form-select" name="genre_id">
                    <option value="">Все типы проектов</option>
                    <?php foreach (getGenres() as $genre): ?>
                        <option value="<?= $genre['id'] ?>" <?= $selectedGenre == $genre['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($genre['name_genre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Карточки проектов -->
<div class="container py-5" id="projectsContainer">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="projectResults">
        <?php if (empty($filteredBooks)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">Проекты не найдены. Попробуйте изменить параметры поиска.</div>
            </div>
        <?php else: ?>
            <?php foreach ($filteredBooks as $book): 
                $genre = getGenre($book['genre_id']); 
            ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <img src="assets/img/<?= htmlspecialchars($book['cover_image']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($book['title']) ?>"
                             style="height: 220px; object-fit: cover;" 
                             onerror="this.src='assets/img/placeholder.jpg'">

                        <div class="card-body">
                            <h5><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text small text-secondary line-clamp-2">
                                <?= htmlspecialchars(mb_substr($book['description'], 0, 100)) ?>...
                            </p>
                            <div class="mt-auto pt-3">
                                <a href="check_book.php?id=<?= (int)$book['id'] ?>" class="btn btn-dark w-100 rounded-pill">Подробнее</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Подвал сайта -->
<footer class="py-4 text-center text-black bg-warning">
    <div class="container">
        <p class="mb-0">
            <a href="login.php" class="btn btn-light rounded-pill px-4">Для сотрудников</a>
        </p>
        <p class="mt-3 small">
            &copy; 2025 Си Ай Проект. Все права защищены.
        </p>
    </div>
</footer>

</body>
</html>