<?php

    $userid = $_POST["Input_user"];
    
    $con = mysqli_connect("", "", "", "");

    if(!$con)
        die("Could not Connect".mysqli_connect_error());

    $check = mysqli_query($con, "SELECT * FROM UserInfo WHERE  not `UserId` = '". $userid."'");
    //플레이어를 제외한 유저 검색

    $p_Player = mysqli_query($con, "SELECT * FROM UserInfo WHERE `UserId` = '". $userid."'");
    //플레이어검색

    $playernumrows = mysqli_num_rows($p_Player);
    if ($playernumrows == 0)
        die("Player does not exist. \n".mysqli_connect_error()); 
	
	$p_row = mysqli_fetch_assoc($p_Player);
	// if($p_row == false)				   
	// 	die("Player does not exist. p_row\n".mysqli_connect_error());

    $numrows = mysqli_num_rows($check);
    if ($numrows == 0)
        die("ID does not exist. \n".mysqli_connect_error());

    $RowDatas = array();
	$Return   = array();

	for($aa = 0; $aa < $numrows; $aa++)
	{
		$a_row = mysqli_fetch_array($check);
		//$p_row = mysqli_fetch_array($player);
		if($a_row != false)
		{
			$RowDatas["UserId"]   = $a_row["UserId"];      	
			$RowDatas["UserNo"]   = $a_row["UserNo"];      	
			$RowDatas["UserNick"] = $a_row["UserNick"];  
			$RowDatas["UserWin"] = $a_row["UserWin"];
			$RowDatas["UserDefeat"] = $a_row["UserDefeat"];
			$RowDatas["UserGold"] = $a_row["UserGold"];

			//UserItem DB에 접근하여 유닛 수 파악			
			$Item = mysqli_query($con,
			"SELECT UI.ItemName,UI.Level,UI.ItemUsable,UI.UserId,DI.*
			FROM UserItem as UI
            JOIN DefItem as DI
			WHERE UI.`UserId` = '".$a_row["UserNo"]."' AND UI.`isAttack`= 0 AND UI.`isBuy` = 1 AND DI.`No` = UI.`KindOfItem` AND DI.`UnitKind` = 0 ");

			$Itemrows = mysqli_num_rows($Item);
			$RowDatas["UnitCount"] = $Itemrows;
			$Unit = array();
			$RowDatas["Unit"] = array();			
			if ($Itemrows != 0)
			{				
				for($bb = 0; $bb < $Itemrows; $bb++)
				{
					$I_row = mysqli_fetch_array($Item);
					if ($I_row != false)
					{	
						$Unit["Level"] = $I_row["Level"];
						$Unit["UnitAttack"] = $I_row["UnitAttack"];
					}
					array_push($RowDatas["Unit"], $Unit);
				}								
			}
			//주석처리 Ctrl + / (VSC)
			//UserItem DB에 접근하여 유닛 수 파악

			array_push($Return, $RowDatas);	
		}
		
	}

	$Deck = mysqli_query($con,
		"SELECT *
		FROM UserDEC
		WHERE `UserN` = '".$p_row["UserNo"]."'");

	$DeckData = array();
	$DeckReturn = array();
	$Deckrows = mysqli_num_rows($Deck);	
	for ($bb = 0; $bb < $Deckrows; $bb++)
	{
		$D_row = mysqli_fetch_array($Deck);
		if ($D_row != false)
		{		
			$DeckData["UserDecN"] = $D_row["UserDecN"];
			$DeckData["UserDec1"] = $D_row["UserDec1"];
			$DeckData["UserDec2"] = $D_row["UserDec2"];
			$DeckData["UserDec3"] = $D_row["UserDec3"];
			$DeckData["UserDec4"] = $D_row["UserDec4"];
			$DeckData["UserDec5"] = $D_row["UserDec5"];
			$DeckData["UserN"] = $D_row["UserN"];
			$DeckData["UserDec1_Num"] = $D_row["UserDec1_Num"];
			$DeckData["UserDec2_Num"] = $D_row["UserDec2_Num"];
			$DeckData["UserDec3_Num"] = $D_row["UserDec3_Num"];
			$DeckData["UserDec4_Num"] = $D_row["UserDec4_Num"];
			$DeckData["UserDec5_Num"] = $D_row["UserDec5_Num"];
			$DeckData["UserDecCount"] = $D_row["UserDecCount"];
		}
		array_push($DeckReturn, $DeckData);			
	}

    $JSONBUFF['RkList'] = $Return;
	$JSONBUFF['DeckCount'] = $Deckrows;
	$JSONBUFF['DeckList'] = $DeckReturn;
    $output = json_encode($JSONBUFF, JSON_UNESCAPED_UNICODE); 
	echo $output;
	echo "<br>";
	echo "DB Connect";

	mysqli_close($con);
?>