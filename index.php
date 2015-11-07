<?php

include_once 'vendor/dom/simple_html_dom.php';
require 'vendor/autoload.php';

$app = new \Slim\Slim([
	'templates.path' => 'templates'
]);

function dbconnection() {
	return new PDO('mysql:dbname=vtsio;host=localhost','root','');
}

$app->get('/table', function () use($app) {
   	$db = dbconnection();
   	echo '<div class="container">
            <form class="form-horizontal" role="form" method="post">
              <div class="form-group">
                <label for="sel1">Select list:</label>
                <select class="form-control" id="sel1" name="choice">
                  <option value="0" href="'. $app->urlFor('table').'">No Filter</option>
                  <option value="1">Java</option>
                  <option value="2">Python</option>
                  <option value="3">PHP</option>
                </select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
              </div>  
            </form>';

    $db->exec("SET NAMES 'utf8'");
    $result = $db->query("select idLinks, link, titre from links");

          echo	'<table class="table table-striped">
            	<thead>
                	<tr>
                    	<th>id</th>
                    	<th>link</th>
                    	<th>title</th>
                	</tr>
            	</thead>
            	<tbody>';
    foreach($result as $d){
   	    echo '<tr>';
        echo '<td>'.$d['idLinks'].'</td>';
        echo '<td><a href="'.$d['link'].'">'.$d['link'].'</a></td>';
        echo '<td>'.$d['titre'].'</td>';
        echo '</tr>';
    }    
    
    echo     '</tbody>
        	</table>
    	</div>'; 
})->name('table');

$app->post('/table', function() use($app){
  $db = dbconnection();
  echo '<div class="container">
            <form class="form-horizontal" role="form" method="post">
              <div class="form-group">
                <label for="sel1">Select filter:</label>
                <select class="form-control" id="sel1" name="choice">
                  <option value="0" href="'.$app->urlFor('table').'">No Filter</option>
                  <option value="1">Java</option>
                  <option value="2">Python</option>
                  <option value="3">PHP</option>
                </select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-default">Submit</button>
              </div>  
            </form>';
  $choix = $app->request->post('choice');
  $db->exec("SET NAMES 'utf8'");
  if ($choix == 0){
    $result = $db->query("select idLinks, link, titre from links");
  } else {
    $result = $db->query("select idLinks, link, titre from links, links_has_tags, tags where links.idLinks=links_has_tags.Links_idLinks and links_has_tags.Tags_idTag=tags.idTag and idTag='$choix'");
  }
          echo  '<table class="table table-striped">
              <thead>
                  <tr>
                      <th>id</th>
                      <th>link</th>
                      <th>title</th>
                  </tr>
              </thead>
              <tbody>';
    foreach($result as $d){
        echo '<tr>';
        echo '<td>'.$d['idLinks'].'</td>';
        echo '<td><a href="'.$d['link'].'">'.$d['link'].'</a></td>';
        echo '<td>'.$d['titre'].'</td>';
        echo '</tr>';
    }    
    
    echo     '</tbody>
          </table>
      </div>';
});

$app->get('/create', function() use($app){
    echo '<form class="form-horizontal" role="form" method="post">
  <div class="form-group">
    <label class="control-label col-sm-2" for="link">Link :</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="link" placeholder="Enter link">
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label><input type="checkbox" name="tag[]" value="1"> Java</label>
        <label><input type="checkbox" name="tag[]" value="2"> Python</label>
        <label><input type="checkbox" name="tag[]" value="3"> PHP</label>
      </div>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form>';
});

$app->post('/create', function() use($app) {
    $db = dbconnection();

    $link = $app->request->post('link');
    if (@fopen($link, 'r')) {
      echo $link;
      $html = file_get_html($link);
      $title = $html->find('title', 0)->plaintext;

      $db->query("insert into links (idLinks, link, titre) values ('', '$link', '$title')");
      $lastid = $db->lastInsertId();
      foreach ($app->request->post('tag') as $tag) {
        $tag = intval($tag);
        print $tag;
        $db->query("INSERT INTO `links_has_tags` VALUES ('$lastid' ,'$tag');");
      }

    } else {
      echo "L'url rentrée n'est pas valide!";
    }

});

$app->render('header.php', compact('app'));
$app->run();
$app->render('footer.php', compact('app'));

?>