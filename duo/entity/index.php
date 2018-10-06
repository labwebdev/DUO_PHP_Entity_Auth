<?php
session_start();
/*
 *
 * Simple form prompts for username and then prompts for sending an DUO request
 * 
 * This was retrofitted from the 'Simple Demo of Duo's Web SDK' file
 */

require_once '../../src/Web.php';

//this is not needed for this
define('AKEY', "THISISNOTANECESSARYITEMTOHAVEINTHISPROGRAM");

/*
 * IKEY, SKEY, and HOST should come from the Duo Security admin dashboard
 * on the Auth API protected app. For security reasons, these keys are best stored
 * outside of the webroot in a production implementation.
 */
define('IKEY', "IKEYIKEYIKEYIKEYIKEYIKEYIKEYIKEY");
define('SKEY', "SKEYSKEYSKEYSKEYSKEYSKEYSKEYSKEYSKEYSKEYSKEY");
define('HOST', "api-xxxxxxxxxxx.duosecurity.com");

echo "<html>";
echo '<head>';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '</head>';
echo "<h1>Duo Security Entity Auth</h1>";


/*
 * STEP 3:
 * Once secondary auth has completed you may log in the user
 */
if (isset($_POST['sig_response'])) {
    /*
     * Verify sig response and the username entered. Make sure that verifyResponse
     * returns the username we entered into the form. 
     */
    $resp = Duo\Web::verifyResponse(IKEY, SKEY, AKEY, $_POST['sig_response']);
    if ($resp === $_SESSION['username']) {
        echo 'The user, <strong>' . $resp . '</strong>, has authenticated to DUO<br>';
        echo 'Positive entity identification was <I>SUCCESSFUL!</I>';
    }
    else {
    	echo 'Unable to confirm identity.<br>';
    	echo 'Authentication FAILED!';
    }
    unset($_SESSION['username']);
    echo "<form action='index.php' method='post'>";
    echo "<input type='submit' value='Reset' />";
    echo "</form>";
}

/*
 * STEP 2:
 * set the session username to the username entered into the form;
 * then generate a sig_request and
 * load up the Duo iframe for DUO authentication
 */
else if (isset($_POST['user']))  {
         $_SESSION['username'] = $_POST['user'];
        $sig_request = Duo\Web::signRequest(IKEY, SKEY, AKEY, $_POST['user']);
    ?>
        <script type="text/javascript" src="Duo-Web-v2.js"></script>
        <link rel="stylesheet" type="text/css" href="Duo-Frame.css">
        <iframe id="duo_iframe"
            data-host="<?php echo HOST; ?>"
            data-sig-request="<?php echo $sig_request; ?>"
        ></iframe>

<?php
    echo "<form action='index.php' method='post'>";
    echo "<input type='submit' value='Reset' />";
    echo "</form>";
}

/*
 * STEP 1: login form
 * Prompt for a username
 */
else {
    // Output simple form prompting for username
    echo "<form action='index.php' method='post'>";
    echo "Input Username: <input type='text' name='user' /> <br />";
    echo "<input type='submit' value='Submit' />";
    echo "</form>";
}

echo "</html>";

?>
