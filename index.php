      <?php


require_once("db.php");

if($_SERVER["REQUEST_METHOD"] ==="POST") {


    $pseudo =strip_tags($_POST["pseudo"]);

    $mail =filter_input(INPUT_POST,"mail",FILTER_SANITIZE_EMAIL);

    $mail2 =filter_input(INPUT_POST,"mail2",FILTER_SANITIZE_EMAIL);

    $mdp =$_POST["mdp"];

    $mdp2 =$_POST["mdp2"];

    // verification de tout les champs

    function register($pseudo,$mail,$mail2,$mdp,$mdp2){

        global $cnx;

        if(empty($pseudo) || empty($mail) || empty($mail2) || empty($mdp) || empty($mdp2)){


           

            return"tous les champs doivent etre remplis.";
        }
     // verification du pseudo
     if(strlen($pseudo)>255){
        return "votre pseudo ne doit par depasser 255 caractere";
     }

            $sql ="SELECT*FROM menbres WHERE pseudo =?";
            $reqpseudo = $cnx->prepare($sql);
            $reqpseudo->execute([$pseudo]);

            // $reqpseudo->fetch()

            if($reqpseudo->rowCount()>0){

                return"ce pseudo es deja utiliser";
            }

            // verification du mail

            if($mail!=$mail2){
                return"les mails ne correspondes par";
            }
            if(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
                return"adresse email invalide";
            }

            // fn($mail)=>!filter_var($mail,FILTER_VALIDATE_EMAIL)??"addresse email invalide";

             $reqmail =$cnx->prepare("SELECT*FROM menbre WHERE mail = ?");
             $reqmail->execute([$mail]);
             if($reqmail->rowCount()>0){

                return"Adresse mail deja utiliser!";
             }
            
// Vérification du mot de passe
    if(strlen($mdp)<8 || !preg_match("#[0-9]+#", $mdp) || !preg_match("#[a-zA-Z]+#", $mdp)){
      
      return "Votre mot de passe doit contenir au moins 8 caractères et contenir une lettre et un chiffre.";
    }
    
    
    if ($mdp!== $mdp2) {
      return "Vos mots de passes ne correspondent pas !";
  }
    // Hashage du mot de passe
    $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
    
    // Insertion des données dans la base de données
    $sql = "INSERT INTO menbres (pseudo, mail, motdepasse) VALUES(?,?,?)";
    $reqInsert = $cnx->prepare($sql);
    $reqInsert->execute([$pseudo, $mail, $mdp_hash]);
    
    return "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>"; // Inscription réuss
 
            }

            
    


            $erreur =register($_POST["pseudo"],$_POST["mail"],$_POST["mail2"],$_POST["mdp"],$_POST["mdp2"]);

}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>espace-menbres</title>
</head>
<body>
      <div align="center">
         <h2>Inscription prof</h2>
         <br /><br />
         <form method="POST" action="">
            <table>
               <tr>
                  <td align="right">
                     <label for="pseudo">Pseudo :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?= $pseudo ?? ''; ?>" />
                    
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail">Mail :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre mail" id="mail" name="mail" value="<?php echo $mail ?? ''; ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail2">Confirmation du mail :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php echo $mail2 ?? ''; ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp2">Confirmation du mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
                  </td>
               </tr>
               <tr>
                  <td></td>
                  <td align="center">
                     <br />
                     <input type="submit" name="forminscription" value="Je m'inscris" />
                  </td>
               </tr>
            </table>
         </form>
         <?php
         if (isset($erreur)) {
            echo '<font color="red">' . $erreur . "</font>";
         }
         ?>
      </div>
   </body>
    

</html>