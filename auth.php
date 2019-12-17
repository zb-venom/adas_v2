<?php 
    require_once '/db/db.php';
    require_once 'salt.php';
    session_start();
    $_SESSION['title'] = "Войти";
    if ($_SESSION['auth'])     
        header('Location: main.php');
    $message = "Введите данные"; $alert_color = "success";
    require_once 'head.php';
    $login = $_POST['login']; $password = $_POST['password']; $check_login = true; $salt = generateSalt();
    $sql = "SELECT id FROM `users` WHERE login='$login'";
    $result = mysqli_query($link, $sql);
    $check_login = mysqli_num_rows($result);
    if (isset($_POST['auth'])) {
        if  (!$login || !$password ) {
            $message = "Заполните все поля!";
            $alert_color = "danger";
        } elseif (!$check_login) {
            $message = "Такого логина не сущетсвует.";
            $alert_color = "danger";
        } else {
            $sql = "SELECT salt FROM `users` WHERE login='$login'";
            $result = mysqli_query($link, $sql);
            $salt = mysqli_fetch_array($result)['salt'];
            if ($result) {
                $password = md5(md5($salt).md5($password));
                $sql = "SELECT id, type FROM `users` WHERE password='$password'";
                $result = mysqli_query($link, $sql);
                $row = mysqli_fetch_array($result);
                if ($row['id']) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['type'] = $row['type'];
                    $_SESSION['auth'] = true;
                    header('Location: index.php');
                    exit();
                } 
                else { 
                    $message = "Ошибка! Нет соеденения с сервером, повторите попытку позже.";
                    $alert_color = "danger";
                }
            } 
            else { 
                $message = "Ошибка! Нет соеденения с сервером, повторите попытку позже.";
                $alert_color = "danger";
            }
        }
    }
?>
<body>
    <div class="navbar nav justify-content-between">
        <div class="navbar-brand"><h3><a class="nav-brand link" href="/"><b>ADAS</b></a></h3></div>
    </div>
    <form method="post">
        <div class="App">
            <div class="App-action">
                <div class="wid-50">
                    <h1><b>Авторизация</b></h1>
                    <div class="alert alert-<?echo $alert_color?>"><?echo $message?></div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="login" placeholder="Логин" value="<?echo $login?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Пароль">
                    </div>
                    <div id="button">
                        <button name="auth" type="submit" class="btn btn-outline-light btn-back">Войти</button>
                    </div>          
                </div>
            </div>
        </div>
    </form>
</body>