<h1>Gestion des frais</h1>
<form action="/gsb/index.php?uc=controleur&action=formvalider" method="POST">
    <h2>Saisie</h2>
    <div class="field">
        <label for="visiteur_id">
            Visiteur :
        </label>
        <span>
            numéro : <select name="visiteur_id" id="visiteur_id">
                <?php foreach ($visiteurs as $ligne) {
                    echo "<option value='{$ligne['id']}'>{$ligne['id']} ({$ligne['nom']})</option>";
                } ?>
            </select>
        </span>
    </div>
    <div class="field">
        <div>
            Période d'engagement
        </div>
        <span>
            <label for="mois">
                Mois (2 chiffres) : <input type="number" name="mois" id="mois" value="<?= isset($_REQUEST['mois']) ? $_REQUEST['mois'] : '' ?>">
            </label>    
            <label for="annee">
                Année (4 chiffres) : <input type="number" name="annee" id="annee" value="<?= isset($_REQUEST['annee']) ? $_REQUEST['annee'] : '' ?>">
            </label>
        </span>
    </div>
    <h2>Frais au forfait</h2>
    <div class="field">
        <label for="repasmidi">
            Repas midi :
        </label>
        <input type="number" name="mon_champ[repasmidi]" id="repasmidi">
    </div>
    <div class="field">
        <label for="nuitees">
            Nuitées :
        </label>
        <input type="number" name="mon_champ[nuitees]" id="nuitees">
    </div>
    <div class="field">
        <label for="etape">
            Étape :
        </label>
        <input type="number" name="mon_champ[etape]" id="etape">
    </div>
    <div class="field">
        <label for="km">
            Km :
        </label>
        <input type="number" name="mon_champ[km]" id="km">
    </div>
    <div class="field">
        <?= isset($error) ? $error : null ?>
        <button type="submit">
            Valider
        </button>
    </div>
</form>