jQuery(document).ready(function($){
	//disabling clicks	
	
	
	//functioanal function
	$('.restrictionorfree').change(function(){
		var yesorno = $(this).val();
		var author = $(this).attr('id');
		//alert(author);
		if(author == null ){
			alert('No author is Selected');
			return false;			
		}
		else{
			//calling ajax
			var message = mainajax(author,yesorno);			
			if(message = 'd'){
				//alert(message);
				window.location.href = window.location.href;
			}
			else{
				alert(message);
				return false;
			}											
		}		
		
	});
	//end of main function
	
	
	
	//author id retriving
	function imgclass(obj){				
		
		return obj.replace(/[a-z]+/,'#imgajax');
	}
	 
	
	//ajax calling
	function mainajax(yrt,valuee){
		
		//showing the image while ajax is loading
		var img = imgclass(yrt);
		$(img).css({'display':'inline'});
		
		//alert(typeof yrt);
		//alert(typeof valuee);
		var ty = null;
		$.ajax({						
			async: false,
			type:'post',			
			dataType:"html",
			url:RestrictedAjax.ajaxurl,
			cache:false,
			timeout:10000,
			data:{
				'action':'restriction_ajax_data',
				'idorclass':yrt,
				'value':valuee				
			},
			
			success:function(result){				
				ty = result;				
			},
			
			error: function(jqXHR, textStatus, errorThrown){
				jQuery('#footer').html(textStatus);
				alert(textStatus);
				return false;
			},			
			
		});
		return ty;
	}
})
