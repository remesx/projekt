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

    $_SESSION['nr_a']=$nr;
    
    
 
    mysqli_close($conn);
    
    
    }
    else{
        header('Location: index.php');
    }

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
        <main>
            <h2>Podaj token aby sprawdzic swoje odpowiedzi</h2>
            <form action="pokaz_odpowiedzi.php" id="odpowiedzi-form" method="POST">
            <input type="text" id="token" name="token">
            <input type="submit" value="Wyslij">
            </form>
        </main>
        
    </body>
    
</html>