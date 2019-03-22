        var dur=550;
		var user_on = 0;
		var c_usr = "";
		var c_pass = "";
		var user_ip = "";
		var parts_idx = [0, 0, 0, 0];
		var init_func = [false, false, false];		
        
        $(function() {
			
			// MAIN AJAX HANDLER //
			 function jqAJAX(act, respTp, sendDat){
			 /* respTp - Response type
			 0 - Console type response (text\html)
			 1 - Dialog content response (ui-block\html)
			 2 - Catalog Row response (table-row\html) 
			 3 - Login state response (value\user_name)
			 */
                
				$('#status').text('Processing ACT: '+act+'...');
				
                $.ajax({
                    method: "POST",
                    url: 'processor.php',
                    data:{
                        action: act,
                        procdata: sendDat
                    },
                    beforeSend: function(xhr){
                        $('status').text('Обрабтываю запрос...').show();
                    }
                }).done(function(respData){
					
					switch (respTp){
						case 0: 
							$('#status').addClass("ui-state-highlight ui-corner-all");
							$('#status').css({"text-align":"left","display":"block"});
							$('#status')
							.fadeOut(dur)
							.html("<span class='ui-icon ui-icon-info' style='float: left;'></span>"+
							"<div >"+respData+"</div>" )
							.fadeIn(dur); 
						break;
						case 1:
							$('#login_script').html(respData);
						break;
						case 2:
						
						break;
						case 3:
							//alert(respData);
							if (respData != ''){
								user_on = 1;
								c_usr = respData.split("|")[0];
								c_pass = respData.split("|")[1];
								$('#tbLogin').hide();
								$('#tbLogOUT').show();
								$('#user_status').addClass("ui-state-default btnTitleFix ui-corner-all");
								$('#user_status').css({'font-weight': 'normal'});
								$('#cur_usr').html( respData.split("|")[0] );
								$('#cur_usr').css({'font-weight': 'bold'});
							}
						break;
					}
					
					$('#status').text('READY ACT: '+act+'!');
					
                }).fail(function(){
                    		$('#status').addClass("ui-state-error ui-corner-all");
							$('#status').css({"text-align":"left","display":"block"});
							$('#status')
							.fadeOut(dur)
							.html("<span class='ui-icon ui-icon-alert' style='float: left;'></span>"+
							"<div > Ошибка при обработке запроса </div>" )
							.fadeIn(dur); 
                });
                
            };
			// AJAX handler END //
			
			
			
			// Dialog Handler //
			$(".dlg_btn").click(function(){
			
				var dlgTitle = $(this).attr('DlgCap');
				var btnCapt = $(this).attr('ABtnCap');	
				var actTp = $(this).attr('actType');
				var c_dlg = $(this).attr('form_nm');
				var dlgHg = $(this).attr('dlgDem').split('x')[1];
				var dlgWd = $(this).attr('dlgDem').split('x')[0];
				var post_str = "";

				//alert(actTp);
				
				$(".dlg_resp").hide();				
				$('#' + c_dlg ).show();
				$('input').val('');
				
				// DIALOG.Create();	
				$('#log-in').dialog({
				title: dlgTitle,
				resizable: true,
				modal: true,
				height: dlgHg,
				width: dlgWd,
				show:{
					effect: 'drop',
					duration: dur/2
					
				},
				hide:{
					effect: 'drop',
					duration: dur/2					
				},
				close: function(){
					// Detect user in system
					jqAJAX(98, 3, user_ip);
					// SelectMenu.Free
					if( Number(actTp) == 5 || Number(actTp) == 7 ) {
						$('#c_dpdCat').selectmenu( "destroy" );
					}
					$(this).dialog( "destroy" );
					$('#genIdx').menu( "destroy" );
				},
				buttons: [
					{
					text: btnCapt,
					click: function(){
							
							switch ( Number(actTp) ){
								
								case 1: post_str = $('#usr').val() + "|" + $('#passwd').val() + "|" + user_ip; break;
								case 2:	post_str = $('#usr').val() + "|" + $('#passwd').val() + "|" + user_ip; break;
								
								case 4:	post_str = $('#c_dnnm').val(); break;
								case 5: post_str = $('#c_dpdCat').val(); break;
								
								case 6:	post_str = $('#c_dnnm').val(); break;
								case 7: post_str = $('#c_dpdCat').val(); break;
							}
							
							//alert(post_str);
						
							jqAJAX(actTp, 1, post_str);
							$(this).dialog("option", "buttons", [  ]);
							$('#' + c_dlg ).hide();
						}
					}
					 ,{
					text: 'Отмена',
					click: function(){
							$(this).dialog( "close" );
						}
					} 
					]
					
				});
				// DIALOG.END
				
				// Prepare DropDown for DEL action
				if( Number(actTp) == 5 || Number(actTp) == 7 ) {					
				// SelectMenu.onCreate()
				
					// Read existing CATegories and GENers for DEL action
					jqAJAX(97, 1, actTp); 
					$('#c_dpdCat').html(select_blank);
					
					// Fix MenuWidget Z-POS
					$('#c_dpdCat').selectmenu({ 
						width: 250,
						open: function( event, ui ) {
								$('#c_dpdCat').data('dialogZ', $('#log-in').parent().zIndex());
								$('.ui-selectmenu-open').zIndex( $('#log-in').zIndex()+1 );
								$('.ui-selectmenu-open').css({'overflow':'visible'});
							}
						});
											
				}
				
				// Prepare Menu for AddGenere
				if( Number(actTp) == 8 ){ 
					// Read existing GENers for ADD MOVIE action
					jqAJAX(96, 1, ""); 
					// Display and log added generes
					$('#genIdx').on( "menuselect", function( event, ui ) {
						$('#m_gen').append( '<div class="ui-state-default btnTitleFix genMov" gid="'+ ui.item.attr('gen_id') +'">' + ui.item.text() + '</div>' );
						$('.genMov').click(function(){	$(this).remove(); });
						$(this).hide();
					});
				}
				
				parts_idx = [0, 0, 0, 0];				
			});
			//  DialogHANDLER.END //
			
			// POSiting GENMenu
			$('#dlgGens').click(function(){
				$('#genIdx').css({'left' : $(this).position().left + 'px' })	.slideDown(dur/2);
			});
			// POS.END
			
			// LGOUT handler //			
			$("#outAct").click(function(){
				jqAJAX(3, 1, c_usr + "|" + c_pass);
				$('#tbLogin').show();
				$('#tbLogOUT').hide();
			});
			// LOGOUT handler end //
			
			// Displaying PARTS
			$('.add_part_tp').click(function(){
				
					if ( Number( $(this).attr('ptp') ) == 1 ){
						parts_idx[0] += 1;
						$( '#ptp' + $(this).attr('ptp') ).append('<div>s'+ parts_idx[0] +' e1-'+ $('#part_leng').val() +'</div>');
					} else {
						parts_idx[ Number($(this).attr('ptp'))-1 ] += 1;
						for (i=1; i <= Number( $('#part_leng').val() ); i++){
							$( '#ptp' + $(this).attr('ptp') ).append('<div>' + $(this).attr('prefx') + '-' + i +'</div>');
						}
						
					}
			});
			// PARTS.END
			
			// STYLING ELEMENTS //
			
			$("#loginAct").button({
			icons: { primary: "ui-icon-key" } 
			});
			$("#regAct").button({
			icons: { primary: "ui-icon-contact" } 
			});
			$("#outAct").button({
			icons: { primary: "ui-icon-extlink" }, text: false 
			});
			$("#newCollectAct").button({
			icons: { primary: "ui-icon-circle-plus" }, text: false 
			});
			$("#delCollectAct").button({
			icons: { primary: "ui-icon-circle-minus" }, text: false
			});
			$("#newGenerAct,#dlgGens").button({
			icons: { primary: "ui-icon-plusthick" }, text: false 
			});
			$("#delGenerAct").button({
			icons: { primary: "ui-icon-minusthick" }, text: false
			});
			$("#addNewMovie").button({
			icons: { primary: "ui-icon-video" }, text: false
			});
			$("#refrshMovie").button({
			icons: { primary: "ui-icon-refresh" }, text: false
			});			
			
			$('.add_part_tp').button();
			$('#part_leng').spinner({
				min: 1,
				step: 1,
				start: 1
			});
			$("#m_state").buttonset().css({'font-size':'10px'});			
			$(document).tooltip();			
			// Styling END //			
			
			
			// INIT FUNCTIONS CALLS
			if (init_func[0]){
				// Init Dbengine, if not present
				jqAJAX(0, 0, "");
			}
			if (init_func[1]){
				// Spoil admin data, if localhost
				jqAJAX(99, 0, "");
			}
			if (init_func[2]){
				//Detect user in system
				jqAJAX(98, 3, user_ip);
			}
			var select_blank = $('#c_dpdCat').html();
			// init functions END //	
			
		});