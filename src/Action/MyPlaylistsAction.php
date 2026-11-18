<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;

class MyPlaylistsAction extends Action {

    public function execute(): string {
        $repo = DeefyRepository::getInstance();
        $playlists = $repo->findAllPlaylists();

        $html = "<h2>Mes playlists</h2><ul>";
        foreach ($playlists as $pl) {
            $id = (int) $pl['id'];
            $nom = htmlspecialchars($pl['nom']);
            $html .= "<li><a href='?action=display-playlist&id=$id'>$nom</a></li>";
        }
        $html .= "</ul>";

        return $html;
    }
}