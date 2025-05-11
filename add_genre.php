<?php
session_start();
include_once '../models/store.php';


$genre = $_POST['name'];

addGenre($genre);
header('Location: ./../admin.php');
exit();