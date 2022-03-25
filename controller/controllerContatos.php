<?php
/******************************************
 * Objetivos Arquivo reponsavel pela manipulção de dados contaveis
 *  Obs (Este arquivo fara a ponte entre a view e a Model)
 * Autor:Gean
 * Data:04/03/2022
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
 function atualizarContato ()
{

}
//fução para realizar a exclusão de um contato
 function excluirContato ()
{
    
}
//fução para  solicitar os dados da model e encaminhar a lista de contatos para a view
 function listarContato ()
{
    //import do arquivo que vai buscar os dados no BD
    require_once('model/bd/contato.php');
    //chama a função que vai buscar os dados no BD
    $dados = selectAllContatos();
    
    if(!empty($dados))
    return $dados;
    else
    return false;
}




?>