<?php

include '../pdo/dao_album.php';

include '../pdo/dao_logs.php';
$logsPdo = DaoLogs::getInstance();
$contentdata = file_get_contents("php://input");
$getdata = json_decode($contentdata);

$albumPdo = DaoAlbum::getInstance();

$dbresult = $albumPdo->Deletar(intval($getdata->id));

  if($dbresult){

    $result = 0;

    $logPojo = $logsPdo->Popula([
	  'data' => date("d/m/Y"),
	  'hora' => date("H:i:s"),
	  'usuario' => $_COOKIE['usrnl'],
	  'acao' => "Removeu o álbum #".$getdata->id." '".$getdata->nome."'."
	]);

	$logsPdo->Inserir($logPojo);

  }else{
    $result = 1;
  }
 
   echo $result;
?>
