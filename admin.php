<?php
require './models/connection.php';
require './models/authentication.php';
include_once './models/store.php';
$pdo = Connection::get()->connect();
$auth = new Authentication($pdo);
$user = $auth->getCurrentUser();
$genres = getGenres();

$AllInfoUser = getUser($_SESSION['user_id']);
$isAdmin = isset($AllInfoUser['role']) && $AllInfoUser['role'] == 2;
if($isAdmin != true){
  header('Location: error.php');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mane</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</head>

<body>
<div class="bd-example">
        <nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">Книги в кубе</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link" href="mane.php">Главная</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="book.php">Книги</a>
                </li>
                
                  <?php if(!isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                  <a class="nav-link" href="login.php">Вход</a>
                </li>
              <?php else: ?>

                <li class="nav-item">
                  <a class="nav-link" href="basket.php">Корзина</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="my_orders.php">Мои заказы</a>
                </li>
                <?php 
$AllInfoUser = getUser($_SESSION['user_id']);
$isAdmin = isset($AllInfoUser['role']) && $AllInfoUser['role'] == 2;
if($isAdmin == true) {
                ?>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="admin.php">Управление</a>
              </li>
                <?}?>
              <li class="nav-item">
                <a class="nav-link" href="myProfile.php"><?= $_SESSION['user'] ?></a>
              </li>  
              <li class="nav-item">
                <a class="nav-link" href="./controllers/logout.php">Выйти</a>
              </li>
              <?php endif; ?>
              <div id="searchResults" class="position-absolute mt-1 bg-white shadow rounded" 
                 style="z-index: 1000; width: 300px; max-height: 400px;margin-left:65%;"></div>
              </div>
              </ul>


              <form class="d-flex" role="search" id="searchForm">
  <input class="form-control me-2" id="searchInput" type="search" 
         placeholder="Поиск книги по названию" aria-label="Search">
  <button class="btn btn-outline-light" type="submit">Найти</button>
</form>

          </div>
        </nav>
      </div>
<!-- Мэйн -->

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h3 class="mb-0"><i class="bg-dark"></i> Добавить новую книгу</h3>
                    </div>
                    <div class="card-body">
                        <form action="./controllers/add_book.php" method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Название книги</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="author" class="form-label">Автор</label>
                                    <input type="text" class="form-control" id="author" name="author" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label for="price" class="form-label">Цена (₽)</label>
                                    <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="stock_quantity" class="form-label">Количество на складе</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" required>
                                </div>
                                <label for="genre_id" class="form-label">Жанр</label>
                                  <select class="form-select" id="genre_id" name="genre_id" required>
                                  <option value="" selected disabled>Выберите жанр...</option>
                                  <?php
                                  foreach ($genres as $genre): ?>
                                  <option style="color:black;" value="<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name_genre']) ?></option>
                                  <?php endforeach; ?>
                                    </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3 mb-3">
                                    <label for="publication_year" class="form-label">Год издания</label>
                                    <input type="number" class="form-control" id="publication_year" name="publication_year" min="1800" max="2023">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pages" class="form-label">Количество страниц</label>
                                    <input type="number" class="form-control" id="pages" name="pages" min="1">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="publisher" class="form-label">Издательство</label>
                                    <input type="text" class="form-control" id="publisher" name="publisher">
                                </div>
                                <div class="col-md-3">
                                    <label for="language" class="form-label">Язык</label>
                                    <input type="text" class="form-control" id="language" name="language">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="cover_image" class="form-label">Обложка книги</label>
                                <input class="form-control" type="file" id="cover_image" name="cover_image" accept="image/*">
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-dark me-md-2">
                                    <i class="bi bi-x-circle"></i> Очистить
                                </button>
                                <button type="submit" class="btn btn-dark">
                                    <i class="bi bi-save"></i> Сохранить книгу
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <div class="container py-5">
          <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="card shadow">
                      <div class="card-header bg-dark text-white">
                          <h4 class="mb-0"><i class="bi bi-tag"></i> Добавить новый жанр</h4>
                      </div>
                      <div class="card-body">
                          <form action="controllers/add_genre.php" method="post" class="genre-form">
                              <div class="mb-3">
                                  <label for="name" class="form-label">Название жанра</label>
                                  <input type="text" class="form-control" id="name" name="name" required>
                              </div>
                              
                              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                  <a href="genres.php" class="btn btn-dark me-md-2">
                                      <i class="bi bi-arrow-left"></i> Назад
                                  </a>
                                  <button type="submit" class="btn btn-dark">
                                      <i class="bi bi-save"></i> Сохранить
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <?php

function getUsers($pdo) {
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrders($pdo) {
    $stmt = $pdo->query("
        SELECT o.*, u.login as user_login 
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getOrderItems($pdo, $orderId) {
    $stmt = $pdo->prepare("
        SELECT oi.*, b.title as book_title 
        FROM order_items oi
        LEFT JOIN books b ON oi.book_id = b.id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete_user':
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                break;
                
            case 'update_order_status':
                $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                $stmt->execute([$_POST['status'], $_POST['order_id']]);
                break;
        }
    }
}

$users = getUsers($pdo);
$orders = getOrders($pdo);
?>

<div class="container py-5">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="bi bi-people"></i> Управление пользователями</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Логин</th>
                                    <th>Email</th>
                                    <th>Имя</th>
                                    <th>Фамилия</th>
                                    <th>Телефон</th>
                                    <th>Роль</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['login']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['lastname']) ?></td>
                                    <td><?= htmlspecialchars($user['number']) ?></td>
                                    <td>
                                        <?= $user['role'] == 2 ? '<span class="badge bg-success">Админ</span>' : '<span class="badge bg-primary">Пользователь</span>' ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-dark me-1" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                            data-id="<?= $user['id'] ?>" data-login="<?= htmlspecialchars($user['login']) ?>" 
                                            data-email="<?= htmlspecialchars($user['email']) ?>" data-name="<?= htmlspecialchars($user['name']) ?>" 
                                            data-lastname="<?= htmlspecialchars($user['lastname']) ?>" data-number="<?= htmlspecialchars($user['number']) ?>" 
                                            data-role="<?= $user['role'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="bi bi-cart"></i> Управление заказами</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID заказа</th>
                                    <th>Пользователь</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $order['id'] ?></td>
                                    <td><?= htmlspecialchars($order['user_login']) ?></td>
                                    <td><?= htmlspecialchars($order['total']) ?> ₽</td>
                                    <td>
                                        <form method="POST" class="status-form">
                                            <input type="hidden" name="action" value="update_order_status">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Ожидает</option>
                                                <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>В обработке</option>
                                                <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Отправлен</option>
                                                <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Завершен</option>
                                                <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Отменен</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-dark me-1 view-order-details" 
                                            data-bs-toggle="modal" data-bs-target="#orderDetailsModal" 
                                            data-order-id="<?= $order['id'] ?>">
                                            <i class="bi bi-eye"></i> Детали
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editUserModalLabel"><i class="bi bi-person-gear"></i> Редактировать пользователя</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="controllers/update_user.php" method="post">
                    <input type="hidden" name="id" id="editUserId">
                    <div class="mb-3">
                        <label for="editLogin" class="form-label">Логин</label>
                        <input type="text" class="form-control" id="editLogin" name="login" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editName" class="form-label">Имя</label>
                        <input type="text" class="form-control" id="editName" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="editLastname" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" id="editLastname" name="lastname">
                    </div>
                    <div class="mb-3">
                        <label for="editNumber" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="editNumber" name="number">
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Роль</label>
                        <select class="form-select" id="editRole" name="role">
                            <option value="1">Пользователь</option>
                            <option value="2">Администратор</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary me-md-2" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-dark">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="orderDetailsModalLabel">
                    <i class="bi bi-receipt"></i> Детали заказа #<span id="orderIdTitle"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="bi bi-info-circle"></i> Информация о заказе</h6>
                        <p><strong>Дата:</strong> <span id="orderDate"></span></p>
                        <p><strong>Статус:</strong> <span id="orderStatus"></span></p>
                        <p><strong>Общая сумма:</strong> <span id="orderTotal"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-person"></i> Информация о пользователе</h6>
                        <p><strong>Пользователь:</strong> <span id="orderUser"></span></p>
                        <p><strong>Адрес доставки:</strong> <span id="orderAddress"></span></p>
                    </div>
                </div>
                
                <h6><i class="bi bi-book"></i> Товары в заказе</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Книга</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTable">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Закрыть
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.view-order-details').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-order-id');
        loadOrderDetails(orderId);
    });
});

async function loadOrderDetails(orderId) {
    try {
        showLoadingState();
        
        const response = await fetch(`./controllers/get_order_details.php?order_id=${orderId}`);
        
        if (!response.ok) {
            throw new Error('Ошибка сети');
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Ошибка данных');
        }
        
        fillOrderDetails(data);
    } catch (error) {
        console.error('Ошибка загрузки:', error);
        showErrorState();
    }
}

function showLoadingState() {
    document.getElementById('orderIdTitle').textContent = 'Загрузка...';
    document.getElementById('orderDate').textContent = 'Загрузка...';
    document.getElementById('orderStatus').innerHTML = '<span class="badge bg-secondary">Загрузка</span>';
    document.getElementById('orderTotal').textContent = '... ₽';
    document.getElementById('orderUser').textContent = 'Загрузка...';
    document.getElementById('orderAddress').textContent = 'Загрузка...';
    document.getElementById('orderItemsTable').innerHTML = `
        <tr>
            <td colspan="4" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
            </td>
        </tr>`;
}

function fillOrderDetails(data) {
    document.getElementById('orderIdTitle').textContent = data.order.id;
    document.getElementById('orderDate').textContent = new Date(data.order.created_at).toLocaleString('ru-RU');

    const statusBadge = document.getElementById('orderStatus');
    statusBadge.innerHTML = `<span class="badge ${getStatusClass(data.order.status)}">${data.order.status}</span>`;
    
    document.getElementById('orderTotal').textContent = `${parseFloat(data.order.total).toFixed(2)} ₽`;
    document.getElementById('orderUser').textContent = `${data.user.name} ${data.user.lastname} (${data.user.login})`;
    document.getElementById('orderAddress').textContent = data.user.address;

    const itemsTable = document.getElementById('orderItemsTable');
    itemsTable.innerHTML = '';
    
    if (data.items && data.items.length > 0) {
        data.items.forEach(item => {
            const row = document.createElement('tr');
            const itemTotal = item.price * item.quantity;
            
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        ${item.cover_image ? 
                            `<img src="${item.cover_image}" class="img-thumbnail me-3" style="width: 50px;">` : 
                            `<div class="img-thumbnail me-3" style="width: 50px; height: 70px; background: #eee;"></div>`}
                        <div>
                            <strong>${item.book_title}</strong><br>
                            <small class="text-muted">${item.author}</small>
                        </div>
                    </div>
                </td>
                <td class="align-middle">${parseFloat(item.price).toFixed(2)} ₽</td>
                <td class="align-middle">${item.quantity}</td>
                <td class="align-middle">${itemTotal.toFixed(2)} ₽</td>
            `;
            itemsTable.appendChild(row);
        });
    } else {
        itemsTable.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted">Нет товаров в заказе</td>
            </tr>`;
    }
}

function showErrorState() {
    document.getElementById('orderIdTitle').textContent = 'Ошибка';
    document.getElementById('orderDate').textContent = 'Не удалось загрузить';
    document.getElementById('orderStatus').innerHTML = '<span class="badge bg-danger">Ошибка</span>';
    document.getElementById('orderTotal').textContent = '0 ₽';
    document.getElementById('orderUser').textContent = 'Данные недоступны';
    document.getElementById('orderAddress').textContent = 'Данные недоступны';
    document.getElementById('orderItemsTable').innerHTML = `
        <tr>
            <td colspan="4" class="text-center text-danger">
                <i class="bi bi-exclamation-triangle"></i> Не удалось загрузить данные заказа
            </td>
        </tr>`;
}

function getStatusClass(status) {
    const statusMap = {
        'pending': 'bg-warning',
        'processing': 'bg-primary',
        'completed': 'bg-success',
        'cancelled': 'bg-danger',
        'shipped': 'bg-info'
    };
    return statusMap[status.toLowerCase()] || 'bg-secondary';
}
</script>
<script>
document.getElementById('editUserModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const modal = this;
    
    modal.querySelector('#editUserId').value = button.getAttribute('data-id');
    modal.querySelector('#editLogin').value = button.getAttribute('data-login');
    modal.querySelector('#editEmail').value = button.getAttribute('data-email');
    modal.querySelector('#editName').value = button.getAttribute('data-name');
    modal.querySelector('#editLastname').value = button.getAttribute('data-lastname');
    modal.querySelector('#editNumber').value = button.getAttribute('data-number');
    modal.querySelector('#editRole').value = button.getAttribute('data-role');
});

</script>
    <footer class="bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Книги в кубе</h5>
                    <p class="text-muted">Лучший выбор книг для всех возрастов и вкусов.</p>
                </div>
                <div class="col-md-4">
                    <h5>Контакты</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope"></i> info@knigi-v-kube.ru</li>
                        <li><i class="bi bi-telephone"></i> +7 (123) 456-78-90</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Мы в соцсетях</h5>
                    <a href="#" class="btn btn-outline-dark me-2"><i class="bi bi-vk"></i></a>
                    <a href="#" class="btn btn-outline-dark me-2"><i class="bi bi-telegram"></i></a>
                    <a href="#" class="btn btn-outline-dark"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <hr>
            <div class="text-center text-muted">
                <small>© 2023 Книги в кубе. Все права защищены.</small>
            </div>
        </div>
    </footer>
    <script>
      
document.addEventListener('DOMContentLoaded', function() {
  const searchForm = document.getElementById('searchForm');
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');
  
  if (!searchForm || !searchInput || !searchResults) {
    console.error('Один из элементов поиска не найден!');
    return;
  }
  function debounce(func, wait) {
    let timeout;
    return function() {
      const context = this, args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(function() {
        func.apply(context, args);
      }, wait);
    };
  }

  function performSearch() {
    const query = searchInput.value.trim();
    console.log('Выполняем поиск:', query); 
    
    if (query.length < 3) {
      searchResults.style.display = 'none';
      return;
    }

    fetch('./controllers/search_ajax.php?query=' + encodeURIComponent(query))
      .then(response => {
        if (!response.ok) throw new Error('Ошибка сети');
        return response.text();
      })
      .then(data => {
        console.log('Получены данные:', data);
        searchResults.innerHTML = data;
        searchResults.style.display = 'block';
      })
      .catch(error => {
        console.error('Ошибка поиска:', error);
        searchResults.innerHTML = '<div class="p-2 text-danger">Ошибка сервера</div>';
        searchResults.style.display = 'block';
      });
  }

  searchForm.addEventListener('submit', function(e) {
    e.preventDefault();
    performSearch();
  });

  searchInput.addEventListener('input', debounce(function() {
    performSearch();
  }, 300));

  document.addEventListener('click', function(e) {
    if (!searchForm.contains(e.target)) {
      searchResults.style.display = 'none';
    }
  });
});
</script>
</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
</html>