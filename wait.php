<?
    require_once '/db/db.php';
    session_start();
    $_SESSION['title'] = "Главная";
    if (!$_SESSION['auth'])     
        header('Location: index.php');
    $type = $_SESSION['type'];
    require_once 'head.php';

?>
<body>
    <div class="navbar nav justify-content-between" style="padding: 0 5vh 0vh 5vh">
        <div class="navbar-brand"><h3><a class="nav-brand link" href="/"><b>ADAS</b></a></h3></div>
        <Form inline>          
        </Form>
        <Form inline>        
          <a class="btn btn-outline-danger" href="exit.php">Выход</a>
        </Form>
    </div>
    <div class="App">
        <div class="App-action">
            <h1><b>ADAS</b></h1>
            <?php if (!$type) echo '<h3>Ваш аккаунт ещё на рассмотрении.</h3>';
                elseif ($type > 0 && $type < 3) echo '<h3>Ваш аккаунт был потверждён</h3><h6><a href="main.php">Перейти на главную</a></h6>';
                elseif ($type == 3) echo '<h3>Ваш аккаунт был рассмотрен но данные были введены неверно</h3><h4 style="color: white;">Свяжитесь с <a href="http://vk.com/zb.venom">администрацией</a>.</h4>';?>
        </div>
    </div>
</body>
</html>