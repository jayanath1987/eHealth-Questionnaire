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



?>
<style>
	body{
		font-family:Arial;
	}
	table{
			font-size:13;
			border-style:solid;
			border-color:#eeeeee;
	}
</style>
	
					<h2><b>Questionnaire  Detail </b></h2>
						<table class='table' width=90% border=1 cellpadding=1 cellspacing=0>
						<tr>	
							<td width=50%><b>Questionnaire Name:</b></td>
							<td><?php echo $questionnaire_info['name'].' ('.$questionnaire_info['code'].')' ?></td>
						</tr>
						<tr>	
							<td><b>Description:</b></td>
							<td><?php echo $questionnaire_info['description']; ?></td>
						</tr>
						<?php 
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
						?>
					</table>
					<?php
						echo '<h2><b>Available questions  </b>';
						echo '</h2>';
					?>	
					<?php 	
					echo '<table id="qtable" class="table  " width=90% border=1  cellpadding=1 cellspacing=0>';
							$cnt=1;
							if (count($question_list)>0){
								for($i=0; $i < count($question_list); ++$i){
									echo '<tr  >';
										echo '<td  width=50%>';
										if ($question_list[$i]["question_type"] == "Header"){
											echo '<b>'.$question_list[$i]['question'].'</b>';
										}
										elseif ($question_list[$i]["question_type"] == "Footer"){
											echo '<a href="#"><b>'.$question_list[$i]['question'].'</b></a>';
										}
										else{
											if (isset($mode)&&($mode != "RUN")){
												echo '<span>'. ($cnt++) .'</span>&nbsp;&nbsp;';
												echo ' ['.$question_list[$i]['code'].']';
											}	
											echo '&nbsp;&nbsp;'.$question_list[$i]['question'];
										}
										echo '</td>';
										echo '<td nowrap >';
				
										if ($question_list[$i]["question_type"] == "Text"){
											echo '<i>[Data type: TEXT]</i>';
										}
										elseif ($question_list[$i]["question_type"] == "Number"){
											echo '<i>[Data type: NUMBER]</i>';
										}
										elseif ($question_list[$i]["question_type"] == "Date"){
											echo '<i>[Data type: DATE]</i>';
										}
										elseif ($question_list[$i]["question_type"] == "TextArea"){
											echo '<i>[Data type: REMARKS]</i>';
										}
										elseif ($question_list[$i]["question_type"] == "Header"){
											echo '<i>[Data type: HEADER]</i>';
										}
										elseif (($question_list[$i]["question_type"] == "SNOMED_DISORDER")
												||($question_list[$i]["question_type"] == "SNOMED_EVENT")
												||($question_list[$i]["question_type"] == "SNOMED_FINDING")
												||($question_list[$i]["question_type"] == "SNOMED_PROCEDURE")){
												echo '<i>[Data type: SNOMED]</i>';
										}
										elseif($question_list[$i]["question_type"] == "Select"){
											$scr = null;
											echo '<i>[Data type: SELECT]</i><br>';
											$var = 'select'.$question_list[$i]["qu_question_id"];
											if  ($question_list[$i]['qu_group'] == "AGGREGATE"){
													echo '[Data type: AGGREGATE]<br>';
											}
											if (isset($$var)){
												$option = $$var;
												for($o=0; $o < count($option); ++$o){
													echo $option[$o]["select_text"].'<br>';
												}
											}
										}
										elseif($question_list[$i]["question_type"] == "MultiSelect"){

											$var = 'mselect'.$question_list[$i]["qu_question_id"];
											echo '<i>[Data type: MULTI_SELECT]</i><br>';
											if (isset($$var)){
												$option = $$var;
												for($o=0; $o < count($option); ++$o){
														echo $option[$o]["select_text"].'<br>';
												}
											}
										}
										elseif($question_list[$i]["question_type"] == "PAIN_DIAGRAM"){
											$var = 'diagram'.$question_list[$i]["qu_question_id"];
											if (isset($$var)){
												$clinic_diagram_info = $$var;
											}
											if (isset($clinic_diagram_info)){
												echo 'Diagram name:'.$clinic_diagram_info["name"].$question_list[$i]["qu_question_id"].'<br>';
												echo 'File name:'.$clinic_diagram_info["diagram_name"].'<br>';
												echo '<img id="diagram" style="border:1px solid #FFFFFF;" src='.base_url().ltrim($clinic_diagram_info["diagram_link"],'./').' width= 200px height= 200px >';
												echo '</img>';
											}
										}
										elseif($question_list[$i]["question_type"] == "Yes_No"){
											echo '<i>[Data type: YES_NO]</i>';
										}
										echo '</td>';
									echo '</tr>';
								}
							}
						?>

					</table>
					<br><br><i>HHIMS v2 Questionnaire documenter  </i>	

