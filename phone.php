<?php
class Phone{
	public $id;
	public $manufacturer;
	public $OS;
	public $price;
	public $memory;
	public $screen;
	public $model;
	public $sim_card;
	/*
	public function __construct(){
		$this->id=0;
		$this->manufacturer=0;
		$this->OS=0;
		$this->price=0;
		$this->memory=0;
		$this->screen=0;
		$this->model=0;
		$this->sim_card=0;
	}*/
	
	public function __construct($manufacturer,$OS,$price,$memory,$screen,$model,$sim_card,$id=null){
		$this->id=$id;
		$this->manufacturer=$manufacturer;
		$this->OS=$OS;
		$this->price=$price;
		$this->memory=$memory;
		$this->screen=$screen;
		$this->model=$model;
		$this->sim_card=$sim_card;
	}
	
	public function get_arr(){
		return array("id" =>$this->id,
					 "manufacturer" =>$this->manufacturer,
					 "OS" =>$this->OS,
					 "price" =>$this->price,
					 "memory" =>$this->memory,
					 "screen" =>$this->screen,
					 "model" =>$this->model,
					 "sim_card" =>$this->sim_card);
	}
	public function info(){
		$arr=$this->get_arr();
		foreach($arr as $key=>$value){
			
			print '"'.$key.'" : "'.$value.'"<br>';
		}
		
	}

	
	
}
?>