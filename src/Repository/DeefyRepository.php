<?php

namespace iutnc\deefy\Repository;

use PDO;
use iutnc\deefy\Audio\tracks\AudioTrack;
use iutnc\deefy\Audio\tracks\AlbumTrack;
use iutnc\deefy\Audio\tracks\PodcastTrack;
use iutnc\deefy\Audio\lists\Playlist;

class DeefyRepository{

    private PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf) {
        $dsn = "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $conf['username'], $conf['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public static function setConfig(string $fichier) :void{
        $conf = parse_ini_file($fichier);
        if ($conf===false){
            Throw new \Exception("Chargement du fichier .ini impossible");
        }else{
            self::$config = $conf;
        }
    }

    public static function getInstance() :DeefyRepository{
        if (self::$instance === null){
            self::$instance = new self(self::$config);
        }
        return self::$instance;
    }

    public function findPlaylistById(int $id): Playlist {
        $stmt = $this->pdo->prepare("SELECT nom FROM playlist WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            throw new \Exception("Playlist introuvable (id : $id)");
        }

        $playlist = new Playlist($row['nom'], [], $id);

        $stmtTracks = $this->pdo->prepare("
            SELECT track.* FROM track
            INNER JOIN playlist2track ON track.id = playlist2track.id_track
            WHERE playlist2track.id_pl = :id
            ORDER BY playlist2track.no_piste_dans_liste
        ");
        $stmtTracks->execute([':id' => $id]);

        while ($trackRow = $stmtTracks->fetch(PDO::FETCH_ASSOC)) {
            if ($trackRow['type'] === 'A') {
                $piste = new AlbumTrack(
                    $trackRow['titre'],
                    $trackRow['filename'],
                    $trackRow['titre_album'] ?? '',
                    $trackRow['artiste_album'] ?? '',
                    $trackRow['genre'],
                    $trackRow['duree'] ?? 0,
                    $trackRow['numero_album'],
                    $trackRow['annee_album']
                );
            } else {
                $piste = new PodcastTrack(
                    $trackRow['titre'],
                    $trackRow['filename'],
                    $trackRow['auteur_podcast'] ?? '',
                    $trackRow['date_posdcast'] ?? '',
                    $trackRow['genre'] ?? '',
                    $trackRow['duree'] ?? 0
                );
            }
            $playlist->ajouterPiste($piste);
        }
        return $playlist;
    }

    public function saveEmptyPlaylist(Playlist $pk): Playlist{
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pk->nomPlaylist]);
        $pk->setId($this->pdo->lastInsertId());
        return $pk;
    }

    public function saveTrack(AudioTrack $track): int {
        $query = "INSERT INTO track (
                    titre, genre, duree, filename, type,
                    artiste_album, titre_album, annee_album, numero_album,
                    auteur_podcast, date_posdcast
                ) VALUES (
                    :titre, :genre, :duree, :filename, :type,
                    :artiste_album, :titre_album, :annee_album, :numero_album,
                    :auteur_podcast, :date_posdcast
                )";
        $stmt = $this->pdo->prepare($query);

        if ($track instanceof AlbumTrack) {
            $params = [
                ':titre'          => $track->titre,
                ':genre'          => $track->genre,
                ':duree'          => $track->duree,
                ':filename'       => $track->nomFichier,
                ':type'           => 'A',
                ':artiste_album'  => $track->artisteAlbum,
                ':titre_album'    => $track->nomAlbum,
                ':annee_album'    => $track->anneeAlbum,
                ':numero_album'   => $track->numAlbum,
                ':auteur_podcast' => null,
                ':date_posdcast'  => null,
            ];
        } else {
            $params = [
                ':titre'          => $track->titre,
                ':genre'          => $track->genre,
                ':duree'          => $track->duree,
                ':filename'       => $track->nomFichier,
                ':type'           => 'P',
                ':artiste_album'  => null,
                ':titre_album'    => null,
                ':annee_album'    => null,
                ':numero_album'   => null,
                ':auteur_podcast' => $track->auteurPodcast,
                ':date_posdcast'  => $track->datePodcast,
            ];
        }

        $stmt->execute($params);
        return (int) $this->pdo->lastInsertId();
    }

    public function addTrackToPlaylist(int $idPlaylist, int $idTrack): void {
        $stmtMax = $this->pdo->prepare("SELECT MAX(no_piste_dans_liste) AS max_no FROM playlist2track WHERE id_pl = :id_pl");
        $stmtMax->execute([':id_pl' => $idPlaylist]);
        $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
        $noPiste = ($row && $row['max_no'] !== null) ? ((int) $row['max_no'] + 1) : 1;
        
        $stmt = $this->pdo->prepare("INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (:id_pl, :id_track, :no)");
        $stmt->execute([
            ':id_pl'    => $idPlaylist,
            ':id_track' => $idTrack,
            ':no'       => $noPiste,
        ]);
    }

}