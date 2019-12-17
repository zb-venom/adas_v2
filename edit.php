<?
    require_once '/db/db.php';
    require_once '/functions.php';
    session_start();
    $_SESSION['title'] = "Редактировать";
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
    if (isset($_POST['add'])) {
        $about = $_POST['about'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $sql = "SELECT name FROM `device` WHERE name='$name'";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        $row = mysqli_fetch_array($result);
        if ($row)
            $error = "Предмет с таким название уже существует!";
        else {
            $sql = "INSERT INTO `device`(`name`, `about`, `type`) VALUES ('$name','$about','$type')";
            $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        }
    }
    if (isset($_POST['save'])) {
        $about = $_POST['about'];
        $id = $_POST['id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $sql = "UPDATE `device` SET `name`='$name', `about`='$about', `type`='$type' WHERE id=$id";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM `device` WHERE id=$id";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
    }
?>   
    <div class="App-main" style="padding-right: 60px;">
        <div class="App-card">
            <div class="App-card-title">
                <h3 style="color: black;">Добавить оборудование</h3>
            </div>
            <? if ($error) echo "<div class='alert alert-danger'>$error</div>";?>
            <div class="App-card-body">
                <form method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="name" placeholder="Название" class="form-control"> 
                        <input type="text" name="type" placeholder="Тип" class="form-control"> 
                        <button class="btn btn-outline-success" name="add">Добавить</button>
                    </div>
                    <textarea name="about" style="resize: none" rows=4 placeholder="Описание" class="form-control"></textarea>
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
                        <input type="text" class="form-control" name="search" placeholder="Что вы хотите найти?" value="<?echo $search?>">
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
                <? $sql_num = "SELECT * FROM `device` WHERE 1"; 
                    if ($search) {$url = "search=$search&"; $sql_num = "SELECT * FROM `device` WHERE `name` LIKE '%$search%' or `name` LIKE '$search%' or `name` LIKE '%$search'";}
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
                        <th scope="col" style="width: 5%;">id</th><th scope="col" style="width: 15%;">Наименование</th><th scope="col">Описание</th><th scope="col" style="width: 15%;">Тип</th><th scope="col" style="width: 20%;">Изменить</th>
                    </tr>
                </thead>
                <tbody>
                    <?php         
                        $limit = $_GET['page'] ? ($_GET['page']-1)*10 : 0;
                        if ($search)        
                            $sql = "SELECT * FROM `device` WHERE `name` LIKE '%$search%' or `about` LIKE '%$search%' or `type` LIKE '%$search%' LIMIT $limit, 10";
                        else
                            $sql = "SELECT * FROM `device` LIMIT $limit, 10";
                        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
                        if($result!==FALSE){
                            while($row = mysqli_fetch_array($result)) {
                                    printf("<form method='post'><tr><td><input class='form-control' readonly name='id' value='%s'></td><td><input class='form-control' name='name' value='%s'></td><td><textarea name='about' class='form-control' style='resize: none; height: 200px;'>%s</textarea></td><td><input class='form-control' name='type' value='%s'></td><td><button name='save' class='btn btn-outline-primary mb-3'>Сохранить изменения</button><button name='delete' class='btn btn-outline-danger'>Удалить</button></td></tr></form>",
                                    $row["id"], $row["name"], $row["about"], $row['type']);
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