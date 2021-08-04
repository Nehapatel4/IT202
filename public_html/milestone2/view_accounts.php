<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()){
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$db = getDB();
$stmt = $db->prepare("SELECT * from Accounts WHERE user_id = :id LIMIT 10 ");
$r = $stmt->execute([":id"=>get_user_id()]);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<h3>Accounts</h3>

<div class="results">
    <?php if (count($accounts) > 0): ?>
        <div class="list-group">
            <?php foreach ($accounts as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Owner Id:</div>
                        <div><?php safer_echo($r["user_id"]); ?></div>
                    </div>
                    <div>
                        <div>Account Number:</div>
                        <div><?php safer_echo($r["account_number"]); ?></div>
                    </div>
                    <div>
                        <div>Account Type:</div>
                        <div><?php safer_echo($r["account_type"]); ?></div>
                    </div>
                    <div>
                        <div>Balance:</div>
                        <div><?php safer_echo($r["balance"]); ?></div>
                    </div>
                    
                    <div>
                        <a type="button" href="test_edit_accounts.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_account.php?id=<?php safer_echo($r['id']); ?>">View</a>
                         <a type="button" href="transaction_history.php?id=<?php safer_echo($r['id']); ?>">History</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
