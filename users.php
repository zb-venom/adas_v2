<?
    require_once '/db/db.php';
    require_once '/functions.php'; 
    require_once 'salt.php';
    session_start();
    $_SESSION['title'] = "Пользователи";
    if (!$_SESSION['auth'])     
        header('Location: index.php');
    if ($_SESSION['type'] != 1)    
        header('Location: wait.php');
    require_once 'head.php';
    $id = $_SESSION['id'];
    $sql = "SELECT login, about FROM `users` WHERE id=$id";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $login = $row['login'];
    $about = $row['about'];
    $search = $_GET['search'];
    date_default_timezone_set("UTC");
    $gen_code = date("sBiNsBdN");
    if (isset($_POST['add'])) {
        $about = $_POST['about'];
        $login = $_POST['login'];
        $type = $_POST['type'];
        $code = $_POST['code'];
        $password = $_POST['password'];
        $salt = generateSalt();
        $sql = "SELECT id FROM `users` WHERE login='$login'";
        $result = mysqli_query($link, $sql);
        $check_login = mysqli_num_rows($result);
        if  (!$login || !$password || !$about) {
            $error = "Заполните все поля!";
        } elseif (preg_match_all("/[A-Za-z0-9.\-_]+/", $login) != 1 || preg_match_all("/[A-Za-z0-9.\-_]+/", $password) != 1) {
            $error = "Логин и пароль должны соответсвовать правилам ниже!";
        } elseif ($check_login) {
            $error = "Логин занят, попробуйте ещё раз!";
        } else {
            $password = md5(md5($salt).md5($password));
            $sql = "INSERT INTO users (`login`, `password`, `salt`, `about`, `type`, `code`) VALUES('$login','$password','$salt', '$about', '$type', '$code')";
            $result = mysqli_query($link, $sql);
            if ($result) {
                $good = true;
            } 
            else { 
                $error = "Ошибка! Нет соеденения с сервером, повторите попытку позже.";
            }
        }
    }
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM users WHERE id=$id";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        $login = $row['login'];
        $type = $row['type'];
        $about = $row['about'];
        $code = $row['code'] ? $row['code'] : $gen_code;
        $edit = true;
    }
    if (isset($_POST['end_edit'])) {
        $id = $_POST['id'];
        $login = $_POST['login'];
        $type = $_POST['type'];
        $about = $_POST['about'];
        $code = $_POST['code'];
        $sql = "UPDATE `users` SET login='$login', about='$about', type=$type, code='$code' WHERE id=$id";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $good = true;
        } 
        else { 
            $error = "Ошибка! Нет соеденения с сервером, повторите попытку позже.";
        }
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM `users` WHERE id=$id";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    }
    
?> 
    <div class="App-main" style="padding-right: 60px;">
        <div class="App-card">
            <div class="App-card-title">
                <h3 style="color: black;">Пользователи</h3>
            </div>
            <? if ($good) echo "<div class='alert alert-success'>Успешно</div>";
                if ($error) echo "<div class='alert alert-danger'>$error</div>";?>
            <div class="App-card-body">
                <form method="post">                    
                    <div class="input-group">
                        <? 
                        if ($edit) 
                            echo "<input class='form-control' style='max-width: 10px;' readonly name='id' value='$id'>
                                <input type='text' value='$about' name='about' class='form-control' placeholder='ФИО, группа'> 
                                <input type='text' value='$login' name='login' class='form-control' placeholder='Логин'> 
                                <select name='type' style='border: solid 1px lightgray; border-radius: 5px 5px 0px 0px'>
                                    <option value='2'>Студент</option>
                                    <option value='1'>Администратор</option>
                                    <option value='3'>Ожидание</option>
                                </select>
                                <input type='text' name='code' class='form-control' value='$code' readonly>
                                <button class='btn btn-outline-success' name='end_edit'>Изменить</button>";
                        else
                            echo "<input type='text' name='about' class='form-control' placeholder='ФИО, группа'> 
                            <input type='text'  name='login' class='form-control' placeholder='Логин'> 
                            <input type='text' name='password' class='form-control' placeholder='Пароль'> 
                            <select name='type' style='border: solid 1px lightgray; border-radius: 5px 5px 0px 0px'>
                                <option value='2'>Студент</option>
                                <option value='1'>Администратор</option>
                                <option value='3'>Ожидание</option>
                            </select>
                            <input type='text' name='code' class='form-control' value='$gen_code' readonly> 
                            <button class='btn btn-outline-success' name='add'>Добавить</button>";                        
                        ?>
                        
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="App-card">
            <div class="App-card-title">
                <h3 style="color: black;">Поиск пользователя</h3>
            </div>
            <div class="App-card-body">
                <form method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Поиск..." value="<?echo $search?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="submit">Найти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <? $sql_num = "SELECT * FROM `users` WHERE 1"; 
                    if ($search) {$url = "search=$search&"; $sql_num = "SELECT * FROM `users` WHERE `about` LIKE '%$search%' or `about` LIKE '$search%' or `about` LIKE '%$search'";}
                    $result_num = mysqli_query($link, $sql_num);
                    $num = mysqli_num_rows($result_num);
                    if (ceil($num/10)>1){
                        for ($i = 1; $i <= ceil($num/10); $i++)
                            echo "<li class='page-item'><a class='page-link' href='?".$url."page=$i'>$i</a></li>";
                    }
                ?>
            </ul>
        </nav>
            <table class="table table-striped table-light">
                <thead>
                    <tr>
                        <th scope="col" style="width: 5%;">id</th><th scope="col">Информация</th><th scope="col">Логин</th><th scope="col">Код</th><th scope="col" style="width: 20%;">Тип</th><th scope="col" style="width: 20%;">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $limit = $_GET['page'] ? ($_GET['page']-1)*10 : 0;
                        if ($search)        
                            $sql = "SELECT * FROM `users` WHERE `about` LIKE '%$search%' or `about` LIKE '$search%' or `about` LIKE '%$search' LIMIT $limit, 10";
                        else
                            $sql = "SELECT * FROM `users` LIMIT $limit, 10";
                        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
                        if($result!==FALSE){
                            while($row = mysqli_fetch_array($result)) {
                                    if (!$row['code']) $row['code'] = "<button name='edit' class='btn btn-outline-primary mb-3'>Добавить</button>";
                                    if (!$row['type']) $row['type'] = "Не потверждён"; elseif ($row['type'] == 1) $row['type'] = "Администратор"; elseif ($row['type'] == 2) $row['type'] = "Студент"; elseif ($row['type'] == 3) $row['type'] = "Ожидает";
                                    printf("<form method='post'><tr><td><input class='form-control' readonly name='id' value='%s'></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><button name='edit' class='btn btn-outline-primary'>Изменить</button>&emsp;<button name='delete' class='btn btn-outline-danger'>Удалить</button></td></tr></form>",
                                    $row['id'], $row["about"], $row['login'], $row['code'], $row['type']);
                                }
                            mysqli_free_result($result);
                        }
                    ?>
                </tbody>
            </table>
    </div>
    <div class="App-footer">
            <hr>
            <h6>About ADAS</h6>
    </div>
</body>
</html>