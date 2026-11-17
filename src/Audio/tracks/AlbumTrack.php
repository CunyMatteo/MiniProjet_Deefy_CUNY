<?php

namespace iutnc\deefy\Audio\tracks;

class AlbumTrack extends AudioTrack{

    protected string $nomAlbum;
    protected string $artisteAlbum;
    protected ?int $numAlbum;
    protected ?int $anneeAlbum;

    public function __construct(string $titre, string $nomFichier, string $nomAlbum, string $artisteAlbum, ?string $genre = null, int $duree = 0, ?int $numAlbum = null, ?int $anneeAlbum = null) {
        parent::__construct($titre, $nomFichier, $genre, $duree);
        $this->nomAlbum = $nomAlbum;
        $this->artisteAlbum = $artisteAlbum;
        $this->numAlbum = $numAlbum;
        $this->anneeAlbum = $anneeAlbum;
    }

}