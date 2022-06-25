<style>
table
{
    border-collapse: collapse; /* Les bordures du tableau seront collées (plus joli) */
}
td
{
    border: 1px solid black;
}
</style>
<?php
// Headers requis
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: GET");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
include_once '../models/Anomalie.php';

// print_r($code);
// print_r($dateDebut);
// On instancie la base de données
$database = new Database();
$db = $database->getConnection();

//compteur
$i = 0;
$a = 0;
// On instancie les produits
$commande = new AnomalieCommande($db);
$centre = new AnomalieCommande($db);

// $donnees = json_decode(file_get_contents("php://input"));
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
        // http_response_code(200);

        // // On encode en json et on envoie
        // echo json_encode($prod);
        ?><h2>Centre : </h2>
        <h4>Id centre : <?php echo $prodcentre['id'];?></h4>
        <h4>Centre : <?php echo $prodcentre['nom'];?></h4>
           <?php
        
        
    }
    // // COMMANDE

    $commande->dateDebut = $dateDebut;
    $commande->dateFin = $dateFin;
    $commande->id = $centre_id;
    // On récupère le commande
    $commande->lireUn();

    $stmt = $commande->lireUn();

    // On vérifie si on a au moins 1 commande
    if($stmt->rowCount() > 0)
    {
        // On initialise un tableau associatif
        $tableauCommandes = [];
        $tableauCommandes['commande'] = [];
        // On parcourt les commandes
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            
            $commande_id[] = $id_commande;
            $cen[] = $id_centre;
            $fou[] = $id_fournisseur;
            $com[] = $commandeId;
            $date[] = $dateLivraison;
            $mon[] = $montantHT;
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
        $nb = 0;
        foreach($commande_id as $data){
            $nb += 1;
        }
        ?><p>Il y a <?php echo $nb ?> commandes concernant le centre : <?php echo $prodcentre["nom"]?> entre le <?php echo $dateDebut ?> et le <?php echo $dateFin ?>. </p><?php
        $x = 0;
        foreach ($commande_id as $id)
        {
            $lnkcommandearticle_id = [];
            ?><table><?php
            $lnkcommandearticle_id[] = $id; ?>
            <tr><td><h3>Commande : </h3>
            
            <h4>Id : <?php echo $commande_id[$i]; ?></h4>
            <h4>id_centre : <?php echo $cen[$i]; ?></h4>
            <h4>fournisseur : <?php echo $fou[$i]; ?></h4>
            <h4>commandeId : <?php echo $com[$i] ?></h4>
            <h4>dateLivraison : <?php echo $date[$i]; ?></h4>
            <h4>montantHT : <?php echo $mon[$i]; ?></h4>
            <form action="../views/createAnomalie.php" method="post">
            <input type="hidden" id="id_lnkcomart" name="id_lnkcomart" value="<?= $id ?>">
                <input type="submit" value="Signaler une anomalie"/>
            </form>
            <?php

            
            // On envoie le code réponse 200 OK
            // http_response_code(200);
            // On encode en json et on envoie
            // echo json_encode($tableauCommandes);
            
            // LNKCOMMANDEARTICLE
            $lnkcommandearticle = new AnomalieCommande($db);
            
            foreach($lnkcommandearticle_id as $idLnk)
            {
                $lnkcommandearticle->idLnk = $idLnk;
                $lnkcommandearticle->lireUnLnk();
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
                        $id_art[] = $id_article;
                        $quantiteC[] = $quantiteCommandee;
                        $prixAT[] = $prixAchatHT;
                        $uniteC[] = $uniteCommande;
                        $id_lnkcomart[] = $id_lnkcommandearticle;
                        $prod = [
                            "id_lnkcommandearticle" => $id_lnkcommandearticle,
                            "id_article" => $id_article,
                            "quantiteCommandee" => $quantiteCommandee,
                            "prixAchatHT" => $prixAchatHT,
                            "uniteCommande" => $uniteCommande
                        ];
                        $tableaulnkcommandearticle['lnkcommandearticle'][] = $prod;
                        ?><br><?php
                        ?></td></tr><?php
                        ?><tr><td>
                        <h2>Détails (lnkcommandearticle) : </h2>
                        <h4>id_lnkcomart : <?php echo $id_lnkcomart[$x]; ?></h4>
                        <h4>id_article : <?php echo $id_art[$x]; ?></h4>
                        <h4>quantiteCommandee : <?php echo $quantiteC[$x]; ?></h4>
                        <h4>prixAchatHT : <?php echo $prixAT[$x]; ?></h4>
                        <h4>uniteCommande : <?php echo $uniteC[$x]; ?></h4><?php
                        $x += 1;
                    }
                    // On envoie le code réponse 200 OK
                    // http_response_code(200);
                    // echo json_encode($tableaulnkcommandearticle);
                        
                    
                    // ARTICLE
                    $article = new AnomalieCommande($db);
                    
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
                                $art[] = $id_article;
                                $artCode[] = $articleCode;
                                $artLib[] = $articleLibelle;
                                $conCode[] = $conditionStockageCode;
                                $condLib[] = $conditionStockageLibelle;
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
                            // http_response_code(200);
                            // // On encode en json et on envoie
                            // echo json_encode($tableauarticle);
                            ?><br><?php
                            ?></td></tr><?php
                            ?><tr><td>
                            <h2>Article : </h2>
                            <h4>id_article : <?php echo $art[$a]; ?></h4>
                            <h4>articleCode : <?php echo $artCode[$a]; ?></h4>
                            <h4>articleLibelle : <?php echo $artLib[$a]; ?></h4>
                            <h4>conditionStockageCode : <?php echo $conCode[$a]; ?></h4>
                            <h4>conditionStockageLibelle : <?php echo $condLib[$a]; ?></h4><?php
                            ?><?php
                            ?></td><?php
                            $a += 1;
                        }
                    }
                    ?></tr><?php
                    ?></table><br><br><?php
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
