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
<html lang="de">
    <head>
        <title>Stronka</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    </head>
    <body>
        <header>
            <nav>
				<a class="menu" href="strona.php">Strona główna</a>
				<a class="menu active" href="utworz_ankiete.php">Utwórz ankiete</a>
				<a class="menu" href="moje_ankiety.php">Moje ankiety</a>
				<a class="menu" href="logout.php">Wyloguj</a>
			</nav>
        </header>

        <?php
            function password_generate($chars) 
            {
              $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz!@#$%&*?';
              return substr(str_shuffle($data), 0, $chars);
            }
              echo password_generate(10)."\n";
        ?>
        
        <form method="POST" id="survey-form" action="wybor_uzytkownikow.php" >
            <label for="name" id="name-label" >Nazwa ankiety:</label><br>
            <input type="text" id="name" name="name" required placeholder="Podaj nazwe ankiety" class="text-inputs"><br>
            <?php   
    
    $section = "SELECT email FROM uzytkownicy";
    $result = $conn->query($section);

    while ($row = mysqli_fetch_assoc($result))
   {
    echo "<tr><td>";
    echo "<input type='checkbox' name='uzytkownicy[]' value='".$row['email']."'";
    echo " />";
    echo $row['email'];

    echo "</td></tr><br/>";
   }

?>

            <label for="pytanie1" id="pytanie1-label">Pytanie 1</label><br>
            <input type="text" id="pytanie1" name="pytanie1" required placeholder="Wprowadź pytanie" class="text-inputs"><br>
            
        </form>
        <button id="button" onclick="dodajPytanie()">Dodaj pytanie</button>
        <input type="submit" form="survey-form" value="Dodaj ankiete"/>
        
     
        

        
    </body>
    <script type='text/javascript'>
    var nr_pytania = 2;
    function dodajPytanie(){
        var formularz = document.getElementById("survey-form");
        var label = document.createElement("label");
        label.for = "pytanie" + nr_pytania;
        label.id = "pytanie" + nr_pytania + "-label";
        label.appendChild(document.createTextNode("Pytanie " + nr_pytania));
        formularz.appendChild(label);
        var input = document.createElement("input");
        input.type = "text";
        input.id =  "pytanie" + nr_pytania ;
        input.name =  "pytanie" + nr_pytania ;
        input.placeholder = "Wprowadź pytanie";
        input.class = "text-inputs";
        input.required = true;
        formularz.appendChild(document.createElement("br"));
        formularz.appendChild(input);
        formularz.appendChild(document.createElement("br"));
        nr_pytania++;
    }

    
    </script>
</html>