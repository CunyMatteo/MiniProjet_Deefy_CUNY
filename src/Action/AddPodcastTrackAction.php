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
            <form method="post" action="?action=add-track">
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
                    <label for="fichier">Nom du fichier audio :</label>
                    <input type="text" id="fichier" name="fichier" placeholder="ex: track.mp3" required>
                </div>
                <button type="submit">Ajouter la piste</button>
            </form>
            HTML;
        }

        $titre = filter_var(trim($_POST['titre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        $auteur = filter_var(trim($_POST['auteur'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        $duree = (int) ($_POST['duree'] ?? 0);
        $genre = filter_var(trim($_POST['genre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        $fichier = filter_var(trim($_POST['fichier'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);

        if ($titre === '' || $fichier === '') {
            return "<p>Erreur : le titre et le fichier sont obligatoires.</p>";
        }

        $track = new PodcastTrack($titre, $fichier, $auteur, date('Y-m-d'), $genre, $duree);

        $repo = DeefyRepository::getInstance();
        $playlist = $_SESSION['playlist'];

        $idTrack = $repo->saveTrack($track);
        $repo->addTrackToPlaylist($playlist->id, $idTrack);

        $playlist->ajouterPiste($track);
        $_SESSION['playlist'] = $playlist;

        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(Renderer::COURT);
        $html .= "<p><a href='?action=add-track'>Ajouter une autre piste</a></p>";

        return $html;
    }
}