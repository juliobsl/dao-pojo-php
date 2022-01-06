var app = angular.module('myIndex', []);

app.directive('ngFile', ['$parse', function ($parse) {
    return {
     restrict: 'A',
     link: function(scope, element, attrs) {
      element.bind('change', function(){
  
        scope.bttUpFoto = false;
        scope.bttSalvar = true;
      $parse(attrs.ngFile).assign(scope,element[0].files)
       scope.$apply();
      });
     }
    };
   }]);

app.controller('indexCtrl', function ($scope, $http, $interval, $timeout, $window,$filter) {

    $scope.id = $scope.tituloEdit = $scope.resumoEdit= $scope.urlHtmlEdit = $scope.imagem = '';

    $scope.bttSalvarEdit = $scope.bttUpFotoEdit = $scope.divImg = false;

    $scope.bttSalvar = $scope.divSucess = $scope.divError = $scope.divEditSucess = $scope.divEditError = $scope.divImgEdit = true;    

    $scope.nomeImagem =  $scope.nomeImagemEdit = $scope.msgSucess = $scope.msgError = $scope.msgEditSucess = $scope.msgEditError = '';

    $scope.dadosEdit='';
    // $scope.uploadfiles = [];

    $scope.bttUpFoto = false;   

    $scope.divRmvSucess = true;
    $scope.msgRmvSucess = true;
    $scope.divRmvError = true;
    $scope.msgRmvError = true;
    $scope.albunsLoad = '';


    $scope.loadAlbuns = function () {
        openLoadToast('','','Carregando álbuns, aguarde...',false);
     
        $scope.url = 'php/albuns/loadAlbuns.php';

        $http({
            method: 'GET',
            url: $scope.url
        }).
            then(function (response) {
                //console.log(response.data);
                let status = response.status;
                if(response.data!=0){                  
                    $scope.albunsLoad = response.data;
                }
                    
                if($scope.albunsLoad.length>0){
                    document.getElementById('tr_load').style.display = 'none';
                }else{
                    document.getElementById('text_tr_load').innerHTML = 'Nenhum álbum a ser carregado.';
                }
                closeLoadToast();
                getParamsUrl();    
            }, function (response) {

                albunsList.albuns = response.data || 'Request failed';
                let status = response.status;
            });
 
    }

    var getParamsUrl = function() {

         var url = new URL(window.location.href);
   
        var r = url.searchParams.get("r");
        var e = url.searchParams.get("e");
        var abrir = url.searchParams.get("abrir");
        
        if(r==1){
            alert('Ops! Erro ao inserir as informações no banco de dados, tente novamente ou contate o suporte.');
        }
        if(e==1){       
            alert('Ops! Erro ao atualizar as informações no banco de dados, tente novamente ou contate o suporte.');        
        }

        if(abrir!=null){
            openLoad('Abrindo o álbum.');   
            setTimeout(function() {
                //console.log(document.getElementById('btn_editar_'+abrir));
                document.getElementById('btn_editar_'+abrir).click();
                closeLoad();
            },2000);
            
            // //console.log($scope.albunsLoad);
            // let album = $scope.albunsLoad.filter(element => element.id==abrir)[0];
           
            // console.log(album);
            // $scope.modalEditar2(album);
        }
        
        if(r!=null || e!=null || abrir!=null){
            window.history.pushState({}, document.title, window.location.pathname);
        }
       
    }

    
    $scope.closeError = function (){
        $scope.divError = true; 
    }  

    $scope.modalEditar2 = function(dados){

        //console.log(dados);


        $scope.dadosEdit = dados;
    

        $('#bs-example-modal-lg').modal('show');     

     
      
    }

    

    $scope.rmvAlbum = function(album){
          
      //console.log("aqui: "+ $scope.tituloEdit + '\n' + $scope.resumoEdit );
        var r = confirm("Você deseja realizar a exclusão da atração #"+album.id+"? Essa ação não pode ser desfeita.");
        if (!r == true) {
          return;
        } 
            
            $http({
                method: 'post',
                url: 'php/albuns/rmvAlbum.php',
                data: {
                    id: album.id,              
                    nome: album.nome,              
                },
                headers: {'Content-Type': undefined},
                }).then(function successCallback(response) { 
                // Store response data
                console.log(response.data);
                    if(response.data == 0){

                        $scope.divRmvSucess = false; 
                        $scope.msgRmvSucess = 'Sucesso! O álbum foi excluido com êxito.';                      
                        $timeout(function () {
                            $scope.divRmvSucess = true;                             
                        }, 2000);   
                          
                        $scope.loadAlbuns();
                    }else if(response.data == 1){                                    
                        $scope.divRmvError = false;
                        $scope.msgRmvError = 'Ops! Erro ao atualizar as informações no banco de dados, tente novamente ou contate o suporte.';
                        $timeout(function () {
                            $scope.divRmvSucess = true;                             
                        }, 5500);
                    }
                // alert(JSON.stringify($scope.response));
                });
   
    }

    $scope.rmvFoto = function(idfoto,pos,idalbum){
        pos = parseInt(pos);
         
        //console.log("aqui: "+ $scope.tituloEdit + '\n' + $scope.resumoEdit );
        var r = confirm("Você deseja realizar a exclusão da foto #"+idfoto+"? Essa ação não pode ser desfeita.");
        if (!r == true) {
          return;
        } 
            
            $http({
                method: 'post',
                url: 'php/albuns/rmvFoto.php',
                data: {
                    id: idfoto,            
                    idalbum: idalbum            
                },
                headers: {'Content-Type': undefined},
                }).then(function successCallback(response) { 
                // Store response data
                console.log(response.data);
                    if(response.data == 0){


                        $scope.divRmvSucess = false; 
                        $scope.msgRmvSucess = 'Sucesso! A foto foi excluida com êxito.';                      
                        $timeout(function () {
                            $scope.divEditSucess = true;                             
                        }, 2000);   
                        
                        $scope.dadosEdit.fotos.splice(pos,1);
                        
                    }else if(response.data == 1){                                    
                        $scope.divEditError = false;
                        $scope.msgEditError = 'Ops! Erro ao atualizar as informações no banco de dados, tente novamente ou contate o suporte.';
                        $timeout(function () {
                            $scope.divEditError = true;                             
                        }, 5500);
                    }
                // alert(JSON.stringify($scope.response));
                });
   
    }

    $scope.salvarTituloImg = function(idfoto,pos,idalbum,nomealbum){
           
            // if($scope.dadosEdit.fotos[pos].titulo==''){
            //     $scope.divEditError = false;
            //     $scope.msgEditError = 'Informe um título adequado.';
            //     $timeout(function () {
            //         $scope.divEditSucess = true;                             
            //     }, 3000);
            //     return;
            // } 
            //console.log("aqui: "+ $scope.tituloEdit + '\n' + $scope.resumoEdit );            
            $http({
                method: 'post',
                url: 'php/albuns/editFotoTitulo.php',
                data: {
                    id: idfoto,            
                    idalbum: idalbum,          
                    titulo: $scope.dadosEdit.fotos[pos].titulo                
                },
                headers: {'Content-Type': undefined},
                }).then(function successCallback(response) { 
                // Store response data
                console.log(response.data);
                    if(response.data == 0){


                        $scope.divEditSucess = false; 
                        $scope.msgEditSucess = 'Sucesso! O título da foto foi atualizado com êxito.';                      
                        $timeout(function () {
                            $scope.divEditSucess = true;                             
                        }, 2000);   
                        
                        $scope.toogleDivTitulo(idfoto);
                        
                    }else if(response.data == 1){                                    
                        $scope.divEditError = false;
                        $scope.msgEditError = 'Ops! Erro ao atualizar as informações no banco de dados, tente novamente ou contate o suporte.';
                        $timeout(function () {
                            $scope.divEditSucess = true;                             
                        }, 5500);
                    }
                // alert(JSON.stringify($scope.response));
                });
   
    }   

    $scope.editFile = function(){
        
        var fd=new FormData();
        angular.forEach($scope.uploadfilesedit,function(file){            
            console.log(file);
            fd.append('file',file);
        });
        console.log(fd);

        $scope.nomeImagemEdit = $scope.uploadfilesedit[0].name;
    
        $http({
        method: 'post',
        url: 'php/albuns/upload.php',
        data: fd,
        headers: {'Content-Type': undefined},
        }).then(function successCallback(response) { 
        // Store response data
        
            if(response.data == 0){
                $scope.bttUpFotoEdit = true;
                $scope.bttSalvarEdit = false;
    
                $scope.divEditSucess = false;
                $scope.msgEditSucess = 'Sucesso! Foto enviada para o servidor';
                $timeout(function () {
                    $scope.divEditSucess = true; 
                    }, 3000);   
                    
            }else if(response.data == 1){
    
                $scope.bttUpFotoEdit = false;
                $scope.bttSalvarEdit = true;
    
                $scope.divEditError = false;
                $scope.msgEditError= 'Erro! Não foi possível salvar imagem no servidor, tente novamente ou contate o suporte.';
                $timeout(function () {
                    $scope.divEditError = true; 
                    }, 5000);   
            
            }else if(response.data == 2){
    
                $scope.bttUpFotoEdit = false;
                $scope.bttSalvarEdit = true;
    
                $scope.divEditError = false;
                $scope.msgEditError= 'Erro! Nenhuma imagem foi selecionada, adicione uma imagem e tente novamente ou contate o suporte.';
                $timeout(function () {
                    $scope.divEditError = true; 
                    }, 5000);
            }
        // alert(JSON.stringify(response.data));
        });
        }

    $scope.trocarFoto = function(){
    
        $scope.nomeImagemEdit = '';
        $("input[type='file']").val('');
        $scope.bttUpFotoEdit = false;
        $scope.bttSalvarEdit = true;
        $scope.divImgEdit = false;
        $scope.divImg = true;

        $scope.editFile();

    }

    $scope.toogleDivTitulo = function(imgid){
        let div = document.getElementById('divtituloimg'+imgid);
        if(div.style.display=="none"){
            div.style.display="block";
        }else{
            div.style.display="none";
        }

    }

    
});

   