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
                <form class="" method="POST" action="<?=$base;?>/config">
                    <br/>
                    <input placeholder="<?=$loggedUser->id?>" type="hidden" name="id" />
                    <br/>
                    <label>Nome Completo:</label><br/>
                    <input class="input" placeholder="<?=$loggedUser->name?>" type="text" name="name" /><br/><br/>
                    <label>Data de Nascimento:</label><br/>
                    <input class="input" id="birthdate" placeholder="<?=date('d/m/Y', strtotime($loggedUser->birthdate));?>" type="text" onfocus="(this.type = 'date')" name="birthdate" /><br/><br/>
                    <label>E-mail:</label><br/>
                    <?php if(isset($flash['email']) > 0): ?>
                            <div class="flash"><?php echo $flash['email']; ?></div>
                    <?php endif; ?>
                    <input class="input" placeholder="<?=$loggedUser->email?>" type="email" name="email" /><br/><br/>
                    <label>Cidade:</label><br/>
                    <?php if(($loggedUser->city) != ""): ?>
                        <input class="input" placeholder="<?=$loggedUser->city?>" type="text" name="city" /><br/><br/>
                    <?php else: ?> 
                        <input class="input" placeholder="Qual a sua cidade?" placeholder="<?=$loggedUser->city?>" type="text" name="city" /><br/><br/>
                    <?php endif; ?>   
                    <label>Trabalho:</label><br/>
                    <?php if(($loggedUser->work) != ""): ?>
                        <input class="input" placeholder="<?=$loggedUser->work?>" type="text" name="work" /><br/><br/><hr/><br/>
                    <?php else: ?> 
                        <input class="input" placeholder="Onde você trabalha?"  placeholder="<?=$loggedUser->work?> "type="text" name="work" /><br/><br/><hr/><br/>
                    <?php endif; ?>
                    <label>Nova senha:</label><br/>
                    <?php if(isset($flash['password']) > 0): ?>
                            <div class="flash"><?php echo $flash['password']; ?></div>
                    <?php endif; ?>
                    <input class="input" id="password" placeholder="Caso queira alterar sua senha, digite a nova senha." type="password" name="password" /><br/><br/>
                    <label>Confirmar senha:</label><br/>
                    <?php if(isset($flash['password']) > 0): ?>
                            <div class="flash"><?php echo $flash['password']; ?></div>
                    <?php endif; ?>
                    <input class="input" id="password1" placeholder="Repita a senha para confirmar" type="password" name="password_confirmation" /><br/><br/>
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