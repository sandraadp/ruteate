<?php 
    session_start();
    $this->layout('layouts/layout'); 
?>
<div class="container">
    <div class="border-bottom">
        <a href="/userConfiguration" class="d-block py-2 mb-3 pl-4 back"><i class="fas fa-chevron-left"></i> MENÚ</a>
    </div>
    <?php if(isset($result) && !empty($result)): ?>
        <div class="py-5 my-5">
            <h1 class="my-5 py-5 text-center"><?php echo $result['message']; ?></h1>
        </div> 
        <?php endif; ?>
        <?php if(isset($data) && !empty($data)): ?>
        <?php 
            $type;
            switch($data[0]['id_type_element']){
                case 1: 
                    $type = "Alojamientos";
                    break;
                case 2:
                    $type = "Espacios naturales";
                    break;
                case 3:
                    $type = "Sendas";
                    $favourite = "favoritas";
                    break;
                case 4:
                    $type = "Miradores";
                    break;
                case 5:
                    $type = "Monumentos";
                    break;
            }
        ?>
        <?php (isset($favourite) && !empty($favourite))? $favourite: $favourite = "favoritos"; ?>
        <h1 class="text-center mt-3 mb-4"><?php echo $type . " " . $favourite; ?></h1>
        
        <?php foreach($data as $result => $value): ?>
            <div id="element<?php echo $data[$result]['id_favourite']; ?>">
                <h4><?php echo $data[$result]['name']; ?></h4>
                <?php if($data[$result]['web'] != '' || $data[$result]['web'] != null):?>
                <a href="<?php echo $data[$result]['web']; ?>" target="_blank"><?php echo $data[$result]['web'];?></a>
                <?php endif; ?>
                <form action="/showFavourites" method="POST" class="form-delete-favourite">
                    <input type="hidden" name="favourite" value="<?php echo $data[$result]['id_favourite']; ?>">
                    <button type="submit" class="btn mx-auto border d-block px-5 mt-2 btn-form btn-delete-favourite">Eliminar</button>
                </form>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</div>