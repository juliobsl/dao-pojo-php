<?php
  // ini_set('display_errors', '1');
  // ini_set('display_startup_errors', '1');
  // error_reporting(E_ALL);
include '../pdo/dao_album.php';
include '../pdo/dao_logs.php';
$logsPdo = DaoLogs::getInstance();

$albunsPdo = DaoAlbum::getInstance();

$nome = $_POST['nomeAlbum'];

// var_dump($_FILES['uploadImagens']);
// var_dump($nome);
// exit;

if($nome == '' || $nome == 'undefined' || $nome == null || empty($nome)){
    $nome = null;
}

$albumForm = $albunsPdo->Popula([
  'nome' => $nome
]);

$dbresult = $albunsPdo->Inserir($albumForm);

  if($dbresult){
   
    $idalbum = $albunsPdo->UltimaAtt();

    //$files = array_filter($_FILES['uploadImagens']['name']); //something like that to be used before processing files.    
    $suc = true;
    // Count # of uploaded files in array
    $total = count($_FILES['uploadImagens']['name']);
    // Loop through each file
    for( $i=0 ; $i < $total ; $i++ ) {

      //Get the temp file path
      $tmpFilePath = $_FILES['uploadImagens']['tmp_name'][$i];

      //Make sure we have a file path
      if ($tmpFilePath != ""){
        //Setup our new file path
        $url = $_SERVER['HTTP_HOST'];
        //echo $url;
        $host = explode('.', $url);
        $subdomain = $host[0];

        if (!file_exists('../../../albuns/'.$subdomain)) {
            mkdir('../../../albuns/'.$subdomain, 0777, true);
        }

        $location = '../../../albuns/'.$subdomain."/";

        $ext = explode(".", $_FILES["uploadImagens"]["name"][$i]);
        $newimgname = $subdomain."-album-".$idalbum."-imagem-0".$i."-".date("dmYhis"). '.' . end($ext);

        $newFilePath = $location . $newimgname;

        //Upload the file into the temp dir
        if(move_uploaded_file($tmpFilePath, $newFilePath)) {
          $albunsPdo->AddImg($newimgname,$idalbum);
        }else{
          $suc= false;
        }
      }else{
        $suc = false;
      }
    }

    if($suc){
      $result = 0;

      $logPojo = $logsPdo->Popula([
        'data' => date("d/m/Y"),
        'hora' => date("H:i:s"),
        'usuario' => $_COOKIE['usrnl'],
        'acao' => "Adicionou o álbum #".$idalbum." '".$nome."' com ".$total." imagens."
      ]);

      $logsPdo->Inserir($logPojo);

    }else{
      $result = 1;
      $albunsPdo->Deletar(intval($idalbum));
    }

  }else{
    $result = 1;
  } 
  
  header("Location:../../albuns.php?r=".$result);

?>
