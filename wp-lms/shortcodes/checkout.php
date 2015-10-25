<?php

wp_login_form();


?>

<h1>Registration</h1>

<form name="registerform" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">
    <p>
        <label for="user_login">Username</label>
        <input type="text" name="user_login" value="">
    </p>
    <p>
        <label for="user_email">E-mail</label>
        <input type="text" name="user_email" id="user_email" value="">
    </p>
    <p style="display:none">
        <label for="confirm_email">Please leave this field empty</label>
        <input type="text" name="confirm_email" id="confirm_email" value="">
    </p>

    <p id="reg_passmail">A password will be e-mailed to you.</p>

    <input type="hidden" name="redirect_to" value="/login/?action=register&success=1" />
    <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" value="Register" />></p>
</form>

<br>
<br>

<p>
    Checkout Page:</p>
<p>
    <br />
    Date is 6/25/2015</p>
<p>
    Login:&nbsp;</p>
