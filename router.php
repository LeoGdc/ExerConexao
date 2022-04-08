<?php
/**************************************************************************
 * Objetivo: Arquivo de rota, prar segmentar as ações encaminhadas pela view
 *     (dados de um form,listagem de dados, ação de excluir ou atualizar).
 *     Esse arquivo sera respomsasvel por emcaminhar as solicitações para a Controller
 * 
 * Autor: Leonardo
 * Data:08/04/2022
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
                    $dados = buscarContato($idContato);
                    
                    //ativa a utilização de variaveis de sessão no servidor
                    session_start();

                    //Guarda em uma variavel de sessão os dados que o BD retornou para a busca do id
                        //Obs(essa variavel de sessão sera ultilizada na index.php, para colocar os dados nas caixas de texto)
                    $_SESSION['dadosContato'] = $dados;
                    
                    //Utilizando o header tambem podemos chamar a index.php, porem havera uma ação de carregamento no navegador(piscando a tela novamento)
                    //header('location: index.php');

                    //Utilizando o require iremos apenas importar a tela da index, assim não havera um novo carregamento da pagina
                    require_once('index.php');
                }else if($action == 'EDITAR'){

                    //recebe o id que foi encaminhado no action pela URL

                    $idContato=$_GET['id'];

                    //chama a função editar na controller

                    $resposta =atualizarContato($_POST, $idContato);

                    //valida se os dados da controller retornou

                    if (is_bool($resposta)){

                        //verificar se o retorno foi verdadeiro
                        if($resposta)
                            echo("<script>
                            alert('Registro Atualizado com sucesso');
                            window.location.href = 'index.php'</script>");
                    }elseif(is_array($resposta))
                    echo("<script>
                            alert('".$resposta['message']."');
                            window.history.back();
                            </script>");
                }       
        break;
                
        }         
    }
     
    
?>