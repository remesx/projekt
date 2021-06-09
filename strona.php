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
<html lang="pl">
    <head>
        <title>Strona główna</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <nav>
                <ul>
				    <li><a class="menu active" href="strona.php">Strona główna</a></li>
                    <li><a class="menu" href="utworz_ankiete.php">Utwórz ankiete</a></li>
                    <li><a class="menu" href="moje_ankiety.php">Moje ankiety</a></li>
                    <li><a class="menu" href="logout.php">Wyloguj</a></li>
                </ul>
			</nav>
        </header>
        <main><div id="h5style"><h5>Twórz i udostępniaj ankiety</h5></div></main>
    </body>
</html>