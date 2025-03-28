// FUNCIÓN CHATBOT FLOTANTE CON CONEXIÓN A BACKEND EN RENDER
function agregar_chatbot() {
    ?>
    <link rel="stylesheet" type="text/css" href="style.css">

    <div id="chat-widget">
        <div id="chat-header" onclick="toggleChat()">💡 Chatbot</div>
        <div id="chat-box">
            <!-- Botones de ayuda -->
             <div id="chat-buttons-container">
                <button type="button" class="help-button" onclick="sendMessage('Sobre Nexeus')">Sobre Nexeus</button>
                <button type="button" class="help-button" onclick="sendMessage('Catalogo de productos')">Catálogo de productos</button>
            </div>
            <div id="chat-content"></div>
            <!-- Contenedor del input y botón de enviar -->
            <div id="chat-input-container">
                <input type="text" id="user-input" placeholder="Escribe aquí..." />
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>
    </div>

    <script>
        let step = 0;
        const BACKEND_URL = "https://backend-chatbot-public-1-5pjk.onrender.com";

        function toggleChat() {
            let chatBox = document.getElementById("chat-box");
            chatBox.style.display = (chatBox.style.display === "none" || chatBox.style.display === "") ? "block" : "none";
        }

        setTimeout(() => {
            let chatHeader = document.getElementById("chat-header");
            chatHeader.innerHTML = "💡 ¿Sabes cuánto podrías ahorrar con IA? Descúbrelo en segundos";
        }, 5000);

        async function sendMessage(message = null) {
            let input = document.getElementById("user-input");
            let chatContent = document.getElementById("chat-content");

            // Si el mensaje es null, tomar el valor del input
            let finalMessage = message ? message : input.value.trim();
            if (finalMessage === "") return; // No enviar mensajes vacíos

            input.value = ""; // Limpiar input solo si el mensaje vino de ahí

            // Crear y añadir mensaje del usuario
            let userMessage = document.createElement("p");
            userMessage.innerHTML = `<b>Tú:</b> ` + finalMessage;
            chatContent.appendChild(userMessage);

            chatContent.scrollTop = chatContent.scrollHeight;

            try {
                let response = await fetch(`${BACKEND_URL}/chat`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ step: step, message: finalMessage })
                });

                let data = await response.json();

                let botMessage = document.createElement("p");
                botMessage.innerHTML = `<b>Chatbot:</b> ` + (data.response);
                chatContent.appendChild(botMessage);

                if (data.step === -1) {
                    let downloadLink = document.createElement("a");
                    downloadLink.href = `${BACKEND_URL}/generate_pdf`;
                    downloadLink.target = "_blank";
                    downloadLink.innerText = "Descargar presupuesto";
                    chatContent.appendChild(downloadLink);
                }

                chatContent.scrollTop = chatContent.scrollHeight;
                step = data.step;
            } catch (error) {
                console.error("Error al conectar con el backend:", error);
                chatContent.innerHTML += `<p><b>Chatbot:</b> Error en la comunicación con el servidor.</p>`;
            }
        }
        /* Funcionalidad enter */
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
}