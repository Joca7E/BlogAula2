<?php

class Usuarios extends Controller
{
    public function __construct()
    {
        $this->$usuarioModel = $this->model('Usuario');
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
                    echo 'Cadastro realizado com sucesso<hr>';
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
            ];

        endif;


        $this->view('usuarios/cadastrar', $dados);
    }
}
