<?php

class etm
{
	public $xml;
	public $folders;
	public $placemarks;

	function __construct()
	{
		$this->xml = simplexml_load_file('my_files/territory.kml');
		$ns = $this->xml->getDocNamespaces();
		if(isset($ns[""])){
			$this->xml->registerXPathNamespace('kml', 'http://earth.google.com/kml/2.2');
		}
	}

	function all()
	{
		return $this->xml->xpath("//kml:Document/kml:Folder");
	}

	function folderLookup($locality, $map)
	{
		return $this->xml->xpath("//kml:Document/kml:Folder[kml:name/text()='" . $locality . "']/kml:Folder[kml:name/text()='" . $map . "']");
	}

	function placemarkLookup($locality, $map)
	{
		return $this->xml->xpath("//kml:Document/kml:Folder[kml:name/text()='" . $locality . "']/kml:Placemark[kml:name/text()='" . $map . "']");
	}

	function getMap($locality, $map)
	{
		$result = '';
		$folders = $this->folderLookup($locality, $map);
		$placemarks = $this->placemarkLookup($locality, $map);

		//list folders if is set
		if (empty($folders) == false) {
			foreach($folders as $folder) {
				foreach($folder->Placemark as $placemark) {
					$placemark->styleUrl = '#standardStyle';
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