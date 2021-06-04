<?php
    session_start();

    if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
    {
        header('Location: strona.php');
        exit();
    }
    
    if(isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
    if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if(isset($_SESSION['fr_regulamin'])) unset($_SESSION['fr_regulamin']);

    if(isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
    if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
    if(isset($_SESSION['e_haslo'])) unset($_SESSION['e_haslo']);
    if(isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
    if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Proejkt</title> 
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
    <div class="container_login">
        <div class="login">
        <h1>Zaloguj się</h1>
        
            <form action="login.php" method="post">
                <label for="login"> Login:</label> </br> <input type="text" name="login"/> <br/>
                <label for="haslo"> Hasło:</label> </br> <input type="password" name="haslo"/> <br/><br/>
                <?php
            if(isset($_SESSION['blad']))
                echo $_SESSION['blad'];
            ?>
                <input type="submit" value="Zaloguj się"/>
                <a href="register.php">Zarejestruj się</a>
                
            </form>
            
            
        <div>
    <div>
</body>
</html> 