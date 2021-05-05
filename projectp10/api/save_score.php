<?php
$response = array("status"=>400, "message"=>"Error saving score");
if(isset($_POST["score"]) && isset($_POST["outcome"])) {
    //we're currently not doing anything with score yet
    //TODO fetch game state data to validate to deter cheating
    require(__DIR__ . "/../lib/helpers.php");
    if (is_logged_in()) {
        $outcome = $_POST["outcome"];// Common::get($_POST, "outcome", "loss");
        $data = $_POST["data"]; //Common::get($_POST, "data", []);
        $data = json_decode($data, true);
        $hashValidation = true;
        $healthValidation = true;
        $dmgValidation = true;
        $ph = null;
        $th = null;
        try {
            $i = 0;
            foreach ($data as $d) {
                $pt = json_decode($d[0], true);
                $et = json_decode($d[1], true);
                $phealth = (float)$pt["h"];
                $ehealth = (float)$et["h"];
                if (!isset($ph)) {
                    $ph = $phealth;
                }
                if (!isset($th)) {
                    $th = $ehealth;
                }
                if ($phealth > $ph || $ehealth > $th) {
                    $healthValidation = false;
                    error_log("Anti-cheat: invalid health, potential healing");
                    break;
                }
                $pdmg = (float)$pt["d"];
                $edmg = (float)$et["d"];
                if ($edmg < ($pdmg * .45)) {
                    error_log("Anti-cheat: Enemy tank damage nerfed less than allowable offset");
                    $dmgValidation = false;
                    break;
                }
                //TODO add other validations as necessary, got lazy to validate other status, but the above are the important ones
                $hash = $d[2];
                $check = md5($d[0] . $d[1]);

                error_log($hash . " vs " . $check);
                error_log(var_export($pt, true));
                error_log(var_export($et, true));
                if ($hash != $check) {
                    $hashValidation = false;
                    error_log("Anti-cheat: hash mismatch");
                    break;
                }
                $i++;
                if($i > 500){
                    //preventing overload of processing, games shouldn't last this long
                    //maybe can be exploited but meh, protect the server
                    break;
                }
            }

        }
        catch(Exception $e){
            error_log("Validation failure");
            error_log($e->getMessage());
            $hashValidation = false;
            $healthValidation = false;
            $dmgValidation = false;
        }
        if(!$hashValidation || !$healthValidation || !$dmgValidation || count($data) < 2){
            $outcome = "loss";
        }
        error_log(var_export($data,true));
        if(is_valid_game(($outcome==="win"))){
            unset($_SESSION["started"]);
            //TODO based on game state calc XP

            //don't feed client data directly into our app/db
            //so we check it and assign a hard coded value
            if($outcome == "win"){
                $gameStatus = "win";
                $xp = 10;
                $points = 1;
            }
            else{
                $gameStatus = "loss";
                $xp = 1;//You learn from losing, right?
                $points = 0;//sorry gotta earn these
            }
            $_SESSION["outcome"] = $gameStatus;
            $user_id = get_user_id();
            //give xp
            $xp_resp = update_experience(get_user_id());//updates based on sum of wins
            if($points != 0) {//!= 0 lets us have the ability to lose points if it becomes a desired feature
                //only save if we have points to save
                
                //give points
                $points_response = changePoints($user_id, $points, "won a round");
                //old method is similar to bank transactions, may update to the same in the future
            }
            else{
                $points_response = true;//force to true since it's pointless to save a change of 0 to DB
                //and we don't want an invalid error recorded or triggered
            }
            $score = $outcome === "win"?1:0;//TODO base it on something like progress/ability?
            $query = "INSERT INTO tfp_scores (user_id, score) VALUES (:uid, :score)";
            $stmt = $db->prepare($query);
            $stmt->execute([":uid"=>get_user_id(), ":score"=>$score]);
            if($xp_response && $points_response){
                $response["status"] = 200;
                $response["message"] = "Saved score";
            }
            else{
                if(!$xp_response){
                    error_log("Error saving xp" . var_export($xp_resp, true));
                }
                if(!$points_response){
                    error_log("Error saving points" . var_export($p_resp, true));
                }
            }
        }
        else{
            $response["message"] = "Invalid game";
        }
    }
    else{
        $response["message"] = "Not logged in";
    }
}
else{
    $response["message"] = "Invalid data";
}
echo json_encode($response);