<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()){
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
    $id = $_GET["id"];
}
?>
<?php
$db = getDB();
$stmt = $db->prepare("SELECT A1.account_type, A1.balance, T.action_type, A1.account_number as Src, A2.account_number as Dest, T.amount from Transactions as T JOIN Accounts as A1 on A1.id = T.act_src_id JOIN Accounts as A2 on A2.id = T.act_dest_id  WHERE act_src_id = :id LIMIT 10");
$r = $stmt->execute([":id"=>$id]);
//echo var_export($stmt->errorInfo(), true);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<h3>Accounts</h3>

<div class="results">
    <?php if (count($accounts) > 0): ?>
        <div class="list-group">
            <?php foreach ($accounts as $r): ?>
                <div class="list-group-item">
                    
                    <div>
                        <div>Account Number:</div>
                        <div><?php safer_echo($r["Src"]); ?></div>
                    </div>
                    <div>
                        <div>Account Type:</div>
                        <div><?php safer_echo($r["account_type"]); ?></div>
                    </div>
                      <div>
                        <div>Action Type:</div>
                        <div><?php safer_echo($r["action_type"]); ?></div>
                    </div>
                    <div>
                        <div>Balance:</div>
                        <div><?php safer_echo($r["balance"]); ?></div>
                    </div>
                    
                   
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
