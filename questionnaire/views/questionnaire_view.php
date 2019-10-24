<?php
/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software 
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along 
with this program. If not, see <http://www.gnu.org/licenses/> 




---------------------------------------------------------------------------------- 
Date : June 2016
Author: Mr. Jayanath Liyanage   jayanathl@icta.lk

Programme Manager: Shriyananda Rathnayake
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/

include_once("header.php");	///loads the html HEAD section (JS,CSS)

?>
<?php echo Modules::run('menu'); //runs the available menu option to that usergroup ?>

	<div class="container" style="width:95%;">
		<div class="row" style="margin-top: 55px; padding-bottom: 10px; padding-top: 15px;">
		  <div class="col-md-2 ">
			<?php 
				if (isset($mode)&&($mode!="RUN")){
					echo Modules::run('leftmenu/preference'); //runs the available left menu for preferance 
				}
			?>
		  </div>
		  <div class="col-md-10 ">
		  		<?php 
					if ( isset($error) ){
						echo '<div class="alert alert-danger"><b>ERROR:</b>'.$error.'</div>';
						exit;
					}
				?>		  
				<div class="panel panel-default"  >
					<div class="panel-heading"><b>Questionnaire </b>
					<?php 
						
						if (isset($mode)&&($mode != "RUN")){
							echo "<a class='btn btn-xs btn-default pull-right' target='_blank' href='".site_url('questionnaire/qprint/'.$questionnaire_info['qu_questionnaire_id'].'/?CONTINUE=questionnaire/open/'.$questionnaire_info['qu_questionnaire_id'].'')."' value=''>Print this</a>";
							echo "<input type='button' class='btn btn-xs btn-warning pull-right' onclick=self.document.location='".site_url('form/edit/qu_questionnaire/'.$questionnaire_info['qu_questionnaire_id'].'/?CONTINUE=questionnaire/open/'.$questionnaire_info['qu_questionnaire_id'].'')."' value='Edit this questionnaire'>";
						}
					?>
					</div>
					<?php
						if (isset($mode)&&($mode == "RUN")){
							echo Modules::run('patient/banner',$patient_info["PID"]);
						}
					?>
					<table class='table  table-striped table-condensed table-bordered' width=20%>
						<tr>	
							<td><b>Questionnaire Name:</b></td>
							<td><?php echo $questionnaire_info['name'].' ('.$questionnaire_info['code'].')' ?></td>
						</tr>
						<tr>	
							<td><b>Description:</b></td>
							<td><?php echo $questionnaire_info['description']; ?></td>
						</tr>
						<?php 
						if (isset($mode)&&($mode != "RUN")){
							echo "<tr>	";
								echo "<td><b>Show in :</b></td>";
								echo "<td>";
								if ($questionnaire_info['show_in_patient']){
									echo "[Patient]  ";
								}
								if ($questionnaire_info['show_in_admission']){
									echo "[Admission]  ";
								}
								if ($questionnaire_info['show_in_clinic']){
									if(isset($clinic_info)&&(!empty($clinic_info))){
										foreach ($clinic_info as $k => $v) {
											if(isset($v['name'])){
												echo '['.$v['name'].']&nbsp;';
											}
										}
									}
									//print_r($clinic_info);
									//if (isset($clinic_info["name"])){
										//echo '['.$clinic_info["name"].'] ';
									//}
								}
								if ($questionnaire_info['show_in_visit']){
									if (isset($visit_info["Name"])){
										echo '['.$visit_info["Name"].'] ';
									}
								}
								echo "screen/s";
								echo "</td>";
							echo "</tr>";
							echo "<tr>	";
								echo "<td><b>Applicable to:</b></td>";
								echo "<td>".$questionnaire_info['applicable_to']."</td>";
							echo "</tr>";
							echo "<tr>	";
								echo "<td><b>SOAP type:</b></td>";
								echo "<td>".$questionnaire_info['soap_type']."</td>";
							echo "</tr>";
							}
						?>
					</table>
				</div>
				
				<div class="panel panel-default"  >
					<?php
						if (isset($mode)&&($mode != "RUN")){
							echo '<div class="panel-heading"><b>Available questions  </b>';
							echo '</div>';
						}					
						echo '<form   role="form" ';
							echo ' action="'.base_url().'index.php/questionnaire/save/" ';
							echo ' method="post" accept-charset="utf-8" onsubmit="block_save()" >';
					?>	
					<?php 	
					echo '<table id="qtable" class="table  ';
					if (isset($mode)&&($mode != "RUN")){
						echo ' table-striped table-condensed table-bordered  table-hover ';
					}
					echo ' " width=20%>';
						
							//if(isset($answer_info))print_r($answer_info);
							if (isset($quest_answer_id)){
								echo '<input type="hidden" name="quest_answer_id" id="quest_answer_id" value="'.$quest_answer_id.'"/>';
							}	
							$cnt=1;
							if (count($question_list)>0){
								for($i=0; $i < count($question_list); ++$i){
									echo '<tr  >';
										//onclick=self.document.location="'.site_url("questionnaire/open/".$question_list[$i]['qu_questionnaire_id']).'"
										echo '<td width=50%>';
										//echo $question_list[$i]["qu_question_repos_id"] ;
										if ($question_list[$i]["question_type"] == "Header"){
											echo '<b>'.$question_list[$i]['question'].'</b>';
										}
										elseif ($question_list[$i]["question_type"] == "Footer"){
											echo '<a href="#"><b>'.$question_list[$i]['question'].'</b></a>';
										}
										else{
											if (isset($mode)&&($mode != "RUN")){
												echo '<span class="badge label-default">'. ($cnt++) .'</span>&nbsp;&nbsp;';
												echo ' ['.$question_list[$i]['code'].']';
											}	
											echo '&nbsp;&nbsp;'.$question_list[$i]['question'];
										}
										if (isset($mode)&&($mode != "RUN")){
											//if ( $i < count($question_list)-1 ){
												//echo '<span class="glyphicon glyphicon-arrow-down pull-right" id="down" title="Move down" style="cursor:pointer" onclick=move("'.$question_list[$i]['qu_question_id'].'","'.($question_list[$i]['show_order']+1).'")></span>';	
												echo '<span class=" btn glyphicon glyphicon-floppy-disk pull-right" id="down" title="Move down" style="cursor:pointer" onclick=update_order("'.$question_list[$i]['qu_question_id'].'","'.($i).'")></span>';	
												//echo '<input class="pull-right" type="button" style="width:25px;" value="'.$question_list[$i]['show_order'].'"/>';	
												echo '<input class="pull-right" id="ord_'.$i.'" type="input" style="width:25px;" value="'.$question_list[$i]['show_order'].'"/>';	
												
											//}
											//if ( $i > 0 ){
												//echo '<span class="glyphicon glyphicon-arrow-up pull-right" id="up" title="move up"  style="cursor:pointer" onclick=move("'.$question_list[$i]['qu_question_id'].'","'.($question_list[$i]['show_order']-1).'")></span>';	
												//echo '<input type="input" style="width:25;" value="'.$question_list[$i]['show_order'].'"/>';	
											//}								
										}		
										echo '</td>';
										echo '<td nowrap >';
				
										if ($question_list[$i]["question_type"] == "Text"){
											echo '<span  class="form-group">';
											echo '<input type="text" class="input-xs" id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'" style="" ';
											echo ' value="';
												if (set_value($question_list[$i]["qu_question_repos_id"])==""){
													if(isset($answer_info))
														echo t_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
												}
												else{
													echo set_value($question_list[$i]["qu_question_repos_id"]);
												}
											echo '"';
											echo ' />';
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										elseif ($question_list[$i]["question_type"] == "Number"){
											echo '<span  class="form-group">';
											echo '<input type="number" class="input-xs" id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'"  style="" ';
											echo ' value="';
											if (set_value($question_list[$i]["qu_question_repos_id"])==""){
													if(isset($answer_info))
														echo t_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
												}
												else{
													echo set_value($question_list[$i]["qu_question_repos_id"]);
												}
											echo '"';
											echo ' />';
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										elseif ($question_list[$i]["question_type"] == "Date"){
											echo '<span  class="form-group">';
											echo '<input type="text" class="input-xs"  id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'"  style=""  onmousedown=\'$("#'.$question_list[$i]["qu_question_repos_id"].'").datepicker({"dateFormat": "yy-mm-dd"});\'  ';
											echo ' value="';
											if (set_value($question_list[$i]["qu_question_repos_id"])==""){
													if(isset($answer_info))
														echo t_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
												}
												else{
													echo set_value($question_list[$i]["qu_question_repos_id"]);
												}
											echo '"';
											echo ' />';
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										elseif ($question_list[$i]["question_type"] == "TextArea"){
											echo '<span  class="form-group">';
												$vtextarea = "";
												if (set_value($question_list[$i]["qu_question_repos_id"])==""){
													if(isset($answer_info))
														$vtextarea = t_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
												}
												else{
													$vtextarea = set_value($question_list[$i]["qu_question_repos_id"]);
												}
											echo '<textarea  onKeyUp="getCannedText(this)" class="input-xs"  id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'"  style="" >'.$vtextarea.'</textarea>';
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										elseif ($question_list[$i]["question_type"] == "Header"){
											echo '<span  class="form-group">';
											echo '</span>';
										}
										elseif (($question_list[$i]["question_type"] == "SNOMED_DISORDER")
												||($question_list[$i]["question_type"] == "SNOMED_EVENT")
												||($question_list[$i]["question_type"] == "SNOMED_FINDING")
												||($question_list[$i]["question_type"] == "SNOMED_PROCEDURE")){
											echo '<span  class="form-group">';
												echo '<div id="" ><table width=100%><tr><td width=30%>';
												echo '<select multiple  size="4" class="form-control input-sm"  id="snomed_select" style="height:80px;" onchange="lookUpSNOMED(\''.$question_list[$i]["qu_question_repos_id"].'\',$(\'#snomed_select\').val(),\'\');">';
															echo '<option value="disorder">Disorder</option> ';
															echo '<option value="event">Event</option> ';
															echo '<option value="finding">Finding</option> ';
															echo '<option value="procedures">Procedure</option>';
											   echo ' </select>';
											echo '</td><td valign=top>';
											$vtextarea = "";
											if (set_value($question_list[$i]["qu_question_repos_id"])==""){
												if(isset($answer_info))
													$vtextarea = t_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
											}
											else{
												$vtextarea = set_value($question_list[$i]["qu_question_repos_id"]);
											}
											echo '<textarea id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'" class="form-control input-sm"  style="height:80px;">'.$vtextarea.'</textarea>';
											echo '</td></tr></table></div>';
											echo '</span>';
												 echo '<div class="modal fade" id="snomedDiv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
												echo '<div class="modal-dialog">';
												  echo '<div class="modal-content">';
													echo '<div class="modal-header">';
													  echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
													  echo '<h4 class="modal-title">SNOMED Search</h4>';
													echo '</div>';
													echo '<div class="modal-body" id="snomed_search">';

													echo '</div>';
													
												  echo '</div><!-- /.modal-content -->';
												echo '</div><!-- /.modal-dialog -->';
											  echo '</div><!-- /.modal -->';
										}
										elseif($question_list[$i]["question_type"] == "Select"){
											$scr = null;
											echo '<span  class="form-group">';
											$var = 'select'.$question_list[$i]["qu_question_id"];
											echo '<select type="text" class="input-xs"  id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'"  style="" ';
											echo ' value="'.set_value($question_list[$i]["qu_question_repos_id"]).'"';
											if  ($question_list[$i]['qu_group'] == "AGGREGATE"){
												echo ' onchange=calculate_aggregate("'.$question_list[$i]["qu_question_repos_id"].'"); ';
											}
											echo ' />';
											echo '<option value=""></option>';
											if (isset($$var)){
												$option = $$var;
												for($o=0; $o < count($option); ++$o){
													echo '<option value="'.$option[$o]["qu_select_id"].'"';
													if (set_value($question_list[$i]["qu_question_repos_id"]) == $option[$o]["qu_select_id"]){
														echo ' selected ';
														$scr = $option[$o]["select_default"];
													}
													if (isset($answer_info)){
														if(m_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"],$option[$o]["qu_select_id"])) {
															echo ' selected = selected ';
															$scr = $option[$o]["select_default"];
														}
													 }
													echo '>';
													echo $option[$o]["select_text"];
													if ($question_list[$i]['qu_group'] == "AGGREGATE"){
														if (is_numeric($option[$o]["select_default"])){
															echo '->'.$option[$o]["select_default"].'';
														}
													}
													'</option>';
												}
											}
											echo '</select />';
											if ($question_list[$i]['qu_group'] == "AGGREGATE"){
												echo '<input class="aggregate" style="padding-left:50px;border:0px;text_align:right;" readonly id="'.$question_list[$i]["qu_question_repos_id"].'_aggregate" ';
												echo ' value="'.$scr.'"';
												echo '>';
											}
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										elseif($question_list[$i]["question_type"] == "MultiSelect"){
											echo '<span  class="form-group">';
											$var = 'mselect'.$question_list[$i]["qu_question_id"];
											//echo '<select type="text" class="input-xs"  id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'"  style="" ';
											//echo ' value="'.set_value($question_list[$i]["qu_question_repos_id"]).'"';
											//echo ' />';
											//echo '<option value=""></option>';
											echo '<input type="hidden" for="f'.$question_list[$i]["qu_question_repos_id"].'" id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'" value="';
											if(isset($answer_info))
														echo t_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
											echo'" ></input>';
											if (isset($$var)){
												$option = $$var;
												for($o=0; $o < count($option); ++$o){
													echo '<span><input  ';
													 echo ' id="'.$option[$o]["qu_select_id"].'" name="'.$option[$o]["qu_select_id"].'" ';
													 echo ' onclick=update_value('.$question_list[$i]["qu_question_repos_id"].',this) ';
													 if (isset($answer_info))
														if(m_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"],$option[$o]["qu_select_id"])) echo ' checked = checked ';
													 
													echo ' class="input-xs" type="checkbox" >'.$option[$o]["select_text"].'</input></span><br>';
												}
											}
											//echo '</select />';
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										elseif($question_list[$i]["question_type"] == "PAIN_DIAGRAM"){
											echo '<span  class="form-group">';
											$var = 'diagram'.$question_list[$i]["qu_question_id"];
											if (isset($$var)){
												$clinic_diagram_info = $$var;
											}
											if (isset($clinic_diagram_info)){
												echo 'Diagram name:'.$clinic_diagram_info["name"].$question_list[$i]["qu_question_id"].'<br>';
												echo 'File name:'.$clinic_diagram_info["diagram_name"].'<br>';
												//echo 'Path:'.$clinic_diagram_info["diagram_link"].'<br>';
												echo '<img id="diagram" style="border:1px solid #FFFFFF;" src='.base_url().ltrim($clinic_diagram_info["diagram_link"],'./').' width= 80px height= 100px >';
												echo '</img>';
												if (isset($mode)&&($mode == "RUN")){
												//site_url('diagram/view/'.$clinic_diagram_info["clinic_diagram_id"].'/'.$patient_info["PID"].'/run')
													$vtextarea = "";
													$ans_id  = "0";
													//print_r($answer_info);
													if (set_value($question_list[$i]["qu_question_repos_id"])==""){
														if(isset($answer_info)){
															$vtextarea = t_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
															$ans_id = get_answer_id($answer_info,$question_list[$i]["qu_question_repos_id"]);
														}
													}
													else{
														$vtextarea = set_value($question_list[$i]["qu_question_repos_id"]);
													}
													if ($ans_id=="") $ans_id=0;
													echo '<a target="_blank" href="javascript:void()" onclick=open_diagram("'.$clinic_diagram_info["clinic_diagram_id"].'","'.$patient_info["PID"].'","'.$question_list[$i]["qu_question_repos_id"].'","'.$ans_id.'");>Open Diagram</a>';
													echo '<textarea class="input-xs" role="diagram_data"   style="display:none" id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'"  style="" >'.$vtextarea.'</textarea>';
												}
											}
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										elseif($question_list[$i]["question_type"] == "Yes_No"){
											echo '<span  class="form-group">';
											echo '<select type="text" class="input-xs"  id="'.$question_list[$i]["qu_question_repos_id"].'" name="'.$question_list[$i]["qu_question_repos_id"].'"  style="" ';
											echo ' value="'.set_value($question_list[$i]["qu_question_repos_id"]).'"';
											echo ' />';
											if (isset($answer_info)){
												$op = y_n_check_answer($answer_info,$question_list[$i]["qu_question_repos_id"]);
												echo '<option value="'.$op.'">'.$op.'</option>';
											}
											else{
												echo '<option value=""></option>';
											}
											
											if (set_value($question_list[$i]["qu_question_repos_id"]) == "Yes"){
												echo '<option value="Yes" selected>Yes</option>';
											}
											else{
												echo '<option value="Yes" >Yes</option>';
											}
											if (set_value($question_list[$i]["qu_question_repos_id"]) == "No"){
												echo '<option value="No" selected>No</option>';
											}
											else{
												echo '<option value="No">No</option>';
											}
												
											echo '</select />';
											echo '</span>';
											echo form_error($question_list[$i]["qu_question_repos_id"]);
										}
										if (isset($mode)&&($mode != "RUN")){
											echo '<input type="button" class="btn btn-xs btn-danger pull-right" onclick=self.document.location="'.site_url('questionnaire/remove/'.$question_list[$i]['qu_question_id'].'/?CONTINUE=questionnaire/open/'.$questionnaire_info['qu_questionnaire_id'].'').'" value="Remove">';
											echo '<input type="button" class="btn btn-xs btn-info pull-right" onclick=self.document.location="'.site_url('form/edit/qu_question_repos/'.$question_list[$i]['qu_question_repos_id'].'/?CONTINUE=questionnaire/open/'.$questionnaire_info['qu_questionnaire_id'].'').'" value="Edit">';
											if($question_list[$i]["question_type"] == "Select"){
												echo '<input type="button" class="btn btn-xs btn-info pull-right" onclick=self.document.location="'.site_url('form/create/qu_select/'.$question_list[$i]['qu_question_repos_id'].'/?CONTINUE=questionnaire/open/'.$questionnaire_info['qu_questionnaire_id'].'').'" value="Add option">&nbsp';
											}
											if($question_list[$i]["question_type"] == "PAIN_DIAGRAM"){
												if (isset($clinic_diagram_info)){
													echo '<input type="button" class="btn btn-xs btn-success " onclick=self.document.location="'.site_url('form/edit/qu_diagram/'.$pain_diagram_info['qu_diagram_id'].'/?CONTINUE=questionnaire/open/'.$questionnaire_info['qu_questionnaire_id'].'').'" value="Change diagram">';
												}
												else{
													echo '<input type="button" class="btn btn-xs btn-success " onclick=self.document.location="'.site_url('form/create/qu_diagram/'.$question_list[$i]['qu_question_repos_id'].'/?CONTINUE=questionnaire/open/'.$questionnaire_info['qu_questionnaire_id'].'').'" value="Select diagram">';
												}
												
											}
										}
										echo '</td>';
									echo '</tr>';
								}
								if (isset($mode)&&($mode != "RUN")){
									echo '<tr>';
										echo '<td><input type="button" class="btn btn-xs btn-success pull-left" onclick=question_search(); value="Add question from repository"></td>';
										echo '<td></td>';
									echo '</tr>';
								}
							}
							else{
								if (isset($mode)&&($mode != "RUN")){
									echo '<tr>';
										echo '<td><input type="button" class="btn btn-xs btn-success pull-left" onclick=question_search(); value="Add question from repository"></td>';
										echo '<td></td>';
									echo '</tr>';
								}
							}
						?>
						<?php
						if (isset($mode)&&($mode == "RUN")){
							echo '<tr>';
								echo '<td>';
								//if ($questionnaire_info['show_in_patient'] == 1){
									if (isset($patient_info["PID"])){
										echo '<input type="hidden" id="PID" name="PID" value="'.$patient_info["PID"].'">';
									}
								//}
								if ($questionnaire_info['show_in_admission'] === 1){
									echo '<input type="text" value="">';
								}
								echo '<input type="hidden" id="qu_questionnaire_id" name="qu_questionnaire_id" value="'.$questionnaire_info['qu_questionnaire_id'].'">';
								if (isset($_GET["CONTINUE"])){
									echo '<input type="hidden" id="CONTINUE" name="CONTINUE" value="'.$_GET["CONTINUE"].'">';
								}
								else{
									echo '<input type="hidden" id="CONTINUE" name="CONTINUE" value="'.set_value("CONTINUE","").'">';
								}
								echo '<input type="hidden" id="link_type" name="link_type" value="'.$link_type.'">';
								echo '<input type="hidden" id="link_id" name="link_id" value="'.$link_id.'">';
								
								echo '';
								echo '</td>';
								echo '<td><br>';
								echo '<div style="position:fixed;left:78%;top:90%;">';
								echo '<button type="submit" name="Save" id="SaveBtn" value="Save" class="btn btn-primary "><span class="glyphicon glyphicon-floppy-disk"></span>Save</button>';
								echo '&nbsp;<button type="button" name="Cancel" id="CancelBtn" value="Save" class="btn btn-primary " onclick=window.history.back(-1); ></span>Cancel</button>';
								echo '</div>';
								echo '</td>';
							echo '</tr>';
						}
						?>
					</table>
						
					</form>
				</div>

			</div>
		</div>
	</div>
	<?php
	function m_check_answer($ans,$qid,$msid){
		if (empty($ans)) return false;
		for ($i=0; $i<count($ans); ++$i){
			if (isset($ans[$i]["qu_question_id"])){
				if ($ans[$i]["qu_question_id"] == $qid){
					$ops = explode(",", $ans[$i]["answer"]);
					if (in_array($msid, $ops)){
						return true;
					}
				}
			}
		}
		return false;
	}
	function get_answer_id($ans,$qid){
		if (empty($ans)) return false;
		for ($i=0; $i<count($ans); ++$i){
			if (isset($ans[$i]["qu_question_id"])){
				if ($ans[$i]["qu_question_id"] == $qid){
						return $ans[$i]["qu_answer_id"];
				}
			}
		}
		return '';
	}
	function t_check_answer($ans,$qid){
		if (empty($ans)) return false;
		for ($i=0; $i<count($ans); ++$i){
			if (isset($ans[$i]["qu_question_id"])){
				if ($ans[$i]["qu_question_id"] == $qid){
						return $ans[$i]["answer"];
				}
			}
		}
		return '';
	}
	function y_n_check_answer($ans,$qid){
		if (empty($ans)) return false;
		for ($i=0; $i<count($ans); ++$i){
			if (isset($ans[$i]["qu_question_id"])){
				if ($ans[$i]["qu_question_id"] == $qid){
						return $ans[$i]["answer"];
				}
			}
		}
		return '';
	}	
		function render_field($fld,$questionnaire_info){
			if (!isset($fld)){
				//return null;
			}
			$html_fld = '';
			if ($fld["question_type"] == "Text"){
				$html_fld .= '<span  class="form-group">';
				$html_fld .= '<input type="text" class="input-xs" style="" />';
				$html_fld .= '<input type="button" class="btn btn-xs btn-warning pull-right" onclick=self.document.location="'.site_url('form/edit/qu_question/'.$fld['qu_question_id']).'" value="Edit">';
				$html_fld .= '</span>';
			}
			elseif($fld["question_type"] == "Select"){
				$html_fld .= '<span  class="form-group">';
				$html_fld .= '<select type="text" class="input-xs" style="" />';
				$html_fld .= '</select />';
				$html_fld .= '<input type="button" class="btn btn-xs btn-warning pull-right" onclick=self.document.location="'.site_url('form/edit/qu_question/'.$fld['qu_question_id']).'" value="Edit">';
				$html_fld .= '<input type="button" class="btn btn-xs btn-success pull-right" onclick=self.document.location="'.site_url('form/create/qu_select/'.$fld['qu_question_id']).'/'.$questionnaire_info['qu_questionnaire_id'].'" value="Add Option">';
				$html_fld .= '</span>';
			}
			return $html_fld;
		}
	?>



	
	<script language="javascript">
	$(function(){
		sum = 0;
		if ($( ".aggregate" ).get(0)){
			$( ".aggregate" ).each(function( index ) {
				if (isNumber($( this ).val())){
				 sum += parseInt($( this ).val());
				}
			});
			$("form").append('<div id="score_div" style="padding:50px;"><b>SCORE=<input style="border:0px" id="score" value=""  readonly></b><br>GRADING: 0-7 = Non-case &nbsp; &nbsp; &nbsp;8-10 = Borderline case  &nbsp; &nbsp; &nbsp;11+ = Case </div>');
			$("#score").val(sum);
		}
	});
	function isNumber (o) {
		  return ! isNaN (o-0) && o !== null && o.replace(/^\s\s*/, '') !== "" && o !== false;
		}
		function calculate_aggregate(id){
			var text = $("#"+id+" option:selected").text();
			val = text.substring(text.indexOf("->")+2,text.length);
			sum = 0;
			if (isNumber(val)){
				$("#"+id+"_aggregate").val(val);			
				if ($("#score_div").get(0)){
					$( ".aggregate" ).each(function( index ) {
						if (isNumber($( this ).val())){
						 sum += parseInt($( this ).val());
						}
					});
					$("#score").val(sum);
				}
				else{
					$("form").append('<div id="score_div" style="padding:50px;"><b>SCORE=<input style="border:0px" id="score" value="'+val+'"  readonly></b><br>GRADING: 0-7 = Non-case &nbsp; &nbsp; &nbsp;8-10 = Borderline case  &nbsp; &nbsp; &nbsp;11+ = Case </div>');
				}
			}
		}
		function block_save(){$("#SaveBtn").val("Saving....").attr("disabled","true");}
		function open_diagram(diagram_id,pid,repid,ans_id){
			var url='<?php echo site_url('diagram/view/'); ?>';
			url+='/'+diagram_id+'/'+pid+'/run/'+ans_id+'/'+repid
			var win = window.open(url,'d_win','fullscreen=yes,location=no,menubar=no');
		}
		function edit_diagram(diagram_id,pid,repid){
		//diagram/view/18/190/view_data/16454710
			var url='<?php echo site_url('diagram/view/'); ?>';
			url+='/'+diagram_id+'/'+pid+'/run/0/'+repid
			var win = window.open(url,'d_win','fullscreen=yes,location=no,menubar=no');
		}
		function update_value(in_put,obj){ 
			var id=obj.id;
			var status=obj.checked;
			
			var txt = $("input[for='f"+in_put+"']").val();
			if (status == false){
				var re = new RegExp(id+',', 'g');
				txt = txt.replace(re,"");
				$("input[for='f"+in_put+"']").val(txt);
				return;
			}
			var arr=null;
			arr = String(txt).split(",");
			for (i=0; i<arr.length; i++){
				if(arr[i] == id){
					return;
				}
			}
			txt += id+',';
			console.log(arr);
			
			$("input[for='f"+in_put+"']").val(txt);
		}
		function lookUpSNOMED(el_id, type, txt) {
		var request = $.ajax({
		
				url : "<?php echo base_url(); ?>index.php/lookup/qu_snomed/?el_id="+el_id+"&type="+type+"",
				global : false,
				type : "POST",
			async : false
			}).responseText;
			$("#snomedDiv").modal();
			$("#snomed_search").html(request);

		}
		function set_data(data,repid){
			$("#"+repid).html(data);
		}
		function update_order(qid,pos){
			if(!qid) return;
			var request = $.ajax({
				url: "<?php echo base_url(); ?>index.php/questionnaire/update_order/?qid="+qid+"&pos="+$("#ord_"+pos).val()
			});
			
			request.done(function (response, textStatus, jqXHR){
				if(response) self.document.location.reload();
			});
		}
		function move(qid,pos){
			if(!qid) return;
			var request = $.ajax({
				url: "<?php echo base_url(); ?>index.php/questionnaire/move_question/?qid="+qid+"&pos="+pos,
				type: "post"
			});
			
			request.done(function (response, textStatus, jqXHR){
				if(response) self.document.location.reload();
			});	
		}
		function add_question(qid){
			if(!qid) return;
			var request = $.ajax({
				url: "<?php echo base_url(); ?>index.php/questionnaire/add_question/?qid="+qid+"&quest_id=<?php echo $questionnaire_info['qu_questionnaire_id'];  ?>",
				type: "post"
			});
			
			request.done(function (response, textStatus, jqXHR){
				if(response) self.document.location.reload();
			});			
			
		}
		
		function question_search(){
				var win = window.open("<?php echo site_url("question/search"); ?>","win",'width=500,height=700'); 
		}
function getCannedText(obj) {
    var remarks_text = String($(obj).val());
    var srh_text = "";
    if (remarks_text[String(remarks_text).length - 1] == " ") {
        if (remarks_text.indexOf("\\") >= 0) {
            srh_text = remarks_text.substr(remarks_text.indexOf("\\") + 1,
                remarks_text.indexOf(" "));
            loadCannedText(srh_text, obj);
        }
    }
}

function loadCannedText(srh, obj) {
    var ihtml = $.ajax({
        url : "<?php echo site_url("lookup/cannedtext/"); ?>"+srh,
        global : false,
        type : "POST",
        async : false
    }).responseText;
    if (ihtml.length > 1) {
        canned_text = ihtml.substr(0, ihtml.length)
        $(obj).val($(obj).val().replace('\\' + srh, String(canned_text)));
    }

}

$('select option')
    .filter(function() {
        return !this.value || $.trim(this.value).length == 0;
    })
   .remove();

$('select option')
    .first()
    .prop('selected', true); 

	</script>