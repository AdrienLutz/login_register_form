<?php
include 'parts/header.php';
require 'pdo.php';

var_dump($_SERVER['REQUEST_METHOD']);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Email saisie
    if (empty($_POST['email'])) {
        $errors['email'] = 'Veuillez saisir un email !';
    }
    // Email valide
    elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'email n\'est pas valide';
    }
    $stmt = $bdd->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam("email", $_POST["email"]);
    $stmt->execute();
    $res = $stmt->fetchAll();
    // count pour les tableaux (et non strlen), d'où d'ailleurs le fetchAll
    if (count($res) > 0) {
        $errors['email'] = 'Impossible, ce compte existe déjà';
    }
    // Password saisi ?
    if(empty($_POST['password'])){
        $errors["password"] = "Veuillez saisir un mot de passe";
    }
    if(empty($_POST['password2'])){
        $errors["password2"] = "Veuillez confirmer votre mot de passe";
    }

    // Le mot de passe fait 4 caractères
    // strlen pour les chaines de caractères (et non count)
    if(strlen($_POST['password']) < 4){
        $errors["password"] = "Le mot de passe doit faire au moins 4 caractères";
    }
    // Password confirmé
    if($_POST['password'] != $_POST['password2']){
        $errors["password2"] = 'Les mots de passe ne sont pas identiques';
    }
    // count pour les tablesu (et non strlen)
    if(count($errors) == 0){
        // Enregistrer mon utilisateur
        $stmt = $bdd->prepare(
            'INSERT INTO users (email, password)
            VALUES (:email, :password)'
            );

        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':password', password_hash($_POST["password"], PASSWORD_DEFAULT));

        $stmt->execute();

    // Redirection de l'utilisateur vers le login
    header("Location: login.php?message=success-login");
    }
}

?>


<body>
    <div class="container">
        <h1>Créer un compte</h1>
        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" class="form-control
        <?php
        // afficher les classes bs en fonction de la saisie
        // !bien se placer dans la classe
        // on reverifie la methode POST car sinon si on ne rentre rien dans l'input
        // il ne peut y avoir d'erreur donc "email" n'est pas dans $errors
        // donc la classe valid fait apparaitre d'office "looks good"
        if (array_key_exists("email", $errors)) {
            echo ('is-invalid');
        } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            echo ('is-valid');
        } ?>" type="text" value="<?php 
                // on garde le contenu en valeur pour pas tout retaper à chaque rafraichissement
                echo(isset($_POST['email'])?$_POST["email"]:"")?>" name="email" placeholder="email">
                <?php
                // afficher un message APRES l'input
                if (array_key_exists("email", $errors)) {
                    echo ('<div id="validationServerUsernameFeedback" class="invalid-feedback">
                ' . $errors['email'] . '
                </div>');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                    echo ('<div class="valid-feedback">
                    Looks good!
            </div>');
                }
                ?>
            </div>
            <div class="form-group mt-2">
        <label for="password">Mot de passe</label>
        <input id="password" class="form-control  <?php
        if(array_key_exists("password", $errors)){
            echo('is-invalid');
        } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
            echo('is-valid');
        }?>" type="password"
               value="<?php echo(isset($_POST['password'])?$_POST["password"]:"")?>"
               name="password" placeholder="Mot de passe">

        <?php
        if(array_key_exists("password", $errors)){
            echo('<div id="validationServerUsernameFeedback" class="invalid-feedback">
                '.$errors['password'].'
                </div>');
        } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
            echo('<div class="valid-feedback">
                    Looks good!
            </div>');
        }
        ?>

        <label for="password2">Confirmation du mot de passe</label>

        <input id="password2" class="form-control  <?php
        if(array_key_exists("password2", $errors)){
            echo('is-invalid');
        } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
            echo('is-valid');
        }?>" type="password"
               name="password2" placeholder="Confirmez"
        value="<?php echo(isset($_POST['password2'])?$_POST["password2"]:"")?>">

        <?php
        if(array_key_exists("password2", $errors)){
            echo('<div id="validationServerUsernameFeedback" class="invalid-feedback">
                '.$errors['password2'].'
                </div>');
        } else if($_SERVER["REQUEST_METHOD"] == 'POST') {
            echo('<div class="valid-feedback">
                    Looks good!
            </div>');
        }
        ?>

    </div>

            <input type="submit" class="btn btn-success mt-3">
        </form>
        <a href="login.php">Me connecter</a>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </div>
</body>

</html>