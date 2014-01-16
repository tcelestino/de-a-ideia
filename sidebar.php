<aside id="sidebar" class="alignleft span3">
		<div class="bar"></div>
		<h1 class="ir">Reta Final</h1>
		<section id="sendPost" class="box">
			<form action="#" method="post" id="savePost" name="save_post">
				<fieldset>
					<div class="alignleft span4">
						<span class="badge hide"></span>
						<?php if($user) { ?>

						<div class="user">
							<figure class="alignleft"><img src="<?php echo $avatar; ?>" alt="<?php echo $name; ?>"></figure>
							<div class="alignleft info-user">
								<a href="<?php bloginfo("template_url"); ?>/incs/logout.php" class="button">Sair</a>
								<span class="name"><?php echo $name; ?></span>
								<span class"email"><?php echo $email; ?></span>
							</div>
						</div>


						<input type="hidden" name="avatar" id="avatar" value="<?php echo $avatar; ?>" />	
						<input type="hidden" name="fb_name" id="fb_name" value="<?php echo $name; ?>" />
						<input type="hidden" name="fb_username" id="fb_username" value="<?php echo $username; ?>" />			
						<input type="hidden" name="fb_email" id="fb_email" value="<?php echo $email; ?>" />
						<?php } else { ?>
						 <a href="<?php bloginfo("template_url"); ?>/incs/auth_facebook.php" class="button fb-connect" id="connectFacebook">
						 	<span class="ir ico alignleft"></span>Conecta-se ao Facebook</a>
						 <div class="clearfix"></div>
						 <div class="control">
						 	<input type="text" name="user_name" id="user_name" class="alignleft span5" placeholder="<?php echo _e("Nome"); ?>" required />

						 	<input type="email" name="user_email" id="user_email" class="alignleft span5" placeholder="<?php echo _e("Email"); ?>" required />	
						 </div>
							<?php } ?>
							<input type="hidden" name="url_media" id="url_media" />
							<input type="hidden" name="url_photo" id="url_photo" />
							<input type="text" name="title" id="post_title" class="span4 tit-post" placeholder="Digite um titulo" />
				
						<div class="control">
							<textarea name="post_content" id="post_content" class="span4" cols="30" rows="10" placeholder="Mensagem"></textarea>
							<div class="count-words">
								<input type="text" name="counter" id="counter" value="300" />caracteres restantes
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="control-group">
							<div class="alignleft control">
								<input type="radio" value="photo" id="attachment_photo" name="attachment" class="alignleft">
								<span class="ico-photo"></span>
								<label for="attachment_photo" class="alignleft">
									Adicionar Foto
								</label>
							</div>
							<div class="alignleft control">
								<input type="radio" value="video" name="attachment" id="attachment_video" class="alignleft">
								<span class="ico-video"></span>
								<label for="attachment_video" class="alignleft">
									Adicionar Vídeo
								</label>
							</div>
							
						</div>

						<!-- <a href="#" class="alignleft send photo">
							<span class="ico"></span>
							<span class="text">Adicionar Foto</span>
						</a>
						<a href="#" class="alignleft send video">
							<span class="ico"></span>
							<span class="text">Adicionar Vídeo</span>
						</a> -->
						
					</div>
					
					<div class="alignleft button-submit">
						<button type="submit" name="enviar" id="enviar">
							Enviar
						</button>
					</div>
					
				</fieldset>
			</form>
		</section> <!--/send post-->
		<section id="sociais" class="box">
			<h3>Acesse também</h3>
			<ul>
				<li><a href="http://facebook.com/soupelegrino13" target="_blank" class="ir ico1">Facebook</a></li>
				<li><a href="http://twitter.com/soupelegrino13" target="_blank" class="ir ico2">Twitter</a></li>
				<li><a href="http://www.soupelegrino13.com.br" target="_blank" class="ir ico3">Website</a></li>
			</ul>
		</section>
		<div class="clearfix"></div>
		<?php if(function_exists("getUsers")) { ?>
		<section id="users" class="box">
			<h3>Quem já está junto</h3>
			<?php getUsers(); ?>
		
		</section>
		<?php } ?>
	</aside>