<?php

    /************
     *  os metodos de requisição para a API são
     * Get - para buscar dados
     * Post - para inserir umnobo dado
     * Delete - para apagar os dados
     * Put/patch - para editar um dado ja existentes
     *      O mais ultilizado é o PUT
     */

    //import do arquivo autoload, que fara as intancias do slim

use Slim\Http\Response;

require_once('vendor/autoload.php');

    //Criando um objeto do slim chamado app, para configurar os EndPoints
    $app = new \Slim\App();

  

    //endpoint: requisição para listar todos os contatos 
$app->get('/contatos', function($request, $response, $args){

        require_once('../modulo/config.php');
    //import da controler contatos que fara a busca de dados
        require_once('../controller/controllerContatos.php');

          //solicita os dados da controller
          if($dados = listarContato()){

            //verifica se houve algum tipo de erro no retorno dos dados na controller
            if(!isset($dados['idErro'])){
              //realiza a conversao do array de dados em formato JSON
                    if($dadosJSON = createJSON($dados))
                    {
                        //caso exista dados a serem retornados, informamos o statusCode200 e 
                        //enviamos um JSON com todos os dados encontrados
                    return $response ->withStatus(200)
                                        ->withHeader('Content-Type','application/json')
                                        ->write($dadosJSON);

                    }
            }else{
              //Converte para JSON  o erro, pois a controller retorna em array
              $dadosJSON= createJSON($dados);

              return $response ->withStatus(404)
                                ->withHeader('Content-Type','application/json')
                                ->write('{"message": "Dados inválidos",
                                          "Erro": '.$dadosJSON.'
                                        }');
            }
          }else{
              //retorna um statusCode que significa que a requisicao foi aceita, porem sem
              //conteudo de retoro
                return $response    ->withStatus(204);
                                  
          }
});
    //endpoint: requisição para listar todos os contatos com id
$app->get('/contatos/{id}', function($request, $response, $args){
       //recebe o id do registro que deverá ser retornado pela API
    //OBS: Esse id está chegando pela variável criada no endpoint
    $id = $args['id'];

    require_once('../modulo/config.php');
    require_once('../controller/controllerContatos.php');

    if($dados = buscarContato($id))
    {
        if($dadosJSON = createJSon($dados))
        return $response ->withStatus(200)
                            ->withHeader('Content-Type','application/json')
                            ->write($dadosJSON);
    }else{
        
         return $response    ->withStatus(204);
                           
    }
});
    //endpoint: requisição para deletar um contato
$app ->delete('/contatos/{id}', function($request, $response, $args){
    
    if(is_numeric($args['id'])){

        //recebe o ID enviado no EndPoint atraves da variavel ID
        $id = $args['id'];

        //import da controller de contatos , que fará a busca de dados
        require_once('../modulo/config.php');
        require_once('../controller/controllerContatos.php');

        //Busca o nome da foto para ser excluida na controller
        if($dados = buscarContato($id)){

            //recebe o nome da foto que a controller retornou
            $foto = $dados['foto'];

            //cria um array com o ID e o nome da foto a ser enviada para a controller excluir o registro
            $arrayDados = array (
                "id" => $id,
                "foto" => $foto
            );


            //chama a função de excluir o contato, encaminhando o array com o ID e a foto
            $resposta = excluirContato($arrayDados);
            if(is_bool($resposta) && $resposta == true){
                return $response
                    ->withStatus(200)
                    ->withHeader('Content-Type','application/json')
                    ->write('{"message": "registro excluido com sucesso"
                }');
            }elseif (is_array($resposta) && isset($resposta['idErro'])){
                if($resposta['idErro'] == 5){
                        return $response
                        ->withStatus(200)
                        ->withHeader('Content-Type','application/json')
                        ->write('{"message": "registro excluido com sucesso, porém houve um problema com a exclusão da foto "
                    }');
                }else{
                    $dadosJSON = createJSON($resposta);

                    return $response ->withStatus(404)
                        ->withHeader('Content-Type','application/json')
                        ->write('{"message": "houve um problema no processo de excluir.",
                            "Erro": '.$dadosJSON.'
                    }');
                }
            }
        }else{
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type','application/json')
                ->write('{"message": "O ID informado não existe na base de dados"
            }');
        }


    }else{
        return $response 
            ->withStatus(404)
            ->withHeader('Content-Type','application/json')
            ->write('{"message": "É obrigatorio informar um id com formato valido (número)"
            }');
    }

   
});
    //endpoint: requisição para inserir um novo contato 
$app->post('/contatos', function($request, $response, $args){

    //recebe do header da requisição qual será o content-type
    $contentTypeHeader = $request->getHeaderLine('Content-Type');

    //cria um array, pois dependendo do content-type temos mais informações separadas
    $contentType  = explode(";", $contentTypeHeader);

    // echo($contentType[0]);
    // die;
  
    switch ($contentType[0]) {
        case 'multipart/form-data':
            
            //recebe os dados comuns enviado pelo corpo da requisição
            $dadosBody = $request->getParsedBody();
            
            //recebe uma imagem enviada pelo corpo da requisição
            $uploadFiles = $request->getUploadedFiles();
           
            //cria um array com todos os dados que chegaram pela requisição, devido aos dados serem protegidos 
            //criamos um array e recuperamos os dados pelos metodos do objetos
            $arrayFoto = array(
                "name" => $uploadFiles['foto']->getClientFileName(),
                "type" => $uploadFiles['foto']->getClientMediaType(),
                "size" => $uploadFiles['foto']->getSize(),
                "tmp_name"=> $uploadFiles['foto']->file
            );

            //Cria uma chave chamada "foto" para colocar todos os dados do objeto, conforme é gerado em form HTML
            $file = array("foto" => $arrayFoto);

            //Cria um array com todos os dados comuns e do arquivo será enviado para o serviador
            $arrayDados = array(
                $dadosBody,
                "file" => $file
            );

            
            require_once('../modulo/config.php');
            require_once('../controller/controllerContatos.php');

            $resposta = inserirContato($arrayDados);

            if(is_bool($resposta) && $resposta == true){

                return $response ->withStatus(201)
                ->withHeader('Content-Type','application/json')
                ->write('{"message": "Registro inserido com sucesso"}');

            }elseif (is_array($resposta) && $resposta['idErro']){

                //Cria o Json dos dados do erro
                $dadosJSON = createJSON($resposta);

                return  $response ->withStatus(400)
                ->withHeader('Content-Type','application/json')
                ->write('{"message": "Houve um problema no processo de inserir.",
                "Erro": '.$dadosJSON.'}');
            }

        
        case 'application/json':

            $dadosBody = $request->getParsedBody();
            var_dump($dadosBody);
            die;

            return $response ->withStatus(200)
                            ->withHeader('Content-Type','application/json')
                            ->write('{"message": "formato selecionado foi json"}');
            break;

        default:
        return $response ->withStatus(404)
        ->withHeader('Content-Type','application/json')
        ->write('{"message": "Formato do Content-Type não é válido para esta requisição"}');
            break;
    }


});

$app->run();
?>