<?php

namespace iutnc\deefy\Render;

class AlbumTrackRenderer extends AudioTrackRenderer{

    protected function renderCourt() :string{
        return <<<HTML
        <p>{$this->piste->titre} - {$this->piste->artisteAlbum}</p>   
        HTML;
    }

    protected function renderLong() :string{
        return <<<HTML
        <p>Titre : {$this->piste->titre}</p>
        <p>Artiste : {$this->piste->artisteAlbum}</p>
        <p>Genre : {$this->piste->genre}</p>
        <p>durée : {$this->piste->duree}</p>
        <p>Numéro de piste : {$this->piste->numAlbum}</p>
        <p>Année de sortie : {$this->piste->anneeAlbum}</p>
        <audio controls src="audio/{$this->piste->nomFichier}"></audio>
        HTML;
    }

}