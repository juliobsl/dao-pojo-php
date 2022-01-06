<?php

    // - nome: album
    // - vars: id,nome,situacao
    
    if($_POST){
        //echo "<pre>";
        if($_POST['definicao']){
            $definicao = $_POST['definicao'];
            $def1 = explode("-",$definicao);

            $objs =  array();
            foreach($def1 as $key => $itens){
                if($itens){

                    $naux = str_replace(" ","",$itens);
                    $iaux = explode(":",$naux);

                    $chave = trim($iaux[0]);
                    $valor = trim($iaux[1]);

                    if($chave=="vars"){
                        $vaux = str_replace(" ","",$valor);
                        $vaux = explode(",",$vaux);
                        $valor = $vaux;
                    }
                        $objs[$chave] = $valor;
                }
                
            }
            //echo "<br>";
            //var_dump($objs);
        

            $saida = '';

            foreach ($objs as $key => $obj) {
                if($key=="nome"){
                    $saida .= "\n".'class Pojo'.ucfirst($obj).' {';
                }

                if($key=="vars"){
                    $saida .="\n\n";
                    for($i=0;$i<sizeof($obj);$i++){
                        $saida .= "\t".'private '."$".$obj[$i].';'."\n";
                    } 
                    $saida .="\n\n";
                    for($i=0;$i<sizeof($obj);$i++){
                        $saida .= "\t".'public function get'.ucfirst($obj[$i]).'() {'."\n";
                        $saida .= "\t\t".'return $this->'.$obj[$i].';'."\n";
                        $saida .= "\t".'}'."\n";
                        $saida .= "\t".'public function set'.ucfirst($obj[$i]).'() {'."\n";
                        $saida .= "\t\t".'$this->'.$obj[$i].' = '."$".''.$obj[$i].';'."\n";
                        $saida .= "\t".'}';
                        $saida .= "\n\n";
                        
                    } 
                }
            }
            //echo "</pre>";

            $saida.="}";
        }else if($_POST['daodef']){
            echo 'aa';
        }
        
        
    }
    

?>

<!DOCTYPE html>
<html>
<title>Gerador de POJO & DAO</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>


    <div class="w3-half w3-padding">
        <form action="#" method="POST" class="w3-container w3-card-4 w3-light-grey">
            <h2>Criar um POJO</h2>
            <p>Lorem impsum dolor set amet.</p>

            <p>
                <label>Definição</label>
                <textarea name="definicao" class="w3-input w3-border w3-round" name="first" type="text" rows="10"></textarea>
            </p>    
            <p>
                <label>POJO</label><br>
                <?php
                    if(!$_POST){
                        echo '--';
                    }else{
                        echo '<pre>'.$saida.'</pre>';;
                    }
                
                ?>
                
            </p>
            <p>
                <button class="w3-btn w3-blue w3-round">Enviar</button>
            </p>
        </form>
    </div>
    <div class="w3-half w3-padding">
        <form action="#" method="POST" class="w3-container w3-card-4 w3-light-grey">
            <h2>Criar um DAO (em desenvolvimento)</h2>
            <p>Lorem impsum dolor set amet.</p>

            <p>
                <label>Definição</label>
                <textarea name="daodef" class="w3-input w3-border w3-round" name="first" type="text" rows="10"></textarea>
            </p>    
            <p>
                <label>DAO</label><br>
                <?php
                    if(!$_POST){
                        echo '--';
                    }else{
                        echo '<pre>'.$saida.'</pre>';;
                    }
                
                ?>
                
            </p>
            <p>
                <button class="w3-btn w3-blue w3-round">Enviar</button>
            </p>
        </form>
    </div>

  
</form>

</body>
</html>
