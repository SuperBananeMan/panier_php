<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();
if ($_POST) {
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    if ($_POST['nom'] === '' || $_POST['prix'] === '' || $_POST['quantite'] === '') {
        echo 'Veuillez remplir tous les champs<br>';
        exit;
    }
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    echo 'Le produit ' . $nom . ' a été ajouté au panier<br>';

    $_SESSION['panier'][] = [
        'nom' => $nom,
        'prix' => $prix,
        'quantite' => $quantite
    ];
}
?>

<form action="index.php" method="post">
    <label for="nom">Nom</label>
    <input type="text" name="nom" id="nom" required>
    <label for="prix">Prix</label>
    <input type="text" name="prix" id="prix" required>
    <label for="quantite">Quantité</label>
    <input type="text" name="quantite" id="quantite" required>
    <input type="submit" value="Ajouter">
</form>
<br>

<?php
if (!empty($_SESSION['panier'])) {
    function afficherPanier($panier)
    {
        echo '
            Voici le contenu de votre panier :
            <br>
            <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($panier as $produit) {
            echo '
                <tr>
                    <td>' . array_search($produit, $panier) . '</td>
                    <td>' . $produit['nom'] . '</td>
                    <td>' . $produit['prix'] . ' €</td>
                    <td>' . $produit['quantite'] . '</td>
                </tr>
            ';
        }
        echo '
            <tr>
                <td class="center" colspan="4">Total</td>
            </tr>
            <tr>
                <td class="center" colspan="4">' . $_SESSION['total'] . '</td>
            </tr>
        ';
        echo '
            </tbody>
            </table>
            <br>
        ';
    }

    function calculerSousTotal($produit)
    {
        $sousTotal = $produit['prix'] * $produit['quantite'];
        echo 'Le sous-total pour le ' . $produit['nom'] . ' est de ' . $sousTotal . ' €<br>';
        return $sousTotal;
    }

    function calculerTotal($panier)
    {
        $total = 0;
        foreach ($panier as $produit) {
            $total += calculerSousTotal($produit);
        }
        echo '<br>';
        echo 'Le total est de ' . $total . ' €<br><br>';
        if ($total > 50) {
            echo 'Vous avez droit à une réduction de 10%<br>';
            $total *= 0.9;
            echo 'Le total avec réduction est de ' . $total . ' €<br><br>';
        }
        $_SESSION['total'] = $total;
        afficherPanier($panier);
    }

    calculerTotal($_SESSION['panier']);

    echo '<a href="vider.php">Vider le panier</a>';
} else {
    echo 'Le panier est vide';
}
?>
</body>
</html>
