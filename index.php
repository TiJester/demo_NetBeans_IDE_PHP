<?php

require_once("Includes/db.php");
$logonSuccess = false;

// проверять учетные данные пользователя
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (WishDB::getInstance()->verify_wisher_credentials($_POST['user'], $_POST['userpassword']));
    if ($logonSuccess == true) {
        session_start();
        $_SESSION['user'] = $_POST['user'];
        header('Location: editWishList.php');
        exit;
    }
}
?>
<!DOCTYPE HTML>
<!--Главная страница проекта-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Заголовок</title>
    </head>
    <body>
        <form name="wishList" action="wishlist.php">
            Показать список желаний: <input type="text" name="user" value="" />
            <input type="submit" value="Показать" />
        </form>
        <br>У Вас пока нет списка желаний?! <a href="createNewWisher.php">Создать сейчас</a> 
<!--        Форма входа-->
        <form name="logon" action="index.php" method="POST" >
            <div style="outline: 2px solid #000; display: inline-block">
            <p>Имя: <input type="text" name="user"></p>
            <p>Пароль  <input type="password" name="userpassword"></p>
            <p><b style='color:#ff0900'>
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST") 
                { 
                if (!$logonSuccess) echo "Недопустимое имя и / или пароль"; 
                } 
                ?>
            </b></p>    
            <input type="submit" value="Изменить список желаний">
            </div>
        </form>
    </body>
</html>
