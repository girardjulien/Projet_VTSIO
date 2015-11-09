<?php


// Dépendances
include_once 'vendor/dom/simple_html_dom.php';
require 'vendor/autoload.php';


// Nouvel objet Slim avec redéfinition du chemin pour les templates
// Le fichier template comprend le header ainsi que le footer du projet
$app = new \Slim\Slim([
  'templates.path' => 'templates'
]);


// Fonction de connexion sur la base de donnée (PDO)
function dbconnection() {
  return new PDO('mysql:dbname=vtsio;host=localhost','root','');
}


// Page d'accueil 
$app->get('/home', function () use($app) {

    $db = dbconnection();

    // Menu déroulant affichant les tags présent dans la base de donnée
    echo '<div class="container">
            <form class="form-horizontal" role="form" method="post">
              <div class="form-group">
                <label for="sel1">Select filter:</label>
                <select class="form-control" id="sel1" name="choice">
                  <option value="0">No Filter</option>';

    $tags = $db->query("select idTag, tag from tags");  

    foreach ($tags as $d) {
      echo       '<option value="'.$d['idTag'].'">'.$d['tag'].'</option>';
    }

    echo       '</select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
              </div>  
            </form>';

    //Tableau de liens contenant le lien, le titre de la page ainsi que la source
            
    // Pour éviter les caractères spéciaux        
    $db->exec("SET NAMES 'utf8'");


    $result = $db->query("select link, title, source from links order by idLink DESC");

          echo  '<table class="table table-striped">
              <thead>
                  <tr>
                      <th>link</th>
                      <th>title</th>
                      <th>source</th>
                  </tr>
              </thead>
              <tbody>';
    foreach($result as $d){
        echo '<tr>';

        //Ouvre le lien dans un nouvel onglet (_blank)
        echo '<td><a href="'.$d['link'].'" target="_blank">'.$d['link'].'</a></td>';

        echo '<td>'.$d['title'].'</td>';
        echo '<td>'.$d['source'].'</td>';
        echo '</tr>';
    }    
    
    echo     '</tbody>
          </table>
      </div>'; 

})->name('home');


// Traitement des données
$app->post('/home', function() use($app){

  $db = dbconnection();

  // Menu déroulant et tableau dans la partie traitement car chaques requetes ramène sur la page de traitement
  echo '<div class="container">
            <form class="form-horizontal" role="form" method="post">
              <div class="form-group">
                <label for="sel1">Select filter:</label>
                <select class="form-control" id="sel1" name="choice">
                  <option value="0"">No Filter</option>';
  $tags = $db->query("select idTag, tag from tags");             
  foreach ($tags as $d) {
    echo         '<option value="'.$d['idTag'].'">'.$d['tag'].'</option>';
    }

  echo        '</select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
              </div>  
            </form>';

  // Récupération du choix du tag          
  $choix = $app->request->post('choice');
  $db->exec("SET NAMES 'utf8'");

  // Requète spéciale si il n'y a pas de tag spécifiques choisis
  if ($choix == 0){
    $result = $db->query("select link, title, source from links order by idLink DESC");
  } 

  else {
    $result = $db->query("select link, title, source from links, links_has_tags, tags where links.idLink=links_has_tags.Links_idLink and links_has_tags.Tags_idTag=tags.idTag and idTag='$choix' order by idLink DESC");
  }
          echo  '<table class="table table-striped">
              <thead>
                  <tr>
                      <th>link</th>
                      <th>title</th>
                      <th>source</th>
                  </tr>
              </thead>
              <tbody>';
    foreach($result as $d){
        echo '<tr>';
        echo '<td><a href="'.$d['link'].'" target="_blank">'.$d['link'].'</a></td>';
        echo '<td>'.$d['title'].'</td>';
        echo '<td>'.$d['source'].'</td>';
        echo '</tr>';
    }    
    
    echo     '</tbody>
          </table>
      </div>';

});


// Page de création de liens
$app->get('/create', function() use($app){

  $db = dbconnection();

  //Formulaire de saisie du lien associé avec des tags
    echo '<div class="container">
          <form class="form-horizontal" role="form" method="post">
            <div class="form-group">
              <label class="control-label col-sm-2" for="link">Link :</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="link" placeholder="Enter link">
              </div>
            </div>
            <div class="form-group"> 
              <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">';

    $tags = $db->query("select idTag, tag from tags");

    foreach ($tags as $d) {
      echo '<label><input type="checkbox" name="tag[]" value="'.$d['idTag'].'">'.$d['tag'].'</label>&nbsp&nbsp&nbsp';
    }

    echo        '</div>
          </div>
      </div>
        <div class="form-group"> 
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Submit</button>
          </div>
        </div>    
    </form>
    </div>';

})->name('create');


// Traitement des données
$app->post('/create', function() use($app) {

    $db = dbconnection();

    $link = $app->request->post('link');

    // Vérifier si un URL est valide
    if (@fopen($link, 'r')) {

      // Utilisation de la librairie externe simple_html_dom  
      $html = file_get_html($link);

      // Récupération du contenu de la balise html <title>
      $title = $html->find('title', 0)->plaintext;

      // Enlève les simples quotes car SQL ne les accepte pas
      $title = str_replace("'", "", $title);

      // Utilisation d'une Regexp pour avoir la source
      // https://www.google.fr/ deviendrait google
      $source = preg_replace("/(^http:\/\/www.|^https:\/\/www.|^http:\/\/|^https:\/\/)([a-z0-9\.-]*[^\/])(.*)\.[^.]+$/i", "$2", $link);

      // Insertions des données dans la base
      $db->query("insert into links (idLink, link, title, source) values ('', '$link', '$title', '$source')");

      $lastid = $db->lastInsertId();

      foreach ($app->request->post('tag') as $tag) {
        $db->query("INSERT INTO `links_has_tags` VALUES ('$lastid' ,'$tag');");
      }

      $app->redirect('home');

    } 

    else {
      echo "L'url rentrée n'est pas valide!";
    }

});


// Page d'ajout de tags
$app->get('/add', function() use($app){

  echo'<div class="container">
        <form class="form-horizontal" role="form" method="post">
          <div class="form-group">
            <label class="control-label col-sm-2" for="newTag">New Tag :</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="newTag" placeholder="Enter a new Tag">
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Submit</button>
            </div>
          </div>
        </form>
      </div>';

})->name('add');


// Insertion du nouveau tag dans la base de donnée
$app->post('/add', function() use($app){

  $db = dbconnection();

  $newTag = $app->request->post('newTag');

  $db->query("insert into tags values ('', '$newTag')");

  $app->redirect('home');

});


// Page d'informations
$app->get('/about', function() use($app){

  echo '<div class="container">
          <center><h2>About</h2>
          <p>This class project is to build a technology monitoring tool.</p>
          <p>- You can find a link with a specified tag,</p>
          <p>- You can add your link with many tags,</p>
          <p>- And you can add your own tags!</p>
          </br>
          <p>Project of : <b>Momenteau Jules, Fontaine Maxime and Girard Julien</b></p>
          </br></br></br></br>
          <p><i>Developped by <b>Girard Julien</b></i></p></center>
        </div>';

})->name('about');


// Affichage du header, du contenu et du footer dans l'ordre.
$app->render('header.php', compact('app'));
$app->run();
$app->render('footer.php', compact('app'));


?>