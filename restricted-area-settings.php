<?php
/*
 *plugin settings section
 * 
 * */
 
 if(!class_exists('wp_restrictionarea_settings')) : 
	class wp_restrictionarea_settings{
		
		//creating an options page
		function optionsPage(){
			add_options_page('wp restricted area','WP Restriction','activate_plugins','restriction-api',array($this,'optionsPageDetails'));
		}
		
		//creating options page in admin panel
		function optionsPageDetails(){
			//starin html form
		?>
			<div class="wrap">
				<?php screen_icon('options-general'); ?>
				<h2>WP Restricted Area Creation</h2>
				<form action="options.php" method="post">
					<?php
						settings_fields('wprestriction_group');
						do_settings_sections('restriction-api');
					?>
					<br/>
					<br/>
					<input type="submit" class="button-primary" value="submit" />
				</form>
			</div>
			
		<?php
			
		}
				
		//registering options
		function registerOption(){
			register_setting('wprestriction_group','wprestriction',array($this,'data_validation'));
			add_settings_section('first_section',' ',array($this,'first_settings_section'),'restriction-api');
			
			add_settings_field('first_input','Message for users',array($this,'first_settings_field'),'restriction-api','first_section');			
			add_settings_field('second_input','Message for visitors',array($this,'second_settings_field'),'restriction-api','first_section');
		}
		
		
		//first settins sections callback
		function first_settings_section(){
			echo '<p style="color:#A52A2A;font-size:20px;font-family:Sans-Serif">Restricted Folders must be within wp-content directory or subdirectory containing ".htaccess" file provided with the plugin </p>';
			echo '<hr/><br/>';
			screen_icon('options-general');
			echo "<h2>Restricted Posts and Pages settings</h2>";
			echo "<p style='color:#4E3BF1;font-size:15px;'>Simply put the string [wprestriction] (including the square bracket) in any of your page and posts to make them restricted</p>";
			echo '<p>Messages that replaces the restricted posts and pages </p>';
			
		}		
		
		
		//first_settings_field for the first sections
		function first_settings_field(){
			$value = get_option('wprestriction');		
			echo '<textarea cols="50" rows="5" name="wprestriction[usersmessage]">'.$value['usersmessage'].'</textarea>';
		}
		
		//second input for the second section
		function second_settings_field(){
			$value = get_option('wprestriction');		
			echo '<textarea cols="50" rows="5" name="wprestriction[visitorsmessage]">'.$value['visitorsmessage'].'</textarea>';
		}
		
		
		//validating data
		function data_validation($data){			
			return $data;			
		}
	}
	
	$wprestriction = new wp_restrictionarea_settings();
	add_action('admin_menu',array($wprestriction,'optionsPage'));
	add_action('admin_init',array($wprestriction,'registerOption'));
endif;

?>
