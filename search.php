<?
    require_once '/db/db.php';
    session_start();
    $_SESSION['title'] = "Поиск";
    if (!$_SESSION['auth'])     
        header('Location: index.php');
    if (!$_SESSION['type'] || $_SESSION['type'] == 3)    
        header('Location: wait.php');
    require_once 'head.php';
    $id = $_SESSION['id'];
    $sql = "SELECT login, about FROM `users` WHERE id=$id";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $login = $row['login'];
    $about = $row['about'];
    $search = $_GET['search'];
?>   
    <div class="App-main" style="padding-right: 60px;">
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
                    <th scope="col" style="width: 15%;">Наименование</th><th scope="col">Описание</th><th style="width: 15%;" scope="col">В наличии</th>
                </tr>
            </thead>
            <tbody>
                <?php     
                    $limit = $_GET['page'] ? ($_GET['page']-1)*10 : 0;
                    if ($search)        
                        $sql = "SELECT * FROM `device` WHERE `name` LIKE '%$search%' or `name` LIKE '$search%' or `name` LIKE '%$search' LIMIT $limit, 10";
                    else
                        $sql = "SELECT * FROM `device` LIMIT $limit, 10";
                    $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
                        while($row = mysqli_fetch_array($result)) {
                                $id_device = $row['id']; 
                                $sqli = "SELECT room, COUNT(id_device) AS count FROM `auditory` WHERE id_device=$id_device AND give=0 GROUP BY room";
                                $resulti = mysqli_query($link, $sqli) or die("Ошибка " . mysqli_error($link));
                                $count = "";
                                while ($rowi = mysqli_fetch_array($resulti)) {
                                    $count .= '<p><small style="color: green;"><b>'.$rowi['count'].'</b> в аудитории №'.$rowi['room'].'</small></p>';
                                }
                                if ($count == "") $count = "<small style='color: red;'>Оборудование отсутствует!</small>";
                                printf("<tr><td>%s</td><td><small>%s</small></td><td>%s</td></tr>",
                                $row["name"], $row["about"], $count);
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