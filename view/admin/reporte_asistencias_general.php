<?php
include "./models/personal.model.php";
include "./controllers/personal.controller.php";
$personal = new PersonalController();
$hoy = date('Y-m-d'); // Obtener la fecha actual en formato Y-m-d
$currentMonth = date('n'); // Obtener el mes actual en formato numérico
$currentYear = date('Y'); // Obtener el año actual en formato numérico
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
                    <option value="<?php echo $i; ?>" <?php echo ($i == $currentMonth) ? 'selected' : ''; ?>>
                      <?php echo $nombre_mes . " - " . $num_dias_mes; ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="col-md-3">
                <!-- Selector de año -->
                <select class="form-control" id="sel_anio" name="sel_anio">
                    <?php for ($i = $currentYear; $i >= 2023; $i--): ?>
                      <option value="<?php echo $i; ?>" <?php echo ($i == $currentYear) ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                      </option>
                    <?php endfor; ?>
                </select>
              </div>
              <div class="col-md-3">
                <!-- Botón para listar -->
                <button class="btn btn-success" id="btn_listar"><i class="fa fa-list"></i> Listar</button>
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
                    <!-- Los encabezados para los días serán generados dinámicamente por JavaScript -->
                  </tr>
                </thead>
                <tbody>
                  <?php if ($personal->listar_personal()): ?>
                    <?php foreach ($personal->listar_personal() as $per): ?>
                      <tr id="tr_percas_<?php echo $per['id_personal']; ?>">
                        <td><?php echo $per['id_personal']; ?></td>
                        <td class="text-left">
                          <?php echo $per["nombres"]; ?>
                        </td>
                        <td class="text-left">
                          <?php echo $per["apaterno"] . ' ' . $per["amaterno"]; ?>
                        </td>
                        <td><?php echo $per["dni_pa"]; ?></td>
                        <!-- Las celdas para los días se llenarán dinámicamente -->
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                          <td class="day-cell"></td>
                        <?php endfor; ?>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7"><span class="text-center">No se encontraron datos</span></td>
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
  document.addEventListener("DOMContentLoaded", function() {
    function updateTableHeaders() {
      var selectedMonth = document.getElementById("sel_mes_ini").value;
      var selectedYear = document.getElementById("sel_anio").value;
      var daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();

      var headerRow = document.querySelector("#asistencia_general thead tr");
      headerRow.innerHTML = "<th>#</th><th class='col'>Nombres</th><th class='col'>Apellidos</th><th class='col'>Dni</th>";

      //Si agrego la variable 31 l reemplazamos por daysInMonth se sacara los dias dinamicamente pero se pierden los estilos de los datatables
      for (var i = 1; i <= 31; i++) {
        headerRow.innerHTML += "<th>" + i + "</th>";
      }
    }

    function updateTableBody() {
      
      var selectedMonth = document.getElementById("sel_mes_ini").value;
      var selectedYear = document.getElementById("sel_anio").value;
      var daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();

      var rows = document.querySelectorAll("#asistencia_general tbody tr");
      rows.forEach(function(row) {
        var cells = row.querySelectorAll("td");
        for (var i = 4; i < cells.length; i++) {
          cells[i].style.display = i <= daysInMonth + 3 ? '' : 'SDM';
          cells[i].textContent = i <= daysInMonth + 3 ? i - 3 : 'SDM';
        }
      });
    }

    function reinitializeDataTable() {
      if ($.fn.DataTable.isDataTable('#asistencia_general')) {
        $('#asistencia_general').DataTable().destroy();
      }

      $('#asistencia_general').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "columnDefs": [
          { "orderable": true, "targets": [0, 1, 2, 3] },
          { "orderable": false, "targets": "_all" },
        ],
        "dom": 'Bfrtip',
        "buttons": [
          'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ]
      });
    }
    updateAndReinitialize();
    function updateAndReinitialize() {
      updateTableHeaders();
      updateTableBody();
      reinitializeDataTable();
    }

    //document.getElementById("sel_mes_ini").addEventListener("change", updateAndReinitialize);
    //document.getElementById("sel_anio").addEventListener("change", updateAndReinitialize);
    document.getElementById("btn_listar").addEventListener("click", updateAndReinitialize);

    
  });
</script>
