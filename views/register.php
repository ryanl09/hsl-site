<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

if (isset($_SESSION['user']) && $_SESSION['user']->get_id()) {
    header('Location: ' . href('dashboard'));
}

start_content_full(0, 'register'); ?>

<div class="login-box">
    <div class="login-header">
        <h1>Register</h1>
        <img src="https://tecesports.com/images/tec-black.png" alt="TEC">
    </div>
    <div class="login-body">
        <div class="row errors" style="display:none;"></div>
        <div class="row">
            <label for="user-type">I am a:</label>
            <div class="input">
                <i class='bx bxs-user-badge' ></i>
                <select name="user-type" id="user-type">
                    <option value="player">Player</option>
                    <option value="team_manager">Team Manager</option>
                    <option value="caster">Caster</option>
                    <option value="college">College</option>
                </select>
            </div>
        </div>
        <div class="row e2">
            <div class="input">
                <i class='bx bxs-user-detail' ></i>
                <input type="text" name="firstname" id="firstname" placeholder="First name *">
            </div>
            <div class="input">
            <i class='bx bx-list-ul lname' ></i>
                <input type="text" name="lastname" id="lastname" placeholder="Last name *">
            </div>
        </div>
        <div class="row">
            <div class="input">
                <i class='bx bx-user-pin' ></i>
                <input type="text" name="pronouns" id="pronouns" placeholder="Pronouns *">
            </div>
        </div>
        <div class="row e2">
            <div class="input">
                <i class='bx bxs-envelope' ></i>
                <input type="email" name="email" id="email" placeholder="Email *">
            </div>
            <div class="input">
                <i class="bx bxl-discord-alt discord" ></i>
                <input type="text" name="discord" id="discord" placeholder="Discord (include #XXXX)">
            </div>
        </div>
        <div class="row">
            <div class="input">
                <i class='bx bxs-user' ></i>
                <input type="text" name="username" id="username" placeholder="Username *">
            </div>
        </div>
        <div class="row e2">
            <div class="input">
                <i class='bx bxs-key'></i>
                <input type="password" name="password" id="password" placeholder="Password *">
            </div>
            <div class="input">
                <i class='bx bxs-check-circle cpass' ></i>
                <input type="password" name="c_password" id="c_password" placeholder="Confirm password *">
            </div>
        </div>
        <div class="row" id="disp-schoolcode">
            <div class="input">
                <i class='bx bxs-school'></i>
                <input type="text" name="schoolcode" id="schoolcode" placeholder="Team Code">
            </div>
        </div>
        <div class="row e2" id="field-schoolinfo" style="display:none;">
            <div class="input">
                <i class='bx bxs-school' ></i>
                <input type="text" name="school" id="school" placeholder="School name (exclude 'high school', etc) *">
            </div>
            <div class="input">
                <i class='bx bxs-face-mask mascot' ></i>
                <input type="text" name="mascot" id="mascot" placeholder="Mascot">
            </div>
        </div>
        <div class="row e2" id="field-schoolinfo2" style="display:none;">
            <div class="input">
                <i class='bx bxs-phone'></i>
                <input type="tel" name="phone" id="phone" placeholder="Phone number">
            </div>
            <div class="input colors">
                <div class="color prim">
                    <input type="color" id="primarycolor">
                    <input type="text" id="primarycolor-text" placeholder="Primary color">
                </div>
                <div class="color seco">
                    <input type="color" id="secondarycolor">
                    <input type="text" id="secondarycolor-text" placeholder="Second color">
                </div>
            </div>
        </div>
        <div class="row" id="field-schoolinfo">
        </div>
        <div class="pass-specs" style="display:none;">
            <p id="p-len" class="good"><i class='bx bx-check'></i>8 or more characters</p>
            <p id="p-low" class="good"><i class='bx bx-check'></i>1 lowercase letter</p>
            <p id="p-upp" class="good"><i class='bx bx-check'></i>1 uppercase letter</p>
            <p id="p-spe" class="bad"><i class='bx bx-x' ></i>1 special character</p>
            <p id="p-mat" class="bad"><i class='bx bx-x' ></i>Passwords match</p>
        </div>
        <div class="check">
            <input type="checkbox" name="showpass" id="showpass">
            <label for="showpass" id="showpass-label">Show password</label>
        </div>
        <div class="check">
            <input type="checkbox" name="terms" id="terms">
            <label for="terms">I have read and accept both the <a href="<?php echo href('terms'); ?>">Terms & Conditions</a> and <a href="<?php echo href('privacy'); ?>">Privacy Policy</a></label>
        </div>
        <div class="row">
            <div class="input">
                <input type="submit" value="Register" id='register-btn'>
            </div>
        </div>
    </div>
    <div class="login-footer">
        <a href="<?php echo href('login'); ?>">Click here to login</a>
    </div>
</div>
<?php end_content_full(1); ?>