<?php
class Hobbie {
	private $title;
	public function setTitle($str) {
		$this->title=$str;
	}
	public function getTitle() {
		return $this->title;
	}
}
class Project {
	private $title;
	public function setTitle($str) {
		$this->title=$str;
	}
	public function getTitle() {
		return $this->title;
	}
}
class Language {
	private $title;
	public function setTitle($str) {
		$this->title=$str;
	}
	public function getTitle() {
		return $this->title;
	}
}
class Person {
	private $name;
	private $surname;
	private $birthdate;
	private $languages;
	private $projects;
	private $hobbies;
	public function showName() {
		return  $this->name." ".$this->surname."";
	}
	public function showBirthdate() {
		return date('Y-m-d',$this->birthdate);
	}
	public function getLanguages() {
		$mas = array();
		for ($i=0 ;$i < count($this->languages);$i++) {
			$mas[]=$this->languages[$i]->getTitle();
		}
		return $mas;
	}
	public function getProjects() {
		$mas = array();
		for ($i=0 ;$i < count($this->projects);$i++) {
			$mas[]=$this->projects[$i]->getTitle();
		}
		return $mas;
	}
	public function getHobbies() {
		$mas = array();
		for ($i=0 ;$i < count($this->hobbies);$i++) {
			$mas[]=$this->hobbies[$i]->getTitle();
		}
		return $mas;
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
	public function setLanguages($mas) {
		
		$this->languages = array();
		for ($i=0 ;$i < count($mas);$i++) {
			$language= new Language;
			$language->setTitle($mas[$i]);
			$this->languages[]=$language;
			$language= null;
		}
	}
	public function setProjects($mas) {
		
		$this->projects = array();
		for ($i=0 ;$i < count($mas);$i++) {
			$project= new Project;
			$project->setTitle($mas[$i]);
			$this->projects[]=$project;
			$project= null;
		}
	}
	public function setHobbies($mas) {
		
		$this->hobbies = array();
		for ($i=0 ;$i < count($mas);$i++) {
			$hobbie= new Hobbie;
			$hobbie->setTitle($mas[$i]);
			$this->hobbies[]=$hobbie;
			$hobbie= null;
		}
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
			City: ".$this->city."\n
			Languages: ".implode(', ',$this->person->getLanguages())."\n
			Profession: ".$this->profession."\n
			Website: ".$this->website."\n
			Projects: ".implode(', ',$this->person->getProjects())."\n
			Hobbies: ".implode(', ',$this->person->getHobbies())."\n";
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
			'languages'=>$this->person->getLanguages(),
			'profession'=>$this->profession,
			'hobbies'=>$this->person->getHobbies(),
			'website'=>$this->website,
			'projects'=>$this->person->getProjects(),
			'email'=>$this->email
			);
		$mas= array($p);
		$persons = array('person'=>$mas);
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
				$this->languages = $this->person->setLanguages($p->languages);
				$this->profession = $p->profession;
				$this->hobbies = $this->person->setHobbies($p->hobbies);
				$this->website = $p->website;
				$this->projects = $this->person->setProjects($p->projects);
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
	
	$resume->exportToJson();
}
?>
