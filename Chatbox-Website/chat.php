<?php

/*
 * SQLite database.
 *
 * The database is stored outside the web root.
 * The data directory will automatically be created
 * if it does not already exist.
 */

$dataDir = __DIR__ . '/../data';
$dbPath = $dataDir . '/chat.db';

if (!is_dir($dataDir)) {

    mkdir(
        $dataDir,
        0700,
        true
    );

}

$db = new SQLite3($dbPath);


/*
 * Create the messages table if it doesn't exist.
 */

$db->exec('
    CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        message TEXT NOT NULL,
        created_at TEXT NOT NULL
    )
');


/*
 * Validate input.
 *
 * This removes leading/trailing whitespace and
 * limits the value to 256 characters.
 *
 * We deliberately do NOT use htmlspecialchars()
 * here. SQLite stores the actual plain text.
 */

function validateInput($input)
{
    return substr(trim($input), 0, 256);
}


/*
 * Blocked keywords.
 *
 * This check is performed server-side because
 * JavaScript can be bypassed.
 */

$blockedKeywords = array(
    'php',
    'javascript',
    'script'
);


/*
 * POST REQUEST
 *
 * Save a new message.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    /*
     * Get the nickname.
     */

    $name = isset($_POST['name'])
        ? validateInput($_POST['name'])
        : 'Anonymous';


    /*
     * Get the message.
     */

    $message = isset($_POST['message'])
        ? validateInput($_POST['message'])
        : '';


    /*
     * Check for blocked keywords.
     */

    foreach ($blockedKeywords as $keyword) {

        if (
            stripos($message, $keyword) !== false
        ) {

            http_response_code(400);

            exit(
                'Blocked keyword detected! ' .
                'Your message contains blocked content.'
            );

        }

    }


    /*
     * Make sure the fields aren't empty.
     */

    if (
        $name === '' ||
        $message === ''
    ) {

        http_response_code(400);

        exit(
            'Invalid input data.'
        );

    }


    /*
     * Generate the timestamp in UTC.
     */

    $timestamp = gmdate(
        'd-M-Y H:i:s'
    );


    /*
     * Prepare the SQLite INSERT statement.
     */

    $stmt = $db->prepare('
        INSERT INTO messages
            (name, message, created_at)
        VALUES
            (:name, :message, :created_at)
    ');


    /*
     * Bind the submitted values.
     */

    $stmt->bindValue(
        ':name',
        $name,
        SQLITE3_TEXT
    );

    $stmt->bindValue(
        ':message',
        $message,
        SQLITE3_TEXT
    );

    $stmt->bindValue(
        ':created_at',
        $timestamp,
        SQLITE3_TEXT
    );


    /*
     * Execute the INSERT.
     */

    $result = $stmt->execute();


    /*
     * Check for a database error.
     */

    if ($result === false) {

        http_response_code(500);

        exit(
            'Database error.'
        );

    }


    /*
     * Keep only the newest 50 messages.
     *
     * Older messages are deleted automatically.
     */

    $db->exec('
        DELETE FROM messages
        WHERE id NOT IN (
            SELECT id
            FROM messages
            ORDER BY id DESC
            LIMIT 50
        )
    ');


    /*
     * Successful response.
     */

    http_response_code(200);

    exit(
        'Message saved successfully.'
    );

}


/*
 * GET REQUEST
 *
 * Return the current messages.
 */

if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    /*
     * Get the messages in chronological order.
     */

    $result = $db->query('
        SELECT
            name,
            message,
            created_at
        FROM messages
        ORDER BY id ASC
    ');


    /*
     * Return one message per line.
     */

    while (
        $row = $result->fetchArray(SQLITE3_ASSOC)
    ) {


        /*
         * Escape the values when they leave the
         * database and are sent toward the browser.
         *
         * The database itself still contains the
         * original plain text.
         */

        $name = htmlspecialchars(
            $row['name'],
            ENT_QUOTES,
            'UTF-8'
        );

        $message = htmlspecialchars(
            $row['message'],
            ENT_QUOTES,
            'UTF-8'
        );


        echo '['
            . $row['created_at']
            . '] '
            . $name
            . ': '
            . $message
            . PHP_EOL;

    }


    exit;

}


/*
 * Reject unsupported HTTP methods.
 */

http_response_code(405);

exit(
    'Method Not Allowed.'
);

?>
