<?php

namespace iutnc\deefy\Dispatch;

use iutnc\deefy\Action\DefaultAction;

class Dispatcher {

    protected string $action;

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void {
        switch ($this->action) {
            case 'default':
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