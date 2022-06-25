<?php
class AnomalieCommande{
    // Connexion
    private $connexion;

    // Propriétés
    public $id_commande;
    public $id_centre;
    public $id_fournisseur;
    public $commandeId;
    public $dateLivraison;
    public $montantHT;
    public $valid = 1;
    public $entreprise_id = 1;
    public $action_corrective = "Aucune";
    public $invalid = -1;

/**
 * Constructeur avec $db pour la connexion à la base de données
 *
 * @param $db
 */
    public function __construct($db){
        $this->connexion = $db;
    }


    public function getCommandeId(){
        $id = $this->connexion->prepare("SELECT commandeId from commande where id_commande = :id");
        $id->bindParam(":id", $this->id_lnkcomart);
        $id->execute();
        foreach($id as $comID){
            $idcom = $comID[0];
        }
        return $idcom;
    }

    public function getArticleId()
    {
        $id_commande = $this->id_lnkcomart;
            
            $reqLnk = $this->connexion->prepare("SELECT id_article from lnkcommandearticle where id_commande = :commande");
            $reqLnk->bindParam(":commande", $id_commande);
            $reqLnk->execute();
            $i = 0;
            foreach($reqLnk as $lnk)
            {
                $id_article[] = $lnk[0];
                $i += 1;
            }
            return $id_article;
    }
    public function verifValidCom()
    {
        $reqValidcom = $this->connexion->prepare("SELECT valid from commande where id_commande = :commande");
        $reqValidcom->bindParam(":commande", $this->id_lnkcomart);
        $reqValidcom->execute();
        foreach($reqValidcom as $valcom){
            $tabValcom = $valcom[0];
        }
        return $tabValcom;
    }
    public function verifValidLnk(){
        $reqValidlnk = $this->connexion->prepare("SELECT valid from lnkcommandearticle where id_article = :article");
        $reqValidlnk->bindParam(":article", $this->paramTab);
        $reqValidlnk->execute();
        foreach($reqValidlnk as $val){
            $tabVal = $val[0];
        }
        return $tabVal;
    }

    public function getArticle(){
        // articleCode, articleLibelle, conditionStockageCode, conditionStockageLibelle
        $reqArt = $this->connexion->prepare("SELECT articleCode, articleLibelle, conditionStockageCode, conditionStockageLibelle from article where id_article = :id");
        $reqArt->bindParam(":id", $this->paramArt);
        $reqArt->execute();
        $i = 0;
        foreach($reqArt as $art){
            $tab[] = $art;
            // print_r($art);
            $i =+ 1;
            
        }
        return $tab;
        // return $reqArt;
    }
/**
 * Lecture des commandes
 *
 * @return void
 */
    public function lire(){
        // lire commande
        $sql = "SELECT id_commande, id_centre, id_fournisseur, commandeId, dateLivraison, montantHT, valid FROM commande";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        // On retourne le résultat
        return $query;
    }

// Read One

/**
 * Lire le centre
 *
 * @return void
 */
    public function CentreID(){

        // On écrit la requête
        $centre = "SELECT id, nom from Tabhierarchie where code = :code";
        $req = $this->connexion->prepare( $centre );
        $req->bindParam(':code', $this->code);
        $req->execute();

        // on récupère la ligne
        // $row = $req->fetch(PDO::FETCH_ASSOC);
        foreach($req as $data){
            $row = $data[0];
        }

        // On hydrate l'objet
        $this->id= $row['id'];
        $this->nom= $row['nom'];
    }

/**
 * Lire un les commande du centre et des dates
 *
 * @return void
 */
    //COMMANDE
    public function lireUn()
    {
        $sql = "SELECT id_commande, id_centre, id_fournisseur, commandeId, dateLivraison, montantHT, valid FROM commande WHERE id_centre = :id_centre and dateLivraison between :dateDebut and :dateFin";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On attache l'id
        $query->bindParam(':id_centre', $this->id);
        $query->bindParam(':dateDebut', $this->dateDebut);
        $query->bindParam(':dateFin', $this->dateFin);

        // On exécute la requête
        $query->execute();

        return $query;
    }

/**
 * lire un lnkcommandearticle
 * @return void
 */
    //LNKCOMMANDEARTICLE
    public function lireUnLnk()
    {
        // On écrit la requête
        $sql = "SELECT id_lnkcommandearticle, id_article, quantiteCommandee, prixAchatHT, uniteCommande FROM lnkcommandearticle WHERE id_commande = :id_commande";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On attache l'id
        $query->bindParam(':id_commande', $this->idLnk);

        // On exécute la requête
        $query->execute();
        return $query;
    }

/**
 * @return void
 */
    //ARTICLE
    public function lireUnArticle()
    {
        // On écrit la requête
        $sql = "SELECT id_article, articleCode, articleLibelle, conditionStockageCode, conditionStockageLibelle FROM article WHERE id_article = :id_article";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On attache l'id
        $query->bindParam(':id_article', $this->id);

        // On exécute la requête
        $query->execute();

        return $query;
    }

    
    // Create
    /**
     * Créer une anomalie
     *
     * @return void
     */
    public function creerAnomalie()
    {
        if (!is_null($this->libelle_typeanomalie))
        {
            $reqhistorique = "SELECT id from anomalie where entreprise_id = :entr and date_anomalie = :date and description = :descr and probleme = :pb and action_corrective = :ac and observation = :obs";
            $historique = $this->connexion->prepare($reqhistorique);
            $historique->bindParam(":entr", $this->entreprise_id);
            $historique->bindParam(":date", $this->date_anomalie);
            $historique->bindParam(":descr", $this->description);
            $historique->bindParam(":pb", $this->probleme);
            $historique->bindParam(":ac", $this->action_corrective);
            $historique->bindParam(":obs", $this->observation);
            $historique->execute();
            foreach($historique as $hist)
            {
                $historiqueAno = $hist[0];
            }
            if(is_null($historiqueAno))
            {
                $sql = "INSERT INTO anomalie (entreprise_id, date_anomalie, description, probleme, action_corrective, observation, status) 
                VALUES (:entreprise_id, :date_anomalie, :description, :probleme, :action_corrective, :observation, :valid)";
                // Préparation de la requête
                $query = $this->connexion->prepare($sql);
        
                // Ajout des données protégées
                $query->bindParam(":entreprise_id", $this->entreprise_id);
                $query->bindParam(":date_anomalie", $this->date_anomalie);
                $query->bindParam(":description", $this->description);
                $query->bindParam(":probleme", $this->probleme);
                $query->bindParam(":action_corrective", $this->action_corrective);
                $query->bindParam(":observation", $this->observation);
                $query->bindParam(":valid", $this->valid);
        
                // Exécution de la requête
                if($query->execute()){
                    return true;
                }
            
                return false;
            }
        }
        
        
    }

    /**
     * Créer une descanomalie
     *
     * @return void
     */
    public function creerDescanomalie()
    {
        if (!is_null($this->libelle_typeanomalie))
        {
            $reqhistorique = "SELECT id_typeanomalie from descanomalie where nomDescanomalie = :descanomalie";
            $historique = $this->connexion->prepare($reqhistorique);
            $historique->bindParam(":descanomalie", $this->nomDescription);
            $historique->execute();
            foreach($historique as $hist)
            {
                $historiqueDesc = $hist[0];
            }
            if(is_null($historiqueDesc))
            {
                $reqTyp = "SELECT id_typeanomalie from typeanomalie where libelle_typeanomalie = :libelle";
                $reqTypId = $this->connexion->prepare($reqTyp);
                $reqTypId->bindParam(":libelle", $this->libelle_typeanomalie);
                $reqTypId->execute();
                foreach($reqTypId as $data){
                    $id_typeanomalie = $data[0];
                }
                
                // Ecriture de la requête SQL en y insérant le nom de la table
                $sql = "INSERT INTO descanomalie SET id_typeanomalie=:id_typeanomalie, nomDescanomalie=:nomDescanomalie, 
                descriptionDescanomalie=:descriptionDescanomalie, criticite=:criticite, valid=:valid";
        
                // Préparation de la requête
                $query = $this->connexion->prepare($sql);
        
                // Ajout des données protégées
                $query->bindParam(":id_typeanomalie", $id_typeanomalie);
                $query->bindParam(":nomDescanomalie", $this->nomDescription);
                $query->bindParam(":descriptionDescanomalie", $this->description);
                $query->bindParam(":criticite", $this->criticite);
                $query->bindParam(":valid", $this->valid);
        
                // Exécution de la requête
                if($query->execute()){
                    return true;
                }
                
                return false;
            }
        }
        
    }


    /**
     * Créer un cranomalie
     *
     * @return void
     */
    public function creerCranomalie()
    {
        $reqTyp = "SELECT id_typeanomalie from typeanomalie where libelle_typeanomalie = :libelle";
        $reqTypId = $this->connexion->prepare($reqTyp);
        $reqTypId->bindParam(":libelle", $this->libelle_typeanomalie);
        $reqTypId->execute();
        foreach($reqTypId as $data){
            $id_typeanomalie = $data[0];
        }
        // var_dump($id_typeanomalie);

        $id_produitcmd = $this->id_lnkcomart;
        $idDesc = "SELECT id_descanomalie from descanomalie where nomDescanomalie = :nomDesc";
        $reqId = $this->connexion->prepare($idDesc);
        $reqId->bindParam("nomDesc", $this->nomDescription);
        $reqId->execute();
        foreach($reqId as $data){
            $id_desc = $data[0];
        }
        $idAnomalie = "SELECT id from anomalie where entreprise_id = :entr and date_anomalie = :date and 
        description = :descr and probleme = :pb and action_corrective = :ac and observation = :obs";
        $reqIdAnomalie = $this->connexion->prepare($idAnomalie);
        $reqIdAnomalie->bindParam(":entr", $this->entreprise_id);
        $reqIdAnomalie->bindParam(":date", $this->date_anomalie);
        $reqIdAnomalie->bindParam(":descr", $this->description);
        $reqIdAnomalie->bindParam(":pb", $this->probleme);
        $reqIdAnomalie->bindParam(":ac", $this->action_corrective);
        $reqIdAnomalie->bindParam(":obs", $this->observation);
        $reqIdAnomalie->execute();
        foreach($reqIdAnomalie as $data)
        {
            $id_ano = $data[0];
        }
        // print_r($id_ano);

        // Insertion en fonction du type de l'anomalie
        if($id_typeanomalie == 1)
        {
            $reqhistorique = "SELECT id_cranomalie from cranomalie where id_produitcmd = :id and id_typeanomalie = 1";
            $historique = $this->connexion->prepare($reqhistorique);
            $historique->bindParam(":id", $id_produitcmd);
            $historique->execute();
            foreach($historique as $hist)
            {
                $historiqueCr = $hist[0];
            }

            if(is_null($historiqueCr))
            {
                // Ecriture de la requête SQL en y insérant le nom de la table
                $sql = "INSERT INTO cranomalie SET id_typeanomalie=:id_typeanomalie, id_descanomalie=:id_descanomalie, 
                id_produitcmd=:id_produitcmd, id_anomalie=:id_anomalie, valid=:valid";

                // Préparation de la requête
                $query = $this->connexion->prepare($sql);

                // Ajout des données protégées
                $query->bindParam(":id_typeanomalie", $id_typeanomalie);
                $query->bindParam(":id_descanomalie", $id_desc);
                $query->bindParam(":id_produitcmd", $id_produitcmd);
                $query->bindParam(":id_anomalie", $id_ano);
                $query->bindParam(":valid", $this->valid);

                // Exécution de la requête
                $query->execute();

                $updateStatut = "UPDATE commande SET valid = :valid where id_commande = :idcommande";
                $req = $this->connexion->prepare($updateStatut);
                $req->bindParam(":valid", $this->invalid);
                $req->bindParam(":idcommande", $this->id_lnkcomart);
                $req->execute();


                $selectId = $this->connexion->prepare("SELECT id_lnkcommandearticle from lnkcommandearticle where id_commande = :id");
                $selectId->bindParam(":id", $this->id_lnkcomart);
                $selectId->execute();

                foreach($selectId as $id){
                    $idlnk[] = $id[0];
                }

                if(isset($idlnk))
                {
                    foreach($idlnk as $id_lnk){
                        $delete = $this->connexion->prepare("DELETE from cranomalie where id_produitcmd = :id and id_typeanomalie = 2");
                        $delete->bindParam(":id", $id_lnk);
                        $delete->execute();
                    }
                    
                }
            }
        }
        else
        {
            $reqhistorique = "SELECT id_cranomalie from cranomalie where id_produitcmd = :id";
            $historique = $this->connexion->prepare($reqhistorique);
            $historique->bindParam(":id", $id_produitcmd);
            $historique->execute();
            foreach($historique as $hist)
            {
                $historiqueCr = $hist[0];
            }

            $statut = "SELECT valid from lnkcommandearticle where id_commande = :id_commande";
            $reqStatut = $this->connexion->prepare($statut);
            $reqStatut->bindParam(":id_commande", $this->id_lnkcomart);
            $reqStatut->execute();
            $status = 0;
            $count = 0;
            foreach($reqStatut as $stat){
                if ($stat[0] == -1){
                    $status += 1;
                }
                $count += 1;
            }
            $res = $count - $status;

            if($res > 1)
            {
                // Ecriture de la requête SQL en y insérant le nom de la table
                $sql = "INSERT INTO cranomalie SET id_typeanomalie=:id_typeanomalie, id_descanomalie=:id_descanomalie, 
                id_produitcmd=:id_produitcmd, id_anomalie=:id_anomalie, valid=:valid";

                // Préparation de la requête
                $query = $this->connexion->prepare($sql);

                // Ajout des données protégées
                $query->bindParam(":id_typeanomalie", $id_typeanomalie);
                $query->bindParam(":id_descanomalie", $id_desc);
                $query->bindParam(":id_produitcmd", $id_produitcmd);
                $query->bindParam(":id_anomalie", $id_ano);
                $query->bindParam(":valid", $this->valid);

                // Exécution de la requête
                $query->execute();

                $updateStatut = "UPDATE lnkcommandearticle SET valid = :valid where id_lnkcommandearticle = :id_lnk";
                $req = $this->connexion->prepare($updateStatut);
                $req->bindParam(":valid", $this->invalid);
                $req->bindParam(":id_lnk", $id_produitcmd);
                $req->execute();
            }
            else
            {
                $id_typeanomalie = 1;

                $del = "DELETE FROM cranomalie where id_produitcmd = :id";
                $reqDel = $this->connexion->prepare($del);
                $reqDel->bindParam(":id", $id_produitcmd);
                $reqDel->execute();

                $updateStatutlnk = "UPDATE lnkcommandearticle SET valid = :valid where id_lnkcommandearticle = :id_lnk";
                $reqst = $this->connexion->prepare($updateStatutlnk);
                $reqst->bindParam(":valid", $this->valid);
                $reqst->bindParam(":id_lnk", $id_produitcmd);
                $reqst->execute();

                $commande = "SELECT id_commande from lnkcommandearticle where id_lnkcommandearticle = :idLNK";
                $reqCommande = $this->connexion->prepare($commande);
                $reqCommande->bindParam(":idLNK", $id_produitcmd);
                $reqCommande->execute();
                foreach($reqCommande as $com){
                    $id_com = $com[0];
                    $sql = "INSERT INTO cranomalie SET id_typeanomalie=:id_typeanomalie, id_descanomalie=:id_descanomalie, 
                    id_produitcmd=:id_produitcmd, id_anomalie=:id_anomalie, valid=:valid";

                    // Préparation de la requête
                    $query = $this->connexion->prepare($sql);

                    // Ajout des données protégées
                    $query->bindParam(":id_typeanomalie", $id_typeanomalie);
                    $query->bindParam(":id_descanomalie", $id_desc);
                    $query->bindParam(":id_produitcmd", $id_com);
                    $query->bindParam(":id_anomalie", $id_ano);
                    $query->bindParam(":valid", $this->valid);

                    // Exécution de la requête
                    $query->execute();

                    $updateStatut = "UPDATE commande SET valid = :valid where id_commande = :idcommande";
                    $req = $this->connexion->prepare($updateStatut);
                    $req->bindParam(":valid", $this->invalid);
                    $req->bindParam(":idcommande", $id_com);
                    $req->execute();
                }
            }
        }                   
        return false;
    }
}