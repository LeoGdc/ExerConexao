<?php
/**************************************************************************
 * Objetivo: Arquivo de rota, prar segmentar as ações encaminhadas pela view
 *     (dados de um form,listagem de dados, ação de excluir ou atualizar).
 *     Esse arquivo sera respomsasvel por emcaminhar as solicitações para a Controller
 * 
 * Autor: Gean
 * Data:04/03/2022
 * Versão: 1.0
 * 
 *************************************************************************/
    $action =(string)null;
    $component =(string)null;
    //validação para verificar se as requisição pe um POST de um formulário
    if($_SERVER['REQUEST_METHOD']== 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'){
    
        $component = strtoupper($_GET['component']);
        $action = strtoupper ($_GET['action']);
        
        //estrutura condicional para validar quem esta solicitando algo para o router
        switch($component){
            case'CONTATOS':
                //import da controller contatos
                require_once('controller/controllerContatos.php');

                //validação para identificar tipo de ação que sera reaalizado
                if ($action == 'INSERIR') {
                    
                    $resposta =inserirContato($_POST);
                    if (is_bool($resposta)){
                        if($resposta)
                            echo("<script>
                            alert('Registro inserido com sucesso');
                            window.location.href = 'index.php'</script>");
                    }elseif(is_array($resposta))
                    echo("<script>
                            alert('".$resposta['message']."');
                            window.history.back();
                            </script>");
                
                }else if($action =='DELETAR'){
                    //recebe o id do registro q devera ser excluido, que foi enviado pela url no link da img do excluir que foi acionado na index
                    $idContato = $_GET['id'];

                    //chama a função de excluir na controller
                    $resposta = excluirContato($idContato);
                    
                    if(is_bool($resposta)){
                        if($resposta){
                            echo("<script>
                            alert('Registro excluido com sucesso!');
                            window.location.href = 'index.php'</script>");
                        }
                    }else if(is_array($resposta)){
                        echo("<script>
                        alert('".$resposta['message']."');
                        window.history.back();
                        </script>");
                    }
                }else if($action == 'BUSCAR'){
                     //recebe o id do registro q devera ser editado, que foi enviado pela url no link da img do editar que foi acionado na index
                    $idContato = $_GET['id'];

                    //chama a função de editar na controller
                    $resposta = excluirContato($idContato);
                    
                }
        break;
                
        }         
    }
     
    
?>