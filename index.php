<?php 
    session_start();
    $_SESSION['title'] = "ADAS";
    if ($_SESSION['auth'])     
        header('Location: main.php');
    require_once 'head.php';
?>
<body>
    <div class="App">
        <div class="App-header">
            <h1><b>ADAS</b></h1>
            <h3>Вас приветсвует ADAS <i>(Automated Device Accounting System)</i></h3>
            <div class="App-info">
              <i>ADAS - это автоматизированная система учета обородования. 
                 Данное приложение реализованно для упрощения учета оборудования в лабораториях университета</i>
            </div>
            <h4>
            <button onclick="document.location.href = 'reg.php'" type="submit" class="btn btn-outline-primary">Регистарция</button>&nbsp;
            <button onclick="document.location.href = 'auth.php'" type="submit" class="btn btn-outline-success">Войти</button>
            </h4>
        </div>
    </div>
</body>
</html>