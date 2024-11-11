<?php
session_start();
require_once("db.php");

// verification du type de requete

function handlepostrequest($cnx){

if($_SERVER["REQUEST_METHOD"]!=="POST"){

     return;
}

// recuperation des donnes et validation du formulaire du formulaire

$mailconnect = filter_input(INPUT_POST,"mailconnect",FILTER_SANITIZE_EMAIL);
$mdpconnect = $_POST["mdpconnect"];

// verification des champs

if(empty($mailconnect) || empty($mdpconnect)){

    return"tous les hamps doivents etre remplir";
}
  return authenticateUser($cnx,$mailconnect,$mdpconnect);
}

 function authenticateUser($cnx,$mailconnect,$mdpconnect){

    // verification de l'exixtence de l'utilisateur dans la bd

    $requser = $cnx->prepare("SELECT * FROM menbres WHERE mail = :mailconnect");
     $requser->execute(compact('mailconnect'));
     $userexist = $requser->rowCount();


     if($userexist!==1){

      return"mauvais mail ";
     }

     
     $userinfo = $requser->fetch();
     if( !password_verify($mdpconnect,$userinfo["motdepasse"])){

     return"mauvais mots de passe";

     }
    //  definition de variable de sessions

    $_SESSION["id"] = $userinfo["id"];
     $_SESSION["pseudo"] = $userinfo["pseudo"]; 
     $_SESSION["mail"] = $userinfo["mail"]; 

     header( "location: profile.php?id=" .$_SESSION["id"]);
exit();
}


  
$erreur = handlepostrequest($cnx);

?>

<html>
<head>
  <title>TUTO PHP</title>
  <meta charset="utf-8">
</head>

<body>
  <div align="center">
    <h2>Connexion</h2>
    <br /><br />
    <form method="POST" action="">
      E-mail : <input type="email" name="mailconnect" placeholder="Mail" /> <br><br>
      PassWord : <input type="password" name="mdpconnect" placeholder="Mot de passe" />
      <br /><br />
      <input type="submit" value="Se connecter !" />
    </form>
    <?php
         if (isset($erreur)) {
            echo '<font color="red">' . $erreur . "</font>";
         }
         ?>
  </div>
</body>

</html>