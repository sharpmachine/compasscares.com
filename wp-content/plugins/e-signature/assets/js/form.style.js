(function($){
	
	 var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
  }

  
	
	 $(".tooltip").hover(function() {
			$('.tooltip span').append("<img class=\"callout\" src=\"" + esig_tool_tip_script.imgurl +"\">");
		});
		
	$(".tooltip").mouseout(function() {
			$('.callout').remove();
});

// select2 form style . 

$(".esig-select2").select2({
    allowClear: true
});
// esig document search 
$("#esig_document_search").select2({
   
     maximumInputLength: 10,
   
});

// disabling third party alert msg . 
$('.updated').hide();

// report bug link clicked 
    $('#esig-report-bug').click(function(){
        
        tb_show("Loading...", '#TB_inline?width=450&height=300&inlineId=report-bug-loading');
        jQuery.ajax({  
        type:"POST",  
        url: esigAjax.ajaxurl+"?action=esig_out_date_msg",    
        success:function(data, status, jqXHR){  
            //alert(data);
           if(data == 'updateok'){
           $("#TB_window").remove();
            $("body").append("<div id='TB_window'></div>");
           tb_show("+ Submit A Bug Request", '#TB_inline?width=450&height=300&inlineId=report-bug-step1');
            }else {
          
             $('#report_bug_button').hide();
             $('#report-bug-radio-button').html(data);
             $("#TB_window").remove();
             $("body").append("<div id='TB_window'></div>");
             tb_show("+ Submit A Bug Request", '#TB_inline?width=450&height=300&inlineId=report-bug-step1');
            }
        },  
        error: function(xhr, status, error){  
           $('.esig-terms-modal-lg .modal-body').html('<h1>No internet connection</h1>');
        }
        });  
		
	});	
    
    $('#esig_report_bug_upload').click(function(){
       
        var report_type = $('input[name="esig_report_bug_type"]:checked').val();
        
       $("#TB_window").remove();
        $("body").append("<div id='TB_window'></div>");
        $(".chosen-container").css("min-width","475px");
			
				$(".chosen-drop").show(0, function () { 
				$(this).parents("div").css("overflow", "visible");
				});
		tb_show("+ Submit A Bug Request", '#TB_inline?width=500&inlineId=report-bug-step-'+ report_type);
       
	});	

    // form submmiting 
     $('#esig_report_bug_submit').click(function(){
       
          document._form_281.submit();
	});	
    $('#esig_report_ticket_submit').click(function(){
       
          document._form_282.submit();
	});
    $('#esig_report_idea_submit').click(function(){
       
          document._form_283.submit();
	});
    	
	
})(jQuery);

