<?php
    session_start();
	require_once('connect.php');
    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
		exit();
	}
	$conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);}
    $kto = (int)$_SESSION['user_id'];
    $kto2 = $_SESSION['user_email'];
   
    
        echo sizeof($_POST);
        echo $kto2;  

        $values = array();
        foreach($_POST as $field => $value) {
            $values[] = $value;
        }
        $sql="INSERT INTO utworzone_ankiety VALUES (NULL, '$kto2', '$values[1]')";
        $sql_numer = "SELECT nr_ankiety FROM utworzone_ankiety WHERE nazwa_ankiety = '$values[1]'";
      
        $name = $_POST['uzytkownicy'];

        // optional
        // echo "You chose the following color(s): <br>";
        
     


        
        

        $nr_ankiety = 0;

        if ($conn->query($sql) === TRUE ) {
            $results = $conn -> query($sql_numer);
            
            while($row = $results->fetch_assoc()) {

                $nr_ankiety = $row['nr_ankiety'];
              }
            echo $nr_ankiety;
        }
        else 
        {
            echo "Błąd";
        }

        for ($i = 2; $i <sizeof($_POST) ;$i++)
        {
            $nr_pytania = $i -1;
           $sql2="INSERT INTO pytania VALUES (NULL, '$nr_ankiety', '$nr_pytania', '$values[$i]')";
           if ($conn->query($sql2) === TRUE ) {
        //    echo "Dodano obiekt";
           // header('Location: strona.php');
        }
        else 
        {
            echo "Błąd";
        }
        }
        foreach ($name as $uzytkownicy){ 
            $sql3="INSERT INTO czy_odpowiedz VALUES (NULL, '$nr_ankiety', '$uzytkownicy', '0')";
           if ($conn->query($sql3) === TRUE ) {
            
           header('Location: moje_ankiety.php');
        }
        }

        
        mysqli_close($conn);
        
        
        
    

 
  //echo $c; echo '<br />';
  //echo $d;
	/*$sql2 = "SELECT * FROM wartosci WHERE id_uzytkownika = '$kto'";
     
    $result2 = $conn->query($sql2);
	$arr_rows = array();
    while($row = $result2->fetch_assoc())
    {
        $arr_rows[] = $row;
    }
    $_SESSION['arr_rows']=$arr_rows;*/
	
?>


<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Stronka</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    </head>
    <body>
        <header>
            <nav>
				<a class="menu active" href="strona.php">Strona główna</a>
				<a class="menu" href="utworz_ankiete.php">Utwórz ankiete</a>
				<a class="menu" href="moje_ankiety.php">Moje ankiety</a>
				<a class="menu" href="logout.php">Wyloguj</a>
			</nav>
        </header>
        
    </body>
    
</html>