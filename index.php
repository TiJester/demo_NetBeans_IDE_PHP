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
<script>
function showHideLogonForm() {
    if (document.all.logon.style.visibility == "visible"){
        document.all.logon.style.visibility = "hidden";
        document.all.myWishList.value = "Мой список желаний >>";
    } 
    else {
        document.all.logon.style.visibility = "visible";
        document.all.myWishList.value = "<< Мой список желаний";
    }
}
function showHideShowWishListForm() {
    if (document.all.wishList.style.visibility == "visible") {
        document.all.wishList.style.visibility = "hidden";
        document.all.showWishList.value = "Показать список желаний >>";
    }
    else {
        document.all.wishList.style.visibility = "visible";
        document.all.showWishList.value = "<< Показать список желаний";
    }
}
</script>	
<!DOCTYPE HTML>
<!--Главная страница проекта-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="wishlist.css" type="text/css" rel="stylesheet" media="all" />
        <title>Заголовок</title>
    </head>
    <body>
        <div class="showWishList">
        <input type="submit" name="showWishList" value="Показать список желаний >>" onclick="javascript:showHideShowWishListForm()"/>
        <form name="wishList" action="wishlist.php" method="GET" style="visibility:hidden">
            <input type="text" name="user" />
            <input type="submit" value="Показать" />
        </form>
        </div>
        <br>У Вас пока нет списка желаний?! </br><a href="createNewWisher.php">Создать сейчас</a></br></br> 
<!--        Форма входа-->
        <div class="logon">
        <input type="submit" name="myWishList" value="Мой список желаний >>" onclick="javascript:showHideLogonForm()"/></br> </br>
        <form name="logon" action="index.php" method="POST" 
            style="visibility:<?php if ($logonSuccess) echo "hidden";
            else echo "visible";?>">
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
        </div>
    </body>
</html>
