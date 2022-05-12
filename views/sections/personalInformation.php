<?php 
    session_start();
    $this->layout('layouts/layout');
    $name = isset($data)? $data['name'] : "";
    $email = isset($data)? $data['email'] : "";
    $userName = isset($data)? $data['username'] :"";
    $about_user = isset($data)? $data['about_user']: "";
    $interests = isset($data)? $data['interests']: "";
?>

<div class="border-bottom">
    <a href="/userConfiguration" class="d-block py-2 pl-4 back"><i class="fas fa-chevron-left"></i> MENÚ</a>
</div>
<div class="container">
    <h3 class="text-center my-3">INFORMACIÓN PERSONAL</h3>
    <div class="personal-information mt-5">
        <div class="user-information row justify-content-center mt-2">
            <form action="/personalInformation/<?php echo $_SESSION['id_user'];?>" method="POST" class="col-6  aling-items-center justify-content-center" id="form2">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name?>">
                </div>
                <div class="mb-3">
                    <label for="email_user" class="form-label">Email</label>
                    <div id="email_user"><?php echo $email;?></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <br>
                    <a class="secondary-link" href="/changePass">Cambiar Contraseña</a>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $userName;?>">
                </div>
                <div class="mb-3">
                    <label for="about_user" class="form-label">Sobre mi</label>
                    <textarea name="about_user" id="about_user" cols="30" rows="10" class="form-control"><?php echo $about_user;?></textarea>
                </div>
                <div class="mb-3">
                    <label for="interests" class="form-label">Temas que te interesan</label>
                    <input type="text" class="form-control" id="interests" name="interests" value="<?php echo $interests;?>">
                </div>
                <input type="hidden" name="email" id="email" value="<?php echo $email;?>">
                <input type="hidden" name="form2" value="form2">
                <button type="submit" class="btn mx-auto border d-block px-4 my-4 btn-form" id="saveInformation">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>