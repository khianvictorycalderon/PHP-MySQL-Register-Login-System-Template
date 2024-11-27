<?php
session_start();
include_once("page.php");
require_once("config.php");
include_once("register_validation.php");
include_once("login_attempt.php");
include_once("logged_in_page.php");

$Login = '
    <div class="home fs-6 lh-1">
        <div class="form center login-box">
            <h4>' . $system_name . '</h4>
            by: <a href="https://khianvictorycalderon.github.io/">Khian Victory D. Calderon</a>
            <hr/>
            <div class="mb-3 left">
                <form method="POST" onsubmit="return logformvalidation()">
                    <label for="log_name" class="form-label">Username / Email:</label>
                    <input id="log_name" name="log_name" type="text" class="form-control" placeholder="Enter your email or username..." maxlength="100" required>
                    <br/>
                    <label for="log_pass" class="form-label">Password:</label>
                    <input id="log_pass" name="log_pass" type="password" class="form-control" placeholder="Enter your password..." maxlength="100" required>
                    <div class="right">
                        <br/>
                        <input name="login" type="submit" class="btn btn-primary" value="Login" />
                    </div>
                </form>
                <hr/>
            </div>
            <form method="POST" class="center">
                No account? <button name="signup" type="submit" class="no-design text-primary btn-link">Sign Up</button>
            </form>
        </div>
    </div>
  ';

$SignUp = '
<div class="home fs-6 lh-1">
    <div class="form center register-box">
        <h4>Create an Account</h4>
        <hr/>
        <form method="POST" onsubmit="return regformvalidation()">
            <div class="box-2x left">
                <div class="box-list">
                    <label class="form-label">First Name:
                        <input id="reg_firstname" name="reg_firstname" type="text" class="form-control reg-input" maxlength="100" required>
                    </label>
                </div>
                <div class="box-list">
                    <label class="form-label">Middle Name:
                        <input id="reg_middlename" name="reg_middlename" type="text" class="form-control reg-input" maxlength="100" required>
                    </label>
                </div>
                <div class="box-list">
                    <label class="form-label">Last Name:
                        <input id="reg_lastname" name="reg_lastname" type="text" class="form-control reg-input" maxlength="100" required>
                    </label>
                </div>
                <div class="box-list left">
                    <label class="form-label">Birth Date:
                        <input id="reg_birthdate" name="reg_birthdate" type="date" class="form-control reg-input" required>
                    </label>
                </div>
            </div>
            <hr/>
            <div class="box-2x left">
                <div class="box-list">
                    <label class="form-label">Username:
                        <input id="reg_username" name="reg_username" type="text" class="form-control reg-input" maxlength="100" required>
                    </label>
                </div>
                <div class="box-list">
                    <label class="form-label">Email:
                        <input id="reg_email" name="reg_email" type="text" class="form-control reg-input" maxlength="100" required>
                    </label>
                </div>
                <div class="box-list">
                    <label class="form-label">Password:
                        <input id="reg_password" name="reg_password" type="password" class="form-control reg-input" minlength="8" maxlength="100" required>
                    </label>
                </div>
                <div class="box-list">
                    <label class="form-label">Confirm Password:
                        <input id="reg_confirmpassword" name="reg_confirmpassword" type="password" class="form-control reg-input" minlength="8" maxlength="100" required>
                    </label>
                </div>
            </div>
            <br/>
            <h6>Password must be 8 - 100 characters long and must contain atleast:</h6>
            <ul class="left">
                <li>Upper Case Letter</li>
                <li>Lower Case Letter</li>
                <li>Number</li>
                <li>Special Character</li>
            </ul>
            <input name="register" type="submit" class="btn btn-primary" value="Create Account" />
        </form>
        <hr/>
        <form method="POST" class="center">
            Already have an account? <button name="gobacktologinpage" type="submit" class="no-design text-primary btn-link">Login</button>
        </form>
    </div>
</div>
 ';
 if($_SERVER["REQUEST_METHOD"]==="POST") {
    if(isset($_POST["signup"])) {
        $home = 'signup';
    } else if (isset($_POST["gobacktologinpage"])) {
        $home = 'login';
    }
 }
?>

<?php if ($home === 'login') { echo $Login; } ?>
<?php if ($home === 'signup') { echo $SignUp; } ?>
<?php if ($home === 'loggedin') { LoggedIn(); } ?>

