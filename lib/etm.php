<?php

class etm
{
    public $string;
	public $xml;
	public $folders;
	public $placemarks;

	function __construct()
	{
        $dir = dirname(dirname(__FILE__));
        chdir($dir);
        $this->string = file_get_contents('my_files/territory.kml');
		$this->xml = simplexml_load_string($this->string);
		$ns = $this->xml->getDocNamespaces();
		if(isset($ns[""])){
			$this->xml->registerXPathNamespace('kml', 'http://earth.google.com/kml/2.2');
		}
	}

	function all()
	{
		return $this->xml->xpath("//kml:Document/kml:Folder | //kml:Document/kml:Placemark");
	}

	function folderLookup($map, $locality)
	{
        if (empty($locality)) {
            return $this->xml->xpath("//kml:Document/kml:Placemark[kml:name/text()='" . $map . "']");
        } else {
            return $this->xml->xpath("//kml:Document/kml:Folder[kml:name/text()='" . $locality . "']/kml:Folder[kml:name/text()='" . $map . "']");
        }
	}

	function placemarkLookup($map, $locality)
	{
		return $this->xml->xpath("//kml:Document/kml:Folder[kml:name/text()='" . $locality . "']/kml:Placemark[kml:name/text()='" . $map . "']");
	}

	function getMap($map, $locality)
	{
		$result = '';
		$folders = $this->folderLookup($map, $locality);
		$placemarks = $this->placemarkLookup($map, $locality);

		//list folders if is set
		if (empty($folders) == false) {
			foreach($folders as $folder) {
                if (empty($folder->Placemark)) {
                    $folder->styleUrl = '#standardStyle';
                } else {
                    foreach($folder->Placemark as $placemark) {
                        $placemark->styleUrl = '#standardStyle';
                    }
                }
				$result = $folder->asXML();
			}
		}

		//list placemarks if is set
		if (empty($placemarks) == false) {
			foreach($placemarks as $placemark) {
				$placemark->styleUrl = '#standardStyle';
				$result = $placemark->asXML();
			}
		}

		return $result;
	}
}