<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Render\AudioListRenderer;
use iutnc\deefy\Render\Renderer;

class CurrentPlaylistAction extends Action {

    public function execute(): string {
        if (!isset($_SESSION['playlist'])) {
            return "<p>Aucune playlist courante sélectionnée.</p>";
        }

        $playlist = $_SESSION['playlist'];

        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(Renderer::COURT);
        $html .= "<p><a href='?action=add-track'>Ajouter une piste</a></p>";

        return $html;
    }
}