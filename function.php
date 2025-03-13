// FUNCIÓN CHATBOT FLOTANTE CON CONEXIÓN A BACKEND EN RENDER
function agregar_chatbot() {
    ?>
    <style>
        #chat-widget {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 320px;
            font-family: Arial, sans-serif;
            z-index: 1000;
        }

        #chat-header {
            background: linear-gradient(45deg, #007bff, #00c3ff);
            color: white;
            padding: 12px;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: waveEffect 2.5s infinite alternate;
        }

        #chat-header:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
        }

        #chat-box {
            display: none;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            overflow-y: auto;
        }

        #chat-content {
            height: 250px;
            overflow-y: auto;
            padding: 5px;
        }

        input {
            width: 80%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 8px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        /* Animación "Wave" - Brillo Pulsante */
        @keyframes waveEffect {
            0% {
                box-shadow: 0 0 10px rgba(0, 195, 255, 0.5);
            }
            100% {
                box-shadow: 0 0 20px rgba(0, 195, 255, 0.9);
            }
        }
    </style>
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