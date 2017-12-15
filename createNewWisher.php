<?php
// подключаем класс WishDB из db.php
require_once ("includes/db.php");

/** учетные данные подключения к базе данных */
$dbHost="localhost"; //on MySql 
$dbXeHost="localhost/XE";
$dbUsername="root";  //имя пользователя к бд
$dbPassword=""; //пароль пользователя к БД
/** Другие переменные */ 
$userNameIsUnique = true; 
$passwordIsValid = true; 
$userIsEmpty = false; 
$passwordIsEmpty = false; 
$password2IsEmpty = false;

    /* Убедитесь, что страница была запрошена сама по себе с помощью метода POST. */
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        /** Проверьте, заполнил ли пользователь имя пользователя в текстовом поле «user» */
        if ($_POST["user"]=="") 
            {
            $userIsEmpty = true; 
            }
            
//  Оптимизация кода для вызова из класса 15122017 
    $wisherID = WishDB::getInstance()->get_wisher_id_by_name($_POST["user"]);
    if ($wisherID) 
        {
       $userNameIsUnique = false;
        }
            
//    /** Создание соединения с базой данных */
//    $con = mysqli_connect($dbHost, $dbUsername, $dbPassword);
//    if (!$con) 
//        {
//        exit('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
//        }
//    /** установлен набор клиентских символов по умолчанию */ 
//    mysqli_set_charset($con, 'utf-8'); 
//    /** Убедитесь, что пользователь, чье имя совпадает с полем пользователя, уже существует */
//    mysqli_select_db($con, "wishlist");
//    $user = mysqli_real_escape_string($con, $_POST["user"]);
//    $wisher = mysqli_query($con, "SELECT id FROM wishers WHERE name='".$user."'");
//    $wisherIDnum=mysqli_num_rows($wisher);
//    if ($wisherIDnum) 
//        {
//        $userNameIsUnique = false;
//        }  
            
    /** Проверьте, правильно ли введен пароль и подтверждено ли оно */
    if ($_POST["password"]=="") 
        {
        $passwordIsEmpty = true;
        }
    if ($_POST["password2"]=="") 
        {
        $password2IsEmpty = true;
        }
    if ($_POST["password"]!=$_POST["password2"]) 
        {
        $passwordIsValid = false;
        }
/** Убедитесь, что логические значения показывают, что входные данные были успешно проверены. 
 ** Если данные были успешно проверены, добавьте их как новую запись в базу данных «wishers». 
 ** После добавления новой записи закройте соединение и перенаправьте приложение на editWishList.php. */
        
//  Оптимизация кода для вызова из класса 15122017 
    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid) 
        {
        WishDB::getInstance()->create_wisher($_POST["user"], $_POST["password"]);
        session_start(); $_SESSION['user'] = $_POST['user']; // работа с сессией
        header('Location: editWishList.php' );
        exit;
        }
//    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid) 
//        {
//        $password = mysqli_real_escape_string($con, $_POST['password']);
//        mysqli_select_db($con, "wishlist");
//        mysqli_query($con, "INSERT wishers (name, password) VALUES ('" . $user . "', '" . $password . "')");
//        mysqli_free_result($wisher);
//        mysqli_close($con);
//        header('Location: editWishList.php');
//        exit;
//        }
    }
?>

<html> 
    <head> 
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <title></title> 
    </head> 
    <body>
        Добро пожаловать!<br>
        <form action="createNewWisher.php" method="POST">
        Твое имя: <input type="text" name="user"/><br/>
    <?php
    if ($userIsEmpty) 
        {
        echo ("<b style='color:#ff0900'>Введите пожайлуста свое имя!</b>");
        echo ("<br/>");
        }                
    if (!$userNameIsUnique) 
        {
        echo ("<b style='color:#ff0900'>Пользователь с таким именем уже существет</b>");
        echo ("<br/>");
        }
    ?> 
        Пароль: <input type="password" name="password"/><br/>
    <?php 
    if ($passwordIsEmpty) 
        {
        echo ("<b style='color:#ff0900'>Введите пожайлуста пароль!</b>"); echo ("<br/>"); 
        } 
    ?>
        Пожалуйста, подтвердите свой пароль: <input type="password" name="password2"/><br/>
    <?php
     if ($password2IsEmpty) 
        {
         echo ("<b style='color:#ff0900'>Подтвердите пароль</b>");
         echo ("<br/>");    
        }                
     if (!$password2IsEmpty && !$passwordIsValid) 
        {
         echo  ("<b style='color:#ff0900'>Пароли не совпадают</b>");
         echo ("<br/>");  
        }                 
    ?>
        <input type="submit" value="Регистрация"/>
        </form>
        
        <form name="backToMainPage" action="index.php">
            <input type="submit" value="На главную"/>
        </form>
    </body> 
</html>
