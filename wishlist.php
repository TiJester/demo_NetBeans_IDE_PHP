<?php
require_once ("includes/db.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <h3>Список пожеланий <b>
            <?php 
            // Имя Желающего
            echo htmlentities($_GET["user"])."<br/>";
            ?>
        </b></h3>
        <?php
  
    $wisherID = WishDB::getInstance()->get_wisher_id_by_name($_GET["user"]); 
    if (!$wisherID) 
        {
        exit("Человек " .$_GET["user"]. " не найден. Проверьте правописание и повторите попытку." ); 
        }
        ?>
        
        <!--Перечень списка желаний выбраного человека в таблице-->
        <table border="red">
        <tr>
            <th>Item 111</th>
            <th>Due Date</th>
        </tr>
        <?php
        $result = WishDB::getInstance()->get_wishes_by_wisher_id($wisherID);
        while ($row = mysqli_fetch_array($result)) {
        echo "<tr><td>" . htmlentities($row["description"]) . "</td>";
        echo "<td>" . htmlentities($row["due_date"]) . "</td></tr>\n";
        }

        ?>
        </table>
        <form name="backToMainPage" action="index.php">
            <input type="submit" value="На главную"/>
        </form>
    </body>
</html>
