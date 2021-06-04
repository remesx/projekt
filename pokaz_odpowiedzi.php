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
        die("Connection failed: " . $conn->connect_error);
    }
    $user_id = (int)$_SESSION['user_id'];
    $user_mail = $_SESSION['user_email'];



    $nr_ankiety = $_SESSION['nr_a'];
    //unset($_SESSION['nr_a']);
    
    $token = $_POST['token'];
    
    $token_hash = hash('sha256', $token);
   

    
    $sql = "SELECT nr_pytania, odpowiedz FROM odpowiedzi WHERE nr_ankiety = '$nr_ankiety' AND token = '$token_hash'";
    $result = $conn->query($sql);
    if((mysqli_num_rows($result)!=0)){
        while($row = $result->fetch_assoc())
        {   
            $odpowiedzi[] = $row;
        }
        $licz = count($odpowiedzi);
        $sql2 = "SELECT nr_pytania, odpowiedz_hash FROM odphash WHERE nr_ankiety = '$nr_ankiety'";
        $result2 = $conn->query($sql2);
        while($row = $result2->fetch_assoc())
        {
              $odphash[] = $row;
        }
        
        
        for($i = 0; $i < $licz; $i++)
        {
            $odptoken = $odpowiedzi[$i]["odpowiedz"].$token;
            $odphashed =  hash('sha256', $odptoken);
            for($j = 0; $j < count($odphash); $j++)
            {
                if($odphashed == $odphash[$j]["odpowiedz_hash"] && $odphash[$j]["nr_pytania"] == $odpowiedzi[$i]["nr_pytania"])
                {
                    $tablica[] = $odpowiedzi[$i];
                }
            }
        }
        $licz2 = (int)count($tablica);
    
    
        $sql3 = "SELECT nr_pytania, pytanie FROM pytania WHERE nr_ankiety = '$nr_ankiety'";
        $result3 = $conn->query($sql3);
        $rows = mysqli_num_rows($result3);
        if($licz2 == $rows){
            $tekst = "Odpowiedzi nie zostały zmanipulowane";
            $_SESSION['tablica'] = $tablica; 
            while($row = $result3->fetch_assoc())
            {
                  $nowytab[] = $row;
            }
            $_SESSION['nowytab']=$nowytab;
        }
        else
        {
            $tekst= "Odpowiedzi zostały zmanipulowane";
            $_SESSION['nowytab'] = [0];
        }
    
        $_SESSION['tekst']=$tekst;

        
    }
    else
        {
            $tekst =  "Błedny token lub odpowiedzi zostały zmanipulowane";
            $_SESSION['nowytab'] = [0];
            $_SESSION['tablica'] = [0]; 
            $_SESSION['tekst']=$tekst;
        }




    
    
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Moje odpowiedzi</title>
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
                <h2 id="h2_czyste">Odpowiedzi</h2>
                <table id="tb_odpowiedzi">
                </table>
            <div>
        </main>
    </body>
    <script>
        console.log("test2");
        var tekst = "<?php echo $_SESSION['tekst']; ?>";
        var table = document.getElementById('tb_odpowiedzi');
        table.innerHTML = "";

	 	var tr = document.createElement("tr");
	 	var td_opis = document.createElement("td");
	 	var node = document.createTextNode(tekst);
	 	td_opis.appendChild(node);
        td_opis.setAttribute("class", "tekst");
        tr.appendChild(td_opis);
        table.appendChild(tr);

        if(tekst == "Odpowiedzi nie zostały zmanipulowane")
        {
            var complex = <?php echo json_encode($_SESSION['tablica']); ?>;
            var complex2 = <?php echo json_encode($_SESSION['nowytab']); ?>;

            for(var i = 0; i<complex.length;i++)
            {
                var tr = document.createElement("tr");
                var td_pytanie = document.createElement("td");
                var node = document.createTextNode(complex2[i]['pytanie']);
                td_pytanie.setAttribute("class", "td_pytanie");
                td_pytanie.appendChild(node);
                tr.appendChild(td_pytanie);


                

                var td_odpowiedz = document.createElement("td");
                var node = document.createTextNode(complex[i]['odpowiedz']);
                td_odpowiedz.setAttribute("class", "td_odpowiedz");
                td_odpowiedz.appendChild(node);
                tr.appendChild(td_odpowiedz);
                table.appendChild(tr);
            }
        }
    </script>
</html>