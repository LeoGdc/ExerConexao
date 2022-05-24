<?php
/******************************************
 * Objetivos Arquivo reponsavel pela manipulção de dados contaveis
 *  Obs (Este arquivo fara a ponte entre a view e a Model)
 * Autor:Leonardo
 * Data:24/05/2022
 * Versão: 1.5 
 * 
 *********************************************/
//import do arquivo configuração do projeto
 require_once(SRC.'modulo/config.php');
//fução para recerber dados da view e encaminhar parar o model (inserir)
function inserirContato ($dadosContato, $file){   
    $nomeFoto = (string) null;
    //validação para verificar se o objeto está vazio
    if (!empty($dadosContato)){
        //Validação de caixa vazia dos elementos nome celular e mail pois são obrigatoris no bd
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])&& !empty($dadosContato['sltEstado'])){

            //validação para identificar se chegou um arquivo para upload
            if($file['flefoto']['name'] != null){

                //import da função upload
                require_once('modulo/upload.php');
                //chama a função de upload
            
                $nomeFoto = uploadFile($file['flefoto']);

                if(is_array($nomeFoto)){

                    //caso aconteça algum erro no processo upload a função ira retornar um array com a possivel mensagem de erro.
                    // esse array sera retornado para a router e ela ira exibir a mensagem para o usuario
                    return $nomeFoto;
                }
            }
           
            
            /****
             * criação do array de dados sra emcaminhado a model
             * para inserir no BD é importante
             */
            $arrayDados = array (
                "nome"      => $dadosContato['txtNome'],
                "telefone"  => $dadosContato['txtTelefone'],
                "celular"   => $dadosContato['txtCelular'],
                "email"     => $dadosContato['txtEmail'],
                "obs"       => $dadosContato['txtObs'],
                "foto"      => $nomeFoto,
                "idestado"  => $dadosContato['sltEstado']
            );
            //import arquivo de modelagem para manipular o BD
            require_once('model/bd/contato.php');
            //chama a função que fara o insert no BD (está função está na model)
            if (insertContato($arrayDados))
            return true;
            else
            return array('idErro' => 1,
                        'message' => 'Não foi possivel inserir os dados no banco de dados');
            
        }
        //Função para receber dados de view e encaminhar paara a model (atualizar)
        else{
            return array('idErro' => 2,
            'message' => 'Existem campos obrigatorios que não foram preenchdidos');
        }
    }
    
}
//fução para recerber dados da view e encaminhar parar o model (atualizar)
function atualizarContato ($dadosContato, $arrayDados){

    $statusUpload = (boolean) null;
    //recebe o id enviado pelo arrayDados
    $id = $arrayDados['id'];

    //recebe foto enviada pelo arrayDados(nome da foto que ja existe no BD)
    $foto = $arrayDados['foto'];

    //receba o objeto de array referente a nova foto que podera ser enviada ao servidor
    $file = $arrayDados['file'];

     //validação para verificar se o objeto está vazio
     if (!empty($dadosContato)){
        //Validação de caixa vazia dos elementos nome celular e mail pois são obrigatoris no bd
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){
            //validação para garantir que o id seja valido
            if(!empty($id) && $id !=0 && is_numeric($id)){


                //validação para identificar se sera enviado ao servidor uma nova foto
                if($file['flefoto']['name'] != null){

                    //import da função upload
                    require_once('modulo/upload.php');
                    //chama a função de upload
                
                    //chama a função de upload para enviar a nova foto ao servidor
                    $novaFoto = uploadFile($file['flefoto']);
                    $statusUpload = true;
    
                }else{
                    //permanece a mesma foto no BD
                    $novaFoto = $foto;
                }
            /****
             * criação do array de dados sra emcaminhado a model
             * para inserir no BD, é importante criar este array conforme
             * as necessidade de manipulação do BD.
             * OBS: Criar as chaves do array conforme os nomes dos atributos
             * do BD
             */
            $arrayDados = array (
                "id"        => $id,
                "nome"      => $dadosContato['txtNome'],
                "telefone"  => $dadosContato['txtTelefone'],
                "celular"   => $dadosContato['txtCelular'],
                "email"     => $dadosContato['txtEmail'],
                "obs"       => $dadosContato['txtObs'],
                "foto"      => $novaFoto,
                "idestado"  => $dadosContato['sltestado']
            );
            //import arquivo de modelagem para manipular o BD
            require_once('model/bd/contato.php');
            //chama a função que fara o insert no BD (está função está na model)
                if (updateContato($arrayDados)){

                    //validação para vereficar se será necessario apagar a foto antiga 
                    //está variavel foi ativada em true na linha 105, quando realizamos
                    //o upload de uma nova foto para o servidor
                    if($statusUpload){
                        unlink(DIRETORIO_FILE_UPLOAD.$foto);
                    }
                    return true;
                }else{
                    return array('idErro' => 1,
                        'message' => 'Não foi possivel atualizar os dados no banco de dados');
                } 
            }else
            return array('idErro' => 4, 'message' => 'não é possivel atualizar um registro sem informar um id válido');
        }
        //Função para receber dados de view e encaminhar paara a model (atualizar)
        else{
            return array('idErro' => 2,
            'message' => 'Existem campos obrigatorios que não foram preenchdidos');
        }
    }
    
}
//fução para realizar a exclusão de um contato
function excluirContato ($arrayDados)
{
    //recebe o id do registro que será excluido
    $id = $arrayDados['id'];
    // recebe o nome da foto que sera excluida da parte do servidor
    $foto = $arrayDados['foto'];
    //validação para verificar se o id contem um numero valido
    if($id != 0 && !empty($id) && is_numeric($id)){
        //import do arquivo de contato
        require_once(SRC.'model/bd/contato.php');
        //chama a função da model e valida se o retorno foi verdadeiro ou falso
            if(deleteContato($id)){
                
                //Validação para caso a foto não exista com o registro
                    if($foto !=null){

               

                //unlink() - função para apagar um arquivo de um diretorio
                //permite apagar a foto fisicamente da pasta no servidor
                    if(@unlink(SRC.DIRETORIO_FILE_UPLOAD.$foto)){
                        return true;
                    }else{

                        return array('idErro' => 5, 'message' => 'O registro do banco de dados foi excluido com sucesso, porem a imagem não foi excluida ');
                    }
                }else
                    return true;
                
            }else 
            return array('idErro' => 3, 'message' => 'o banco de dados não pode excluir o registro.');
            
        }else{
            return array('idErro' => 4, 'message' => 'não é possivel excluir um registro sem informar um id válido');
        }
    }
//fução para  solicitar os dados da model e encaminhar a lista de contatos para a view
function listarContato (){
    //import do arquivo que vai buscar os dados no BD
    require_once(SRC.'model/bd/contato.php');
    //chama a função que vai buscar os dados no BD
    $dados = selectAllContatos();
    
    if(!empty($dados))
    return $dados;
    else
    return false;
}
//função para buscar contato atraves do id de registro
function buscarContato($id){
     //validação para verificar se o id contem um numero valido
     if($id != 0 && !empty($id) && is_numeric($id)){
         //import do arquivo de contato
        require_once(SRC.'model/bd/contato.php');

        //chama a função na model que vai buscar o BD
        $dados = selectByIdContato($id);

        //valida se existem dados para serem desvolvidos
        if(!empty($dados))
            return $dados;
        else
            return false;

     }else {
        return array('idErro' => 4, 'message' => 'não é possivel buscar um registro sem informar um id válido');
    }
}
?>