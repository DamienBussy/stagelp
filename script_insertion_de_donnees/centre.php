<?php
$MonFile = "./BASE_REFERENTIEL.csv";

$cpt = 0;

$arrHierarchie = array();

// POSISTION

$arrPos = array();

$arrPos["CODE_CP"] = 0;

$arrPos["LIB_CP"] = 1;

$arrPos["CODE_SEGMENT"] = 3;

$arrPos["LIB_SEGMENT"] = 4;

$arrPos["CODE_DIVISION"] = 5;

$arrPos["LIB_DIVISION"] = 6;

$arrPos["CODE_DR"] = 7;

$arrPos["LIB_DR"] = 8;

$arrPos["CODE_SECTEUR"] = 9;

$arrPos["LIB_SECTEUR"] = 10;

$arrPos["CODE_SITE"] = 11;

$arrPos["LIB_SITE"] = 12;

?>
<pre>
<?php 

if (($handle = fopen($MonFile, "r")) !== FALSE)

{
    

    while (($arrData = fgetcsv($handle, 0, ";")) !== FALSE)

    {

        if (count($arrData) == 13)

        {

            if ($cpt == 0)

            {
                $cpt++;

                continue;
            }

            $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["LIB_CP"]];
            // var_dump($arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]]);
            if (!array_key_exists("NOM", $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]]))

            {

                $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]]["NOM"] = $arrData[$arrPos["LIB_SEGMENT"]];

            }
            if (!array_key_exists("NOM", $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]]))

            {

                $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]]["NOM"] = $arrData[$arrPos["LIB_DIVISION"]];

            }

            if (!array_key_exists("NOM", $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]]))

            {

                $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]]["NOM"] = $arrData[$arrPos["LIB_DR"]];

            }

            if (!array_key_exists("NOM", $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]]))

            {

                $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]]["NOM"] = $arrData[$arrPos["LIB_SECTEUR"]];

            }

            if (!array_key_exists("NOM", $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]]))

            {

                $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]]["NOM"] = $arrData[$arrPos["LIB_SITE"]];

            }

            if (!array_key_exists("NOM", $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_CP"]]]))

            {

                $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["LIB_CP"]];

            }

            $cpt++;

        }

        // Tableau des SEGMENTS
        $segmentsNOM[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]]["NOM"];
        $segmentsCODE[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["CODE_SEGMENT"]];

        // Tableau des DIVISIONS
        $divisionsNOM[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]]["NOM"];
        $divisionsCODE[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["CODE_DIVISION"]];
        $arrtestDiv[] = $arrData[$arrPos["CODE_SEGMENT"]];


        // Tableau des SECTEURS
        $secteursNOM[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]]["NOM"];
        $secteursCODE[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["CODE_SECTEUR"]];
        $arrtestSec[] = $arrData[$arrPos["CODE_DIVISION"]];

        // Tableau des SITES
        $sitesNOM[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]]["NOM"];
        $sitesCODE[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["CODE_SITE"]];
        $arrtestSit[] = $arrData[$arrPos["CODE_SECTEUR"]];
        // Tableau des FX
        $fxNOM[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"];
        $fxCODE[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["CODE_CP"]];
        $arrtestFX[] = $arrData[$arrPos["CODE_SITE"]];
    

        // Tableau des FR
        $frNOM[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["LIB_CP"]];
        $frCODE[] = $arrHierarchie[$arrData[$arrPos["CODE_SEGMENT"]]][$arrData[$arrPos["CODE_DIVISION"]]][$arrData[$arrPos["CODE_DR"]]][$arrData[$arrPos["CODE_SECTEUR"]]][$arrData[$arrPos["CODE_SITE"]]][$arrData[$arrPos["CODE_CP"]]]["NOM"] = $arrData[$arrPos["CODE_CP"]];
        $arrtestFR[] = $arrData[$arrPos["CODE_SITE"]];


    }
}
// print_r($frNOM);

// debian
// $conn = new PDO("mysql:host=localhost;dbname=restaurant", "test", "test");
// $db = new mysqli("localhost", "test", "test", "restaurant");

// wamp
$conn = new PDO("mysql:host=localhost;dbname=stagelpmi", "root", "");


$seg = array();
$i = 1;

foreach ($segmentsCODE as $code)
{
    $seg[$i] = $code;
    $i = $i + 2;
}
$a = 2;
foreach($segmentsNOM as $nom)
{
    $seg[$a] = $nom;
    $a  += 2;
    
}

$rang = 2;
$idsegment = 2;
foreach($seg as $segment)
{
    $test = $seg[$rang];
    
    $tableauSegment[] = $segment;
    $tableauSegmentNom[] = $test;
    $countNOM = 0;
    $count = 0;
    foreach($tableauSegment as $select)
    {
        if($segment == $select)
        {
            $count = $count +1;
        }
    }
    foreach($tableauSegmentNom as $selectNom)
    {
        if($test == $selectNom)
        {
            $countNOM = $countNOM +1;
        }
    }
    if ($countNOM < 2)
    {
        if($idsegment < 12)
        {
            $reqSeg = $conn->prepare("INSERT INTO Tabhierarchie (parent_id, nom, code, valid) VALUES (1, :nom, :code, 1)");
            $reqSeg->bindParam(':nom', $test);
            $reqSeg->bindParam(':code', $segment);
            $reqSeg->execute();
            $idsegment += 1;
        }
    }
    $rang += 2;
}

// INSERT DIVISION
$div = array();
$i = 1;

foreach ($divisionsCODE as $code)
{
    $div[$i] = $code;
    $i = $i + 2;
}
$a = 2;
foreach($divisionsNOM as $nom)
{
    $div[$a] = $nom;
    $a  += 2;
    
}

$rang = 2;
$iddivision = 13;
$parentid[] = array();
$x = 0;
foreach($arrtestDiv as $data)
{
    $tabDiv[] = $data;
}
foreach($div as $division)
{
    $test = $div[$rang];
    
    $tableauDivision[] = $division;
    $tableauDivisionNom[] = $test;
    $countNOM = 0;
    $count = 0;
    foreach($tableauDivision as $select)
    {
        if($division == $select)
        {
            $count = $count +1;
        }
    }
    foreach($tableauDivisionNom as $selectNom)
    {
        if($test == $selectNom)
        {
            $countNOM = $countNOM +1;
        }
    }
    $id = 0;

    $codeparent = $tabDiv[$x];

    $parentid = $conn->prepare("SELECT id from Tabhierarchie where code = :code");
    $parentid->bindParam(':code', $codeparent);
    $parentid->execute();
    foreach($parentid as $data){
        $id = $data[0];
    }
    // print_r($id);
    $valid = 1;
    $x += 1;
    if ($countNOM < 2)
    {
        $reqDiv = $conn->prepare("INSERT INTO Tabhierarchie (parent_id, nom, code, valid) VALUES (:id, :nom, :code, :valid)");
        $reqDiv->bindParam(':id', $id);
        $reqDiv->bindParam(':nom', $test);
        $reqDiv->bindParam(':code', $division);
        $reqDiv->bindParam(':valid', $valid);
        $reqDiv->execute();
        $iddivision += 1;        
    }
    $rang += 2;
}


// // INSERT SECTEUR

$sec = array();
$i = 1;

foreach ($secteursCODE as $code)
{
    $sec[$i] = $code;
    $i = $i + 2;
}
$a = 2;
foreach($secteursNOM as $nom)
{
    $sec[$a] = $nom;
    $a  += 2;
}

$rang = 2;
$idsecteur = 63;
$parentidSec[] = array();
$x = 0;
foreach($arrtestSec as $data)
{
    $tabSec[] = $data;
}
foreach($sec as $secteur)
{
    $test = $sec[$rang];
    
    $tableauSecteur[] = $secteur;
    $tableauSecteurNom[] = $test;
    $countNOM = 0;
    $count = 0;
    foreach($tableauSecteur as $select)
    {
        if($secteur == $select)
        {
            $count = $count +1;
        }
    }
    foreach($tableauSecteurNom as $selectNom)
    {
        if($test == $selectNom)
        {
            $countNOM = $countNOM +1;
        }
    }
    $id = 0;

    $codeparent = $tabSec[$x];
    $parentidSec = $conn->prepare("SELECT id from Tabhierarchie where code = :code");
    $parentidSec->bindParam(':code', $codeparent);
    $parentidSec->execute();
    foreach($parentidSec as $data){
        $id = $data[0];
    }
    // print_r($id);
    $valid = 1;
    $x += 1;
    if ($countNOM < 2)
    {
        if ($idsecteur < 1029)
        {
            $reqDiv = $conn->prepare("INSERT INTO Tabhierarchie (parent_id, nom, code, valid) VALUES (:id, :nom, :code, :valid)");
            $reqDiv->bindParam(':id', $id);
            $reqDiv->bindParam(':nom', $test);
            $reqDiv->bindParam(':code', $secteur);
            $reqDiv->bindParam(':valid', $valid);
            $reqDiv->execute();
            $idsecteur +=1; 
        }
           
    }
    $rang += 2;
    
}


// // INSERT SITE

$sit = array();
$i = 1;

foreach ($sitesCODE as $code)
{
    $sit[$i] = $code;
    $i = $i + 2;
}
$a = 2;
foreach($sitesNOM as $nom)
{
    $sit[$a] = $nom;
    $a  += 2;
}

$rang = 2;
$idsite = 1029;
$parentidSit[] = array();
$x = 0;
foreach($arrtestSit as $data)
{
    $tabSit[] = $data;
}
foreach($sit as $site)
{
    $test = $sit[$rang];
    
    $tableauSite[] = $site;
    $tableauSiteNom[] = $test;
    $countNOM = 0;
    $count = 0;
    foreach($tableauSite as $select)
    {
        if($site == $select)
        {
            $count = $count +1;
        }
    }
    foreach($tableauSiteNom as $selectNom)
    {
        if($test == $selectNom)
        {
            $countNOM = $countNOM +1;
        }
    }
    $id = 0;

    $codeparent = $tabSit[$x];
    $parentidSit = $conn->prepare("SELECT id from Tabhierarchie where code = :code");
    $parentidSit->bindParam(':code', $codeparent);
    $parentidSit->execute();
    foreach($parentidSit as $data){
        $id = $data[0];
    }
    // print_r($id);
    $valid = 1;
    $x += 1;
    if ($countNOM < 2)
    {
        if ($idsite < 7083)
        {
            $reqDiv = $conn->prepare("INSERT INTO Tabhierarchie (parent_id, nom, code, valid) VALUES (:id, :nom, :code, :valid)");
            $reqDiv->bindParam(':id', $id);
            $reqDiv->bindParam(':nom', $test);
            $reqDiv->bindParam(':code', $site);
            $reqDiv->bindParam(':valid', $valid);
            $reqDiv->execute();
            $idsecteur +=1; 
        }
    }
    $rang += 2;
    
}


// INSERT FR

$cp = array();
$i = 1;

foreach ($frCODE as $code)
{
    $cp[$i] = $code;
    $i = $i + 2;
}
$a = 2;
foreach($frNOM as $nom)
{
    $cp[$a] = $nom;
    $a  += 2;
}

$rang = 2;
$idfr = 5424;
$parentidCp[] = array();
$x = 0;
foreach($arrtestFR as $data)
{
    $tabFr[] = $data;
}
foreach($cp as $fr)
{
    $test = $cp[$rang];
    
    $tableauFR[] = $fr;
    $tableauFRNom[] = $test;
    $countNOM = 0;
    $count = 0;
    foreach($tableauFR as $select)
    {
        if($fr == $select)
        {
            $count = $count +1;
        }
    }
    foreach($tableauFRNom as $selectNom)
    {
        if($test == $selectNom)
        {
            $countNOM = $countNOM +1;
        }
    }
    $id = 0;

    $codeparent = $tabFr[$x];
    // print_r($codeparent);
    $parentidCp = $conn->prepare("SELECT id from Tabhierarchie where code = :code");
    $parentidCp->bindParam(':code', $codeparent);
    $parentidCp->execute();
    foreach($parentidCp as $data){
        $id = $data[0];
    }
    // print_r($id);
    $valid = 1;
    $x += 1;
    if ($countNOM < 2)
    {
        $reqDiv = $conn->prepare("INSERT INTO Tabhierarchie (parent_id, nom, code, valid) VALUES (:id, :nom, :code, :valid)");
        $reqDiv->bindParam(':id', $id);
        $reqDiv->bindParam(':nom', $test);
        $reqDiv->bindParam(':code', $fr);
        $reqDiv->bindParam(':valid', $valid);
        $reqDiv->execute();
        $idsecteur +=1; 
        
    }
    $rang += 2;
    
}