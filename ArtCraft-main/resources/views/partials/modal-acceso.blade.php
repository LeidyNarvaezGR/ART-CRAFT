{{-- Ventana pequeña de acceso. Se abre al comprar o al elegir "Iniciar sesión". --}}
<div class="modal-acceso" id="modal-acceso" hidden aria-hidden="true">
    <div class="modal-acceso__fondo" data-cerrar-acceso></div>

    <div class="modal-acceso__caja" role="dialog" aria-modal="true" aria-labelledby="modal-acceso-titulo">
        <button type="button" class="modal-acceso__cerrar" data-cerrar-acceso aria-label="Cerrar">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="modal-acceso__marca">
            <img src="{{ asset('imagenes/logo-icono.png') }}?v=4" alt="">
            <div>
                <h2 id="modal-acceso-titulo">Inicia sesión o crea tu cuenta</h2>
                <p>Accede para comprar y guardar tus favoritos.</p>
            </div>
        </div>

        <div class="modal-acceso__rol" role="group" aria-label="Tipo de cuenta">
            <button type="button" class="activo" data-rol="cliente" aria-pressed="true">
                <i class="fa-solid fa-bag-shopping"></i> Soy cliente
            </button>
            <button type="button" data-rol="artesano" aria-pressed="false">
                <i class="fa-solid fa-palette"></i> Soy artesano
            </button>
        </div>

        <div class="modal-acceso__tabs" role="tablist">
            <button type="button" class="activo" data-pestana="login" role="tab" aria-selected="true">
                Iniciar sesión
            </button>
            <button type="button" data-pestana="registro" role="tab" aria-selected="false">
                Crear cuenta
            </button>
        </div>

        <div class="modal-acceso__panel" data-panel="login">
            <form action="{{ route('login') }}" method="POST" data-form-acceso="login" data-turbo="false">
                @csrf
                <input type="hidden" name="tipo" value="cliente" data-tipo-acceso>

                <label for="modal-usuario">Usuario o correo electrónico</label>
                <input id="modal-usuario" type="text" name="usuario" autocomplete="username" required>

                <label for="modal-password">Contraseña</label>
                <input id="modal-password" type="password" name="password" autocomplete="current-password" required>

                <p class="modal-acceso__error" data-error role="alert"></p>

                <button type="submit" class="modal-acceso__enviar">
                    <i class="fa-solid fa-right-to-bracket"></i> Entrar y continuar
                </button>
            </form>
        </div>

        <div class="modal-acceso__panel" data-panel="registro" hidden>
            <form action="{{ route('registrar') }}" method="POST" data-form-acceso="registro" data-turbo="false">
                @csrf
                <input type="hidden" name="tipo" value="cliente" data-tipo-acceso>

                <label for="modal-nombre">Nombre</label>
                <input id="modal-nombre" type="text" name="nombre" autocomplete="name" required>

                <label for="modal-email">Correo electrónico</label>
                <input id="modal-email" type="email" name="email" autocomplete="email" required>

                <label for="modal-clave">Contraseña</label>
                <input id="modal-clave" type="password" name="password" autocomplete="new-password" minlength="8" required>

                <p class="modal-acceso__error" data-error role="alert"></p>

                <button type="submit" class="modal-acceso__enviar">
                    <i class="fa-solid fa-user-plus"></i> Crear cuenta y continuar
                </button>
            </form>
        </div>

        <p class="modal-acceso__nota">Demostración: las credenciales no se validan con un servidor real.</p>
    </div>
</div>
