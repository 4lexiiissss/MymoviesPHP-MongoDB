<?php


use MongoDB\BSON\ObjectId;

$mdb = new myDbClass();

$client = $mdb->getClient();
$movies_collection = $mdb->getCollection('movies');
$confirm = GETPOST('confirm_envoyer');
if ($confirm == 'Envoyer') {
    /**
     *  A implémenter : 
     * Récupérer les données transmises par le formulaire
     * Les envoyer en tant que nouvel enregistrement dans votre base MongoDB
     * Si c'est OK : On retourne à la liste,
     * Si il y a eu une erreur, On reste sur la page d'ajout
     * */

    $title = $_POST['title'] ?? '';
    $year = $_POST['year'] ?? '';
    $realisateurs = $_POST['realisateurs'] ?? '';
    $production = $_POST['production'] ?? '';
    $actors = $_POST['actors'] ?? '';
    $synopsis = $_POST['synopsis'] ?? '';

    $realisateurs = array_map('trim', explode(',', $realisateurs));
    $production = array_map('trim', explode(',', $production));
    $actors = array_map('trim', explode(',', $actors));

    $errors = [];
    if (empty($title))
        $errors[] = "Le titre du film est requis.";
    if (empty($year) || !is_numeric($year))
        $errors[] = "L'année de sortie du film doit etre un nombre et requise.";
    if (empty($realisateurs))
        $errors[] = "Les réalisateurs sont requis.";
    if (empty($production))
        $errors[] = "Les producteurs sont requis.";
    if (empty($actors))
        $errors[] = "Les acteurs principaux sont requis.";
    if (empty($synopsis))
        $errors[] = "Le synopsis est requis.";

    if (empty($errors)) {
        $movieNew = [
            'title' => $title,
            'year' => (int) $year,
            'realisateurs' => $realisateurs,
            'production' => $production,
            'actors' => $actors,
            'synopsis' => $synopsis,
        ];

        try {
            $movies_collection->insertOne($movieNew);
            header('Location: index.php');
            exit();
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else {
        foreach ($errors as $error) {
            echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
        }
    }

    exit(0);
}
?>
<div class="dtitle w3-container w3-teal">
    <h2>Ajout d'un nouvel element</h2>
</div>
<form class="w3-container" action="index.php?action=add" method="POST">
    <div class="dcontent">
        <div class="w3-row-padding">
            <div class="w3-half">
                <label class="w3-text-blue" for="title"><b>Titre</b></label>
                <input class="w3-input w3-border" type="text" id="title" name="title" />
            </div>
            <div class="w3-half">
                <label class="w3-text-blue" for="year"><b>Année de sortie</b></label><br />
                <input type="text" id="year" name="year" />
            </div>
        </div>
        <div class="w3-row-padding">
            <div class="w3-half">
                <label class="w3-text-blue" for="realisateurs"><b>Réalisateurs</b></label>
                <textarea class="w3-input w3-border" id="realisateurs" name="realisateurs"></textarea>
            </div>
            <div class="w3-half">
                <label class="w3-text-blue" for="production"><b>production</b></label>
                <textarea class="w3-input w3-border" id="production" name="production"></textarea>
            </div>
        </div>
        <div class="w3-row-padding">
            <div class="w3-half">
                <label class="w3-text-blue" for="actors"><b>Acteurs Principaux</b></label>
                <textarea class="w3-input w3-border" id="actors" name="actors"></textarea>
            </div>
        </div>
        <label class="w3-text-blue" for="synopsis"><b>Synopsis</b></label>
        <textarea class="w3-input w3-border" id="synopsis" name="synopsis"></textarea>
        <br />
        <div class="w3-row-padding">
            <div class="w3-half">
                <input class="w3-btn w3-red" type="submit" name="cancel" value="Annuler" />
            </div>
            <div class="w3-half">
                <input class="w3-btn w3-blue-grey" type="submit" name="confirm_envoyer" value="Envoyer" />
            </div>
        </div>
        <br /><br />
</form>
</div>
<div class="dfooter">
</div>
</di