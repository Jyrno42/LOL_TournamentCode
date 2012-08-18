LOL_TournamentCode
==================

Some PHP code for RIOTs TournamentCode generation/stats retrieve helping.

###Usage

####Generating a code:
```php
$tournamentCode = new TournamentCode();
$gameConfig = GameConfig::Get("TournamentCodeGame", "secretpass", "http://myServer.com/report.php", "gameTestID");
print $tournamentCode->Generate(
          TournamentCode::SUMMONERS_RIFT, // Summoners rift as map
          TournamentCode::BLIND_PICK, // Blind pick as picktype
          5, // 5 players in each team
          TournamentCode::SPEC_FRIENDS, // Allow only friends to spectate
          $gameConfig // Use the gameconfig we just created!
);
```

####Getting the report
```php
$data = file_get_contents("php://input");
if($data === FALSE || strlen($data) < 1)
{
  die("Problem getting input from riot.");
}
$result = new TournamentResult($data);
$game = $result->GetGameInfo();

var_dump($game);
```