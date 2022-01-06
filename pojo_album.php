<?php

class PojoAlbum {

    private $id;
    private $nome;
    private $situacao;   
    

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getSituacao() {
        return $this->situacao;
    }
    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

  
  

}

?>