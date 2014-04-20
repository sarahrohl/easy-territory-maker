<?php

class EasyTerritoryMaker
{
    public $secure = false;

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

	function __construct($secure = false)
	{
        $this->secure = $secure;

        //Include types
        require_once('Territory.php');
        require_once('TerritoryCollection.php');

        //Throw helpful error if territory.kml doesn't exist
        if (!file_exists('my_files/territory.kml')) {
            throw new Exception("The 'territory.kml' file, created with Google Earth, does not exist in the 'my_files' folder.  Please save it there, and continue.");
        }

        //get territory.kml file, and read it to xml, and setup kml namespace
        $this->territoryString = file_get_contents('my_files/territory.kml');
        $territoryXML = simplexml_load_string($this->territoryString);
        $territoryXML->registerXPathNamespace('kml', 'http://earth.google.com/kml/2.2');
        $this->territoryXML = $territoryXML;

        global $etm_config; require_once("config.php");
        //get google.spreadsheet.key, a spreadsheet, for use with tracking changes with territory over time

        $key = $etm_config->googleSpreadsheetKey;
        $url = 'https://spreadsheets.google.com/feeds/list/' . $key . '/od6/public/values';
        $this->territoryActivityString = $territoryActivityString = file_get_contents($url);
        $territoryActivityXML = simplexml_load_string($territoryActivityString);
        $territoryActivityXML->registerXPathNamespace('gsx', 'http://schemas.google.com/spreadsheets/2006/extended');
        $territoryActivityXML->registerXPathNamespace('openSearch', 'http://a9.com/-/spec/opensearchrss/1.0/');
        $this->territoryActivityXML = $territoryActivityXML;
		$dateFormat = $etm_config->dateFormat;

        foreach ($territoryActivityXML->entry as $child) {
            $row = $child->children('gsx', TRUE);
            $territoryName = $row->territory . '';
            if (empty($this->territories[$territoryName])) {
                $this->territories[$territoryName] = new TerritoryCollection();
            }

            $territory = new Territory($row, $dateFormat);
            $this->territories[$territoryName]->add($territory);

            if (empty($territory->in)) {
                $this->territories[$territoryName]->out = true;
                $this->territoriesOut[$territoryName] = $territory;
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
    function lookupKml($territory, $locality = null)
	{
		$territoryKML = null;
        //If locality is set (ie Folder), use folder, otherwise, use Placemark
        if (empty($locality)) {
	        //try first for  Placemark, then Folder
	        $territoryKML = $this->territoryXML->xpath(<<<XPATH
//kml:Document
    /kml:Placemark[kml:name/text()='$territory']
XPATH
            );

	        if (empty($territoryKML)) {
		        $territoryKML = $this->territoryXML->xpath(<<<XPATH
//kml:Document
    /kml:Folder
        /kml:Placemark[kml:name/text()='$territory']
XPATH
				);
	        }
        } else {
	        $territoryKML = $this->territoryXML->xpath(<<<XPATH
//kml:Document
    /kml:Folder[kml:name/text()='$locality']
        /kml:Placemark[kml:name/text()='$territory']
XPATH
            );
        }

		if (empty($territoryKML)) {
			return null;
		}

		return $territoryKML;
	}

	/**
	 * @param $territory
	 * @param $locality
	 * @return null|Territory
	 */
	public function lookup($territory, $locality = null)
	{
		$kml = $this->lookupKml($territory, $locality);

		if ($kml == null) {
			return null;
		}

		$locality = $kml[0]->xpath("..");

		$root = $locality[0]->xpath("..");

		require_once('Territory.php');

        if (isset($root[0]->Document)) {
            $foundTerritory = new Territory();
            $foundTerritory->territory = $kml[0]->name . '';
            $foundTerritory->congregation = $locality[0]->name . '';
        }

        else {
            $foundTerritory = new Territory();
            $foundTerritory->territory = $kml[0]->name . '';
            $foundTerritory->locality = $locality[0]->name . '';
            $foundTerritory->congregation = $root[0]->name . '';
        }

        if ($this->secure) {
            if (isset($this->territories[$foundTerritory->territory])) {
                $preSecureTerritoryCollection = $this->territories[$foundTerritory->territory];
                $preSecureTerritory = $preSecureTerritoryCollection->mostRecent();
                $publisherNameParts = explode(" ", $preSecureTerritory->publisher);
                $initials = '';
                foreach($publisherNameParts as $part) {
                    $initials .= $part{0};
                }

                $attemptedInitials = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $_REQUEST['initials']));
                $actualInitials = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $initials));
                if (
                    !empty($attemptedInitials)
                    && $attemptedInitials ===  $actualInitials
                ) {
                    //SUCCESS!
                    session_start();
                    $_SESSION['viewFolder'] = true;
                    return $foundTerritory;
                } else {
                    //Failed attempt
                    session_destroy();
                    return null;
                }
            }
        }

		return $foundTerritory;
	}

    /**
     * @param $territory
     * @param $locality
     * @return string
     */
    function getSingleKml($territory, $locality)
	{
		$result = '';
		$territoryItems = $this->lookupKml($territory, $locality);

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


	/**
	 * @param $territory
	 * @return null|TerritoryCollection
	 *
	 */
	function getSingleStatus($territory)
	{
		$activity = null;
		if (array_key_exists($territory, $this->territories)) {
			$activity = $this->territories[$territory];
		}

		$status = '<span style="color: green;">In</span>';;
		if ($activity != null) {
			if ($activity->out) {
				$expected = date("m/d/Y", $activity->mostRecent()->idealReturnDate);
				$status = "<span style='color: blue;'>Out - Expected $expected</span>";
			}
		}

		return $status;
	}

	public function sort()
	{
		usort($this->territoriesOut, function (Territory $a, Territory $b) {
			return $a->out - $b->out;
		});
	}

	/**
	 * @return Territory[]
	 */
	function getIdealReturnDates()
	{
		$this->sort();
		return $this->territoriesOut;
	}

	/**
	 * @return Territory[]
	 */
	public function getPriority()
	{
		$territories = array();
		foreach ($this->territories as $territoryCollection) {
			$territory = $territoryCollection->mostRecent();
			if (!empty($territory->in)) {
				$territories[$territory->territory] = $territory;
			}
		}

		usort($territories, function (Territory $a, Territory $b) {
			return $a->in - $b->in;
		});

		return $territories;
	}
}