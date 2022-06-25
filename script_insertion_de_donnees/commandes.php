<?php
// $conn = new PDO("mysql:host=localhost;dbname=stagelpmi", "test", "test");
$conn = new PDO("mysql:host=localhost;dbname=stagelpmi", "root", "");
$table_data = '';
$query = '';

// json file name
$filename = "commandes.json";

// Read the JSON file in PHP
$data = file_get_contents($filename); 

// Convert the JSON String into PHP Array
$array = json_decode($data, true);
$id = array();
$valid = 1;
$idcommande = 1;
$idarticle = 1;
$tableauFournisseur = array();


// COMMANDES
foreach($array as $row) 
{
    foreach($row as $commandeData)
    {

        // Insertion des fournisseur
        $count = 0;
        $tableauFournisseur[] = $commandeData["fournisseurCode"];
        foreach ($tableauFournisseur as $data){
            if ($commandeData["fournisseurCode"] == $data)
            {
                $count += 1;
            }
        }
        if ($count < 2){
            $reqFournisseur = $conn->prepare("INSERT INTO fournisseur (fournisseurCode, fournisseurLibelle, fournisseurAvoirPrix, valid) 
            VALUES (:fournisseurCode, :fournisseurLibelle, :fournisseurAvoirPrix, :valid)");
                $reqFournisseur->bindParam(':fournisseurCode', $commandeData["fournisseurCode"]);
                $reqFournisseur->bindParam(':fournisseurLibelle', $commandeData["fournisseurLibelle"]);
                $reqFournisseur->bindParam(':fournisseurAvoirPrix', $commandeData["fournisseurAvoirPrix"]);
                $reqFournisseur->bindParam(':valid', $valid);
                $reqFournisseur->execute();
        }


        //Préparation des clé étrangère centre & fournisseur dans commande
        $centreProfitCode = $commandeData["centreProfitCode"];
        $centre = $conn->prepare("SELECT id from Tabhierarchie where code = :centre");
        $centre->bindParam(':centre', $centreProfitCode);
        $centre->execute();
        
        foreach ($centre as $id)
        {
            $idCentre = $id[0];
        }

        $fournisseurCode = $commandeData["fournisseurCode"];
        $fournisseur = $conn->prepare("SELECT id_fournisseur from fournisseur where fournisseurCode = :fournisseur");
        $fournisseur->bindParam(':fournisseur', $fournisseurCode);
        $fournisseur->execute();
        
        foreach ($fournisseur as $id)
        {
            $idFournisseur = $id[0];
        }
        // print_r($idCentre);

        //Insertion des commande
        $query = $conn->prepare("INSERT INTO commande (id_centre, id_fournisseur, commandeId, dateLivraison, montantHT, valid) 
        VALUES (:centreID, :id_fournisseur, :commandeId, :dateLivraison, :montantHT, :valid)");
            $query->bindParam(':centreID', $idCentre);
            $query->bindParam(':id_fournisseur', $idFournisseur);
            $query->bindParam(':commandeId', $commandeData["commandeId"]);
            $query->bindParam(':dateLivraison', $commandeData["dateLivraison"]);
            $query->bindParam(':montantHT', $commandeData["montantHT"]);
            $query->bindParam(':valid', $valid);
            $query->execute();
        
    }
}


// FAMILLES
$tableaufamilleN3 = array();
$countfamilleN3 = 0;
$idparentFamilleN3 =0;
$nv3famille = 1;
$idfamilleN3 = 0;

$tableaufamilleN2 = array();
$countfamilleN2 = 0;
$nv2famille = 2;
$idfamilleN2 = 0;

$tableaufamilleN1 = array();
$countfamilleN1 = 0;
$nv1famille = 3;
$idfamilleN1 = 0;
foreach($array as $row) 
{
    foreach($row as $commandeData){
        // Insertion des famille
        foreach($commandeData["articles"] as $art)
        {
            // NIVEAU 3
            $familleN3Code = $art["familleN1Code"];
            $tableaufamilleN3[] = $familleN3Code;
            $countfamilleN3 = 0;
            foreach($tableaufamilleN3 as $data){
                if ($familleN3Code == $data){
                    $countfamilleN3 += 1;
                }
            }
            if ($countfamilleN3 < 2)
            {
                $reqfamilleN3 = $conn->prepare("INSERT INTO famille (id_parent, nv_famille, familleNCode, familleNLibelle, conditionStockageCode, conditionStockageLibelle, valid)
                VALUES (:id_parent, :nv_famille, :familleNCode, :familleNLibelle, :conditionStockageCode, :conditionStockageLibelle, :valid)
                ");
                $reqfamilleN3->bindParam(':id_parent', $idparentFamilleN3);
                $reqfamilleN3->bindParam(':nv_famille', $nv3famille);
                $reqfamilleN3->bindParam(':familleNCode', $art["familleN1Code"]);
                $reqfamilleN3->bindParam(':familleNLibelle', $art["familleN1Libelle"]);
                $reqfamilleN3->bindParam(':conditionStockageCode', $art["conditionStockageCode"]);
                $reqfamilleN3->bindParam(':conditionStockageLibelle', $art["conditionStockageLibelle"]);
                $reqfamilleN3->bindParam(':valid', $valid);
                $reqfamilleN3->execute();
                $idfamilleN3 += 1;
            }

            // NIVEAU 2
            $familleN2Code = $art["familleN2Code"];
            $tableaufamilleN2[] = $familleN2Code;
            $countfamilleN2 = 0;
            foreach($tableaufamilleN2 as $data){
                if ($familleN2Code == $data){
                    $countfamilleN2 += 1;
                }
            }
            $familleN2 = $conn->prepare("SELECT id_famille from famille where familleNCode = :famille");
            $familleN2->bindParam(':famille', $familleN3Code);
            $familleN2->execute();
            
            foreach ($familleN2 as $id)
            {
                $idparentFamille = $id[0];
            }


            if(!is_null($idparentFamille))
            {
                if ($countfamilleN2 < 2)
                {
                    $reqfamilleN2 = $conn->prepare("INSERT INTO famille (id_parent, nv_famille, familleNCode, familleNLibelle, conditionStockageCode, conditionStockageLibelle, valid)
                    VALUES (:id_parent, :nv_famille, :familleNCode, :familleNLibelle, :conditionStockageCode, :conditionStockageLibelle, :valid)
                    ");
                    $reqfamilleN2->bindParam(':id_parent', $idparentFamille);
                    $reqfamilleN2->bindParam(':nv_famille', $nv2famille);
                    $reqfamilleN2->bindParam(':familleNCode', $art["familleN2Code"]);
                    $reqfamilleN2->bindParam(':familleNLibelle', $art["familleN2Libelle"]);
                    $reqfamilleN2->bindParam(':conditionStockageCode', $art["conditionStockageCode"]);
                    $reqfamilleN2->bindParam(':conditionStockageLibelle', $art["conditionStockageLibelle"]);
                    $reqfamilleN2->bindParam(':valid', $valid);
                    $reqfamilleN2->execute();
                    $idfamilleN2 += 1;
                }
            }
            else
            {
                if ($countfamilleN2 < 2)
                {
                    $reqfamilleN2 = $conn->prepare("INSERT INTO famille (id_parent, nv_famille, familleNCode, familleNLibelle, conditionStockageCode, conditionStockageLibelle, valid)
                    VALUES (:id_parent, :nv_famille, :familleNCode, :familleNLibelle, :conditionStockageCode, :conditionStockageLibelle, :valid)
                    ");
                    $reqfamilleN2->bindParam(':id_parent', $idfamilleN3);
                    $reqfamilleN2->bindParam(':nv_famille', $nv2famille);
                    $reqfamilleN2->bindParam(':familleNCode', $art["familleN2Code"]);
                    $reqfamilleN2->bindParam(':familleNLibelle', $art["familleN2Libelle"]);
                    $reqfamilleN2->bindParam(':conditionStockageCode', $art["conditionStockageCode"]);
                    $reqfamilleN2->bindParam(':conditionStockageLibelle', $art["conditionStockageLibelle"]);
                    $reqfamilleN2->bindParam(':valid', $valid);
                    $reqfamilleN2->execute();
                    $idfamilleN2 += 1;
                }
            }


            // NIVEAU 1
            $familleN1Code = $art["familleN3Code"];
            $tableaufamilleN1[] = $familleN1Code;
            $countfamilleN1 = 0;
            foreach($tableaufamilleN1 as $data){
                if ($familleN1Code == $data){
                    $countfamilleN1 += 1;
                }
            }
            $familleN1 = $conn->prepare("SELECT id_famille from famille where familleNCode = :famille");
            $familleN1->bindParam(':famille', $familleN1Code);
            $familleN1->execute();
            
            foreach ($familleN1 as $id)
            {
                $idparent1Famille = $id[0];
            }


            if(!is_null($idparent1Famille))
            {
                if ($countfamilleN2 < 2)
                {
                    $reqfamilleN1 = $conn->prepare("INSERT INTO famille (id_parent, nv_famille, familleNCode, familleNLibelle, conditionStockageCode, conditionStockageLibelle, valid)
                    VALUES (:id_parent, :nv_famille, :familleNCode, :familleNLibelle, :conditionStockageCode, :conditionStockageLibelle, :valid)
                    ");
                    $reqfamilleN1->bindParam(':id_parent', $idparent1Famille);
                    $reqfamilleN1->bindParam(':nv_famille', $nv1famille);
                    $reqfamilleN1->bindParam(':familleNCode', $art["familleN3Code"]);
                    $reqfamilleN1->bindParam(':familleNLibelle', $art["familleN3Libelle"]);
                    $reqfamilleN1->bindParam(':conditionStockageCode', $art["conditionStockageCode"]);
                    $reqfamilleN1->bindParam(':conditionStockageLibelle', $art["conditionStockageLibelle"]);
                    $reqfamilleN1->bindParam(':valid', $valid);
                    $reqfamilleN1->execute();
                }
            }
            else
            {
                if ($countfamilleN2 < 2)
                {
                    $reqfamilleN1 = $conn->prepare("INSERT INTO famille (id_parent, nv_famille, familleNCode, familleNLibelle, conditionStockageCode, conditionStockageLibelle, valid)
                    VALUES (:id_parent, :nv_famille, :familleNCode, :familleNLibelle, :conditionStockageCode, :conditionStockageLibelle, :valid)
                    ");
                    $reqfamilleN1->bindParam(':id_parent', $idfamilleN2);
                    $reqfamilleN1->bindParam(':nv_famille', $nv1famille);
                    $reqfamilleN1->bindParam(':familleNCode', $art["familleN3Code"]);
                    $reqfamilleN1->bindParam(':familleNLibelle', $art["familleN3Libelle"]);
                    $reqfamilleN1->bindParam(':conditionStockageCode', $art["conditionStockageCode"]);
                    $reqfamilleN1->bindParam(':conditionStockageLibelle', $art["conditionStockageLibelle"]);
                    $reqfamilleN1->bindParam(':valid', $valid);
                    $reqfamilleN1->execute();
                }
            }

            
            
        }
    }
    
}


// ARTICLE
foreach($array as $row) 
{
    foreach($row as $commandeData){
        // Insertion des articles
        foreach($commandeData["articles"] as $art){
            $tabArticleCode[] = $art["articleCode"];
            $count = 0;
            foreach ($tabArticleCode as $code)
            {
                if ($code == $art["articleCode"]){
                    $count += 1;
                }
            }
            if ($count < 2)
            {
                $reqfamilleId = $conn->prepare("SELECT id_famille from famille where familleNCode = :codefamille");
                $reqfamilleId->bindParam(':codefamille', $art["familleN1Code"]);
                $reqfamilleId->execute();
                foreach ($reqfamilleId as $id)
                {
                    $idFamille = $id[0];
                }
                $req = $conn->prepare("INSERT INTO article (id_famille, articleCode, articleLibelle, conditionStockageCode, conditionStockageLibelle, valid) 
                VALUES (:id_famille, :articleCode, :articleLibelle, :conditionStockageCode, :conditionStockageLibelle, :valid)");
                $req->bindParam(':id_famille', $idFamille);
                $req->bindParam(':articleCode', $art["articleCode"]);
                $req->bindParam(':articleLibelle', $art["articleLibelle"]);
                $req->bindParam(':conditionStockageCode', $art["conditionStockageCode"]);
                $req->bindParam(':conditionStockageLibelle', $art["conditionStockageLibelle"]);
                $req->bindParam(':valid', $valid);
                $req->execute();
    
                // Insertion des données de la tables commandearticle
                $reqCA = $conn->prepare("INSERT INTO lnkcommandearticle (id_commande, id_article, quantiteCommandee, prixAchatHT, uniteCommande, valid) 
                VALUES (:id_commande, :id_article, :quantiteCommandee, :prixAchatHT, :uniteCommande, :valid)");
                $reqCA->bindParam(':id_commande', $idcommande);
                $reqCA->bindParam(':id_article', $idarticle);
                $reqCA->bindParam(':quantiteCommandee', $art["quantiteCommandee"]);
                $reqCA->bindParam(':prixAchatHT', $art["prixAchatHT"]);
                $reqCA->bindParam(':uniteCommande', $art["uniteCommande"]);
                $reqCA->bindParam(':valid', $valid);
                $reqCA->execute();
                // print_r($idarticle);?><br><?php
                $idarticle += 1;
            }
            else
            {
                $code = $art["articleCode"];
                $req = $conn->prepare("SELECT id_article from article where articleCode=:articleCode");
                $req->bindParam(':articleCode', $code);
                $req->execute();
                foreach ($req as $data){
                    $id_article = $data[0];
                }

                // Insertion des données de la tables commandearticle
                $reqCA = $conn->prepare("INSERT INTO lnkcommandearticle (id_commande, id_article, quantiteCommandee, prixAchatHT, uniteCommande, valid) 
                VALUES (:id_commande, :id_article, :quantiteCommandee, :prixAchatHT, :uniteCommande, :valid)");
                $reqCA->bindParam(':id_commande', $idcommande);
                $reqCA->bindParam(':id_article', $id_article);
                $reqCA->bindParam(':quantiteCommandee', $art["quantiteCommandee"]);
                $reqCA->bindParam(':prixAchatHT', $art["prixAchatHT"]);
                $reqCA->bindParam(':uniteCommande', $art["uniteCommande"]);
                $reqCA->bindParam(':valid', $valid);
                $reqCA->execute();
            }
            
        }
        $idcommande += 1;
    }
    
}
