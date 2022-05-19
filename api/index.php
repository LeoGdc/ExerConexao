<?php 
    /**********************************************************************************************************
     * objetivo: Arquivo principal da Api que irá receber a url requisitada e redirecionar para as APIs(router)
     * data:19/05/2022
     * Autor: Leonardo N.Vivi
     * versão 1.0
     *********************************************************************************************************/


    //Permite ativar quais endereços de sites que poderam fazer requisições na API (* libera para todos os sites)
    header('Access-Control-Allow-Origin: *');
    //Permite ativar os metodos do protocolo HTTP que irão requisitar a API
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    //Permite ativar o content-type das requisições(formato de dados que será utilizado(JSON, XML, FORM/DATA, etc...))
    header('Access-Control-Allow-Header: Content-Type');
    //Permite liberar quais content-type serão utilizados na API
    header('Content-Type: application/json');

    //recebe a URL digitada na requisição
    $urlHTTP = (string) $_GET['url'];
    
    //Converte a url requisitada em um array para dividir as opções de busca, que é separada pela "/"
    $url = explode('/', $urlHTTP);

    //verifica qual api sera encaminhada a requisição (contatos, estado, etc...)
    switch (strtoupper($url[0])) {
        case 'CONTATOS':
            require_once('contatosAPI/index.php');
            break;
        case'ESTADOS':
            require_once('estadosAPI/index.php');
            break;
    }
?>