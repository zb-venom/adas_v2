<?
    require_once '/db/db.php';
    session_start();
    $_SESSION['title'] = "Главная";
    if (!$_SESSION['auth'])     
        header('Location: index.php');
    if (!$_SESSION['type'] || $_SESSION['type'] == 3)    
        header('Location: wait.php');
    require_once 'head.php';
    $id = $_SESSION['id'];
    $sql = "SELECT login, about, code FROM `users` WHERE id=$id";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $login = $row['login'];
    $about = $row['about'];
    $user_code = $row['code'];
    $query = "SELECT * FROM auditory WHERE give = $user_code";
    $result_q = mysqli_query($link, $query);
    while ($row_q = mysqli_fetch_array($result_q)) {
        $sqli = "SELECT * FROM device WHERE id=".$row_q['id_device'];
        $resulti = mysqli_query($link, $sqli);
        $rowi = mysqli_fetch_array($resulti);
        $take .= "<tr><td>".$rowi["name"]."</td><td>".$rowi['type']."</td><td>".$row_q['code']."</td><td>В аудиторию ".$row_q['room']."</td></tr>";
    }
?>
    <div class="App-main">
        <div class="container-fuild">    
            <div class="row" style="margin-right: 35px;">    
                <div class="col-3">             
                    <div class="App-card">
                        <div class="App-card-title">
                            <h5>Профиль</h5>
                        </div>
                        <div class="App-card-body">
                            <h6>Логин: <?echo $login;?></h6>
                            <h6>ФИО: <?echo $about;?></h6>
                            <h6>Код: <?echo $user_code;?></h6>
                            <a href="http://qrcoder.ru/code/?<?php echo $user_code?>&20&0"><img src="http://qrcoder.ru/code/?<?php echo $user_code?>&6&0" alt="QR Code" download></a>
                        </div>
                    </div>
                </div> 
                <div class="col-9">
                    <div class="App-card">
                        <div class="App-card-title">
                            <h5>У вас на руках</h5>
                        </div>
                        <div class="App-card-body">
                            <div class="App-center">
                                <?php
                                if ($take) 
                                    echo '<table class="table table-striped table-light">
                                        <thead>
                                            <tr>
                                                <th scope="col">Наименование</th><th scope="col">Тип</th><th scope="col">Код</th><th scope="col" style="width: 20%;">Вернуть</th>
                                            </tr>
                                        </thead>
                                        <tbody>'.$take.'</tbody></table>';
                                else
                                    echo '<h3 style="color: darkgrey;">Вы ещё ничего не получали</h3>
                                <h6><a href="search.php">Поиск оборудования...</a></h6>';?>
                            </div>
                        </div>
                    </div>
                </div>             
            </div>
        </div>
    </div>
    <div class="App-footer">
            <hr>
            <h6>About ADAS</h6>
    </div>
</body>
</html>