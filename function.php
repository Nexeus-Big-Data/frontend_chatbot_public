// FUNCIÓN CHATBOT FLOTANTE
function agregar_chatbot() {
    ?>
    <div id="chat-widget">
        <div id="chat-header" onclick="toggleChat()">💡 Chatbot</div>
        <div id="chat-box">
            <div id="chat-content"></div>
            <input type="text" id="user-input" placeholder="Escribe aquí..." />
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </div>

    <script>
        let step = 0;

        function toggleChat() {
            let chatBox = document.getElementById("chat-box");
            chatBox.style.display = (chatBox.style.display === "none" || chatBox.style.display === "") ? "block" : "none";
        }

        // Mensaje de bienvenida tras 5 segundos
        setTimeout(() => {
            let chatHeader = document.getElementById("chat-header");
            chatHeader.innerHTML = "💡 ¿Sabes cuánto podrías ahorrar con IA? Descúbrelo en segundos";
        }, 5000);

        async function sendMessage() {
            let input = document.getElementById("user-input");
            let message = input.value;
            input.value = "";

            document.getElementById("chat-content").innerHTML += `<p><b>Tú:</b> ${message}</p>`;

            let response = await fetch("http://tu-servidor-fastapi.com/chat", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ step: step, answer: message })
            });

            let data = await response.json();
            if (data.step !== -1) {
                document.getElementById("chat-content").innerHTML += `<p><b>Chatbot:</b> ${data.question}</p>`;
                step = data.step;
            } else {
                document.getElementById("chat-content").innerHTML += `<p><b>Chatbot:</b> ${data.message}</p>`;
                document.getElementById("chat-content").innerHTML += `<a href="http://tu-servidor-fastapi.com/generate_pdf" target="_blank">Descargar presupuesto</a>`;
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            const userInput = document.getElementById("user-input")

            if (userInput){
                userInput.addEventListener("keypress", function(event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        sendMessage();
                    }
                });
            } else {
                console.error("Error: No se encontró el elemento #user-input.");
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'agregar_chatbot');
