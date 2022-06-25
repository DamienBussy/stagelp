<?php
include_once '../config/Database.php';
include_once '../models/Anomalie.php';


// On instancie la base de donnees
$database = new Database();
$db = $database->getConnection();

if (isset($_POST['commandeId']) && !empty($_POST['commandeId'])) {
    $commandeId = urlencode($_POST['commandeId']);
}

if (isset($_POST['date_anomalie']) && !empty($_POST['date_anomalie'])) {
    $date_anomalie = urlencode($_POST['date_anomalie']);
}

if (isset($_POST['probleme']) && !empty($_POST['probleme'])) {
    $probleme = urlencode($_POST['probleme']);
}

if (isset($_POST['observation']) && !empty($_POST['observation'])) {
    $observation = urlencode($_POST['observation']);
}

if (isset($_POST['nomDescription']) && !empty($_POST['nomDescription'])) {
    $nomDescription = urlencode($_POST['nomDescription']);
}
if (isset($_POST['description']) && !empty($_POST['description'])) {
    $description = urlencode($_POST['description']);
}

if (isset($_POST['criticite']) && !empty($_POST['criticite'])) {
    $criticite = urlencode($_POST['criticite']);
}

if (isset($_POST['libelle_typeanomalie']) && !empty($_POST['libelle_typeanomalie'])) {
    $libelle_typeanomalie = urlencode($_POST['libelle_typeanomalie']);
}
if (isset($_POST['id_commande']) && !empty($_POST['id_commande'])) {
    $id_lnkcomart = urlencode($_POST['id_commande']);
}

// if (isset($_POST['compteur']) && !empty($_POST['compteur'])) {
//     $compteur = urlencode($_POST['compteur']);
// }
$compter = 0;
if (isset($_POST['article'])) {
    foreach($_POST['article'] as $a)
    {
        $article[] = $a;
        $compter += 1;
    }
}

$count = $_POST['compteur'];


if(isset($_POST['libelle_typeanomalie'])){
    if($_POST['libelle_typeanomalie'] == "com"){
        $libelle_typeanomalie = "commande";
    }
    else
    {
        if ($count == $compter){
            if($_POST['libelle_typeanomalie'] == "art"){
                $libelle_typeanomalie = "commande";
            }
        }
        else{
            if($_POST['libelle_typeanomalie'] == "art"){
                $libelle_typeanomalie = "article";
            }
        }
        
    }
}


// On instancie les produits
$anomalie = new AnomalieCommande($db);
$decsanomalie = new AnomalieCommande($db);
$cranomalie = new AnomalieCommande($db);
$status = new AnomalieCommande($db);


// Anomalie
$anomalie->libelle_typeanomalie = $libelle_typeanomalie;
// $anomalie->date_anomalie = $date_anomalie;
$anomalie->probleme = $probleme;
$anomalie->observation = $observation;
$anomalie->description = $description;

// descanomalie
$decsanomalie->libelle_typeanomalie = $libelle_typeanomalie;
$decsanomalie->nomDescription = $nomDescription;
$decsanomalie->description = $description;
$decsanomalie->criticite = $criticite;

// cranomalie
$cranomalie->id_lnkcomart = $id_lnkcomart;
$cranomalie->libelle_typeanomalie = $libelle_typeanomalie;
// $cranomalie->commandeId = $commandeId;
// $cranomalie->articleCode = $articleCode;
$cranomalie->nomDescription = $nomDescription;
// $cranomalie->date_anomalie = $date_anomalie;
$cranomalie->probleme = $probleme;
$cranomalie->observation = $observation;
$cranomalie->description = $description;
foreach($article as $data)
{
    $cranomalie->articleList[] = $data;
}


// modifier le status
// $status->libelle_typeanomalie = $libelle_typeanomalie;
// $status->commandeId = $commandeId;


if($anomalie->creerAnomalie()){
    echo 'Anomalie créée avec succès';
}
$decsanomalie->creerDescanomalie();

if ($libelle_typeanomalie == "commande")
{
    $cranomalie->creerCranomalie();
}
else
{
    $cranomalie->creerCranomalie();
}

// $test->getArticle();



?>
<br>
<a href="http://192.168.56.110/projet-commande">Revenir à l'accueil</a>

