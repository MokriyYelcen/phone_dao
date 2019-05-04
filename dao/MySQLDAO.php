<?php



class MySQLDAO implements IMyDao{//interface
	private  $connection;
	public function __construct(){
		$this->connection= mysqli_connect('127.0.0.1','root','','test_db') ;
	}
	
	public  function _add(Phone $item){//итем-логический объект
		$errors=array();
		if($this->connection){
			$manuf=$item->manufacturer;
			$OS=$item->OS;
			if(!is_numeric($manuf)&& !is_numeric($OS)){
				mysqli_begin_transaction($this->connection /*,MYSQL_TRANS_START_READ_WRITE*/);
				if($tmp=mysqli_query($this->connection,"SELECT `id_manufacturer` FROM `manufecturers` WHERE `name` LIKE'%$manuf%'")){
					$row=mysqli_fetch_assoc($tmp);//разбивает рез-т запроса по полям в массив(ключ-имя столбца)
					$id_manufacturer=$row['id_manufacturer'];
				}
				else{
					$errors['obj phone']='no such manufacturer in db';
				}
				if($tmp1=mysqli_query($this->connection,"SELECT `id_OS` FROM `OS` WHERE `name` LIKE'%$OS%'")){
					$row=mysqli_fetch_assoc($tmp1);
					$id_OS=$row['id_OS'];
				}
				else{
					$errors['obj phone']= $OS.' -> no such OS in db';
				}
			}
			else{
				$errors['obj phone']='manufacturer and OS are numeric';
			}
			
			
			if(mysqli_query($this->connection,"INSERT INTO `mobile_phones`(`id_manufacturer`,
																		   `id_OS`,
																		   `price`,
																		   `memory`,
																		   `screen`,
																		   `model`,
																		   `sim_card`) VALUES".'('.
																								"'".$id_manufacturer."',".
																								"'".$id_OS."',".
																								"'".$item->price ."',".
																								"'".$item->memory ."',".
																								"'".$item->screen ."',".
																								"'".$item->model ."',".
																								"'".$item->sim_card ."')"
																															)){
																																
				mysqli_commit($this->connection);
				
			}
			else{
				mysqli_rollback($this->connection);
				$errors['insert']='can`t insert row';
			}
		}
		else{
			$errors['connection']='can`t connect';
		}
		return $errors;
	}
	
	
	public  function _delete($id){
		$errors=array();
		if($this->connection){
			if(mysqli_query($this->connection,"DELETE FROM `mobile_phones` WHERE `id_phone`=$id ")){
				
			}
			else{
				$errors['delete']='can`t delete';
			}
			
		}
		else{
			$errors['connection']='can`t connect mysql';
				
		}
		return $errors;
	}
	public  function _search($searched=null){
		$errors=array();
		if($searched==null){
			if($this->connection){
				if($res=mysqli_query($this->connection,"
														SELECT `id_phone`,`manufecturers`.`name` as 'manufecturer',`OS`.`name` as 'OS',`price`,`memory`,`screen`,`model`,`sim_card`
														FROM `mobile_phones` 
														INNER JOIN `manufecturers` ON(`mobile_phones`.`id_manufacturer`=`manufecturers`.`id_manufacturer`)
														INNER JOIN `OS` ON(`mobile_phones`.`id_OS`=`OS`.`id_OS`)
														")
				)
				{
					$arr=array();
					while($row=mysqli_fetch_assoc($res)){
						$arr[]=new Phone(
										 $row['manufecturer'],
										 $row['OS'],
										 $row['price'],
										 $row['memory'],
										 $row['screen'],
										 $row['model'],
										 $row['sim_card'],
										 $row['id_phone']);
					}
					
					return $arr;
				}
				else{
					$errors['read']='can`t read';
				}
			}
			else{
				$errors['connection']='can`t connect mysql';
			}
		}
		
		
		
		
		
		return $errors;
	}
	public  function _change_price($difference){
		$errors=array();
		if($this->connection){
			if(mysqli_query($this->connection,"UPDATE `mobile_phones` SET `price`=`price`+ $difference")){}
			else {
				$errors['update']='can`t update';
				
			}
				
				
		}
		else{
			$errors['connection']='can`t connect mysql';
		}
		return $errors;
	}
	
	public  function _available_manufacturer(){
		$errors=array();
		if($this->connection){
			if($res=mysqli_query($this->connection,"SELECT `name` FROM `manufecturers` ")){
				$arr=array();
				while($row=mysqli_fetch_assoc($res)){
					$arr[]=$row['name'];
				}
				return $arr;
			}
			else{
				$errors['read']='can`t read manufacturers';
			}
		}
		else{
			$errors['connection']='can`t connect mysql';
		}
	}
	
	public  function _available_OS(){
		$errors=array();
		if($this->connection){
			if($res=mysqli_query($this->connection,"SELECT `name` FROM `OS` ")){
				$arr=array();
				while($row=mysqli_fetch_assoc($res)){
					$arr[]=$row['name'];
				}
				return $arr;
			}
			else{
				$errors['read']='can`t read manufacturers';
			}
		}
		else{
			$errors['connection']='can`t connect mysql';
		}
	}
	

public function _clear_db(){
		$errors=array();
		if($this->connection){
			$delpho=mysqli_query($this->connection,"DELETE FROM `mobile_phones` WHERE `id_phone` IS NOT NULL");
			
			if($delpho){
				$delman=mysqli_query($this->connection,"DELETE FROM `manufecturers` WHERE `id_manufacturer` IS NOT NULL");
				$delOS=mysqli_query($this->connection,"DELETE FROM `OS` WHERE `id_OS` IS NOT NULL");
				if($delman&&$delOS){
				
				}
				else{
					$errors['delete']='can`t delete all from manufecturers OS';
				}
			}
			else{
					$errors['delete']='can`t delete all from phone';
				}
			
		}
		else{
			$errors['connection']='can`t connect mysql';
				
		}
		return $errors;
	}



	public  function _add_manufacturer($new){
		$errors=array();
		if($this->connection){
			if($res=mysqli_query($this->connection,"INSERT INTO`manufecturers`(`name`) VALUES ('$new')")){
				
			}
			else{
				$errors['insert']='can`t insert manufacturer';
			}
		}
		else{
			$errors['connection']='can`t connect mysql';
		}
		return $errors;
	}
	public  function _add_OS($new){
		$errors=array();
		if($this->connection){
			if($res=mysqli_query($this->connection,"INSERT INTO `OS`(`name`) VALUES ('$new')")){
				
			}
			else{
				$errors['insert']='can`t insert OS';
			}
		}
		else{
			$errors['connection']='can`t connect mysql';
		}
		return $errors;
	}
}
?>