<?php

namespace iutnc\deefy\Render;

use iutnc\deefy\Audio\tracks\AlbumTrack;
use iutnc\deefy\Audio\lists\Playlist;
use iutnc\deefy\Audio\tracks\PodcastTrack;

class AudioListRenderer implements Renderer{

    protected Playlist $playlist;

    public function __construct(Playlist $playlist){
        $this->playlist = $playlist;
    }

    public function render(int $selecteur) :string{
        $html = "<h3>{$this->playlist->nomPlaylist}</h3>";
        $dureeTotale = 0;
        $nbPistes = 0;

        foreach ($this->playlist->listePistes as $piste){
            if ($piste instanceof AlbumTrack){
                $renderer = new AlbumTrackRenderer($piste);
                $html = $html . '<br>' .  $renderer->render($selecteur);
            }else{
                if ($piste instanceof PodcastTrack){
                    $renderer = new PodcastTrackRenderer($piste);
                    $html = $html . '<br>' .  $renderer->render($selecteur);
                }
            }
            $dureeTotale = $dureeTotale + $piste->duree;
            $nbPistes++;
        }

        $html = $html . '<br>' . "Nombre de pistes : " . $nbPistes . '<br>' . "Durée totale : " . $dureeTotale;
        return $html;

    }
}