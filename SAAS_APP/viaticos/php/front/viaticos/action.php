<div class="container" style="padding:50px 50px;">
<div class="alert alert-info" role="alert"> <strong>Resultados:</strong> Mostramos los datos enviados del formulario. </div>
<div class="row well alert alert-success"><?php echo "<pre>";print_R($_POST);?></div>
</div>


<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){
    $id_viatico=null;
    $bodega;
    if ( !empty($_GET['id_viatico'])) {
      $id_viatico = $_REQUEST['id_viatico'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{
      /*include_once '../../back/functions.php';
      $clase= new insumo();
*/
      /*$datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
      foreach($datos AS $d){
        $bodega = $d['id_bodega_insumo'];
      }*/
    }
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="insumos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="insumos/js/cargar_vue.js"></script>
  <script type="text/javascript">





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

    <div id='vueapp'>

    <table border='1' width='100%' style='border-collapse: collapse;'>
       <tr>
         <th>Name</th>
         <th>Email</th>
         <th>Country</th>
         <th>City</th>
         <th>Job</th>

       </tr>

       <tr v-for='contact in contacts'>
         <td>{{ contact.name }}</td>
         <td>{{ contact.email }}</td>
         <td>{{ contact.country }}</td>
         <td>{{ contact.city }}</td>
         <td>{{ contact.job }}</td>
       </tr>
     </table>




  </div>

  <script>
var app = new Vue({
  el: '#vueapp',
  data: {
      name: '',
      email: '',
      country: '',
      city: '',
      job: '',
      contacts: []
  },
  mounted: function () {
    console.log('Hello from Vue!')
    this.getContacts()
  },

  methods: {
    getContacts: function(){
      axios.get('viaticos/php/front/viaticos/ajaxfile.php')
        .then(function (response) {
            console.log(response.data);
            app.contacts = response.data;

        })
        .catch(function (error) {
            console.log(error);
        });
    },
    createContact: function(){
    },
    resetForm: function(){
    }
  }
})
</script>

  <?php
 }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
