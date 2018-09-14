<?php

  include("config/config.php");

  $accessToken = getToken($clientId, $clientSecret);

  $songTitleList = "Hidrógeno,Helio,Litio,Berilio,Boro,Carbono,Nitrógeno,Oxígeno,Flúor,Neón,Sodio,Magnesio,Aluminio,Silicio,Fósforo,Azufre,Cloro,Argón,Potasio,Calcio,Escandio,Titanio,Vanadio,Cromo,Manganeso,Hierro,Cobalto,Níquel,Cobre,Zinc,Galio,Germanio,Arsénico,Selenio,Bromo,Kriptón,Rubidio,Estroncio,Itrio,Circonio,Niobio,Molibdeno,Tecnecio,Rutenio,Rodio,Paladio,Plata,Cadmio,Indio,Estaño,Antimonio,Telurio,Yodo,Xenón,Cesio,Bario,Lantano,Cerio,Praseodimio,Neodimio,Prometio,Samario,Europio,Gadolinio,Terbio,Disprosio,Holmio,Erbio,Tulio,Iterbio,Lutecio,Hafnio,Tántalo,Wolframio,Renio,Osmio,Iridio,Platino,Oro,Mercurio,Talio,Plomo,Bismuto,Polonio,Astato,Radón,Francio,Radio,Actinio,Torio,Protactinio,Uranio,Neptunio,Plutonio,Americio,Curio,Berkelio,Californio,Einstenio,Fermio,Mendelevio,Nobelio,Lawrencio,Rutherfordio,Dubnio,Seaborgio,Bohrio,Hasio,Meitnerio,Darmstatio,Roentgenio,Copernicio,Nihonio,Flerovio,Moscovio,Livermorio,Teneso,Oganesón";
  $songTitleList = "Hidrógeno,Helio,Litio";
  $songTitleArray = explode(",",$songTitleList);

  $stats = array();

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Tabla Periódica</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">

        <style>
            .card {margin-bottom: 0.5rem;}
            .card .tile.tile-centered .tile-content{width: 80%;}
            .btn-play {float:right;}
            .btn-play:before {content: "  >"; font-size: 16px; font-weight: bold; color: #666; padding: 5px 7px; border: 2px solid #666; border-radius: 50%;}
            .btn-play.playing:before {content: "||";}

        </style>
    </head>
    <body class="bg-gray">
        <div class="container">
        <h1>Tabla Periódica v0.2</h1>
        <div class="columns">
            <div class="column col-4 col-xl-6 col-md-8 col-sm-12 col-mx-auto">
                <blockquote class="twitter-tweet" data-lang="en"><p lang="es" dir="ltr">Mi hermana y yo estamos buscando canciones con elementos de la tabla periódica. De momento tenemos (por orden de número atómico):<br>· Lithium de Evanescense.<br>· Titanium de David Guetta feat. Sia.<br>y<br>· Gold de Spandau Ballet.<br><br>¿Conocéis más?</p>&mdash; Súbete a la nutria (@subetealanutria) <a href="https://twitter.com/subetealanutria/status/1039225332547637248?ref_src=twsrc%5Etfw">September 10, 2018</a></blockquote>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>


<?php
 foreach ($songTitleArray as $key=>$songTitle) {

  $songArray = getSongList($songTitle, $accessToken);

  $stats[$songTitle] = count($songArray);

?>
            <div class="column col-12">
                <h2><?= ($key+1), " - ", ucfirst(mb_strtolower($songTitle)) ?><span class="badge" data-badge="<?= count($songArray) ?>"></span></h2>
            </div>

<?php
foreach ($songArray as $song) {

    $artistArray = array();
    foreach ($song["artists"] as $artist) {
        $artistArray[] =  $artist;
    }
?>
            <div class="column col-3 col-xl-4 col-md-6 col-sm-12">
              <div class="card">
                <div class="card-body">
                  <div class="tile tile-centered">
                    <div class="tile-icon">
                        <a href="<?= $song["url"] ?>" target="blank">
                            <img src="<?= $song["image"] ?>" class="icon icon-file centered" width="48" alt="<?= $song["name"] ?>"/>
                        </a>
                    </div>
                    <div class="tile-content">
                        <p class="tile-title"><?= implode(", ", $artistArray) ?></p>
                        <p class="tile-subtitle text-gray"><?= $song["name"] ?></p>
                    </div>
                    <div class="btn-play">
                        <audio class="js-player" src="<?= $song["preview"] ?>"></audio>
                    </div>
                  </div>
                </div>
              </div>
            </div>
<?php
    }
?>

<?php
}

/*
foreach ($stats as $name => $count) {
    echo $name , ";" , $count , "<br/>";
}
*/

?>

          </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                let playButtons = document.getElementsByClassName('btn-play');
                [].forEach.call(playButtons, activateButtons);

                function activateButtons(button){
                    button.addEventListener('click', function(){
                        if (button.classList.contains("playing")){
                            button.querySelector('audio').pause();
                        } else {
                            button.querySelector('audio').play();
                        }
                        button.classList.toggle('playing');
                    })
                }
            });
        </script>
    </body>
</html>



<?php
function getToken($clientId, $clientSecret){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,            'https://accounts.spotify.com/api/token' );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_POST,           1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS,     'grant_type=client_credentials' );
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: Basic '.base64_encode($clientId.':'.$clientSecret)));

    $result=curl_exec($ch);
    $json = json_decode($result, true);

    //print_r($json);

    return $json['access_token'];

}



function getSongList($songTitle, $accessToken) {

    $apiEndpoint = "https://api.spotify.com/v1/search?q=".urlencode("track:$songTitle")."&type=track&limit=50";

    $results = array();
    $pageCount = 0;

    do {

        $json = curlCall($apiEndpoint, $accessToken);
        $apiEndpoint = $json["tracks"]["next"];
        $results = array_merge($results, $json["tracks"]["items"]);
        $pageCount++;
        //print_r($results);
        //break;

    } while (isset($json["tracks"]["next"]) && $pageCount < 100);

    $trackList = array();

    foreach ($results as $key => $track) {

        $artistList = array();
        foreach ($track["artists"] as $artist) {
            $artistList[$artist["id"]] = $artist["name"];
        }

        if (strtolower($track["name"])!==strtolower($songTitle)) {
            continue;
        }
        $trackList[$key]["artists"] = $artistList;
        $trackList[$key]["url"] = $track["external_urls"]["spotify"];
        $trackList[$key]["name"] = $track["album"]["name"];
        $trackList[$key]["image"] = $track["album"]["images"][2]["url"];
        $trackList[$key]["preview"] = $track["preview_url"];
    }

    //print_r($trackList);

    return $trackList;

}



function curlCall($apiEndpoint, $accessToken){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_HTTPHEADER,
                  array('Accept: application/json',
                        'Content-Type: application/json',
                        'Authorization: Bearer '.$accessToken));

    $result=curl_exec($ch);
    $json = json_decode($result, true);

    //print_r($json);

    return $json;

}
