<?php
require './models/connection.php';
require './models/authentication.php';
include_once './models/store.php';

$pdo = Connection::get()->connect();
$auth = new Authentication($pdo);
$user = $auth->getCurrentUser();
$books = getBooks();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Си Ай Проект</title>
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

        .hero-section {
            height: 40vh;
        }

        .main-text {
            font-size: calc(1.1rem + 0.2vw);
            line-height: 1.6;
            color: #000;
        }

        .custom-bullet {
            color: #FFD700;
            margin-right: 0.5em;
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
<nav class="navbar navbar-expand-lg bg-warning fixed-top py-4">
    <div class="container-fluid">
        <a class="navbar-brand me-4 ms-3" href="#">
            <img src="logo.png" alt="Логотип" width="60" height="auto" class="h-auto">
        </a>
        <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0 ms-3 d-flex align-items-center">
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="project.php">Наши проекты</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="#about">О компании</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="#team">Преимущества</a>
                </li>
                <li class="nav-item me-3">
                    <a class="btn nav-button fs-5 px-4 py-2" href="#contacts">Контакты</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 90px;"></div>
<div class="position-relative w-100 hero-section">
    <img src="1.jpg" alt="Фоновое изображение" class="w-100 h-100 object-fit-contain">
    <div class="position-absolute top-50 start-50 translate-middle text-white display-4 fw-bold">
        Си Ай Проект
    </div>
</div>
<br><br><br>
<section class="container my-8" id="about">
    <div class="row">
        <div class="col-md-6">
            <p class="main-text fs-5">
                Компания ООО «Си Ай Проект» была основана 11 сентября 2019 года в городе Челябинске.
                С момента основания компания сосредоточена на разработке строительных проектов и предоставлении полного комплекса инженерно-технических и консалтинговых услуг в сфере проектирования,
                строительства и управления объектами различного назначения.
            </p>
        </div>
        <div class="col-md-6">
            <p class="main-text fs-5">
                Компания активно развивает региональные и федеральные проекты, опираясь на профессиональную команду инженеров, проектировщиков, аудиторов и консультантов. Высокий уровень экспертизы позволяет нам реализовывать проекты любой сложности с ориентацией на устойчивое развитие, инновации и соблюдение современных нормативных требований.
                Сегодня «СИ АЙ ПРОЕКТ»
            </p>
        </div>
    </div>
</section>
<br><br><br><br><br><br>
<section class="text-center my-8" id="team">
    <p class="main-text fs-5 w-75 mx-auto">
        Команда ООО «СИ АЙ ПРОЕКТ» — это специалисты с опытом в инженерном проектировании, строительстве и техконсалтинге. Мы обеспечиваем полный цикл работ: от анализа и проектирования до авторского надзора и ввода объектов в эксплуатацию.
    </p>
    <p class="main-text fs-5 mt-3">
        <span class="text-warning fs-4">90%</span> проектов включают энергоэффективные решения и соответствуют стандартам устойчивого развития, снижая издержки и повышая экологическую безопасность.
    </p>
    <p class="main-text fs-5 mt-3">
        <span class="text-warning fs-4">92%</span> проектных решений учитывают перспективы роста территорий и возможность масштабирования инженерной инфраструктуры.
    </p>
    <p class="main-text fs-5 mt-3">
        <span class="text-warning fs-4">80%</span> проектов выполняются с использованием BIM и цифровых инструментов, что снижает ошибки и повышает эффективность строительства.
    </p>
</section>
<br><br><br><br><br><br>
<section class="position-relative py-5 my-8" style="background: url('2.jpg') no-repeat center center / cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white" style="opacity: 0.85;"></div>
    <div class="container position-relative z-2">
        <div class="row text-center g-4 text-black">
            <div class="col-md-4">
                <h3 class="mb-4 display-6">Архитектурное проектирование</h3>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Разработка строительных проектов
                </p>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Проектирование систем водо-, тепло- и газоснабжения
                </p>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Проекты промышленных объектов и технологических процессов
                </p>
            </div>
            <div class="col-md-4">
                <h3 class="mb-4 display-6">Инжиниринг и сопровождение</h3>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Авторский надзор и строительный контроль
                </p>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Управление проектами строительства
                </p>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Деятельность заказчика-застройщика и генподрядчика
                </p>
            </div>
            <div class="col-md-4">
                <h3 class="mb-4 display-6">Консалтинг и аудит</h3>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Технические консультации в сфере энергосбережения
                </p>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Финансовый аудит и бухгалтерское сопровождение
                </p>
                <p class="main-text fs-5 mb-3">
                    <span class="custom-bullet">•</span> Налоговое консультирование
                </p>
            </div>
        </div>
    </div>
</section>
<br><br><br><br><br><br>
<section id="contacts" class="py-5">
    <div class="container text-center">
        <h2 class="mb-4">Контакты</h2>
        <div class="mt-5 container" style="max-width: 600px;">
            <form class="border rounded shadow-sm p-4 bg-white">
                <h5 class="mb-3 text-start">Свяжитесь с нами</h5>
                <p class="text-muted text-start small mb-4">
                    Оставьте заявку или задайте вопрос — мы свяжемся с вами в ближайшее время.
                </p>

                <p class="mb-2 text-start">
                    <strong>Телефон:</strong><br>
                    +7 904 812 95 49
                </p>

                <p class="mb-2 text-start">
                    <strong>WhatsApp:</strong><br>
                    <a href="https://wa.me/79048129549 " target="_blank">+7 904 812 95 49</a>
                </p>

                <p class="mb-2 text-start">
                    <strong>Email:</strong><br>
                    <a href="mailto:ivanoffspb@mail.ru">ivanoffspb@mail.ru</a>
                </p>

                <p class="mb-2 text-start">
                    <strong>Адрес:</strong><br>
                    ул. Каслинская, д. 99Д, офис 6/5.1
                </p>

                <p class="mb-0 text-start">
                    <strong>Пн–Пт:</strong><br>
                    с 9:00 до 18:00
                </p>
            </form>
        </div>
    </div>
</section>
<br><br><br><br><br><br>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

</body>
</html>