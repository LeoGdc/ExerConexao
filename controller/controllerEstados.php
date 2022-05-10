<?php 
/******************************************
 * Objetivos Arquivo reponsavel pela manipulção de dados de estados
 *  Obs (Este arquivo fara a ponte entre a view e a Model)
 * Autor:Leonardo
 * Data:10/05/2022
 * Versão: 1.0 
 * 
 *********************************************/

    //import do arquivo configuração do projeto
 require_once('modulo/config.php');

 function listarEstado (){
    //import do arquivo que vai buscar os dados no BD
    require_once('model/bd/estado.php');
    //chama a função que vai buscar os dados no BD
    $dados = selectAllEstados();
    
    if(!empty($dados))
    return $dados;
    else
    return false;
}
?>