<?php

require_once("LOL_TournamentCode.php");

$data = file_get_contents("php://input");
if($data === FALSE || strlen($data) < 1)
{
	die("Problem getting input from riot.");
}
$result = new TournamentResult($data);
$game = $result->GetGameInfo();

var_dump($game);

?>