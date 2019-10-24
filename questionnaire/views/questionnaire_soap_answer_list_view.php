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
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
ICT Agency,
160/24, Kirimandala Mawatha,
Colombo 05, Sri Lanka
---------------------------------------------------------------------------------- 
Author: Author: Mr. Jayanath Liyanage   jayanathl@icta.lk
                 
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/


if (empty($clinic_previous_record_list)) return;
?>
<div class="panel panel-default"  style="padding:2px;margin-bottom:1px;" >
				<div class="panel-heading" ><b>Current SOAP records</b></div>
					<?php
						/*
						if ($opd_visits_info["referred_admission_id"] >0){
							echo '&nbsp;<span class="label label-info"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;This visit referred to admission </span>';
							echo '<a class="btn btn-default btn-xs" href="'.site_url("admission/view/".$opd_visits_info["referred_admission_id"]).'"> Open </a>';
						}
						*/
						//print_r($clinic_previous_record_list);
						echo '<table class="table "  style="font-size:0.95em;margin-bottom:0px;">';
							if (!empty($clinic_previous_record_list)){
								for($i=0;$i<count($clinic_previous_record_list);++$i){
								$sum = 0;
									echo '<tr>';
										echo '<td>';echo '<i class="" style="cursor:pointer;display:block" onclick=$("#data_soap_'.$i.'").toggle(); >';
										//print_r($clinic_previous_record_list[$i]);
											if ($clinic_previous_record_list[$i]["soap_type"]!=""){
											$letter = substr($clinic_previous_record_list[$i]["soap_type"],2,1);
											//echo $letter;
											if ($this->config->item($letter)!=""){
												$label = '<span class="badge" style="background:'.$this->config->item($letter).';">'.$letter.'</span>';
													echo $label ;
												}
                                    }
                                            $dte1 = "";
                                           $dte = explode (' ',$clinic_previous_record_list[$i]["CreateDate"]);
                                            if( !empty($dte) and (isset($dte[0]))){
                                                $dte1= $dte[0];
                                            }
											echo $clinic_previous_record_list[$i]["qu_name"].' '.$dte1.' ';
											if(Modules::run("security/check_delete_access","qu_quest_answer","can_delete")==1){
												if ($status == null)
													echo '<a  title = "Delete this? " class="pull-right glyphicon glyphicon-remove-sign" 
													href="'.site_url("questionnaire/delete/".$clinic_previous_record_list[$i]["qu_quest_answer_id"].'/'.$pid.'/'.$clinic_previous_record_list[$i]["link_id"].'?continue='.$continue).'"></a>';
													ECHO '</i>';
											}
											echo '<hr style="margin:0px;">';// By: '.$clinic_previous_record_list[$i]["CreateUser"].'
											if (!empty($clinic_previous_record_list[$i]["data"])){
												echo '<div id="data_soap_'.$i.'" style="display:none">';
												echo '<table class="table table-condensed table-striped table-hover" style="margin-bottom: 2px">';
												for($j=0;$j<count($clinic_previous_record_list[$i]["data"]);++$j){
													if ($clinic_previous_record_list[$i]["data"][$j]["answer"]=="") continue;
													echo '<tr>';
														echo '<td nowrap width=300px>';
															if($clinic_previous_record_list[$i]["data"][$j]["answer_type"] == "Footer"){
																continue;
															}
															elseif($clinic_previous_record_list[$i]["data"][$j]["answer_type"] == "Header"){
																echo '<b style="text-align:center;">'.$clinic_previous_record_list[$i]["data"][$j]["question"].'</b>';
															}
															else{
																echo $clinic_previous_record_list[$i]["data"][$j]["question"];
															}
														echo '</td>';
														echo '<td>';
															if($clinic_previous_record_list[$i]["data"][$j]["answer_type"]=="PAIN_DIAGRAM"){
																$var = 'diagram'.$clinic_previous_record_list[$i]["data"][$j]["qu_question_id"];
																$clinic_diagram_info = $$var;
																//print_r($clinic_diagram_info);
																if (isset($clinic_diagram_info )){
																	echo '<a target="_blank" href="javascript:void()" onclick=open_diagram("'.$clinic_diagram_info["clinic_diagram_id"].'","'.$patient_info["PID"].'","'.$clinic_previous_record_list[$i]["data"][$j]["qu_answer_id"].'");>Open Diagram</a>';
																}
															}
															else{
																echo $clinic_previous_record_list[$i]["data"][$j]["answer"];
																if($clinic_previous_record_list[$i]["data"][$j]["qu_group"]== "AGGREGATE"){
																	echo '&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$clinic_previous_record_list[$i]["data"][$j]["select_default"].'</b>';
																	$sum +=$clinic_previous_record_list[$i]["data"][$j]["select_default"];
																}
															}
														echo '</td>';
													echo '</tr>';	
												}
												echo '</table>';
												if ($sum>0){
													echo '<b style="padding-left:100px;">SCORE ='.$sum.'<br>GRADING: 0-7 = Non-case &nbsp; &nbsp; &nbsp;8-10 = Borderline case  &nbsp; &nbsp; &nbsp;11+ = Case</b>';
												}
												//print_r($clinic_previous_record_list);
												//if ($status == ""){
													//echo '<a class=" pull-right" href="'.site_url("questionnaire/edit/".$clinic_previous_record_list[$i]["qu_questionnaire_id"].'/'.$pid.'/'.$clinic_previous_record_list[$i]["link_type"].'/'.$clinic_previous_record_list[$i]["link_id"].'/'.$clinic_previous_record_list[$i]["qu_quest_answer_id"].'?CONTINUE='.$continue).'">Edit</a>';
												//}
												echo '</div>';
																	}	
										echo '</td>';				
									echo '</tr>';				
								}	
							}
						echo '</table>';
					?>
			</div>	<!-- END OPD INFO-->
<?php 
 ?>

	<script language="javascript">
		function open_diagram(diagram_id,pid,ans_id){
		var url='<?php echo site_url('diagram/view/'); ?>';
		url+='/'+diagram_id+'/'+pid+'/view_data/'+ans_id;
		var win = window.open(url,'d_win','fullscreen=yes,location=no,menubar=no');
	}
	</script>