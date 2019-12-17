<?php 
    require_once '/db/db.php';
    require_once 'salt.php';
    session_start();
    $_SESSION['title'] = "Регистрация";
    if ($_SESSION['auth'])     
        header('Location: main.php');
    $message = "Введите данные";  $alert_color = "success";
    require_once 'head.php';
    $login = $_POST['login']; $password = $_POST['password']; $password2 = $_POST['password2']; $about = $_POST['about']; $check_login = true; $salt = generateSalt();
    $sql = "SELECT id FROM `users` WHERE login='$login'";
    $result = mysqli_query($link, $sql);
    $check_login = mysqli_num_rows($result);
    if (isset($_POST['end_reg'])) {
        if  (!$login || !$password || !$password2 || !$about) {
            $message = "Заполните все поля!";
            $alert_color = "danger";
        } elseif ($password != $password2) {
            $message = "Пароли не совпадают";
            $alert_color = "danger";
        } elseif (preg_match_all("/[A-Za-z0-9.\-_]+/", $login) != 1 || preg_match_all("/[A-Za-z0-9.\-_]+/", $password) != 1) {
            $message = "Логин и пароль должны соответсвовать правилам ниже!";
            $alert_color = "danger";
        } elseif ($check_login) {
            $message = "Логин занят, попробуйте ещё раз!";
            $alert_color = "danger";
        } else {
            $password = md5(md5($salt).md5($password));
            $sql = "INSERT INTO users (`login`, `password`, `salt`, `about`) VALUES('$login','$password','$salt', '$about')";
            $result = mysqli_query($link, $sql);
            if ($result) {
                header('Location: index.php');
                exit();
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
                <h1><b>Регистрация</b></h1>
                <div class="alert alert-<?echo $alert_color?>"><?echo $message?></div>
                <div class="form-group">
                    <input type="about" class="form-control" name="about" placeholder="Информация" value="<?echo $about?>">
                    <small style="color: cyan; padding-top: -10vh;">Введите через запятую ФИО, номер группы [<b><i>Иванов Иван Иванович, 717-1</i></b>]</small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="login" placeholder="Логин" value="<?echo $login?>">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Пароль">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password2" placeholder="Повторите пароль" >
                    <small style="color: white">Логин и пароль должны содержать только символы латинского алфавита, цифры, точку, нижнее подчёркивание и тире</small>
                </div>
                <div id="button">
                    <button name="end_reg" type="submit" class="btn btn-outline-light btn-back">Завершить регистрацию</button>
                </div>          
                </div>
            </div>
        </div>
    </form>
</body>