<?php $this->layout('layouts/layout', [
     'mainTitle' => 'Acceder',
]) ?>


<div class="container">
    <h1 class="text-center mt-5">Inicia sesión</h1>
    <div class="row justify-content-center" >
        <div class="col-12 aling-items-center col-lg-6" >
            <p class="text-center">¿Aún no te has registrado en Ruteate? <a href="/register">Regístrate aquí</a></p>   
            <form class="p-4   m-5" method="POST" action="<?php (isset($error) && !empty($error)) ? '/access' : '/profile'; ?> ">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" autofocus required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control mw-100" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" >
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <?php if(isset($error) && !empty($error)): ?>            
                    <p class="">
                        <strong><?php echo $error; ?></strong>
                    </p>
                <?php endif; ?>
                <button type="submit" class="btn mx-auto border d-block px-5 btn-form" id="btn-access">Entrar</button>
                <div class="mt-3">
                    <p class="text-center"><a href="/resetPassword">¿Has olvidado tu contraseña?</a></p>
                </div>
            </form>
        </div>
    </div>
</div>