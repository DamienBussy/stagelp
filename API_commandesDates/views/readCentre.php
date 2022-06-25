<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Commande & Date</title></head>
<body>
    <form action="../api/read.php" method="post">
        <label for="code">Code du Centre : </label> <input type="text" name="code" />
        <br>
        <label for="dateDebut">Date Début : </label><input type="date" id="dateDebut" name="dateDebut">
        <br>
        <label for="dateFin">Date Fin : </label><input type="date" id="dateFin" name="dateFin">
        <br>
        <input type="submit" value="Rechercher" />
    </form>
</body>
</html>