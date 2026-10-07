# Simple Chatbox Application

1. **Overview:**

   * A straightforward chatbox/shoutbox-like application developed using HTML, CSS, JavaScript, PHP, and SQLite.
   * The application provides a simple real-time-style chat interface without requiring a large web framework or database server.

2. **Installation:**

   * Copy the project files to your web server.
   * Set the `Chatbox-Website` folder as the web server's document root.
   * Keep the `Chatbox-Website` and `data` directories together under the same parent directory.
   * Do not move the `Chatbox-Website` directory out of the project structure, as `chat.php` uses the parent directory to locate the `data` directory.
   * The outer project directory can be renamed or moved as long as the `Chatbox-Website` and `data` directories remain together under the same parent directory.
   * Make sure PHP is installed and the SQLite3 extension is enabled.
   * The `data` directory will automatically be created outside the web root when the application is first run.
   * The `chat.db` database file will automatically be created inside the `data` directory.
   * No manual database or directory creation is required.
   * Voila! Your chatbox is ready to roll!

3. **Message Storage:**

   * Messages are stored in an SQLite database named `chat.db`.
   * The database is stored in the `data` directory outside the web server's document root.
   * SQLite is embedded directly into the application and does not require a separate database server such as MySQL or MariaDB.
   * The `data` directory and required `messages` table are automatically created by `chat.php` if they do not already exist.
   * The chatbox keeps only the newest 50 messages. Older messages are automatically deleted.
   * Messages are stored as plain text in the database. HTML escaping is performed when messages are sent to the browser.

4. **Input Validation & Security:**

   * User input is trimmed and limited to 256 characters.
   * Prepared SQLite statements are used when inserting messages.
   * Certain keywords, including `php`, `javascript`, and `script`, are blocked.
   * Keyword filtering is performed server-side because client-side JavaScript validation can be bypassed.
   * Usernames and messages are escaped with `htmlspecialchars()` when displayed in the browser to prevent submitted HTML from being interpreted as page content.
   * The SQLite database is stored outside the web server's document root, preventing direct HTTP requests from accessing the database file.

5. **Dependencies:**

   * PHP is required for the application to function.
   * PHP must have the SQLite3 extension enabled.
   * No external database server is required.
   * No additional JavaScript libraries or frameworks are required.

6. **Project Structure:**

   ```text
   old-school-chatbox-1.03/
   ├── Chatbox-Website/
   │   ├── index.html
   │   └── chat.php
   │
   └── data/
       └── chat.db
   ```

   * `Chatbox-Website/` contains the files served by the web server.
   * `index.html` contains the chatbox interface, styling, and JavaScript responsible for loading and submitting messages.
   * `chat.php` handles database operations, input validation, message submission, and message retrieval.
   * `data/` stores application data outside the web server's document root.
   * `chat.db` is the SQLite database automatically created by the application.
   * `Chatbox-Website/` and `data/` must remain together under the same parent directory because `chat.php` locates `data/` relative to its own directory.
   * The outer project directory can be renamed without affecting the application.

7. **Message Updates:**

   * The browser periodically requests the current messages from `chat.php`.
   * New messages are submitted to the server using HTTP POST requests.
   * Messages are retrieved using HTTP GET requests.
   * The chatbox automatically scrolls to the newest messages.

8. **License:**

   * This project is released under the MIT License.
   * See [`LICENSE`](https://github.com/nxs8739/old-school-chatbox/blob/main/LICENSE.txt) for the full license text.

