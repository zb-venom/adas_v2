<?
    require_once '/db/db.php';
    require_once '/functions.php'; 
    session_start();
    $_SESSION['title'] = "Оборудование";
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
        $id_device = $_POST['id_device'];
        $code = $_POST['code'];
        $room = $_POST['auditory'];
        $sql = "SELECT code FROM `auditory` WHERE code='$code'";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        $row = mysqli_fetch_array($result);
        if ($row)
            $error = "Предмет с таким кодом уже существует! Повторите поппытку!";
        else {
            $sql = "INSERT INTO `auditory`(`id_device`, `code`, `room`) VALUES ('$id_device','$code','$room')";
            $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
            $good = true;
        }
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM `auditory` WHERE id=$id";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    }
?> 
    <div class="App-main" style="padding-right: 60px;">
        <div class="App-card">
            <div class="App-card-title">
                <h3 style="color: black;">Оборудование</h3>
            </div>
            <? if ($good) echo "<div class='alert alert-success'>Запись была добавлена!</div>";
                if ($error) echo "<div class='alert alert-danger'>$error</div>";?>
            <div class="App-card-body">
                <form method="post">
                    <div class="input-group">
                        <select name="id_device" style="border: solid 1px lightgray; border-radius: 5px 0px 0px 5px">
                            <? $sql = "SELECT id, name FROM `device`";
                                $result = mysqli_query($link, $sql);
                                while ($row =  mysqli_fetch_array($result))
                                    echo "<option value='".$row['id']."'>".$row['name']."</option>"; ?>
                        </select> 
                        <input type="text" name="code" class="form-control" value="<? echo $gen_code;?>" readonly> 
                        <select name="auditory" style="border: solid 1px lightgray; border-radius: 5px 5px 0px 0px">
                            <option value="404">Аудитория 404</option>
                            <option value="707">Аудитория 707</option>
                        </select> 
                        <button class="btn btn-outline-success" name="add">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="App-card">
            <div class="App-card-title">
                <h3 style="color: black;">Поиск оборудования</h3>
            </div>
            <div class="App-card-body">
                <form method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Введите код оборудования" value="<?echo $search?>">
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
                <? $sql_num = "SELECT * FROM `auditory` WHERE 1"; 
                    if ($search) {$url = "search=$search&"; $sql_num = "SELECT * FROM `auditory` WHERE `code` LIKE '%$search%' or `code` LIKE '$search%' or `code` LIKE '%$search'";}
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
                        <th scope="col" style="width: 5%;">id</th><th scope="col">Наименование</th><th scope="col">Тип</th><th scope="col">Код</th><th scope="col" style="width: 20%;">Аудитория</th><th scope="col" style="width: 20%;">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $limit = $_GET['page'] ? ($_GET['page']-1)*10 : 0;
                        if ($search)        
                            $sql = "SELECT * FROM `auditory` WHERE `code` LIKE '%$search%' or `code` LIKE '$search%' or `code` LIKE '%$search' LIMIT $limit, 10";
                        else
                            $sql = "SELECT * FROM `auditory` LIMIT $limit, 10";
                        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
                        if($result!==FALSE){
                            while($row = mysqli_fetch_array($result)) {
                                    $id_device = $row["id_device"];
                                    $sqli = "SELECT name, about, type FROM `device` WHERE id=$id_device";
                                    $resulti = mysqli_query($link, $sqli) or die("Ошибка " . mysqli_error($link));
                                    $rowi = mysqli_fetch_array($resulti);
                                    printf("<form method='post'><tr><td><input class='form-control' readonly name='id' value='%s'></td><td>%s</td><td>%s</td><td>%s</td><td>Аудитория: %s</td><td><button name='delete' class='btn btn-outline-danger'>Удалить</button></td></tr></form>",
                                    $row['id'], $rowi["name"], $rowi['type'], $row['code'], $row['room']);
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