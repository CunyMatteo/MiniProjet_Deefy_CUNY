<?php
require_once 'vendor/autoload.php';

use iutnc\deefy\Repository\DeefyRepository;
use iutnc\deefy\Render\AudioListRenderer;
use iutnc\deefy\Render\Renderer;

DeefyRepository::setConfig('config/db.config.ini');
$repo = DeefyRepository::getInstance();

$pl = $repo->findPlaylistById(1);
$renderer = new AudioListRenderer($pl);
echo $renderer->render(Renderer::COURT);