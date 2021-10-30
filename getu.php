<?php
// * /
// PHPStar-Pub
// This script sends the UserNames of the Users that contacted the client.
// This script also has the ability to delete all info on read (UserNames that contacted the client).
// File indicator is 3 and should be placed inside AccessToken like this AccessToken:3
// Sends *1.) n=Username *2. p=AccessToken:number *3.) i=number
// / *
date_default_timezone_set('UTC');
$Get_UserName = (isset($_GET['n']) ? $_GET['n'] : null); // Username
$Get_AccessToken = (isset($_GET['p']) ? $_GET['p'] : null); // AccessToken
$Get_SACData = (isset($_GET['i']) ? $_GET['i'] : 2); // Data Actions. If set to 1 deletes user data after read. If set to 3 re-sends all data to client.
unset($_GET);
$tmp_ipaddress = $_SERVER["REMOTE_ADDR"];
if(strlen($tmp_ipaddress) > 6 and (strlen($tmp_ipaddress) < 16 and substr_count($tmp_ipaddress, ".") === 3) or (strlen($tmp_ipaddress) < 40 and substr_count($tmp_ipaddress, ":") > 2 and substr_count($tmp_ipaddress, ":") < 8)){
	$tmp_ipaddress = hash('sha256', $tmp_ipaddress); // X2
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
	if(file_exists($bannedforhourfolder) == false){
		$bansfolder = "../Dba/iprb/";
		clearstatcache($bansfolder);
		if(file_exists($bansfolder) == false){
			$rootfolder = "../Dba";
			clearstatcache($rootfolder);
			if(file_exists($rootfolder) == false){
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
	}else{
		$tmp_ip_blockedrequests = 0;
	}
	if($tmp_ip_blockedrequests >= 0 and $tmp_ip_blockedrequests < 5){
		$UserName = urlencode($Get_UserName);
		$AccessToken = $Get_AccessToken;
		$SACData = urlencode($Get_SACData);
		// Set allowed chars
		$tmp_allowedchars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$tmp_allowednums = "0123456789";
		// For UserName
		for($n = 0; $n < strlen($UserName); $n++){
			if($n > 13){
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
			if($n === 64){
				$tmp_AccessToken_Chars = $AccessToken[$n];
				$tmp_Token_allowedchars = ":";
				if(strpos($tmp_Token_allowedchars, $tmp_AccessToken_Chars) === false){
					$AccessToken = " ";
					break;
				}
			}elseif($n > 64){
				$tmp_AccessToken_Chars = $AccessToken[$n];
				$tmp_Token_allowedchars = $tmp_allowednums;
				if(strpos($tmp_Token_allowedchars, $tmp_AccessToken_Chars) === false){
					$AccessToken = " ";
					break;
				}
			}else{
				$tmp_AccessToken_Chars = $AccessToken[$n];
				$tmp_Token_allowedchars = $tmp_allowedchars.$tmp_allowednums;
				if(strpos($tmp_Token_allowedchars, $tmp_AccessToken_Chars) === false){
					$AccessToken = " ";
					break;
				}
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
		if(is_string($UserName) === true and strpos($UserName, '.') === false and strpos($UserName, '%') === false and strpos($UserName, '/') === false and strpos($UserName, '<') === false and strpos($UserName, '>') === false and strpos($UserName, '$') === false and is_string($AccessToken) === true and strpos($AccessToken, '.') === false and strpos($AccessToken, '%') === false and strpos($AccessToken, '/') === false and strpos($AccessToken, '<') === false and strpos($AccessToken, '>') === false and strpos($AccessToken, '$') === false and substr_count($AccessToken, ':') === 1 and strlen($UserName) > 4 and strlen($UserName) < 15 and strlen($AccessToken) > 64 and strlen($AccessToken) <= 67 and $SACData > 0){
			$UserName = strtolower($UserName) ? strtolower($UserName) : " ";
			$tmp_AccessToken_GT = explode(':', $AccessToken)[0] ? explode(':', $AccessToken)[0] : 0; // Access Token
			$tmp_AccessToken_AC = explode(':', $AccessToken)[1] ? explode(':', $AccessToken)[1] : 0; // File Indicator
			$DoubleHash = hash('sha256', $tmp_AccessToken_GT) ? hash('sha256', $tmp_AccessToken_GT) : "A"; // Double X2
			// 441
			$UserData = "../Dbu/".$UserName."/".$DoubleHash.".mdma"; // Set the Access Token path
			$tmp_ipfile = "../Dbu/".$UserName."/".$tmp_ipaddress.".mdma"; // Set Logged User Ip file
			// ChessCER 1 Check for the Name in Both Files. #Block access to diffrent User if malformed
			$tmp_one_nc = "AAA";
			$tmp_one_nc = explode("/", $UserData)[2] ? explode("/", $UserData)[2] : "AAA"; // Check for name
			$tmp_two_nc = "BBB";
			$tmp_two_nc = explode("/", $tmp_ipfile)[2] ? explode("/", $tmp_ipfile)[2] : "BBB"; // Check for name
			// ChessCER 2 Check for IP in Access Token. #Block access from within the same ip by sending the ip as token
			$tmp_one_ip = "AAA";
			$tmp_two_ip = "AAA";
			$tmp_forip = explode("/", $UserData)[3] ? explode("/", $UserData)[3] : $tmp_one_ip = ($tmp_two_ip = "AAA");
			if(substr_count($tmp_forip, ".") === 1){
				$tmp_one_ip = explode(".", $tmp_forip)[0] ? explode(".", $tmp_forip)[0] : $tmp_one_ip = ($tmp_two_ip = "AAA");
			}else{
				$tmp_one_ip = "AAA";
				$tmp_two_ip = "AAA";
			}
			$tmp_forip = explode("/", $tmp_ipfile)[3] ? explode("/", $tmp_ipfile)[3] : $tmp_two_ip = ($tmp_one_ip = "AAA");
			if(substr_count($tmp_forip, ".") === 1){
				$tmp_two_ip = explode(".", $tmp_forip)[0] ? explode(".", $tmp_forip)[0] : $tmp_two_ip = ($tmp_one_ip = "AAA");
			}else{
				$tmp_two_ip = "AAA";
				$tmp_one_ip = "AAA";
			}
			// ChessCER 3 Make sure that the file's specified is not malformed. #Block malformed by overall length and possision
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
			// ChessCER 4 Make sure that Token and IP are in the correct possision.
			$tmp_UD_atlengthone = "A";
			$tmp_UD_atlengthone = explode("/", $UserData)[3] ? explode("/", $UserData)[3] : "A";
			$tmp_UD_atlengthtwo = 1;
			$tmp_UD_atlengthtwo = strlen($tmp_UD_atlengthone) + strlen($UserName) + 8 ? strlen($tmp_UD_atlengthone) + strlen($UserName) + 8 : 3;
			$tmp_IP_iplengthone = "A";
			$tmp_IP_iplengthone = explode("/", $tmp_ipfile)[3] ? explode("/", $tmp_ipfile)[3] : "A";
			$tmp_IP_iplengthtwo = 2;
			$tmp_IP_iplengthtwo = strlen($tmp_IP_iplengthone) + strlen($UserName) + 8 ? strlen($tmp_IP_iplengthone) + strlen($UserName) + 8 : 4;
			// End Game
			$ExtraData = "../Dbu/".$UserName."/".$tmp_ipaddress.".getu.omd";
			$tmp_checkaccess = "../Dbu/".$UserName."/Messages/getu.inUse"; // Set inUse <- Manipulate usage
			clearstatcache();
			if((int)$tmp_AccessToken_AC === 3 and is_string($tmp_one_nc) === true and is_string($tmp_two_nc) === true and strlen($tmp_one_nc) > 4 and strlen($tmp_two_nc) > 4 and strlen($tmp_one_nc) < 15 and strlen($tmp_two_nc) < 15 and strlen($tmp_one_nc) === strlen($UserName) and $tmp_one_nc === $UserName and strlen($tmp_two_nc) === strlen($UserName) and $tmp_two_nc === $UserName and $tmp_one_nc === $tmp_two_nc and is_string($tmp_one_ip) === true and is_string($tmp_two_ip) === true and is_string($tmp_ipaddress) === true and strlen($tmp_one_ip) === 64 and strlen($tmp_two_ip) === 64 and $tmp_one_ip != $tmp_ipaddress and $tmp_one_ip != $tmp_two_ip and $tmp_two_ip === $tmp_ipaddress and is_string($UserData) === true and is_string($tmp_ipfile) === true and strlen($tmp_UDone) === 7 and $tmp_UDone == "../Dbu/" and strlen($tmp_UDtwo) === 1 and $tmp_UDtwo == "/" and strlen($tmp_UDthree) === 5 and $tmp_UDthree == ".mdma" and strlen($tmp_IPone) === 7 and $tmp_IPone == "../Dbu/" and strlen($tmp_IPtwo) === 1 and $tmp_IPtwo == "/" and strlen($tmp_IPthree) === 5 and $tmp_IPthree == ".mdma" and strlen($tmp_UD_atlengthone) === 69 and strlen($tmp_IP_iplengthone) === 69 and substr_count($UserData, ".") === 3 and substr_count($tmp_ipfile, ".") === 3 and substr_count($UserData, "/") === 3 and substr_count($tmp_ipfile, "/") === 3 and $tmp_UD_atlengthtwo === strlen($UserData) and $tmp_IP_iplengthtwo === strlen($tmp_ipfile) and $tmp_UD_atlengthtwo === $tmp_IP_iplengthtwo and strlen($UserData) > 81 and strlen($UserData) < 92 and strlen($tmp_ipfile) > 81 and strlen($tmp_ipfile) < 92 and $tmp_AccessToken_GT != $DoubleHash and strlen($UserData) === strlen($tmp_ipfile) and $UserData != $tmp_ipfile and file_exists($UserData) === true and file_exists($tmp_ipfile) === true and file_exists($tmp_checkaccess) === false){
				file_put_contents($tmp_checkaccess, " ");
				$UserPMess = "../Dbu/".$UserName."/Messages/Users.data";
				$UserTMess = "../Dbu/".$UserName."/Messages/Users.n.data";
				if((file_exists($UserPMess) or file_exists($UserTMess)) and strlen($UserPMess) > 24 and strlen($UserPMess) < 35 and strlen($UserTMess) > 24 and strlen($UserTMess) < 35){
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
					echo("<br><br>1. MESS (!) <br>");
					// *** // ** ! * ! ** // *** // Data
					if(strlen($GUserPData) > 5 and strlen($GUserTData) > 5){
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
						clearstatcache($ExtraData);
						if(!file_exists($ExtraData) and (int)$SACData !== 3){
							$GUserCData = CFMess($GUserTData, "");
						}
					}elseif(strlen($GUserPData) > 5){
						$GUserCData = CFMess($GUserPData, "");
						if($SACData === 1){
							$tmp_put = PutConO($UserPMess, "", "");
							while($tmp_put != 0 or $tmp_put != 4){
								$tmp_put = PutConO($UserPMess, "", "");
							}
						}else{
							if($GUserCData !== $GUserPData){
								$tmp_put = PutConO($UserPMess, $GUserCData, "");
								while($tmp_put != 0 or $tmp_put != 4){
									$tmp_put = PutConO($UserPMess, $GUserCData, "");
								}
							}
						}
						clearstatcache($ExtraData);
						if(!file_exists($ExtraData) and (int)$SACData !== 3){
							$GUserCData = "";
						}
					}elseif(strlen($GUserTData) > 5){
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
					}else{
						//echo " to 0 logs";
						$GUserCData = "";
					}
					// 444 V
					clearstatcache($ExtraData);
					if(file_exists($ExtraData)){
						unlink($ExtraData);
					}
					//echo "<br><br>2. DATA (!)";
					// *** // ** ! * ! ** // *** // Send
					if(strlen($GUserCData) > 5){
						$tmpmsgcount = substr_count($GUserCData, ";") ? substr_count($GUserCData, ";") : 0;
						if($tmpmsgcount > 0){
							//echo "<br><br>Correct:<br>"
							echo $GUserCData;
						}else{
							echo "Error";
						}
					}else{
						if(strlen($GUserCData) > 0){
							echo "Error";
						}else{
							echo "NoUsers";
						}
					}
				}else{
					// Check this
					if(strlen($UserPMess) > 24 and strlen($UserPMess) < 35 and strlen($UserTMess) > 24 and strlen($UserTMess) < 35){
						echo "NoUsers";
					}elseif((strlen($UserPMess) > 24 and strlen($UserPMess) < 35) or (strlen($UserTMess) > 24 and strlen($UserTMess) < 35)){
						echo "NoUsers";
					}else{
						BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
					}
				}
				clearstatcache($tmp_checkaccess);
				if(file_exists($tmp_checkaccess) === true){
					unlink($tmp_checkaccess);
				}
			}else{
				clearstatcache();
				if($tmp_AccessToken_AC === 3 and file_exists($UserData) == true and file_exists($tmp_ipfile) == true and file_exists($tmp_checkaccess) == true and is_string($tmp_one_nc) == true and is_string($tmp_two_nc) == true and strlen($tmp_one_nc) > 4 and strlen($tmp_two_nc) > 4 and strlen($tmp_one_nc) < 15 and strlen($tmp_two_nc) < 15 and $tmp_one_nc === $UserName and $tmp_two_nc === $UserName and $tmp_one_nc === $tmp_two_nc and is_string($tmp_one_ip) == true and is_string($tmp_two_ip) == true and strlen($tmp_one_ip) == 69 and strlen($tmp_two_ip) == 69 and $tmp_one_ip != $tmp_ipaddress and $tmp_one_ip != $tmp_two_ip and substr_count($UserData, ".") === 3 and substr_count($tmp_ipfile, ".") === 3 and strlen($UserData) > 81 and strlen($UserData) < 92 and strlen($tmp_ipfile) > 81 and strlen($tmp_ipfile) < 92 and $UserData != $tmp_ipfile){
					echo "Packet!";
				}else{
					BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
				}
			}
		}else{
			BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
		}
	}elseif($tmp_ip_blockedrequests === 5){
		echo "Banned!";
	}else{
		BPacket("Packet!", $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
	}
}else{
	echo "Packet!"; // There is no ip so we can't count from this file. Just output Packet for this.
}
// * /
// Functions
// / *
// * * *
// Writes to files
// 0 = Success *|* 1 = Fail at the first one *|* 2 = Success at first failed at second *|* 3 = All failled *|* 4 = Nothing Happened
function PutConO($aa, $ab, $ag){
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
// Sort, Remove Duplicate
function CFMess($aa, $bb){
	$gg = "";
	$dd = "";
	$ee = "";
	// Remove duplicate and some invalid
	if(!empty($aa)){
		$tmp_cf_count = substr_count($aa, ";") ? substr_count($aa, ";") : 0;
		if($tmp_cf_count > 0){
			for($x = 0; $x < $tmp_cf_count; $x++) {
				if(str_contains($gg, explode(";", $aa)[$x]) == false and ((strlen(explode(";", $aa)[$x]) > 4 and strlen(explode(";", $aa)[$x]) < 15) or (strlen(explode(";", $aa)[$x]) == 64))){
					$gg = $gg . explode(";", $aa)[$x] . ";";
				}
			}
		}
	}
	// Remove duplicate and some invalid
	if(!empty($bb)){
		$tmp_cf_count = substr_count($bb, ";") ? substr_count($bb, ";") : 0;
		if($tmp_cf_count > 0){
			for($x = 0; $x < $tmp_cf_count; $x++) {
				if(str_contains($dd, explode(";", $bb)[$x]) == false and ((strlen(explode(";", $bb)[$x]) > 4 and strlen(explode(";", $bb)[$x]) < 15) or (strlen(explode(";", $bb)[$x]) == 64))){
					$dd = $dd . explode(";", $bb)[$x] . ";";
				}
			}
		}
	}
	// Sort and remove duplicate
	if(!empty($gg) and !empty($dd)){
		$tmp_tcf_count = substr_count($dd, ";") ? substr_count($dd, ";") : 0;
		$tmp_pcf_count = substr_count($gg, ";") ? substr_count($gg, ";") : 0;
		if($tmp_tcf_count > 0 and $tmp_pcf_count > 0){
			for($x = 0; $x < $tmp_tcf_count; $x++){
				if(strlen($gg) <= 0){
					break;
				}
				if(str_contains($gg, explode(";", $dd)[$x])){
					$gg = str_replace(explode(";", $dd)[$x].";", "", $gg);
				}
			}
			// Add the newer sender first. This should checked by client too.
			$ee = $dd . $gg;
		}
	}
	if(empty($ee) and !empty($gg)){
		$ee = $gg;
	}
	echo "<br><br>Function say e: ".$ee;
	return $ee;
}
// * * *
// Ephemeral Access Tokens Manager
function ChangeToken(){
	$OneTimeKey = hash('sha256', rand(101, 999).rand(1001,9999).rand(1001,9999).rand(10001,99999).rand(10001,99999).rand(1001,9999).rand(1001,9999).rand(101, 999));
	$TmpSaveKey = hash('sha256', $OneTimeKey);
	$nfileu = $UserPath."/".$TmpSaveKey.".mdma";
	file_put_contents($nfileu, " ");
	$TmpSaveKey = "0000";
}
// * * *
// BFP Manager
function BPacket($exponent, $ptmp_daymonthyearhour, $ptmptime, $ptmp_ipaddress){
	$ptmptime += 1;
	$tmpfileip = "../Dba/iprb/".$ptmp_daymonthyearhour."/".$ptmptime.".".$ptmp_ipaddress.".bip";
	clearstatcache($tmpfileip);
	if(file_exists($tmpfileip) and $ptmptime > 0 and $ptmptime < 5){ // Check if next log exist.
		for($h = $ptmptime; $h <= 5; $h++){
			$ptmptime = $h;
			$tmpfileip = "../Dba/iprb/".$ptmp_daymonthyearhour."/".$h.".".$ptmp_ipaddress.".bip";
			clearstatcache($tmpfileip);
			if(file_exists($tmpfileip) == false){
				file_put_contents($tmpfileip, " ");
				break;
			}
		}
	}elseif(file_exists($tmpfileip) == false and $ptmptime > 0 and $ptmptime <= 5){
		file_put_contents($tmpfileip, " ");
	}else{
		if((int)$ptmptime === 0){
			$ptmptime = 5;
			$tmpfileip = "../Dba/iprb/".$ptmp_daymonthyearhour."/".$ptmptime.".".$ptmp_ipaddress.".bip";
			file_put_contents($tmpfileip, " ");
		}
	}
	if($ptmptime >= 5){
		echo "Banned!";
	}else{
		echo "$exponent";
	}
}
// * * *
//
?>
