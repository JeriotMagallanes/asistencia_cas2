<?php
switch ($s) {
    case 'personal_cas':
        include "./view/admin/personal_cas.php";
        break;
    case 'asistencia_general':
        include "./view/admin/reporte_asistencias_general.php";
        break;
    case 'asistencia':
        include "./view/admin/asistencia_cas.php";
        break;
    case 'usuarios':
        include "./view/admin/acceso_usuarios.php";
        break;
    case 'comision':
        include "./view/admin/papeleta.comision.php";
        break;
    case 'personal':
        include "./view/admin/papeleta.personal.php";
        break;
    case 'logout':
        session_destroy();
        // header("Location: ./index.php");
        echo '<script>location.href = "./index.php";</script>';
        break;
    case 'info':
        include "./view/admin/info_personal.php";
        break;
    case 'frm_rep':
        include "./view/admin/frm_reportes.php";
        break;
    case 'export':
        include "./view/admin/export_by.php";
        break;
    default:
        include "./view/admin/panel_control.php";
        break;
} 