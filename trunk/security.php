<?php
session_start();
global $etm_config; require_once('config.php');

if (isset($_REQUEST['logout'])) {
    session_destroy();
    exit;
}

if (isset($_REQUEST['password'])) {
    if ($_REQUEST['password'] === $etm_config['password']) {
        $_SESSION['fingerprint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_REQUEST['password'] . $_SERVER['REMOTE_ADDR']);
        $_SESSION['viewFolder'] = true;
    }
}

if (
    !isset($_SESSION['fingerprint'])
    || $_SESSION['fingerprint'] != md5($_SERVER['HTTP_USER_AGENT'] . $etm_config['password'] . $_SERVER['REMOTE_ADDR'])
) {

    echo "<html>
    <body>
        <form method='POST'>
            <input name='password' type='password'/> <input type='submit' value='Go'/>
        </form>
    </body>
</html>";
    session_destroy();
    exit();
}