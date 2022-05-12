<?php $this->layout('layouts/layout', [
     'mainTitle' => 'Reset',
]); 

?>

<div class="container">
    <h1 class="text-center my-5">Recuperar Contraseña</h1>
    <div class="row justify-content-center mb-5 pb-5" >
        <div class="mt-3 col-6 text-center">
        <?php if(isset($error) && !empty($error)):?>
                <p class="my-2"><strong><?php echo $error?></strong></p>  
        <?php endif; ?>
        <?php if(isset($success) && !empty($success)):?>
                <p>
                <?php echo $success?>
                </p>  
                <?php header('refresh:2; url= /access'); 
                die();
                ?>
        <?php endif; ?>
        </div>
    
        <form class="p-4 col-6  aling-items-center m-5" method="POST" action="/password-reset" id="form-reset-pass" name="form-reset-pass">
            <div class="mb-4">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" autofocus required>
            </div>
            <button type="submit" class="btn mx-auto border d-block  py-2 btn-form mb-5" id="password-reset">Enviar email de recuperación</button>
        </form>
    </div>
</div>



