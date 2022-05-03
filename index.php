<?php
    //import do arquivo de configuracao do projeto
    require_once('modulo/config.php');
    //variavel criada para diferenciar no action do formulario qual ação deveria ser levada para a router (inserir ou editar).
    //nas condições abaixo, mudamos o action dessa variavel para a ação de editar.
    $form = (string)"router.php?component=contatos&action=inserir";
    //variavel para carregar o nome da foto do banco de dados
    $foto = (string) null;
    //valida se a utilização de variaveis de sessao esta ativa no servidor
    if(session_status()){
        //valida se a variavel de sessao dados contato não esta vazia
         if(!empty($_SESSION['dadosContato'])){
             $id        = $_SESSION['dadosContato']['id'];
             $nome      = $_SESSION['dadosContato']['nome'];
             $telefone  = $_SESSION['dadosContato']['telefone'];
             $celular   = $_SESSION['dadosContato']['celular'];
             $email     = $_SESSION['dadosContato']['email'];
             $obs       = $_SESSION['dadosContato']['obs'];
             $foto      = $_SESSION['dadosContato']['foto'];

             //mudamos a ação do form para editar o registro no click do bt "salvar"
             $form = "router.php?component=contatos&action=editar&id=".$id;

             //destroi uma variavel da memoria do server
             unset($_SESSION['dadosContato']);
         }
    }

?>
<!DOCTYPE>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title> Cadastro </title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/main.js" defer></script>


    </head>
    <body>
       
        <div id="cadastro"> 
            <div id="cadastroTitulo"> 
                <h1> Cadastro de Contatos </h1>
                
            </div>
            <div id="cadastroInformacoes">
                <!-- enctype="multipart/form-data essa opção é obrigatoria para enviar arquivos do formulario em htmlk para o servidor -->
                <form  action="<?=$form?>" name="frmCadastro" method="post" enctype="multipart/form-data">
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Nome: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="text" name="txtNome" value="<?=isset($nome)?$nome:null ?>" placeholder="Digite seu Nome" maxlength="100">
                        </div>
                    </div>
                                     
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Telefone: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="tel" name="txtTelefone" value="<?=isset($telefone)?$telefone:null?>">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Celular: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="tel" name="txtCelular" value="<?=isset($celular)?$celular:null?>">
                        </div>
                    </div>
                   
                    
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Email: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="email" name="txtEmail" value="<?=isset($email)?$email:null?>">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Escolha um Arquivo </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="file" name="flefoto" accept=".jpg, .png, .jpeg, .gif">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Observações: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <textarea name="txtObs" cols="50" rows="7"><?=isset($obs)?$obs:null?></textarea>
                        </div>
                    </div>
                    <div class="campos">
                        <img src="<?=DIRETORIO_FILE_UPLOAD.$foto?>" alt="">
                    </div>
                    <div class="enviar">
                        <div class="enviar">
                            <input type="submit" name="btnEnviar" value="Salvar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="consultaDeDados">
            <table id="tblConsulta" >
                <tr>
                    <td id="tblTitulo" colspan="6">
                        <h1> Consulta de Dados.</h1>
                    </td>
                </tr>
                <tr id="tblLinhas">
                    <td class="tblColunas destaque">Nome</td>
                    <td class="tblColunas destaque">Celular</td>
                    <td class="tblColunas destaque">Email</td>
                    <td class="tblcolunas destaque">foto</td>
                    <td class="tblColunas destaque">Opções</td> 
                </tr>
                <?php
                    require_once('controller/controllerContatos.php');
                    $listaContato = listarContato();
                    if($listaContato){

                    
                    foreach ($listaContato as $item)
                    {
                        $foto = $item['foto'];
                ?>
               
               
                <tr id="tblLinhas">
                    <td class="tblColunas registros"><?=$item['nome']?></td>
                    <td class="tblColunas registros"><?=$item['celular']?></td>
                    <td class="tblColunas registros"><?=$item['email']?></td>
                    <td class="tblColunas registros">
                        <img src="<?=DIRETORIO_FILE_UPLOAD.$foto?>" class="foto">
                    </td>
                   
                    <td class="tblColunas registros">
                        <a href="router.php?component=contatos&action=buscar&id=<?=$item['id']?>">
                            <img src="img/edit.png" alt="Editar" title="Editar" class="editar">
                        </a>
                        <a onclick="return confirm('Deseja realmente Excluir este item?');" href="router.php?component=contatos&action=deletar&id=<?=$item['id']?>& foto=<?=$foto?>">
                            <img src="img/trash.png" alt="Excluir" title="Excluir" class="excluir">
                        </a>
                            <img src="img/search.png" alt="Visualizar" title="Visualizar" class="pesquisar">
                    </td>
                </tr>
                 <?php
               }
            }
               ?>
            </table>
        </div>
    </body>
</html>