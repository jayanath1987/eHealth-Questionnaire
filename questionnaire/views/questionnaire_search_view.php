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

include_once("header.php");	///loads the html HEAD section (JS,CSS)

?>


	<div class="container" style="width:95%;">
		<div class="row" >
		  <div class="col-md-10 ">
		  		<?php 
					if ( isset($error) ){
						echo '<div class="alert alert-danger"><b>ERROR:</b>'.$error.'</div>';
						exit;
					}
				?>		  
				<div class="panel panel-default"  >
					<div class="panel-heading"><b>Questionnaire search</b>
					</div>
					<?php 
						//var_dump($questionnaire_list); 
						if (!empty($questionnaire_list)){
							echo ' <table class="table table-striped ">';
								for ( $i=0; $i < count($questionnaire_list); ++$i ){
                                    
                                    $label ="";
                                    if ($questionnaire_list[$i]["soap_type"]!=""){
											$letter = substr($questionnaire_list[$i]["soap_type"],2,1);
											//echo $letter;
											if ($this->config->item($letter)!=""){
												$label = '<span class="badge" style="background:'.$this->config->item($letter).';">'.$letter.'</span>';
                                            }
                                     }       
									echo ' <tr>';
										echo ' <td>'.($i+1).'</td>';
										echo ' <td>'.$questionnaire_list[$i]['code'].'</td>';
										echo ' <td>'.character_limiter($questionnaire_list[$i]['name'], 30).'</td>';
										echo ' <td>'.$label.'</td>';
                                        echo ' <td><span class="label label-success">'.$questionnaire_list[$i]['applicable_to'][0].'</span></td>';
										echo ' <td>'.character_limiter($questionnaire_list[$i]['description'], 30).'</td>';
										$q_type = "SOAP";
										if ($questionnaire_list[$i]['soap_type'] == "A.General"){
											$q_type = "GENERAL";
										}
										else 	if ($questionnaire_list[$i]['soap_type'] == "F.Notes"){
											$q_type = "NOTES";
										}
										
										echo ' <td><button type="button" class="btn btn-primary btn-xs" onclick="select_questionnaire(\''.$questionnaire_list[$i]['qu_questionnaire_id'].'\',\''.$q_type.'\')">Select</button></td>';
									echo ' </tr>';
								}
							echo ' </table>';
						}
					?>
				</div>
			</div>
		</div>
	</div>



	
	<script language="javascript">
		function select_questionnaire(id,q_type){
			opener.update_value(id,q_type);
			window.close();
		}
	</script>