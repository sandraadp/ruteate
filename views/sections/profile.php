<?php $this->layout('layouts/layout'); ?>
<?php 
    $data = array();
	$data['id_user'] = $_SESSION['id_user'];
?>

<script>
   let user = <?php echo json_encode($data);?>
</script>

<div class="leaflet-sidebar collapsed" id="sidebar">
	<div class="leaflet-sidebar-tabs">
		<ul role="tablist">
			<li>
				<a href="#info" role="tab" title="Información"><i class="fas fa-info"></i></a>
			</li>
		</ul>
	</div>
	<div class="leaflet-sidebar-content">
		<div class="leaflet-sidebar-pane" id="info">
			<h3 class="leaflet-sidebar-header text-center">Información<span class="leaflet-sidebar-close"><i class="fa fa-caret-left"></i></span></h3><br>
			<h4 class="text-center leaflet-sidebar-pane-title"></h4>
			<hr>
			<div class="lorem text-justify" id="info-text"></div>
		</div>
        
	</div>
</div>
<div class="sidebar-map" id="map"></div>