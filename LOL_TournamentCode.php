<?php

class TournamentCode
{
	const SUMMONERS_RIFT = 1;
	const TWISTED_TREELINE = 4;
	const PROVING_GROUNDS = 7;
	//	const CRYSTAL_SCAR = 3;
	
	const BLIND_PICK = 1;
	const DRAFT_MODE = 2;
	const ALL_RANDOM = 4;
	const TOURNAMENT_DRAFT = 6;
	
	const SPEC_NONE = "NONE";
	const SPEC_ALL = "ALL";
	const SPEC_LOBBYONLY = "LOBBYONLY";
	const SPEC_FRIENDS = "FRIENDLISTONLY"; // TODO: Find out this parameters real value.

	public $mapId = self::SUMMONERS_RIFT;
	public $pickId = self::BLIND_PICK;
	public $teamSize = 5;
	public $spec = self::SPEC_ALL;
	
	public $urlFormat = "pvpnet://lol/customgame/joinorcreate/map%d/pick%d/team%d/spec%s/%s";
	
	public static function GetMaps()
	{
		return array(
			self::SUMMONERS_RIFT => self::MapName(self::SUMMONERS_RIFT),
			self::TWISTED_TREELINE => self::MapName(self::TWISTED_TREELINE),
			self::PROVING_GROUNDS => self::MapName(self::PROVING_GROUNDS)
		);
	}
	
	public static function MapName($mapId)
	{
		if($mapId == self::SUMMONERS_RIFT)
			return "Summoners Rift";
		else if($mapId == self::TWISTED_TREELINE)
			return "Twisted Treeline";
		else if($mapId == self::PROVING_GROUNDS)
			return "Proving Grounds";
		else
			return "Unknown";
	}
	
	public static function GetTypes()
	{
		return array(
			self::BLIND_PICK => self::PickTypeStr(self::BLIND_PICK),
			self::DRAFT_MODE => self::PickTypeStr(self::DRAFT_MODE),
			self::ALL_RANDOM => self::PickTypeStr(self::ALL_RANDOM),
			self::TOURNAMENT_DRAFT => self::PickTypeStr(self::TOURNAMENT_DRAFT),
		);
	}
	
	public static function PickTypeStr($pickType)
	{
		if($pickType == self::BLIND_PICK)
			return "Blind Pick";
		else if($pickType == self::DRAFT_MODE)
			return "Draft Mode";
		else if($pickType == self::ALL_RANDOM)
			return "All Random";
		else if($pickType == self::TOURNAMENT_DRAFT)
			return "Tournament Draft";
		else
			return "Unknown";
	}
	
	protected static function clamp($val, $min, $max)
	{
		if($val < $min) $val = $min;
		if($val > $max) $val = $max;
		return $val;
	}
	
	public function Generate($mapId=null, $pickId=null, $teamSize=null, $spec=null, $conf=null)
	{
		$mapId = $mapId ? $mapId : $this->mapId;
		$pickId = $pickId ? $pickId : $this->pickId;
		$teamSize = TournamentCode::clamp(($teamSize ? $teamSize : $this->teamSize), 0, 5);
		$spec = $spec ? $spec : $this->spec;
		
		$conf = $conf ? $conf : "eyJuYW1lIjoiYW5vdGhlciBnYW1lIiwiZXh0cmEiOiJ7XCJnYW1lXCI6XCJhIGdhbWVcIn0iLCJwYXNzd29yZCI6Ijk4MjM0NzUwMjM0ODk1NyIsInJlcG9ydCI6Ind3dy5nb29nbGUuY29tIn0=";
		
		if($mapId == self::TWISTED_TREELINE)
		{
			$teamSize = TournamentCode::clamp($teamSize, 0, 3);
		}
		
		return sprintf($this->urlFormat, $mapId, $pickId, $teamSize, $spec, $conf);
	}
}

class GameConfig
{
	public static function Get($name, $password, $reporturl, $innerGameID)
	{
		$arr = array(
		
			"name" => $name,
			"extra" => json_encode(array("game" => $innerGameID)),
			"password" => $password,
			"report" => $reporturl//,
			//"team" => 2
		
		);
		return base64_encode(json_encode($arr));
	}
}

class LolHelper
{

	public static function MapChampion($n)
	{
		$n = strtolower($n);
		return "http://edge2.mobafire.com/images/champion/icon/$n.png";
	}
}

class TournamentResult
{
	private $innerClass = null;
	
	public function TournamentResult($str)
	{
		$this->innerClass = json_decode($str);
	}
	
	public function GetGameInfo()
	{
		$game = array();
		$game["key"] = $this->innerClass->tournamentMetaData->passbackDataPacket;
		
		$game["teams"] = array();
		$game["teams"][0] = array();
		$game["teams"][1] = array();
		
		foreach($this->innerClass->teamPlayerParticipantsSummaries as $k => $v)
		{
			if($v->isWinningTeam)
				$game["winner"] = 0;
			$game["teams"][0][] = $v->summonerName;
		}
		foreach($this->innerClass->otherTeamPlayerParticipantsSummaries as $k => $v)
		{
			if($v->isWinningTeam)
				$game["winner"] = 1;
			$game["teams"][1][] = $v->summonerName;
		}
	
		return $game;
	}
	
	public function PrintSummoner($team, $id)
	{
		$tObj = $team == 0 ? $this->innerClass->teamPlayerParticipantsSummaries : $this->innerClass->otherTeamPlayerParticipantsSummaries;
		$cId = LolHelper::MapChampion($tObj[$id]->skinName);
		$spell0 = $tObj[$id]->spell1Id;
		$spell1 = $tObj[$id]->spell2Id;
		
		$col = $tObj[$id]->isWinningTeam ? "#00FF00" : "#FF0000";
	
		print "<div style='background: $col; width: 248px; margin-left: 10px; float: left;'>";
		print "<img src='$cId' />";
		print "<img src='spells/$spell0.png' />";
		print "<img src='spells/$spell1.png' />";
		print "</div>";
	}
}

?>