<?php
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Créer une Anomalie</title></head>
<body>
<?php
if (isset($_POST['id_lnkcomart']) && !empty($_POST['id_lnkcomart'])) {
    $id_lnkcomart = urlencode($_POST['id_lnkcomart']);
}
    include_once '../config/Database.php';
    include_once '../models/Anomalie.php';
    $database = new Database();
    $db = $database->getConnection();
    $anomaliecommande = new AnomalieCommande($db);
    
    $anomaliecommande->id_lnkcomart = $id_lnkcomart;
    $idcom = $anomaliecommande->getCommandeId();
    ?>
    <form action="../api/createAnomalie.php" method="post">
        
        <h3>Description de l'anomalie :</h3>
        <br>
        <label for="commandeId">Commande ID : </label><input type="text" id="commandeId" disabled="disabled" value="<?= $idcom ?>"/>
        <br>
        <label for="date_anomalie">Date d'anomalie : </label><input type="date" id="date_anomalie" name="date_anomalie" />
        <br>
        <label for="observation">observation : </label> <input type="text" name="observation" />
        <br>
        <label for="probleme">probleme : </label> <input type="text" name="probleme" />
        <br>
        <label for="nomDescription">nomDescription : </label> <input type="text" name="nomDescription" />
        <br>
        <label for="description">description : </label> <input type="text" name="description" />
        <br>
        <label for="criticite">criticite : </label> <input type="text" name="criticite" />
        <br>
        <label for="libelle_typeanomalie">Type d'anomalie:</label>
        <select name="libelle_typeanomalie" id="libelle_typeanomalie">
            <option value="">Choisir un type</option>
            <option value="art">Article</option>
            <option value="com">Commande</option>
        </select>
        <!-- <label for="libelle_typeanomalie">libelle_typeanomalie : </label><input type="text" name="libelle_typeanomalie" />
        <br> -->
        <h3>Article :</h3>
        <br>
        <?php
            // $reqCom = $bdd->prepare("SELECT id_commande from commande where id_commande = :commande");
            // $reqCom->bindParam(":commande", $_POST['id_lnkcomart']);
            // $reqCom->execute();
            // foreach($reqCom as $com)
            // {
            //     $id_commande = $com[0];
            // }
            $id_article[] = $anomaliecommande->getArticleId();
            $tabValcom = $anomaliecommande->verifValidCom();
            // print_r($tabValcom);
            $compteur = 0;
            foreach($id_article as $data)
            {
                foreach($data as $idart)
                {
                    $id = $idart;
                    // print_r($data[0]);
                    $i = 0;
                    // print_r($data);
                    // foreach($tabVal as $x){
                    //     print_r($x);
                    // }
                    $paramTab = $id;
                    $anomaliecommande->paramTab = $paramTab;
                    $tabVal = $anomaliecommande->verifValidLnk();

                    // print_r($id);
                    $paramArt = $id;
                    $anomaliecommande->paramArt = $paramArt;
                    $reqArt = $anomaliecommande->getArticle();
                    // print_r($reqArt);
                    
                    $v = 0;
                    foreach($reqArt as $article)
                    {
                        // print_r($article);
                        // articleCode, articleLibelle, conditionStockageCode, conditionStockageLibelle
                        while ($i < 4)
                        {
                            // if ($i == 2 || $i == 3 || $i == 4 || $i == 5)
                            // {
                                echo $article[$i].' | ';
                            // }
                            $i += 1; 
                        }
                        // var_dump($tabValcom);
                        // var_dump($tabVal);
                        if($tabValcom == -1)
                        {
                            ?> <input type="checkbox" name="article[]" value="<?= $article[0] ?>" checked><br><br><?php
                        }
                        else
                        {
                            if ($tabVal[$v] == -1)
                            {
                                ?> <input type="checkbox" name="article[]" value="<?= $article[0] ?>" checked><br><br><?php
                            }
                            else{
                                ?> <input type="checkbox" name="article[]" value="<?= $article[0] ?>"><br><br><?php
                            }
                        }
                        
                        $v += 1;
                    }
                    $compteur += 1;
                }  
            }
        ?>
        
        <input type="hidden" id="id_commande" name="id_commande" value="<?= $id_lnkcomart ?>">
        <input type="hidden" id="compteur" name="compteur" value="<?= $compteur ?>">
        <input type="submit" value="Créer" />
    </form>
</body>
</html>
