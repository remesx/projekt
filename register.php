<?php
    session_start();

    if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		
		//Sprawdź poprawność loginu
		$login = $_POST['login'];
		
		//Sprawdzenie długości loginu
		if ((strlen($login)<3) || (strlen($login)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($login)==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		
		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
		
		//Sprawdź poprawność hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}	

        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
        
		
		//Czy zaakceptowano regulamin?
		if (!isset($_POST['regulamin']))
		{
			$wszystko_OK=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}				
		
		//Bot or not? Oto jest pytanie!
		$sekret = "6Lecsy0aAAAAAEpPxUIrrXQ7KtQzZoCH_JxTtH8h";
		
		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz = json_decode($sprawdz);
		
		if ($odpowiedz->success==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
        }

        $_SESSION['fr_login'] = $login;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_haslo1'] = $haslo1;
		$_SESSION['fr_haslo2'] = $haslo2;
		if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;

        require_once "connect.php";

        mysqli_report(MYSQLI_REPORT_STRICT);
        try
        {
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_errno!=0) {
                
                throw new Exception(mysqli_connect_errno());
            }
            else
            {
                $result = $conn->query("SELECT id FROM uzytkownicy WHERE email='$email'");
                if(!$result) throw new Exception($conn->error);

                $ile_takich_miali = $result->num_rows;
                if($ile_takich_miali>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_email']="Istnieje już konto z takim adresem email";
                     
                }

                $result = $conn->query("SELECT id FROM uzytkownicy WHERE user='$login'");
                if(!$result) throw new Exception($conn->error);

                $ile_takich_loginow = $result->num_rows;
                if($ile_takich_loginow>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_login']="Istnieje już konto z takim loginem";
                     
                }
                if($wszystko_OK==true)
                {
                    if($conn->query("INSERT INTO uzytkownicy VALUES (NULL, '$login','$haslo_hash', '$email')"))
                    {
                        $_SESSION['udanarejestracja']=true;
                        header('Location: index.php');
                    }
                    else
                    {
                        throw new Exception($conn->error);
                    }
                }

                $conn->close();
            }
        }
        catch(Exception $e)
        {
            echo '<span style="color:red;">Bląd serwera, przepraszamy za utrudnienia, spróbuj pózniej</span>';
            echo '<br /> dev inf'.$e;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Rejestracja</title> 
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        .error
        {
            color:red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
<body>
    <div class="container_login">
            <div class="login register">
            <h1>Zarejestruj się</h1>
            <form method="post">
                <label for="login">Login:</label> <br /><input type="text" value="<?php
                    if(isset($_SESSION['fr_login']))
                    {
                        echo $_SESSION['fr_login'];
                        unset($_SESSION['fr_login']);
                    }
                ?>" name="login"/> <br/>

                <?php
                    if(isset($_SESSION['e_login']))
                    {
                        echo '<div class="error">'.$_SESSION['e_login'].'</div>';
                        unset($_SESSION['e_login']);
                    }
                ?>

                <label for="email">E-mail:</label> <br /><input type="text" value="<?php
                    if(isset($_SESSION['fr_email']))
                    {
                        echo $_SESSION['fr_email'];
                        unset($_SESSION['fr_email']);
                    }
                ?>" name="email"/> <br/>

                <?php
                    if (isset($_SESSION['e_email']))
                    {
                        echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                        unset($_SESSION['e_email']);
                    }
                ?>
                

                <label for="haslo1">Haslo:</label> <br /><input type="password" name="haslo1"/> <br/>

                
                <?php
                    if (isset($_SESSION['e_haslo']))
                    {
                        echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
                        unset($_SESSION['e_haslo']);
                    }
                ?>		
                
                <label for="haslo2">Powtorz haslo:</label> <br /><input type="password" name="haslo2"/> <br/>
                <label>
                    <input type="checkbox" name="regulamin" <?php 
                        if(isset($_SESSION['fr_regulamin']))
                        {
                            echo "checked";
                            unset($_SESSION['fr_regulamin']);
                        }
                    ?>/> Akceptuje regulamin
                </label>

                <?php
                    if (isset($_SESSION['e_regulamin']))
                    {
                        echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
                        unset($_SESSION['e_regulamin']);
                    }
                ?>	
                
                <div class="g-recaptcha" data-sitekey="6Lecsy0aAAAAAJz9kSfD7vOEy2lxej3uhwxcD6NK"></div>

                <?php
                    if (isset($_SESSION['e_bot']))
                    {
                        echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                        unset($_SESSION['e_bot']);
                    }
                ?>	

                <br />
                <input type="submit" value="Zarejestruj się"/>
            </form>
        </div>
    </div>
   


</body>
</html> 