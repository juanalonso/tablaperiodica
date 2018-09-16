<?php 
  
  //https://picturepan2.github.io/spectre/
  
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
        <meta charset="utf-8"/>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125783247-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'UA-125783247-1');
        </script>
        <title>Tabla Periódica</title>
        <meta property="og:title" content="Tabla periódica">
        <meta property="og:type" content="article">
        <meta property="og:url" content="http://neuronasmuertas.com/tablaperiodica">
        <meta property="og:site_name" content="Neuronas muertas">
        <meta property="og:image" content="http://neuronasmuertas.com/tablaperiodica/img/card.png">
        <meta property="og:description" content="Lista de canciones de Spotify que se llaman exactamente igual que un elemento químico">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@kokuma">
        <meta name="twitter:title" content="Tabla periódica">
        <meta name="twitter:description" content="Lista de canciones de Spotify que se llaman exactamente igual que un elemento químico">
        <meta name="twitter:creator" content="@kokuma">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
        <style>
            .card {margin-bottom: 0.5rem;}
        </style>
    </head>
    <body class="bg-gray">
        <div class="container">
        <h1>Tabla Periódica v0.25</h1>
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
            <div class="column col-2 col-xl-4 col-md-6 col-sm-12">
              <div class="card">
                <div class="card-body">
                  <div class="tile tile-centered">
                    <div class="tile-icon">
                      <a href="<?= $song["url"] ?>" target="blank"><img src="<?= $song["image"] ?>" class="icon icon-file centered" width="48" alt="<?= $song["name"] ?>"/></a>
                    </div>
                    <div class="tile-content">
                        <p class="tile-title"><?= implode(", ", $artistArray) ?></p>
                        <p class="tile-subtitle text-gray"><?= $song["name"] ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
<?php
    }
}

/*
foreach ($stats as $name => $count) {
    echo $name , ";" , $count , "<br/>";
}
*/

?>
          </div>
          <div class="grid-lg">
            <p>Desarrollado por <a href="https://twitter.com/kokuma" target="_blank">kokuma</a>, con ayuda de <a href="https://twitter.com/erikiva" target="_blank">erikiva</a>, a partir de un tuit de <a href="https://twitter.com/subetealanutria" target="_blank">subetealanutria</a>.</p>
          </div>
        </div>
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
