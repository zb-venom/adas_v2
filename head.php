<?php
    session_start();
    $title = $_SESSION['title'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/ico/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <title><?echo $title;?></title>
</head>
<body>
    <? if ($_SESSION['auth']) {echo '<div class="navbar nav justify-content-between" style="padding: 0 5vh 0vh 5vh">
        <div class="navbar-brand"><h3><a class="nav-brand link" href="/"><b>ADAS</b></a></h3></div>
        <Form inline>          
            <a href="main.php">Главная</a>&emsp;
            <a href="search.php">Поиск оборудования</a>&emsp;';
            if ($_SESSION['type']==1) echo '<a href="edit.php">Редактировать данные</a>&emsp;
                                               <a href="devices.php">Оборудование</a>&emsp;
                                               <a href="users.php">Пользователи</a>&emsp;';
            echo '<a onClick="toBottom()" href="#">Информация</a>
        </Form>
        <Form inline>        
          <a class="btn btn-outline-danger" href="exit.php">Выход</a>
        </Form>
    </div> ';}?>
    <small style="position: fixed; right: 1vw; bottom: 1vh; font-size: 15px; color: white;">made by <b>Venom<b></small>