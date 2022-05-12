<?php $this->layout('layouts/layout', [
     'mainTitle' => 'Reset',
]);
?>

<div class="container">
    <h1 class="text-center mt-2">Reset Password</h1>
    <div class="row justify-content-center" >
        <form class="p-4 col-6  aling-items-center m-1" method="POST" action="/visor_access">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" autofocus required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control mw-1000" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control mw-1000" id="password2" name="password2" required>
            </div>
            <button type="submit" class="btn mx-auto border d-block px-5">Restablecer contraseña</button>
        </form>
    </div>
    
</div>