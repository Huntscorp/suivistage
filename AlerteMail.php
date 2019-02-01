<?php
SESSION_start();
include 'connexion.php';
$dateJour = date("y-m-d");//Date du jour

    $requete="select idrelance, email 
                from etudiant, demande, relance
                where etudiant.idetudiant=demande.idetudiant
                AND demande.iddemande=relance.iddemande
                AND idetat=5
                AND DATEDIFF(CURRENT_DATE, date_demande)>=30
                AND idrelance in (select idrelance
			from relance
			where DATEDIFF(CURRENT_DATE, date_alerteRelance)>=7
			OR date_alerteRelance IS null)
                AND idrelance in (select idrelance
			from relance
			where DATEDIFF(CURRENT_DATE, date_relance)>=30
			OR date_relance IS null)
                ORDER BY email;";
    $table = $connection ->query($requete);
    while ($ligne = $table->fetch())
    {
        /*========== Envoie du mail d'alerte ==========*/
        // =====Appel de php mailer
        require_once('PHPMailer-master/src/PHPMailer.php');
        require_once('PHPMailer-master/src/SMTP.php');
        // =====Création du mail
        $mail = new PHPMailer\PHPMailer\PHPMailer;
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        //===
        $mail->Username = 'servicemail.ppe@gmail.com';
        $mail->Password = 'Azerty45';
        $mail->SMTPSecure = 'ssl';
        $mail->From = 'servicemail.ppe@gmail.com';
        $mail->FromName = 'Service Stage';
        $mail->AddAddress($ligne['email']); //Adresse mail destinatair
        $mail->IsHTML(TRUE);
        //===
        $mail->Subject = 'Rappel de stage';
        $mail->Body = "Vous n'avez pas relancé d'entreprise depuis un mois, pensez à le faire.
                            <br><br><br>Ceci est un mail automatique, merci de ne pas répondre.";
        // =====
        if(!$mail->Send()) {
           echo "Le message n'a pas pu être envoyer.";
           echo 'Mailer Error: ' . $mail->ErrorInfo;
           exit;
        }
        /*====================*/
        /*===== Mise à jour de la base de donnée =====*/
        $sql='UPDATE relance SET date_alerteRelance="'.$dateJour.'" where idrelance='.$ligne['idrelance'].';';
        $connection ->query($sql);
    }
    $table->closeCursor();
?>