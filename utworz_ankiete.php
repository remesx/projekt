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
    $kto_mail = $_SESSION['user_email'];
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
        <title>Nowa ankieta</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <header>
            <nav>
                <ul>
				    <li><a class="menu" href="strona.php">Strona główna</a></li>
                    <li><a class="menu active" href="utworz_ankiete.php">Utwórz ankiete</a></li>
                    <li><a class="menu" href="moje_ankiety.php">Moje ankiety</a></li>
                    <li><a class="menu" href="logout.php">Wyloguj</a></li>
                </ul>
			</nav>
        </header>

        
        <main>
        <div class="buttons-container">
            <button id="button" onclick="dodajPytanie()" class="buttons">Dodaj pytanie</button>
            <button id="dodaj" class="buttons" onclick="wyslij()"> Dodaj ankiete </button>
        </div>
        <form method="POST" name="ankietaForm" id="survey-form" action="wybor_uzytkownikow.php" class="form-utworz">
            <div class="form-uzytkownicy required" >
                <h3>Wybierz użytkowników którym chcesz udostępnić ankietę:</h3>
        
        <?php   

            $section = "SELECT email FROM uzytkownicy";
            $result = $conn->query($section);

            while ($row = mysqli_fetch_assoc($result))
            {
                if($row['email'] != $kto_mail){
                echo "<input type='checkbox' name='uzytkownicy[]' value='".$row['email']."'";
                echo " />";
                echo "<label>".$row['email']."</label>";
                echo "<br/>";
                }
            }

        ?>
            </div>
            <div class="form-pytania" id="form-pytania">
            <label for="name" id="name-label" >Nazwa ankiety:</label><br>
            <input type="text" id="name" name="name" required placeholder="Podaj nazwe ankiety" class="text-inputs"><br>
            

            <label for="pytanie1" id="pytanie1-label">Pytanie 1</label><br>
            <input type="text" id="pytanie1" name="pytanie1" required placeholder="Wprowadź pytanie" class="text-inputs"><br>
            </div>
        </form>
        </main>
     
        

        <script>
    var nr_pytania = 2;
    function dodajPytanie(){
        var formularz = document.getElementById("form-pytania");
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
    function wyslij(){
        var formValid = document.forms["survey-form"].checkValidity();
        console.log(formValid);
        
        if($('div.form-uzytkownicy.required :checkbox:checked').length > 0 & formValid){
            document.ankietaForm.submit();
        }
        else if(!formValid){
            alert("Wszystkie pola na temat ankiety są wymagane");
            console.log("niezaznaczone");
        }
        else
        {
            alert("Wybierz co najmniej jednego użytkownika");
        }
        
    }

    
    </script>
    </body>
  
</html>