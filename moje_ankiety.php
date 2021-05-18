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
    $kto2 = $_SESSION['user_mail'];
	$sql2 = "SELECT nr_ankiety FROM czy_odpowiedz WHERE mail_uzytkownika = '$kto2'";

    $result2 = $conn->query($sql2);
	$arr_rows = array();
    while($row = $result2->fetch_assoc())
    {
        $arr_rows[] = $row;
	}
    $_SESSION['arr_rows']=$arr_rows;
	
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
            
        </main>
    </body>
</html>