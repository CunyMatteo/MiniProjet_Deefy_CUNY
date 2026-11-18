<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Audio\lists\Playlist;
use iutnc\deefy\Repository\DeefyRepository;
use iutnc\deefy\Render\AudioListRenderer;
use iutnc\deefy\Render\Renderer;

class AddPlaylistAction extends Action {

    public function execute(): string {
        if ($this->methodeHttp === 'GET') {
            return <<<HTML
            <h2>Créer une nouvelle playlist</h2>
            <form method="post" action="?action=add-playlist">
                <label for="nom">Nom de la playlist :</label>
                <input type="text" id="nom" name="nom" required>
                <button type="submit">Créer</button>
            </form>
            HTML;
        }

        // Traitement en POST
        $nom = trim($_POST['nom'] ?? '');
        if ($nom === '') {
            return "<p>Erreur : le nom de la playlist ne peut pas être vide.</p>";
        }

        $nomClean = filter_var($nom, FILTER_SANITIZE_SPECIAL_CHARS);
        $playlist = new Playlist($nomClean);

        $repo = DeefyRepository::getInstance();
        $playlist = $repo->saveEmptyPlaylist($playlist);

        $_SESSION['playlist'] = $playlist;

        header('Location: ?action=current-playlist');
        exit;
    }
}