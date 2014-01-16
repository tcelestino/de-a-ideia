<?php

//	habilita suporte aos menus
add_theme_support('menus');

//	habilita thumbnails
if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(640, 320, true);
	add_image_size('images', 425, 9999);            	
}

/**
*	FUNCIONALIDADES PARA WP
*/

//	adiciona códigos no head
function add_head($function_to_add, $priority = 10, $accepted_args = 1) {
	add_action("wp_head", $function_to_add, $priority, $accepted_args);
}

//	adiciona códigos no footer
function add_footer($function_to_add, $priority = 10, $accepted_args = 1) {
	add_action("wp_footer", $function_to_add, $priority, $accepted_args);
}

//	adiciona códigos no sidebar
function add_sidebar($function_to_add, $priority = 10, $accepted_args = 1) {
	add_action("wp_footer", $function_to_add, $priority, $accepted_args);
}

//	paginação
function paglink($first=1,$last=1,$middle=10,$baseURL=false,$wp_query=false ) {
       if(!$baseURL) $baseURL= get_bloginfo('url');
       if(!$wp_query)global $wp_query;
       $page = $wp_query->query_vars["paged"];
       if ( !$page ) $page = 1;
       $qs = $_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : "";
       if ( $wp_query->found_posts > $wp_query->query_vars["posts_per_page"] ) {
               echo '<div class="pagination">';
               if ( $page > 1 ) { 
                       //echo '<a href="'.$baseURL.(($page==2)?('page/'.($page-1).'/'):'').$qs.'" class="link-anterior">anterior</a>';
               }
			   else {
					//echo '<div class="link-anterior-sem">anterior</div>'; 
			   }
   
         $dots=false;
			   
			   echo '<div class="pages">';
               for ( $i=1; $i <= $wp_query->max_num_pages; $i++ ){ // Loop through pages
                       if($i<=$first || $i<=$middle && $page<$middle || $i>$wp_query->max_num_pages-$last || $i>$wp_query->max_num_pages-$middle && $page>$wp_query->max_num_pages-$middle+1 || $i>$page-ceil($middle/2) && $i<=$page+floor($middle/2)){
                               if ( $i == $page ) { // Current page or linked page?
                                       echo '<strong class="bg2">'.$i.'</strong>';
                               } else {
                                       echo '<a class="bg1" href="'.$baseURL.(($i!=1)?('page/'.$i.'/'):'').$qs.'">'.$i.'</a>';
                               }
                               $dots=false;
                       }elseif(!$dots){
                               echo '<span>...</span>';
                               $dots=true;
                       }
               }
			   
			    echo '</div>';
			   
               if ( $page < $wp_query->max_num_pages ) { // Next link?
                       //echo '<a href="'.$baseURL.'page/'.($page+1).'/'.$qs.'" class="link-proximo">próximo</a>';
               }
			   else {
					 //echo '<div class="link-proximo-sem">próximo</div>';   
			   }
               echo '</div>';
       }
}
//  adicionar facebook avatar
add_filter('user_contactmethods', 'avatar_facebook');
function avatar_facebook($user_contactmethods) {
  $user_contactmethods["_avatar_facebook"] = "Facebook Avatar";
  return $user_contactmethods;
}

//  adiciona metabox
add_action('add_meta_boxes', 'custom_boxs');
function custom_boxs() {
  add_meta_box('youtube', 'Vídeo Enviado', 'youtubeURL', 'post', 'normal', 'high');
}
function youtubeURL($post, $metabox) {
    include('wpadmin/youtube.php');
}

// printa na tela o campo especifico
function printArrayField($field, $print=true){
  global $post;
  $val = get_post_meta($post->ID, $field, true);
  if($print){
    echo $val;
  } else {
    return $val;
  }
}

// pega o embed do youtube
//  return @string
function youtubeEmbed($url, $width=308, $height=245, $autoplay=0){
  $id = parse_url($url);
  $id = $id['query'];
  $id = parse_str($id, $out);
  $id = $out['v'];
  
  echo '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?rel=0&hd=1&autoplay='.$autoplay.'" frameborder="0" allowfullscreen></iframe>';
}
// pega a imagem do video do youtube
// return @string
function getYoutubeImage($url, $size='small'){
  $id = parse_url($url);
  $id = $id['query'];
  $id = parse_str($id, $out);
  $id = $out['v'];
  
  if($size=='small'){
    $size = '3';
  } else if($size=='full'){
    $size = '0';
  }
  return 'http://img.youtube.com/vi/'.$id.'/'.$size.'.jpg';
}

// carrega o arquivo que vai faz todas as ações do site
add_action( 'init', 'functions_js' );
function functions_js() {
  wp_enqueue_script(
      'functions_js',
      get_template_directory_uri() . '/js/functions.js',
      array('jquery')
    );
}
// adiciona o post ao WordPress
add_action('wp_ajax_nopriv_post_insert', 'post_insert');  
add_action('wp_ajax_post_insert', 'post_insert'); 
function post_insert(){

   require_once(ABSPATH . 'wp-admin/includes/image.php');

  if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] )) {

    $avatar   =  trim(wp_strip_all_tags($_POST["avatar"]));
    $username =  trim(wp_strip_all_tags($_POST["username"]));
    $name     =  trim(wp_strip_all_tags($_POST["name"]));
    $email    =  trim(wp_strip_all_tags($_POST["email"]));

    $title    = trim(wp_strip_all_tags($_POST['title']));
    $content  = nl2br(trim(wp_strip_all_tags($_POST['content'])));
    $media    = trim(wp_strip_all_tags(!isset($_POST["media"]))) ? NULL : $_POST["media"];
    $photo    = trim(wp_strip_all_tags(!isset($_POST["photo"]))) ? NULL : base64_decode($_POST["photo"]);

    $return   = array(); // aqui que faco da putaria

    // trato e vejo se os campos (titulo / content) estao vazio
    if(empty($title)) {
      $return["status"] = 0;
      $return["message"] = "Digite o titulo companheiro";
    } elseif (empty($content)) {
      $return["status"] = 0;
      $return["message"] = "Digite um texto.";
    } elseif(empty($name)) {
        $return["status"] = 0;
        $return["message"] = "Digite seu nome";
    } elseif(empty($email)) {
        $return["status"] = 0;
        $return["message"] = "Digite seu email";
    } elseif(!preg_match("/^[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*\\.[A-Za-z0-9]{2,4}$/", $email)) {
      $return["status"] = 0;
      $return["message"] = "Digite um email válido";
    }else {
      // verifico se o usuario ja existe atraves do username do facebook
      // caso ele já existe, adiciona o post com o id do usuario já cadastrado
      if(username_exists($username)) {

        $user = get_user_by("login", $username);

        $post = array(
          "post_title" => $title,
          "post_content" => $content,
          "post_status"  => "draft",
          "post_author" => $user->ID
        ); 
      } elseif(email_exists($email)) {

        $user = get_user_by("email", $email);

        $post = array(
          "post_title" => $title,
          "post_content" => $content,
          "post_status"  => "draft",
          "post_author" => $user->ID
        ); 

      } else {

          $user = save_user($avatar, $username, $name, $email);

          $post = array(
              "post_title" => $title,
              "post_content" => $content,
              "post_status"  => "draft",
              "post_author" => $user
            );
        }

          $post_id = wp_insert_post($post);

          if(!empty($photo)) {

            $wp_filetype = wp_check_filetype(basename($photo),null );
            $wp_upload_dir = wp_upload_dir();

             $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename( $photo ) ),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $wp_upload_dir['baseurl'] . _wp_relative_upload_path($photo) 
            );

            $attachment_id = wp_insert_attachment($attachment, $photo, $post_id);
            if($attachment_id) {

              $attach_data = wp_generate_attachment_metadata( $attachment_id, $photo );
              //wp_update_attachment_metadata( $attachment_id, $attach_data );
              set_post_thumbnail( $post_id, $attachment_id ); // seta a imagem enviada como imagem destacada
            } else {
              $return["status"] = 0;
              $return["message"] = "Erro ao tentar enviar sua imagem.";
            }
           
          }

          if($post_id) {
            if($media) {
              add_post_meta($post_id, "_youtube_url", $media, true);
            } else {
              $return["status"] = 0;
              $return["message"] = "Erro ao tentar enviar seu vídeo.";
            }
          } else {
            $return["status"] = 0;
            $return["message"] = "Erro ao tentar enviar seu post.";
          }
        $return["status"] = 1;
        $return["message"] = "Sua ideia foi enviada com sucesso. Em breve, estará no ar.";
    }  


    $output = json_encode($return);
           if(is_array($output)){
          print_r($output);   
           }
           else{
          echo $output;
           }
           die;
  }
}


function getUsers() {
  global $wpdb;

  $sort= "user_registered";
  $level = 0;

  $default = get_template_directory_uri()."/images/no_avatar.jpg"; // imagem padrão para o avatar
  $size = 50;

  $all_users = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE ID = ANY (SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'wp_user_level' AND meta_value = $level)")); // query para pegar todos os usuários baseados no nivel assinantes
  if($all_users) {
  ?>
  <ul>
  <?php


  foreach($all_users as $user) {

    // infos dos usuarios
    $user_id = $user->ID;
    $user_email = $user->user_email;
    $user_name = $user->user_nicename;

    $fb_avatar = get_usermeta($user_id, "_avatar_facebook"); // campo personalizado do avatar do facebook
    ?>
    <li>
      <?php 

      if($fb_avatar) { 
        echo "<img src='".$fb_avatar."' alt='".$user_name."' />";
      } else {
        echo "<img src='".$default."' alt='".$user_name."' />";
      }?>

    </li>
<?php } ?>
</ul>
<?php } else { echo "<p>Dê sua ideia</p>"; } }

//  salva um usuario no wordpress
//  return @integer
function save_user($avatar, $username, $name, $email) {

  if(empty($username)) {
      $username = "Guest_".rand(000000,999999).$username; // cria um usuario caso não exista
  }
    $userdata = array(
         "ID" => '',
         "user_pass" => wp_generate_password(),
         'user_login' => $username,
         'user_nicename' => $name,
         'user_url' => '',
         'user_email' => $email,
         'display_name' => $name,
         'nickname' => $username,
         'first_name' => $name,
         'user_registered' => '',
         'role' => get_option('default_role')
     );
     $profile_id = wp_insert_user($userdata);
     if($profile_id) {

        if(empty($avatar)) {
          $avatar = "";
        } else {
          add_user_meta($profile_id, "_avatar_facebook", $avatar, true);
        }
       return $profile_id;
     }
  }



// adiciona jsdk javascript do facebook
add_action('wp_footer', 'facebook_js');
function facebook_js() { ?>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '', // App ID
      frictionlessRequests: true,
      status     : true, 
      cookie     : true,
      xfbml      : true  // parse XFBML
    });
  };

  (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/pt_BR/all.js';
        document.getElementById('fb-root').appendChild(e);
      }()

  </script>

<?php

}