<?php 
    session_start();
    $this->layout('layouts/layout');
?>

<div class="container">
    <div class="configuration-options my-5">
        <div class="user-data my-5">
            <img src="/assets/images/imageonline-co-transparentimage.png" alt="imagen usuario" id="img-avatar">
            <h2 class="text-center py-4"><?php echo $_SESSION['name'] . ' ' . $_SESSION['surname'];?></h2>
        </div>
        <div class="favourites-spaces text-center py-2">
            <a href="/showFavourites/2/<?php echo $_SESSION['id_user']; ?>" role="button" class="d-block py-2">ESPACIOS NATURALES FAVORITOS</a>
        </div>
        <div class="favourites-routes text-center py-2">
            <a href="/showFavourites/3/<?php echo $_SESSION['id_user']; ?>" role="button" class="d-block py-2"> SENDAS FAVORITAS</a>
        </div>
        <div class="favourites-monuments text-center py-2">
            <a href="/showFavourites/5/<?php echo $_SESSION['id_user']; ?>" role="button" class="d-block py-2">MONUMENTOS FAVORITOS</a>
        </div>
        <div class="favourites-lookouts text-center py-2">
            <a href="/showFavourites/4/<?php echo $_SESSION['id_user']; ?>" role="button" class="d-block py-2">MIRADORES FAVORITOS</a>
        </div>
        <div class="favourites-hotels text-center py-2">
            <a href="/showFavourites/1/<?php echo $_SESSION['id_user']; ?>" role="button" class="d-block py-2">ALOJAMIENTOS FAVORITOS</a>
        </div>
        <div class="personal-information text-center py-2">
            <a class="d-block py-2" href="/personalInformation/<?php echo $_SESSION['id_user'];?>" role="button">INFORMACIÓN PERSONAL</a>
        </div>
        <div class="privacy-options text-center mb-4 py-2">
            <a href="/privacyOptions/<?php echo $_SESSION['id_user']; ?>" role="button" class="d-block py-2">OPCIONES DE PRIVACIDAD</a>
        </div>
    </div>
</div>