<?php
// * /
// Drag0n-PHP ~ Public
// This script sends the UserNames of the Users that contacted a User Account.
// This script also has the ability to delete all info of UserNames that contacted the client on read and can be set by client (Logged User).
// * Data Actions *
// Description: The script by default know who contacted the User last, after it sended the contacted Users data once and won't send all the data to client more than once unless specified.
// If i = NUMBER is 1 it deletes user data after read.
// If i = NUMBER is 3 it will send all the contacted Users data to client again.
// * Extended logging Setup *
// Description: The scipt will collect information for some unplaned actions that shouldn't happen. Example: A folder specified for this script should exist but it's not.
// Info :
// - *1. Admin log file is located in /Dba/logs/getu.php.log
// - *2. It should be set-enabled inside the file.
// Variable ExtendedLogging_E: (default : True)
// True  := Will collect all the information for some unwanted behavior from this file and save it to a log file.
// False := Will not collect information. If it was enabled it won't clear the logs.
// * * * Information for: Data that need to be send for requests * * *
// The indicator for this file is 0 and should be placed inside AccessToken like this AccessToken:0
// Sends *1. n=USERNAME *2. p=ACCESSTOKEN:NUMBER *3. i=NUMBER
// / *
date_default_timezone_set('UTC');
$Get_UserName = $_GET['n'] ?: null); // Username
$Get_AccessToken = $_GET['p'] ?: null); // AccessToken
$Get_SACData = $_GET['i'] ?: 2); // Data Actions
unset($_GET);
//Todo Mode
$myfriend = new ThisIsNotTheMainTheater;
$myfriend.ConstWhatFor($Get_UserName);
//
class ThisIsNotTheMainTheater{
	// Set Variables
	Private $IsFunctionInUse = False;
	Private $IsBPacketUsed = False;
	Private $ExtendedLogging_E = True;
	// Start Functions
	// * * *
	// Path Checker : Validation of path
	// Todo Mode
	private fuction PathCheck($tmp_Path, $tmp_Details){
		// Fast check of provided data.
		is_string($tmp_Path) === True ?: return False;
		is_string($tmp_Details) === True ?: return False;
		// Set Variables
		$tmp_PC_PathLength = 1;
		$tmp_PC_findDot = 0;
		$tmp_PC_findSlash = 0;
		$tmp_PC_StaticOne = False; // Dba
		$tmp_PC_StaticTwo = False; // Dbu
		$tmp_PC_getExtension = " ";
		$tmp_PC_AllowedExtensionOne = "mdma";
		$tmp_PC_AllowedExtensionTwo = "inUse";
		$tmp_PC_AllowedExtensionThree = "bip";
		$tmp_PC_AllowedExtensionFour = " ";
		// Get path length
		$tmp_PC_PathLength = strlen($tmp_Path) ?: 1;
		if((int)$tmp_PC_PathLength === 0){
			return False;
		}elseif((int)$tmp_PC_PathLength === 1){
			return False;
		}else{

			$tmp_PC_StaticOne = str_contains($tmp_Path, "../Dba/") ?: False;
			$tmp_PC_StaticTwo = str_contains($tmp_Path, "../Dbu/") ?: False;
			if(($tmp_PC_StaticOne === False and $tmp_PC_StaticTwo === False) or ($tmp_PC_StaticOne === True and $tmp_PC_StaticTwo === True)){
				return False;
			}else{
				$tmp_PC_findDot = substr_count($tmp_Path, ".") ?: 0;
				$tmp_PC_findSlash = substr_count($tmp_Path, "/") ?: 0;
				if($tmp_PC_StaticOne === True){
					$tmp_PC_getExtension = explode($tmp_Path, ".")((int)$tmp_PC_findDot-1) ?: " ";
					if($tmp_PC_getExtension === $tmp_PC_AllowedExtensionThree){ //bip
						$tmp_PC_findDot === 4 ?: return False;
						$tmp_PC_findSlash === 4 ?: return False;
						if($tmp_PC_PathLength === 147){
						//
						}
					}else{
						return False;
					}
					if($tmp_PC_PathLength > 66 and $tmp_PC_PathLength < 100){
						return True;
					}else{
						return False;
					}
				}
				if($tmp_PC_StaticTwo === True){
					$tmp_PC_findDot === 3 ?: return False;
					$tmp_PC_findSlash === 4 ?: return False;
					if($tmp_PC_PathLength > 66 and $tmp_PC_PathLength < 100){
						return True;
					}else{
						return False;
					}
					$tmp_PC_getExtension = explode($tmp_Path, ".")((int)$tmp_findme) ?: " ";
					if(str_contains($tmp_PC_getExtension, "inUse") === True and substr_count($tmp_PC_getExtension) === 5){
						//
					}
				}
			}
		}
	}
	// * * *
	// Unlink
	//
	private function UnlinkProperties($RemoveProper){
		$IsItOkay = False;
		$IsItOkay = PathChecker($RemoveProper) ?: False;
		if($IsItOkay === True){
			clearstatcache($RemoveProper);
			if(file_exists($RemoveProper) === true){
				unlink($RemoveProper);
				return True;
			}
		}else{
			return False;
		}
	}
	// * * *
	// Writes to files
	// 0 = Success *|* 1 = Fail at the first one *|* 2 = Success at first failed at second *|* 3 = All failled *|* 4 = Nothing Happened
	private function PutConO($aa, $ab, $ag){
		$los_co = 4;
		if(strlen($aa) > 28){
			$tmp_ca = $aa . ".inUse";
			clearstatcache($tmp_ca);
			if(file_exists($tmp_ca)){
				$los_co = 1;
			}else{
				file_put_contents($tmp_ca, " ", FILE_APPEND|LOCK_EX);
				if(strlen($ab) === 0){
					// DELETE DATA
					file_put_contents($aa, "", LOCK_EX);
				}else{
					//APPEND DATA
					file_put_contents($aa, $ab, FILE_APPEND);
				}
				unlink($tmp_ca);
				$los_co = 0;
			}
			clearstatcache($aa);
		}
		if(strlen($ag) > 28){
			$tmp_ca = $ag . ".inUse";
			clearstatcache($tmp_ca);
			if(file_exists($tmp_ca)){
				if($los_co == 0){
					$los_co = 2;
				}else{
					$los_co = 3;
				}
			}else{
				// DELETE DATA
				file_put_contents($tmp_ca, " ", FILE_APPEND|LOCK_EX);
				file_put_contents($ag, "", LOCK_EX);
				unlink($tmp_ca);
				if($los_co != 0){
					$los_co = 3;
				}
			}
		}
		unset($aa, $ab, $ag);
		return($los_co);
	}
	// * * *
	// Remove duplicate and some invalid.
	// Todo Mode
	private function CFMess($tmp_CF_Data){
		is_string($tmp_CF_Data) === True ?: $tmp_CF_Data = " ";
		$tmp_CFM_Return = " ";
		if(!empty($tmp_CF_Data) and is_string($tmp_CF_Data) === True){
			$tmp_CFM_Count = 0;
			$tmp_CFM_Count = substr_count($tmp_CF_Data, ";") ?: $tmp_CFM_Count = 0;
			if($tmp_CFM_Count > 0){
				// Extended Logging detect corrupted data with fast calculation
				if($ExtendedLogging_E === True){
					if($tmp_CFM_Count != 0){
						$tmp_CFM_EL_Min = (int)$tmp_CFM_Count * 5;
						$tmp_CFM_EL_Max = (int)$tmp_CFM_Count * 15;
						$tmp_CFM_DataLength = strlen($tmp_CF_Data);
						if(($tmp_CFM_DataLength >= $tmp_CFM_EL_Min and $tmp_CFM_DataLength <= $tmp_CFM_EL_Max) == False){
							//ExtendedLogging;
						}
					}
				}
				for($x = 0; $x < $tmp_CFM_Count; $x++) {
					$tmp_CFM_tmp = " ";
					$tmp_CFM_tmpLength = 0;
					$tmp_CFM_tmp = explode(";", $tmp_CF_Data)[$x] . ";" ?: $tmp_CFM_Return = " ";
					$tmp_CFM_tmpLength = (strlen($tmp_CFM_tmp) ?: $tmp_CFM_tmpLength = 0;
					if(!empty($tmp_CFM_tmp) and is_string($tmp_CFM_tmp) === True and str_contains($tmp_CFM_Return, $tmp_CFM_tmp) == false and $tmp_CFM_tmpLength > 4 and $tmp_CFM_tmpLength < 15){
						$tmp_CFM_AllowedChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789;";
						for($n = 0; $n < strlen($tmp_CFM_tmp); $n++){
							if($n > 13 or strlen($tmp_CFM_tmp) < 5){
								$tmp_CFM_tmp = " ";
								break;
							}
							$tmp_CFM_tmpName = $tmp_CFM_tmp[$n];
							if(strpos($tmp_CFM_AllowedChars, $tmp_CFM_tmpName) === false){
								$tmp_CFM_tmp = " ";
								break;
							}
						}
						if(is_string($tmp_CFM_Return) === True and strlen($tmp_CFM_tmp) > 4){
							strlen($tmp_CFM_Return) < 5 ? $tmp_CFM_Return = $tmp_CFM_tmp : $tmp_CFM_Return = $tmp_CFM_Return . $tmp_CFM_tmp;
						}
					}
				}
			}
			// Unset Variables
			$tmp_CFM_Count = 0;
		}
		return $tmp_CFM_Return;
	}
	// * * *
	// Join strings, remove duplicate and put the new first.
	private function CFJoin($tmp_CFS_One, $tmp_CFS_Two){
		$tmp_CFS_Return = " ";
		if(!empty($tmp_CFS_One) and !empty($tmp_CFS_Two) and is_string($tmp_CFS_One) === True and is_string($tmp_CFS_Two) === True){
			$tmp_CFS_OneCount = substr_count($tmp_CFS_One, ";") ? substr_count($tmp_CFS_One, ";") : 0;
			$tmp_CFS_TwoCount = substr_count($tmp_CFS_Two, ";") ? substr_count($tmp_CFS_Two, ";") : 0;
			if($tmp_CFS_OneCount > 0 and $tmp_CFS_TwoCount > 0){
				for($x = 0; $x < $tmp_CFS_OneCount; $x++){
					if(strlen($tmp_CFS_OneCount) <= 0){
						break;
					}
					if(str_contains($tmp_CFS_OneCount, explode(";", $tmp_CFS_TwoCount)[$x])){
						$tmp_CFS_OneCount = str_replace(explode(";", $tmp_CFS_TwoCount)[$x].";", "", $tmp_CFS_OneCount);
					}
				}
				// Add the newer sender first. This should checked by client too.
				$tmp_CFS_Return = $tmp_CFS_TwoCount . $tmp_CFS_OneCount;
			}
		}
		return $tmp_CFS_Return;
	}
	// * * *
	// Server Response And Data
	private void function SResponse($SData){
		is_string($SData) === True ?: $SData = "Packet!";
		$OneZeroTwoFour = 1024;
		$TwoZeroFourEight = 2048;
		$FourZeroNineSix = 4096;
		$DataStringLength = strlen($SData);
		// Try fake data for response length prediction-guessing hardening.
		if($DataStringLength < $OneZeroTwoFour){ // < 1024
			if($DataStringLength === 1023){
				$SData = $SData." ";
			}else{
				$SData[1022] = " ";
			}
		}elseif($DataStringLength > $OneZeroTwoFour and $DataStringLength < $TwoZeroFourEight){ // 1024
			if($DataStringLength === 2047){
				$SData = $SData." ";
			}else{
				$SData[2046] = " ";
			}
		}elseif($DataStringLength > $TwoZeroFourEight and $DataStringLength < $FourZeroNineSix){ // 2048
			if($DataStringLength === 4095){
				$SData = $SData." ";
			}else{
				$SData[4094] = " ";
			}
		}
	}
	// * * *
	// User Check
	private function UserWhatA($amn){
		is_string($amn) === true ?: $amn = " ";
		if(strlen($amn) > 17 and strlen($amn) < ){
			
		}
	}
	// * * *
	// Ephemeral Access Tokens Manager
	private function ChangeToken(){
		$OneTimeKey = hash('sha256', rand(101, 999).rand(1001,9999).rand(1001,9999).rand(10001,99999).rand(10001,99999).rand(1001,9999).rand(1001,9999).rand(101, 999));
		$TmpSaveKey = hash('sha256', $OneTimeKey);
		$nfileu = $UserPath."/".$TmpSaveKey.".mdma";
		$TmpSaveKey = "0000";
		clearstatcache($nfileu);
		if(file_exists($nfileu) === true){
			return False;
		}else{
			file_put_contents($nfileu, " ");
		}
		return True;
	}
	private function FixDate($ptmp_daymonthyearhour){
		if(is_string($ptmp_daymonthyearhour) === false or strlen($ptmp_daymonthyearhour) != 64){
			$ptmp_daymonthyearhour = " ";
			$ptmp_daymonthyearhour = hash('sha256', date("d:m:Y:G")); // X2
		}
		return $ptmp_daymonthyearhour;
	}
	private function FixTime($ptmptime){
		is_string($ptmptime) === false ?: 1;
		$ptmptime >= 0 ? $ptmptime += 1 : $ptmptime = 1;
		return $ptmptime;
	}
	private function FixIP($ptmp_ipaddress){
		if(is_string($ptmp_ipaddress) === false or strlen($ptmp_daymonthyearhour) != 64){
			$ptmp_ipaddress = $_SERVER["REMOTE_ADDR"] ?: " ";
			//	Add address changed check for when the program excecuted
			if(is_string($ptmp_ipaddress) === true and strlen($ptmp_ipaddress) > 6 and ((strlen($ptmp_ipaddress) < 16 and substr_count($ptmp_ipaddress, ".") === 3) or (strlen($ptmp_ipaddress) < 40 and substr_count($ptmp_ipaddress, ":") > 2 and substr_count($ptmp_ipaddress, ":") < 8))){
				$ptmp_ipaddress = hash('sha256', $ptmp_ipaddress) ?: " "; // X2
			}else{
				$ptmp_ipaddress = " ";
			}
		}
		return $ptmp_ipaddress;
	}
	private void function BPacket($exponent, $ptmp_daymonthyearhour, $ptmptime, $ptmp_ipaddress){
		if($IsBPacketUsed === False){
			$IsBPacketUsed = True;
			$ptmp_daymonthyearhour = FixDate($ptmp_daymonthyearhour) ?: " ";
			$ptmptime = FixDate($ptmptime) ?: 0;
			$ptmp_ipaddress = FixDate($ptmp_ipaddress) ?: " ";
			$tmpfileip = "../Dba/iprb/".$ptmp_daymonthyearhour."/".$ptmptime.".".$ptmp_ipaddress.".bip";
			if(is_string($tmpfileip) === true and strlen($tmpfileip) === 147 and substr_count($tmpfileip, ".") === 4 and substr_count($tmpfileip, "/") === 3 and str_contains($tmpfileip, "../Dba/iprb/") === true and str_contains($tmpfileip, ".bip") === true){
				clearstatcache($tmpfileip);
				if(file_exists($tmpfileip) === false and $ptmptime <= 5){
					file_put_contents($tmpfileip, " ");
				}elseif(file_exists($tmpfileip) === true and $ptmptime < 5){ // Check if next log exist.
					for($h = $ptmptime; $h <= 5; $h++){
						$ptmptime = $h ?: " ";
						$ptmp_daymonthyearhour = FixDate($ptmp_daymonthyearhour) ?: " ";
						$ptmptime = FixDate($ptmptime) ?: 0;
						$ptmp_ipaddress = FixDate($ptmp_ipaddress) ?: " ";
						$tmpfileip = "../Dba/iprb/".$ptmp_daymonthyearhour."/".$h.".".$ptmp_ipaddress.".bip";
						clearstatcache($tmpfileip);
						if(file_exists($tmpfileip) === false and $ptmptime === $h){
							file_put_contents($tmpfileip, " ");
							break;
						}elseif($ptmptime !== $h){
							$ptmptime = 5;
							$ptmp_daymonthyearhour = FixDate($ptmp_daymonthyearhour) ?: " ";
							$ptmptime = FixDate($ptmptime) ?: 0;
							$ptmp_ipaddress = FixDate($ptmp_ipaddress) ?: " ";
							$tmpfileip = "../Dba/iprb/".$ptmp_daymonthyearhour."/".$h.".".$ptmp_ipaddress.".bip";
							file_put_contents($tmpfileip, " ");
							break;
						}
					}
				}else{
					//
					if((int)$ptmptime === 0){
						$ptmptime = 5;
						$tmpfileip = "../Dba/iprb/".$ptmp_daymonthyearhour."/".$ptmptime.".".$ptmp_ipaddress.".bip";
						file_put_contents($tmpfileip, " ");
					}
				}
				if($ptmptime >= 5){
					SResponse("Banned!");
				}else{
					if(is_string($exponent) === true and strlen($exponent) > 0){
						SResponse("$exponent");
					}else{
						SResponse("Packet!");
					}
				}
			}else{
				SResponse("Packet!");
			}
			$IsBPacketUsed = False;
		}
	}
	// * * *
	// Extended Logging
	private function Extended_Logging($ELData){
		$Extended_Logging_folder = "../Dba/Logs";
		clearstatcache($Extended_Logging_folder);
		if(file_exists($Extended_Logging_folder) === false){
			mkdir($Extended_Logging_folder, 0600);
		}
		$Extended_Logging_file = "../Dba/logs/getu.php.log";
		PathCheck($Extended_Logging_file);
		clearstatcache($Extended_Logging_file);
		if(file_exists($Extended_Logging_file) === true){
			file_get_contents($Extended_Logging_file);
			file_put_contents($Extended_Logging_file);
			$Extended_Logging_data = $ELData;
		}
	}
	public function $constwhatfor(){
		$tmp_ipaddress = $_SERVER["REMOTE_ADDR"] ?: " ";
		if(is_string($tmp_ipaddress) === true and strlen($tmp_ipaddress) > 6 and ((strlen($tmp_ipaddress) < 16 and substr_count($tmp_ipaddress, ".") === 3) or (strlen($tmp_ipaddress) < 40 and substr_count($tmp_ipaddress, ":") > 2 and substr_count($tmp_ipaddress, ":") < 8))){
			$tmp_ipaddress = hash('sha256', $tmp_ipaddress) ?: " "; // X2
		}else{
			$tmp_ipaddress = " ";
			unset($Get_UserName, $Get_AccessToken, $Get_SACData);
		}
		if(strlen($tmp_ipaddress) === 64){
			// ** Create Date Hash
			$tmp_daymonthyearhour = hash('sha256', date("d:m:Y:G")); // X2
			// ** Get Server Date and Time
			$tmp_serverday = date("j");
			$tmp_servermonth = date("n");
			$tmp_serveryear = date("Y");
			$tmp_serverhour = date("G");
			// ** IP logs
			$bannedforhourfolder = "../Dba/iprb/".$tmp_daymonthyearhour;
			clearstatcache($bannedforhourfolder);
			if(file_exists($bannedforhourfolder) === false){
				$bansfolder = "../Dba/iprb/";
				clearstatcache($bansfolder);
				if(file_exists($bansfolder) === false){
					$rootfolder = "../Dba";
					clearstatcache($rootfolder);
					if(file_exists($rootfolder) === false){
						mkdir("../Dba", 0600);
					}
					mkdir("../Dba/iprb", 0600);
				}
				mkdir("../Dba/iprb/".$tmp_daymonthyearhour, 0600);
			}
			$onefileip = "../Dba/iprb/".$tmp_daymonthyearhour."/1.".$tmp_ipaddress.".bip";
			$twofileip = "../Dba/iprb/".$tmp_daymonthyearhour."/2.".$tmp_ipaddress.".bip";
			$threefileip = "../Dba/iprb/".$tmp_daymonthyearhour."/3.".$tmp_ipaddress.".bip";
			$fourfileip = "../Dba/iprb/".$tmp_daymonthyearhour."/4.".$tmp_ipaddress.".bip";
			$fivefileip = "../Dba/iprb/".$tmp_daymonthyearhour."/5.".$tmp_ipaddress.".bip";
			$tmp_ip_blockedrequests = 0;
			clearstatcache();
			if(file_exists($fivefileip)){
				$tmp_ip_blockedrequests = 5;
			}elseif(file_exists($fourfileip)){
				$tmp_ip_blockedrequests = 4;
			}elseif(file_exists($threefileip)){
				$tmp_ip_blockedrequests = 3;
			}elseif(file_exists($twofileip)){
				$tmp_ip_blockedrequests = 2;
			}elseif(file_exists($onefileip)){
				$tmp_ip_blockedrequests = 1;
			}
			if($tmp_ip_blockedrequests >= 0 and $tmp_ip_blockedrequests < 5){
				is_string($Get_UserName) === true ? $UserName = urlencode($Get_UserName) : $UserName = " ";
				is_string($Get_AccessToken) === true ? $AccessToken = $Get_AccessToken : $AccessToken = " " ;
				is_string($Get_SACData) === true ? $SACData = urlencode($Get_SACData) : $SACData = 2;
				// Set allowed chars
				$tmp_allowedchars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
				$tmp_allowednums = "0123456789";
				// For UserName
				for($n = 0; $n < strlen($UserName); $n++){
					if($n > 13 or strlen($UserName) < 5){
						$UserName = " ";
						break;
					}
					$tmp_Name = $UserName[$n];
					$tmp_Name_allowedchars = $tmp_allowedchars.$tmp_allowednums;
					if(strpos($tmp_Name_allowedchars, $tmp_Name) === false){
						$UserName = " ";
						break;
					}
				}
				// For AccessToken
				for($n = 0; $n < strlen($AccessToken); $n++){
					if($n > 66){
						$AccessToken = " ";
						break;
					}
					$tmp_Token_allowedchars = $tmp_allowedchars.$tmp_allowednums;
					if($n === 64){
						$tmp_Token_allowedchars = ":";
					}elseif($n > 64){
						$tmp_Token_allowedchars = $tmp_allowednums;
					}else{
						$tmp_Token_allowedchars = $tmp_allowedchars.$tmp_allowednums;
					}
					$tmp_AccessToken_Chars = $AccessToken[$n];
					if(strpos($tmp_Token_allowedchars, $tmp_AccessToken_Chars) === false){
						$AccessToken = " ";
						break;
					}
				}
				// For SACData
				for($n = 0; $n < strlen($SACData); $n++){
					if($n > 1){
						$SACData = 2;
						break;
					}
					$tmp_SACData_Chars = $SACData[$n];
					if(strpos($tmp_allowednums, $tmp_SACData_Chars) === false){
						$SACData = 2;
						break;
					}
				}
				//
				if(is_string($UserName) === true and strpos($UserName, '.') === false and strpos($UserName, '%') === false and strpos($UserName, '/') === false and strpos($UserName, '<') === false and strpos($UserName, '>') === false and strpos($UserName, '$') === false and is_string($AccessToken) === true and strpos($AccessToken, '.') === false and strpos($AccessToken, '%') === false and strpos($AccessToken, '/') === false and strpos($AccessToken, '<') === false and strpos($AccessToken, '>') === false and strpos($AccessToken, '$') === false and substr_count($AccessToken, ':') === 1 and strlen($UserName) > 4 and strlen($UserName) < 15 and strlen($AccessToken) > 65 and strlen($AccessToken) <= 67 and $SACData > 0){
					$UserName = strtolower($UserName) ? strtolower($UserName) : " ";
					$tmp_AccessToken_GT = explode(':', $AccessToken)[0] ? explode(':', $AccessToken)[0] : 0; // Access Token
					$tmp_AccessToken_AC = explode(':', $AccessToken)[1] ? explode(':', $AccessToken)[1] : 0; // File Indicator
					$DoubleHash = hash('sha256', $tmp_AccessToken_GT) ? hash('sha256', $tmp_AccessToken_GT) : "A"; // Double X2
					$UserData = "0";
					$UserData = "../Dbu/".$UserName."/".$DoubleHash.".mdma" ?: "AAA"; // Set the Access Token path
					$tmp_ipfile = "0";
					$tmp_ipfile = "../Dbu/".$UserName."/".$tmp_ipaddress.".mdma" ?: "AAA"; // Set Logged User Ip file
					// ChessCER 1
					$tmp_one_nc = "AAA";
					$tmp_two_nc = "BBB";
					$tmp_one_nc = explode("/", $UserData)[2] ? explode("/", $UserData)[2] : "AAA"; // Check for name
					$tmp_two_nc = explode("/", $tmp_ipfile)[2] ? explode("/", $tmp_ipfile)[2] : "BBB"; // Check for name
					// ChessCER 2
					$tmp_one_ip = "AAA";
					$tmp_two_ip = "AAA";
					$tmp_forip = " ";
					$tmp_forip = explode("/", $UserData)[3] ? explode("/", $UserData)[3] : $tmp_one_ip = "AAA";
					substr_count($tmp_forip, ".") === 1 ? $tmp_one_ip = explode(".", $tmp_forip)[0] : $tmp_one_ip = "AAA";
					$tmp_forip = " ";
					$tmp_forip = explode("/", $tmp_ipfile)[3] ? explode("/", $tmp_ipfile)[3] : $tmp_one_ip = "AAA";
					substr_count($tmp_forip, ".") === 1 ? $tmp_two_ip = explode(".", $tmp_forip)[0] : $tmp_one_ip = "AAA";
					// ChessCER 3
					$tmp_UDone = "A";
					$tmp_UDone = $UserData[0].$UserData[1].$UserData[2].$UserData[3].$UserData[4].$UserData[5].$UserData[6] ? $UserData[0].$UserData[1].$UserData[2].$UserData[3].$UserData[4].$UserData[5].$UserData[6] : "A"; // The correct place and possision of first two . / 
					$tmp_UDtwo = 0;
					$tmp_UDtwo = $UserData[(int)strlen($UserName)+7] ? $UserData[(int)strlen($UserName)+7] : 0; // The possision of the last /
					$tmp_UDthree = "A";
					$tmp_UDthree = $UserData[(int)strlen($UserData)-5].$UserData[(int)strlen($UserData)-4].$UserData[(int)strlen($UserData)-3].$UserData[(int)strlen($UserData)-2].$UserData[(int)strlen($UserData)-1] ? $UserData[(int)strlen($UserData)-5].$UserData[(int)strlen($UserData)-4].$UserData[(int)strlen($UserData)-3].$UserData[(int)strlen($UserData)-2].$UserData[(int)strlen($UserData)-1] : "A"; // The correct place of mdma and possision of last .
					$tmp_IPone = "A";
					$tmp_IPone = $tmp_ipfile[0].$tmp_ipfile[1].$tmp_ipfile[2].$tmp_ipfile[3].$tmp_ipfile[4].$tmp_ipfile[5].$tmp_ipfile[6] ? $tmp_ipfile[0].$tmp_ipfile[1].$tmp_ipfile[2].$tmp_ipfile[3].$tmp_ipfile[4].$tmp_ipfile[5].$tmp_ipfile[6] : "A"; // The correct place and possision of first two . / 
					$tmp_IPtwo = 0;
					$tmp_IPtwo = $tmp_ipfile[(int)strlen($UserName)+7] ? $tmp_ipfile[(int)strlen($UserName)+7] : 0; // The possision of the last /
					$tmp_IPthree = "A";
					$tmp_IPthree = $tmp_ipfile[(int)strlen($tmp_ipfile)-5].$tmp_ipfile[(int)strlen($tmp_ipfile)-4].$tmp_ipfile[(int)strlen($tmp_ipfile)-3].$tmp_ipfile[(int)strlen($tmp_ipfile)-2].$tmp_ipfile[(int)strlen($tmp_ipfile)-1] ? $tmp_ipfile[(int)strlen($tmp_ipfile)-5].$tmp_ipfile[(int)strlen($tmp_ipfile)-4].$tmp_ipfile[(int)strlen($tmp_ipfile)-3].$tmp_ipfile[(int)strlen($tmp_ipfile)-2].$tmp_ipfile[(int)strlen($tmp_ipfile)-1] : "A"; // The correct place of mdma and possion of last .
					// ChessCER 4
					$tmp_UD_atlengthone = "A";
					$tmp_UD_atlengthone = explode("/", $UserData)[3] ? explode("/", $UserData)[3] : "A";
					$tmp_UD_atlengthtwo = 1;
					$tmp_UD_atlengthtwo = strlen($tmp_UD_atlengthone) + strlen($UserName) + 8 ? strlen($tmp_UD_atlengthone) + strlen($UserName) + 8 : rand(101, 499);
					$tmp_IP_iplengthone = "A";
					$tmp_IP_iplengthone = explode("/", $tmp_ipfile)[3] ? explode("/", $tmp_ipfile)[3] : "A";
					$tmp_IP_iplengthtwo = 2;
					$tmp_IP_iplengthtwo = strlen($tmp_IP_iplengthone) + strlen($UserName) + 8 ? strlen($tmp_IP_iplengthone) + strlen($UserName) + 8 : rand(501, 999);
					// End Game
					$ExtraData = "../Dbu/".$UserName."/".$tmp_ipaddress.".getu.omd"; // Check that again.
					$tmp_checkaccess = "../Dbu/".$UserName."/getu.".$tmp_AccessToken_GT.".inUse"; // Set inUse
					clearstatcache();
					if(strlen($tmp_AccessToken_AC) === 1 and (int)$tmp_AccessToken_AC === 3 and is_string($tmp_one_nc) === true and is_string($tmp_two_nc) === true and strlen($tmp_one_nc) > 4 and strlen($tmp_two_nc) > 4 and strlen($tmp_one_nc) < 15 and strlen($tmp_two_nc) < 15 and strlen($tmp_one_nc) === strlen($UserName) and $tmp_one_nc === $UserName and strlen($tmp_two_nc) === strlen($UserName) and $tmp_two_nc === $UserName and $tmp_one_nc === $tmp_two_nc and is_string($tmp_one_ip) === true and is_string($tmp_two_ip) === true and is_string($tmp_ipaddress) === true and strlen($tmp_one_ip) === 64 and strlen($tmp_two_ip) === 64 and $tmp_one_ip != $tmp_ipaddress and $tmp_one_ip != $tmp_two_ip and $tmp_two_ip === $tmp_ipaddress and is_string($UserData) === true and is_string($tmp_ipfile) === true and strlen($tmp_UDone) === 7 and $tmp_UDone == "../Dbu/" and is_string($tmp_UDtwo) === true and strlen($tmp_UDtwo) === 1 and $tmp_UDtwo == "/" and strlen($tmp_UDthree) === 5 and $tmp_UDthree == ".mdma" and strlen($tmp_IPone) === 7 and $tmp_IPone == "../Dbu/" and is_string($tmp_IPtwo) === true and strlen($tmp_IPtwo) === 1 and $tmp_IPtwo == "/" and strlen($tmp_IPthree) === 5 and $tmp_IPthree == ".mdma" and $tmp_UDthree === $tmp_IPthree and strlen($tmp_UD_atlengthone) === 69 and strlen($tmp_IP_iplengthone) === 69 and substr_count($UserData, ".") === 3 and substr_count($tmp_ipfile, ".") === 3 and substr_count($UserData, "/") === 3 and substr_count($tmp_ipfile, "/") === 3 and $tmp_UD_atlengthtwo === strlen($UserData) and $tmp_IP_iplengthtwo === strlen($tmp_ipfile) and $tmp_UD_atlengthtwo === $tmp_IP_iplengthtwo and strlen($UserData) > 81 and strlen($UserData) < 92 and strlen($tmp_ipfile) > 81 and strlen($tmp_ipfile) < 92 and $tmp_AccessToken_GT != $DoubleHash and hash_equals($tmp_AccessToken_GT, $DoubleHash) === false and strlen($UserData) === strlen($tmp_ipfile) and $UserData != $tmp_ipfile and hash_equals($DoubleHash, $tmp_ipaddress) === false and file_exists($UserData) === true and file_exists($tmp_ipfile) === true and file_exists($tmp_checkaccess) === false){
						file_put_contents($tmp_checkaccess, " "); // Set .inUse file
						$tmp_CorAccCheck = file_get_contents($UserData) ?: " ";
						if(strlen($tmp_CorAccCheck) === 64 and $tmp_CorAccCheck === $tmp_ipaddress and hash_equals($tmp_CorAccCheck,$tmp_ipaddress) === true){
							$UserPMess = "../Dbu/".$UserName."/Messages/Users.data";
							$UserTMess = "../Dbu/".$UserName."/Messages/Users.n.data";
							clearstatcache($UserPMess);
							clearstatcache($UserTMess);
							if(strlen($UserPMess) > 31 and strlen($UserPMess) < 42 and strlen($UserTMess) > 31 and strlen($UserTMess) < 42 and ((file_exists($UserPMess) === true and file_exists($UserTMess) === true) or (file_exists($UserPMess) === true or file_exists($UserTMess) === true))){
								clearstatcache($UserTMess);
								if(file_exists($UserTMess)){
									// Get new UserName data
									$GUserTData = file_get_contents($UserTMess) ? file_get_contents($UserTMess) : "";
									// Check new data length
									if(strlen($GUserTData) > 5){
										// Count UserName split total users
										$tmpmsgcount = substr_count($GUserTData, ";") ? substr_count($GUserTData, ";") : 0;
										if($tmpmsgcount < 1){
											$GUserTData = "";
										}
									}else{
										$GUserTData = "";
									}
								}else{
									$GUserTData = "";
								}
								clearstatcache($UserPMess);
								if(file_exists($UserPMess)){
									// Get user data from PMess file
									$GUserPData = file_get_contents($UserPMess) ? file_get_contents($UserPMess) : $GUserPData = "";
									if(strlen($GUserPData) > 5){
										$tmpmsgcount = substr_count($GUserPData, ";") ? substr_count($GUserPData, ";") : $tmpmsgcount = 0;
										if($tmpmsgcount < 1){
											$GUserPData = "";
										}
									}else{
										$GUserPData = "";
									}
								}else{
									$GUserPData = "";
								}
								// *** // ** ! * ! ** // *** // --- Data --- // *** // ** ! * ! ** // *** //
								$GUserCData = " ";
								if(strlen($GUserPData) > 5){
									$GUserCData = CFMess($GUserPData, $GUserTData);
									if($SACData === 1){
										$tmp_put = PutConO($UserPMess, "", $UserTMess);
										while($tmp_put != 0 or $tmp_put != 4){
											$tmp_put = PutConO($UserPMess, "", $UserTMess);
										}
									}else{
										$tmp_put = PutConO($UserPMess, $GUserCData, $UserTMess);
										while($tmp_put != 0 or $tmp_put != 4){
											$tmp_put = PutConO($UserPMess, $GUserCData, $UserTMess);
										}
									}
								}
								if(strlen($GUserTData) > 5){
									$GUserCData = CFMess($GUserTData, "");
									if($SACData === 1){
										$tmp_put = PutConO($UserTMess, "", $UserPMess);
										while($tmp_put != 0 or $tmp_put != 4){
											$tmp_put = PutConO($UserTMess, "", $UserPMess);
										}
									}else{
										$tmp_put = PutConO($UserPMess, $GUserCData, $UserTMess);
										while($tmp_put != 0 or $tmp_put != 4){
											$tmp_put = PutConO($UserPMess, $GUserCData, $UserTMess);
										}
									}
								}
								clearstatcache($ExtraData);
								if(!file_exists($ExtraData) and (int)$SACData !== 3){
									$GUserCData = CFMess($GUserTData, "");
								}
								if((strlen($GUserPData) < 5 or strlen($GUserTData) < 5) or (strlen($GUserPData) < 5 and strlen($GUserTData) < 5)){
									$GUserCData = "";
								}
								UnlinkProperties($ExtraData);
								// *** // ** ! * ! ** // *** // Send
								$tmp_S_DataLength = 0;
								is_string($GUserCData) === True ? $tmp_S_DataLength = strlen($GUserCData) : $tmp_S_DataLength = 0;
								if($tmp_S_DataLength > 5){
									$tmpmsgcount = substr_count($GUserCData, ";") ? substr_count($GUserCData, ";") : 0;
									if($tmpmsgcount > 0){
										SResponse($GUserCData);
									}else{
										SResponse("Error");
									}
								}else{
									if($tmp_S_DataLength > 0){
										SResponse("Error");
									}else{
										SResponse("NoUsers");
									}
								}
							}else{
								if(strlen($UserPMess) > 31 and strlen($UserPMess) < 42 and strlen($UserTMess) > 31 and strlen($UserTMess) < 42){
									SResponse("NoUsers");
								}else{
									BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
								}
							}
							$tmp_CheckChess = False;
							$tmp_CheckChess = UnlinkProperties($tmp_checkaccess) ?: False;
							if($tmp_CheckChess === True){
								if($IsFunctionInUse === False){
									BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
								}else{
									BPacket("Error!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
								}
							}
						}else{
							$tmp_CheckChess = False;
							$tmp_CheckChess = UnlinkProperties($tmp_checkaccess) ?: False;
							if($tmp_CheckChess === True){
								if($IsFunctionInUse === False){
									BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
								}else{
									BPacket("Error!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
								}
							}
						}
					}else{
						clearstatcache();
						if(strlen($tmp_AccessToken_AC) === 1 and (int)$tmp_AccessToken_AC === 3 and is_string($tmp_one_nc) === true and is_string($tmp_two_nc) === true and strlen($tmp_one_nc) > 4 and strlen($tmp_two_nc) > 4 and strlen($tmp_one_nc) < 15 and strlen($tmp_two_nc) < 15 and strlen($tmp_one_nc) === strlen($UserName) and $tmp_one_nc === $UserName and strlen($tmp_two_nc) === strlen($UserName) and $tmp_two_nc === $UserName and $tmp_one_nc === $tmp_two_nc and is_string($tmp_one_ip) === true and is_string($tmp_two_ip) === true and is_string($tmp_ipaddress) === true and strlen($tmp_one_ip) === 64 and strlen($tmp_two_ip) === 64 and $tmp_one_ip != $tmp_ipaddress and $tmp_one_ip != $tmp_two_ip and $tmp_two_ip === $tmp_ipaddress and is_string($UserData) === true and is_string($tmp_ipfile) === true and strlen($tmp_UDone) === 7 and $tmp_UDone == "../Dbu/" and is_string($tmp_UDtwo) === true and strlen($tmp_UDtwo) === 1 and $tmp_UDtwo == "/" and strlen($tmp_UDthree) === 5 and $tmp_UDthree == ".mdma" and strlen($tmp_IPone) === 7 and $tmp_IPone == "../Dbu/" and is_string($tmp_IPtwo) === true and strlen($tmp_IPtwo) === 1 and $tmp_IPtwo == "/" and strlen($tmp_IPthree) === 5 and $tmp_IPthree == ".mdma" and $tmp_UDthree === $tmp_IPthree and strlen($tmp_UD_atlengthone) === 69 and strlen($tmp_IP_iplengthone) === 69 and substr_count($UserData, ".") === 3 and substr_count($tmp_ipfile, ".") === 3 and substr_count($UserData, "/") === 3 and substr_count($tmp_ipfile, "/") === 3 and $tmp_UD_atlengthtwo === strlen($UserData) and $tmp_IP_iplengthtwo === strlen($tmp_ipfile) and $tmp_UD_atlengthtwo === $tmp_IP_iplengthtwo and strlen($UserData) > 81 and strlen($UserData) < 92 and strlen($tmp_ipfile) > 81 and strlen($tmp_ipfile) < 92 and $tmp_AccessToken_GT != $DoubleHash and hash_equals($tmp_AccessToken_GT, $DoubleHash) === false and strlen($UserData) === strlen($tmp_ipfile) and $UserData != $tmp_ipfile and hash_equals($DoubleHash, $tmp_ipaddress) === false and file_exists($UserData) === true and file_exists($tmp_ipfile) === true and file_exists($tmp_checkaccess) === true){
							SResponse("Packet!");
						}else{
							BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
						}
					}
				}else{
					BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
				}
			}elseif($tmp_ip_blockedrequests === 5){
				SResponse("Banned!");
			}else{
				BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
			}
		}else{
			echo "Packet!"; // There is no ip so we can't count. Just output Packet for this.
		}
	}
}
?>
