<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Audio\tracks\PodcastTrack;
use iutnc\deefy\Repository\DeefyRepository;
use iutnc\deefy\Render\AudioListRenderer;
use iutnc\deefy\Render\Renderer;

class AddPodcastTrackAction extends Action {

    public function execute(): string {
        if (!isset($_SESSION['playlist'])) {
            return "<p>Erreur : aucune playlist courante sélectionnée.</p>";
        }

        if ($this->methodeHttp === 'GET') {
            return <<<HTML
            <h2>Ajouter une piste</h2>
            <form method="post" action="?action=add-track" enctype="multipart/form-data">
                <div>
                    <label for="titre">Titre :</label>
                    <input type="text" id="titre" name="titre" required>
                </div>
                <div>
                    <label for="auteur">Auteur :</label>
                    <input type="text" id="auteur" name="auteur" required>
                </div>
                <div>
                    <label for="duree">Durée (en secondes) :</label>
                    <input type="number" id="duree" name="duree" min="1" required>
                </div>
                <div>
                    <label for="genre">Genre :</label>
                    <input type="text" id="genre" name="genre">
                </div>
                <div>
                    <label for="fichier">Fichier audio (.mp3) :</label>
                    <input type="file" id="fichier" name="fichier" accept=".mp3,audio/mpeg" required>
                </div>
                <button type="submit">Ajouter la piste</button>
            </form>
            HTML;
        }

        $titre = filter_var(trim($_POST['titre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        $auteur = filter_var(trim($_POST['auteur'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        $duree = (int) ($_POST['duree'] ?? 0);
        $genre = filter_var(trim($_POST['genre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);

        if ($titre === '' || $auteur === '') {
            return "<p>Erreur : le titre et l'auteur sont obligatoires.</p>";
        }

        // Vérification de l'upload du fichier MP3
        if (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
            return "<p>Erreur lors de l'envoi du fichier audio.</p>";
        }

        $nomOriginal = $_FILES['fichier']['name'];
        $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));
        $typeMime = $_FILES['fichier']['type'];

        if ($extension !== 'mp3' || ($typeMime !== 'audio/mpeg' && $typeMime !== 'audio/mp3')) {
            return "<p>Erreur : seul le format MP3 est accepté.</p>";
        }

        // Création du dossier audio/ s'il n'existe pas encore
        $dossierAudio = __DIR__ . '/../../audio/';
        if (!is_dir($dossierAudio)) {
            mkdir($dossierAudio, 0755, true);
        }

        $nomFichier = uniqid('track_') . '.mp3';
        $destination = $dossierAudio . $nomFichier;

        if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $destination)) {
            return "<p>Erreur : impossible d'enregistrer le fichier sur le serveur.</p>";
        }

        $track = new PodcastTrack($titre, $nomFichier, $auteur, date('Y-m-d'), $genre, $duree);

        $repo = DeefyRepository::getInstance();
        $playlist = $_SESSION['playlist'];

        $idTrack = $repo->saveTrack($track);
        $repo->addTrackToPlaylist($playlist->id, $idTrack);

        $playlist->ajouterPiste($track);
        $_SESSION['playlist'] = $playlist;

        header('Location: ?action=current-playlist');
        exit;
    }
}