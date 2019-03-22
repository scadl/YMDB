        var dur=550;
		var user_grp = "";
		var c_usr = "";
		var userid = 0;
		var user_ip = "";
		var ok_str = '';
		var dlgHg = 0;
		var dlgWd = 0;
		var dlgCap = "";
		var parts_idx = 0;
		var filter_cat = 0;
		var step = 5;
		var page = 0;
		var rowcount = 0;
		var sel_collect = 1;
		var ajax_open = false;
		var reqests = 0;
		var init_func = [false, false, false, false];		
		        
        $(function() {
						
			// MAIN AJAX HANDLER //
			 function jqAJAX(act, respTp, sendDat){
			 /* respTp - Response type
			 0 - Console type response (text\html)
			 1 - Dialog content response (ui-block\html)
			 2 - Catalog Row response (table-row\html) 
			 3 - Login state response (value\user_name)
			 */
                
				if ( ajax_open ){
					reqests += 1;
					setTimeout( function(){ jqAJAX(act, respTp, sendDat)}, 500*reqests);
					console.log('Запрос в ожидании! - act:"'+act+'" respTp:'+respTp);
				} else {
					ajax_open = true;
				
				console.log('Запрос - act:"'+act+'" respTp:"'+respTp+'" sendDat:"'+sendDat+'"');
				
                $.ajax({
                    method: "POST",
                    url: 'processor_lines_img.php',
                    data:{
                        action: act,
                        procdata: sendDat
                    },
                    beforeSend: function(xhr){
                        //console.log('Обрабтываю запрос...');
						 $('#status_ico').attr( "src", 'img/loader_s.gif' );
						 $('#status_ico').attr( "title", 'Запрос обрабатывается' );
                    }
                }).done(function(respData){
					
					switch (respTp){
						case 0: 
							$('#status').text( respData ).show();
							if (init_func[3]){
								jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect + '|' + userid); //READ MOVies and COLLection
							}
						break;
						case 1:
							$('#login_script').html(respData);
							jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect + '|' + userid); //READ MOVies and COLLection	
							//$('#c_filtCat [value="'+filter_cat+'"]').attr("selected", "selected");
							//$('#c_collCat [value="'+sel_collect+'"]').attr("selected", "selected");								
						break;
						case 2:
							
							$('#MOVIES').html(respData);							
							$('#c_filtCat [value="'+filter_cat+'"]').attr("selected", "selected");
							$('#c_collCat [value="'+sel_collect+'"]').attr("selected", "selected");
							
							// $('#c_filtCat [value="'+filter_cat+'"]').prop('selected', true);
						break;
						case 3:
							//alert(respData);
							if (respData != ''){
								
								var userData = respData.split("|");
								
								userid = userData[0];
								c_usr = userData[1];
							//  password = userData[2];
								user_grp = userData[3];	
							//  ip = userData[4];
								sel_collect = userData[5];
								filter_cat = userData[6];
								step = userData[7];
								page = userData[8];								
														
								$('#tbLogin').hide();
								$('#tbLogOUT').show();
								
								if ( user_grp == 'owner' ){
									$('#tbSecCtrl').show();
								}
							
								$('#cur_usr').html( c_usr );
								$('.step_pos').html( page );
								//$('#c_limitCat :contains("'+step+'")').attr("selected", "selected");
								$('#c_limitCat select option[value="' + step + '"]').html();
							} else {
								sel_collect = 1;
								filter_cat = 0;
								step = 5;
								page = 0;		
							}
							if (init_func[3]){
								//jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect  + '|' + userid);	//READ MOVies and COLLection			
								jqAJAX(97, 1, '7|c_filtCat|'+filter_cat); // GET Generes
								jqAJAX(97, 1, '5|c_collCat|'+sel_collect); // GET Collections
							}
							//$('#c_filtCat>option[value="'+filter_cat+'"]').attr("selected", "selected");
							//$('#c_collCat [value="'+sel_collect+'"]').attr("selected", "selected");
						break;
					}
					
					console.log('Выполнен запрос: '+act+'!');
					ajax_open = false;
					reqests -= 1;
					if(reqests <= 0){
						$('#status_ico').attr( "src", 'img/ok.png' );
						$('#status_ico').attr( "title", 'Запрос ОК' );	
					}
										
					if (respData.indexOf('database is locked')!==-1){
						//setTimeout( jqAJAX(act, respTp, sendDat), 5000);
						console.log('Ошибка при обработке запроса сервером!');
						console.log('Запрос - act:"'+act+'" respTp:"'+respTp+'" respDat:"'+respData+'"');
					} else {						

					}
					
					if (act!==96){
						console.log('Запрос - act:"'+act+'" respTp:"'+respTp+'" respDat:"'+respData+'"');
					} else {
						console.log('Запрос - act:"'+act+'" respTp:"'+respTp);
					}
					
                }).fail(function(){
					
							$('#status').css({"color":"red"});
							$('#status').html("<div > Ошибка при обработке запроса </div>" );
							//$('#status').hide();
                });
				
				}
                
            };
			// AJAX handler END //
			
			
			function InitDialog(wd, hg, cap){
				//alert('dialog');
				$('#log-in').css({
					'width': wd + 'px',
					'top' : (  $(window).scrollTop()  + ( $(window).height() / 2 )  - ( hg / 2 ) ) + 'px',
					'left' : ( ( $(window).width() / 2 ) - ( wd / 2 ) ) + 'px'
				});
				$('#dlgBkg').height( $(document).height() );
				$('#dlgBkg').width( $(document).width() );
				
				$('#dlgCapt div:nth-child(2)')
				.html( cap )
				.css({'width':(wd - 54) +'px'});
				
			}
			
			
			// Dialog Handler //
			$(".dlg_btn").click(function(){
								
				dlgHg = $(this).attr('dlgDem').split('x')[1];
				dlgWd = $(this).attr('dlgDem').split('x')[0];
				dlgCap = $(this).attr('DlgCap');
				
				InitDialog( dlgWd , dlgHg , dlgCap );
				
				$('#dlgBkg').fadeIn( dur );
				
				var form_nm = $(this).attr('form_nm');
				var form_act = $(this).attr('actType');
					
				$(".dlg_resp").hide();	
				$(".forms").hide();
				$('#' + form_nm ).show();
				$('input[type="text"]').val('');				
				$('input[type="password"]').val('');
				$('#part_leng').val('1');
				$('#NmID').val('1');
				
				$('.mv_cell').html('');		
				$('#c_dpdCat').html('');
				$('#g_exCat').html('');
				$('#c_AddCat').html('');
				$('#m_gens').html('');
				
				$('#ID_spin').hide();
				$('#m_nm').attr('size','100');
								
				// Prpare Collection.Form
				if ( form_nm == 'collectForm' ){
					
					$('#' + form_nm + ' :button').each(function(idx){
						$(this).attr('actType', form_act.split('|')[idx] );
						$(this).css({'border-radius':'25px'});
					});
					
					$('#c_wait').show();
					$('#c_dpdCat').html('');
					jqAJAX(97, 1, form_act.split('|')[1] + '|c_dpdCat'); // GET Collections
				}
				// collectionForm.end
				
				
				if ( form_nm == 'movieForm' ){					
					$('#g_exCat').html('');
					$('#mID').val( "" );
					$('#cID').val( "" );
					$('#m_nm').attr('style','width:600px;');
					$("#btnSubmit").html('<b>Добавить!</b>').attr('actType','8');				
					jqAJAX(97, 1, 7 + '|g_exCat|'+filter_cat); // GET Geners
					jqAJAX(97, 1, 5 + '|c_AddCat|'+sel_collect); // GET Geners
				}
				
								
				// DIALOG.Create();	
				$('#log-in')
				.show({
					effect: 'drop',
					duration: dur/2					
				});
							
			});
			//  DialogHANDLER.END //
			
			
			// dlgClose.click
			$('#dlgBkg, #dlgClose').click(function(){
				$('#dlgBkg').fadeOut( dur );
				$('#log-in').hide({
					effect: 'drop',
					duration: dur/2					
				});
				jqAJAX(98, 3, user_ip);
				parts_idx = 0;
			});
			// dlgClose.end
			
			
			// dlgActionBtn.click
			$('.btnAction').click(function(){
				
				$('.dlg_resp').hide();
				
				var post_str = "";
				var actTp = $(this).attr('actType');

				switch ( Number(actTp) ){
								
					case 1: case 2:
						post_str = $('#usr').val() + "|" + $('#passwd').val() + "|" + user_ip; 
					break;
								
					case 4:	case 6:
						post_str = $('#c_dnnm').val(); 
					break;
					
					case 5: case 7:
						post_str = $('#c_dpdCat').val();
					break;
					
					
					case 8: case 9:
						
						post_str = $('#c_AddCat').val() + '|' + $('#m_nm').val() + '|' + $('#m_url').val() + '|' + $('input[type="radio"]:checked').attr('rate') + '|';
						
						$('#m_gens > option').each(function(){
							post_str += $(this).val() + '/';
						});
						
						post_str += '|';
						
						$('.ep').each(function(){
							var c_ep = $(this).text().split('-');
							post_str += c_ep[0] + '!' + c_ep[1] + '!' + $(this).attr('state') + '/';
						});						
						
						if( Number(actTp) == 9 ){
							post_str += '|' + $('#mID').val();
							post_str += '|' + $('#NmID').val();
							post_str += '|' + $('#Old_cID').val();
						}
						
						post_str += '|' + $('#img_url').val();
						
					break;
					
					case 10:
						post_str = $(this).text() ;
					break;
				
				}
							
				jqAJAX(actTp, 1, post_str);
				//$('#dlgClose').click();
				
			});
			// dlgActionBtn.end
					
			
			// GNERS.list handle
			$('#btnAddGen').click(function(){
				$('#m_gens').append( '<option value="'+ $('#g_exCat').val() +'"> '+ $('#g_exCat > option:selected').text() +' </option>' );
			});
			$('#btnRemGen').click(function(){				
				$('#m_gens > option[value="'+ $('#m_gens').val() +'"]').remove(  );
			});
			// GENERS.list END
			
			
			// LGOUT handler //			
			$("#outAct").click(function(){
				jqAJAX(3, 1, userid);
				$('#tbLogin').show();
				$('#tbLogOUT').hide();
				$('#tbSecCtrl').hide();
				user_grp = "";
			});
			// LOGOUT handler end //
			
			
			// Displaying PARTS
			$('.add_part_tp').click(function(){
				
						
						if ( $(this).attr('prefx') == "E" ){ 
							parts_idx = Number(parts_idx) + 1;
						}
							
						$( '#cl_' + $(this).attr('prefx') )
						.append('<span class="fa fa-clock-o"></span>'+
						'<div class="ep state_0" state="0">S'+ parts_idx + ' ' + $(this).attr('prefx') + '1-' + $('#part_leng').val() +'</div>'+
						'<br>');

						
						$( ".ep" ).bind( "click", function() { 
							$('.ep').each(function(){
								$(this).removeClass('epSel');
								$(this).css({'background-color':'transparent'});
							});
							$(this).addClass('epSel');
							$(this).css({'background-color':'silver'});
						});

					
					InitDialog( dlgWd , Number( dlgHg ) + Number( $('#eps_tb').height() ) , dlgCap );
					
			});
			// PARTS.END		
			
			
			// STATES.modifiers
			$('.state_set').click(function(){
				
				var stt = $(this).attr('ptp');
				
				fa_icon = '';
				switch ( Number(stt) ){
					case 1: fa_icon = 'fa-trash-o'; break;
					case 2: fa_icon = 'fa-floppy-o'; break;
					case 4: fa_icon = 'fa-eye-slash'; break;
					case 5: fa_icon = 'fa-eye'; break;
				}
				
				switch( Number( stt ) ){
					case 1: case 2: case 4: case 5:
						$('.epSel')
						.attr('class', 'ep')
						.addClass('state_'+stt)
						.attr('state', stt )
						.prev('span')
						.attr('class', 'fa '+fa_icon)
						;
					break;
					case 3:
						$('.epSel')
						.remove()
						.prev('span')
						.remove();
					break;
				}
				
			});
			// STATES.end
			
			
			// SPINNER handler
			$('.spin').click(function(){
				if ( $(this).attr('oper')=='inc' ){
					var spval = Number( $('#'+$(this).attr('tg') ).val() );
					$('#'+$(this).attr('tg') ).val( spval+=1 );
				} else {
					
					var spval = Number( $('#'+$(this).attr('tg') ).val() );
					if ( (spval-1) <= 1 ){
						$('#'+$(this).attr('tg') ).val( 1 );
					} else {
						$('#'+$(this).attr('tg') ).val( spval-=1 );
					}

					
				}
			});
			// SPINNER.end
			
			// GENERS FILTER handler
			$('#c_filtCat').change(function(){
				//alert( $(this).val() );
				filter_cat = $(this).val();
				jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect + '|' + userid); //READ MOVies and COLLection
			});
			// GENERS.end
			
			// STEP handler
			$('#c_limitCat').change(function(){
				step = $(this).val();
				jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect + '|' + userid); //READ MOVies and COLLection
			});
			// STEP.end
			
			// COLLection.Select handler
			$('#c_collCat').change(function(){
				sel_collect = $(this).val();
				jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect + '|' + userid); //READ MOVies and COLLection
			});
			// COLLection.Select.end
			
			// PAGE handler
			$('.step_btn').click(function(){
				if ( $(this).attr('direct')=='+' ){
					page++; 
					if ( rowcount >= step ){						
						$('.step_pos').text(page);
					}else{
						page--;
					}
					$('.step_pos').text(page);
					console.log('Строк на странице: '+rowcount);
				} else{
					page--;
					if ( page>-1 ){						
						$('.step_pos').text(page);
					}else{
						page=0;
					}
				};
				jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect + '|' + userid); //READ MOVies and COLLection
			});
			// PAGE.end
						
			// Refresh Catalog List
			$("#movRefresh").click(function(){
				jqAJAX(96, 2, user_grp + '|' + filter_cat + '|' + step + '|' + page + '|' + sel_collect + '|' + userid); //READ MOVies and COLLection
				jqAJAX(97, 1, '7|c_filtCat|'+filter_cat); // GET Generes
				jqAJAX(97, 1, '5|c_collCat|'+sel_collect); // GET Collections
			});
			// Refresh.end
			
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