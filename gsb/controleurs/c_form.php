<?php

$action = !isset($_REQUEST['action']) ? 'accueil' : $_REQUEST['action'];
$visiteurs = PdoGsb::getPdoGsb()->getVisiteurs();

switch ($action) {
    case 'accueil':
        include('vues/v_accueil.php');
        break;
    case 'formvalider':
        if (!isset($_REQUEST['visiteur_id']))
            include('vues/v_accueil.php');
        else if (!$_REQUEST['mois'])
            echo 'Mois maquant';
        else if (!$_REQUEST['annee'])
            echo 'Année maquante';
        else if (!$_REQUEST['mon_champ'])
            echo 'Veuillez compléter tous les champs';
        else {
            $visiteur = PdoGsb::getPdoGsb()->getInfosVisiteur($_REQUEST['visiteur_id']);
            $inputNull = 0;
            $labels = [
                "etape" => "ETP",
                "repasmidi" => "REP",
                "nuitees" => "NUI",
                "km" => "KM"
            ];

            if (!$visiteur) {
                echo "Impossible de trouver le visiteur '{$_REQUEST['visiteur_id']}'";
                break;
            }

            foreach ($_REQUEST['mon_champ'] as $key => $value) {
                if (!$value) {
                    $inputNull += 1;
                    continue;
                }

                foreach ($labels as $keyLabel => $idFraisForfais) {
                    if ($key != $keyLabel)
                        continue;

                    $date = $_REQUEST['annee'].$_REQUEST['mois'];
                    $checkFicheFraisExist = PdoGsb::getPdoGsb()->getFicheFrais($_REQUEST['visiteur_id'], $date, $idFraisForfais);

                    if (!$checkFicheFraisExist) {
                        $request = PdoGsb::getPdoGsb()->insertFicheFrais($_REQUEST['visiteur_id'], $date, 0, 0, date('Y-m-d'), "CR");
                        if (gettype($request) == 'boolean' && $request) {
                            echo "<p><code>{$request}</code> ajouté avec succès dans la table fichefrais</p>";
                        } else {
                            echo "<p>Impossible d'ajouter <code>{$keyLabel}</code> dans la table fichefrais</p>";
                            continue;
                        }
                    }

                    $request = PdoGsb::getPdoGsb()->insertForfait($_REQUEST['visiteur_id'], $date, intval($value), $idFraisForfais);
                    if (gettype($request) == 'boolean' && $request)
                        echo "<p><code>{$request}</code> ajouté avec succès dans la table lignefraisforfait</p>";
                    else
                    echo "<p>Impossible d'ajouter <code>{$keyLabel}</code> dans la table fichefrais</p>";
                }
            }

            if ($inputNull == count($_REQUEST['mon_champ']))
                echo "Veuillez préciser au moins l'un des champs 'frais fortfait'";

            include('vues/v_accueil.php');
        }
        break;
}
