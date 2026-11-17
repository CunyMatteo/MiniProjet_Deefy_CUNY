<?php

namespace iutnc\deefy\Audio\tracks;

class PodcastTrack extends AudioTrack{

    protected string $auteurPodcast;
    protected ?string $datePodcast;

    public function __construct(string $titre, string $nomFichier, string $auteurPodcast, ?string $datePodcast = null, ?string $genre = null, int $duree = 0) {
        parent::__construct($titre, $nomFichier, $genre, $duree);
        $this->auteurPodcast = $auteurPodcast;
        $this->datePodcast = $datePodcast;
    }
}