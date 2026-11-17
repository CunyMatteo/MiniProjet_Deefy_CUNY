<?php

namespace iutnc\deefy\Audio\tracks;

class AudioTrack{

    protected string $titre;
    protected ?string $genre;
    protected int $duree;
    protected string $nomFichier;

    public function __construct (string $titre, string $nomFichier, ?string $genre = null, int $duree = 0){
        $this->titre = $titre;
        $this->nomFichier = $nomFichier;
        $this->genre = $genre;
        $this->duree = $duree;
    }

    public function __get(string $name) :mixed{
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \Exception("Propriété invalide : $name");
    }

    
}