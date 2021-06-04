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
    $sql2 = "SELECT autor FROM utworzone_ankiety WHERE nr_ankiety = '$nr'";
    $result = $conn->query($sql2);
    while($row = $result->fetch_assoc())
    {
          $autor = $row['autor'];
	}
    if($autor != $user_mail)
    {
        header('Location: index.php');
    }
    
    $sql3 = "SELECT nazwa_ankiety FROM utworzone_ankiety WHERE nr_ankiety = '$nr'";
    $result2 = $conn->query($sql3); 
    $row = $result2->fetch_assoc();
    
    $nazwa= $row['nazwa_ankiety'];
    $_SESSION['nazwa']=$nazwa;
   

    $sql4 = "SELECT nr_pytania, pytanie FROM pytania WHERE nr_ankiety = '$nr'";
    $result3 = $conn->query($sql4);
    while($row = $result3->fetch_assoc())
    {   
        $pytania[] = $row;
    }

    $_SESSION['pytania']=$pytania;

    $sql5 = "SELECT nr_pytania, odpowiedz FROM odpowiedzi WHERE nr_ankiety = '$nr' ORDER BY nr_pytania";
	$result4 = $conn->query($sql5);
    while($row = $result4->fetch_assoc())
    {   
        $odpowiedzi[] = $row;
    }
    if(empty($odpowiedzi))
    {   
        $odpowiedzi = 0; 
    }
    $_SESSION['odpowiedzi']=$odpowiedzi;

    $sql6 = "SELECT mail_uzytkownika, czy_odpowiedz FROM czy_odpowiedz WHERE nr_ankiety = '$nr'";
	$result5 = $conn->query($sql6);
    while($row = $result5->fetch_assoc())
    {   
        $uzytkownicy[] = $row;
    }
  
    $_SESSION['uzytkownicy']=$uzytkownicy;



    mysqli_close($conn);
    
    
}
else{
    header('Location: index.php');
}


?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Informacje o ankiecie</title>
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
                    <li><a class="menu active" href="moje_ankiety.php">Moje ankiety</a></li>
                    <li><a class="menu" href="logout.php">Wyloguj</a></li>
                </ul>
			</nav>
        </header>
        <main>
            <div class="container">
                <div class = "pytania">
                    <h2 id="nazwa">
                    </h2>
                    <table id="tb_pytania">
                    </table>
                </div>
                <div class = "container_obie border">
                    <div class ="container_wy">
                        <h3>Użytkownicy którzy wypełnili ankiete</h3>
                        <table id="tb_uzytkownicy_wypelnione" class="tabela">
                        </table>
                    <div>
                    <div class ="container_do">
                        <h3>Użytkownicy którzy nie wypełnili ankiety</h3>
                        <table id="tb_uzytkownicy_niewypelnione" class="tabela">
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </body>
    <script>
        var nazwa = "<?php echo $_SESSION['nazwa']; ?>";
        var pytania = <?php echo json_encode($_SESSION['pytania']); ?>;
        var odpowiedzi = <?php echo json_encode($_SESSION['odpowiedzi']); ?>;
        var uzytkownicy = <?php echo json_encode($_SESSION['uzytkownicy']); ?>;
        var table = document.getElementById('tb_pytania');
        table.innerHTML = "";

        var nazwa_ankiety = document.getElementById('nazwa');
        nazwa_ankiety.innerHTML = "Ankieta: " + nazwa;

        for(var i = 0; i<pytania.length;i++)
            {
                var tr = document.createElement("tr");
                var td_numer = document.createElement("td");
                var node = document.createTextNode("Pytanie " + pytania[i]['nr_pytania']+":");
                td_numer.setAttribute("class", "td_numer");
                td_numer.appendChild(node);
                tr.appendChild(td_numer);
                

                var td_pytanie = document.createElement("td");
                var node = document.createTextNode(pytania[i]['pytanie']);
                td_pytanie.setAttribute("class", "td_pytanie");
                td_pytanie.appendChild(node);
                tr.appendChild(td_pytanie);
                table.appendChild(tr);
                if(odpowiedzi != null)
                {
                    for(var j = 0; j<odpowiedzi.length; j++)
                    {
                        
                        
                        if(i+1 == odpowiedzi[j]['nr_pytania'])
                        {
                            console.log(odpowiedzi[j]['nr_pytania'])
                            var tr = document.createElement("tr");
                            var td_odpowiedz = document.createElement("td");
                            var node = document.createTextNode("Odp: " + odpowiedzi[j]['odpowiedz']);
                            td_odpowiedz.setAttribute("class", "td_odpowiedz");
                            td_odpowiedz.setAttribute("colspan", "2");
                            td_odpowiedz.appendChild(node);
                            tr.appendChild(td_odpowiedz);
                            table.appendChild(tr);
                        }
                    }
                }
            }
        
        var table1 = document.getElementById('tb_uzytkownicy_wypelnione');
        var table2 = document.getElementById('tb_uzytkownicy_niewypelnione');
        table1.innerHTML = "";
        table2.innerHTML = "";
        for(var i = 0; i<uzytkownicy.length;i++)
        {   
            console.log(uzytkownicy[i]['czy_odpowiedz']);
            console.log(typeof(uzytkownicy[i]['czy_odpowiedz']));
            var tr = document.createElement("tr");
            var td_uzytkownicy = document.createElement("td");
            var node = document.createTextNode(uzytkownicy[i]['mail_uzytkownika']);
            td_uzytkownicy.setAttribute("class", "td_uzytkownicy");
            td_uzytkownicy.appendChild(node);
            tr.appendChild(td_uzytkownicy);
            if(parseInt(uzytkownicy[i]['czy_odpowiedz']))
            {
                table1.appendChild(tr);   
            }
            else
            {
                table2.appendChild(tr);  
            }
        }
  
            




    </script>
    
</html>