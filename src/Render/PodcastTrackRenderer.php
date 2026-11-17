<?php

namespace iutnc\deefy\Render;

class PodcastTrackRenderer extends AudioTrackRenderer{

    protected function renderCourt() :string{
        return <<<HTML
        <p>{$this->piste->titre} - {$this->piste->auteurPodcast}</p>   
        HTML;
    }

    protected function renderLong() :string{
        return <<<HTML
        <p>Titre : {$this->piste->titre}</p>
        <br>
        <p>Auteur : {$this->piste->auteurPodcast}</p>
        <br>
        <p>Genre : {$this->piste->genre}</p>
        <br>
        <p>durée : {$this->piste->duree}</p>
        <br>
        <p>Année de sortie : {$this->piste->datePodcast}</p>
        <br>
        <audio controls src="audio/{$this->piste->nomFichier}"></audio>
        HTML;
    }

}