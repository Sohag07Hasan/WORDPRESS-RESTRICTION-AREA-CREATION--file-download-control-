<?php
/*
Plugin Name: WP Restriced Area Creation
Plugin URI: http://sohag07hasan.elance.com
Description: To make restriction to certain files and folders
Version: 1.0.0
Author: Mahibul Hasan
Author URI: http://sohag07hasan.elance.com
*/

if(!class_exists('wprestrictedarea')) : 
	class wprestrictedarea{		
		
		//add a column in users.php page table
		function add_column($columns){
			$columns['restriction'] = '<a id ="restrictionnn" href="#">Restriction</a>';
			return $columns;
		}
		
		// populate coustom column of the table
		function free_users($empty='',$column,$id){
			$img = plugins_url('', __FILE__).'/images/ajax-loader.gif';
			$options = '<select style="width:50px" class="restrictionorfree" id="rest-'.$id.'">
				<option value="yes" '.$this->selection($id,'yes').'>YES</option>
				<option value="no" '.$this->selection($id,'no').'>NO</option>
			</select><img id="imgajax-'.$id.'" style="width:20px;margin-left:5px;display:none;" src="'.$img.'" alt="" />';
			if($column == 'restriction'){
				return $options;
			}			
		}
		
		//function for select=selected manipulation
		function selection($id,$str){
			
			$userdata = get_userdata($id);
			$value = $userdata->restriction;			
			return (($value == $str))? 'selected="selected"' : '';
					
		}
		
		//javascript addition
		function javascript_adition(){
			wp_enqueue_script('jquery');
			wp_register_script('qprestirction',plugins_url('', __FILE__).'/js/restrictionjavascript.js',array('jquery'));			
			wp_enqueue_script('qprestirction');				
			
			wp_localize_script( 'qprestirction','RestrictedAjax', array( 
						'ajaxurl' => admin_url( 'admin-ajax.php' ),						
						'pluginsurl' =>  plugins_url('', __FILE__)						
				));
		}
		
		//ajax data manipulation
		function ajaxmanipulation(){
			$id = $_REQUEST['idorclass'];
			$id = preg_replace('/[^\d]/','',$id);
			$id = (int)$id;
			$value = trim($_REQUEST['value']);			
			//updating usermeta data by using function
			if(empty($id) && empty($value)){
				$message = 'For Techincal reason action is aborted! please reload the page and try again';
				exit;
			}
			if(update_user_meta($id,'restriction',$value)){
				$message = 'd';
			}
			else{
				$message = 'For Techincal reason action is aborted! please reload the page and try again';
			}
			echo $message;
			exit;
		}
		//end of ajax data
		
		
		//filetype checking
		function filechecking(){
			$link = $_SERVER['REQUEST_URI'];		
			
			//$baselink = 
			preg_match('/\w.+\.\w.[^\/]+$\b/',$link,$b);
			//checking file type whether they are php html com etc
			//extention checking
			if($b[0]){
								
				$ext = preg_replace('/\w.+\./','',$b[0]);
				$ext = preg_replace('/[?&].+$/','',$ext);
				$allow = array('php','html','com');
				$ext = strtolower($ext);				
								
				if(in_array($ext,$allow)){
					unset($b[0]);
				}
				if($ext == ''){
					unset($b[0]);
				}
			}			
								
			
			if($b[0]){
				if(is_user_logged_in()){
					//get the current user infos
					global $current_user;
					get_currentuserinfo();
					if($current_user->restriction == 'no'){						
						
						// absolute path for the wp-content						
						//$path = get_option('wprestriction');
						$path = __FILE__;						
						
						
						$wp_content_path = preg_replace('/wp-content.+$/','',$path);
											
						//filepath relative to wp-content
						preg_match('/(?<=wp-content)[\/]\w.+$/',$link,$f);
						$filelink = $f[0];
						
						//absolute location of requested file
						$absurl = $wp_content_path.'wp-content'.$filelink;					
						
						
						//calling header function
						$this->headersset($ext,$absurl);
																
					}									
					
					
				}
				
			}
		}
		
		//header setting function
		function headersset($ext,$url){
			
			//retrieving file informations
			if(file_exists($url)) :							
			
				//retrieving content type
				$fsize = filesize($url);				
				
				switch ($ext) { 
					case "pdf": $ctype="application/pdf"; break; 
					case "exe": $ctype="application/octet-stream"; break; 
					case "zip": $ctype="application/zip"; break; 
					case "doc": $ctype="application/msword"; break; 
					case "xls": $ctype="application/vnd.ms-excel"; break; 
					case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
					case "gif": $ctype="image/gif"; break; 
					case "png": $ctype="image/png"; break; 
					case "jpeg": 
					case "jpg": $ctype="image/jpg"; break; 
					default: $ctype="application/force-download"; 
				}
				
				
				$f = fopen($url, 'r');
				$con = fread( $f, $fsize );
				header("Content-Type: $ctype");
				//flush();
				echo $con;
				fclose($f);
				//exit is must
				exit;
								 
			endif;
		}		
		
	}

$wpfree = new wprestrictedarea();
//register_activation_hook( __FILE__,array($wpfree,'pluginsetup'));
add_filter('manage_users_columns',array($wpfree,'add_column'));
add_filter('manage_users_custom_column',array($wpfree,'free_users'),10,3);
add_action('admin_print_scripts',array($wpfree,'javascript_adition'));
add_action('wp_ajax_restriction_ajax_data',array($wpfree,'ajaxmanipulation'));
add_action('wp_ajax_nopriv_restriction_ajax_data',array($wpfree,'ajaxmanipulation'));
add_action('init',array($wpfree,'filechecking'));
//add_action('wp_print_scripts',array($wpfree,'javascript_adition'));

endif;


//includig the settings page
include("restricted-area-settings.php");
include("posts-meta-saving.php");
include("post-page-restriction.php");


?>
