<?php

namespace iutnc\deefy\Dispatch;

use iutnc\deefy\Action\DefaultAction;
use iutnc\deefy\Action\MyPlaylistsAction;
use iutnc\deefy\Action\DisplayPlaylistAction;
use iutnc\deefy\Action\CurrentPlaylistAction;
use iutnc\deefy\Action\AddPlaylistAction;
use iutnc\deefy\Action\AddPodcastTrackAction;

class Dispatcher {

    protected string $action;

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void {
        switch ($this->action) {
            case 'my-playlists':
                $act = new MyPlaylistsAction();
                break;

            case 'display-playlist':
                $act = new DisplayPlaylistAction();
                break;
            
            case 'current-playlist':
                $act = new CurrentPlaylistAction();
                break;

            case 'add-playlist':
                $act = new AddPlaylistAction();
                break;

            case 'add-track':
                $act = new AddPodcastTrackAction();
                break;

            default:
                $act = new DefaultAction();
                break;
        }

        $html = $act->execute();
        $this->renderPage($html);
    }

    private function renderPage(string $html): void {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Deefy</title>
        </head>
        <body>
            <header>
                <h1>Deefy</h1>
                <nav>
                    <ul>
                        <li><a href="?action=default">Accueil</a></li>
                        <li><a href="?action=my-playlists">Mes playlists</a></li>
                        <li><a href="?action=add-playlist">Créer une playlist</a></li>
                        <li><a href="?action=current-playlist">Playlist courante</a></li>
                    </ul>
                </nav>
            </header>
            <main>
                $html
            </main>
        </body>
        </html>
        HTML;
    }
}