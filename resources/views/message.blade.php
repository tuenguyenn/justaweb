<div id="chat-box">
    <div id="messages"></div>
    <input type="text" id="message" placeholder="Nhập tin nhắn...">
    <button onclick="sendMessage()">Gửi</button>
</div>

@vite( 'resources/js/app.js')
<script>
   function sendMessage() {
    let message = document.getElementById('message').value;
    let receiver_id = 2;  // ID của người nhận (có thể là nhân viên hoặc khách hàng)

    axios.post('/send-message', {
        message: message,
        receiver_id: receiver_id
    })
    .then(response => {
        console.log(response.data);
    })
    .catch(error => {
        console.error(error);
    });
}
let userId = {{ Auth::id() }};  // Hoặc cách khác để lấy userId từ PHP (Blade)

// Khi nhận được sự kiện mới
window.Echo.private('chat.' + receiverId)
    .listen('ChatMessageSent', (event) => {
        let messageHTML = `
            <div class="message">
                <strong>${event.message.sender_id === userId ? 'You' : 'User ' + event.message.sender_id}:</strong>
                <p>${event.message.message}</p>
            </div>
        `;
        document.getElementById('chatBody').innerHTML += messageHTML;
    });



</script>