<?php 
    require_once '/db/db.php';
    $key = $_POST['key'];
    $auditory = $_POST['auditory'];
    $user = $_POST['user'];
    $device = $_POST['device'];
    if($key=='2') {
        $sql = "SELECT * FROM `auditory` WHERE room='$auditory' AND code='$device' AND give=0";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        $row = mysqli_fetch_array($result);
        if(empty($row))
            printf("ERROR._none:<br>%s", $device);
        else {
            $sql = "UPDATE `auditory` SET give='$user' WHERE room='$auditory' AND code='$device'";
            $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
            printf("Получение оборудования #%s из аудиотрии #%s
Пользователь #%s успешно получил оборудование!", $device, $auditory, $user);
        }
    } elseif($key=='1') {
        $sql = "SELECT * FROM `auditory` WHERE room='$auditory' AND code='$device' AND give='$user'";
        $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
        $row = mysqli_fetch_array($result);
        if(empty($row))
            printf("Оборудования #%s из аудиотрии #%s
На месте или было выдано не Вам!", $device, $auditory);
        else {
            $sql = "UPDATE `auditory` SET give=0 WHERE room='$auditory' AND code='$device'";
            $result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
            printf("Возврат оборудования #%s из аудиотрии #%s
Пользователь #%s успешно вернул оборудование!", $device, $auditory, $user);
        }
    }


?>