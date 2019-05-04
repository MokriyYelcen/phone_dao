<?php

require ('references.php');//инклюды
$dao= DAOFactory::get_obj_DAO(DAOenum:: MySQLDAO);//create dao object, operation with db
$errors=array();

	if('POST'==$_SERVER['REQUEST_METHOD']){//server config
		
		//////////////////////////////////////////////////////////////////////////////////////////////
		
		
		switch (control()){//определяет вариант использования
			case'del':
			
			
			if(delete_row_by_id($dao)){//в этой функции запрос на удаление
				 show_phone_table($dao);
			}else{
				 show_errors();
			}
			
			break;
			case'add':
			
			if($input=validate_input()){
				print'validation went well </br>';
				 if(add_phone($dao,$input)){
					show_phone_table($dao);
				}else{
					show_errors();
				}
				
				
			}
			else{
				print'validation didnt go well </br>';show_errors();
				}
			
			break;
			case'update':

			if(update_price($dao)){
				show_phone_table($dao);
			}
			else{
				show_errors();
			}
		}
		
	}else{
		show_phone_table($dao);
	}
	
	


?>