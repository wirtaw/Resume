<?php
class Person {
	private $name;
	private $surname;
	private $birthdate;
	public function showName() {
		return  $this->name." ".$this->surname."";
	}
	public function showBirthdate() {
		return date('Y-m-d',$this->birthdate);
	}
	public function setName($str) {
		$this->name=$str;
	}
	public function setSurname($str) {
		$this->surname=$str;
	}
	public function setBirthdate($str) {
		$this->birthdate=strtotime($str);
	}
	public function getName() {
		return $this->name;
	}
	public function getSurname() {
		return $this->surname;
	}
	public function getBirthdate() {
		return date('Y-m-d H:i:s',$this->birthdate);
	}
}
class Resume {
	public $person=null;
	public $country='';
	public $languages=[];
	public $profession='';
	public $hobbies=[];
	public $website='';
	public $projects=[];
	public $email='';
	public $errors=null;
	public function show() {
		if (null === $this->errors) {
			echo "Name: ".$this->person->showName()."\n
			Age: ".$this->person->showBirthdate()."\n
			Email: ".$this->email."\n
			Country: ".$this->country."\n
			City: ".$this->city."\n";
		} else {
			echo "old infomation ".$this->errors."\n";
		}
	}
	public function exportToJson() {
		$p = array(
			'name'=>array(
				'first'=>$this->person->getName(),
				'last'=>$this->person->getSurname()
			),
			'birthdate'=>$this->person->getBirthdate(),
			'city'=>$this->city,
			'country'=>$this->country,
			'languages'=>$this->languages,
			'profession'=>$this->profession,
			'hobbies'=>$this->hobbies,
			'website'=>$this->website,
			'projects'=>$this->projects,
			'email'=>$this->email
			);
		$persons = array($p);
		file_put_contents('info/data.json',json_encode($persons));
	}
	public function init($string) {
		$jsonObj=json_decode($string);
		if (null === $jsonObj && json_last_error() !== JSON_ERROR_NONE) {
			$this->errors=json_last_error();
		} else {
			foreach($jsonObj->person as $p){
				$this->person= new Person;
				$this->person->setName($p->name->first);
				$this->person->setSurname($p->name->last);
				$this->person->setBirthdate($p->birthdate);
				$this->city = $p->city;
				$this->country = $p->country;
				$this->languages = $p->languages;
				$this->profession = $p->profession;
				$this->hobbies = $p->hobbies;
				$this->website = $p->website;
				$this->projects = $p->projects;
				$this->email = $p->email;
			}
		}
	}
};
$filepath = 'info/data.json';
$resume = null;
if (class_exists('Resume')) {
	$resume = new Resume;
} 
if (file_exists($filepath)) {
	$string=file_get_contents($filepath);
	$resume->init($string);
}
if (is_object($resume)) {
	$resume->show();
	//$resume->exportToJson();
}
?>
