<?php 
    session_start(); 
    $this->layout('layouts/layout'); 
?>
<div class="container">
    <div class="row justify-content-between p-3 my-5">
        <h3 class="text center mt-3">Cambiar contraseña</h3>
        <div>
            <p><a href="/personalInformation/<?php echo $_SESSION['id_user'];?>" title="cerrar"><i class="fas fa-times"></i></a></p>
        </div>
    </div>
    <hr>
     <?php if(isset($message)):?>
            <p class="text-center d-block">
                <strong><?php echo $message; ?></strong>
            </p>
        
        <?php endif; ?>
    <div class="row justify-content-center py-5">
    
       
        <p class="text-center col-6">Para cambiar la contraseña debe rellenar los dos campos siguientes</p>
        <form class="px-4 col-6  aling-items-center mx-3 my-5 py-5" method="POST" action="/changePass/<?php echo $_SESSION['id_user'];?>">
            <div class="my-3">
                <label for="oldPassword" class="form-label">Antigua contraseña</label>
                <input type="password" class="form-control" id="oldPassword" name="oldPassword" autofocus required>
            </div>
            <div class="my-3">
                <label for="newPassword" class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
            <button type="submit" class="btn mx-auto border d-block px-5 btn-form my-5">Guardar</button>
        </form>
    </div>
</div>