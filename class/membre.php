<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function inscription($_POST) {

    define('WP_USE_THEMES', false);
    define('SHORTINIT', true);
    global $wpdb;
    //$wp_root_path = dirname(dirname(dirname(__DIR_)));
    require('./forum/wp-load.php' );

    require( ABSPATH . WPINC . '/formatting.php' );
    require( ABSPATH . WPINC . '/capabilities.php' );
    require( ABSPATH . WPINC . '/user.php' );
    require( ABSPATH . WPINC . '/meta.php' );
    require( ABSPATH . WPINC . '/pluggable.php' );
    require( ABSPATH . WPINC . '/kses.php' );
    @wp_cookie_constants();

    //$user_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->users");
    //echo "<p>User count is {$user_count}</p>";
    $user_name = $_POST['log'];
    $user_email = $_POST['email'];
    $password = $_POST['pwd'];

    $user_id = username_exists($user_name);
    if (!$user_id and email_exists($user_email) == false) {
        $user_id = wp_create_user($user_name, $password, $user_email);
        connection($_POST);
    } else {
        echo 'User already exists.  Password inherited.';
    }
    
    
    /**
      require_once './class/PasswordHash.php';

      // Connexion au serveur
      $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("erreur de connexion au serveur");

      mysqli_select_db($link, DB_NAME) or die("erreur de connexion a la base de donnees");
      $hasher = new PasswordHash(8, true);
      $hash = $hasher->HashPassword(stripslashes($params['password']));
      if (strlen($hash) < 20)
      fail('Failed to hash new password');
      unset($hasher);


      $insert = "'" . $params['username'] . "','" . $hash . "','" . $params['firstname'] . " " . $params['lastname'] . "','" . $params['email'] . "','','" . date('Y-m-d H:i:s') . "','','" . $params['firstname'] . " " . $params['lastname'] . "'";
      // Creation et envoi de la requete
      $query = "INSERT INTO wp_users (user_login,user_pass,user_nicename,user_email,user_url,user_registered,user_activation_key,display_name) values ($insert)";
      $result = mysqli_query($query) or die("erreur de connexion a la base de d'insertion");
      $idmembre = mysqli_insert_id($link);
      $insertTel = $idmembre . ",1," . $params['phone'];
      $queryTel = "INSERT INTO wp_cimy_uef_data (USER_ID,FIELD_ID,VALUE) values ($insertTel)";
      $resultTel = mysqli_query($queryTel) or die("erreur de connexion a la base de d'insertion");
      mysqli_insert_id($link);
      mysql_close();
      connection($params['username'], $params['password']); */
}

function connection($_POST) {
    define('WP_USE_THEMES', false);
    define('SHORTINIT', true);
    global $wpdb;
    //$wp_root_path = dirname(dirname(dirname(__DIR_)));
    require_once('./forum/wp-load.php' );

    require_once( ABSPATH . WPINC . '/formatting.php' );
    require_once( ABSPATH . WPINC . '/capabilities.php' );
    require_once( ABSPATH . WPINC . '/user.php' );
    require_once( ABSPATH . WPINC . '/meta.php' );
    require_once( ABSPATH . WPINC . '/pluggable.php' );
    @wp_cookie_constants();

    //$user_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->users");
    //echo "<p>User count is {$user_count}</p>";
    $username = $_POST['log'];
    $password = $_POST['pwd'];

    // try to log into the external service or database with username and password
    $ext_auth = wp_authenticate($username, $password);

    if (is_wp_error($ext_auth)) {
        $ext_auth = false;
    }
    // if external authentication was successful
    if ($ext_auth) {
        // find a way to get the user id
        $user_id = username_exists($username);
        // userdata will contain all information about the user
        $userdata = get_userdata($user_id);
        $user = wp_set_current_user($user_id, $username);
        // this will actually make the user authenticated as soon as the cookie is in the browser
        wp_set_auth_cookie($user_id);
        // the wp_login action is used by a lot of plugins, just decide if you need it
        do_action('wp_login', $userdata->ID);
        // you can redirect the authenticated user to the "logged-in-page", define('MY_PROFILE_PAGE',1); f.e. first
        //header("Location:" . get_page_link(MY_PROFILE_PAGE));
    }
    if (!empty($userdata)) {
        $_SESSION['sessionId'] = session_id();
        $_SESSION['user'] = get_object_vars($user);
        $_SESSION['connected'] = true;
        return true;
    } else {
        return false;
    }
}

function isConnected() {
    
}

function fail($pub, $pvt = '') {
    $msg = $pub;
    if ($pvt !== '')
        $msg .= ": $pvt";
    exit("An error occurred ($msg).\n");
}

function logout() {

    define('WP_USE_THEMES', false);
    define('SHORTINIT', true);
    global $wpdb;
    //$wp_root_path = dirname(dirname(dirname(__DIR_)));
    require('./forum/wp-load.php' );

    require( ABSPATH . WPINC . '/formatting.php' );
    require( ABSPATH . WPINC . '/capabilities.php' );
    require( ABSPATH . WPINC . '/user.php' );
    require( ABSPATH . WPINC . '/meta.php' );
    require( ABSPATH . WPINC . '/pluggable.php' );
    wp_logout();
    session_destroy();
    header("Location: /");
}

?>
