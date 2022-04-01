<?php
/**************************************************************************
 * Objetivo: responsavel de manipular os dados dentro   BD 
 *              (insert,update,select e delete)
 * 
 * Autor: Gean
 * Data:11/03/2022
 * Versão: 1.0
 * 
 *************************************************************************/
//impor
require_once('conexaoMySql.php');
 //função para realizar o insert no BD
function insertContato($dadosContato){
    //abre a conexão com o BD
    $conexao = conexaoMysql();
    $sql = "insert into tblcontatos
        (nome,
        telefone,
        celular,
        email,
        obs)
    values
    ('".$dadosContato["nome"]."',
    '".$dadosContato["telefone"]."',
    '".$dadosContato["celular"]."',
    '".$dadosContato["email"]."',
    '".$dadosContato["obs"]."');";
    //executa o script no BD
        //Validação para verificar  se o script sql esta correto
    if(mysqli_query($conexao, $sql))
    {
        //validação para ver se al inha for gravada no bd 
        if(mysqli_affected_rows($conexao))
        {
            fecharConexaoMySql($conexao);
            $statusRespota = true;
            }
            else{
                fecharConexaoMySql($conexao);
                $statusRespota = false;
            }
        }else{
            fecharConexaoMySql($conexao);
            $statusRespota = false;
        }

        fecharConexaoMySql($conexao);
        return $statusRespota;
        
    }

//função para realizar update no BD
function updateContato(){

}
//função para realizar delete no BD
function deleteContato($id){

    //abre a conexão com o BD
    $conexao = conexaoMysql();
    
    //Script para deletar um resgistro no bd 
    $sql = "delete from tblcontatos where idcontato=".$id;

    //valida se o script esta correto, sem erro de sixtaxe e executa o BD
    if(mysqli_query($conexao, $sql)){

        //valida se o BD teve sucesso na execução do script
        if(mysqli_affected_rows($conexao))
        $statusRespota =true;
    }

    //fecha a conexão com o BD mySql
    fecharConexaoMySql($conexao);

    return $statusRespota;
  

}
//função para listar todos os contatos do BD
function selectAllContatos(){
    //Abre as conexão com o BD
    $conexao = conexaoMysql();
    //Script para listar todos os dados no BD
    $sql = "select * from tblcontatos order by idcontato desc";
    //Executa o script sql no BD e guarda o retorno dos dados, se houver 
    $result = mysqli_query($conexao, $sql);
    //valida se o BD retornou os registro
    if($result){
        //mysqli_fetch_assoc() - permite converter os dados do BD em array de manipulação no PHP
        //Nesta, repetição estamos, convertendo os dados do BD em um Array ($rsDados) , além de
        // o proprio while conseguir gerenciar a quantidade de vezes que deverá ser feita a repetição
        $cont = 0;
        while($rsDados = mysqli_fetch_assoc($result)){
            //Criar um array com os dados BD
                $arryDados{$cont} = array(
                    "id" => $rsDados['idcontato'],
                    "nome" => $rsDados['nome'],
                    "telefone" => $rsDados['telefone'],
                    "celular" => $rsDados['celular'],
                    "email" => $rsDados['email'],
                    "obs" => $rsDados['obs']  
                );
            $cont++;
         }
            //solicita o fechamento da conexão com o BD
            fecharConexaoMySql($conexao);

            return $arryDados;
    }
}
//função para buscar um contato no BD atraves do id do registro
function selectByIdContato($id){
    
     //Abre as conexão com o BD
     $conexao = conexaoMysql();
     //Script para listar todos os dados no BD
     $sql = "select * from tblcontatos where idcontato =".$id;
     //Executa o script sql no BD e guarda o retorno dos dados, se houver 
     $result = mysqli_query($conexao, $sql);
     //valida se o BD retornou os registro
     if($result){
         //mysqli_fetch_assoc() - permite converter os dados do BD em array de manipulação no PHP
         //Nesta, repetição estamos, convertendo os dados do BD em um Array ($rsDados) , além de
         // o proprio while conseguir gerenciar a quantidade de vezes que deverá ser feita a repetição
         
         if($rsDados = mysqli_fetch_assoc($result)){
             //Criar um array com os dados BD
                 $arrayDados = array(
                     "id" => $rsDados['idcontato'],
                     "nome" => $rsDados['nome'],
                     "telefone" => $rsDados['telefone'],
                     "celular" => $rsDados['celular'],
                     "email" => $rsDados['email'],
                     "obs" => $rsDados['obs']  
                 );
          }
             //solicita o fechamento da conexão com o BD
             fecharConexaoMySql($conexao);
 
             return $arrayDados;
     }
}
?>