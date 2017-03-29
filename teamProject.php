<!-- trello: https://trello.com/b/UZgfuW1d/teamproject -->
<!-- Git: https://github.com/Darkjaky/TeamProject.git --> 
<!-- We worked on the same C9 workspace, and we communicate by just speaking -->

<?php

    session_start();
    $host = "localhost";
    $dbname = "TeamProject";
    $username = "pego3791";
    $password = "";
    $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password) or die ('Could not connect: '.$host);
    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Team Project</title>
	    <meta charset="utf8">  
	    <link rel="stylesheet"  href="teamProject.css">
    </head>
    <body>
        <h1> IKEA <br> Buy Online! </h1>
        <div>
        <form action="teamProject.php" method="post">
            <br> &nbsp 
                Min Price: &nbsp  <input type="text" name="minPrice" value="" />
             &nbsp 
                Max Price: &nbsp  <input type="text" name="maxPrice" value="" />
             &nbsp 
                Quantity mini: <input type="text" name="Quantity" value="" />
            <br> &nbsp 
            <p> &nbsp  Sort by : 
            <input checked="" type="radio" name="program" value="Price" id="numb1" /> 
                <label for="numb1">Price Ascendant</label>
            <input  type="radio" name="program" value="blabla" id="numb3" /> 
                <label for="numb3">Price Descendant</label>
            <input type="radio" name="program" value="Name" id="numb2"  /> 
                <label for="numb2">Product Name</label> </p>
           <div style="text-align:center;">
           <br>
           
        <?php
        $test="";
        
        if(isset( $_POST["program"]))
        {
            $sort = $_POST["program"];
            if($sort == "blabla")
            {
                $sort = "Price";
                $test = " DESC";
            }
        }
        
        echo "<div>";
        if($sort == "")
            $sort = 'Price';
        $sql = "SELECT * FROM Product ORDER BY ". $sort. $test;
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        
        echo "<table class =\"tableau\">";
        echo "<tr class =  \"bold\">
                <td>Product Name  </td>
                <td>Quantity</td>
                <td>Price</td>
                <td>Add to cart</td>
            </tr>";
        
        $col = 1;
        
        while ($row = $stmt->fetch())
        {
            $description[$row["IdProduct"]-1] = $row["Description"];
            
            echo '<form action="#" method="post">';
            if( $row["Price"] <= $_POST["maxPrice"] || $_POST["maxPrice"] == "")
            {
                if( $row["Price"] >= $_POST["minPrice"] || $_POST["minPrice"] == "")
                {
                    if( $row["Quantity"] >= $_POST["Quantity"] || $_POST["Quantity"] == "")
                    {
                        echo "<tr class= \"col"  . $col . "\" >";
                            echo "<td><a href='?desc=" . $row["IdProduct"] . "'> ";
                            echo $row["Name"] . "</a></td>" ;
                            echo "<td>" . $row["Quantity"] . "</td>" ;
                            echo "<td>". $row["Price"] . "</td>" ;
                            echo '<td><input type="checkbox" name="add[]" value="' . $row['Name'] . '"></td>';
                        echo "</tr>";
                        if($col == 1)
                        {
                            $col++;
                        }
                        else 
                        {
                            $col--;
                        }
                    }
                }
            }
        }
        echo "</table>";
        echo "</div>";
        echo "</div>";
        if(isset($_GET["desc"]))
        {
            echo "<div style='float:left; padding-bottom:10px;'>";
            $entier = $_GET["desc"]-1;
            echo "<p>" . $description[$entier] . "</p>";
            echo "</div>";
        }
        
        $add = $_POST['add'];
        if(!empty($add)){
            foreach($add as $elm)
            {
                $sql = "SELECT * FROM Product WHERE Name='" . $elm . "'";
                $stmt = $dbConn->prepare($sql);
                $stmt->execute();
                
                while ($row = $stmt->fetch())
                {
                     if ($_SESSION['cart'] == null)
                        $_SESSION['cart'] = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                    $_SESSION['cart'][$row['IdProduct'] - 1] += 1;
                }
            }
        }
        
        if($_POST['resetCart'] == "Reset Cart")
            $_SESSION['cart'] = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        
        echo '<div class="cart">';
        echo '<h2> &nbsp Current Cart: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp </h2>';
        for ($i = 0; $i < 36; ++$i)
        {
            $sql = "SELECT * FROM Product WHERE IdProduct='" . ($i+1) . "'";
            $stmt = $dbConn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch())
            {
                if ($_SESSION['cart'][$i] != 0)
                {
                    echo $row['Name'] . ' -  Quantity: ' . $_SESSION['cart'][$i] . ' -  Price: $' . ($row['Price'] * $_SESSION['cart'][$i]) . '<br>';
                    $total += $row['Price'] * $_SESSION['cart'][$i];
                }
            }
        }
        echo '<h2> &nbsp Total: $' . $total .'</h2><br></div>';
        ?>
        
        <div class="float-right">
        <br><br>
            <input type="submit" name="valide" value="Enter"/>
            <input type="reset" name="Annuler" value="Reset"/>
            <button name="resetCart" value="Reset Cart"> Reset Cart</button>
       </div>
    </form>
    </body>
</html>