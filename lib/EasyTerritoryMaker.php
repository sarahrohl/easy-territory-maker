<?php

class EasyTerritoryMaker
{
	public $territoryString;
    /**
     * @var SimpleXMLElement
     */
    public $territoryXML;
    public $territoryActivityString;
    /**
     * @var SimpleXMLElement
     */
    public $territoryActivityXML;

    /**
     * @var TerritoryCollection[]
     */
    public $territories = array();

    /**
     * @var Territory[]
     */
    public $territoriesOut = array();

	function __construct()
	{
        //Include types
        require_once('Territory.php');
        require_once('TerritoryCollection.php');

        //Change directory to that of the parent directory
        $dir = dirname(dirname(__FILE__));
        chdir($dir);

        //Throw helpful error if territory.kml doesn't exist
        if (!file_exists('my_files/territory.kml')) {
            throw new Exception("The 'territory.kml' file, created with Google Earth, does not exist in the 'my_files' folder.  Please save it there, and continue.");
        }

        //get territory.kml file, and read it to xml, and setup kml namespace
        $this->territoryString = file_get_contents('my_files/territory.kml');
        $territoryXML = simplexml_load_string($this->territoryString);
        $territoryXML->registerXPathNamespace('kml', 'http://earth.google.com/kml/2.2');
        $this->territoryXML = $territoryXML;

        //get google.spreadsheet.key, a spreadsheet, for use with tracking changes with territory over time
        if (file_exists('my_files/google.spreadsheet.key')) {
            $key = file_get_contents('my_files/google.spreadsheet.key');
            $url = 'https://spreadsheets.google.com/feeds/list/' . $key . '/od6/public/values';
            $this->territoryActivityString = $territoryActivityString = file_get_contents($url);
            $territoryActivityXML = simplexml_load_string($territoryActivityString);
            $territoryActivityXML->registerXPathNamespace('gsx', 'http://schemas.google.com/spreadsheets/2006/extended');
            $territoryActivityXML->registerXPathNamespace('openSearch', 'http://a9.com/-/spec/opensearchrss/1.0/');
            $this->territoryActivityXML = $territoryActivityXML;

            foreach ($territoryActivityXML->entry as $child) {
                $row = $child->children('gsx', TRUE);
                $territoryName = $row->territory . '';
                if (empty($this->territories[$territoryName])) {
                    $this->territories[$territoryName] = new TerritoryCollection();
                }

                $territory = new Territory($row);
                $this->territories[$territoryName]->add($territory);

                if (empty($territory->in)) {
                    $this->territoriesOut[$territoryName] = $territory;
                }
            }
        }
	}

    /**
     * @return SimpleXMLElement[]
     */
    function all()
	{
        //Search through the xml at Document.Folder, or Document.Placemark
		return $this->territoryXML->xpath(<<<XPATH
//kml:Document
    /kml:Folder
|//kml:Document
    /kml:Placemark
XPATH
        );
	}

    /**
     * @param string $territory
     * @param string $locality
     * @return SimpleXMLElement[]
     */
    function lookup($territory, $locality)
	{
        //If locality is set (ie Folder), use folder, otherwise, use Placemark
        if (empty($locality)) {
            return $this->territoryXML->xpath(<<<XPATH
//kml:Document
    /kml:Placemark[kml:name/text()='$territory']
XPATH
            );
        } else {
            return $this->territoryXML->xpath(<<<XPATH
//kml:Document
    /kml:Folder[kml:name/text()='$locality']
        /kml:Placemark[kml:name/text()='$territory']
XPATH
            );
        }
	}

    /**
     * @param $territory
     * @param $locality
     * @return string
     */
    function getSingleKml($territory, $locality)
	{
		$result = '';
		$territoryItems = $this->lookup($territory, $locality);

		//list folders if is set
		if (empty($territoryItems) == false) {
			foreach($territoryItems as $territoryItem) {
                if (empty($territoryItems->Placemark)) {
                    $territoryItem->styleUrl = '#standardStyle';
                } else {
                    foreach($territoryItem->Placemark as $placemark) {
                        $placemark->styleUrl = '#standardStyle';
                    }
                }
				$result = $territoryItem->asXML();
			}
		}

		return $result;
	}


}