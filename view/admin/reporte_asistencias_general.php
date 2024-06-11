<?php
$currentMonth = date('n'); // Obtener el mes actual en formato numérico
$currentYear = date('Y'); // Obtener el año actual en formato numérico
$selectedMonth = isset($_GET['sel_mes_ini']) ? $_GET['sel_mes_ini'] : $currentMonth;
$selectedYear = isset($_GET['sel_anio']) ? $_GET['sel_anio'] : $currentYear;

// Verificar si los parámetros fueron recibidos y asignar a variables
if (isset($_GET['sel_mes_ini']) && isset($_GET['sel_anio'])) {
    $selectedMonth = $_GET['sel_mes_ini'];
    $selectedYear = $_GET['sel_anio'];
    // Puedes agregar lógica adicional aquí si necesitas hacer algo con los parámetros recibidos
    echo "Mes y año recibidos: $selectedMonth - $selectedYear";
} else {
    // Aquí puedes manejar el caso donde no se recibieron parámetros
  include "./models/personal.model.php";
  include "./controllers/personal.controller.php";
}

$personal = new PersonalController();
?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Personal CAS</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Personal CAS</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <!-- Selectores de mes y año -->
            <div class="form-row mb-3">
              <div class="col-md-3">
                <!-- Selector de mes -->
                <select class="form-control" id="sel_mes_ini" name="sel_mes_ini">
                  <?php for ($i = 1; $i <= 12; $i++): ?>
                    <?php
                      $nombre_mes = date('F', mktime(0, 0, 0, $i, 1));
                      $num_dias_mes = date('t', mktime(0, 0, 0, $i, 1));
                    ?>
                    <option value="<?php echo $i; ?>" <?php echo ($i == $selectedMonth) ? 'selected' : ''; ?>>
                      <?php echo $nombre_mes . " - " . $num_dias_mes; ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="col-md-3">
                <!-- Selector de año -->
                <select class="form-control" id="sel_anio" name="sel_anio">
                    <?php for ($i = $currentYear; $i >= 2023; $i--): ?>
                      <option value="<?php echo $i; ?>" <?php echo ($i == $selectedYear) ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                      </option>
                    <?php endfor; ?>
                </select>
              </div>
              <div class="col-md-3">
                <!-- Botón para listar -->
                <button class="btn btn-success" id="btn_listar" onclick="listarAsistencias()"><i class="fa fa-list"></i> Listar</button>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover text-center" id="asistencia_general">
                <thead style="background-color:#000063;color:white">
                  <tr>
                    <th>#</th>
                    <th class="col">Nombres</th>
                    <th class="col">Apellidos</th>
                    <th class="col">Dni</th>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                      <th class="col"><?php echo $i; ?></th>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $asistencias = $personal->listar_asistencias_personal($selectedMonth, $selectedYear);
                  if ($asistencias):
                    foreach ($asistencias as $index => $per):
                  ?>
                      <tr id="tr_percas_<?php echo $per['dni_pa']; ?>">
                        <td><?php echo $index + 1; ?></td>
                        <td class="text-left"><?php echo $per["nombres"]; ?></td>
                        <td class="text-left"><?php echo $per["apaterno"] . ' ' . $per["amaterno"]; ?></td>
                        <td><?php echo $per["dni_pa"]; ?></td>
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                          <td><?php echo $per["dia_$i"]; ?></td>
                        <?php endfor; ?>
                      </tr>
                  <?php
                    endforeach;
                  else:
                  ?>
                    <tr>
                      <td colspan="35"><span class="text-center">No se encontraron datos</span></td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<script>
function listarAsistencias() {
    var mes = document.getElementById('sel_mes_ini').value;
    var anio = document.getElementById('sel_anio').value;
    window.location.href = 'view/admin/reporte_asistencias_general.php?sel_mes_ini=' + mes + '&sel_anio=' + anio;
}

$(document).ready(function() {
    $('#asistencia_general').DataTable();
});
</script>
