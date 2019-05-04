<?php
require ('references.php');
if('POST'==$_SERVER['REQUEST_METHOD']){
		$daomy=DAOFactory::get_obj_DAO(DAOenum::MySQLDAO);
		$daomon=DAOFactory::get_obj_DAO(DAOenum::MongoDBDAO);
	switch(migration_case()){
		case 'to_mysql':
		$stat=Migration::migrate($daomon,$daomy);
		show_stat($stat);
		break;
		
		case 'to_mongo':
		$stat=Migration::migrate($daomy,$daomon);
		show_stat($stat);
		break;
		
		case '':
		print"Something went wrong";
		break;
	}
}else{
	show_migration_form();
}


?>