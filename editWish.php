<?php
    session_start(); 
    if (!array_key_exists("user", $_SESSION)) 
            {
            header('Location: index.php'); exit; 
            }
            
    require_once("Includes/db.php");
        $wisherID = WishDB::getInstance()->get_wisher_id_by_name($_SESSION['user']);

        $wishDescriptionIsEmpty = false;
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        $wish = array("id" => $_POST["wishID"], "description" => 
                $_POST["wish"], "due_date" => $_POST["dueDate"]);
            else if (array_key_exists("wishID", $_GET))
            $wish = mysqli_fetch_array(WishDB::getInstance()->get_wish_by_wish_id($_GET["wishID"]));
            else
            $wish = array("id" => "", "description" => "", "due_date" => "");
               header('Location: editWishList.php' );
               exit;            
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>

       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <?php 
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        $wish = array("description" => $_POST["wish"],"due_date" => $_POST["dueDate"]);
        else
        $wish = array("description" => "",
        "due_date" => ""); 
        ?>
        <form name="editWish" action="editWish.php" method="POST">
            <input type="hidden" name="wishID" value="<?php echo $wish["id"];?>" />
            Опишите свое желание: <input type="text" name="wish"  value="<?php echo $wish['description'];?>" /><br/>
            <?php  if ($wishDescriptionIsEmpty) echo "Введите описание<br/>";?>
            Когда вы хотите его получить? <input type="text" name="dueDate" value="<?php echo $wish['due_date']; ?>"/><br/>
            <input type="submit" name="saveWish" value="Сохранить изменения"/>
            <input type="submit" name="back" value="Вернуться к списку"/>
        </form>
    </body>
</html> 