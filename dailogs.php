<!-- DIALOGS -->
<div id="dlgBkg" style="display:none;"></div>
<div id="log-in" style="display:none; text-align:center">
		
		<!-- DIALOG.CAPTION -->
		<div id='dlgCapt' style='padding: 5px; '> 
			<div style='display:inline-block; width:15px; border-radius: 15px;'> </div> 
			<div style='display:inline-block; width:90%; font-weight:bold; color:white; font-size:15px;'> </div> 
			<div style='display:inline-block; width:15px; border-radius: 15px; cursor: pointer; padding:2px;' id='dlgClose' class='iaElem'> X </div> 
		</div>
		<!-- dlgCAption.end -->
	
		<!-- LOGIN/REGISTER DIALOG -->		
		<div id='login_ok' style="display:none;" class='dlg_resp'> <br> <div style="color:green"> <b> Вы вошли в систему! </b> </div> </div>
		<div id='login_err' style="display:none;" class='dlg_resp'> <div style="color:red"> <b>Вы не зарегистрированы в системе!</b><br> Попробуйте снова.  </div> <hr> </div> 
		<div id='reg_ok' style="display:none;" class='dlg_resp'>  <div style="color:blue"> <b>Вы зарегистрированы в системе.</b><br> Теперь войдите.  </div> <hr> </div>
		<div id='reg_err' style="display:none;" class='dlg_resp'> <div style="color:red"> <b>Вы НЕ зарегистрированы в системе.</b><br> Кто-то уже занял этот логин.  </div> <hr> </div>
		
		<div id='loginForm' style="display:none;" class='forms'>
		<br>
        <label for="user"> Ваш логин </label><br>
        <input type="text" name="user" id="usr">
        <br><br>
        <label for="password"> Ваш пароль </label><br>
        <input type="password" name="password" id="passwd">
        <br><br>
		
		<button class='iaElem btnAction' actType="1" title=""> <span class="fa fa-sign-in"></span> Войти </button>
		<button class='iaElem btnAction' actType="2" title=""> <span class="fa fa-key "></span> Регистрация </button>		
        </div>
		<!-- login form end -->
		
		<!-- COLLECTIONS/GENERS ADD DIALOG -->				
    	<div id='collectForm' style="display:none;" class='forms'>
        <label for="c_dnnm"> Добавить: </label><br>
        <input type="text" name="c_dnnm" id="c_dnnm" style='width:190px;'>
		<button class='iaElem btnAction' actType="">  
			<span class="fa fa-plus-circle" style="font-size:15px"></span>  
		</button>
        <br><br>
		<label for="cats">Удалить:</label><br>
			<select name="cats" id="c_dpdCat" style='width:195px'>
			</select>
		<button class='iaElem btnAction' actType="">  
			<span class="fa fa-minus-circle" style="font-size:15px"></span>
		</button>
        </div>

		<div id='c_wait' style="display:none;"class='dlg_resp'> <hr> <div style="color:blue"> <i>Статус...</i> </div> <br> </div>
		<div id='c_add_ok' style="display:none;" class='dlg_resp'> <hr> <div style="color:green"> Коллекция/Жанр <b>добавлен(а) успешно</b> </div></div>
		<div id='c_add_err' style="display:none;" class='dlg_resp'> <hr> <div style="color:red"> <b> Ошибка </b>: Такая(ой) колекция/жанр уже существует  </div> <br></div> 
		<div id='c_del_ok' style="display:none;" class='dlg_resp'> <hr> <div style="color:maroon"> Коллекция/Жанр <b>удален(а) успешно</b> </div></div> 
		<!-- collect./gener. dilog end -->
		
		<!-- MOVIE FORM .main -->
		<br>
		<div id='movieForm' style="display:none;" class='forms'>
        
		<label for="m_nm"> Название: </label><br>
		<input type="text" name="m_nm" id="m_nm" style='width:600px;'>
		
		<div style='display:inline-block;' id='ID_spin'>
		<input id="NmID" value="1" size="3" readonly style=''>	
		<button class='iaElem spin' oper='inc' tg='NmID' style='padding:0px; border-radius:0px;'> 
			<span class="fa fa-plus" style="font-size:11px; padding:4px;"></span> 
		</button>
		<button class='iaElem spin' oper='dec' tg='NmID' style='padding:0px; border-radius:0px;'> 
			<span class="fa fa-minus" style="font-size:11px; padding:4px;"></span> 
		</button>	
		</div>
		
		<br>
		<input type="hidden" id='mID'>
		<br>

		
		<label for="m_url"> Ссылка на описание: </label><br>
        <input type="text" name="m_url" id="m_url" style='width:600px;'><br>
		<br>
		
		<label for="m_url"> Ссылка на обложку: </label><br>
        <input type="text" name="img_url" id="img_url" style='width:600px;'><br>
		<br>

		<hr>
		
		<table cellpadding='25' width='100%' border='0'><tr>
				
        <td align='left' width='30%'>
			<label > Статус/Отзыв: </label><br><br>
			<input type="radio" id="ft_w" name='radio' rate="-2" checked="checked">Планир. см.</input><br>
			<input type="radio" id="down" name='radio' rate="-1" >Скачано</input><br>
			<br>
			<input type="radio" id="best" name='radio' rate="5" >ОТЛИЧНО</input><br>
			<input type="radio" id="good" name='radio' rate="4" >ХОРОШО</input><br>
			<input type="radio" id="mid" name='radio' rate="3" >СРЕДНЕ</input><br>
			<input type="radio" id="acc" name='radio' rate="2" >ТЕРПИМО</input><br>
			<input type="radio" id="bad" name='radio' rate="1" >УЖАСНО</input><br>
		</td>	
		
		<td width='70%' valign='top' align='right'>
		<label> Жанры к фильму: </label><br><br>
		<table><tr><td>
		<select id='m_gens' size='8' style='width:270px;'> 
			<!-- <option gid="0"> Comedy </option> --> 
		</select></td><td>
		<button class='iaElem' id='btnRemGen' style=''> У<br> Б<br> Р<br> А<br> Т<br> Ь </button>
		</td></tr></table>
		<br>		
		<select name="cats" id="g_exCat" style='width:270px;' >
		</select>
		<button class='iaElem' id='btnAddGen' title='Добавить этот жанр'> 
			<span class="fa fa-plus-circle" style="font-size:15px"></span> 
		</button>
		
		</td>
		
		</tr></table>
		
		<hr>
		
		<table border='0' width='100%'><tr>		
		<td>		
		
		<label for="m_parts" > Составные части: </label><br>
		<div id="m_parts" >
		
		<div style='font-weight:normal; padding-bottom:5px; vertical-align:middle;'>Добавить часть, объёмом
		
		<span style='border: solid 1px gray'>
		<input id="part_leng" value="1" size="3" readonly style='border: solid 1px gray'>	
		<button class='iaElem spin' oper='inc' tg='part_leng' style='padding:2px;'> <span class="fa fa-plus-circle" style="font-size:15px"></span> </button>
		<button class='iaElem spin' oper='dec' tg='part_leng' style='padding:2px;'> <span class="fa fa-minus-circle" style="font-size:15px"></span> </button>	
		</span> &ensp;	
		эпизодов, типа: </div>
		
		<button class='add_part_tp iaElem' prefx='E'> Сезон </button>
		<button class='add_part_tp iaElem' prefx='MVO'> Полнометр. </button>
		<button class='add_part_tp iaElem' prefx='SP'> Спешл </button>
		<button class='add_part_tp iaElem' prefx='OVA'> OVA </button>
		</div>	
		
		</td><td>
		
		<label for="m_state" > Пометить как: </label><br>
		<div id="m_state" >
				
		<button class='state_set iaElem' ptp='1' title="Удалён"> <span class="fa fa-trash-o">  </button>
		<button class='state_set iaElem' ptp='2' title="Записан"> <span class="fa fa-floppy-o"> </button>
		<button class='state_set iaElem' ptp='4' title="Просмотрен"> <span class="fa fa-eye-slash"> </button>
		<button class='state_set iaElem' ptp='5' title="Смотрю"> <span class="fa fa-eye"> </button>
		<button class='state_set iaElem' ptp='3' title="Отмена"> <span class="fa  fa-ban"> </button>
		</div>	
		
		</td></tr>
		</table>

		
		<table width="630" style='margin:10px;' >
		<tr >
		<td width='25%' align='center' class='mv_th'> Сезоны: </td> 
		<td width='25%' align='center' class='mv_th'> Полнометр.: </td>
		<td width='25%' align='center' class='mv_th'> Спешлы: </td>
		<td width='25%' align='center' class='mv_th'> OVA(Ы): </td>
		</tr><tr id='eps_tb'>
		<td id='cl_E' valign='top' class='mv_cell'> </td> 
		<td id='cl_MVO' valign='top' class='mv_cell'> </td>
		<td id='cl_SP' valign='top' class='mv_cell'> </td>
		<td id='cl_OVA' valign='top' class='mv_cell'> </td>
		</tr></table>
		
		<hr>
		
		<table width='100%' border='0'><tr>		
		<td align='left' style='padding:5px 15px;' valign='middle'>
			<label>Поместить в коллекцию:</label> <br>
			<select name="cats" id="c_AddCat" style='width:175px'> </select>
		</td>		
		<td align='right' style='padding:5px 15px;' valign='middle'> 
			<button class='iaElem btnAction' id='btnSubmit' actType="8">  btnSubmit  </button> 
		</td>		
		</tr></table>
		
		<input type='hidden' id='cID'> <input type='hidden' id='Old_cID'>
		<div id='fakeBtnDel' style='display:none' class='btnAction' actType="10"></div>
        </div>
		<!-- movieForm.end -->
		
		<div id='login_script'></div>
		
</div>
<!-- /dialogs -->

<!-- TOOLBARS -->
<div align='center'>
	<table border="0" >
    <tr>
	
	<td width="30">
		<table class='blocks'>
		<tr>
			<td > Статус: </td>
		</tr><tr>
			<td align='center'> 
				<img id="status_ico" src='img/ok.png' width='35' title='Запрос ОК'>
			</td>
		</tr>
		</table>
		
		<div style='width:15px;' class='blocks'></div>
	
	</td>
	
	<td id='tbLogin' align='center'>
	
		<table class='blocks'>
		<tr>
			<td > Войти / Зарегистрироватья:</td>
		</tr><tr>
			<td align='center'> 
				<button class='dlg_btn iaElem' DlgCap="Авторизация в системе" form_nm="loginForm" dlgDem='250x200' style='padding:6px 10px'> 
				<span class="fa fa-key "></span> Получить доступ 
				</button>
			</td>
		</tr>
		</table>
		
		<div style='width:10px;' class='blocks'></div>
    </td>
	
	
	<td id='tbLogOUT' style='display:none' valign="middle">
	
		<table class='blocks'>
		<tr>
			<td > Вы опознаны как:</td>
		</tr><tr>
			<td align='center'> 
				<div id='cur_usr' style='padding:6px; 
				display: inline-block; font-weight:bold;'></div> 
				<button id="outAct" class='iaElem' title="Выйти"> <span class="fa fa-sign-out"> </button>
			</td>
		</tr>
		</table>
		
		<div style='width:10px; ' class='blocks'></div>
		
		<table class='blocks' >
		<tr> <td align='center' title='Добавить запись о фильме'> Фильм: </td> </tr>		
		<tr> <td>
		<button class='dlg_btn iaElem' DlgCap="<span class='fa fa-film'></span> Добавление фильма" form_nm="movieForm" dlgDem='650x660' title="Добавить"> 
			<span class="fa fa-plus-circle"></span> 
		</button>
		<button class='iaElem' id='movRefresh' title="Обновить"> 
			<span class="fa fa-refresh"></span> 
		</button>		
		</td> </tr>
		</table>
		
		<div style='width:10px;' class='blocks'></div>
	
	</td>
	
		<td id='tbSecCtrl' style='display:none' valign="middle">
		
		<table class='blocks'>
		<tr> <td align='center'> Коллекции: </td> </tr>
		<tr> <td>
		<button class='dlg_btn iaElem' DlgCap="<span class='fa fa-folder-open-o'></span> Коллекции" actType="4|5" form_nm="collectForm" dlgDem='250x200' title="Добавить/Удалить " style='padding:5px 10px'> 
			<span class="fa fa-folder-open-o"></span> > <span class="fa fa-plus-square-o"></span> / <span class="fa fa-minus-square-o"></span>
		</button>
		</td> </tr>
		</table>
		
		<!--<div style='width:10px; display:inline-block;' class='blocks'></div>-->
		
		<table class='blocks'>
		<tr> <td align='center'> Жанры: </td> </tr>
		<tr> <td>
		<button class='dlg_btn iaElem' DlgCap="<span class='fa fa-puzzle-piece'></span> Жанры" actType="6|7" form_nm="collectForm" dlgDem='250x200' title="Добавить/Удалить " style='padding:5px 10px'> 
			<span class="fa fa-puzzle-piece"></span> > <span class="fa fa-plus-square-o"></span> / <span class="fa fa-minus-square-o"></span>
		</button>
		</td> </tr>
		</table>
		
		<div style='width:10px;' class='blocks'></div>
	</td>	
	
	<td id='tbFilList' style='display:normal' valign="middle">
		
		<table class='blocks'>
		<tr> <td align='center'> Выбор коллекции: </td> </tr>
		<tr> <td>
			<div class="iaElemStat">
			<span class="fa fa-folder-open"></span>
			<select name="cats" id="c_collCat" style='
			width:130px; background-color:grey; color:white
			' >
			</select>
			</div>
		</td> </tr>
		</table>		
		
		<table class='blocks'>
		<tr> <td align='center'> Фильтровать (жанр): </td> </tr>
		<tr> <td>
			<div class="iaElemStat">
			<span class="fa fa-puzzle-piece"></span>
			<select name="cats" id="c_filtCat" style='
			width:130px; background-color:grey; color:white
			' >
			<option value="0" selected> - Нет фильтра - </option>
			</select>
			</div>
		</td> </tr>
		</table>
		
		<div style='width:10px; display:inline-block;' class='blocks'></div>
		
		<table class='blocks'>
		<tr> <td align='center'> Листать (Шаг): </td> </tr>
		<tr> <td>
			<div class="iaElemStat" style='height:20px;'>
			
			<span class="fa fa-road" title='Шаг'></span>
			<select name="cats" id="c_limitCat" title='Шаг' style='
			width:50px; background-color:grey; color:white
			' >
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="70">70</option>
			<option value="100">100</option>
			</select>
			
			</div>
		</td> </tr>	
		</table>
		
		<div style='width:10px; display:inline-block;' class='blocks'></div>
		
		<table class='blocks'>
		<tr> <td align='center'> Навигация: </td> </tr>
		<tr> <td>							
			
			<button class='step_btn iaElem' direct='-'> <span class="fa fa-chevron-circle-left"></span> </button>
			<div style='' class='step_pos' > 0 </div>
			<button class='step_btn iaElem' direct='+'> <span class="fa fa-chevron-circle-right"></span> </button>			
			
						
		</td> </tr>
		</table>
	
	</td>
	
	</tr>
	<tr>
	
	<td colspan='3'>
	 <div align="center" id='status' style='padding:5px;'>  </div>
	</td>
	
	</tr>
    </table>
</div>
<!-- /toolbar -->

<!-- IMAGE FRAME -->
<img class="image_frame" id="image_frame" style="position:absolute; display:none; box-shadow: 0 0 15px rgba(0,0,0,0.7);">
<!-- /IMAGE_FRAME -->