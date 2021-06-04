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
        <title>Podaj token</title>
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
                <div class="div-token">
                    <h2>Podaj token aby sprawdzic swoje odpowiedzi</h2>
                    <form action="pokaz_odpowiedzi.php" id="odpowiedzi-form" method="POST">
                    <input type="text" id="token" name="token">
                    </br>
                    <input type="submit" value="Wyslij" class="view_button">
                <div>
                </form>
            </div>
        </main>
        
    </body>
    
</html>