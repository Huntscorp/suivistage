<?php
SESSION_start();
include 'connexion.php';

$mailDest = "malon.loic99@gmail.com"; // adresse de destination

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mailDest)) {
    $passage_ligne = "\r\n";
}
else {
    $passage_ligne = "\n";
}
//=====Déclaration du message format texte et html
$message_txt = "Ceci est un e-mail de test donc à effacer";
$message_html = "<html><head></head><body>Ceci est un e-mail de test donc à effacer</body></html>";
//=====Création boundary
$boundary = "-----=".md5(rand());
//===== Definition sujet
$sujet = "Message de test";
//=====Création du header du mail
$header = "From: \"malon.loic99\"<malon.loic99@gmail.com>".$passage_ligne;
$header .= "Reply-to: \"malon.loic99\" <malon.loic99@gmail.com>".$passage_ligne;
$header .= "MIME-Version: 1.0".$passage_ligne;
$header .= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//=====Création du message
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout message en texte
$message .= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message .= "Content-Transfer-Encoding: 4bit".$passage_ligne;
$message .= "$passage_ligne.$message_txt.$passage_ligne";
//==========
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout message en html
$message .= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message .= "Content-Transfer-Encoding: 4bit".$passage_ligne;
$message .= "$passage_ligne.$message_html.$passage_ligne";
//==========
$message = $passage_ligne."--".$boundary.$passage_ligne;
$message = $passage_ligne."--".$boundary.$passage_ligne;

//=====Envoie du mail
mail($mailDest, $sujet, $message, $header);




?>