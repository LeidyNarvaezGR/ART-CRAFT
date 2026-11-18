
   <div id="artcraft-chatbot">

    <!-- BOTÓN CON ICONO DE ESTRELLAS -->
    <button
        id="chatbot-abrir"
        type="button"
        aria-label="Abrir chatbot de ART CRAFT"
    >
        <img
            src="{{ asset('imagenes/chatbot-icono.png') }}"
            alt="Icono chatbot ART CRAFT"
            class="chatbot-icono"
        >
    </button>

    <!-- VENTANA DEL CHATBOT -->
    <div id="chatbot-ventana" style="display: none;">

        <div class="chatbot-encabezado">
            <strong>Asistente ART CRAFT</strong>

            <button
                id="chatbot-cerrar"
                type="button"
                aria-label="Cerrar chatbot"
            >
                ✕
            </button>
        </div>

        <div id="chatbot-mensajes">
            <div class="chatbot-bot">
                ¡Hola! 👋 Soy el asistente virtual de
                ART CRAFT. ¿En qué puedo ayudarte?
            </div>
        </div>

        <form id="chatbot-formulario">
            <input
                type="text"
                id="chatbot-texto"
                placeholder="Escribe tu pregunta..."
                maxlength="1000"
                required
            >

            <button type="submit">
                Enviar
            </button>
        </form>

    </div>
</div>

<style>
    /* CONTENEDOR FLOTANTE */
    #artcraft-chatbot {
        position: fixed !important;
        bottom: 20px !important;
        left: 20px !important;
        right: auto !important;
        top: auto !important;
        z-index: 2147483000 !important;
        width: auto !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        font-family: Arial, sans-serif;
    }

    /* BOTÓN DE ESTRELLAS */
    #chatbot-abrir {
        width: 90px;
        height: 90px;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    #chatbot-abrir:hover {
        transform: scale(1.12);
    }

    /* IMAGEN DEL CHATBOT */
    .chatbot-icono {
        display: block;
        width: 90px;
        height: 90px;
        object-fit: contain;
    }

    /* VENTANA DEL CHATBOT */
    #chatbot-ventana {
        width: min(350px, calc(100vw - 40px));
        height: min(450px, calc(100vh - 40px));
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        flex-direction: column;
    }

    /* ENCABEZADO */
    .chatbot-encabezado {
        background: #8c1d0d;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #chatbot-cerrar {
        background: transparent;
        color: white;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    /* MENSAJES */
    #chatbot-mensajes {
        flex: 1;
        min-height: 0;
        padding: 15px;
        overflow-y: auto;
        background: #f5eee5;
    }

    .chatbot-bot,
    .chatbot-usuario {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 12px;
        max-width: 85%;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }

    .chatbot-bot {
        background: white;
        color: #333;
    }

    .chatbot-usuario {
        background: #8c1d0d;
        color: white;
        margin-left: auto;
    }

    /* FORMULARIO */
    #chatbot-formulario {
        display: flex;
        padding: 10px;
        gap: 5px;
        background: white;
    }

    #chatbot-texto {
        flex: 1;
        min-width: 0;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    #chatbot-formulario button {
        background: #8c1d0d;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const chatbot = document.getElementById('artcraft-chatbot');
    const abrir = document.getElementById('chatbot-abrir');
    const cerrar = document.getElementById('chatbot-cerrar');
    const ventana = document.getElementById('chatbot-ventana');
    const formulario = document.getElementById('chatbot-formulario');
    const entrada = document.getElementById('chatbot-texto');
    const mensajes = document.getElementById('chatbot-mensajes');

    if (!chatbot || !abrir || !cerrar || !ventana ||
        !formulario || !entrada || !mensajes) {
        return;
    }

    // MOVER EL CHATBOT DIRECTAMENTE AL BODY
    // PARA EVITAR QUE QUEDE DENTRO DEL FOOTER
    if (chatbot.parentElement !== document.body) {
        document.body.appendChild(chatbot);
    }

    // ABRIR CHATBOT
    abrir.addEventListener('click', function () {
        ventana.style.display = 'flex';
        abrir.style.display = 'none';
        entrada.focus();
    });

    // CERRAR CHATBOT
    cerrar.addEventListener('click', function () {
        ventana.style.display = 'none';
        abrir.style.display = 'flex';
    });

    // AGREGAR MENSAJES
    function agregarMensaje(texto, clase) {
        const div = document.createElement('div');
        div.className = clase;
        div.textContent = texto;

        mensajes.appendChild(div);
        mensajes.scrollTop = mensajes.scrollHeight;

        return div;
    }

    // ENVIAR MENSAJES AL BACKEND
    formulario.addEventListener('submit', async function (e) {
        e.preventDefault();

        const texto = entrada.value.trim();

        if (!texto) return;

        agregarMensaje(texto, 'chatbot-usuario');

        entrada.value = '';
        entrada.disabled = true;

        const boton = formulario.querySelector('button');
        boton.disabled = true;

        const esperando = agregarMensaje(
            'Escribiendo...',
            'chatbot-bot'
        );

        try {
            const respuesta = await fetch(
                "{{ route('chatbot.mensaje') }}",
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        mensaje: texto
                    })
                }
            );

            const datos = await respuesta.json();

            if (!respuesta.ok) {
                throw new Error(
                    datos.error || 'No se pudo obtener respuesta.'
                );
            }

            esperando.textContent =
                datos.respuesta || 'No recibí una respuesta.';

        } catch (error) {
            esperando.textContent =
                'No pude responder en este momento. Intenta nuevamente.';

            console.error('Error del chatbot:', error);

        } finally {
            entrada.disabled = false;
            boton.disabled = false;
            entrada.focus();
        }
    });
});
</script>