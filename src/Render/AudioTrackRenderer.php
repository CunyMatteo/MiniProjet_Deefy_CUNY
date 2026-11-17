<?php

namespace iutnc\deefy\Render;
use iutnc\deefy\Audio\tracks\AudioTrack;

abstract class AudioTrackRenderer implements Renderer{

    protected AudioTrack $piste;

    public function __construct(AudioTrack $piste){
        $this->piste = $piste;
    }

    public function render(int $selecteur) :string {
        if ($selecteur === self::COURT){
            return $this->renderCourt();
        }else {
            if ($selecteur === self::LONG){
            return $this->renderLong();
            }else{
                Throw new \Exception("Format de rendu invalide");
            }
        }
    }

    abstract protected function renderCourt() :string;

    abstract protected function renderLong() :string;
}