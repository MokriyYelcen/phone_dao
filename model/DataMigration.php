<?php
class Migration{
	
	public static function migrate($source,$target){
		$migration_stat=array();
		$time_start = microtime_float(true);
//for correct data migration, need to clear target db
		$errors_clear = $target->_clear_db();
		if(count($errors_clear)==0){
			$migration_stat['clearing']='clearing went well';
			
			//////////
//migrate manufaturers
		$content_man=$source->_available_manufacturer();
		$count_man=count($content_man);
		$i_man=0;
		foreach($content_man as $ex){
			if(count($target->_add_manufacturer($ex))==0){
				$i_man++;
			}
		}
		$migration_stat['manufacturer']="$i_man manufacturer records from $count_man copied well";
//////////
//migrate body type
		$content_bt=$source->_available_OS();
		$count_bt=count($content_bt);
		$i_bt=0;
		foreach($content_bt as $ex){
			if(count($target->_add_OS($ex))==0){
				$i_bt++;
			}
		}
		$migration_stat['body_type']="$i_bt body type records from $count_bt copied well";
//////////
//migrate cars
		$content_car=$source->_search();
		$count_car=count($content_car);
		$i_car=0;
		foreach($content_car as $ex){
			if(count($target->_add($ex))==0){
			$i_car++;
			}
		}
		$migration_stat['car']="$i_car car records from $count_car copied well";
		$time_end = microtime_float(true);
		$time=$time_end-$time_start;
		$migration_stat['time']="migration took a $time sec";
			
		}
		else{
			foreach($errors_clear as $key=>$value){
				$migration_stat[$key]=$value;
			}
		}

/////////
		return $migration_stat;
	}
	
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
?>