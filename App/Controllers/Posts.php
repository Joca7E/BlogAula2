<?php
class Posts extends Controller{
    private $postModel;
    public function __construct()
    {
        if(!Sessao::estaLogado()){
            Url::redirecionar('usuarios/logar');
        }//fim do if
        $this->postModel = $this->model('Post');
        
    }//fim do funão contrutor

    public function index(){
        $dados = [
            'posts' => $this->postModel->lerPosts()
        ];

        $this->view('posts/index', $dados);

    }//fim da função index
    public function cadastrar(){
        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        if(isset($formulario)):
            $dados = [
                'titulo' => trim($formulario['titulo']),
                'texto' => trim($formulario['texto']),
                'usuario_id' => $_SESSION['usuario_id'],
            ];

            if(in_array("",$formulario)):
                if(empty($formulario['titulo'])):
                    $dados['titulo_erro'] = "Preencha o campo titulo";
                endif;
                if(empty($formulario['texto'])):
                    $dados['texto_erro'] = "Preencha o campo texto";
                endif;
            else:
                if($this->postModel->armazenar($dados)):
                    Sessao::mensagem('post', 'Post cadastrado com sucesso');
                    Url::redirecionar('posts');
                else:
                    die("Erro ao armazenar post no banco de dados");
                endif;
            endif;
        else:
            $dados = [
                'titulo' => '',
                'texto' => '',
                'titulo_erro' => '',
                'texto_erro' => ''
            ];
        endif;
        $this->view('posts/cadastrar');
    }//fim da função index
}//fim da classe Posts

?>