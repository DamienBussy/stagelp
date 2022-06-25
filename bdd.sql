CREATE TABLE centre (
    id_centre int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_parent int,
    codeCentre varchar(20),
    nomCentre varchar(255),
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);

CREATE TABLE commande (
    id_commande int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_centre int,
    id_fournisseur int,
    commandeId int,  
    dateLivraison datetime, 
    montantHT decimal,
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);

CREATE TABLE article (
    id_article INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_famille INT,
    articleCode varchar(20),
    articleLibelle varchar(255),
    conditionStockageCode varchar(255),
    conditionStockageLibelle varchar(255),
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);

CREATE TABLE lnkcommandearticle (
    id_lnkcommandearticle int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_commande int,
    id_article int,
    quantiteCommandee decimal(10,4), 
    prixAchatHT decimal(10,4), 
    uniteCommande varchar(10), 
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);

CREATE TABLE fournisseur (
    id_fournisseur int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fournisseurCode varchar(20), 
    fournisseurLibelle varchar(255), 
    fournisseurAvoirPrix tinyint,
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);


CREATE TABLE famille (
    id_famille int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_parent int,
    nv_famille int,
    familleNCode varchar(20), 
    familleNLibelle varchar(255),
    conditionStockageCode varchar(10),
    conditionStockageLibelle varchar(255),
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);


CREATE TABLE Tabhierarchie (
    id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    parent_id int,
    code varchar(255),
    nom varchar(20),    
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);

CREATE TABLE anomalie (
  id int(11) NOT NULL AUTO_INCREMENT,
  utilisateur_id int(11) DEFAULT NULL,
  entreprise_id int(11) NOT NULL,
  module_id int(11) DEFAULT NULL,
  releve_id varchar(50) DEFAULT NULL,
  rappel_id int(11) DEFAULT NULL,
  date_anomalie timestamp NULL DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  probleme varchar(150) DEFAULT NULL,
  action_corrective varchar(150) DEFAULT NULL,
  observation text DEFAULT NULL,
  corrected tinyint(1) NOT NULL DEFAULT 0,
  archived tinyint(1) NOT NULL DEFAULT 0,
  deleted tinyint(1) NOT NULL DEFAULT 0,
  guid varchar(255) DEFAULT NULL,
  client_last_updated timestamp NULL DEFAULT NULL,
  updated timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  created timestamp NOT NULL DEFAULT current_timestamp(),
  status int(5) DEFAULT 0,
  PRIMARY KEY (id),
  KEY INDEX_anomalie_entstatmodraprel (entreprise_id,status,module_id,rappel_id,releve_id),
  KEY INDEX_anomalie_delcorarc (deleted,corrected,archived)
);



CREATE TABLE cranomalie (
    id_cranomalie int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_typeanomalie int,
    id_descanomalie int,
    id_produitcmd int,
    id_anomalie int,
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);

CREATE TABLE typeanomalie (
    id_typeanomalie int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    libelle_typeanomalie varchar(50)
);


CREATE TABLE descanomalie (
    id_descanomalie int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_typeanomalie int,
    nomDescanomalie varchar(255),
    descriptionDescanomalie text,
    criticite int(5),
    dateCreate timestamp,
    dateUpdate timestamp,
    valid int
);

INSERT INTO tabhierarchie (parent_id, code, nom, dateCreate, dateUpdate, valid) VALUES (1, code1, CENTRE N°1, NULL, NULL, 1);

INSERT INTO typeanomalie (id_typeanomalie, libelle_typeanomalie) VALUES (1, "commande");
INSERT INTO typeanomalie (id_typeanomalie, libelle_typeanomalie) VALUES (2, "article");


ALTER TABLE commande ADD FOREIGN KEY (id_centre) REFERENCES centre(id_centre);
ALTER TABLE commande ADD FOREIGN KEY (id_fournisseur) REFERENCES fournisseur(id_fournisseur);
ALTER TABLE lnkcommandearticle ADD FOREIGN KEY (id_commande) REFERENCES commande(id_commande);
ALTER TABLE lnkcommandearticle ADD FOREIGN KEY (id_article) REFERENCES article(id_article);
ALTER TABLE article ADD FOREIGN KEY (id_famille) REFERENCES famille(id_famille);


ALTER TABLE descanomalie ADD FOREIGN KEY (id_typeanomalie) REFERENCES typeanomalie(id_typeanomalie);
ALTER TABLE cranomalie ADD FOREIGN KEY (id_typeanomalie) REFERENCES typeanomalie(id_typeanomalie);
ALTER TABLE cranomalie ADD FOREIGN KEY (id_descanomalie) REFERENCES descanomalie(id_descanomalie);
ALTER TABLE cranomalie ADD FOREIGN KEY (id_produitcmd) REFERENCES lnkcommandearticle(id_lnkcommandearticle);
ALTER TABLE cranomalie ADD FOREIGN KEY (id_produitcmd) REFERENCES commande(id_commande);
ALTER TABLE cranomalie ADD FOREIGN KEY (id_anomalie) REFERENCES anomalie(id);
ALTER TABLE anomalie ADD FOREIGN KEY (description) REFERENCES descanomalie(descriptionDescanomalie);