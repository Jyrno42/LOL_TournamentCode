<?php

require_once("LOL_TournamentCode.php");

$tournamentCode = new TournamentCode();
$gameConfig = GameConfig::Get("TournamentCodeGame", "secretpass", "http://myServer.com/report.php", "gameTestID");
print $tournamentCode->Generate(TournamentCode::SUMMONERS_RIFT, TournamentCode::BLIND_PICK, 5, TournamentCode::SPEC_FRIENDS, $gameConfig);

?>