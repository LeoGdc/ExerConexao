<?php
/**************************************************************************
 * Objetivo: Arquivo responsavel em realizar oploads de arquivos
 * Autor: Leonardo
 * Data:25/04/2022
 * Versão: 1.0
 * 
 *************************************************************************/

 //funcão para realizar uploads de imagens
 function uploadFile($arrayFile){

    require_once('modulo/config.php');

    $arquivo = $arrayFile;
    $sizeFile = (int) 0;
    $typeFile = (string) null;
    $nameType = (string) null;
    $tempFile = (string) null;

    //validação para identificar se existe arquivo válido (Maior que 0 e que tenha uma extensão)
    if($arquivo['size'] > 0 && $arquivo['type'] != ""){
        //recupera o tamanho do arquivo que é bytes e converte em kb ( /1024)
        $sizeFile = $arquivo['size']/1024;
        //recupera o tipo do arquivo
        $typeFile = $arquivo['type'];
        //recupera o nome do arquivo
        $nameFile = $arquivo['name'];

        //recupera o caminho do diretorio temporario que esta o arquivo
        $tempFile = $arquivo['tmp_name'];

        //validação para permitir o upload apenas de arquivos de no maximo 5mb
        if($sizeFile <= MAX_FILE_UPLOAD){
            //validação para permitir somente as extenções validas
            if(in_array($typeFile, EXT_FILE_UPLOAD)){
                //separa somente o nome do arquivo sem sua extenção
                $nome = pathinfo($nameFile, PATHINFO_FILENAME);

                //separa somente a extensão do arquivo sem o nome
                $extensao =pathinfo($nameFile, PATHINFO_EXTENSION);

                //Existem diversos algoritmos para criptografia de dados
                    //md5()
                    //sha1()
                    //hash()

                //md5(gerando uma criptografia de dados)
                //uniquid gerando um a sequencia numerica diferente tendo como base, configuração da maquina   
                //time() pega a hora:minuto: segundo que esta sendo feito upload da foto
                $nomeCripty = md5($nome.uniqid(time()));

                //voltamos novamente o nome do arquivo com a extensão
                $foto = $nomeCripty.".".$extensao;

                //Envia o arquivo da pasta temporaria do apache para a pasta criada no projeto
                if(move_uploaded_file($tempFile, DIRETORIO_FILE_UPLOAD.$foto)){
                    return $foto;
                }else{
                    return array('idErro' => 13,
                    'message' => 'não foi possivel mover o arquivo para o servidor');
                }

            }else{
                return array('idErro' => 12,
                'message' => 'A extenção do arquivo selecionada não é permitida.');
            }
        }else{
            return array('idErro' => 10,
                'message' => 'Tamanho de arqvuivo ínvalido no upload.');
        }
    }else{
        return array('idErro' => 11,
                'message' => 'não é possivel realizar o upload sem um arquivo selecionado.');

    }
}

?>