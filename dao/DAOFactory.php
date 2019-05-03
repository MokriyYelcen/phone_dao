<?php


class DAOFactory{
	
	public static function get_obj_DAO( $type){//type db
		if ($type=='mysql'){
			
			return new MySQLDAO();//obj dao mysql
		}
		if ($type=='MongoDB'){
			
			return new MongoDAO();
		}
	}
}
?>