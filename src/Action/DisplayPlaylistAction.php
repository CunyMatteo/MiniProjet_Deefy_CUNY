<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;
use iutnc\deefy\Render\AudioListRenderer;
use iutnc\deefy\Render\Renderer;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        if (!isset($_GET['id'])) {
            return "<p>Erreur : aucun identifiant de playlist fourni.</p>";
        }

        $id = (int) $_GET['id'];
        $repo = DeefyRepository::getInstance();

        try {
            $playlist = $repo->findPlaylistById($id);
        } catch (\Exception $e) {
            return "<p>Playlist introuvable.</p>";
        }

        $_SESSION['playlist'] = $playlist;

        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(Renderer::COURT);
        $html .= "<p><a href='?action=add-track'>Ajouter une piste</a></p>";

        return $html;
    }
}