<!-- <?php
// Register your site and set proper keys https://www.google.com/recaptcha/admin/
// don't forget to change the key in the index.html as well.
$GOOGLE_CAPTCHA_SECRET = "6LcbNOAaAAAAAPTCfldAMoglSVRSmiz1ol9Ym8z0";
// Your email address where the emails should send to
$EMAIL_TO = "nicodemusekuwam@gmail.com";


// Turn off all error reporting
error_reporting(0);

// PRIMARY CHECK If not a POST FORM request we say "adieu"
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    /* Redirect to a different page in the current directory that was requested */
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    // since webserver handles index.html / index.php with the slash alias no needed
    $extra = '';
    header("Location: http://$host$uri/$extra");
    exit;
}


/**
 * Validates the Google Captcha Code from the field over the backend
 *n
 * @param string $submittedCaptcha
 * @return boolean
 */
function is_google_captcha_valid($submittedCaptcha)
{
    global $GOOGLE_CAPTCHA_SECRET;
    $postdata = http_build_query([
        'secret'   => $GOOGLE_CAPTCHA_SECRET,
        'response' => $submittedCaptcha
    ]);
    $opts = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        ]
    ];
    $context  = stream_context_create($opts);
    $result = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify',
        false,
        $context
    );

    $check = json_decode($result);
    return $check->success;
}


/**
 * Cleans the data input string data
 *
 * @param string $data
 * @return string
 */
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    return $data;
}

/**
 * Simple mail to send function
 *
 * @param string $from email
 * @param string $subject
 * @param string $message
 * @return bool
 */
function send_email($from, $subject, $message)
{
    global $EMAIL_TO;

    $headers = 'From: ' . $from . '' . "\r\n" .
        'Reply-To: ' . $from . '' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    return mail($EMAIL_TO, $subject, $message, $headers);
}

/**
 * Constructs our message from the form
 *
 * @param array $form_data
 * @return string
 */
function constuct_email_message($data)
{
    $message = "";
    foreach ($data as $key => $value) {
        $message .= $key . " : " . $value . "\n";
    }
    return $message;
}

// define variables and set to empty values
$response = [
    'success' => true,
    'errors' => []
];

// requiredFields from our form 
$requiredFields = [
    'name' => null,
    'email' => null,
    'subject' => null,
    'message' => null,
    'g-recaptcha-response' => null,
];
// value as reference to update the value
foreach ($requiredFields as $field => &$value) {
    if (empty($_POST[$field])) {
        // adding an error for the specific field
        $response['errors'][$field] = ucfirst($field) . " is required";
    } else {
        $value = clean_input($_POST[$field]);
        if ($field == 'email') {
            $value = filter_var($value, FILTER_SANITIZE_EMAIL);
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $response['errors'][$field] = ucfirst($field) . " is not a valid email";
            }
        } else if ($field == 'g-recaptcha-response') {
            if (!is_google_captcha_valid($value)) {
                $response['errors'][$field] = "Google Captcha value is not valid";
            }
        }
    }
}

// if we have any errors then it was not successful
if (count($response['errors']) > 0) {
    $response['success'] = false;
} else {
    // delete here key's from array which should not be sent
    unset($requiredFields['g-recaptcha-response']);

    $message = constuct_email_message($requiredFields);
    $subject = "Contact form submission";
    send_email($requiredFields['email'], $subject, $message);
}


// send our response 
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($response); -->
