<?php

namespace iutnc\deefy\Render;

interface Renderer {

    public const COURT = 1;
    public const LONG = 2;

    public function render(int $selecteur) :string;
}