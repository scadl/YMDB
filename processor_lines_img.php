<?php

re:

switch( $_POST['action'] ){

    case 0:
        // Initalizing engine: Creating database, register admin, ...
		
		$dpass = 1;
		for ($i=0; $i<=7; $i++){
			$dpass+=rand(1, 597)*$i;
		}
		
		$db = new SQLite3('yourmovies.db');
		$qres = $db->exec('CREATE TABLE IF NOT EXISTS geners (id INTEGER PRIMARY KEY, g_name TEXT);') or die("Error in query: <span style='color:red;'>".$qres."</span>");
		$qres = $db->exec('CREATE TABLE IF NOT EXISTS collect (id INTEGER PRIMARY KEY, c_name TEXT, visible NUMERIC);') or die("Error in query: <span style='color:red;'>".$qres."</span>");
		$qres = $db->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, user TEXT, passwd TEXT, grp TEXT, ip TEXT, last_coll INTEGER, last_filter INTEGER, last_step INTEGER, last_page INTEGER);');	
		$qres = $db->exec('INSERT INTO users (user, passwd, grp) VALUES ("Admin", "'.$dpass.'", "owner");');			

		if ( !( $qres ) )
			{ print('Error in User init'); }
		
        print('Движок инициализирован <br> Теперь можно войти в систему, <br> с аккаунтом: [Admin] ['.$dpass.']');
		
		unset($db); unset($qres); unset($dpass);
    break;
	
	case 1:
		// Login into system
		
		$post_data = explode('|', $_POST['procdata']);
		
		$db = new SQLite3('yourmovies.db');
		$dbdata = $db -> query('SELECT user, passwd FROM users WHERE user="'.$post_data[0].'" AND passwd="'.$post_data[1].'";');
		
		if( !$dbdata -> fetchArray(SQLITE3_NUM) ){
				print('	<script> 
					$("#login_err").show(); 
					
				</script>');
			} else {
				print('	<script> 
					$("#login_ok").show(); 
					$("#loginForm").hide();
				</script>');
				$dbdata = $db -> query('UPDATE users SET ip="'.$post_data[2].'" WHERE user="'.$post_data[0].'" AND passwd="'.$post_data[1].'"');
			}
		unset($db); unset($dbdata);	unset($post_data);		
	break;
	
	case 2:
		// Register in system
		
		$post_data = explode('|', $_POST['procdata']);
		
		$db = new SQLite3('yourmovies.db');
		$dbdata = $db -> query('SELECT user, passwd FROM users WHERE user="'.$post_data[0].'";');
		
		if( !$dbdata -> fetchArray(SQLITE3_NUM) ){
				print('	<script> $("#reg_ok").show(); </script>');
				$db -> query('INSERT INTO users (user, passwd, grp) VALUES ("'.$post_data[0].'", "'.$post_data[1].'", "visitor");');
			} else {
				print('	<script> $("#reg_err").show(); </script>');
			}
		unset($db); unset($dbdata);	unset($post_data);		
	break;	
	
	case 3:
		// LogOUT from system
		
		//$post_data = explode('|', $_POST['procdata']);
		$post_data = $_POST['procdata'];
				
		$db = new SQLite3('yourmovies.db');
		$db -> query('UPDATE users SET ip="" WHERE id="'.$post_data.'";');
			
		unset($db); unset($post_data);				
	break;
	
	case 4:
		// ADD a NEW COLLECTion
		
		$post_data = explode('|', $_POST['procdata']);
		
		$db = new SQLite3('yourmovies.db');
		$dbdata = $db -> query('SELECT c_name FROM collect WHERE c_name="'.$post_data[0].'";');
		
		if( !$dbdata -> fetchArray(SQLITE3_NUM) ){
				print('	<script> $("#c_add_ok").show(); </script>');
				
				$db -> query('INSERT INTO collect (c_name, visible) VALUES ("'.$post_data[0].'", 1);');
			  //$db -> query('UPDATE users SET vis_coll = vis_coll || "|" || ( SELECT id FROM collect WHERE c_name="'.$post_data[0].'" ) WHERE user="'.$post_data[1].'";');
				
				$row1 = $db -> query('SELECT id FROM collect WHERE c_name="'.$post_data[0].'"') -> fetchArray(SQLITE3_NUM);
				
				$db -> query('CREATE TABLE clln_'.$row1[0].' (id INTEGER PRIMARY KEY, name TEXT, url TEXT, rating INTEGER, geners_ids TEXT, eps_arr TEXT, image_url TEXT);');
				//$db -> query('CREATE TABLE clln_'.$row1[0].'_ep (id INTEGER PRIMARY KEY, mov_id TEXT, eps_name TEXT, ep_leng INTEGER, state NUMERIC);');
				
			} else {
				print('	<script> $("#c_add_err").show(); </script>');
			}
		unset($db); unset($dbdata);	unset($post_data); unset($row1);		
	break;	
	
	case 5:
		// DELETE a CATegory
		
		$post_data = $_POST['procdata'];
		
		$db = new SQLite3('yourmovies.db');
		$db -> query('DELETE FROM collect WHERE id="'.$post_data.'";');
		$db -> query('DROP TABLE clln_'.$post_data.';');
		//$db -> query('DROP TABLE clln_'.$post_data.'_ep;');
		print('	<script> 
			$("#c_del_ok").show(); 
			$("option[value='.$post_data.']").remove();
		</script>');
		
		unset($db); unset($post_data);		
	break;	

	case 6:
		// ADD a NEW GENere
		
		$post_data = explode('|', $_POST['procdata']);
		
		$db = new SQLite3('yourmovies.db');
		$dbdata = $db -> query('SELECT g_name FROM geners WHERE g_name="'.$post_data[0].'";');
		
		if( !$dbdata -> fetchArray(SQLITE3_NUM) ){
				print('	<script> $("#c_add_ok").show(); </script>');				
				$db -> query('INSERT INTO geners (g_name) VALUES ("'.$post_data[0].'");');				
			} else {
				print('	<script> $("#c_add_err").show(); </script>');
			}
		unset($db); unset($dbdata);	unset($post_data);		
	break;	
	
	case 7:
		// DELETE a GENere
		
		$post_data = $_POST['procdata'];
		
		$db = new SQLite3('yourmovies.db');
		$db -> query('DELETE FROM geners WHERE id="'.$post_data.'";');
		print('	<script> 
			$("#c_del_ok").show();
			$("option[value='.$post_data.']").remove();
		</script>');
		
		unset($db); unset($post_data);		
	break;	
	
	case 8:
		// INSERT A NEW MOVie into DB
	
		// debug
		//print('	<script> alert("Adding - PHP: '.$_POST['procdata'].'"); </script>');
		
		// process
		$post_data = explode('|', $_POST['procdata']);
		
		$db = new SQLite3('yourmovies.db');
		$dbdata = $db -> query('SELECT name FROM clln_'.$post_data[0].' WHERE name="'.$post_data[1].'";'); //Check movie existing by name
		
		if( !$dbdata -> fetchArray(SQLITE3_NUM) ){
				$db -> query('INSERT INTO clln_'.$post_data[0].' (name, url, rating, geners_ids, eps_arr, image_url) VALUES ("'.$post_data[1].'", "'.$post_data[2].'", "'.$post_data[3].'", "'.$post_data[4].'", "'.$post_data[5].'", "'.$post_data[6].'");');				
				print('	<script> 
						$("#dlgBkg").click();
						//alert("OK! ADDed sucesfully..."); 							
					</script>');				
			} else {
				print('	<script> 
						//$("#c_add_err").show(); 
						alert("NO! Error. Already in DB"); 
					</script>');
			}
		unset($db); unset($dbdata);	unset($post_data);		
		
	break;
	
	case 9:
		// UPDATE a MOVie in DB
		
		// debug
		//print('	<script> alert("Updating - PHP: '.$_POST['procdata'].'"); </script>');
		
		// process
		$post_data = explode('|', $_POST['procdata']);
				
		$db = new SQLite3('yourmovies.db');
		
		if ( $post_data[0] != $post_data[8] ){
			$db -> query('DELETE FROM clln_'.$post_data[8].' WHERE id='.$post_data[6].';');	
			$db -> query('INSERT INTO clln_'.$post_data[0].' (name, url, rating, geners_ids, eps_arr) VALUES ("'.$post_data[1].'", "'.$post_data[2].'", "'.$post_data[3].'", "'.$post_data[4].'", "'.$post_data[5].'");');
		} else {
			$qres = $db -> query('UPDATE clln_'.$post_data[0].' SET id='.$post_data[7].', name="'.$post_data[1].'", url="'.$post_data[2].'", rating="'.$post_data[3].'", geners_ids="'.$post_data[4].'", eps_arr="'.$post_data[5].'", image_url="'.$post_data[9].'" WHERE id='.$post_data[6].';') or die ("<script> alert('".$qres."'); </script>");
		}
		
		print('	<script> 			
			$("#dlgBkg").click();
			//alert("Update OK!"); 
		</script>');
		
		unset($db); unset($dbdata);	unset($post_data);
		
	break;
	
	case 10:
		// DELETE a MOVie from DB
		
		// print('	<script> alert("Deleting - PHP: '.$_POST['procdata'].'"); </script>');
		
		$post_data = explode('|', $_POST['procdata']);
		
		$db = new SQLite3('yourmovies.db');
		$db -> query('DELETE FROM clln_'.$post_data[0].' WHERE id='.$post_data[1].';');	
		
		unset($db); unset($dbdata);	unset($post_data);
		
	break;
	
	case 96:
		// READ MOVies and COLLections
		
		$post_data = explode('|', $_POST['procdata']);
		
		if ( !isset($post_data[1]) || $post_data[1]==0 ){
			$filter_q = '';
		} else {
			$filter_q = ' WHERE geners_ids LIKE "%/'.$post_data[1].'/%" OR geners_ids LIKE "%/'.$post_data[1].'" OR geners_ids LIKE "'.$post_data[1].'/%"';
		}
		
		$limit_q = ' LIMIT '.$post_data[2];
		$limit_nm = $post_data[2];

		$offset_q = ' OFFSET '.($post_data[3]*$post_data[2]);
		$offset_nm = ($post_data[3]*$post_data[2]);
		
		$dbrowsC = 0;
		
		//print("<script> $('#status').html( <img src='img/loader_s.gif' width='16'> 'Processing...' ).show(); </script>");
		
		$db = new SQLite3('yourmovies.db');
		$db -> busyTimeout(50000);
		
		$db -> exec('UPDATE users SET last_coll='.$post_data[4].', last_filter='.$post_data[1].', last_step='.$post_data[2].', last_page='.$post_data[3].' WHERE id="'.$post_data[5].'";');
		
		$dbdata = array();
		$dbdata = $db -> query('SELECT * FROM collect WHERE id='.$post_data[4].';');
		while ( $c_row = $dbdata -> fetchArray(SQLITE3_BOTH) ){
			
			if ( $c_row['visible'] == '0' ){
				$coll_vis = 'display:none;';
				$fa_ficon = 'fa-folder';
			} else {
				$coll_vis = 'display:normal;';
				$fa_ficon = 'fa-folder-open';
			}
			
			$dbrows = $db -> query('SELECT COUNT(*) FROM clln_'.$c_row['id'].$filter_q.';')->fetchArray(SQLITE3_BOTH)[0];
			//$dbrowsC = $db -> query('SELECT COUNT(*) FROM clln_'.$c_row['id'].$offset_q.';')->fetchArray(SQLITE3_BOTH)[0];
			
			print('<table width="100%" border="0" >
				   <tr class="cat_head_dis" COLLID="'.$c_row['id'].'" collvis="'.$c_row['visible'].'">
						<td align="center" colspan="5" valign="middle"> 
							<span class="fa '.$fa_ficon.' facoll_'.$c_row['id'].'" style="display:inline-block"></span>
							<span style="font-size: 15pt; font-weight:bold;">'.$c_row['c_name'].' ['.$dbrows.'] </span> 
						</td>
					</tr>
					</table>
					');
			
			$rowdata = $db -> query('SELECT * FROM clln_'.$c_row['id'].$filter_q.$limit_q.$offset_q.';');
			while ( $m_row = $rowdata -> fetchArray(SQLITE3_BOTH) ){
					
					$dbrowsC++;
					$epS = 0;	$epNm = ""; 
					$ep_tb = explode('/', $m_row['eps_arr']);
					for( $i = 0; $i < count($ep_tb)-1; $i++ ){
						$epDat = explode('!', $ep_tb[$i]);
						$epS += $epDat[1];
						switch ( $epDat[2] ){
							case 0: $fa_icon = 'fa-clock-o'; break;
							case 1: $fa_icon = 'fa-trash-o'; break;
							case 2: $fa_icon = 'fa-floppy-o'; break;
							case 4: $fa_icon = 'fa-eye-slash'; break;
							case 5: $fa_icon = 'fa-eye'; break;
						}
						$epNm .= '
						<div class="state_'.$epDat[2].' epC_'.$c_row['id'].'-'.$m_row['id'].' non-rated" state="'.$epDat[2].'"
						style=""><span class="fa '.$fa_icon.' state_'.$epDat[2].'" style="padding-right:3px;"></span>' . $epDat[0] . '-' . $epDat[1] . '</div> ';
					}
					$genNm = "";
					$genId = explode('/', $m_row['geners_ids']);
					for( $i = 0; $i < count($genId)-1; $i++ ){
						//$rclr = array( rand(50,120), rand(50,120), rand(50,120));
						//$trclr= $rclr[0].','.$rclr[1].','.$rclr[2];
						//$brclr= ($rclr[0]+135).','.($rclr[1]+135).','.($rclr[2]+135);
						$gen1 = $db -> query('SELECT * FROM geners WHERE id='.$genId[$i].';') -> fetchArray(SQLITE3_NUM)[1];
						$genNm .= '<div class="genC_'.$c_row['id'].'-'.$m_row['id'].' genCap" gid="'.$genId[$i].'" 
						><span class="fa fa-puzzle-piece" style="padding-right:3px;"></span>'. $gen1 .',</div> ';
					}
					
					switch( $m_row['rating'] ){
						case -2: $rateStr = 'Статус: Планирую смотреть';	 break;
						case -1: $rateStr = 'Статус: Скачал / Подготовил';	 break;
						case 1:	$rateStr = 'Оценка: Ужасно';	 break;
						case 2:	$rateStr = 'Оценка: Терпимо';	 break;
						case 3:	$rateStr = 'Оценка: Средне';	  break;
						case 4:	$rateStr = 'Оценка: Хорошо';	 break;
						case 5:	$rateStr = 'Оценка: Отлично';	  break;
					}
					
					$offset_nm++;
					
					if ( stripos($m_row['image_url'], "jpg") || stripos($m_row['image_url'], "jpeg") ) {
						$img_url = $m_row['image_url'];
					} else {
						$img_url = "img/no_image.png";
					}
					
					print('
					
					<table border="0" width="100%">
						<tr>
							<td rowspan="2" width="120" class="rated_'.$m_row['rating'].' rwCell rated_title" >
								<img src="'.$img_url.'" height="110" class="thumb_frame name_row mimg_'.$c_row['id'].'-'.$m_row['id'].'" img_url="'.$img_url.'">
							</td>
					<td>
					
					<table width="100%" border="0" style="height:63px"  cellspacing="0">
					<tr class="rowid_'.$c_row['id'].'-'.$m_row['id'].'" style="'.$coll_vis.'"> 
					
					<td valign="middle" align="center" class="rwCell rated_title rated_'.$m_row['rating'].'" style="font-size: 12pt; width:20px"> 
					'.$offset_nm.' 
					</td>
					
					<td width="2px"></td>
					
					<td valign="middle" align="left" class="rwCell rated_title rated_'.$m_row['rating'].' mrow_'.$c_row['id'].'-'.$m_row['id'].'" style="font-size: 12pt; "> 					 
					<div class="rated_'.$m_row['rating'].' rated_title" title="'.$rateStr.'"> ');
					
					if ( stristr($m_row['url'], 'http') ){
						print('
					
						<a 
						  class="murl_'.$c_row['id'].'-'.$m_row['id'].' mvurl rated_'.$m_row['rating'].'" 
						  href="'.$m_row['url'].'" target="_blank" style="text-decoration: none;">
							<span class="fa fa-globe" title="О фильме в сети (dbID: '.$m_row['id'].')"></span>
							<span class="mnm_'.$c_row['id'].'-'.$m_row['id'].'">'.$m_row['name'].' </span>
						  </a>
						');
					}else {
						print('<span class="mnm_'.$c_row['id'].'-'.$m_row['id'].'">'.$m_row['name'].' </span>');
					}
					
					print('
						  
						
					</div>
					</td>
					
					<td width="2px"></td>
					
					<td valign="middle" align="center" class="rwCell rated_title rated_'.$m_row['rating'].'" width="70">
					<div title="Всего Эпизодов" class="mveps"><span class="fa fa-film"></span> '.$epS.' </div>				
					</td>
					
					</tr></table>
					
					</td></tr>	<tr><td>
					
					<table width="100%" border="0" style="height:63px"  cellspacing="0">
					<tr class="rowid_'.$c_row['id'].'-'.$m_row['id'].'" style="'.$coll_vis.'">
					
					<td valign="middle" align="center"  class="rwCell rated_title rated_'.$m_row['rating'].'" title="'.$rateStr.'">
					'. $genNm .'</td>

					<td width="2px"></td>
					
					<td valign="middle" align="center" class="rwCell rated_title rated_'.$m_row['rating'].'" title="'.$rateStr.'">
					'. $epNm .'</td>

					
					');
					
					if ( $post_data[0] == 'owner' ){
					print('
					<td width="20" style="padding-left: 3px; border-bottom: solid 1px silver;" valign="middle" align="center">
						<div style="display:inline-block">
						<button class="iaElem rowEd" dbid="'.$c_row['id'].'-'.$m_row['id'].'" rate="'.$m_row['rating'].'"> 
						<img src="img/eBtn.png" width="18">
						</button> 
						</div> <br> <div style="display:inline-block">
						<button class="iaElem rowDel" dbid="'.$c_row['id'].'-'.$m_row['id'].'" style="margin-top:3px;">
						<img src="img/eBtn.png" width="18">
						</button>						
						</div>
					</td>
					');
					}
					
					print('
					</tr></table>		
						
					</td></tr></table>					
					');
				}
			
			Print('');
			
			//$dbdata = NULL; // closes the connection
			
		}
		
		?> <script>
			rowcount = <?php print($dbrowsC); ?>;
			$(function() {
					$(".rowEd").click(function(){
						
						dbid = $(this).attr('dbid');
						$("button[form_nm='movieForm']").click();
						
						$('#m_nm').val( $('.mnm_' + dbid).text() );
						$('#m_url').val( $('.murl_' + dbid).attr('href') );
						$('#img_url').val( $('.mimg_' + dbid).attr('img_url') );
						$("input[rate="+  $(this).attr('rate') +"]").click();
						$("#m_gens").html('');
						$('.genC_'+dbid).each(function(){
							$("#m_gens").append( "<option value='"+$(this).attr('gid')+"'> "+$(this).text()+" </option>"  );
						});												
						$('.epC_'+dbid).each(function(ind){
								
								fa_icon = '';
								switch ( Number( $(this).attr('state') ) ){
									case 0: fa_icon = 'fa-clock-o'; break;
									case 1: fa_icon = 'fa-trash-o'; break;
									case 2: fa_icon = 'fa-floppy-o'; break;									
									case 4: fa_icon = 'fa-eye-slash'; break;
									case 5: fa_icon = 'fa-eye'; break;
								}
							
							 prefix = $(this).text().split(' ')[1].split('1-')[0] ; 		 //alert(prefix);
							 $("#cl_"+prefix).append( '<span class="fa '+fa_icon+'"></span>'+
							 '<div class="ep state_'+$(this).attr('state')+'" state="'+$(this).attr('state')+'">'+ $(this).text() +'</div><br>' 
							 );							 				
							
							$( ".ep" ).bind( "click", function() { 
							$('.ep').each(function(){
								$(this).removeClass('epSel');
								$(this).css({'background-color':'transparent'});
							});
							$(this).addClass('epSel');
							$(this).css({'background-color':'silver'});
						});
						});												
						$("#btnSubmit").html('<b>Обновить...</b>').attr('actType','9');
						//$("#c_AddCat").val(1 );
						$('#mID').val( $(this).attr('dbid').split('-')[1] );					
						$("#cID").val( $(this).attr('dbid').split('-')[0] );
						$('#Old_cID').val( $(this).attr('dbid').split('-')[0] );
						$('#NmID').val( $(this).attr('dbid').split('-')[1] );
						$('#dlgCapt div:nth-child(2)').html( 'Обновить фильм' );
						$('#ID_spin').show();
						$('#m_nm').attr('style','width:510px;');
						InitDialog( dlgWd , Number( dlgHg ) + Number( $('#eps_tb').height() ) , dlgCap );	
						
					});
										
					$(".rowDel").click(function(){
						$('.rowid_' + $(this).attr('dbid') ).remove();
						$('#fakeBtnDel').text( $(this).attr('dbid').split('-')[0] + '|' + $(this).attr('dbid').split('-')[1] );
						$('#fakeBtnDel').click();
						
					});
					
																					
					//$('.mveps').each(function(ind){			$(this).next().next().css({'width': ($(this).width() + 23) + 'px'}); 			});
					
					// Attach IMAGE Handler
					$('.name_row').hover(
					function(){
						// MouseEnter
						//alert( $(this).attr('img_url') );
						var img_url = $(this).attr('img_url');
												
						if ( img_url.indexOf('jpg')>=0 || img_url.indexOf('jpeg')>=0 ){
							//$("#image_frame").attr("src", 'img/loader.gif').attr("src", img_url);
							
							$.ajax({
								url: "img_importer.php?img_url="+img_url,
							    cache: true,
								processData : false,
								beforeSend: function() {
									$("#image_frame").attr("src", 'img/loader.gif');
								}
							}).always(function() {
								$("#image_frame").attr("src", img_url);
								if ( $("#image_frame").height() > ($( window ).height() / 2) ){
									$("#image_frame").css({"height": ($( window ).height() / 2) + "px"});
								}
								if ( $("#image_frame").height() + $(this).scrollTop() + 45 > $( window ).height() + $(window).scrollTop() ){
										$("#image_frame").css({	"top": ( ($( window ).height() + $(window).scrollTop()) - ($("#image_frame").height()+25))+"px"
									});
								}
							});
														
						} else {
							$("#image_frame").attr("src", 'img/no_image.png');
						}
						
						$("#image_frame").show();
						

						
					}, function(){
						// MouseLeave
						$("#image_frame").hide();
						$("#image_frame").css({"height":"auto"});
					});
					
					$('.name_row').mousemove(function(ev){
						
						// MOVING frame BINDED to cursor
						$("#image_frame").css({
							"top": (ev.pageY+25)+"px",
							"left": (ev.pageX+15)+"px"							
						});
						//,"height": "550px"
						
						//console.log( $("#image_frame").height() + ev.pageY + 25 );
						if ( $("#image_frame").height() + ev.pageY + 45 > $( window ).height() + $(window).scrollTop() ){
							$("#image_frame").css({
								"top": ( ($( window ).height() + $(window).scrollTop()) - ($("#image_frame").height()+25))+"px"
							});
						}
						
						if ( $("#image_frame").height() > ($( window ).height() / 2) ){
									$("#image_frame").css({"height":"550px"});
						}
						
						/*
						// FIXED Frame POSITION by right border 
						$("#image_frame").css({
							"top": ( $(window).scrollTop() + ($( window ).height()/2) - ($("#image_frame").height() / 2) )+"px",
							"right": "55px"
						});
						*/
						
					});
					
					/*
					var ifmfrm = [];
					var imurl = [];
					
					$(".thumb_frame").each(function(ind){
							ifmfrm[ind] = $(this);
							imurl[ind] = $(this).attr("img_url");
							$.ajax({
								url: "img_importer.php?img_url="+imurl[ind],
							    cache: true,
								processData : false,
								beforeSend: function() {
									ifmfrm[ind].attr("src", 'img/loader.gif');
								}
							}).fail(function( jqXHR, textStatus ) {
								alert('error');
							}).always(function() {
								ifmfrm[ind].attr("src",  "img_importer.php?img_url="+imurl[ind]);
							});
							
							//alert($(this).attr("img_url"));
					});
					*/
					$('#status').hide();
				
				});	
				
					</script>
			
		<?php
		$db->close();
		unset($db); unset($dbdata); unset($rowdata); unset($post_data); unset($c_row); unset($m_row); 
		unset($epS); unset($epNm); unset($ep_tb); unset($epDat); unset($genNm); unset($genId); unset($gen1);
	break;	
	
	case 95:
		// UPDATE Collection VISIBILity state
		print('	<script> alert("Visibility set: '.$post_data.'"); </script>');
		
		$post_data = explode('|', $_POST['procdata']);				
		$db = new SQLite3('yourmovies.db');
		
		$qres = $db -> query('UPDATE collect SET visible="'.$post_data[1].'" WHERE id='.$post_data[0].';') 
		or die ("<script> alert('".$qres."'); </script>");
		
		unset($db); unset($dbdata);	unset($post_data);
		
	break;	
	
	case 97:
		// SCAN categories OR geners
		
		$post_data = explode('|', $_POST['procdata']);
		
		$db = new SQLite3('yourmovies.db');
		
		switch ( $post_data[0] ){
			case 5: 
				$db_read = 'collect'; 
				$dbdata = $db -> query('SELECT * FROM '.$db_read.' ORDER BY id;');
				print('<script> $("#'.$post_data[1].'").html( "<option value=-1 selected> - Нет коллекции - </option>" ); </script>');
			break;	
			case 7: 
				$db_read = 'geners'; 
				$dbdata = $db -> query('SELECT * FROM '.$db_read.' ORDER BY g_name;');
				print('<script> $("#'.$post_data[1].'").html( "<option value=0 selected> - Нет фильтра - </option>" ); </script>');
			break;
		}
		
		$cats = '';
		
		while ( $c_row = $dbdata -> fetchArray(SQLITE3_NUM) ){
			$cats .= '<option value='.$c_row[0].'> '.$c_row[1].' </option>';
		}
		
		print('<script> 
		$("#'.$post_data[1].'").append("'.$cats.'");
		
		if ( "'.$post_data[1].'"=="c_AddCat" )
			$("#'.$post_data[1].'").val( $("#cID").val() );	
		</script>');

		unset($db); unset($dbdata); unset($c_row); unset($db_read);	unset($post_data);			
	break;	
	
	case 98:
		// Detect user in system
				
		$db = new SQLite3('yourmovies.db');
		$dbdata = $db -> query('SELECT user FROM users WHERE ip="'.$_POST['procdata'].'";');
		
		if( !$dbdata -> fetchArray(SQLITE3_NUM) ){
				print('');
			} else {
				$dbdata = $db -> query('SELECT * FROM users WHERE ip="'.$_POST['procdata'].'";');
				while ( $c_row = $dbdata -> fetchArray(SQLITE3_NUM) ){
					foreach ( $c_row as $c_col ){
						print( $c_col . '|' );
					}
				}
			}
		unset($db); unset($dbdata); unset($c_row);			
	break;		
	
	case 99:
		// LOCALHOST password reminder
		
		if ( file_exists('yourmovies.db') ){
			$db = new SQLite3('yourmovies.db');
			$dbdata = $db -> query('SELECT user, passwd FROM users WHERE id=1;');
			while ( $c_row = $dbdata -> fetchArray(SQLITE3_NUM) ){
				print('This is your admin data: ['.$c_row[0].'] ['.$c_row[1].']');
			}
			unset($db); unset($dbdata); unset($c_row);
		}
	break;
}

?>