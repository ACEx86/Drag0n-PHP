<?php
// * /
// Drag0n-PHP ~ Public
// This script sends the User Names of the Users that contacted a User Account for faster mapping.
// This script also has the ability to delete all info of User Names that contacted the client on read and can be set by client (Logged User).
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
$Get_UserName = null; // Username
$Get_AccessToken = null; // AccessToken
$Get_SACData = '2'; // Data Actions
if(!empty($_POST) and is_array($_POST) === True){
	if(!empty($_POST['n']) and is_string($_POST['n']) === True){
		$Get_UserName = $_POST['n'] ?: $Get_UserName = null;
	}
	if(!empty($_POST['p']) and is_string($_POST['p']) === True){
		$Get_AccessToken = $_POST['p'] ?: $Get_AccessToken = null;
	}
	if(!empty($_POST['i']) and is_string($_POST['i']) === True){
		$Get_SACData = $_POST['i'] ?: $Get_SACData = '2';
	}
}
unset($_POST);
$GetU = new GetU_Class($Get_UserName, $Get_AccessToken, $Get_SACData);
//
Class GetU_Class{
	// - Set Variables
	Private Bool $IsFunctionInUse = False;
	Private Bool $IsBPacketUsed = False;
	Private Bool $ExtendedLogging_E = True;
	Private Bool $ProfileLogging_E = True;
	Private String $AllowedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	Private String $AllowedNums = '0123456789';
	Private String $IpAddress = '';
	// - Start Functions
	// * * *
	// CheckAndFix : Remove duplicate and some invalid.
	//
	Private Function CheckAndFix($tmp_CAF_Data){
		$tmp_CFM_Return = ' ';
		if(isset($tmp_CAF_Data) === True and is_string($tmp_CAF_Data) === True){
			$tmp_CFM_Count = 0;
			$tmp_CFM_DataLength = 0;
			$tmp_CFM_Count = substr_count($tmp_CAF_Data, ';') ?: $tmp_CFM_Count = 0;
			$tmp_CFM_DataLength = strlen($tmp_CAF_Data) ?: $tmp_CFM_DataLength = 0;
			if(isset($tmp_CFM_Count) === True and isset($tmp_CFM_DataLength) === True and is_integer($tmp_CFM_DataLength) === True and is_integer($tmp_CFM_Count) === True and $tmp_CFM_Count > 0 and $tmp_CFM_DataLength > 5){
				for($x = 0; $x < $tmp_CFM_Count; $x++){
					$tmp_CFM_tmp = ' ';
					$tmp_CFM_tmpLength = 0;
					$tmp_CFM_tmpExact = ' ';
					$tmp_CFM_tmp = explode(';', $tmp_CAF_Data)[$x].';' ?: $tmp_CFM_tmp = ' ';
					$tmp_CFM_tmpLength = strlen($tmp_CFM_tmp) ?: $tmp_CFM_tmpLength = 'a';
					$tmp_CFM_tmpExact = ';'.$tmp_CFM_tmp ?: $tmp_CFM_tmpExact = ' ';
					if(isset($tmp_CFM_tmp) === True and is_string($tmp_CFM_tmp) === True and isset($tmp_CFM_tmpExact) === True and is_string($tmp_CFM_tmpExact) === True and isset($tmp_CFM_tmpLength) === True and is_integer($tmp_CFM_tmpLength) === True and $tmp_CFM_tmpLength > 5 and $tmp_CFM_tmpLength <= 15 and isset($tmp_CFM_Return) === True and is_string($tmp_CFM_Return) === True and substr_count($tmp_CFM_tmp, ';') === 1 and substr_count($tmp_CFM_tmpExact, ';') === 2 and (substr_count($tmp_CFM_Return, ';') < 1 or str_contains($tmp_CFM_Return, $tmp_CFM_tmpExact) === False)){
						$tmp_CFM_AllowedChars = $this->AllowedChars.$this->AllowedNums.';' ?: $tmp_CFM_AllowedChars = '';
						for($n = 0; $n < $tmp_CFM_tmpLength; $n++){
							$tmp_CFM_tmpName = ' ';
							if($n >= 0 and $n <= 15 and isset($tmp_CFM_tmp) === True and is_string($tmp_CFM_tmp) === True and strlen($tmp_CFM_tmp) > 5 and strlen($tmp_CFM_tmp) <= 15 and $n <= strlen($tmp_CFM_tmp) - 1){
								$tmp_CFM_tmpName = $tmp_CFM_tmp[$n];
							}else{
								$tmp_CFM_tmpName = '';
								$tmp_CFM_tmp = ' ';
								break;
							}
							if(isset($tmp_CFM_tmp) === True and is_string($tmp_CFM_tmp) === True and isset($tmp_CFM_tmpLength) === True and is_integer($tmp_CFM_tmpLength) === True and strlen($tmp_CFM_tmp) == $tmp_CFM_tmpLength and isset($tmp_CFM_tmpName) === True and is_string($tmp_CFM_tmpName) === True and strlen($tmp_CFM_tmpName) === 1 and isset($tmp_CFM_AllowedChars) === True and is_string($tmp_CFM_AllowedChars) === True and strlen($tmp_CFM_AllowedChars) === 63 and str_contains($tmp_CFM_AllowedChars, $tmp_CFM_tmpName) === True){
								if((str_contains(';' , $tmp_CFM_tmpName) === True and $n != strlen($tmp_CFM_tmp) - 1) or (str_contains(';' , $tmp_CFM_tmpName) === False and $n == strlen($tmp_CFM_tmp) - 1)){
									$tmp_CFM_tmpName = '';
									$tmp_CFM_tmp = ' ';
									break;
								}
							}else{
								$tmp_CFM_tmpName = '';
								$tmp_CFM_tmp = ' ';
								break;
							}
							$tmp_CFM_tmpName = '';
						}
						if(isset($tmp_CFM_Return) === True and is_string($tmp_CFM_Return) === True and isset($tmp_CFM_tmp) === True and is_string($tmp_CFM_tmp) === True and isset($tmp_CFM_tmpExact) === True and is_string($tmp_CFM_tmp_Exact) === True and strlen($tmp_CFM_tmp) > 5 and strlen($tmp_CFM_tmp) <= 15 and strlen($tmp_CFM_tmpExact) === strlen($tmp_CFM_tmp) + 1 and str_contains($tmp_CFM_tmpExact, $tmp_CFM_tmp) === True and (substr_count($tmp_CFM_Return, ';') < 1 or str_contains($tmp_CFM_Return, $tmp_CFM_tmpExact) === False)){
							$tmp_add_name = '';
							$tmp_add_name = explode(';', $tmp_CAF_Data)[$x] . ';' ?: $tmp_add_name = '';
							if(empty($tmp_add_name)){
								$tmp_add_name = '';
								$For_Extended_Ip = $this->tmp_ipaddress ?: $For_Extended_Ip = 'Unkown';
								$this->ExtendedLogging_E === False ?: $this->Extended_Logging('CheckAndFix: Failled to validate on insert data. With IP Address: '.$For_Extended_Ip);
							}
							if(is_string($tmp_CFM_tmp) === True and $tmp_add_name === $tmp_CFM_tmp and strlen($tmp_add_name) === strlen($tmp_CFM_tmp)){
								strlen($tmp_CFM_Return) <= 5 ? $tmp_CFM_Return = $tmp_CFM_tmp : $tmp_CFM_Return = $tmp_CFM_Return . $tmp_CFM_tmp;
							}
							$tmp_add_name = '';
						}
					}
					$tmp_CFM_tmp = '';
					$tmp_CFM_tmpLength = 0;
					$tmp_CFM_tmpExact = '';
				}
			}
			$tmp_CFM_Count = 0;
			$tmp_CFM_DataLength = 0;
			$tmp_CAF_Data = '';
		}
		return $tmp_CFM_Return;
	}
	// * * *
	// PutConO : Writes to files
	//
	Private Function PutConO($aa, $ab, $ag){
		if(strlen($aa) > 28){
			if(strlen($ab) === 0){
				// DELETE DATA
				file_put_contents($aa, '', LOCK_EX);
			}else{
				//APPEND DATA
				file_put_contents($aa, $ab, FILE_APPEND);
			}
			$los_co = 0;
			clearstatcache($aa);
		}
		unset($aa, $ag);
		return($los_co);
	}
	// * * *
	// ChangeToken : Ephemeral Access Tokens Manager
	//
	Private Function ChangeToken(){
		$OneTimeKey = hash('sha256', rand(101, 999).rand(1001,9999).rand(1001,9999).rand(10001,99999).rand(10001,99999).rand(1001,9999).rand(1001,9999).rand(101, 999));
		$TmpSaveKey = hash('sha256', $OneTimeKey);
		$nfileu = $UserPath.'/'.$TmpSaveKey.'.mdma';
		$TmpSaveKey = '0000';
		clearstatcache($nfileu);
		if(file_exists($nfileu) === true){
			return False;
		}else{
			file_put_contents($nfileu, ' ');
		}
		return True;
	}
	// * * *
	// GetDate
	//
	Private Function Get_Date(){
		$tmp_daymonthyearhour = '';
		$tmp_daymonthyearhour = hash('sha256', date("d:m:Y:G")) ?: $tmp_daymonthyearhour = ''; // X2
		return $tmp_daymonthyearhour;
	}
	// * * *
	Private Function Get_Time($ptmptime){
		is_string($ptmptime) === false ?: 1;
		$ptmptime >= 0 ? $ptmptime += 1 : $ptmptime = 1;
		return $ptmptime;
	}
	// * * *
	// Get Ip Address
	//
	Private Function Get_Ip(){
		$tmp_ipaddress = '';
		if(!empty($_SERVER) and is_array($_SERVER) === True){
			if(!empty($_SERVER['REMOTE_ADDR'])){
				$tmp_ipaddress_check = $_SERVER['REMOTE_ADDR'] ?: $tmp_ipaddress_check = '';
				if(!empty($tmp_ipaddress_check) and is_string($tmp_ipaddress_check) === True and !empty($tmp_ipaddress) and is_string($tmp_ipaddress) === True and strlen($tmp_ipaddress_check) === strlen($tmp_ipaddress)){
					$tmp_ipaddress = $tmp_ipaddress_check ?: $tmp_ipaddress = '';
				}
			}
			if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$tmp_ipaddress_check = $_SERVER['HTTP_X_FORWARDED_FOR'] ?: $tmp_ipaddress_check = '';
				if(!empty($tmp_ipaddress_check) and is_string($tmp_ipaddress_check) === True and !empty($tmp_ipaddress) and is_string($tmp_ipaddress) === True and strlen($tmp_ipaddress_check) === strlen($tmp_ipaddress)){
					$tmp_ipaddress = $tmp_ipaddress_check ?: $tmp_ipaddress = '';
				}
			}
			if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$tmp_ipaddress_check = $_SERVER['HTTP_CLIENT_IP'] ?: $tmp_ipaddress_check = '';
				if(!empty($tmp_ipaddress_check) and is_string($tmp_ipaddress_check) === True and !empty($tmp_ipaddress) and is_string($tmp_ipaddress) === True and strlen($tmp_ipaddress_check) === strlen($tmp_ipaddress)){
					$tmp_ipaddress = $tmp_ipaddress_check ?: $tmp_ipaddress = '';
				}
			}
		}
		return $tmp_ipaddress;
	}
	// * * *
	Private Function Get_IpAddress(){
		$tmp_ipaddress = $this->Get_Ip() ?: $tmp_ipaddress = '';
		if(is_string($tmp_ipaddress) === True and strlen($tmp_ipaddress) > 6 and ((strlen($tmp_ipaddress) < 16 and substr_count($tmp_ipaddress, '.') === 3) or (strlen($tmp_ipaddress) < 40 and substr_count($tmp_ipaddress, ':') > 2 and substr_count($tmp_ipaddress, ':') < 8))){
			$tmp_usedchar = '';
			for($i = 0; $i < strlen($tmp_ipaddress); $i++){
				$tmp_allowedchars = $this->AllowedNums.'.:' ?: $tmp_allowedchars = '';
				$tmp_IP = '';
				if(!empty($tmp_ipaddress) and is_string($tmp_ipaddress) === True and $i <= strlen($tmp_ipaddress) - 1){
					$tmp_IP = $tmp_ipaddress[$i] ?: $tmp_IP = ' ';
					if($tmp_IP = '.'){
						if(!empty($tmp_usedchar) and is_string($tmp_usedchar) === True and $tmp_usedchar === ':'){
							$tmp_ipaddress = '';
							break;
						}else{
							$tmp_usedchar = '.';
						}
					}elseif($tmp_IP = ':'){
						if(!empty($tmp_usedchar) and is_string($tmp_usedchar) === True and $tmp_usedchar === '.'){
							$tmp_ipaddress = '';
							break;
						}else{
							$tmp_usedchar = ':';
						}
					}
				}else{
					$tmp_ipaddress = '';
					break;
				}
				if(!empty($tmp_allowedchars) and !empty($tmp_IP) and is_string($tmp_allowedchars) === True and is_string($tmp_IP) === True and strlen($tmp_allowedchars) <= 1 and strlen($tmp_IP) < 1 and strlen($tmp_IP) > 1 and str_contains($tmp_allowedchars, $tmp_IP) === False){
					$tmp_ipaddress = '';
					break;
				}
			}
			if(!empty($tmp_ipaddress) and is_string($tmp_ipaddress) === True and strlen($tmp_ipaddress) > 1){
				if(!empty($this->IpAddress) and is_string($this->IpAddress) and strlen($this->IpAddress) > 1){
					if($this->IpAddress === $tmp_ipaddress){
						$tmp_ipaddress = hash('sha256', $tmp_ipaddress) ?: $tmp_ipaddress = ''; // X2
					}else{
						if(!empty($this->Extended_Logging) and $this->Extended_Logging === True){
							//profile_loggin
							$this->Extended_Logging('Get_IpAddress: Ip is not identical deauth.');
						}	
					}
				}else{
					if(empty($this->IpAddress) and strlen($this->IpAddress) < 1){
						$this-> IpAddress = $tmp_ipaddress;
						$tmp_ipaddress = hash('sha256', $tmp_ipaddress) ?: $tmp_ipaddress = ''; // X2
					}else{
						if(!empty($this->Extended_Logging) and $this->Extended_Logging === True){
							$this->Extended_Logging('Get_IpAddress: Failled at validating old ip.');
						}
					}
				}
			}
		}else{
			$tmp_ipaddress = '';
		}
		return $tmp_ipaddress;
	}
	// * * *
	// Blocked Packets
	//
	Private Function BPacket($exponent, $ptmp_daymonthyearhour, $ptmptime, $ptmp_ipaddress){
		if($this->IsBPacketUsed === False){
			$this->IsBPacketUsed = True;
			$tmp_BP_daymonthyear = '';
			$tmp_BP_daymonthyear = $this->Get_Date ?: $tmp_BP_daymonthyear = '';
			$tmp_BP_GetIp = '';
			$tmp_BP_GetIp = $this->Get_IpAddress ?: $tmp_BP_GetIp = '';
			$ptmptime = $this->FixDate($ptmptime) ?: $ptmptime = 0;
			$ptmp_ipaddress = $this->FixDate($ptmp_ipaddress) ?: $ptmp_ipaddress = ' ';
			$tmpfileip = '../Dba/iprb/'.$ptmp_daymonthyearhour.'/'.$ptmptime.'.'.$ptmp_ipaddress.'.bip';
			if(is_string($tmpfileip) === true and strlen($tmpfileip) === 147 and substr_count($tmpfileip, '.') === 4 and substr_count($tmpfileip, '/') === 3 and str_contains($tmpfileip, '../Dba/iprb/') === True and str_contains($tmpfileip, '.bip') === True){
				clearstatcache($tmpfileip);
				if(file_exists($tmpfileip) === false and $ptmptime <= 5){
					file_put_contents($tmpfileip, ' ');
				}elseif(file_exists($tmpfileip) === true and $ptmptime < 5){ // Check if next log exist.
					for($h = $ptmptime; $h <= 5; $h++){
						$ptmptime = $h ?: $ptmptime = ' ';
						$ptmp_daymonthyearhour = FixDate($ptmp_daymonthyearhour) ?: $ptmp_daymonthyearhour = ' ';
						$ptmptime = FixDate($ptmptime) ?: $ptmptime = 0;
						$ptmp_ipaddress = FixDate($ptmp_ipaddress) ?: $ptmp_ipaddress = ' ';
						$tmpfileip = '../Dba/iprb/'.$ptmp_daymonthyearhour.'/'.$h.'.'.$ptmp_ipaddress.'.bip';
						clearstatcache($tmpfileip);
						if(file_exists($tmpfileip) === false and $ptmptime === $h){
							file_put_contents($tmpfileip, ' ');
							break;
						}elseif($ptmptime !== $h){
							$ptmptime = 5;
							$ptmp_daymonthyearhour = FixDate($ptmp_daymonthyearhour) ?: ' ';
							$ptmptime = FixDate($ptmptime) ?: 0;
							$ptmp_ipaddress = FixDate($ptmp_ipaddress) ?: ' ';
							$tmpfileip = '../Dba/iprb/'.$ptmp_daymonthyearhour.'/'.$h.'.'.$ptmp_ipaddress.'.bip';
							file_put_contents($tmpfileip, ' ');
							break;
						}
					}
				}else{
					if((int)$ptmptime === 0){
						$ptmptime = 5;
						$tmpfileip = '../Dba/iprb/'.$ptmp_daymonthyearhour.'/'.$ptmptime.'.'.$ptmp_ipaddress.'.bip';
						file_put_contents($tmpfileip, ' ');
					}
				}
				if($ptmptime >= 5){
					$this->SResponse('Banned!');
				}else{
					if(is_string($exponent) === true and strlen($exponent) > 0){
						$this->SResponse("$exponent");
					}else{
						$this->SResponse('Packet!');
					}
				}
			}else{
				$this->SResponse('Packet!');
			}
			$this->IsBPacketUsed = False;
		}
	}
	// * * *
	// Server Response And Data : Control and fake time and data length for the responses that server gives. 
	//
	Private Function SResponse($SData){
		is_string($SData) === True ?: $SData = 'Packet!';
		$OneZeroTwoFour = 1024;
		$TwoZeroFourEight = 2048;
		$FourZeroNineSix = 4096;
		$DataStringLength = strlen($SData);
		// Try fake data for response length and time prediction-guessing hardening. Octet or Echo
		if($DataStringLength < $OneZeroTwoFour){ // < 1024
			if($DataStringLength === 1023){
				$SData = $SData.' ';
			}else{
				$SData[1022] = ' ';
			}
		}elseif($DataStringLength > $OneZeroTwoFour and $DataStringLength < $TwoZeroFourEight){ // 1024
			if($DataStringLength === 2047){
				$SData = $SData.' ';
			}else{
				$SData[2046] = ' ';
			}
		}elseif($DataStringLength > $TwoZeroFourEight and $DataStringLength < $FourZeroNineSix){ // 2048
			if($DataStringLength === 4095){
				$SData = $SData.' ';
			}else{
				$SData[4094] = ' ';
			}
		}
	}
	// * * *
	// Extended Logging
	//
	private function Extended_Logging($ELData){
		$Extended_Logging_folder = '../Dba/Logs';
		clearstatcache($Extended_Logging_folder);
		if(file_exists($Extended_Logging_folder) === False and is_writeable($Extended_Logging_folder) === True){
			mkdir($Extended_Logging_folder, 0600);
		}
		$Extended_Logging_file = '../Dba/logs/getu.php.log';
		clearstatcache($Extended_Logging_file);
		if(file_exists($Extended_Logging_file) === True and is_writeable($Extended_Logging_file) === True){
			file_get_contents($Extended_Logging_file);
			file_put_contents($Extended_Logging_file);
			$Extended_Logging_data = $ELData;
		}
	}
	// /*/ Code /*/ //
	Private Function GetU_Start($Get_UserName, $Get_AccessToken, $Get_SACData){
		$tmp_ipaddress = $this->Get_IpAddress() ?: $tmp_ipaddress = '';
		if(isset($tmp_ipaddress) === True and is_string($tmp_ipaddress) === True and strlen($tmp_ipaddress) === 64){
			// ** Create Date Hash
			$tmp_daymonthyearhour = 'cafeaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
			$tmp_daymonthyearhour = hash('sha256', date("d:m:Y:G")) ?: $tmp_daymonthyearhour = 'cafeaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'; // X2
			// ** Get Server Date and Time
			$tmp_serverday = date("j");
			$tmp_servermonth = date("n");
			$tmp_serveryear = date("Y");
			$tmp_serverhour = date("G");
			// ** IP logs
			$bannedforhourfolder = '../Dba/iprb/'.$tmp_daymonthyearhour;
			clearstatcache();
			if(isset($bannedforhourfolder) === True and is_string($bannedforhourfolder) === True and file_exists($bannedforhourfolder) === False){
				$bansfolder = '../Dba/iprb/';
				clearstatcache();
				if(isset($bansfolder) === True and is_string($bansfolder) === True and file_exists($bansfolder) === False){
					$rootfolder = '../Dba';
					clearstatcache();
					if(isset($rootfolder) === True and is_string($rootfolder) === True and file_exists($rootfolder) === False){
						if(is_writeable($rootfolder) === True){
							if(mkdir($rootfolder, 0600) === False and $this->ExtendedLogging_E === True){
								// Reference : Critical_1.4
								$this->Extended_Logging('We could not create the folder for writing packet counts : '.$rootfolder.' . Reference : Critical_1.4');
							}
						}else{
							// Reference : Critical_1.5
							$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts : '.$rootfolder.' , seem to not exist and we do not have the permission to create it. Reference : Critical_1.5');
						}
					}else{
						if(isset($rootfolder) === True){
							if(is_string($rootfolder) === True){
								clearstatcache();
								if(file_exists($rootfolder) === False){
									// Reference : Critical_1.2
									$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts : '.$rootfolder.' does not exist. It seemed existen. Reference : Critical_1.2');
								}
							}else{
								// Reference : Critical_1.1
								$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts could not be determined. The variable is not a string. Reference : Critical_1.1');
							}
						}else{
							// Reference : Critical_1.0
							$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts could not be determined. The variable is unset even after we tried to set it. Reference : Critical_1.0');
						}
					}
					clearstatcache();
					if(isset($bansfolder) === True and is_string($bansfolder) === True and file_exists($bansfolder) === False){
						if(is_writeable() === True){
							if(mkdir($bansfolder, 0600) === False and $this->ExtendedLogging_E === True){
								// Reference : Critical_2.4
								$this->Extended_Logging('We could not create the folder for writing packet counts : '.$bansfolder.'  . Reference : Critical_2.4');
							}
						}else{
							// Reference : Critical_2.5
							$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts : '.$bansfolder.' , seem to not exist and we do not have the permission to create it. Reference : Critical_2.5');
						}
					}else{
						if(isset($bansfolder) === True){
							if(is_string($bansfolder) === True){
								clearstatcache();
								if(file_exists($bansfolder) === True){
									// Reference : Critical_2.9
									$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts : '.$bansfolder.' , seemed not existen before but it exist now. We did not created it. Reference : Critical_2.9');
								}
							}else{
								// Reference : Critical_2.7
								$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts could not be determined. The variable is not a string even if it was before. Reference : Critical_2.7');
							}
						}else{
							// Reference : Critical_2.6
							$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts could not be determined. The variable is unset even if it was not before. Reference : Critical_2.6');
						}
					}
				}else{
					if(isset($bansfolder) === True){
						if(is_string($bansfolder) === True){
							clearstatcache();
							if(file_exists($bansfolder) === False){
								// Reference : Critical_2.2
								$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The one time creating folder for writing packet counts : '.$bansfolder.' does not exist. It seemed existen. Reference : Critical_2.2');
							}
						}else{
							// Reference : Critical_2.1
							$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The one time creating folder for writing packet counts could not be determined. The variable is not a string. Reference : Critical_2.1');
						}
					}else{
						// Reference : Critical_2.0
						$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The one time creating folder for writing packet counts could not be determined. The variable is unset even after we tried to set it. Reference : Critical_2.0');
					}
				}
				clearstatcache();
				if(isset($bannedforhourfolder) === True and is_string($bannedforhourfolder) === True and file_exists($bannedforhourfolder) === False){
					if(is_writeable($bannedforhourfolder) === True){
						if(mkdir($bannedforhourfolder, 0600) === False and $this->ExtendedLogging_E === True){
							// Reference : Critical_3.4
							$this->Extended_Logging('We could not create the folder for writing packet counts : '.$bannedforhourfolder.'  . Reference : Critical_3.4');
						}
					}else{
						// Reference : Critical_3.5
						$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts : '.$bannedforhourfolder.' , seem to not exist and we do not have the permission to create it. Reference : Critical_3.5');
					}
				}else{
					if(isset($bannedforhourfolder) === True){
						if(is_string($bannedforhourfolder) === True){
							clearstatcache();
							if(file_exists($bannedforhourfolder) === True){
								// Reference : Critical_3.3
								$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts : '.$bannedforhourfolder.' , seemed not existen before but it exist now. We did not created it. Reference : Critical_3.3');
							}
						}else{
							// Reference : Critical_3.1
							$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts could not be determined. The variable is not a string even if it was before. Reference : Critical_3.1');
						}
					}else{
						// Reference : Critical_3.0
						$this->ExtendedLogging_E === False ?: $this->Extended_Logging('The folder for writing packet counts could not be determined. The variable is unset even if it was not before. Reference : Critical_3.0');
					}
				}
			}
			$tmp_ip_blockedrequests = 0;
			clearstatcache();
			if(isset($bannedforhourfolder) === True and is_string($bannedforhourfolder) === True and file_exists($bannedforhourfolder) === True){
				$onefileip = '../Dba/iprb/'.$tmp_daymonthyearhour.'/1.'.$tmp_ipaddress.'.bip' ?: $onefileip = '../Dba/iprb';
				$twofileip = '../Dba/iprb/'.$tmp_daymonthyearhour.'/2.'.$tmp_ipaddress.'.bip' ?: $twofileip = '../Dba/iprb';
				$threefileip = '../Dba/iprb/'.$tmp_daymonthyearhour.'/3.'.$tmp_ipaddress.'.bip' ?: $threefileip = '../Dba/iprb';
				$fourfileip = '../Dba/iprb/'.$tmp_daymonthyearhour.'/4.'.$tmp_ipaddress.'.bip' ?: $fourfileip = '../Dba/iprb';
				$fivefileip = '../Dba/iprb/'.$tmp_daymonthyearhour.'/5.'.$tmp_ipaddress.'.bip' ?: $fivefileip = '../Dba/iprb';
				clearstatcache();
				if(file_exists($fivefileip) === True){
					$tmp_ip_blockedrequests = 5;
				}elseif(file_exists($fourfileip) === True){
					$tmp_ip_blockedrequests = 4;
				}elseif(file_exists($threefileip) === True){
					$tmp_ip_blockedrequests = 3;
				}elseif(file_exists($twofileip) === True){
					$tmp_ip_blockedrequests = 2;
				}elseif(file_exists($onefileip) === True){
					$tmp_ip_blockedrequests = 1;
				}
			}
			if($tmp_ip_blockedrequests >= 0 and $tmp_ip_blockedrequests < 5){
				is_string($Get_UserName) === True ? $UserName = urlencode($Get_UserName) : $UserName = ' ';
				is_string($Get_AccessToken) === True ? $AccessToken = $Get_AccessToken : $AccessToken = ' ';
				is_string($Get_SACData) === True ? $SACData = urlencode($Get_SACData) : $SACData = '2';
				// For UserName
				for($n = 0; $n < strlen($UserName); $n++){
					$tmp_allowedchars = $this->AllowedChars.$this->AllowedNums ?: $tmp_allowedchars = '';
					$tmp_Name = ' ';
					if($n >= 0 and $n < 15 and isset($UserName) === True and is_string($UserName) === True and strlen($UserName) > 5 and strlen($UserName) < 15 and $n <= strlen($UserName) - 1){
						$tmp_Name = $UserName[$n] ?: $tmp_Name = ' ';
					}else{
						$UserName = ' ';
						break;
					}
					if(isset($tmp_allowedchars) === True and isset($tmp_Name) === True and is_string($tmp_allowedchars) === True and is_string($tmp_Name) === True and strlen($tmp_allowedchars) === 62 and strlen($tmp_Name) === 1){
						if(str_contains($tmp_allowedchars, $tmp_Name) === False){
							$UserName = ' ';
							break;
						}
					}else{
						$UserName = ' ';
						break;
					}
				}
				// For AccessToken
				for($n = 0; $n < strlen($AccessToken); $n++){
					$tmp_allowedchars = $this->AllowedChars.$this->AllowedNums ?: $tmp_allowedchars = '';
					if($n === 64){
						$tmp_allowedchars = ':';
					}elseif($n > 64){
						$tmp_allowedchars = $this->AllowedNums ?: $tmp_allowedchars = '';
					}
					$tmp_AccessToken_Chars = ' ';
					if($n >= 0 and $n < 66 and !empty($AccessToken) and is_string($AccessToken) === True and strlen($AccessToken) > 63 and strlen($AccessToken) < 67 and $n <= strlen($AccessToken) - 1){
						$tmp_AccessToken_Chars = $AccessToken[$n] ?: $tmp_AccessToken_Chars = ' ';
					}else{
						$AccessToken = ' ';
						break;
					}
					if(isset($tmp_allowedchars) === True and isset($tmp_AccessToken_Chars) === True and is_string($tmp_allowedchars) === True and is_string($tmp_AccessToken_Chars) === True and strlen($tmp_allowedchars) >= 1 and strlen($tmp_AccessToken_Chars) === 1){
						if(str_contains($tmp_allowedchars, $tmp_AccessToken_Chars) === False){
							$AccessToken = ' ';
							break;
						}
					}else{
						$AccessToken = ' ';
						break;
					}
				}
				// For SACData
				if(isset($SACData) === True and is_string($SACData) === True and strlen($SACData) === 1){
					if(str_contains($this->AllowedNums, $SACData) === False){
						$SACData = '2';
					}
				}else{
					$SACData = '2';
				}
				if(is_string($UserName) === True and is_string($AccessToken) === True and is_string($SACData) === True and substr_count($AccessToken, ':') === 1 and strlen($UserName) > 4 and strlen($UserName) < 15 and strlen($AccessToken) > 65 and strlen($AccessToken) <= 67 and $SACData > 0){
					$UserName = strtolower($UserName) ?: $UserName = ' ';
					$tmp_AccessToken_GT = explode(':', $AccessToken)[0] ?: $tmp_AccessToken_GT = 0; // Access Token
					$tmp_AccessToken_AC = explode(':', $AccessToken)[1] ?: $tmp_AccessToken_AC = 0; // File Indicator
					$DoubleHash = hash('sha256', $tmp_AccessToken_GT) ? hash('sha256', $tmp_AccessToken_GT) : $DoubleHash = 'A'; // Double X2
					$UserData = '0';
					$UserData = '../Dbu/'.$UserName.'/'.$DoubleHash.'.mdma' ?: $UserData = 'AAA'; // Set the Access Token path
					$tmp_ipfile = '0';
					$tmp_ipfile = '../Dbu/'.$UserName.'/'.$tmp_ipaddress.'.mdma' ?: $tmp_ipfile = 'AAA'; // Set Logged User Ip file
					// ChessCER 1
					$tmp_one_nc = 'AAA';
					$tmp_two_nc = 'BBB';
					$tmp_one_nc = explode('/', $UserData)[2] ?: $tmp_one_nc = 'AAA'; // Check for name
					$tmp_two_nc = explode('/', $tmp_ipfile)[2] ?: $tmp_two_nc = 'BBB'; // Check for name
					// ChessCER 2
					$tmp_one_ip = 'AAA';
					$tmp_two_ip = 'AAA';
					$tmp_forip = ' ';
					$tmp_forip = explode('/', $UserData)[3] ?: $tmp_one_ip = 'AAA';
					substr_count($tmp_forip, '.') === 1 ? $tmp_one_ip = explode('.', $tmp_forip)[0] : $tmp_one_ip = 'AAA';
					$tmp_forip = ' ';
					$tmp_forip = explode('/', $tmp_ipfile)[3] ?: $tmp_one_ip = 'AAA';
					substr_count($tmp_forip, '.') === 1 ? $tmp_two_ip = explode('.', $tmp_forip)[0] : $tmp_one_ip = 'AAA';
					// ChessCER 3
					$tmp_UDone = 'A';
					$tmp_UDone = $UserData[0].$UserData[1].$UserData[2].$UserData[3].$UserData[4].$UserData[5].$UserData[6] ?: $tmp_UDone = 'A'; // The correct place and possision of first two . / 
					$tmp_UDtwo = 0;
					$tmp_UDtwo = $UserData[(int)strlen($UserName)+7] ?: $tmp_UDtwo = 0; // The possision of the last /
					$tmp_UDthree = 'A';
					$tmp_UDthree = $UserData[(int)strlen($UserData)-5].$UserData[(int)strlen($UserData)-4].$UserData[(int)strlen($UserData)-3].$UserData[(int)strlen($UserData)-2].$UserData[(int)strlen($UserData)-1] ?: $tmp_UDthree = 'A'; // The correct place of mdma and possision of last .
					$tmp_IPone = 'A';
					$tmp_IPone = $tmp_ipfile[0].$tmp_ipfile[1].$tmp_ipfile[2].$tmp_ipfile[3].$tmp_ipfile[4].$tmp_ipfile[5].$tmp_ipfile[6] ?: $tmp_IPone = 'A'; // The correct place and possision of first two . / 
					$tmp_IPtwo = 0;
					$tmp_IPtwo = $tmp_ipfile[(int)strlen($UserName)+7] ?: $tmp_IPtwo = 0; // The possision of the last /
					$tmp_IPthree = 'A';
					$tmp_IPthree = $tmp_ipfile[(int)strlen($tmp_ipfile)-5].$tmp_ipfile[(int)strlen($tmp_ipfile)-4].$tmp_ipfile[(int)strlen($tmp_ipfile)-3].$tmp_ipfile[(int)strlen($tmp_ipfile)-2].$tmp_ipfile[(int)strlen($tmp_ipfile)-1] ?: $tmp_IPthree = 'A'; // The correct place of mdma and possion of last .
					// ChessCER 4
					$tmp_UD_atlengthone = 'A';
					$tmp_UD_atlengthone = explode('/', $UserData)[3] ?: $tmp_UD_atlengthone = 'A';
					$tmp_UD_atlengthtwo = 1;
					$tmp_UD_atlengthtwo = strlen($tmp_UD_atlengthone) + strlen($UserName) + 8 ?: $tmp_UD_atlengthtwo = rand(101, 499);
					$tmp_IP_iplengthone = 'A';
					$tmp_IP_iplengthone = explode('/', $tmp_ipfile)[3] ?: $tmp_IP_iplengthone = 'A';
					$tmp_IP_iplengthtwo = 2;
					$tmp_IP_iplengthtwo = strlen($tmp_IP_iplengthone) + strlen($UserName) + 8 ?: $tmp_IP_iplengthtwo = rand(501, 999);
					// End Game
					$tmp_checkaccess = '../Dbu/'.$UserName.'/getu.'.$tmp_AccessToken_GT.'.inUse'; // Set inUse
					clearstatcache();
					if(isset($tmp_AccessToken_AC) === True and is_string($tmp_AccessToken_AC) === True and strlen($tmp_AccessToken_AC) === 1 and (int)$tmp_AccessToken_AC === 3 and isset($tmp_one_nc) === True and is_string($tmp_one_nc) === True and isset($tmp_two_nc) === True and is_string($tmp_two_nc) === True and strlen($tmp_one_nc) > 4 and strlen($tmp_two_nc) > 4 and strlen($tmp_one_nc) < 15 and strlen($tmp_two_nc) < 15 and isset($UserName) === True and is_string($UserName) === True and strlen($tmp_one_nc) === strlen($UserName) and $tmp_one_nc === $UserName and strlen($tmp_two_nc) === strlen($UserName) and $tmp_two_nc === $UserName and $tmp_one_nc === $tmp_two_nc and isset($tmp_one_ip) === True and is_string($tmp_one_ip) === True and isset($tmp_two_ip) === True and is_string($tmp_two_ip) === True and isset($tmp_ipaddress) === True and is_string($tmp_ipaddress) === True and strlen($tmp_one_ip) === 64 and strlen($tmp_two_ip) === 64 and $tmp_one_ip != $tmp_ipaddress and $tmp_one_ip != $tmp_two_ip and $tmp_two_ip === $tmp_ipaddress and isset($UserData) === True and is_string($UserData) === True and isset($tmp_ipfile) === True and is_string($tmp_ipfile) === True and isset($tmp_UDone) === True and strlen($tmp_UDone) === 7 and $tmp_UDone === '../Dbu/' and isset($tmp_UDtwo) === True and is_string($tmp_UDtwo) === True and strlen($tmp_UDtwo) === 1 and $tmp_UDtwo === '/' and isset($tmp_UDthree) === True and strlen($tmp_UDthree) === 5 and $tmp_UDthree === '.mdma' and isset($tmp_IPone) === True and is_string($tmp_IPone) === True and strlen($tmp_IPone) === 7 and $tmp_IPone === '../Dbu/' and isset($tmp_IPtwo) === True and is_string($tmp_IPtwo) === True and strlen($tmp_IPtwo) === 1 and $tmp_IPtwo === '/' and isset($tmp_IPthree) === True and is_string($tmp_IPthree) === True and strlen($tmp_IPthree) === 5 and $tmp_IPthree === '.mdma' and $tmp_UDthree === $tmp_IPthree and isset($tmp_UD_atlengthone) === True and is_string($tmp_UD_atlengthone) === True and strlen($tmp_UD_atlengthone) === 69 and isset($tmp_IP_iplengthone) === True and is_string($tmp_IP_iplengthone) === True and strlen($tmp_IP_iplengthone) === 69 and substr_count($UserData, '.') === 3 and substr_count($tmp_ipfile, '.') === 3 and substr_count($UserData, '/') === 3 and substr_count($tmp_ipfile, '/') === 3 and isset($tmp_UD_atlengthtwo) === True and is_integer($tmp_UD_atlengthtwo) === True and $tmp_UD_atlengthtwo === strlen($UserData) and isset($tmp_IP_iplengthtwo) === True and is_integer($tmp_IP_iplengthtwo) === True and $tmp_IP_iplengthtwo === strlen($tmp_ipfile) and $tmp_UD_atlengthtwo === $tmp_IP_iplengthtwo and strlen($UserData) > 81 and strlen($UserData) < 92 and strlen($tmp_ipfile) > 81 and strlen($tmp_ipfile) < 92 and $tmp_AccessToken_GT != $DoubleHash and hash_equals($tmp_AccessToken_GT, $DoubleHash) === False and strlen($UserData) === strlen($tmp_ipfile) and $UserData != $tmp_ipfile and $DoubleHash != $tmp_ipfile and hash_equals($DoubleHash, $tmp_ipaddress) === False and file_exists($UserData) === True and file_exists($tmp_ipfile) === True and file_exists($tmp_checkaccess) === False){
						clearstatcache($tmp_checkaccess);
						if(is_writeable($tmp_checkaccess) === True){
							file_put_contents($tmp_checkaccess, ' '); // Set .inUse file
							$tmp_CorAccCheck = file_get_contents($UserData) ?: ' ';
							if(strlen($tmp_CorAccCheck) === 64 and $tmp_CorAccCheck === $tmp_ipaddress and hash_equals($tmp_CorAccCheck, $tmp_ipaddress) === True){
								// Inside here we get the new and old user names that contacted the client.
								// We send only the new data if the client don't ask for all data.
								// Each time we wipe new data file and put them in old data
								// We want to CheckAndFix all the data we get, clear the new data file and put everything in old file
								// unless the client specifies that we should delete all data after each read.
								$UsersOldFile = '../Dbu/'.$UserName.'/Messages/Users.data';
								$UsersNewFile = '../Dbu/'.$UserName.'/Messages/Users.n.data';
								clearstatcache();
								if(strlen($UsersOldFile) > 31 and strlen($UsersOldFile) < 42 and strlen($UsersNewFile) > 31 and strlen($UsersNewFile) < 42 and ((file_exists($UsersOldFile) === True and file_exists($UsersNewFile) === True) or (file_exists($UsersOldFile) === True or file_exists($UsersNewFile) === True))){
									$GetUserNewData = '';
									$GetUserOldData = '';
									$GUserCData = '';
									// Check for new data
									clearstatcache($UsersNewFile);
									if(file_exists($UsersNewFile)){
										if(is_readable($UsersNewFile) === True){
											$GetUserNewData = file_get_contents($UsersNewFile) ?: $GetUserNewData = 'Error';
										}
										if(!empty($GetUserNewData) and is_string($GetUserNewData) === True and strlen($GetUserNewData) > 5){
											$tmpmsgcount = substr_count($GetUserNewData, ';') ?: $tmpmsgcount = 0;
											if($tmpmsgcount < 1){
												$GetUserNewData = '';
											}else{
												$GetUserNewData = $this->CheckAndFix($GetUserNewData) ?: $GetUserNewData = '';
												$GUserCData = $GetUserNewData;
												$this->PutConO($UsersNewFile, '');
											}
										}else{
											$GetUserNewData = '';
										}
									}else{
										$GetUserNewData = '';
									}
									// Check for old data
									clearstatcache($UsersOldFile);
									if(file_exists($UsersOldFile)){
										if(is_readable($UsersOldFile) === True){
											$GetUserOldData = file_get_contents($UsersOldFile) ?: $GetUserOldData = 'Error';
										}
										if(!empty($GetUserOldData) and is_string($GetUserOldData) === True and strlen($GetUserOldData) > 5){
											$tmpmsgcount = substr_count($GetUserOldData, ';') ?: $tmpmsgcount = 0;
											if($tmpmsgcount < 1){
												$GetUserOldData = '';
											}else{
												$GetUserOldData = $this->CheckAndFix($GetUserOldData) ?: $GetUserOldData = '';
												$GUserCData = $GUserCData.$GetUserOldData;
												if((int)$SACData === 1){
													$this->PutConO($UsersOldFile, '');
												}else{
													$this->PutConO($UsersOldFile, $GUserCData);
												}
											}
										}else{
											$GetUserOldData = '';
										}
									}else{
										$GetUserOldData = '';
									}
									// *** // --- Send --- // *** //
									$tmp_S_DataLength = 0;
									if(!empty($GUserCData) and is_string($GUserCData) === True){
										$tmp_S_DataLength = strlen($GUserCData) ?: $tmp_S_DataLength = 0;
									}else{
										$tmp_S_DataLength = 0;
									}
									if($tmp_S_DataLength > 5){
										$tmpmsgcount = substr_count($GUserCData, ';') ?: $tmpmsgcount = 0;
										if($tmpmsgcount > 0){
											SResponse($GUserCData);
										}else{
											SResponse('Error');
										}
									}else{
										if($tmp_S_DataLength > 0){
											SResponse('Error');
										}else{
											SResponse('NoUsers');
										}
									}
								}else{
									if(strlen($UsersOldFile) > 31 and strlen($UsersOldFile) < 42 and strlen($UsersNewFile) > 31 and strlen($UsersNewFile) < 42){
										SResponse('NoUsers');
									}else{
										BPacket('Packet!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
									}
								}
								$tmp_CheckChess = False;
								$tmp_CheckChess = UnlinkProperties($tmp_checkaccess) ?: False;
								if($tmp_CheckChess === True){
									if($IsFunctionInUse === False){
										BPacket('Packet!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
									}else{
										BPacket('Error!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
									}
								}
							}else{
								$tmp_CheckChess = False;
								$tmp_CheckChess = UnlinkProperties($tmp_checkaccess) ?: False;
								if($tmp_CheckChess === True){
									if($IsFunctionInUse === False){
										BPacket('Packet!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
									}else{
										BPacket('Error!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
									}
								}
							}
						}
					}else{
						clearstatcache();
						if(isset($tmp_AccessToken_AC) === True and is_string($tmp_AccessToken_AC) === True and strlen($tmp_AccessToken_AC) === 1 and (int)$tmp_AccessToken_AC === 3 and isset($tmp_one_nc) === True and is_string($tmp_one_nc) === True and isset($tmp_two_nc) === True and is_string($tmp_two_nc) === True and strlen($tmp_one_nc) > 4 and strlen($tmp_two_nc) > 4 and strlen($tmp_one_nc) < 15 and strlen($tmp_two_nc) < 15 and isset($UserName) === True and is_string($UserName) === True and strlen($tmp_one_nc) === strlen($UserName) and $tmp_one_nc === $UserName and strlen($tmp_two_nc) === strlen($UserName) and $tmp_two_nc === $UserName and $tmp_one_nc === $tmp_two_nc and isset($tmp_one_ip) === True and is_string($tmp_one_ip) === True and isset($tmp_two_ip) === True and is_string($tmp_two_ip) === True and isset($tmp_ipaddress) === True and is_string($tmp_ipaddress) === True and strlen($tmp_one_ip) === 64 and strlen($tmp_two_ip) === 64 and $tmp_one_ip != $tmp_ipaddress and $tmp_one_ip != $tmp_two_ip and $tmp_two_ip === $tmp_ipaddress and isset($UserData) === True and is_string($UserData) === True and isset($tmp_ipfile) === True and is_string($tmp_ipfile) === True and isset($tmp_UDone) === True and strlen($tmp_UDone) === 7 and $tmp_UDone === '../Dbu/' and isset($tmp_UDtwo) === True and is_string($tmp_UDtwo) === True and strlen($tmp_UDtwo) === 1 and $tmp_UDtwo === '/' and isset($tmp_UDthree) === True and strlen($tmp_UDthree) === 5 and $tmp_UDthree === '.mdma' and isset($tmp_IPone) === True and is_string($tmp_IPone) === True and strlen($tmp_IPone) === 7 and $tmp_IPone === '../Dbu/' and isset($tmp_IPtwo) === True and is_string($tmp_IPtwo) === True and strlen($tmp_IPtwo) === 1 and $tmp_IPtwo === '/' and isset($tmp_IPthree) === True and is_string($tmp_IPthree) === True and strlen($tmp_IPthree) === 5 and $tmp_IPthree === '.mdma' and $tmp_UDthree === $tmp_IPthree and isset($tmp_UD_atlengthone) === True and is_string($tmp_UD_atlengthone) === True and strlen($tmp_UD_atlengthone) === 69 and isset($tmp_IP_iplengthone) === True and is_string($tmp_IP_iplengthone) === True and strlen($tmp_IP_iplengthone) === 69 and substr_count($UserData, '.') === 3 and substr_count($tmp_ipfile, '.') === 3 and substr_count($UserData, '/') === 3 and substr_count($tmp_ipfile, '/') === 3 and isset($tmp_UD_atlengthtwo) === True and is_integer($tmp_UD_atlengthtwo) === True and $tmp_UD_atlengthtwo === strlen($UserData) and isset($tmp_IP_iplengthtwo) === True and is_integer($tmp_IP_iplengthtwo) === True and $tmp_IP_iplengthtwo === strlen($tmp_ipfile) and $tmp_UD_atlengthtwo === $tmp_IP_iplengthtwo and strlen($UserData) > 81 and strlen($UserData) < 92 and strlen($tmp_ipfile) > 81 and strlen($tmp_ipfile) < 92 and $tmp_AccessToken_GT != $DoubleHash and hash_equals($tmp_AccessToken_GT, $DoubleHash) === False and strlen($UserData) === strlen($tmp_ipfile) and $UserData != $tmp_ipfile and $DoubleHash != $tmp_ipfile and hash_equals($DoubleHash, $tmp_ipaddress) === False and file_exists($UserData) === True and file_exists($tmp_ipfile) === True and file_exists($tmp_checkaccess) === True){
							$this->SResponse('Packet!');
						}else{
							$this->BPacket('Packet!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
						}
					}
				}else{
					$this->BPacket('Packet!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
				}
			}elseif($tmp_ip_blockedrequests === 5){
				$this->SResponse('Banned!');
			}else{
				$this->BPacket('Packet!', $tmp_daymonthyearhour, $tmp_ip_blockedrequests, $tmp_ipaddress);
			}
		}else{
			echo 'Packet!'; // There is no ip so we can't count. Just output Packet for this.
		}
	}
	// * * *
	// Construct..OR?
	Public Function __construct($Get_UserName, $Get_AccessToken, $Get_SACData){
		$this->GetU_Start($Get_UserName, $Get_AccessToken, $Get_SACData);
	}
}
?>
