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
        <br>
        <p>Artiste : {$this->piste->artisteAlbum}</p>
        <br>
        <p>Genre : {$this->piste->genre}</p>
        <br>
        <p>durée : {$this->piste->duree}</p>
        <br>
        <p>Numéro de piste : {$this->piste->numAlbum}</p>
        <br>
        <p>Année de sortie : {$this->piste->anneeAlbum}</p>
        <br>
        <audio controls src="audio/{$this->piste->nomFichier}"></audio>
        HTML;
    }

}