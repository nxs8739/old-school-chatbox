# Simple Chatbox Application

1. **Overview:**

   * A straightforward chatbox/shoutbox-like application developed using HTML, CSS, JavaScript, PHP, and SQLite.
   * The application provides a simple real-time-style chat interface without requiring a large web framework or database server.

2. **Installation:**

   * Copy the contents of the `Chatbox-Website` folder into your web server directory.
   * Make sure PHP is installed and the SQLite3 extension is enabled.
   * The `chat.db` database file will automatically be created in the same directory as `chat.php` when the application is first run.
   * Voila! Your chatbox is ready to roll!

3. **Message Storage:**

   * Messages are stored in an SQLite database named `chat.db`.
   * SQLite is embedded directly into the application and does not require a separate database server such as MySQL or MariaDB.
   * The database and required `messages` table are automatically created by `chat.php` if they do not already exist.
   * The chatbox keeps only the newest 50 messages. Older messages are automatically deleted.
   * Messages are stored as plain text in the database. HTML escaping is performed when messages are sent to the browser.

4. **Input Validation & Security:**

   * User input is trimmed and limited to 256 characters.
   * Prepared SQLite statements are used when inserting messages.
   * Certain keywords, including `php`, `javascript`, and `script`, are blocked.
   * Keyword filtering is performed server-side because client-side JavaScript validation can be bypassed.
   * Usernames and messages are escaped with `htmlspecialchars()` when displayed in the browser to prevent submitted HTML from being interpreted as page content.

5. **Dependencies:**

   * PHP is required for the application to function.
   * PHP must have the SQLite3 extension enabled.
   * No external database server is required.
   * No additional JavaScript libraries or frameworks are required.

6. **Project Structure:**

   ```text
   Chatbox-Website/
   ├── index.html
   ├── chat.php
   └── chat.db
   ```

   * `index.html` contains the chatbox interface, styling, and JavaScript responsible for loading and submitting messages.
   * `chat.php` handles database operations, input validation, message submission, and message retrieval.
   * `chat.db` is the SQLite database automatically created by the application.

7. **Message Updates:**

   * The browser periodically requests the current messages from `chat.php`.
   * New messages are submitted to the server using HTTP POST requests.
   * Messages are retrieved using HTTP GET requests.
   * The chatbox automatically scrolls to the newest messages.

8. **License:**

   * This project is released under the MIT License.
   * See `LICENSE` for the full license text.

---

**Created by Beanz / Nathaniel**

A small, simple chatbox application built with PHP and SQLite.
