jQuery(document).ready(function($) {

	$('.list-icons li').each(function() {
		
		$(this).hover(function () {
			$(this).addClass('hovered');
		});
		
		$(this).mouseout(function () {
			$(this).removeClass('hovered');
		});
		
		$(this).click(function () {
			$('.list-icons li').removeClass('activei');
			$(this).addClass('activei');
			
			$('.list-icons li input').each(function() {
				$(this).removeAttr('checked');
			});
			
			$(this).find('input').attr('checked','checked');
			
		});
		
	});
    $('#active_license').on('click', function(e){
        e.preventDefault();
        var formdata = $('#check-key').find('textarea, select, input').serialize();
        formdata = formdata + '&action=ajc_active_license&nonce=' + admin_ajaxcart.nonce;
        $('#image_loading').show();
        $.post(admin_ajaxcart.url, formdata, function(data){
            $('#image_loading').hide();
            $('#message').html(data);
            alert(data);
            location.reload();
        });
    });	
    $('#remove_license').on('click', function(e){
        e.preventDefault();
        var con = confirm("Do your want to remove license?");
        if(con){
            var formdata = $('#check-key').find('textarea, select, input').serialize();
            formdata = formdata + '&action=ajc_remove_license&nonce=' + admin_ajaxcart.nonce;
            $('#image_loading').show();
            $.post(admin_ajaxcart.url, formdata, function(data){
                $('#image_loading').hide();
                $('#message').html(data);
                alert(data);
                location.reload();                
            });
        }
    });    
});

(function($){
	"use strict";
	$(document).ready(function() {
		//skin
	    $("input[name='jform']").click(function() {
	        var test = $(this).val();
	        $("tr#desc").hide();
	        $("tr.skin-" + test).show();
	    });
	    if ($('input[name=jform]:checked').val() =='cus') {
	        $("tr#desc").hide();
	        $("tr.skin-cus").show();
	        //return false;
	    }
	    else {
	        $("tr#desc").hide();
	        $("tr.skin-skil").show();
	    }
	   
    	//icon
	    $("input[name='jform-icon-display']").click(function() {
	    	if($('input[name=jform-icon-display]:checked').val() =='show'){
	    		$("tr.icon-display-show").show();
	    		if ($('input[name=jform-icon]:checked').val() =='1') {
			        $("tr.icon-set").hide();
			        $("tr#icon-1").show();
			    }
			    if ($('input[name=jform-icon]:checked').val() =='0') {
			        $("tr.icon-set").hide();
			        $("tr#icon-0").show();
			    }
	    	}else{
	    		$("tr.icon-display-show").hide();
	    	}
	    });
	    //icon custom
	    if ($('input[name=jform-icon]:checked').val() =='1') {
	        $("tr.icon-set").hide();
	        $("tr#icon-1").show();
	    }
	     if ($('input[name=jform-icon]:checked').val() =='0') {
	        $("tr.icon-set").hide();
	        $("tr#icon-0").show();
	    }
	    if($('input[name=jform-icon-display]:checked').val() == "hide"){
	    	$("tr.icon-display-show").hide();
	    }
	    $("input[name='jform-icon']").click(function() {
	    	var nick = $(this).val();
	        $("tr.icon-set").hide();
	        $("tr#icon-" + nick).show();
	    });

	    //position
	    if($('input[name=adcart-numsub]:checked').val() == "num"){
	    	$("tr.icon-pos").hide();
	    }
	    $("input[name='adcart-numsub']").click(function() {
		    if($('input[name=adcart-numsub]:checked').val() == "sub"){
		    	$("tr.icon-pos").show();
		    }else{
		    	$("tr.icon-pos").hide();
		    }
		});
	});
	
})(jQuery);
