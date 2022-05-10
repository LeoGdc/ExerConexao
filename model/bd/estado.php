<?php
/**************************************************************************
 * Objetivo: responsavel de manipular os dados dentro   BD 
 *              (select)
 * 
 * Autor: Leonardo
 * Data:10/05/2022
 * Versão: 1.0
 * 
 *************************************************************************/

//import
require_once('conexaoMySql.php');

function selectAllEstados(){
    //Abre as conexão com o BD
    $conexao = conexaoMysql();
    //Script para listar todos os dados no BD
    $sql = "select * from tblestados order by nome asc";
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
                    "idestado"        => $rsDados['idestado'],
                    "nome"      => $rsDados['nome'],
                    "sigla"     => $rsDados['sigla']
                );
            $cont++;
         }
            //solicita o fechamento da conexão com o BD
            fecharConexaoMySql($conexao);

            return $arryDados;
    }
}
?>