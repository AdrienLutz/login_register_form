<?php
try {
    $host = 'localhost';
    $dbName = 'login_bdd';
    $user = 'root';
    $password = '';
    $bdd = new PDO(
        'mysql:host='.$host.';dbname='.$dbName.';charset=utf8',
        $user,
        $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e){
    throw new InvalidArgumentException('Erreur connexion à la base de données :'.$e->getMessage());
    exit;
}
?>