<?php


include '../pdo/dao_album.php';


$albunsPdo = DaoAlbum::getInstance();

$result = array();
$result = json_encode($result);

//Build an array of markers from the result set
  $albuns = $albunsPdo->BuscarTodos(0);
  //echo "<pre>";
  foreach ($albuns as $key => $album) {
    if($album['nome']==''){
      $album['nome'] = "Álbum #0".$album['id'];
    }
   $imgs['fotos'] = $albunsPdo->CarregarFotos($album['id']);

    if(sizeof($imgs['fotos'])>0){
  		$albuns[$key]['fotos'] = $imgs['fotos'];
  	}
  }

  //var_dump($markers);

 

  if(!sizeof($albuns )>0){  
    $albuns = 0;
  }

 if($albuns != 0){
    $result =  json_encode($albuns);       
  }
 
   echo $result;
    //echo "</pre>";
?>
