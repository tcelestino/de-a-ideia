<style type="text/css">
.dados_video input { width: 99%; }
</style>
<div class="dados_video">
	<?php
        $youtube = printArrayField('_youtube_url', false);
        if($youtube!=''){
            youtubeEmbed($youtube, 260, 200);
			echo '<br /><br />';
        }
    ?>
    <input type="text" name="_youtube_url" value="<?php echo $youtube; ?>" />
</div>