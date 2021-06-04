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
    if(!isset($_SESSION['nr_a'])){
        header('Location: index.php');
		exit();
    }
    $nr_a = $_SESSION['nr_a'];
    unset($_SESSION['nr_a']);
    unset($_SESSION['pytania']);

    function password_generate($chars) 
    {
       $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz!@#$%&*?';
       return substr(str_shuffle($data), 0, $chars);
    }
    $token = password_generate(10);

    $token_hash = hash('sha256', $token);

    $values = array();
    foreach($_POST as $field => $value) {
        $values[] = $value;
    }

    for ($i = 0; $i <sizeof($_POST) ;$i++)
    {   
        $nr_pytania = $i + 1;
        $nr_ankiety = (int)$nr_a;
        $sql2="INSERT INTO odpowiedzi VALUES (NULL, '$nr_ankiety', '$nr_pytania', '$values[$i]', '$token_hash')";
        if ($conn->query($sql2) === TRUE ) {
        //    echo "Dodano obiekt";
           // header('Location: strona.php');
        }
        else 
        {
            echo "Błąd";
        }
    }
    $sql3 = "UPDATE czy_odpowiedz SET czy_odpowiedz = 1 WHERE nr_ankiety = '$nr_a' AND mail_uzytkownika = '$user_mail'";
    if ($conn->query($sql3) === TRUE ) {
        //    echo "Dodano obiekt";
           // header('Location: strona.php');
    }
    else 
    {
        echo "Błąd2";
    }
    for ($i = 0; $i <sizeof($_POST) ;$i++)
    {   
        $nr_pytania = $i + 1;
        $nr_ankiety = (int)$nr_a;
        $odp= $values[$i].$token;
        $odp_hash = hash('sha256', $odp);

        $sql4="INSERT INTO odphash VALUES (NULL, '$odp_hash','$nr_ankiety', '$nr_pytania')";
        if ($conn->query($sql4) === TRUE ) {
        
        }
        else 
        {
            echo "Błąd3";
        }
    }
    
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Zapisz token</title>
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
                <div>
                    <h2>Zapisz poniższy token aby zweryfikowac odpowiedzi:</h2>
                    <h3>
                    <?php
                        echo $token;
                    ?>
                <div>
                </h3>
            </div>
        </main>
    </body>
</html>
   