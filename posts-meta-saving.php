<?php
/*
 * this class is to save meta data
 * */
 
 if(!class_exists('save_postmeta_data')) : 
	class save_postmeta_data{
		//saving data
		function save_meta($post_id){
			if(isset($_REQUEST['author'])){
				update_post_meta($post_id,'restricted_users',$_REQUEST['author']);
			}
		}		
		
	}
	
	$save_postmeta = new save_postmeta_data();
	add_action('save_post',array($save_postmeta,'save_meta'));
	
endif;
?>
