<?php

use MongoDB\BSON\ObjectId;

$mdb = new myDbClass();

$client = $mdb->getClient();
$id = GETPOST('id');
if ($id == '') {
?>
    <div class="dtitle w3-container w3-teal">
        <h2>Cet élément n'a pas été trouvé</h2>
    </div>
<?php
} else {
    $obj_id = new MongoDB\BSON\ObjectId($id);
    $movies_collection = $mdb->getCollection('movies');
    $cursor = $movies_collection->find(
        ['_id' => $obj_id],
        ['limit' => 1],
    );

    $cursor->setTypeMap(array('root' => 'array', 'document' => 'array', 'array' => 'array'));
    $iterator = new IteratorIterator($cursor);

    $iterator->rewind();
    $cols = array(
        '_id' => array('lbl' => '#', 'type' => 'id'),
        'title' => array('lbl' => 'Titre', 'type' => 'text'),
        'year' => array('lbl' => 'Année', 'type' => 'text'),
        'production' => array('lbl' => 'Production', 'type' => 'array'),
        'actors' => array('lbl' => 'Acteurs', 'type' => 'array'),
        'synopsis' => array('lbl' => 'Synopsis', 'type' => 'textarea'),
    );
    // $iterator->next();
    if ($iterator->valid()) {
        $document = $iterator->current();
        $elt = secure_document($document, $cols);
    } else {
?>
        <div class="dtitle w3-container w3-teal">
            <h2>Cet élément n'a pas été trouvé</h2>
        </div>
<?php
        exit();
    }

    $confirm = GETPOST('confirm_envoyer');
    if ($confirm == 'Envoyer') {
        /** 
         * A implémenter : 
         * Récupérer les données transmises par le formulaire 
         * Les envoyer pour supprimer l'enregistrement correspondant dans votre base MongoDB 
         * Si c'est OK : On supprime, 
         * Si il y a eu une erreur, On reste sur la page de base. 
         **/
        
        try {
            $movies_collection->deleteOne(['_id' => $obj_id]);
            header('Location: index.php?action=list');
            exit();
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }

        exit(0);
    }

?>
    <div class="dtitle w3-container w3-teal">
        <h2>Suppression d'un élément</h2>
    </div>
    <form class="w3-container" action="index.php?action=delete" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />
        <div class="dcontent">
            <p>Êtes-vous sûr de vouloir supprimer cet élément ?</p>
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
<?php
}
