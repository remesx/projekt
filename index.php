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
<body>

<h1>My First Heading</h1>
<p>My first paragraph.</p>
    <form action="login.php" method="post">
        Login: </br> <input type="text" name="login"/> <br/>
        Hasło: </br> <input type="password" name="haslo"/> <br/><br/>
        <input type="submit" value="Zaloguj się"/>
    </form>
    <a href="register.php"><button>Zarejestruj się</button></a>
    <?php
    if(isset($_SESSION['blad']))
        echo $_SESSION['blad'];
    ?>

</body>
</html> 