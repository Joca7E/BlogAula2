<?php

class Usuarios extends Controller
{
    private $usuarioModel;
    
    public function __construct()
    {
        $this->usuarioModel = $this->model('Usuario');
    }

    public function cadastrar()
    {

        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($formulario)) :
            $dados = [
                'nome' => trim($formulario['nome']),
                'email' => trim($formulario['email']),
                'senha' => trim($formulario['senha']),
                'confirma_senha' => trim($formulario['confirma_senha']),
            ];

            if (in_array("", $formulario)) :

                if (empty($formulario['nome'])) :
                    $dados['nome_erro'] = 'Preencha o campo nome';
                endif;

                if (empty($formulario['email'])) :
                    $dados['email_erro'] = 'Preencha o campo e-mail';
                endif;

                if (empty($formulario['senha'])) :
                    $dados['senha_erro'] = 'Preencha o campo senha';
                endif;

                if (empty($formulario['confirma_senha'])) :
                    $dados['confirma_senha_erro'] = 'Confirme a Senha';
                endif;
            else :
                if(Checa::checarNome($formulario['nome'])):
                    $dados['nome_erro'] = 'O nome informado é inválido.';
                    elseif(Checa::checarEmail($formulario['email'])):
                        $dados['email_erro'] = 'O e-mail informado é inválido.';
                        
                    elseif (strlen($formulario['senha']) < 6) :
                    $dados['senha_erro'] = 'A senha deve ter no minimo 6 caracteres';
                    elseif ($formulario['senha'] != $formulario['confirma_senha']) :
                    $dados['confirma_senha_erro'] = 'As senhas são diferentes';
                else:
                    $dados['senha'] = password_hash($formulario['senha'], PASSWORD_DEFAULT);

                    if($this->usuarioModel->armazenar($dados)):
                   Sessao::mensagem('usuario', 'Cadastro realizado com sucesso');
                   Url::redirecionar('usuarios/logar');
                    else:
                        die("Erro ao armazenar o usuário no banco de dados");
                    endif;
                endif;
            endif;
            /*
            echo 'Senha Original: '.$formulario['senha']."<hr>";
            echo 'Senha MD5: '.md5($formulario['senha'])."<hr>";
            echo '<hr>';
            $senha_segura = password_hash($formulario['senha'], PASSWORD_DEFAULT);
            echo 'Senha hash: '.$senha_segura.'<hr>';
            */
            var_dump($formulario);
        else :
            $dados = [
                'nome' => '',
                'email' => '',
                'senha' => '',
                'confirma_senha' => '',
                'nome_erro' => '',
                'email_erro' => '',
                'senha_erro' => '',
                'confirma_senha_erro' => '',
            ];

        endif;


        $this->view('usuarios/cadastrar', $dados);
    }//fim da função cadastrar
    
    public function logar()
    {

        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($formulario)) :
            $dados = [
                'email' => trim($formulario['email']),
                'senha' => trim($formulario['senha']),  
            ];

            if (in_array("", $formulario)) :
                if (empty($formulario['email'])) :
                    $dados['email_erro'] = 'Preencha o campo e-mail';
                endif;

                if (empty($formulario['senha'])) :
                    $dados['senha_erro'] = 'Preencha o campo senha';
                endif;
            else :
                    if(Checa::checarEmail($formulario['email'])):
                        $dados['email_erro'] = 'O e-mail informado é inválido.';
                else:
                    $usuario = $this->usuarioModel->checarLogin($formulario['email'], $formulario['senha']);
                    if($usuario):
                     $this->criarSessaoUsuario($usuario);
                    else:
                        Sessao::mensagem('usuario','Usuario ou senha inválidos', 'alert alert-danger');
                    endif;
                endif;
            endif;
            var_dump($formulario);
        else :
            $dados = [
                'email' => '',
                'senha' => '',
                'email_erro' => '',
                'senha_erro' => '',
            ];

        endif;


        $this->view('usuarios/logar', $dados);
    }
    private function criarSessaoUsuario($usuario){
        $_SESSION['usuario_id'] = $usuario->id;
        $_SESSION['usuario_nome'] = $usuario->nome;
        $_SESSION['usuario_email'] = $usuario->email;

        URL::redirecionar('paginas/home');
    }// fim da função criarSessaoUsuario

    public function sair(){
        unset($_SESSION['usuario_id']);
        unset($_SESSION['usuario_nome']);
        unset($_SESSION['usuario_email']);

        session_destroy();
       URL::redirecionar('usuarios/logar');
    }//fim da função sair
}
