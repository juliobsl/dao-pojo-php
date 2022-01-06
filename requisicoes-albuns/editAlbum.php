<?php
  // ini_set('display_errors', '1');
  // ini_set('display_startup_errors', '1');
  // error_reporting(E_ALL);

include '../pdo/dao_album.php';

include '../pdo/dao_logs.php';
$logsPdo = DaoLogs::getInstance();

$albunsPdo = DaoAlbum::getInstance();



$id = $_POST['idEdit'];
$nome = $_POST['nomeAlbumEdit'];
$situacao = $_POST['situacaoEdit'];
// var_dump($_FILES['uploadImagensEdit']);
// var_dump($nome);
// exit;

if($nome == '' || $nome == 'undefined' || $nome == null || empty($nome)){
    $nome = null;
}

$albumForm = $albunsPdo->Popula([
  'id' => $id,
  'nome' => $nome,
  'situacao' => $situacao
]);

$dbresult = $albunsPdo->Editar($albumForm);

  if($dbresult){
   
    $idalbum = $id;

    //$files = array_filter($_FILES['uploadImagensEdit']['name']); //something like that to be used before processing files.    
   
    // Count # of uploaded files in array
    $total = count($_FILES['uploadImagensEdit']['name']);
    // Loop through each file
    if($total>0 || $_FILES['uploadImagensEdit']['name'][0]!=""){
      for( $i=0 ; $i < $total ; $i++ ) {

        //Get the temp file path
        $tmpFilePath = $_FILES['uploadImagensEdit']['tmp_name'][$i];

        //Make sure we have a file path
        if ($tmpFilePath != ""){
          $url = $_SERVER['HTTP_HOST'];
          //echo $url;
          $host = explode('.', $url);
          $subdomain = $host[0];

          if (!file_exists('../../../albuns/'.$subdomain)) {
              mkdir('../../../albuns/'.$subdomain, 0777, true);
          }

          $location = '../../../albuns/'.$subdomain."/";

          $ext = explode(".", $_FILES["uploadImagensEdit"]["name"][$i]);
          $newimgname = $subdomain."-album-".$idalbum."-imagem-0".$i."-".date("dmYhis"). '.' . end($ext);

          $newFilePath = $location . $newimgname;

          //Upload the file into the temp dir
          if(move_uploaded_file($tmpFilePath, $newFilePath)) {
            $albunsPdo->AddImg($newimgname,$idalbum);
          }
        }
      }

    }
       
    $result = 0;

    if($situacao==1){
      $sit = 'Publicado';
    }else{
      $sit = 'Em rascunho';
    }

	$logPojo = $logsPdo->Popula([
	  'data' => date("d/m/Y"),
	  'hora' => date("H:i:s"),
	  'usuario' => $_COOKIE['usrnl'],
	  'acao' => "Atualizou o álbum #".$id." '".$nome."' (".$sit.")."
	]);

	$logsPdo->Inserir($logPojo);

  }else{
    $result = 1;
  } 
  
  header("Location:../../albuns.php?e=".$result);

?>
