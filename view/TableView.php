<?php
function show_phone_table($dao){
	//формируем селект для вставки производителя
	$range_m=$dao->_available_manufacturer();
	$select_manuf='<select size="0"  name="manufacturer[]">';
	foreach($range_m as $available){
		$select_manuf.="<option value=\"$available\">$available</option>";
	}
    $select_manuf.='</select>';
	//формируем селект для вставки оси
	$range_os=$dao->_available_OS();
	$select_OS='<select size="0"  name="OS[]">';
	foreach($range_os as $available1){
		$select_OS.="<option value=\"$available1\">$available1</option>";
	}
    $select_OS.='</select>';
	
	
	$arr=$dao->_search();
	$action=$_SERVER['PHP_SELF'];
	print"<form  name=\"dele\" method=\"post\" action=\"$action\" align=\"center\" style=\"width:800\" > ";
	print'<table align="center" border="1" >';
	foreach($arr as $ex){
		$id=$ex->id;
		$manufacturer=$ex->manufacturer;
		$OS=$ex->OS;
		$price=$ex->price;
		$memory=$ex->memory;
		$screen=$ex->screen;
		$model=$ex->model;
		$sim_card=$ex->sim_card;
	$r='<tr>'.
									"<td>$model</td>".
									"<td>$manufacturer</td>".
									"<td>$price</td>".
									"<td>$memory</td>".
									"<td>$screen</td>".
									"<td>$OS</td>".
									"<td>$sim_card</td>".
									"<td><button name=\"button[]\" value=\"$id\" ><strong style=\"color:RED;background:YELLOW;font-size:30px;\">&#10008</strong></button></td>".
									
			'</tr>';
		print $r;
	}

	print"<tr>".
	"<td>"."<input type=\"text\" name=\"model\" placeholder=\"model\" >"."</td>".
	"<td>".$select_manuf."</td>".
	"<td>"."<input type=\"text\" name=\"price\" placeholder=\"price\" >"."</td>".
	"<td>"."<input type=\"text\" name=\"memory\" placeholder=\"memory\" >"."</td>".
	"<td>"."<input type=\"text\" name=\"screen\" placeholder=\"screen\" >"."</td>".
	"<td>".$select_OS."</td>".
	"<td>"."<input type=\"text\" name=\"sim_card\" placeholder=\"sim card\" >"."</td>".
	"<td>"."<input type=\"submit\" name=\"add\" value=\"add new phone\" style=\"font-size:30px;\" >"."</td>".
	"</tr>";
	print "<tr>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."<input type=\"text\" name=\"difference\" placeholder=\"difference\" >"."</td>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."<input type=\"submit\" name=\"update\" value=\"Change price\" style=\"font-size:30px;\" >"."</td>".
	"</tr>";
	print "<tr>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."</td>".
	"<td>"."<a href=\"DataMigrationController.php\">Migration</a>"."</td>".
	"</tr>";
	print"</table>".'</form>';
}

function delete_row_by_id($dao){
	$id=$_POST['button'][0];
	$errors=$dao->_delete($id);
	if(count($errors)==0){
		return true;
	}
	else{
		foreach($errors as $ex =>$value){
			$GLOBALS['errors'][$ex]=$value;
		}
		return false;
	}
}

function add_phone($dao,$valid_new_phone){
	
		$errors=$dao->_add($valid_new_phone);
		if(count ($errors)==0){
			return true;
		}
		else{
			foreach($errors as $key=>$value){
				$GLOBALS['errors'][$key]=$value;
			}
			return false;

		}
	
}

function validate_input(){
	
	$val_errors=0;
	
 	if(strlen(trim($model=$_POST['model']))<=255){
		$inputmodel=htmlspecialchars($model);
	}else{
		$GLOBALS["errors"]['model']='lengths must be less than 255 symbols';
		$val_errors++;
	}
	
if(count($_POST['manufacturer'])!=0){
		$inputmanufacturer=htmlspecialchars($_POST['manufacturer'][0]);
	}else{
		$GLOBALS["errors"]['manufacturer']='select it please';
		$val_errors++;
	}
	$price=$_POST['price'];
	if(is_numeric($price)&&$price<=100000000){
		$inputprice=$price;
	}else{
		$GLOBALS["errors"]['price']=' must be numeric and less than 100000000';
		$val_errors++;
	}
	$memory=$_POST['memory'];
	if(16==$memory||32==$memory||64==$memory||128==$memory||256==$memory||512==$memory){
		$inputmemory=$memory;
	}else{
		$GLOBALS["errors"]['memory']='must be numeric and degree of two less than 512';
		$val_errors++;
	} 
	$screen=$_POST['screen'];
	if(is_numeric($screen)&&$screen<=15){
		$inputscreen=$screen;
	}else{
		$GLOBALS["errors"]['screen']='screen must be less than 15 inch';
		$val_errors++;
	} 
	
	if(count($_POST['OS'])!=0){
		$inputOS=htmlspecialchars($_POST['OS'][0]);
	}else{
		$GLOBALS["errors"]['OS']='lengths must be less than 255 symbols and be a known OS';
		$val_errors++;
	} 
	$sim_card=$_POST['sim_card'];
	if(is_numeric($sim_card)&&$sim_card<=2){
		$inputsim_card=$sim_card;
	}else{
		$GLOBALS["errors"]['sim_card']='single or double';
		$val_errors++;
	} 
	
	
	$input=new Phone(
										 $inputmanufacturer,
										 $inputOS,
										 $inputprice,
										 $inputmemory,
										 $inputscreen,
										 $inputmodel,
										 $inputsim_card,
										 null);
	
	
	
	if($val_errors==0){
		return $input;
	}else{
		return false;
	}
}

function update_price($dao){
	$difference=$_POST['difference'];
	$errors=$dao->_change_price($difference);
	if(0==count($errors)){
		return true;
	}
	else{
		foreach($errors as $ex =>$value){
			$GLOBALS['errors'][$ex]=$value;
		}
		return false;
	}
}
function show_errors(){
	$errors=$GLOBALS["errors"];
	foreach($errors as $place=>$error){
		print'Error in '.$place.' text: '.$error;
	}
}
function control(){
	if($_POST['button'][0]){return 'del';}
	if($_POST['add']){return 'add';}
	if($_POST['update']){return 'update';}
}

?>