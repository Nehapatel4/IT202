<link rel="stylesheet" href="static/css/styles.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<nav>
    <ul class="nav">
        <li><a href="home.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      
        <?php if (is_logged_in() ): ?>
            <li><a href="profile.php">Profile</a></li>
           
            <li><a href="user_create_account.php">Create Account</a></li>
             <li><a href="view_accounts.php">list Account</a></li>
             <li><a href="sample_transactions.php?type=deposit">Deposite</a></li>
              <li><a href="sample_transactions.php?type=withdraw">Withdraw</a></li>
              <li><a href="sample_transactions.php?type=transfer">Transfer</a></li>
              <li><a href="send_money.php?type=withdraw">Send Money</a></li>
        
        <?php endif; ?>
          <?php if (has_role("Admin")): ?>
            
            <li><a href="test_edit_accounts.php?id=1">Edit Accounts</a></li>
        <?php endif; ?>
         <?php if (is_logged_in() ): ?>
         <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>