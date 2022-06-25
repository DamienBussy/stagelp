<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_POST['code']) && !empty($_POST['code'])) {
    $code = urlencode($_POST['code']);
}

if (isset($_POST['dateDebut']) && !empty($_POST['dateDebut'])) {
    $dateDebut = urlencode($_POST['dateDebut']);
}

if (isset($_POST['dateFin']) && !empty($_POST['dateFin'])) {
    $dateFin = urlencode($_POST['dateFin']);
}

// On vérifie que la méthode utilisée est correcte
// if($_SERVER['REQUEST_METHOD'] == 'GET'){
// On inclut les fichiers de configuration et d'accès aux données
include_once '../config/Database.php';
include_once '../models/Commande.php';

// print_r($code);
// print_r($dateDebut);
// On instancie la base de données
$database = new Database();
$db = $database->getConnection();

//compteur
$i = 0;
$a = 0;
// On instancie les produits
$commande = new Commandes($db);
$centre = new Commandes($db);

$donnees = json_decode(file_get_contents("php://input"));
// $donnees->code = $code;
// $donnees->dateDebut = $dateDebut;
// $donnees->dateFin = $dateFin;

// centre
if(!empty($code && !empty($dateDebut) && !empty($dateFin)))
{
    // print_r($code);
    $centre->code = $code;
    // $centre->code = $donnees->code;
    
    // On récupère le centre
    $centre->CentreID();

    // On vérifie si le centre existe
    if($centre->code != null){
        // On crée un tableau contenant le centre
        $centre_id = $centre->id;
        $prodcentre = [
            "id" => $centre->id,
            "nom" => $centre->nom
        ];
        // On envoie le code réponse 200 OK
        http_response_code(200);

        // // On encode en json et on envoie
        echo json_encode($prod);        
    }
    // // COMMANDE

    $commande->dateDebut = $dateDebut;
    $commande->dateFin = $dateFin;
    $commande->id = $centre_id;
    // On récupère le commande
    $commande->lireUn();

    $stmt = $commande->lireUn();

    // On vérifie si on a au moins 1 commande
    if($stmt->rowCount() > 0){
        // On initialise un tableau associatif
        $tableauCommandes = [];
        $tableauCommandes['commande'] = [];
        // On parcourt les commandes
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $commande_id[] = $id_commande;
            $prod = [
                "id_commande" => $id_commande,
                "id_centre" => $id_centre,
                "id_fournisseur" => $id_fournisseur,
                "commandeId" => $commandeId,
                "dateLivraison" => $dateLivraison,
                "montantHT" => $montantHT,
                "valid" => $valid
            ];
            $tableauCommandes[] = $prod;
        }
        // On envoie le code réponse 200 OK
        http_response_code(200);
        // On encode en json et on envoie
        echo json_encode($tableauCommandes);
        
        foreach ($commande_id as $id)
        {            
            // LNKCOMMANDEARTICLE
            $lnkcommandearticle = new Commandes($db);
            $lnkcommandearticle->id = $id;
            $stmt = $lnkcommandearticle->lireUnLnk();
    
            // On vérifie si on a au moins 1 commande
            if($stmt->rowCount() > 0)
            {
                // On initialise un tableau associatif
                $tableaulnkcommandearticle = [];
                $tableaulnkcommandearticle['lnkcommandearticle'] = [];
                // On parcourt les commandes
                $article_id = [];
                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    
                    $article_id[] = $id_article;
                    $prod = [
                        "id_article" => $id_article,
                        "quantiteCommandee" => $quantiteCommandee,
                        "prixAchatHT" => $prixAchatHT,
                        "uniteCommande" => $uniteCommande
                    ];
                    $tableaulnkcommandearticle['lnkcommandearticle'][] = $prod;
                }
                // On envoie le code réponse 200 OK
                http_response_code(200);
                echo json_encode($tableaulnkcommandearticle);

                // ARTICLE
                $article = new Commandes($db);
                
                foreach ($article_id as $id)
                {
                    $article->id = $id;
                    // On récupère le commande
                    $article->lireUnArticle();
                    $stmt = $article->lireUnArticle();
            
                    // On vérifie si on a au moins 1 commande
                    if($stmt->rowCount() > 0){
                        // On initialise un tableau associatif
                        $tableauarticle = [];
                        $tableauarticle['article'] = [];
                        // On parcourt les commandes
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            extract($row);
                            $prod = [
                                "id_article" => $id_article,
                                "articleCode" => $articleCode,
                                "articleLibelle" => $articleLibelle,
                                "conditionStockageCode" => $conditionStockageCode,
                                "conditionStockageLibelle" => $conditionStockageLibelle
                            ];
                            $tableauarticle['article'][] = $prod;
                        }
                        // On envoie le code réponse 200 OK
                        http_response_code(200);
                        // // On encode en json et on envoie
                        echo json_encode($tableauarticle);
                    }
                }
            }
            $i += 1;
        }
    }
    else
    {
        // 404 Not found
        http_response_code(404);
        
        echo json_encode(array("message" => "Le commande n'existe pas."));
    }
}
?>
