<?php

    //import do arquivo autoload, que fara as intancias do slim
    require_once('vendor/autoload.php');

    //Criando um objeto do slim chamado app, para configurar os EndPoints
    $app = new \Slim\App();

    //endpoint: requisição para listar todos os contatos 
    $app->get('/contatos', function($request, $response, $args){

    });
    //endpoint: requisição para listar todos os contatos com id
    $app->get('/contatos/{id}', function($request, $response, $args){

    });
    //endpoint: requisição para listar todos os contatos 
    $app->get('/contatos', function($request, $response, $args){

    });
?>