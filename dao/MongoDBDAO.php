<?

use MongoDB\BSON\ObjectID;
use MongoDB\Driver\Query;
class MongoDAO implements IMyDao{
	private $client;
	private $db;

	public function __construct(){
		$this->client=new MongoClient();
		$this->db=$this->client->phone;
		
	}
	public  function _add(Phone $item){
		
		$errors=array();
		//$query= new Query([],);
		$doc=array(
					 "manufacturer" =>$item->manufacturer,
					 "OS" =>$item->OS,
					 "price" =>$item->price*1,
					 "memory" =>$item->memory,
					 "screen" =>$item->screen,
					 "model" =>$item->model,
					 "sim_card" =>$item->sim_card
					 );
		if($res=$this->db->phone->insert($doc)){
			
		}
		else{
			$errors['connection']='can`t connect MongoDB(insert)';
		}
		return $errors;
	}
	public  function _delete($id){
		$errors=array();
		//$query= new Query([],);
		if($res=$this->db->phone->remove(array('_id' => new MongoId($id)), array("justOne" => true))){
			
		}
		else{
			$errors['connection']='can`t connect MongoDB(delete)';
		}
		return $errors;
	}
	public  function _search($searched=null){
		$errors=array();
		if($searched==null){
			
			if($res=$this->db->phone->find()){//find()==select*from phones
				$arr=array();
				foreach($res as $doc ){
					
					$arr[]=new Phone(
											$doc["manufacturer"],
											$doc["OS"],
											$doc["price"],
											$doc["memory"],
											$doc["screen"],
											$doc["model"],
											$doc["sim_card"],
											$doc['_id']
									);

				}
			return $arr;
			}
			else{
				$errors['connection']='can`t connect MongoDB';
			}
		}
		return $errors;
	}
	public  function _change_price($difference){
		$errors=array();
		//$query= new Query([],);
		if($res=$this->db->phone->update([],array('$inc'=>array("price"=>$difference*1)),['multiple'=>true])){
			
		}
		else{
			$errors['connection']='can`t connect MongoDB(update)';
		}
		return $errors;
	}
	public  function _available_manufacturer(){
		$errors=array();
		if($res=$this->db->manufacturer->find()){
			$arr=array();
			foreach($res as $doc){
				$arr[]=$doc['manufacturer'];
			}
			return $arr;
		}
		else{
			$errors['connection']='can`t connect MongoDB';
		}
		return $errors;
	}
	public  function _available_OS(){
		$errors=array();
		if($res=$this->db->OS->find()){
			$arr=array();
			foreach($res as $doc){
				$arr[]=$doc['OS'];
			}
			return $arr;
		}
		else{
			$errors['connection']='can`t connect MongoDB';
		}
		return $errors;
	}


public  function _clear_db(){
		$errors=array();
		$delman=$this->db->manufacturer->remove(array(), array("justOne" => false));
		$delOS=$this->db->OS->remove(array(), array("justOne" => false));
		if($delman&&$delOS){
			if($res=$this->db->phone->remove(array(), array("justOne" => false))){

			}
			else{
				$errors['connection']='can`t connect MongoDB(delete all from phone)';
			}
		}else{
			$errors['connection']='can`t connect MongoDB(delete all from OS, manufacturers)';
		}
		return $errors;
		
	}
		public  function _add_manufacturer($new){
		$errors=array();
		if($res=$this->manufacturer->insert(array('manufacturer' => $new))){
			
		}
		else{
			$errors['connection']='can`t connect MongoDB(insert_manufacturer)';
		}
		return $errors;
	}
	public  function _add_OS($new){
		$errors=array();
		if($res=$this->OS->insert(array('OS' => $new))){
			
		}
		else{
			$errors['connection']='can`t connect MongoDB(insert_manufacturer)';
		}
		return $errors;
	}
	public  function _delete_test(){
		$errors=array();
		//$query= new Query([],);
		if($res=$this->db->manufacturer->remove(array('manufacturer' => 'test'))){
			print "test manufacturers remove";
		}
		else{
			$errors['connection']='can`t connect MongoDB(delete)';
		}
		print_r ($errors);
	}
}

?>








