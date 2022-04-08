<?php
/******************************************
 * Objetivos Arquivo reponsavel pela manipulção de dados contaveis
 *  Obs (Este arquivo fara a ponte entre a view e a Model)
 * Autor:Leonardo
 * Data:08/04/2022
 * Versão: 1.0 
 * 
 *********************************************/
//fução para recerber dados da view e encaminhar parar o model (inserir)
 function inserirContato ($dadosContato){   
    //validação para verificar se o objeto está vazio
    if (!empty($dadosContato)){
        //Validação de caixa vazia dos elementos nome celular e mail pois são obrigatoris no bd
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){
            
            /****
             * criação do array de dados sra emcaminhado a model
             * para inserir no BD é importante
             */
            $arrayDados = array (
                "nome" => $dadosContato['txtNome'],
                "telefone" => $dadosContato['txtTelefone'],
                "celular" => $dadosContato['txtCelular'],
                "email" => $dadosContato['txtEmail'],
                "obs" => $dadosContato['txtObs']
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
 function atualizarContato ($dadosContato, $id){
     //validação para verificar se o objeto está vazio
     if (!empty($dadosContato)){
        //Validação de caixa vazia dos elementos nome celular e mail pois são obrigatoris no bd
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){
            //validação para garantir que o id seja valido
            if(!empty($id) && $id !=0 && is_numeric($id)){

            
            /****
             * criação do array de dados sra emcaminhado a model
             * para inserir no BD é importante
             */
            $arrayDados = array (
                "id"        => $id,
                "nome"      => $dadosContato['txtNome'],
                "telefone"  => $dadosContato['txtTelefone'],
                "celular"   => $dadosContato['txtCelular'],
                "email"     => $dadosContato['txtEmail'],
                "obs"       => $dadosContato['txtObs']
            );
            //import arquivo de modelagem para manipular o BD
            require_once('model/bd/contato.php');
            //chama a função que fara o insert no BD (está função está na model)
            if (updateContato($arrayDados))
            return true;
            else
            return array('idErro' => 1,
                        'message' => 'Não foi possivel atualizar os dados no banco de dados');
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
 function excluirContato ($id)
{
    //validação para verificar se o id contem um numero valido
    if($id != 0 && !empty($id) && is_numeric($id)){
        //import do arquivo de contato
        require_once('model/bd/contato.php');
        //chama a função da model e valida se o retorno foi verdadeiro ou falso
            if(deleteContato($id))
                return true;
            else 
            return array('idErro' => 3, 'message' => 'o banco de dados não pode excluir o registro.');
        }else{
            return array('idErro' => 4, 'message' => 'não é possivel excluir um registro sem informar um id válido');
        }
    }
//fução para  solicitar os dados da model e encaminhar a lista de contatos para a view
 function listarContato (){
    //import do arquivo que vai buscar os dados no BD
    require_once('model/bd/contato.php');
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
        require_once('model/bd/contato.php');

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