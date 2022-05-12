<?php 
    $this->layout('layouts/layout');
    $action = (isset($errors) && !empty($errors))? '/register': '/profile';

?>

<div class="container">
    <h1 class="text-center mt-2">Registro</h1>
    <div class="row justify-content-center">
    <?php if(isset($errors['final']) && !empty($errors['final'])): ?>
        <span>
            <strong><?php echo $errors['final']; ?></strong>
        </span> 
    <?php endif; ?>
        <form class="p-4 col-6  aling-items-center m-2" method="POST" action="/register">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" autofocus required>
            </div>
            <?php if(isset($errors['email']) && !empty($errors['email'])):?>
                <span>
                    <strong><?php echo $errors['email'] ?></strong>
                </span>               
            <?php endif; ?>
            <?php if(isset($errors['duplicated']) && !empty($errors['duplicated'])):?>
                <span>
                    <strong><?php echo $errors['duplicated'] ?></strong>
                </span>               
            <?php endif; ?>
            
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
            <div class="mb-3">
                <label for="password1" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password1" name="password1" required>
            </div>
            <?php if(isset($errors['way']) && !empty($errors['way'])):?>
                <span>
                    <strong><?php echo $errors['way'] ?></strong>
                </span>               
            <?php endif; ?>
            <div class="mb-3">
                <label for="password2" class="form-label">Repita la contraseña</label>
                <input type="password" class="form-control" id="password2" name="password2" required>
            </div>
            <?php if(isset($errors['same']) && !empty($errors['same'])): ?>
                <span>
                    <strong><?php echo $errors['same']; ?></strong>
                </span>               
            <?php endif; ?>
            <div class="mb-3 form-check">
               <p><input type="checkbox" class="form-check-input" name="privacy" required>Acepto la <a href="/privacyPolicy">Política de Privacidad</a></p>
                
            </div>
            <button type="submit" class="btn mx-auto d-block px-5 btn-form">Registrar</button>
        </form>
    </div>
</div>