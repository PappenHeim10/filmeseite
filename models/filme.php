<?php
namespace mvc;
include_once 'include/datenbank.php';

class Genre extends Datenbank
{
	// attributes
	private $id;
	private $genre;

	// konstruktor führt die funktion setDaten auf
	function __construct(Array $daten = []) 
	{
		$this->setDaten($daten);
	}
	
	public function setDaten(array $daten) // funktion prüft ob in einem übergebenen $array passende werte zu passenden settern der klasse admin befinden
	{
		if($daten)
		{
			foreach ($daten as $key => $value) 
			{
				$setter = "set" . ucfirst($key);
					
				if(method_exists($this, $setter))
				{
					$this->$setter($value);
				}
			}
		}
	}
	
	/////// Getter / Setter
	function setGenre($genre)
	{
		$this->genre = $genre;
	}
	
	function getGenre()
	{
		return $this->genre;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	// Methods
	public function insert(){}
    public function update($id){}
    public function delete($id){}
    public function select($id){}
    public function selectAll(){}
}