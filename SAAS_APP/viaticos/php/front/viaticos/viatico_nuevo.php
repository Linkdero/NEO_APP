<style type="text/css">
  #regiration_form fieldset:not(:first-of-type) {
    display: none;
  }




/* Multi-Step Form */
* {
  box-sizing: border-box;
}

#regForm {
  background-color: #fff;

  width: 100%;

}



/* Mark input boxes that get errors during validation: */
input.invalid {
  background-color: #ffdddd;
}

textarea.invalid {
  background-color: #ffdddd;
}

select.invalid{
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}



#prevBtn {
  background-color: #bbbbbb;
}

/* Step marker: Place in the form. */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #4CAF50;
}





/* step wizard*/
.stepwizard-step p {
    margin-top: 10px;
}
.stepwizard-row {
    display: table-row;
}
.stepwizard {
    display: table;
    width: 50%;
    position: relative;
}
.stepwizard-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
}
.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-order: 0;
}
.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}
.btn-circle_ {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
}
</style>
<?php
include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
include_once '../../../../empleados/php/back/functions.php';
sec_session_start();
$clase= new viaticos();
$paises = $clase->get_paises();
$horas = $clase->get_items(37);
$tipo_nombramiento = $clase->get_items(58);
$tipo_evento = $clase->get_items(62);
$funcionarios = $clase->get_funcionarios();


/*$id_persona=$_SESSION['id_persona'];
$clase2 = new empleado;
  $e = $clase2->get_empleado_by_id_ficha($id_persona);

  echo $e['id_dirf'].' '.$e['dir_funcional'];
  if($e['id_tipo']==2){
    echo $e['id_dirn'].' '.$e['dir_nominal'];
  }*/

?>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">

  <script src="viaticos/js/funciones.js"></script>
  <script src="viaticos/js/source_modal.js"></script>
  <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css"/>
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
  <script src='assets/js/plugins/datatables/new/dataTables.checkboxes.min.js'></script>
  <script>
  /*$('.js-datepicker').on('changeDate', function(ev){
    $(this).datepicker('hide');
});*/
  </script>


</head>
<body>
  <div class="modal-header">
    <h2>Solicitud de Viáticos</h2>


    <ul class="list-inline ml-auto mb-0">

      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>


    </span>
  </div>
  <div id="" class="modal-body">



    <div class="stepwizard col-sm-12" hidden disabled>
        <div class="stepwizard-row setup-panel" disabled>
          <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-info btn-circle_" disabled>1</a>
            <p>Step 1</p>
          </div>
          <!--div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle_" disabled="disabled">2</a>
            <p>Step 2</p>
          </div>
          <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle_" disabled="disabled">3</a>
            <p>Step 3</p>
          </div>-->
          <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle_" disabled="disabled">2</a>
            <p>Step 2</p>
          </div>
        </div>
      </div>

      <form class="validation_nuevo_viatico" role="form" action="" method="post">
        <div class="row setup-content" id="step-1">
          <div class="col-sm-4">
            <span class="numberr">1</span><strong class=""> Datos de la Comisión</strong><br><br>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="row">
                        <label for="id_fecha_comision ">Fecha Nombramiento*</label>
                        <div class=" input-group  has-personalizado" >
                          <input  type="date" class=" form-control form-control_ form-control-sm" id="id_fecha_comision" name="id_fecha_comision" required  autocomplete="off"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="row">
                        <label for="id_fecha_salida">Fecha Salida*</label>
                        <div class=" input-group  has-personalizado" >
                          <input  type="date" class=" form-control form-control_ form-control-sm" id="id_fecha_salida" name="id_fecha_salida" required  autocomplete="off"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="row">
                        <label for="id_hora_salida">Hora salida*</label>
                        <div class=" input-group  has-personalizado" >
                            <select class="js-select2 form-control form-control-sm" id="id_hora_salida">
                              <?php
                              if($horas["status"] == 200){
                                  $response = "";
                                  foreach($horas["data"] as $hora){
                                      $response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
                                  }
                              }else{
                                  $response = $hora["msg"];
                              }
                              echo $response;
                              ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="row">
                        <label for="id_fecha_regreso">Fecha Regreso*</label>
                        <div class=" input-group  has-personalizado" >
                          <input  type="date" class=" form-control form-control_ form-control-sm" id="id_fecha_regreso" name="id_fecha_regreso"  required autocomplete="off"  autocomplete="off"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="row">
                        <label for="id_hora_regreso">Hora regreso*</label>
                        <div class=" input-group  has-personalizado" >
                            <select class="form-control input-lg form-control_ form-control-sm" id="id_hora_regreso">
                              <?php
                              if($horas["status"] == 200){
                                  $response = "";
                                  foreach($horas["data"] as $hora){
                                      $response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
                                  }
                              }else{
                                  $response = $hora["msg"];
                              }
                              echo $response;
                              ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="row">
                        <label for="usuario">Motivo*</label>
                        <div class=" input-group  has-personalizado" >
                          <textarea rows="3" type="text" oninput="this.value = this.value.toUpperCase()" class=" form-control form-control_ form-control-sm" id="motivo" name="motivo" placeholder="Observaciones" required autocomplete="off"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-sm-4">
            <span class="numberr">2</span><strong class=""> Datos del Cheque</strong><br><br>


            <div class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="id_fecha_cheque">Fecha entrega de cheque*</label>
                    <div class=" input-group  has-personalizado" >
                      <input type="date" class="js-datepicker form-control form-control_ form-control-sm" id="id_fecha_cheque" name="id_fecha_cheque" placeholder="@Fecha entrega de cheque" required autocomplete="off" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="id_hora_cheque">Hora entrega de cheque*</label>
                    <div class=" input-group  has-personalizado" >
                        <select class="form-control input-lg form-control_ form-control-sm" id="id_hora_cheque">
                          <?php
                          if($horas["status"] == 200){
                              $response = "";
                              foreach($horas["data"] as $hora){
                                  $response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
                              }
                          }else{
                              $response = $hora["msg"];
                          }
                          echo $response;
                          ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="id_es_extension">Es Extensión*</label>
                    <div class=" input-group  has-personalizado" >
                        <label class="css-input switch switch-sm switch-success"><input class="chequeado" id="chk_otro_formulario" onchange="mostar_formulario_anterior()" type="checkbox"/><span></span></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="formulario_anterior" style="display:none" class="col-sm-12 slide_up_anim">
              <div >
                <div class="form-group">
                  <div class="">
                    <div class="row">
                      <label for="id_formulario_anterior">Formulario Anterior*</label>
                      <div class=" input-group  has-personalizado" >
                          <input id="id_formulario_anterior" type="number" min="1"class="form-control form-control-sm"></input>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <span class="numberr">3</span><strong class=""> Funcionario y lugar</strong><br><br>
            <div class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="id_funcionario">Funcionario*</label>
                    <div class=" input-group  has-personalizado" >
                        <select class="form-control input-lg form-control_  form-control-sm chosen-select-width" id="id_funcionario">
                          <?php
                          if($funcionarios["status"] == 200){
                              $response = "";
                              $response.="<option value='0'>NINGUNO</option>";
                              foreach($funcionarios["data"] as $f){
                                  $response .="<option value=".$f['id_persona'].">".$f['primer_nombre']." ".$f['segundo_nombre']." ".$f['tercer_nombre']." ".$f['primer_apellido']." ".$f['segundo_apellido']." ".$f['tercer_apellido']."</option>";
                              }
                          }else{
                              $response = $f["msg"];
                          }
                          echo $response;
                          ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="pais">País*</label>
                  <div class=" input-group  has-personalizado" >
                      <select class="form-control input-lg form-control_  form-control-sm chosen-select-width" id="pais">
                        <?php
                        if($paises["status"] == 200){
                            $response = "";
                            $response .="<option value='0'>Seleccionar</option>";
                            $response .="<option value='GT'>GUATEMALA</option>";
                            foreach($paises["data"] as $pais){
                                $response .="<option value=".$pais['id_pais'].">".$pais['nombre']."</option>";
                            }
                        }else{
                            $response = $pais["msg"];
                        }
                        echo $response;
                        ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="departamento">Departamento</label>
                  <div class=" input-group  has-personalizado" >
                      <select class="form-control form-control-sm" id="departamento">
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="municipio">Municipio</label>
                  <div class=" input-group  has-personalizado" >
                      <select class="form-control form-control-sm" id="municipio">
                      </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="aldea">Aldea</label>
                  <div class=" input-group  has-personalizado" >
                      <select class="form-control form-control-sm" id="aldea">
                      </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="Beneficios">Beneficios</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" name="beneficios" id="beneficios">
                      <option value="0">Seleccionar Beneficios</option>
                      <option value="1">Hospedaje y Alimentación</option>
                      <option value="2">Solo Hospedaje</option>
                      <option value="3">Solo Alimentación</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>



          <!--<div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="url_noticia">URL*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" class=" form-control " id="url_noticia" name="url_noticia" placeholder="url de la noticia" required autocomplete="off"/>
                  </div>
                </div>
              </div>
            </div>
          </div>-->

          <!--<div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="categoria">Categoría*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control" id="categoria" name="categoria" required>
                      <option value="1">Positiva</option>
                      <option value="2">Negativa</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>-->

          <div class="col-sm-12" style="display:none">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="usuario">Detalles de la Comisión*</label>
                  <div class=" input-group  has-personalizado" >
                    <textarea rows="3" type="text" oninput="this.value = this.value.toUpperCase()" class=" form-control form-control_  form-control-sm" id="observaciones" name="observaciones" placeholder="Observaciones" required autocomplete="off">
                      value
                    </textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          </div>

            <button class="btn btn-info nextBtn btn-sm pull-right" type="button" focus>Siguiente</button>

        </div>

        <!--<div class="row setup-content" id="step-2">





              <button class="btn btn-default prevBtn btn-sm pull-left" type="button">Anterior</button>
              <button class="btn btn-info nextBtn btn-sm pull-right" type="button">Siguiente</button>

        </div>
        <div class="row setup-content" id="step-3">


        <button class="btn btn-default prevBtn btn-sm pull-left" type="button">Anterior</button>
        <button class="btn btn-info nextBtn btn-sm pull-right" type="button">Siguiente</button>

      </div>-->
        <div class="row setup-content" id="step-2">
          <div class="col-sm-8">
            <div class="">

              <span class="numberr">4</span><strong class=""> Agregar empleados a la comisión</strong><br><br>
              <div class="form-group">
                <table id="tb_empleados_asignar" class="table table-sm table-bordered table-striped" width="100%">
                  <thead>
                    <!--<th class="text-center">Fotografia</th>-->
                    <th class="text-center">Gafete</th>
                    <th class="text-center">Empleado</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Seleccionar</th>
                  </thead>
                  <tbody>

                  </tbody>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <button class="btn btn-default prevBtn btn-sm pull-left" style="margin-top:0px" type="button">Anterior</button>
              <button class="btn btn-success btn-sm pull-right" style="margin-top:0px" onclick="nuevo_viatico()">Generar Viático</button>
            </div>
          </div>
          <div class="col-sm-4">
            <span class="numberr">5</span><strong class=""> Empleados seleccionados</strong><br><br>
            <div class="card" id="empleados_asignados"  >
              <div class=" scrollable-menu" id="empleados_asignados_datos">

              </div>

            </div>
          </div>


          </div>
        </div>
      </form>



    </div>








<script src="assets/js/plugins/chosen/chosen.jquery.js"></script>
<script src="assets/js/plugins/chosen/docsupport/prism.js"></script>
<script src="assets/js/plugins/chosen/docsupport/init.js"></script>
<link rel="stylesheet" href="./assets/js/plugins/chosen/chosen.css">


<script>
  /*$.ajax({
    type: "POST",
    url: "viaticos/php/back/listados/get_pais.php",
    dataType: 'html',
    data: { },
    success:function(data) {
        $("#pais").html(data);
        get_departamentos();
    }
  });*/
  $("#pais").change(function() {
    get_departamentos(1);
  });

  $("#departamento").change(function() {
    get_municipios();
  });

  $("#municipio").change(function() {
    get_aldeas();
  });
</script>
<script>
$(document).ready(function(){
var current = 1,current_step,next_step,steps;
steps = $("fieldset").length;
$(".next").click(function(){
current_step = $(this).parent();
next_step = $(this).parent().next();
next_step.show();
current_step.hide();
setProgressBar(++current);
});
$(".previous").click(function(){
current_step = $(this).parent();
next_step = $(this).parent().prev();
next_step.show();
current_step.hide();
setProgressBar(--current);
});
setProgressBar(current);
// Change progress bar action
function setProgressBar(curStep){
var percent = parseFloat(100 / steps) * curStep;
percent = percent.toFixed();
$(".progress-bar")
.css("width",percent+"%")
.html(percent+"%");
}
});

$(document).ready(function() {

	// Random Alert shown for the fun of it
	/*function randomAlert() {
		var min = 5,
			max = 20;
		var rand = Math.floor(Math.random() * (max - min + 1) + min); //Generate Random number between 5 - 20
		// post time in a <span> tag in the Alert
		$("#time").html('Next alert in ' + rand + ' seconds');
		$('#timed-alert').fadeIn(500).delay(3000).fadeOut(500);
		setTimeout(randomAlert, rand * 1000);
	};
	randomAlert();*/
});

$('.btn').click(function(event) {
    event.preventDefault();
    var target = $(this).data('target');
	// console.log('#'+target);
	$('#click-alert').html('data-target= ' + target).fadeIn(50).delay(3000).fadeOut(1000);

});


// Multi-Step Form
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the crurrent tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Guardar";
  } else {
    document.getElementById("nextBtn").innerHTML = "Siguiente";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    //document.getElementById("formulario_nuevo_viatico").submit();
    //alert('message');
    //formulario_nuevo_viatico();
    //
    Swal.fire({
      title: '<strong></strong>',
      text: "¿Guardar registo?",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, Guardar!'
    }).then((result) => {
        if (result.value) {
          $('#modal-remoto').modal('hide');

        }
      });
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByClassName("form-control_");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

<script>
$(document).ready(function () {

  seleccionar_empleados();


  var navListItems = $('div.setup-panel div a'),
          allWells = $('.setup-content'),
          allNextBtn = $('.nextBtn'),
  		  allPrevBtn = $('.prevBtn');

  allWells.hide();

  navListItems.click(function (e) {
      e.preventDefault();
      var $target = $($(this).attr('href')),
              $item = $(this);

      if (!$item.hasClass('disabled')) {
          navListItems.removeClass('btn-info').addClass('btn-default');
          $item.addClass('btn-info');
          allWells.hide();
          $target.show();
          $target.find('input:eq(0)').focus();
          $target.find('select:eq(0)').focus();
          $target.find('textarea:eq(0)').focus();
      }
  });

  allPrevBtn.click(function(){
      var curStep = $(this).closest(".setup-content"),
          curStepBtn = curStep.attr("id"),
          prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

          prevStepWizard.removeAttr('disabled').trigger('click');
  });

  allNextBtn.click(function(){
      var curStep = $(this).closest(".setup-content"),
          curStepBtn = curStep.attr("id"),
          nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
          curInputs = curStep.find("input[type='text'],input[type='url'],textarea,select,input[type='date']"),
          //curTexts = curStep.find("input[type='text'],input[type='url']"),
          isValid = true;

      $(".form-group").removeClass("has-error");
      for(var i=0; i<curInputs.length; i++){
          if (!curInputs[i].validity.valid){
              isValid = false;
              $(curInputs[i]).closest(".form-group > div").addClass("has-error");
              //$(curTexts[i]).closest(".form-group").addClass("has-error");
          }
      }

      if (isValid)
          nextStepWizard.removeAttr('disabled').trigger('click');
  });

  $('div.setup-panel div a.btn-info').trigger('click');
});
</script>
