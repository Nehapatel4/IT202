<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
echo"<h1> Send Money</h1";
?>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function do_bank_action($account1, $account2, $amountChange, $type){
	//require("config.php");
	//$conn_string = "mysql:host=$host;dbname=$database;charset=utf8mb4";
	  //$db = new PDO($conn_string, $username, $password);
  $db = getDB();
  $stmt = $db->prepare("select  IFNULL (SUM(amount),0) as balance FROM Transactions where act_src_id=:id"); 
  $result = $stmt->execute([":id"=>$account1]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $a1total = $result["balance"];
  $result = $stmt->execute([":id"=>$account2]);
   $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $a2total = $result["balance"];
  //$a1total = 0;TODO get expected_total of account 1
  //$a2total = 0;TODO get expected_total of account 2
  $query = "INSERT INTO `Transactions` (`act_src_id`, `act_dest_id`, `amount`, `action_type`, `expected_Total`) 
    VALUES(:p1a1, :p1a2, :p1change, :type, :a1total), 
			(:p2a1, :p2a2, :p2change, :type, :a2total)";
  $a1total += $amountChange;
  $a2total -= $amountChange; 
	
	$stmt = $db->prepare($query);
	$stmt->bindValue(":p1a1", $account1);
	$stmt->bindValue(":p1a2", $account2);
	$stmt->bindValue(":p1change", $amountChange);
	$stmt->bindValue(":type", $type);
	$stmt->bindValue(":a1total", $a1total);
	//flip data for other half of transaction
	$stmt->bindValue(":p2a1", $account2);
	$stmt->bindValue(":p2a2", $account1);
	$stmt->bindValue(":p2change", ($amountChange*-1));
	$stmt->bindValue(":type", $type);
	$stmt->bindValue(":a2total", $a2total);
	$result = $stmt->execute();
	echo var_export("Sucessful Transfer");
  //echo var_export($result, true);
	//echo var_export($stmt->errorInfo(), true);
  
 $stmt = $db->prepare("Update Accounts set balance = (select  IFNULL (SUM(amount),0) FROM Transactions where act_src_id=:id) WHERE id = :id"); 
 $result = $stmt->execute([":id"=>$account1]);
 $result = $stmt->execute([":id"=>$account2]);

}
$db = getDB();
$stmt = $db->prepare("SELECT id,account_number from Accounts WHERE user_id = :id LIMIT 10 ");
$r = $stmt->execute([":id"=>get_user_id()]);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<form method="POST">

        

	 <select class="form-control" name="account1">
                    <option value="-1">Source</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php safer_echo($acc["id"]); ?>"
                        ><?php safer_echo($acc["account_number"]); ?></option>
                    <?php endforeach; ?>
                </select>
  <!-- If our sample is a transfer show other account field-->
	<?php if($_GET["type"] == 'transfer') : ?>
	 <select class="form-control" name="account2">
                    <option value="-1">Destination</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php safer_echo($acc["id"]); ?>"
                        ><?php safer_echo($acc["account_number"]); ?></option>
                    <?php endforeach; ?>
                </select>
  <!--<input type="text" name="account2" placeholder="Other Account Number">-->
	<?php endif; ?>
	<input type="number" name="amount" placeholder="Last 4 Digit Account Number"/>
	<input type="hidden" name="type" value="<?php echo $_GET['type'];?>"/>
 
	<input type="number" name="amount" placeholder="$0.00"/>
	<input type="hidden" name="type" value="<?php echo $_GET['type'];?>"/>

	
	 
  <!--Based on sample type change the submit button display-->
	<input type="submit" value="Move Money"/>
</form>

<?php
if(isset($_POST['type']) && isset($_POST['account1']) && isset($_POST['amount'])){
	$type = $_POST['type'];
 if(isset($_POST['account2'])){
 
    $account2 = $_POST['account2'];
    }
	$amount = (int)$_POST['amount'];
  $db = getDB();
  $stmt = $db->prepare("SELECT id FROM Accounts where account_number = '000000000000'"); 
  $result = $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $world = $result["id"];
	switch($type){
		case 'deposit':
			do_bank_action($world, $_POST['account1'], ($amount * -1), $type);
			break;
		case 'withdraw':
			do_bank_action($_POST['account1'], $world, ($amount * -1), $type);
			break;
		case 'transfer':
				do_bank_action($_POST['account1'], $account2, ($amount * -1), $type);
      
			break;
	}
}
?>