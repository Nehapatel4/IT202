<?php require_once(__DIR__ . "/partials/nav.php"); ?>
  <?php if (!is_logged_in()): ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    <?php endif; ?>

    <form method="POST">
       
        <label>Account Type</label>
        <select name="account_type">
            <option>Checking</option>
            <option>Savings</option>
            <option>Loan</option>
            <option>World</option>
        </select>
        <label>Balance</label>
        <input type="number" name="balance"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php



     if(isset($_POST["save"])){
      $isValid = true;
      $balance = $_POST["balance"];
        if($balance < 5){
        flash("Low balance, need amount higher than 5");
        $isValid = false;
        print($isValid);
      }
      if($isValid){
      //TODO add proper validation/checks
    
      $account_number = (string)rand(000000000000,999999999999);
         
      $account_type = $_POST["account_type"]; 
      
      $user = get_user_id();
      $db = getDB();
      $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, balance, user_id) VALUES(:account_number, :account_type, :balance,:user)");
      $r = $stmt->execute([
          ":account_number"=>$account_number,
          ":account_type"=>$account_type,
          ":balance"=>$balance,
          ":user"=>$user
      ]);
      if($r){
          flash("Created successfully with id: " . $db->lastInsertId());
      }
      else{
          $e = $stmt->errorInfo();
          flash("Error creating: " . var_export($e, true));
      }
  }
 

}


?>
<?php require(__DIR__ . "/partials/flash.php");