<?php
    session_start();

    if((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
    {
        header('Location: index.php');
        exit();
    }
    require_once "connect.php";

    // Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    else
    {
        $login=$_POST['login'];
        $haslo=$_POST['haslo'];

        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
    

         
        if($result = $conn->query(sprintf("SELECT * FROM uzytkownicy WHERE user = '%s' ",mysqli_real_escape_string($conn,$login))))
        {
            $ilu_userow = $result->num_rows;
            if($ilu_userow>0)
            {   
                $wiersz = $result->fetch_assoc();
                if(password_verify($haslo, $wiersz['pass']))
                {
                    $_SESSION['zalogowany'] = true;

                
                    $_SESSION['user'] = $wiersz['user'];
                    $_SESSION['user_id'] = $wiersz['id'];
                    $_SESSION['user_email'] = $wiersz['email'];

                    //$kto = (int)$_SESSION['user_id'];
                   // $sql2 = "SELECT * FROM wartosci WHERE id_uzytkownika = '$kto'";

                    //$result2 = $conn->query($sql2);
                    //$arr_rows = array();
                   // while($row = $result2->fetch_assoc())
                   // {
                   //     $arr_rows[] = $row;
                   // }
                   // $_SESSION['arr_rows']=$arr_rows;

                    unset($_SESSION['blad']);
                    $result->free_result();
                   // $result2->free_result();
                    header('Location: strona.php');
                }
                else
                {
                    $_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
                    header ('Location: index.php');
                }
            }
            else
            {
                $_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
                header ('Location: index.php');
            }
        }
        


        $conn->close();
    }

    
?>