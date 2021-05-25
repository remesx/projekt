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
    $sql2 = "SELECT nr_ankiety FROM czy_odpowiedz WHERE mail_uzytkownika = '$user_mail'";
    $sql3 = "SELECT nr_ankiety, nazwa_ankiety FROM utworzone_ankiety WHERE autor = '$user_mail'";
    $sql4 = "SELECT czy_odpowiedz.nr_ankiety, utworzone_ankiety.nazwa_ankiety, czy_odpowiedz FROM czy_odpowiedz 
    INNER JOIN utworzone_ankiety ON czy_odpowiedz.nr_ankiety = utworzone_ankiety.nr_ankiety WHERE mail_uzytkownika = '$user_mail' "; 

    

    $result2 = $conn->query($sql2);
	$arr_rows = array();
    while($row = $result2->fetch_assoc())
    {
        $arr_rows[] = $row;
	}
    $_SESSION['arr_rows']=$arr_rows;
    

    $result3 = $conn->query($sql3);
	$ankiety = array();
    while($row = $result3->fetch_assoc())
    {
        $ankiety[] = $row;
	}
    $_SESSION['ankiety']=$ankiety;
    
    $result4 = $conn->query($sql4);
	$u_ankiety = array();
    while($row = $result4->fetch_assoc())
    {
        $u_ankiety[] = $row;
	}
	$_SESSION['u_ankiety']=$u_ankiety;
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
				<a class="menu " href="strona.php">Strona główna</a>
				<a class="menu" href="utworz_ankiete.php">Utwórz ankiete</a>
				<a class="menu active" href="moje_ankiety.php">Moje ankiety</a>
				<a class="menu" href="logout.php">Wyloguj</a>
			</nav>
        </header>
        <main>
            <h2>Moje ankiety</h2>
            <table id="tb_moje_ankiety">
			</table>
            <h2>Udostepnione ankiety</h2>
            <h3>Wypełnione</h3>
            <table id="tb_wypelnione_ankiety">
			</table>
            <h3>Do wypełnienia</h3>
            <table id="tb_do_wypelnienia_ankiety">
			</table>
            
        </main>
    </body>

    <script>
        var table = document.getElementById('tb_moje_ankiety');
	    //var tab2 = document.getElementById('tb_wydatki');
		table.innerHTML = "";
		//tab2.innerHTML = "";
		var complex = <?php echo json_encode($_SESSION['ankiety']); ?>;
        console.log(complex);
        
        
        for(var i = 0; i<complex.length;i++)
		{ 
			console.log("xd");
		
		    console.log("xd2");
		    var tr = document.createElement("tr");
		    var td_opis = document.createElement("td");
		    var node = document.createTextNode(complex[i]['nazwa_ankiety']);
		    td_opis.appendChild(node);

            
		    var td_podglad = document.createElement("td");
		    var td_a = document.createElement("button");
		    td_a.setAttribute("id", "numer_ankiety-"+ complex[i]['nr_ankiety']);
		    td_a.setAttribute("onclick", "view(this)");
		    td_a.setAttribute("class", "view_button");
		    var node = document.createTextNode('Podgląd');
		    td_a.appendChild(node);	
		    td_podglad.appendChild(td_a);
            
			tr.appendChild(td_opis);
			tr.appendChild(td_podglad);


			table.appendChild(tr);
			
		}
        function view(item){
            console.log(item);
            var a = item.id.split('-');
            console.log(a);

			window.location.href = "pokaz_ankiete.php?nr=" + a[1];
        }


        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

        
        var table = document.getElementById('tb_wypelnione_ankiety');
        var table2 = document.getElementById('tb_do_wypelnienia_ankiety');
	    //var tab2 = document.getElementById('tb_wydatki');
		table.innerHTML = "";
        table2.innerHTML = "";
		//tab2.innerHTML = "";
		var complex = <?php echo json_encode($_SESSION['u_ankiety']); ?>;

        
        
        for(var i = 0; i<complex.length;i++)
		{ 
		    var tr = document.createElement("tr");
		    var td_opis = document.createElement("td");
		    var node = document.createTextNode(complex[i]['nazwa_ankiety']);
		    td_opis.appendChild(node);

            
		    var td_podglad = document.createElement("td");
            var td_a = document.createElement("button");
		    td_a.setAttribute("id", "numer_ankiety-"+ complex[i]['nr_ankiety']);
            if(complex[i]['czy_odpowiedz']==0){
		        td_a.setAttribute("onclick", "uzupelnij(this)");
                var node = document.createTextNode('Uzupełnij');
            }
            else{
                td_a.setAttribute("onclick", "view2(this)");
                var node = document.createTextNode('Wyświetl');
            }

		    td_a.setAttribute("class", "view_button");
		    td_a.appendChild(node);	
		    td_podglad.appendChild(td_a);
            
			tr.appendChild(td_opis);
			tr.appendChild(td_podglad);

            if(complex[i]['czy_odpowiedz']==0)
			{
					table2.appendChild(tr);
			}
			else
			{
					table.appendChild(tr);
			}
			
			
		}

        function uzupelnij(item){
            var a = item.id.split('-');
            console.log(a);

			window.location.href = "uzupelnij_ankiete.php?nr=" + a[1];
        }

        function view2(item){
            var a = item.id.split('-');
            window.location.href = "podaj_token.php?nr=" + a[1];
        }
    </script>
</html>