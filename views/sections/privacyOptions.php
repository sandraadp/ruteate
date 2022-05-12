<?php 
    session_start();
    $this->layout('layouts/layout');
?>
<div class="border-bottom">
    <a href="/userConfiguration" class="d-block py-2 pl-4 back"><i class="fas fa-chevron-left"></i> MENÚ</a>
</div>
    <div class="container">
        <h3 class="text-center mt-5">OPCIONES DE PRIVACIDAD</h3>
        <hr>
    
        <div class="modal fade" tabindex="-1" id="modalDeleteUser" role="dialog" arialabeledby="titleModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titleModal">Borrar cuenta</h5>
                        <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro de querer borrar la cuenta actual y todos los datos asociados? </p>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">Cancelar</button>
                        <form action="/deleteUser" method="POST">
                            <input type="hidden" name="user" value="<?php echo $_SESSION['id_user'];?>" id="user">
                            <button type="submit" class="btn btn-danger" id="deleteUser">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
        <div class="row  justify-content-center my-5">
            <div class=" align-items-center">
                <div class="my-5 tex-center p-2">
                    <p class="paragraph d-block text-center">Puedes obtener más información sobre cómo tratamos tus datos personales en las páginas de <a href="/privacyPolicy" target="_blank" class="secondary-link">Política de privacidad</a> y <a href="/terms&conditions" target="_blank" class="secondary-link">Términos y condiciones</a></p>
                </div>
                <div class="mx-3 my-5 border form-delete-user p-5">
                    <h5 class="text-center py-3 secondary-title">BORRAR CUENTA DE USUARIO</h5>
                    <P class="p-4">Si lo deseas, en cualquier momento puedes borrar tu cuenta de usuario. De ese modo eliminaremos todos tus datos personales almacenados en el sitio de Ruteate.</P>
                    <button type="button" class="btn mx-auto d-block px-5 btn-add-delete my-3"data-toggle="modal" data-target="#modalDeleteUser">Dar de baja la cuenta</button>
                </div>
            </div>
        </div>
    </div>
</div>