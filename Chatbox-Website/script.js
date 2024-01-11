function displayChatMessages(messages) {
  const chatMessagesElement = document.getElementById('chatMessages');
  chatMessagesElement.innerHTML = messages.map((message) => `<p>${message}</p>`).join('');
  scrollToBottom();
}

function fetchChatMessages() {
  fetch('chat.php')
    .then((response) => response.text())
    .then((data) => {
      const messages = data.split('\n').filter((message) => message.trim() !== '');
      displayChatMessages(messages);
    })
    .catch((error) => {
      console.error('Error fetching chat messages:', error);
    });
}

// Poll for new messages every 30 seconds
setInterval(fetchChatMessages, 30000); // 30,000 milliseconds = 30 seconds

function addChatMessage(message) {
  const chatMessagesElement = document.getElementById('chatMessages');
  const newMessageElement = document.createElement('p');
  newMessageElement.textContent = message;
  chatMessagesElement.appendChild(newMessageElement);
  scrollToBottom();
}

fetchChatMessages();

document.getElementById('chatForm').addEventListener('submit', async (event) => {
  event.preventDefault();
  const name = document.getElementById('name').value.trim();
  let message = document.getElementById('message').value.trim().toLowerCase();

  if (name === '' || message === '') {
    // Display a pop-up message for empty fields
    alert('Nickname and message are required.');
    return;
  }

  // Check for blocked keywords
  const blockedKeywords = ['js', 'javascript', 'php'];
  let blocked = false;
  blockedKeywords.forEach((keyword) => {
    if (message.includes(keyword)) {
      // Display a pop-up message for blocked keywords
      alert('Blocked keyword detected. Message not sent.');
      blocked = true;
    }
  });

  if (!blocked) {
    // Send the message using AJAX
    try {
      await fetch('chat.php', {
        method: 'POST',
        body: new URLSearchParams({ name: name, message: message }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      });
      fetchChatMessages();
    } catch (error) {
      console.error('Error sending chat message:', error);
    }
  }

  // Clear the message input after submission
  event.target.reset();
});

function scrollToBottom() {
  const chatbox = document.getElementById('chatbox');
  chatbox.scrollTop = chatbox.scrollHeight;
}
