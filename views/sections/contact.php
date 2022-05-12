<?php $this->layout('layouts/layout');?>

<div class="container">
    <h1 class="text-center">Contacto</h1>
    <?php if(isset($error) && !empty($error)):?>
        <div class="information-messagge">
            <p class="text-center"><?php echo $error; ?></p>
        </div>
        <?php header('refresh:2; url= /'); 
            die();
        ?>
    <?php elseif(isset($success) && !empty($success)): ?>
        <div class="information-messagge">
            <p class="text-center"><?php echo $success; ?></p>
        </div>
        <?php header('refresh:2; url= /'); 
            die();
        ?>
    <?php else: ?>
        <p class="text-center">Puedes enviarnos un mensaje para cualquier consulta que necesites mediante el formulario de la derecha. Te responderemos lo más rápido que nos sea posible. Cuando te pongas en contacto con nosotros recibirás un mensaje automático que confirma que lo hemos recibido.</p>
    <?php endif; ?>
    <?php if(!isset($error) && !isset($success)): ?>
    
    <div class="row justify-content-center">
        <form action="" method="POST" >
            <div>
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" autofocus required>
            </div>
            <div>
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div>
                <label for="mesagge" class="form-label">Mensaje</label>
                <textarea name="messagge" id="messagge" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="mb-3 form-check">
                <p><input type="checkbox" class="form-check-input" name="privacy" required>Acepto la <a href="/privacyPolicy">Política de Privacidad</a></p>
            </div>
            <button type="submit" class="btn mx-auto border d-block px-5 btn-form">Enviar</button>
        </form>
    </div>
    <?php endif; ?>
</div>