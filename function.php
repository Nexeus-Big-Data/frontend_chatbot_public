<?php
// FUNCIÓN CHATBOT FLOTANTE CON CONEXIÓN A BACKEND EN RENDER
function agregar_chatbot() {
    ?>
    <link rel="stylesheet" type="text/css" href="chatbot.css">
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
        const BACKEND_URL = "https://chatbot-api.onrender.com";

        function toggleChat() {
            let chatBox = document.getElementById("chat-box");
            chatBox.style.display = (chatBox.style.display === "none" || chatBox.style.display === "") ? "block" : "none";
        }

        setTimeout(() => {
            let chatHeader = document.getElementById("chat-header");
            chatHeader.innerHTML = "💡 ¿Sabes cuánto podrías ahorrar con IA? Descúbrelo en segundos";
        }, 5000);

        async function sendMessage() {
            let input = document.getElementById("user-input");
            let message = input.value.trim();
            if (message === "") return;

            input.value = "";
            let chatContent = document.getElementById("chat-content");
            chatContent.innerHTML += `<p><b>Tú:</b> ${message}</p>`;

            try {
                let response = await fetch(new URL("/chat", BACKEND_URL), {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ step: step, answer: message })
                });

                let data = await response.json();

                if (data.step !== -1) {
                    chatContent.innerHTML += `<p><b>Chatbot:</b> ${data.question}</p>`;
                    step = data.step;
                } else {
                    chatContent.innerHTML += `<p><b>Chatbot:</b> ${data.message}</p>`;
                    chatContent.innerHTML += `<a href="${BACKEND_URL}/generate_pdf" target="_blank">Descargar presupuesto</a>`;
                }
            } catch (error) {
                console.error("Error al conectar con el backend:", error);
                chatContent.innerHTML += `<p><b>Chatbot:</b> Error en la comunicación con el servidor.</p>`;
            }
        }
    </script>
    <?php
}
?>
add_action('wp_footer', 'agregar_chatbot');