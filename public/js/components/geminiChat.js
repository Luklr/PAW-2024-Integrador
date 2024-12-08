class GeminiChat {
    constructor() {
        this.init();
    }

    init() {
        var css = tools.nuevoElemento("link","",{rel: "stylesheet",href:"/js/components/styles/chat.css"})
            document.head.appendChild(css);
        
        // Seleccionar contenedor y botón
        const chatContainer = document.querySelector(".gemini-chat-container");
        const toggleButton = document.querySelector(".toggle-chat");

        // Alternar expansión del chat
        toggleButton.addEventListener("click", () => {
            chatContainer.classList.toggle("expanded");
        });

        // Manejar selección de plantilla
        document.querySelectorAll('input[name="template1"], input[name="template2"]').forEach(input => {
            input.addEventListener('click', (e) => this.handleTemplateSelection(e));
        });

        // Botón de envío
        const sendButton = document.getElementById('send-chat-message');
        if (sendButton) {
            sendButton.addEventListener('click', (event) => {
                event.preventDefault();
                this.sendMessage();
            });
        }
    }

    handleTemplateSelection(event) {
        const template1 = document.getElementById('chat-template-1');
        const template2 = document.getElementById('chat-template-2');
        const sendButton = document.getElementById('send-chat-message');

        sendButton.classList.remove('hidden');
        // Mostrar u ocultar plantillas basadas en la selección
        if (event.target.value === "1") {
            template1.classList.remove('hidden');
            template2.classList.remove('hidden');
        } else {
            template1.classList.add('hidden');
            template2.classList.remove('hidden');
        }
    }

    sendMessage() {
        const selectedTemplate = document.querySelector('input[name="template1"]:checked, input[name="template2"]:checked');
        if (!selectedTemplate) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Seleccione una plantilla para la consulta",
                customClass: {
                    title: "swal-title",
                    content: "swal-content",
                    confirmButton: "swal-confirm-button",
                    cancelButton: "swal-cancel-button"
                }
            });
            return;
        }
    
        const typeMsg = parseInt(selectedTemplate.value, 10);
        let messageParts = [];
    
        if (typeMsg === 1) {
            const component = document.querySelector('input[name="component_type"]:checked');
            const pcType = document.querySelector('input[name="pc_type"]:checked');
            if (!component || !pcType) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Seleccione el tipo de PC y el tipo de componente",
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "swal-confirm-button",
                        cancelButton: "swal-cancel-button"
                    }
                });
                return;
            }
            messageParts = [component.value, pcType.value];
        } else if (typeMsg === 2) {
            const pcType = document.querySelector('input[name="pc_type"]:checked');
            if (!pcType) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Seleccione el tipo de PC",
                    customClass: {
                        title: "swal-title",
                        content: "swal-content",
                        confirmButton: "swal-confirm-button",
                        cancelButton: "swal-cancel-button"
                    }
                });
                return;
            }
            messageParts = [pcType.value];
        }
    
        // Enviar datos al servidor
        fetch('/send_message_gemini', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type_msg: typeMsg,
                message: messageParts
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    this.addChatMessage(data.gemini_text, 'gemini');
                }
            })
            .catch(error => {
                console.error(error);
            });
    }

    addChatMessage(text, sender) {
        const chatContainer = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message', sender); // Clase 'chat-message gemini' o 'chat-message user'
        messageDiv.textContent = text;

        // Insertar mensaje al final del chat
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight; // Hacer scroll al final
    }
}

