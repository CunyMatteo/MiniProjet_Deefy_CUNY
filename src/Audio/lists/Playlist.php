<?php

namespace iutnc\deefy\Audio\lists;
use iutnc\deefy\Audio\tracks\AudioTrack;

class Playlist{

    protected string $nomPlaylist;
    protected array $listePistes = [];
    protected ?int $id;

    public function __construct(string $nomPlaylist, array $listePistes = [], ?int $id = null) {
        $this->nomPlaylist = $nomPlaylist;
        $this->listePistes = $listePistes;
        $this->id = $id;
    } 

    public function __get(string $name) :mixed{
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \Exception("Propriété invalide : $name");
    }

    public function setId(?int $id) :void{
        $this->id = $id;
    }

    public function ajouterPiste(AudioTrack $piste) :void{
        $this->listePistes[] = $piste;
    }

}