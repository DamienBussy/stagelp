<?php
class Commandes{
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

/**
 * Constructeur avec $db pour la connexion à la base de données
 *
 * @param $db
 */
    public function __construct($db){
        $this->connexion = $db;
    }


/**
 * Lecture des commandes
 *
 * @return void
 */
    public function lire(){
        // On écrit la requête
        $sql = "SELECT id_commande, id_centre, id_fournisseur, commandeId, dateLivraison, montantHT, valid FROM commande";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        // On retourne le résultat
        return $query;

        //$req = $this->db->query('SELECT id_commande, id_centre, id_fournisseur, commandeId, dateLivraison, montantHT, valid FROM commande;');
        //$req = $req->result();

    }

// Read One

/**
 * Lire un produit
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
        $row = $req->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->id= $row['id'];
        $this->nom= $row['nom'];
    }

/**
 * Lire un produit
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
 * @return void
 */
    //LNKCOMMANDEARTICLE
    public function lireUnLnk()
    {
        // On écrit la requête
        $sql = "SELECT id_article, quantiteCommandee, prixAchatHT, uniteCommande FROM lnkcommandearticle WHERE id_commande = :id_commande";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On attache l'id
        $query->bindParam(':id_commande', $this->id);

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



    // // Create
    // /**
    //  * Créer une commande
    //  *
    //  * @return void
    //  */
    // public function creer()
    // {
    //     // Récupération de l'id centre avec le code
    //     $recupIdCentre = "SELECT id from Tabhierarchie where code = :codeCentre";
    //     $reqCen = $this->connexion->prepare($recupIdCentre);
    //     $reqCen->bindParam(":codeCentre", $this->code_centre);
    //     $reqCen->execute();
    //     foreach($reqCen as $data){
    //         $id_centre = $data[0];
    //     }

    //     // Récupération de l'id fournisseur avec le code
    //     $recupIdFournisseur = "SELECT id_fournisseur from fournisseur where fournisseurCode = :code";
    //     $reqFou = $this->connexion->prepare($recupIdFournisseur);
    //     $reqFou->bindParam(":code", $this->code_fournisseur);
    //     $reqFou->execute();
    //     foreach($reqFou as $data){
    //         $id_fournisseur = $data[0];
    //     }
    //     if(!is_null($id_centre) && !is_null($id_fournisseur))
    //     {
    //         // Ecriture de la requête SQL en y insérant le nom de la table
    //         $sql = "INSERT INTO commande SET id_centre=:id_centre, id_fournisseur=:id_fournisseur, commandeId=:commandeId, 
    //         dateLivraison=:dateLivraison, montantHT=:montantHT, valid=:valid";

    //         // Préparation de la requête
    //         $query = $this->connexion->prepare($sql);


    //         // Ajout des données protégées
    //         $query->bindParam(":id_centre", $id_centre);
    //         $query->bindParam(":id_fournisseur", $id_fournisseur);
    //         $query->bindParam(":commandeId", $this->commandeId);
    //         $query->bindParam(":dateLivraison", $this->dateLivraison);
    //         $query->bindParam(":montantHT", $this->montantHT);
    //         $query->bindParam(":valid", $this->valid);

    //         // Exécution de la requête
    //         if($query->execute()){
    //             return true;
    //         }
    //     }
    //     else{
    //         echo 'Les codes fournisseur et/ou centre ne sont pas correct';
    //     }
        
    //     return false;
    // }

    // public function creerArticle()
    // {
    //     $commande = "SELECT max(id_commande) from commande";
    //     $reqId = $this->connexion->prepare($commande);
    //     $reqId->execute();
    //     foreach($reqId as $data){
    //         $id_commande = $data[0];
    //     }
        
    //     $selectArticle = "SELECT id_article from article where articleLibelle = :articleLibelle";
    //     $select = $this->connexion->prepare($selectArticle);
    //     $select->bindParam(":articleLibelle", $this->articleList);
    //     foreach($select as $data){
    //         $idArticle = $data[0];
    //     }
    //     var_dump($idArticle);
    //     // Ecriture de la requête SQL en y insérant le nom de la table
    //     $sql = "INSERT INTO lnkcommandearticle SET id_commande=:id_commande, id_article=:id_article, quantiteCommandee=:quantiteCommandee, 
    //     prixachatHT=:prixachatHT, uniteCommande=:uniteCommande, valid=:valid";

    //     // Préparation de la requête
    //     $query = $this->connexion->prepare($sql);


    //     // Ajout des données protégées
    //     $query->bindParam(":id_commande", $id_commande);
    //     $query->bindParam(":id_article", $idArticle);
    //     $query->bindParam(":quantiteCommandee", $this->articleList);
    //     $query->bindParam(":prixachatHT", $this->prixachatHT);
    //     $query->bindParam(":uniteCommande", $this->uniteCommande);
    //     $query->bindParam(":valid", $this->valid);

    //     // Exécution de la requête
    //     if($query->execute()){
    //         return true;
    //     }

        
    //     return false;
    // }
}