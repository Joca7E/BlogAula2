<div class="container py-5">
    <?= Sessao::mensagem('post'); ?>
    <div class="card">
        <div class="card-header bg-info text-white">
            Postagens
            <div class="float-right">
                <a href="<?=URL?>/posts/cadastrar" class="btn btn-light">Escrever</a>
            </div>
        </div>
        <div class="card-body">
            <?php foreach ($dados['posts'] as $post): ?>
                <h1><?= $post->titulo ?></h1>
                 <p><?= $post->texto  ?></p>
            <?php endforeach ?>
        </div>
    </div>
</div>