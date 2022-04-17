<?=$render('header', ['loggedUser'=>$loggedUser]);?>
<section class="container main">
    <?=$render('sidebar', ['activeMenu' => 'config']);?>
    <section class="feed mt-10">
        <div class="row">
            <div class="column pr-5">
                <h1>Configurações</h1><br/><br/>
                <label>Novo Avatar</label>
                <input type="file" name="avatar" /><br/>
                <label>Nova Capa</label>
                <input type="file" name="cover" /><br/><hr/>
                <form class="form-config" method="POST" action="<?=$base;?>/config">
                    <?php if(!empty($flash)): ?>
                        <div class="flash"><?php echo $flash; ?></div>
                    <?php endif; ?>
                    <br/>
                    <input placeholder="<?=$loggedUser->id?>" type="hidden" name="id" />
                    <br/>
                    <label>Nome Completo:</label><br/>
                    <input class="campo" placeholder="<?=$loggedUser->name?>" type="text" name="name" /><br/><br/>
                    <label>Data de Nascimento:</label><br/>
                    <input class="campo" id="birthdate" placeholder="<?=date('d/m/Y', strtotime($loggedUser->birthdate));?>" type="text" name="birthdate" /><br/><br/>
                    <label>E-mail:</label><br/>
                    <input class="campo" placeholder="<?=$loggedUser->email?>" type="email" name="email" /><br/><br/>
                    <label>Cidade:</label><br/>
                    <?php if(($loggedUser->city) != ""): ?>
                        <input class="campo" placeholder="<?=$loggedUser->city?>" type="text" name="city" /><br/><br/>
                    <?php else: ?> 
                        <input class="campo" placeholder="Qual a sua cidade?" placeholder="<?=$loggedUser->city?>" type="text" name="city" /><br/><br/>
                    <?php endif; ?>   
                    <label>Trabalho:</label><br/>
                    <?php if(($loggedUser->work) != ""): ?>
                        <input class="campo" placeholder="<?=$loggedUser->work?>" type="text" name="work" /><br/><br/><hr/><br/>
                    <?php else: ?> 
                        <input class="campo" placeholder="Onde você trabalha?"  placeholder="<?=$loggedUser->work?> "type="text" name="work" /><br/><br/><hr/><br/>
                    <?php endif; ?>
                    <label>Nova senha:</label><br/>
                    <input class="campo" id="password" placeholder="Caso queira alterar sua senha, digite a nova senha." type="password" name="password" /><br/><br/>
                    <label>Confirmar senha:</label><br/>
                    <input class="campo" id="password1" placeholder="Repita a senha para confirmar" type="password" name="password_confirmation" /><br/><br/>
                    <input class="button" type="submit" value="Salvar"/>
                </form>
            </div>
            <div class="column side pl-5">
               <?=$render('right-side');?>
            </div>
        </div>
    </section>
</section>

<?=$render('footer');?>