<?php 

require "incs/facebook.php";

// api e secret app
$config_app = array(
	'appId' => '',
	'secret' => ''
);

$facebook = new Facebook($config_app);

$uid = $facebook->getUser();

if ($uid) {
  try {
    $user = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $uid = null;
  }
}

if($user) {
	//	pega o avatar
	$avatar = $facebook->api("/me/"."?fields=picture");
	$avatar = $avatar["picture"]["data"]["url"];
	$username = $user["username"];
	$name = $user["name"];
	$email = $user["email"];

} get_header(); ?>
<div id="wrap"  class="span1">
	<div class="alignleft span2 content">
		<div class="bar"></div>
		<?php if(have_posts()) { 
			while(have_posts()) { 
				the_post(); 
		?>
		<article class="post">
			<figure class="avatar">

				<?php 

				$user_id = $post->post_author;
				$wp_avatar = get_usermeta($user_id, "_avatar_facebook");
				$avatar_default = get_template_directory_uri()."/images/no_avatar.jpg";
				$author = get_userdata($user_id);
				$author_name = $author->user_nicename;

				if($wp_avatar) {

				?>
				<img src="<?php echo $wp_avatar;?>" alt="<?php echo $author_name; ?>" />

			<?php } else { ?>

				<img src="<?php echo $avatar_default;?>" alt="<?php echo $author_name; ?>" />
			<?php } ?>
			</figure>
			<div class="info-data">
				<span class="name-user"><?php the_author(); ?></span>
				<time datatime="<?php the_date("c"); ?>" pubdate><?php the_time("d"); ?> de <?php the_time("F"); ?> - às <?php the_time("G:i"); ?></time>
			</div>
			<div class="detail-post"></div>
			<article class="content-post">
				<h2><?php the_title(); ?></h2>
				<?php the_content(); ?>
			</article>
			<aside class="media">
			
				<?php 

				  $args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post->ID, 'order' => 'ASC', 'orderby' => 'menu_order ID' ); 
				  $attachments = get_posts($args);

				  foreach ($attachments as $attachment) { 
				  		 $thumbnail = wp_get_attachment_image_src( $attachment->ID, 'images' );

				  		 echo '<img src="'.$thumbnail[0].'" alt="" title="" />'; 
				  		}


				$video = get_post_meta($post->ID, '_youtube_url', true);
				if(function_exists("getYoutubeImage")) { 
					if($video) {
					youtubeEmbed($video, 425, 250);
					}
				}
				 ?>
			</aside>
		</article>
		<?php } } else { ?>
		<article class="post">
			<p>Ainda não existe nenhuma ideia. Deixe a sua, usando o formulário ao lado.</p>			
		</article>
		<?php } ?>
		<?php if (  $wp_query->max_num_pages > 1 ) : ?>
		<div class="clearfix"></div>
		<div class="pagination">
			<div class="controls nav-previous">
				<?php next_posts_link( __( 'Anteriores') ); ?>
			</div>
			<div class="controls nav-next">
				<?php previous_posts_link( __( 'Próximo' ) ); ?>
			</div>
		</div>
		<?php endif; ?>

	</div>
	<aside id="sidebar" class="alignleft span3">
		<div class="bar"></div>

		<section id="sendPost" class="box">

			<p>Este é um espaço para vocês se expressarem. Com a sua conta no Facebook ou com seu e-mail você pode escrever uma mensagem, postar uma foto ou um vídeo e contribuir com a campanha de Pelegrino! Vamos todos juntos com o 13 mudar Salvador.</p>

			<div class="hide sending-post">
				<span>Aguarde, estamos enviando sua mensagem</span>
			</div>

			<div class="hide success-post">
				<span class="ir ico-success"></span>
				<p>Enviado com sucesso.</p>
				<!-- <p>Sua ideia foi enviada com sucesso. Em breve, estará no ar.</p> -->
			</div>

		


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
						 	<span class="ir ico alignleft"></span>Conectar ao Facebook</a>
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

					</div>
					
					<div class="alignleft button-submit">
						<button type="submit" name="enviar" id="enviar">
							Enviar
						</button>
					</div>
					
				</fieldset>
			</form>
		</section> 
		
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
</div>
<div class="modal hide" id="uploadPhoto">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>Envie uma foto</h3>
	</div>
	<div class="modal-body">
		<ol>
			<li>Clique em ESCOLHER ARQUIVO e selecione uma imagem do seu computador. </li>
			<li>Clique em SALVAR FOTO.</li>
			<li>Pronto! Sua imagem já está selecionada e, em breve, estará no ar.</li>
		</ol>
		<span id="loading" class="label label-important hide">Carregando</span>
		<span class="status label hide"></span>
		<form action="" method="POST" enctype="multipart/form-data">
			<fieldset>
				<!-- <input type="text" name="fakeupload" id="fakeupload" /> -->
				<input type="file" name="fileToUpload" id="fileToUpload" />
				<div class="clearfix"></div>
				<span class="label label-info">Formatos permitidos: PNG, JPG ou GIF de até 5 MB.</span>
				<div class="clearfix"></div>
				<button type="submit" class="btn btn-primary" onclick="return ajaxFileUpload();">Salvar Foto</button>
			</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-danger" data-dismiss="modal">Fechar</a>
	</div>
</div>
<div class="modal hide" id="videoYouTube">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>Adicionar um vídeo</h3>
	</div>
	<div class="modal-body">
		<ol>
			<li>Cole na caixa abaixo o link do vídeo que deseja enviar.</li>
			<li>Clique em SALVAR VÍDEO.</li>
			<li>Pronto! Seu vídeo estará no ar em breve.</li>
		</ol>
		<input type="text" name="attach-video" id="attach-video" placeholder="http://youtube.com/" />

		<a href="#" class="btn btn-success save-video">Salvar Vídeo</a>

		<!-- <h4>Fique ligado(a)!</h4> -->
		<span class="label label-info">ATENÇÃO! Só serão válidos links do YOUTUBE.</span>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-danger" data-dismiss="modal">Fechar</a>
	</div>
</div>

<?php get_footer(); ?>