<?php 
   
    if(!empty($_FILES)){


        $url = $_SERVER['HTTP_HOST'];
        //echo $url;
        $host = explode('.', $url);
        $subdomain = $host[0];

        if (!file_exists('../../../albuns/'.$subdomain)) {
            mkdir('../../../albuns/'.$subdomain, 0777, true);
        }

        $location = '../../../albuns/'.$subdomain;
        $filename = $_FILES['file']['name'];

        $total = count($_FILES['file']['name']);
        // Loop through each file
        for( $i=0 ; $i < $total ; $i++ ) {

          //Get the temp file path
          $tmpFilePath = $_FILES['file']['tmp_name'][$i];

          //Make sure we have a file path
          if ($tmpFilePath != ""){
            //Setup our new file path
            $newFilePath = "../../../albuns/" . $_FILES['file']['name'][$i];

            //Upload the file into the temp dir
            if(move_uploaded_file($tmpFilePath, $newFilePath)) {
              $suc = true;
            }else{
              $suc = false;
            }
          }else{
            $suc  = false;
          }
        }
        if($suc){
            echo 1;
        }else{
            echo 2;
        }
    }else{
            echo 2;
    }

?>


