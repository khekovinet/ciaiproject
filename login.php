<?php
require './models/connection.php';
require './models/authentication.php';
include_once './models/store.php';

$pdo = Connection::get()->connect();
$auth = new Authentication($pdo);
$user = $auth->getCurrentUser();

session_start(); // Убедись, что сессия запущена
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход | Си Ай Проект</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
    </style>
</head>
<body>

<!-- Шапка сайта -->
<nav class="navbar navbar-expand-lg bg-warning fixed-top py-4">
    <div class="container-fluid">

        <!-- Логотип слева -->
        <a class="navbar-brand me-4 ms-3" href="mane.php">
            <img src="logo.png" alt="Логотип" width="60" height="auto" class="h-auto">
        </a>

        <!-- Меню навигации -->
        <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0 ms-3 d-flex align-items-center">
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="mane.php">Главная</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="#">О компании</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="#">Преимущества</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="#">Контакты</a>
                </li>
            </ul>
        </div>

    </div>
</nav>
<br><br><br><br>
<!-- Пространство под шапкой -->
<div style="height: 90px;"></div>

<!-- Форма входа -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h2 class="text-center mb-0">Авторизация</h2>
                    <p class="text-muted text-center mt-2">Для сотрудников</p>
                    
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-2 mb-0" role="alert">
                            <?php
                                $errorMessages = [
                                    1 => 'Пользователь с таким логином не существует',
                                    2 => 'Введите ваш логин',
                                    3 => 'Введите пароль',
                                    4 => 'Неверный пароль'
                                ];
                                $errorMessage = $errorMessages[$_SESSION['error']] ?? 'Произошла ошибка при авторизации';
                            ?>
                            <?= $errorMessage ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                </div>
                <div class="card-body px-5 pb-5 pt-2">
                    <form action="./controllers/loginUser.php" method="post" id="loginForm">
                        <div class="mb-3">
                            <label for="login" class="form-label">Логин</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="login" name="login" placeholder="ivanov" required
                                       value="<?= isset($_SESSION['login_attempt']) ? htmlspecialchars($_SESSION['login_attempt']) : '' ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 text-white mb-3">
                            Войти
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br><br><br><br><br>

<!-- Подвал сайта -->
<footer class="bg-warning py-4 text-center text-black">
    <div class="container">
        <p class="mb-0">
            <a href="#" class="btn btn-light rounded-pill px-4">Для сотрудников</a>
        </p>
        <p class="mt-3 small">
            &copy; 2025 Си Ай Проект. Все права защищены.
        </p>
    </div>
</footer>  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
