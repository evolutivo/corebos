<?php
/*************************************************************************************************
 * Copyright 2011-2013 JPL TSolucio, S.L.  --  This file is a part of evvtMap vtiger CRM extension.
* You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
* Vizsage Public License (the "License"). You may not use this file except in compliance with the
* License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
* and share improvements. However, for proper details please read the full License, available at
* http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
* the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
* applicable law or agreed to in writing, any software distributed under the License is distributed
* on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and limitations under the
* License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
*************************************************************************************************
*  Module       : evvtMap
*  Version      : 5.4.0
*  Author       : JPL TSolucio, S. L.
*************************************************************************************************/

/* Helper class for GeoCoder, contains latitude and longitude */
class GeoCode
{
        public $latitude;
        public $longitude;
        public $approx;

        public function __construct($latitude, $longitude, $approx=false)
        {
                $this->latitude = $latitude;
                $this->longitude = $longitude;
                $this->approx = $approx;
        }
}


class GeoCoder{
        
        private $delay = 0;
        private $baseUrl = "";
        public $status = 0;
        
        public function __construct()
        {
                $this->baseUrl = 'http://maps.google.com/maps/api/geocode/xml?sensor=false';
        }

        public function getDelay()
        {
                return $this->delay;
        }

        public function setDelay($value)
        {
                $this->delay = value;
        }

        /** 
		Search the given location in cache
		if $id is specified, retrieve directly the record
        */
        private function searchCache($id, $state, $city, $postalCode, $street="", $country="")
		{
			global $adb;
			if ($id) {
				$query = "SELECT lat,lng,if(street='',1,0) as approx FROM vtiger_evvtmap WHERE evvtmapid=?";
				$result = $adb->pquery($query,array($id));
				if ($result && $adb->num_rows($result)>0) {
					$row = $adb->fetch_array($result);
					return new GeoCode($row['lat'],$row['lng'],$row['approx']);
				}
			} else {
				$param = array();
				$basequery = $query = "SELECT lat,lng,if(street='',1,0) as approx FROM vtiger_evvtmap WHERE state=? AND city=? AND postalcode=? ";
				$param[] = trim($state);
				$param[] = trim($city);
				$param[] = trim($postalCode);
				$street = trim($street);
				if (!empty($street)) {
					$query .= "AND street=? ";
					$param[] = $street;
				}
				$country = trim($country);
				if (!empty($country)) {
					$query .= "AND country=? ";
					$param[] = $country;
				}
				$result = $adb->pquery($query,$param);
				if ($result && $adb->num_rows($result)>0) {
					$row = $adb->fetch_array($result);
					return new GeoCode($row['lat'],$row['lng'],$row['approx']);
				} else {  //try with a simpler query
					$result = $adb->pquery($basequery,$param);
					if ($result && $adb->num_rows($result)>0) {
						$row = $adb->fetch_array($result);
						return new GeoCode($row['lat'],$row['lng'],$row['approx']);
					}
				}
			}
			return null;
		}

        /**
        Search location on Google Maps GeoCoder and store the result on the database.
        @return a GeoCode() object on success, null otherwise
        */
        private function retrive($id, $state, $city, $postalCode, $street="", $country="")
        {
                //echo "No hit in cache: contacting Google Maps.<br/>";

                // Initialize delay in geocode speed
                $address = "$street, $postalCode, $city, $country, $state";
                $request_url = $this->baseUrl ."&address=" . urlencode($address);
                $xml = simplexml_load_file($request_url,"SimpleXMLElement",LIBXML_COMPACT);
                if(!$xml)
                {
                        //        echo ("Google Maps URL not loading: $request_url");
                        return null;
                }
		$this->status = $xml->status;
                if ($xml->status == 'OK') {
                        // Successful geocode
                        return $this->updateCache(array($id,$state,$city,$postalCode,$street,$country),$xml);
                } else {
                        if ($xml->status == 'ZERO_RESULTS') {
                                //attempt only with state, postalCode and city
                                $request_url = $this->baseUrl . "&address=" . urlencode("$postalCode, $city, $state");
                                $xml = simplexml_load_file($request_url);
                                if($xml)
                                {
                                        if ($xml->status == 'OK') {
                                                // Successful geocode
                                                return $this->updateCache(array($id,$state, $city, $postalCode, "", ""),$xml);
                                        }
                                }
                        }
                        // failure to geocode
                        //echo "Address '$address' failed to geocoded. Status: ".$xml->status;
                        return null;
                }
        }

        /**
        Search the given location and return the geographic coordination, 
        First serach in the cache (database table), if no result found lookup the location on Google Maps GeoCoder and save the response for future requests.
        @return a GeoCode() object on success, null otherwise
        */
        public function getGeoCode($id, $state, $city, $postalCode, $street="", $country="")
        {
			// if (empty($city)) return null;
			$ret = $this->searchCache($id, $state, $city, $postalCode, $street, $country); //check cache
			if($ret)
				return $ret;
			else //retrieve data from google maps geocoder and save the data into database
				$ret = $this->retrive($id, $state, $city, $postalCode, $street, $country);
			return $ret;
        }

        /**
        Save new coordinates to database.
        @param $location array(id,state,city,postal code, street, country)
        @param $coordinates object with new coordinates
        @return a GeoCode() object on success, null otherwise
        */
        private function updateCache($location,$coordinates)
        {
			global $adb,$log;
			$lat = $location[] = $coordinates->result->geometry->location->lat;
			$lng = $location[] = $coordinates->result->geometry->location->lng;
			$query = 'INSERT INTO vtiger_evvtmap (evvtmapid,state,city,postalcode,street,country,lat,lng) VALUES ('.generateQuestionMarks($location).')';
			$update_result = $adb->pquery($query,$location);
			if ($update_result===false) {
				return null;
			} else {
				return new GeoCode($lat,$lng,$location[4]=="");
			}
        }

        /**
        Populate the cache given a set of locations, pay attention to delay of each request
        Input array is a multidimensional array, each entry is a location with this composition:
                [$id, $state, $city, $postalCode, $street, $country]
                0     1       2      3            4              5 
        */
        public function populateCache($locations)
        {
			// Initialize delay in geocode speed
			$delay = 500000;
			$retry = 0;

			// Iterate through the rows, geocoding each address
			foreach ($locations as $location) {
				$geocode_pending = true;

				while ($geocode_pending) {
					$address = "{$location[4]}, {$location[3]}, {$location[2]}, {$location[5]}, {$location[1]}"; 
					$id = $location[0];
					$request_url = $this->baseUrl . "&address=" . urlencode($address);
					$xml = simplexml_load_file($request_url);
					if (!$xml) {
						// "Can't retrieve '$address' whith url=$request_url<br/>";
						break;
					}
					if ($xml->status == 'OK') {
						// Successful geocode
						$geocode_pending = false;
						$update_result = $this->updateCache($location,$xml);
						if (!$update_result) {
							break;
						}
					} else if ($xml->status == 'OVER_QUERY_LIMIT') {
						// sent geocodes too fast
						// Geocode too fast. Increasing delay
						$delay += 100000;
						$retry++;
						// We've hit the daily limit
						if ($retry > 3) return "LIMIT_REACHED";
					} else if ($xml->status == "ZERO_RESULTS") {
						// attempt only with state, postalCode and city
						$request_url = $this->baseUrl . "&address=" . urlencode("{$location[3]}, {$location[2]}, {$location[1]}");
						$xml = simplexml_load_file($request_url);
						if ($xml) {
							if ($xml->status == 'OK') {
								// Successful geocode
								$update_result = $this->updateCache(array($location[0],$location[1], $location[2], $location[3], "", ""),$xml);
							}
							$geocode_pending = false; //skip to next location
						} else {
							// failure to geocode
							$geocode_pending = false;
							// "Address $id => '$address' failed to geocoded. Received status ".$xml->status." <br/>";
						}
						usleep($delay);
					}
					flush();
				}
			}
		}
}
?>
