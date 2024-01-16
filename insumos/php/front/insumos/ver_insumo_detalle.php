<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){
    $id_insumo=null;
    $bodega;
    if ( !empty($_GET['id_insumo'])) {
      $id_insumo = $_REQUEST['id_insumo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{
      include_once '../../back/functions.php';
      $clase= new insumo();

      $datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
      foreach($datos AS $d){
        $bodega = $d['id_bodega_insumo'];
      }
    }
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="insumos/js/carjar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script type="text/javascript">
  get_data();



</script>

</head>
<body>

  <div class="modal-header">
    <h4 class="modal-title">Detalle del Insumo</h4>
    <ul class="list-inline ml-auto mb-0">

      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>

  </div>
  <input id="id_insumo" hidden value="<?php echo $id_insumo;?>"></input>
  <div class="modal-body">
    <div id="celda">

    </div>
    <div class="row">
      <!--<aside class="col-sm-1 border-right">
        <article class="gallery-wrap">
        <div class="img-big-wrap">
        <div></div>
        </div> <!-- slider-product.// -->
        <!--<div class="img-small-wrap">

        </div> <!-- slider-nav.// -->
        <!--</article> <!-- gallery-wrap .end// -->
      <!--</aside>-->
      <aside class="col-sm-12">
        <article class=" ">
          <!--<h3 class="title mb-3">Original Version of Some product name</h3>-->

          <p class="price-detail-wrap">
          <span class="price h3 ">
          <span class="currency">SICOIN: </span><span class="">
            <a data-name="<?php echo $id_insumo?>" class="sicoin" data-type="text" data-pk="<?php echo $id_insumo?>" data-title="Actualizar Sicoin"><i class="text-warning num data_"></i></a>
          </span>
          </span>
          <span></span>
          </p> <!-- price-detail-wrap .// -->
          <dl class="item-property">
          <!--<dt>Descripción</dt>
          <dd><p>Here goes description consectetur adipisicing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco </p></dd>
        </dl>-->
          <dl class="param param-feature">
          <dt>Tipo</dt>
          <dd class="tipo data_"></dd>
          </dl>  <!-- item-property-hor .// -->
          <dl class="param param-feature">
          <dt>Marca</dt>
          <dd class="marca data_"></dd>
          </dl>  <!-- item-property-hor .// -->
          <dl class="param param-feature">
          <dt>Modelo</dt>
          <dd class="modelo data_"></dd>
          </dl>  <!-- item-property-hor .// -->
          <dl class="param param-feature">
          <dt>Serie</dt>
          <dd class="serie data_"></dd>
          </dl>  <!-- item-property-hor .// -->
          <dl class="param param-feature">
          <dt>Existencias</dt>
          <dd class="">
            <a data-name="<?php echo $id_insumo?>" class="exis " data-type="text" data-pk="<?php echo $id_insumo?>" data-title="Actualizar Existencia"><i class="existencia data_"></i></a>

          </dd>

          </dl>  <!-- item-property-hor .// -->

          <hr>
          <div class="row">
            <div class="col-sm-5">
              <dl class="param param-inline">
                <dt>Modelo: </dt>
                <dd>
                  <select class="form-control form-control-sm" style="width:70px;">
                    <option> 1 </option>
                    <option> 2 </option>
                    <option> 3 </option>
                  </select>
                </dd>
              </dl>  <!-- item-property .// -->
            </div> <!-- col.// -->
          </div>

          <div id='myapp'>

			<!-- Select All records -->
			<input type='button' @click='allRecords()' value='Select All users'>
			<br><br>

			<!-- Select record by ID -->
			<input type='text' v-model='cliente_id' placeholder="Enter Userid between 1 - 24">
			<input type='button' @click='recordByID()' value='Select user by ID'>
			<br><br>

			<!-- List records -->
			<table border='1' width='80%' style='border-collapse: collapse;'>
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Email</th>
				</tr>

				<tr v-for='user in users'>
					<td>{{ user.cliente_nit }}</td>
					<td>{{ user.name }}</td>
					<td>{{ user.email }}</td>
				</tr>
			</table>

		</div>

		<!-- Script -->
		<script>
			var app = new Vue({
				el: '#myapp',
				data: {
					users: "",
					cliente_id: 0
				},
				methods: {
					allRecords: function(){

						axios.get('ajaxfile.php')
						.then(function (response) {
						    app.users = response.data;
						})
						.catch(function (error) {
						    console.log(error);
						});
					},
					recordByID: function(){
						if(this.cliente_id > 0){

							axios.get('ajaxfile.php', {
							    params: {
							      	cliente_id: this.cliente_id
							    }
							})
						  	.then(function (response) {
						    	app.users = response.data;
						  	})
						  	.catch(function (error) {
						    	console.log(error);
						  	});
						}

					}
				}
			})

		</script>


  </div>
  <?php if($bodega!=5066){?>
  <script>



  ////
  $('.exis').editable({

    url: 'insumos/php/back/funciones/update_existencia_by_id.php',
    type: 'POST',
    title: 'Actualizar información',
    validate: function(value){
      if($.trim(value) == '')
      {
        Swal.fire({
          type: 'error',
          title: 'El valor no puede ser vacío',
          showConfirmButton: false,
          timer: 1100
        });
      }
    }
  });

  $('.sicoin').editable({

    url: 'insumos/php/back/funciones/update_sicoin_by_id.php',
    type: 'POST',
    title: 'Actualizar información',
    validate: function(value){
      if($.trim(value) == '')
      {
        Swal.fire({
          type: 'error',
          title: 'El valor no puede ser vacío',
          showConfirmButton: false,
          timer: 1100
        });
      }
    }
  });


  </script>
  <?php }
 }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
