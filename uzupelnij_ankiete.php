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
    $user_id = (int)$_SESSION['user_id'];
    $user_mail = $_SESSION['user_email'];
   
   

if(isset ($_GET["nr"]))
{
    $nr = (int)$_GET["nr"];
    $sql3 = "SELECT EXISTS(SELECT * FROM czy_odpowiedz WHERE mail_uzytkownika = '$user_mail' AND nr_ankiety = '$nr' AND czy_odpowiedz = 0)"; 
    $result2 = $conn->query($sql3);
    $row = mysqli_fetch_row($result2);

    
 
    $prawda = $row[0];

    if($prawda == '1')
    {
        $sql2 = "SELECT nr_pytania, pytanie FROM pytania WHERE nr_ankiety = '$nr'";
        $result = $conn->query($sql2);
        while($row = $result->fetch_assoc())
        {
            $pytania[] = $row;
        }
        $_SESSION['pytania']=$pytania;
        $_SESSION['nr_a']=$nr;
        
    }
    else{
        header('Location: index.php');
    }
    
        
    
    
 
    mysqli_close($conn);
    
    
}
else{
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Uzupełnij ankiete</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <nav>
                <ul>
				    <li><a class="menu" href="strona.php">Strona główna</a></li>
                    <li><a class="menu" href="utworz_ankiete.php">Utwórz ankiete</a></li>
                    <li><a class="menu" href="moje_ankiety.php">Moje ankiety</a></li>
                    <li><a class="menu" href="logout.php">Wyloguj</a></li>
                </ul>
			</nav>
        </header>

        <main>
            <div class="container">
                <form method="POST" id="uzupelnij-form" action="zapisz_odpowiedzi.php" >
                </form>    
                <input type="submit" form="uzupelnij-form" value="Zatwierdź" class="view_button"/>
            </div>
        </main>
    </body>
    <script>
          var form = document.getElementById('uzupelnij-form');
	    //var tab2 = document.getElementById('tb_wydatki');
		form.innerHTML = "";
		//tab2.innerHTML = "";
		var complex = <?php echo json_encode($_SESSION['pytania']); ?>;
        
        
        
        for(var i = 0; i<complex.length;i++)
		{ 
		    var formularz = document.getElementById("uzupelnij-form");
            var label = document.createElement("label");
            label.for = "odpowiedz-" + complex[i]['nr_pytania'];
            label.id = "odpowiedz" + complex[i]['nr_pytania'] + "-label";
            label.appendChild(document.createTextNode(complex[i]['pytanie']));
            formularz.appendChild(label);
            var input = document.createElement("input");
            input.type = "text";
            input.id =  "odpowiedz" + complex[i]['nr_pytania'] ;
            input.name =  "odpowiedz" + complex[i]['nr_pytania'] ;
            input.placeholder = "Wprowadź opowiedz";
            input.class = "text-inputs";
            input.required = true;
            formularz.appendChild(document.createElement("br"));
            formularz.appendChild(input);
            formularz.appendChild(document.createElement("br"));

			
		}
    </script>
</html>