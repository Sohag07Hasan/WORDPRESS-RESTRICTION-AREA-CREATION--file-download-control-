<?php
/*
 * This is to make the posts or page restricted
 * */
 
 if(!class_exists('wp_pagepost_restriction')) : 
	class wp_pagepost_restriction{
		
		//box set up function
		function advanced_boxex(){
										
			add_meta_box('page-post-restriction',__('Users (checked are restricted)'),array($this,'restriction'),'page','side','high');
			add_meta_box('page-post-restriction',__('Users (checked are restricted)'),array($this,'restriction'),'post','side','high');		
		}	
		
		//box populating function
		function restriction(){
			global $post;
			
			$users = get_users();
			//echo "<input type='checkbox' id='allcheckboxrestriction' value='' /> All Users<br/>";
			foreach($users as $user){
				$a .= "<input name='author[]' type='checkbox' value='$user->ID' ".$this->checkmark($user->ID,$post->ID)." /> $user->display_name <br/>"; 
			}
			echo $a;
		}
		
		//checked = checked function
		function checkmark($user,$id){
			$rusers = get_post_meta($id,'restricted_users',true);			
			if(is_array($rusers)){
				if(in_array($user,$rusers)){
					$a = "checked='checked'";
				}
				else{
					$a = "";
				}
			}
			else{
				$a = "";
			}		
			
			return $a;
		}
				
		//function for checking the posts and pages which of them are restricted
		
		function wp_postandpage($content){
			
			global $post;
			
			if(strstr($content,'[wprestriction]')){
				$messages = get_option('wprestriction');
				if(is_user_logged_in()){					
					//restricted users
					
					
					$rusers = get_post_meta($post->ID,'restricted_users',true);
					if(!is_array($rusers)){
						$rusers = array('','');
					}
					
					//current user
					global $current_user;
					get_currentuserinfo();
					$user = $current_user->ID;
										
					if(in_array($user,$rusers)){
						return $messages['usersmessage'];
					}
					else{
						return $this->contents($content);
					}
					
					$message = $messages['usersmessage'];
				}
				else{
					return $messages['visitorsmessage'];
				}
			}
			else{
				return $content;
			}
			
			
		}
		
		//content sanitization
		function contents($a){
			return preg_replace('/\[wprestriction\]/','',$a);
		}
	}
	
	$postandpage = new wp_pagepost_restriction();
	add_action('add_meta_boxes',array($postandpage,'advanced_boxex'));
	add_filter('the_content',array($postandpage,'wp_postandpage'));
endif;
?>
