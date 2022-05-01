<?php
/**
 * @Author:    Cristian Daniel Visan <cristian.visan@gmail.com>
 * Last change:    May 14, 2007 *
 */

/* INCLUDES */
include "includes/functions.inc.php";

if (isset($_REQUEST['section']) AND $_REQUEST['section'] != '') {
    $section = $_REQUEST['section'];
} else {
    $section = 'ocupare_camere';
}

if (isset($_POST['action']) AND $_POST['action'] != '') {
    $action = $_POST['action'];
}

//$maintencance_notice = '<center>Notificare! In cadrul aplicatiei pot aparea erori temporare datorita unor modificari in curs. <br/></center>';


// LISTA CAMERE
if ($section == 'lista_camere') {
    $q = "SELECT MAX(etaj) AS max FROM cs_camere";
    $r = getDb()->GetRow($q);
    $max = $r['max'];

    ?>
    <div class="page_title" align="center">LISTA CAMERE</div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <table align="center" border="0" cellpadding="0" cellspacing="1" width="80%">
        <tr bgcolor="#FCFCFC" height="22">
            <td colspan="4">&bull; <a title="Adauga camera" href="<?= $link_site.'?page=camere&section=adauga_camera'; ?>" class="link">Adauga camera</a><br/></td>
        </tr>

        <tr bgcolor="#FCFCFC" height="22">
            <td colspan="4" class="texttable">
                <?php for ($i = 0; $i <= $max; $i++) {
                    echo '<a href="#etaj'.$i.'" class="link_path">'.(($i == 0) ? 'PARTER' : 'Etaj '.$i).'</a>|';
                } ?></td>
        </tr>

        <?php

        for ($i = 0; $i <= $max; $i++) {
            $camere = get_camere_etaj($i);
            if (count($camere) > 0) { ?>
                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead"><a href="#" name="etaj<?= $i; ?>"></a><?php echo ($i == 0) ? 'PARTER' : 'Etaj '.$i ?></td>
                </tr>
                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">Numar</td>
                    <td class="tablehead">Indicativ</td>
                    <td class="tablehead">Numar paturi</td>
                    <td class="tablehead">Actiuni</td>
                </tr>
                <?php

                foreach ($camere as $c) {
                    ?>
                    <tr style="background-color: rgb(230, 244, 255);" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="texttable" align="center"><?= $c['numar'] ?></td>
                        <td class="texttable" align="center"><?= $c['indicativ'] ?></td>
                        <td class="texttable" align="center"><?= $c['nr_paturi'] ?></td>
                        <td class="text" align="center">
                            <a title="Modificare camera" href="<?= $link_site.'?page=camere&section=editeaza_camera&w='.$c['id']; ?>" class="link">Modifica</a> |
                            <a title="Sterge camera" href="<?= $link_site.'?page=camere&section=sterge_camera&w='.$c['id']; ?>" class="link">Sterge</a> |
                            <a title="Istoric camera" href="<?= $link_site.'?page=camere&section=istoric_camera&w='.$c['id']; ?>" class="link">Istoric</a>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"><br/></td>
                </tr>
            <?php }
        }
        ?>
        <tr bgcolor="#FCFCFC" height="22">
            <td colspan="4" class="texttable"><?php for ($i = 0; $i <= $max; $i++) {
                    echo '<a href="#etaj'.$i.'" class="link_path">'.(($i == 0) ? 'PARTER' : 'Etaj '.$i).'</a>|';
                } ?></td>
        </tr>

    </table>

    <?php

}

if ($section == 'istoric_camera') {

    $q = "SELECT * FROM cs_camere WHERE id='".$_GET['w']."'";
    $camera_info = getDb()->GetRow($q);

    $toDisplay = '<div class="page_title" align="center">ISTORIC CAMERA Etaj: '.$camera_info['etaj'].' Nr: '.$camera_info['numar'].' Paturi: '.$camera_info['nr_paturi'].'</div>';

    $sql = "SELECT * FROM cs_persoane_camere WHERE id_camera = {$_GET['w']} ORDER BY data_start DESC, data_end DESC";
    $istoric = getDb()->Execute($sql)->GetRows();

    $toDisplay .= '<table id="table_list" align="center" bgcolor="#a1c2e7">';
    $toDisplay .= '
		<tr>
			<th>Locatar</th>
			<th>Nr pat</th>
			<th>Data cazare</th>
			<th>Data decazare</th>
			<th>Status</th>
		</tr>';

    if (count($istoric) == 0) {
        $toDisplay .= '<tr><td>Nu exista istoric pentru camera selectata</td></tr>';
    }

    foreach ($istoric AS $row) {

        $c = get_locatar_info($row['id_persoana']);

        $toDisplay .= '
		<tr>
			<td>'.$c['nume'].' '.$c['prenume'].' <a href="index.php?page=info_locatar&section=detalii_locatar&w='.$row['id_persoana'].'">['.$row['id_persoana'].']</a></td>
			<td>'.$row['nr_pat'].'</td>
			<td>'.$row['data_start'].'</td>
			<td>'.$row['data_end'].'</td>
			<td>'.(($row['status_activ'] == 1) ? 'Cazat' : 'Decazat').'</td>
		</tr>';
    }

    $toDisplay .= '</table>';

    print $toDisplay;
}

//=================================================================================================
// EDITEAZA CAMERA
//=================================================================================================

if ($section == 'editeaza_camera') {
    $q = "SELECT * FROM cs_camere WHERE id='".$_GET['w']."'";
    $res = getDb()->GetRow($q);

    ?>
    <div class="page_title" align="center">EDITEAZA CAMERA</div>

    <form method='post' action="<?= $link_site.'?page=camere' ?>">
        <input type="hidden" name="action" value="editeaza_camera_do">
        <input type="hidden" name="id" value="<?= $_GET['w']; ?>">
        <table border="0" cellspacing=5 cellpadding=0 align="center" bgcolor='#a1c2e7'>
            <tr>
                <td class="text" align="left">Etaj:</td>
                <td class=text align="left"><select name="etaj" class="select"><?= write_select($arr_etaje_camin, $res['etaj']); ?></select></td>
            </tr>
            <tr>
                <td class="text" align="left">Numar camera:</td>
                <td class=text align="left"><input type=text name="numar" class=input value="<?= $res['numar'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Nr. paturi:</td>
                <td class=text align="left"><input type=text name="nr_paturi" class=input value="<?= $res['nr_paturi'] ?>"></td>
            </tr>
            <tr>
                <td align=left colspan=2><input type='submit' name="submit" class="button_albastru" value='Modifica'><input type='reset' class="button_albastru" value='Renunta'></td>
            </tr>
        </table>
    </form>
    <?php
}

// EDITEAZA CAMERA DO ======================================================================================================

if ($_POST['action'] == 'editeaza_camera_do') {
    //print_r($_POST);
    getDb()->AutoExecute('cs_camere', $_POST, 'UPDATE', "id = ".$_POST['id']."");
    header('Location: '.$link_site.'?page=camere&section=lista_camere');
}

// STERGE CAMERA ===========================================================================================================

if ($_GET['section'] == 'sterge_camera') {

    $q = "DELETE FROM ".$db_prefix."camere  WHERE id=".$_GET['w']." LIMIT 1";
    getDb()->Execute($q);
    header('Location: '.$link_site.'?page=camere&section=lista_camere');
}


// ADAUGA CAMERA ===========================================================================================================

if ($section == 'adauga_camera') { ?>
    <div class="page_title" align="center">ADAUGA CAMERA</div>

    <form method='post' action="<?= $link_site.'?page=camere' ?>">
        <input type="hidden" name="action" value="adauga_camera_do">
        <table border=0 cellspacing=5 cellpadding=0 align="center" bgcolor='#a1c2e7'>
            <tr>
                <td class="text" align="left">Etaj:</td>
                <td class=text align="left"><select name="etaj" class="select"><?= write_select($arr_etaje_camin, $res['etaj']); ?></select></td>
            </tr>
            <tr>
                <td class="text" align="left">Numar camera:</td>
                <td class=text align="left"><input type=text name="numar" class="input"></td>
            </tr>
            <!--	<tr><td class="text" align ="left">Indicativ camera:</td><td class=text align="left"><input type=text name="parity" disabled="disabled" class= input value=""></td></tr> -->
            <tr>
                <td class="text" align="left">Nr. paturi:</td>
                <td class=text align="left"><input type=text name="nr_paturi" class="input"></td>
            </tr>
            <tr>
                <td align=left colspan=2><input type='submit' name="submit" class="button_albastru" value='Adauga'><input type='reset' class="button_albastru" value='Renunta'></td>
            </tr>
        </table>
    </form>
    <?php
}

/* ADAUGA CAMERA DO*/
if ($_POST['action'] == 'adauga_camera_do') {
    print_r($_POST);
    $db->AutoExecute('cs_camere', $_POST, 'INSERT');
    header('Location: '.$link_site.'?page=camere&section=lista_camere');
}

//=================================================================================================
// PLAN GENERAL DE OCUPARE A CAMERELOR 
//=================================================================================================

if ($section == 'ocupare_camere') {
    /* START LOCURI NEOCUPATE */

    /* ID_CAMERE CU CEL PUTIN UN PAT OCUPAT */
    $q_id_cam_ocupate = "
		SELECT DISTINCT id_camera, id_persoana
		FROM camin_studentesc.cs_persoane_camere 
		WHERE status_activ = 1";
    $r_idcamo = $db->Execute($q_id_cam_ocupate);
    $res_idcamo = $r_idcamo->GetAssoc();

    $res_idcamo = @implode(",", array_keys($res_idcamo));

    // camere libere integral
    $q_camere_libere = "SELECT * FROM camin_studentesc.cs_camere WHERE id NOT IN ($res_idcamo) ORDER BY etaj ASC , numar ASC";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_camere_libere = $db->Execute($q_camere_libere);
    $nr_camere_libere = $r_camere_libere->GetRows(); //pa($nr_camere_libere);

    $neo = "
		SELECT 
			COUNT( cs_persoane_camere.nr_pat ) AS paturi_ocupate, 
			cs_persoane_camere.id_camera, 
			cs_camere.etaj, 
			cs_camere.numar, 
			cs_camere.nr_paturi
		FROM camin_studentesc.cs_camere
		INNER JOIN camin_studentesc.cs_persoane_camere ON cs_camere.id = cs_persoane_camere.id_camera
		AND cs_persoane_camere.camera_integral != 1
		AND cs_persoane_camere.status_activ = 1
		GROUP BY cs_persoane_camere.id_camera
		ORDER BY etaj ASC , numar ASC
	";
    $r_neo = $db->Execute($neo);
    $res_neo = $r_neo->GetRows(); //pa($res_neo);

    $loc_neocupat = array();
    foreach ($res_neo AS $key => $loc) {
        if ($loc['paturi_ocupate'] != $loc['nr_paturi']) {
            $loc_neocupat[$key] = $loc;
        }
    }

    //parr($loc_neocupat);

    /* camere cu locuri neocupate */
    if (count($loc_neocupat) > 0) {
        $display_locuri_neocupate1 = '';

        $etaj = 0;
        $i = 0;
        foreach ($loc_neocupat as $loc) {
            if (/*$etaj != $loc['etaj']*/ $i++ % 7 == 6) {
                $display_locuri_neocupate1 .= '<br/>';
            }

            $display_locuri_neocupate1 .= '<a href="#numar_camera_' . $loc['numar'] . '" class="link_normal">Etaj ' . $loc['etaj'] . ' C ' . $loc['numar'] . '</a> &middot; ';
            $etaj = $loc['etaj'];
        }
    }

    /* camere libere integral */
    if (count($nr_camere_libere) > 0) {
        $display_locuri_neocupate2 = '';

        $etaj = 0;
        $i = 0;
        foreach ($nr_camere_libere as $loc) {
            if (/*$etaj != $loc['etaj']*/ $i++ % 7 == 6) {
                $display_locuri_neocupate2 .= '<br/>';
            }

            $display_locuri_neocupate2 .= '<a href="#numar_camera_'.$loc['numar'].'" class="link_normal">Etaj '.$loc['etaj'].' C '.$loc['numar'].'</a> &middot; ';
            $etaj = $loc['etaj'];
        }
    }

    $display_locuri_neocupate = "
     <table style='width: 95%'>
         <tr style='vertical-align: top'>
            <td width='50%'>
                <div class='page_subtitle'>Camere cu locuri neocupate</div>
                " . $display_locuri_neocupate1 . "
            </td>
            <td width='50%'>
                <div class='page_subtitle'>Camere libere</div>
                " . $display_locuri_neocupate2 . "
            </td>
        </tr>
    </table>
    ";

    /* END LOCURI NEOCUPATE */


    $q = "SELECT MAX(etaj) AS max FROM cs_camere";
    $r = $db->Execute($q);
    $max = $r->FetchRow(0);
    $max = $max['max'];

    // verificare daca exista locatari care ocupa o camera intrega - vom lua id'ul acelei camere
    $q_c1 = "SELECT id_camera FROM cs_persoane_camere WHERE camera_integral = 1";
    $r_c1 = $db->Execute($q_c1);
    $result_c1 = $r_c1->GetRows();

    if (count($result_c1) > 0) {
        foreach ($result_c1 AS $c1) {
            $arr_c1[] = $c1['id_camera'];    // lista camere ocupate integral de o singura persoana
        }
    }

    (is_array($arr_c1) && count($arr_c1) > 0) ? $arr_c1 : $arr_c1 = array();

    $locatar = intval($_GET['locatar']);

    ?>
    <div class="page_title" align="center">
        <?php if ($locatar != 0) {
            echo 'Adaugare locatar necazat<br/><span style="color:#000">'.strtoupper(get_persoana_dupa_id($locatar)).'</span>';
        } else {
            echo 'Plan general de ocupare a camerelor';
        }
        ?>
    </div>

    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <!-- SELECT FILTRARE ETAJ CAMERA -->
    <form action="" method="POST">
        <table border="0" cellspacing="0" cellpadding="3" align="center" bgcolor="#e6f4ff" width="350">
            <tr height="22">
                <td class="text" align="right" width="150">Selecteaza etaj:</td>
                <td class="text" align="left" width="200"><select class="selectx2" id="select_etaj" name="select_etaj" onChange="showCamereEtaj(this.value); document.getElementById('iesucks').disabled = false ">
                        <option value="">Alege etaj</option><?= write_select($arr_etaje_camin, $_POST['select_etaj']) ?></select></td>
            </tr>

            <!-- IE SUCKS Am pus un select la misto ca sa arate bine si am scris din ajax tot selectul pentru ca selectul in IE nu suporta innetHTML -->

            <tr height="22">
                <td class="text" align="right" width="150">Selecteaza camera:</td>
                <td class="text" align="left" width="200" id="select_camera"><select class="selectx2" id="iesucks" disabled readonly></select></td>
            </tr>

            <tr height="22">
                <td class="text" align="right" width="150"></td>
                <td class="text" align="left" width="200"><input type="submit" class="buttonaut" value="Afiseaza"></td>
            </tr>
        </table>
    </form>
    <br/><br/>
    <div id="rselect_camera"></div>

    <?php print $display_locuri_neocupate; ?>

    <br/><br/>

    <table align="center" border="0" cellpadding="0" cellspacing="1" width="785">

    <?php
    $cat_navigation = '<tr bgcolor="#FCFCFC" height="22"><td colspan="4" class="texttable" align="center">';
    for ($i = 0; $i <= $max; $i++) {
        $cat_navigation .= '<a href="#etaj'.$i.'" class="link_path">'.(($i == 0) ? 'PARTER' : 'Etaj '.$i).'</a>|';
    }
    $cat_navigation .= '</td></tr>';

    echo $cat_navigation;

    if (($_POST['select_etaj'] == '') AND ($_POST['select_camera'] == '')) {
        for ($i = 0; $i <= $max; $i++) {
            $camere = get_camere_etaj($i); //pa($camere);

            if (count($camere) > 0) { ?>
                <tr bgcolor="#2a87cc" height="22">
                    <td class="texttable" align="left" width="5" bgcolor="#FEFEFE">&nbsp;</td>
                    <td class="tablehead" align="center" width="80" style="color:#FF9900; font-size:16px;"><?php echo ($i == 0) ? 'PARTER' : 'Etaj '.$i ?><a href="#" name="etaj<?= $i ?>"></td>
                    <td colspan="2" width="500" bgcolor="#FEFEFE">&nbsp;</td>
                </tr>
                <?php

                foreach ($camere as $c) {
                    ?>
                    <tr style="background-color: #2a87cc; height:22px">
                        <td class="tablehead" colspan="4" style="text-align:left; padding-left:3px; color:#FFFFFF; font-size:14px;">
                            Numar camera: <span style="color:#FF9900"><?= $c['numar'] ?></span><a href="#" name="numar_camera_<?= $c['numar'] ?>"></a> &nbsp;&nbsp;
                            Numar paturi: <span style="color:#FF9900"><?= $c['nr_paturi'] ?></span>
                        </td>
                    </tr>
                    <?php
                    // daca locatarul sta singur in camera trebuie sa aiba numar pat = 0 si camera_integral = 1

                    if (in_array($c['id'], $arr_c1)) {
                        $s = get_persoana_camera_pat($c['id'], 0);

                        // echo 'A '; pa($s);

                        ?>
                        <tr onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#E6F4FF" height="22">
                            <td class="texttable" align="left" width="5">&bull;</td>
                            <td class="texttable" width="300"
                                style="text-align:left; padding-left:3px;"><?php echo (($s['nume'] != '') AND ($s['prenume'] != '')) ? $s['nume'].' '.$s['prenume'].' ['.$s['id_persoana'].']<br/><span class="blue" style="font-weight:500;">(camera ocupata integral)</span>' : '<span style="color:#FF3333">LOC NEOCUPAT</span>'; ?></td>
                            <td class="text" align="center" width="150"><?php echo (($s['data_start'] != '') AND ($s['data_end'] != '')) ? date4html($s['data_start']).' &rsaquo; '.date_alarm(date4html($s['data_end']), 7).'' : '-' ?></td>
                            <td class="texttable" width="230" style="text-align:center; padding-left:3px;">
                                <?php
                                if (count($s) > 0) {
                                    ?>
                                    <a title="Editeaza detalii cazare" href="<?= $link_site.'?page=camere&section=editeaza_detalii_cazare&w='.$c['id'].'-0'; ?>" class="link12">Editeaza detalii cazare</a>
                                    <?php
                                } else {
                                    ?>
                                    <a title="Adauga locatar" href="<?= $link_site.'index.php?page=locatari&section=adauga_locatar&w='.$c['id'].'-0'; ?>" class="link12">Adauga locatar</a>
                                    <?php
                                } ?>
                            </td>
                        </tr>
                        <?php
                    } else {
                        for ($numarpat = 1; $numarpat <= $c['nr_paturi']; $numarpat++) {
                            $s = get_persoana_camera_pat_fast($c['id'], $numarpat);

                            // echo 'B '; pa($s);
                            ?>
                            <tr onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#E6F4FF" height="22">
                                <td class="texttable" align="left" width="5"><?= $numarpat.'. '; ?></td>
                                <td class="texttable" width="300" style="text-align:left; padding-left:3px;"><?php echo (($s['nume'] != '') AND ($s['prenume'] != '')) ? $s['nume'].' '.$s['prenume'].' ['.$s['id_persoana'].']' : '<span style="color:#FF3333">LOC NEOCUPAT</span>'; ?></td>
                                <td class="text" align="center" width="150"><?php echo (($s['data_start'] != '') AND ($s['data_end'] != '')) ? date4html($s['data_start']).' &rsaquo; '.date_alarm(date4html($s['data_end']), 7).'' : '-' ?></td>
                                <td class="texttable" width="230" style="text-align:center; padding-left:3px;">
                                    <?php

                                    if (count($s) > 0) {
                                        ?>
                                    <a title="Editeaza detalii cazare" href="<?= $link_site.'index.php?page=camere&section=editeaza_detalii_cazare&w='.$c['id'].'-'.$numarpat.'-'.$s['id_persoana']; ?>" class="link12">Editeaza detalii cazare</a>
                                        <?php
                                    } else {
                                        if ($_GET['locatar'] != '') {
                                            ?><a tip="Adauga locatar" href="<?= $link_site.'index.php?page=camere&section=adauga_detalii_cazare_locatar&w='.$c['id'].'-'.$numarpat.'&locatar='.$locatar; ?>" class="link12">Adauga locatar necazat</a>
                                            <?php
                                        } else {
                                            ?><a tip="Adauga locatar" href="<?= $link_site.'index.php?page=locatari&section=adauga_locatar&w='.$c['id'].'-'.$numarpat; ?>" class="link12">Adauga locatar nou</a>
                                            <?php
                                        }
                                    }
                                    ?></td>
                            </tr>

                        <?php }
                    }

                }
                echo $cat_navigation;
            }
        }
        ?>
        </table>
        <?php
        echo '<br>'.$display_locuri_neocupate.'<br><br>';
    }

// AFISEAZA DOAR CAMERELE ETAJULUI SELECTAT

    if (($_POST['select_etaj'] != '') AND (($_POST['select_camera'] == 0) OR ($_POST['select_camera'] == ''))) {
        $camere = get_camere_etaj($_POST['select_etaj']); //pa($camere);

        if (count($camere) > 0) { ?>
            <tr bgcolor="#2a87cc" height="22">
                <td class="texttable" align="left" width="5" bgcolor="#FEFEFE">&nbsp;</td>
                <td class="tablehead" align="center" width="80" style="color:#FF9900; font-size:16px;"><?php echo ($_POST['select_etaj'] == 0) ? 'PARTER' : 'Etaj '.$_POST['select_etaj'] ?></td>
                <td colspan="2" width="500" bgcolor="#FEFEFE">&nbsp;</td>
            </tr>

            <?php

            foreach ($camere as $c) {
                ?>
                <tr style="background-color: #2a87cc; height:22px">
                    <td class="tablehead" colspan="4" style="text-align:left; padding-left:3px; color:#FFFFFF; font-size:14px;">
                        Numar camera: <span style="color:#FF9900"><?= $c['numar'] ?></span> ;&nbsp;&nbsp;
                        Numar paturi: <span style="color:#FF9900"><?= $c['nr_paturi'] ?></span>
                    </td>
                </tr>
                <?php
                // daca locatarul sta singur in camera trebuie sa aiba numar pat = 0 si camera_integral = 1
                if (in_array($c['id'], $arr_c1)) {
                    $s = get_persoana_camera_pat($c['id'], 0);

                    ?>
                    <tr style="background-color:#E6F4FF;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#E6F4FF" height="22">
                        <td class="texttable" align="left" width="5">&bull;</td>
                        <td class="texttable" width="300"
                            style="text-align:left; padding-left:3px;"><?php echo (($s['nume'] != '') AND ($s['prenume'] != '')) ? $s['nume'].' '.$s['prenume'].'<br/><span class="blue" style="font-weight:500;">(camera ocupata integral)</span>' : '<span style="color:#FF3333">LOC NEOCUPAT</span>'; ?></td>
                        <td class="text" align="center" width="150"><?php echo (($s['data_start'] != '') AND ($s['data_end'] != '')) ? date4html($s['data_start']).' &rsaquo; '.date_alarm(date4html($s['data_end']), 7).'' : '-' ?></td>
                        <td class="texttable" width="230" style="text-align:center; padding-left:3px;">
                            <?php
                            if (count($s) > 0) {
                                ?>

                                <a title="Vizualizeaza detalii cazare" name="<?= $c['id'].'-0' ?>" href="#<?= $c['id'].'-0' ?>" onClick="javascript:show_hide(<?= $c['id'] ?>,0);" class="link12">Detalii</a> |
                                <a title="Editeaza detalii cazare" href="<?= $link_site.'?page=camere&section=editeaza_detalii_cazare&w='.$c['id'].'-0'; ?>" class="link12">Editeaza detalii cazare</a>
                                <?php
                            } else {
                                ?>
                                <a title="Adauga locatar" href="<?= $link_site.'index.php?page=locatari&section=adauga_locatar&w='.$c['id'].'-0'; ?>" class="link12">Adauga locatar</a>
                                <?php
                            } ?>
                        </td>
                    </tr>
                    <?php
                } else {
                    for ($numarpat = 1; $numarpat <= $c['nr_paturi']; $numarpat++) {
                        $s = get_persoana_camera_pat($c['id'], $numarpat);

                        ?>
                        <tr style="background-color:#E6F4FF;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#E6F4FF" height="22">
                            <td class="texttable" align="left" width="5"><?= $numarpat.'. '; ?></td>
                            <td class="texttable" width="300" style="text-align:left; padding-left:3px;"><?php echo (($s['nume'] != '') AND ($s['prenume'] != '')) ? $s['nume'].' '.$s['prenume'].' ['.$s['id_persoana'].'] ' : '<span style="color:#FF3333">LOC NEOCUPAT</span>'; ?></td>
                            <td class="text" align="center" width="150"><?php echo (($s['data_start'] != '') AND ($s['data_end'] != '')) ? date4html($s['data_start']).' &rsaquo; '.date_alarm(date4html($s['data_end']), 7).'' : '-' ?></td>
                            <td class="texttable" width="230" style="text-align:center; padding-left:3px;">
                                <?php
                                if (is_array($s) && count($s) > 0) {
                                    ?>

                                    <a title="Vizualizeaza detalii cazare" name="<?= $c['id'].'-'.$numarpat ?>" href="#<?= $c['id'].'-'.$numarpat ?>" onClick="javascript:show_hide(<?= $c['id'] ?>,<?= $numarpat ?>);" class="link12">Detalii</a> |
                                    <a title="Editeaza detalii cazare" href="<?= $link_site.'?page=camere&section=editeaza_detalii_cazare&w='.$c['id'].'-'.$numarpat; ?>" class="link12">Editeaza detalii cazare</a>
                                    <?php
                                } else {
                                    ?>
                                    <a title="Adauga locatar" href="<?= $link_site.'index.php?page=locatari&section=adauga_locatar&w='.$c['id'].'-'.$numarpat; ?>" class="link12">Adauga locatar</a>
                                    <?php
                                } ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <div id="sh_<?= $c['id'] ?>_<?= $numarpat ?>" style="margin-top:2px; background-color:#E6F4FF; float:left; padding:3px 0px 1px 0px; text-align:left; display:none; width:785px; font-size: 10px;">
                                    <?= print_r(get_persoana_camera_pat($c['id'], $numarpat)); ?>
                                    <br/><br/>
                                    <a href="#<?= $c['id'].'-'.$numarpat ?>" onClick="javascript:show_hide(<?= $c['id'] ?>, <?= $numarpat ?>);" class="link_path" style="font-size: 10px;"><b>x</b> inchide descriere</a>
                                </div>
                            </td>
                        </tr>
                    <?php }
                    ?>
                    <tr bgcolor="#FCFCFC" height="22">
                        <td colspan="4" class="texttable" align="center">
                            <?php for ($i = 0; $i <= $max; $i++) {
                                echo '<a href="#etaj'.$i.'" class="link_path">'.(($i == 0) ? 'PARTER' : 'Etaj '.$i).'</a>|';
                            } ?></td>
                    </tr>
                    <?php
                }

            }

            echo '<tr><td colspan="4">&nbsp;</td></tr>';

        }
    }


    if (($_POST['select_etaj'] != '') AND ($_POST['select_camera'] != '') AND ($_POST['select_camera'] != 0)) {
        //echo 'etajul '.	$_POST['select_etaj']  . 'camera '.	$_POST['select_camera'] ;

        $camere = get_camere_etaj($_POST['select_etaj'], $_POST['select_camera']); //pa($camere);
        ?>
        <tr bgcolor="#2a87cc" height="22">
            <td class="texttable" align="left" width="5" bgcolor="#FEFEFE">&nbsp;</td>
            <td class="tablehead" align="center" width="80" style="color:#FF9900; font-size:16px;"><?php echo ($_POST['select_etaj'] == 0) ? 'PARTER' : 'Etaj '.$_POST['select_etaj'] ?></td>
            <td colspan="2" width="500" bgcolor="#FEFEFE">&nbsp;</td>
        </tr>

        <?php
        foreach ($camere as $c) {
            ?>
            <tr style="background-color: #2a87cc; height:22px">
                <td class="tablehead" colspan="4" style="text-align:left; padding-left:3px; color:#FFFFFF; font-size:14px;">
                    Numar camera: <span style="color:#FF9900"><?= $c['numar'] ?></span> ;&nbsp;&nbsp;
                    <!-- Indicativ: <span style="color:#FF9900"><?= $c['indicativ'] ?></span> ;&nbsp;&nbsp; -->
                    Numar paturi: <span style="color:#FF9900"><?= $c['nr_paturi'] ?></span>
                </td>
            </tr>
            <?php
            // daca locatarul sta singur in camera trebuie sa aiba numar pat = 0 si camera_integral = 1
            if (in_array($c['id'], $arr_c1)) {
                $s = get_persoana_camera_pat($c['id'], 0); //print_r($s);

                ?>
                <tr style="background-color:#E6F4FF;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#E6F4FF" height="22">
                    <td class="texttable" align="left" width="5">&bull;</td>
                    <td class="texttable" width="300"
                        style="text-align:left; padding-left:3px;"><?php echo (($s['nume'] != '') AND ($s['prenume'] != '')) ? $s['nume'].' '.$s['prenume'].'<br/><span class="blue" style="font-weight:500;">(camera ocupata integral)</span>' : '<span style="color:#FF3333">LOC NEOCUPAT</span>'; ?></td>
                    <td class="text" align="center" width="150"><?php echo (($s['data_start'] != '') AND ($s['data_end'] != '')) ? date4html($s['data_start']).' &rsaquo; '.date_alarm(date4html($s['data_end']), 7).'' : '-' ?></td>
                    <td class="texttable" width="230" style="text-align:center; padding-left:3px;">
                        <?php
                        if (count($s) > 0) {
                            ?>

                            <a title="Vizualizeaza detalii cazare" name="<?= $c['id'].'-0' ?>" href="#<?= $c['id'].'-0' ?>" onClick="javascript:show_hide(<?= $c['id'] ?>,0);" class="link12">Detalii</a> |
                            <a title="Editeaza detalii cazare" href="<?= $link_site.'?page=camere&section=editeaza_detalii_cazare&w='.$c['id'].'-0'; ?>" class="link12">Editeaza detalii cazare</a>
                            <?php
                        } else {
                            ?>
                            <a title="Adauga locatar" href="<?= $link_site.'?index.php?page=locatari&section=adauga_locatar&w='.$c['id'].'-0'; ?>" class="link12">Adauga locatar</a>
                            <?php
                        } ?>
                    </td>
                </tr>
                <?php
            } else {
                for ($numarpat = 1; $numarpat <= $c['nr_paturi']; $numarpat++) {
                    $s = get_persoana_camera_pat($c['id'], $numarpat);

                    ?>
                    <tr style="background-color:#E6F4FF;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#E6F4FF" height="22">
                        <td class="texttable" align="left" width="5"><?= $numarpat.'. '; ?></td>
                        <td class="texttable" width="300" style="text-align:left; padding-left:3px;"><?php echo (($s['nume'] != '') AND ($s['prenume'] != '')) ? $s['nume'].' '.$s['prenume'].' ['.$s['id_persoana'].'] ' : '<span style="color:#FF3333">LOC NEOCUPAT</span>'; ?></td>
                        <td class="text" align="center" width="150"><?php echo (($s['data_start'] != '') AND ($s['data_end'] != '')) ? date4html($s['data_start']).' &rsaquo; '.date_alarm(date4html($s['data_end']), 7).'' : '-' ?></td>
                        <td class="texttable" width="230" style="text-align:center; padding-left:3px;">
                            <?php
                            if (count($s) > 0) {
                                ?>

                                <a title="Vizualizeaza detalii cazare" name="<?= $c['id'].'-'.$numarpat ?>" href="#<?= $c['id'].'-'.$numarpat ?>" onClick="javascript:show_hide(<?= $c['id'] ?>,<?= $numarpat ?>);" class="link12">Detalii</a> |
                                <a title="Editeaza detalii cazare" href="<?= $link_site.'?page=camere&section=editeaza_detalii_cazare&w='.$c['id'].'-'.$numarpat.'-'.$s['id_persoana']; ?>" class="link12">Editeaza detalii cazare</a>
                                <?php
                            } else {
                                ?>
                                <a title="Adauga locatar" href="<?= $link_site.'index.php?page=locatari&section=adauga_locatar&w='.$c['id'].'-'.$numarpat; ?>" class="link12">Adauga locatar</a>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <div id="sh_<?= $c['id'] ?>_<?= $numarpat ?>" style="margin-top:2px; background-color:#E6F4FF; float:left; padding:3px 0px 1px 0px; text-align:left; display:none; width:785px; font-size: 10px;">
                                <?= get_persoana_camera_pat($c['id'], $numarpat); ?>
                                <br/><br/>
                                <a href="#<?= $c['id'].'-'.$numarpat ?>" onClick="javascript:show_hide(<?= $c['id'] ?>, <?= $numarpat ?>);" class="link_path" style="font-size: 10px;"><b>x</b> inchide descriere</a>
                            </div>
                        </td>
                    </tr>
                <?php }
            }
        }
    }
}

/**
 * @param $etaj
 * @param $camere_all
 * @return array
 */
function cuplu_camere_etaj($etaj, $camere_all)
{
    $camera = array();

    if (count($camere_all) > 0) {
        $i = 0;
        foreach ($camere_all AS $key => $r) { // pa($r);
            if ($r['etaj'] == $etaj) {
                $i++;
                $camera[$i] = array('id' => $key, 'numar' => $r['numar'], 'etaj' => $r['etaj'], 'nr_paturi' => $r['nr_paturi'], 'status_ocupare' => $r['status_ocupare']);
            }
        }
    }

    return $camera;
}

// status activ = 1 => locatar pe durata anului
// status activ = 2 => locatar pe durata verii
// status activ = 3 => locatar necazat pe durata verii dar care are cazare adaugata pentru anul universitar urmator
// status activ = 9 => locatar decazat

// EDITEAZA DETALII CAZARE ========================================================================
if ($section == 'editeaza_detalii_cazare') {

    list($id_camera, $id_pat, $id_locatar) = explode('-', $_GET['w']);

    echo '<!-- [ID CAMERA: '.$id_camera.', ID PAT: '.$id_pat.', ID LOCATAR: '.$id_locatar.'] -->';

    $no_paturi = get_nr_paturi_camera($id_camera);

    $s = get_persoana_camera_pat($id_camera, $id_pat);
    pa($s, 1);

    if (($s == null) && $id_locatar > 0) {
        //echo 'ceva';
        $s = get_persoana_camera_pat($id_camera, $id_pat, $id_locatar);
        pa($s, 1);
    }

    $q = "SELECT * FROM cs_camere ORDER BY etaj ASC, numar ASC";
    $result = getDb()->Execute($q)->GetAssoc();

    $q = "SELECT MAX(etaj) AS max FROM cs_camere";
    $r = $db->Execute($q);
    $max = $r->FetchRow(0);
    $max = $max['max'];

    // START LOCURI NEOCUPATE

    /* ID_CAMERE CU CEL PUTIN UN PAT OCUPAT */
    $q_id_cam_ocupate = "SELECT DISTINCT id_camera, id_persoana FROM cs_persoane_camere";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_idcamo = $db->Execute($q_id_cam_ocupate);
    $res_idcamo = $r_idcamo->GetAssoc();

    $res_idcamo = @implode(",", array_keys($res_idcamo));

    $q_camere_libere = "SELECT id, numar FROM cs_camere WHERE id NOT IN ($res_idcamo)";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_camere_libere = $db->Execute($q_camere_libere);
    $nr_camere_libere = $r_camere_libere->GetAssoc(); //pa($nr_camere_libere);

    /* CAUTARE CAMERE CU UN CEL PUTIN UN PAT NEOCUPAT */
    $neo = "
		SELECT 
			COUNT(cs_persoane_camere.nr_pat) AS paturi_ocupate, 
			cs_persoane_camere.id_camera, 
			cs_camere.etaj, 
			cs_camere.numar, 
			cs_camere.nr_paturi
		FROM cs_camere
		LEFT JOIN cs_persoane_camere ON cs_camere.id = cs_persoane_camere.id_camera
		WHERE 1
		AND cs_persoane_camere.camera_integral != 1
		AND cs_persoane_camere.status_activ IN (1, 2)
		#AND cs_persoane_camere.data_start < '".date("Y-10-01")."'
		GROUP BY cs_persoane_camere.id_camera
		ORDER BY etaj ASC , numar ASC";

    #highlight_string($neo);

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_neo = $db->Execute($neo);
    $res_neo = $r_neo->GetRows(); //pa($res_neo);

    $loc_neocupat = [];

    foreach ($res_neo AS $key => $loc) {
        if ($loc['paturi_ocupate'] != $loc['nr_paturi']) {
            $loc_neocupat[$key] = $loc;
        }
    }

    $nr_camera_loc_neocupat = [];

    foreach ($loc_neocupat AS $loc) {
        $nr_camera_loc_neocupat[] = $loc['numar'];
    }
    /* END LOCURI NEOCUPATE */

    //pa($nr_camera_loc_neocupat);

    $all_locuri_libere = array_merge($nr_camera_loc_neocupat, $nr_camere_libere);    //pa($all_locuri_libere);

    if ($id_camera == 999) {
        $atentionare = 'ATENTIE ! Locatarului i-a fost atribuit un loc temporar pentru a face posibil schimbul locurilor de cazare intre locatari';
    }
    ?>

    <div class="page_title" align="center">Editeaza detalii cazare - <span style="color:#000"><?= $s['nume'].' '.$s['prenume'] ?><?php echo ($s['nume_casatorie'] != '') ? '('.$s['nume_casatorie'].')' : ''; ?></span>[<?= $s['id_persoana'] ?>]</div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>
    <div class="msg" align="center"><?= $atentionare ?></div>

    <form method='post' action="">
        <input type="hidden" name="action" value="editeaza_detalii_cazare_do">
        <input type="hidden" name="id_cazare" value="<?= $s['id'] ?>">
        <input type="hidden" name="id_persoana" value="<?= $s['id_persoana'] ?>">
        <input type="hidden" name="numar_paturi" value="<?= $no_paturi; ?>">

        <table border=0 cellspacing=5 cellpadding=0 align="center" bgcolor='#a1c2e7' width="90%">

            <tr>
                <td class="text" align="left">Numar camera:</td>
                <td class=text align="left">
                    <select name="id_camera" class="select_free" onchange="showPaturi(this.value, <?= $s['id_persoana'] ?>)">
                        <?php
                        for ($i = 0; $i <= $max; $i++) {
                            $camere = cuplu_camere_etaj($i, $result);

                            if (count($camere) > 0) {
                                foreach ($camere AS $c) {
                                    $text = '';

                                    if (in_array($c['numar'], $all_locuri_libere)) {
                                        $text = ' (LOC NEOCUPAT)';
                                    } else {
                                        $text = '';
                                    }
                                    ?>
                                    <option value="<?= $c['id'] ?>"<?php echo ($id_camera == $c['id']) ? ' SELECTED style="color:blue"' : '' ?>><?php echo ($c['etaj'] != 0) ? ' Etaj:'.$c['etaj'] : ' Parter';
                                        echo ' --- Camera:'.$c['numar']; ?><?= $text; ?></option>
                                    <?php
                                }
                            }
                        }
                        ?>
                        <option value="999" <?php echo ($id_camera == 999) ? ' SELECTED style="color:blue"' : 'style="color:red"' ?>>LOC DE SCHIMB</option>

                    </select>
                </td>
            </tr>

            <?php
            // daca nu sunt pe LOC TEMPORAR afisez paturile
            if ($id_camera != 999) { ?>

                <tr>
                    <td class="text" align="left" valign="top">Numar pat:</td>
                    <td class=text align="left" id="txtHint">
                        <?php
                        $nr_paturi = get_nr_paturi_camera($id_camera);

                        $display = '<b>Alege pat:</b><br/>';

                        for ($i = 1; $i <= $nr_paturi; $i++) {
                            $q1 = "
                                SELECT * FROM cs_persoane_camere
                                WHERE 1
                                AND id_camera= ".$id_camera."
                                AND (nr_pat= ".$i." OR nr_pat_2 = ".$i.")
                                AND status_activ != 9
                                ORDER BY nr_pat ASC ";
                            $r1 = $db->Execute($q1);
                            $result1 = $r1->GetRowAssoc($toUpper = false);

                            //pa($result1);
                            //echo '<hr/>';

                            $display .= '<div style="display:block"><input type="checkbox" name="nr_pat['.$i.']" id="nr_pat['.$i.']" onClick="if(this.checked) document.getElementById(\'integral\').disabled = true; if(!this.checked) document.getElementById(\'integral\').disabled = false;" ';

                            if (($result1['id_persoana'] != '') AND ($result1['nr_pat'] != '' OR $result1['nr_pat_2'] != 0)) {
                                $display .= ' CHECKED ';

                                if ($result1['id_persoana'] != $id_persoana) {
                                    $display .= ' disabled="disabled" ';
                                }
                            }

                            $display .= ' /><label for="nr_pat['.$i.']">Pat '.$i.' ';

                            if (($result1['id_persoana'] != '') AND ($result1['nr_pat'] != '')) {
                                $nume = get_persoana_dupa_id($result1['id_persoana']);

                                $text_extra_cazare = ''; //($result1['data_start'] >= date("Y-10-01") )? ' LOC NEOCUPAT PE PERIOADA VERII' : '';

                                if ($s['id_persoana'] == $result1['id_persoana']) {
                                    $display .= '<span class="blue"> - <b> '.$nume.'</b> [<b>'.$result1['id_persoana'].'</b>] ('.date4html($result1['data_start']).'&rsaquo;'.date4html($result1['data_end']).')</span>';
                                    $display .= $text_extra_cazare;
                                } else {
                                    $display .= ' - <b> '.$nume.'</b> [<b>'.$result1['id_persoana'].'</b>] ('.date4html($result1['data_start']).'&rsaquo;'.date4html($result1['data_end']).')';
                                    $display .= $text_extra_cazare;
                                }

                                if ($result1['status_activ'] == 2) {
                                    $display .= ' [cazat pe vara]';
                                }

                            } else {
                                $display .= ' - <span style="color:#666"><b>LOC NEOCUPAT</b></span>';
                            }

                            $display .= '</label></div>'."\n";
                        }

                        echo $display;
                        ?>

                        <br/>
                        <input type="checkbox" name="cazat_pe_vara" id="cazat_pe_vara" <?php echo ($s['status_activ'] == 2) ? ' CHECKED ' : '' ?><?php echo ($s['status_activ'] == 3) ? ' DISABLED ' : '' ?>
                               onclick="if(this.checked) { document.getElementById('necazat_pe_vara').disabled = true; } if(!this.checked) document.getElementById('necazat_pe_vara').disabled = false; "><label for="cazat_pe_vara">Cazat pe perioada verii.</label>

                        <br/>
                        <input type="checkbox" name="necazat_pe_vara" id="necazat_pe_vara" <?php echo ($s['status_activ'] == 3) ? ' CHECKED ' : '' ?><?php echo ($s['status_activ'] == 2) ? ' DISABLED ' : '' ?>
                               onclick="if(this.checked) { document.getElementById('cazat_pe_vara').disabled = true; } if(!this.checked) document.getElementById('cazat_pe_vara').disabled = false; "><label for="necazat_pe_vara">Loc neocupat pe perioada verii (loc ocupat dupa data de <?= date(
                                "01-10-Y"
                            ) ?>).</label>

                        <br/><br/>
                        <input type="checkbox" name="camera_integral" id="integral" <?php echo ($s['camera_integral'] == 1) ? ' CHECKED ' : '' ?>
                               onClick="if(this.checked) for(var js=1; js<=<?= $nr_paturi ?>; js++)  { document.getElementById('nr_pat['+js+']').disabled = true ;}  if(!this.checked) for(var fs=1; fs<=<?= $nr_paturi ?>; fs++) { document.getElementById('nr_pat['+fs+']').disabled = false; }"><label
                                for="integral">Camera intreaga (toate paturile)</label>


                    </td>
                </tr>

                <?php
            } else {
                echo '	<tr><td class="text" align ="left" valign="top"></td><td class=text align="left" id="txtHint"></tr>';
            }

            ?>
            <tr>
                <td class="titlu" colspan="4"><br/><b>Perioada cazare</b></td>
            </tr>

            <tr>
                <td class="text" align="left">Data inceput:</td>
                <td class=text align="left"><input type="text" class="input_100" size="20" name="data_start" id="txtDateRet" value="<?= date4html($s['data_start']) ?>"> <img id="imgDateRet" src="images/edit_small.gif" style="cursor:pointer;"
                                                                                                                                                                              onclick="displayDatePicker('data_start', false, 'dmy', '-');" title="Alege data de inceput" valign="middle"></td>
            </tr>

            <tr>
                <td class="text" align="left">Data final:</td>
                <td class=text align="left"><input type="text" class="input_100" size="20" name="data_end" id="txtDateRet" value="<?= date4html($s['data_end']) ?>"> <img id="imgDateRet" src="images/edit_small.gif" style="cursor:pointer;" onclick="displayDatePicker('data_end', false, 'dmy', '-');"
                                                                                                                                                                          title="Alege data de sfarsit" valign="middle"></td>
            </tr>

            <tr>
                <td class="titlu" colspan="4">
                    <hr/>
            </tr>
            <tr>
                <td class="titlu" colspan="4"><br/><b>Alte detalii cazare</b></td>
            </tr>
            <?php

            //pa($s);

            /* data corespunzatoare pentru fiecare utilitate adaugata */
            if ($s) {
                $q_ud = "
                    SELECT id_utilitate, cs_persoane_utilitati.* 
                    FROM cs_persoane_utilitati 
                    WHERE id_locatar = ".$s['id_persoana']." AND id_cazare = ".$s['id']."
                ";

                $db->SetFetchMode(ADODB_FETCH_ASSOC);
                $r_ud = $db->Execute($q_ud);
                $utilitati_info = $r_ud->GetAssoc();

                //psql($q_ud);
                //pa($utilitati_info);
            }

            $help['title'] = 'Echipamente electrice';
            $help['text'] = '
<b>Modificare data/perioada valabilitate echipament electric</b>
1. Se da click in interiorul campurilor unde trebuie introdusa data. La click va fi afisat un calendar din care se va alege data corespunzatoare.
2. Daca data sau datele sunt diferite de cele avute anterior va aparea un buton "OK".
3. Apasati acel buton pentru a salva datele noi.
4. Daca nu este completata data apare predefinit \'00-00-0000\'. Calendarul are un buton "Luna curenta" pe care daca dati click va duce in luna curenta. Folositi-l !
';

            ?>

            <tr>
                <td class="text" align="left" valign="top">Echipamente electrice: <?= show_help($help); ?></td>
                <td class=text align="left">&nbsp;</td>
            </tr>

            <tr>
                <td class="text" valign="top" colspan="2">

                    <?php
                    $utilitati_here = get_utilitati();  //pa($utilitati_here);
                    $nu = count($utilitati_here) + 1;

                    $current_values = explode("|", $s['utilitati_cazare']);
                    ?>

                    <div style="display:block; float:left; width:600px" onmouseover="this.className='over'" onmouseout="this.className='hover'">
                        <input type="checkbox" name="utilitati_cazare[0]" id="utilitati_cazare[0]" <?php echo ($s['utilitati_cazare'] == 0) ? ' CHECKED ' : '' ?>
                               onClick="if(this.checked) var nu=1; while(nu<=<?= $nu; ?>) { document.getElementById('ucid['+nu+']').disabled = true ; nu++} var nu2=1; if(!this.checked) while(nu2<=<?= $nu; ?>) { document.getElementById('ucid['+nu2+']').disabled = false; nu2++}"><label
                                for="utilitati_cazare[0]">FARA ECHIPAMENTE ELECTRICE</label><br/>
                    </div>

                    <?php
                    $for_id = 1;
                    foreach ($utilitati_here AS $value => $text) {
                        echo '<!-- ------------ ------------ ------------ ------------ ------------ -->'."\n";
                        echo '<div style="float:left; width:600px; margin-bottom:5px; padding-top:2px;" onmouseover="this.className=\'over\'" onmouseout="this.className=\'hover\'">'."\n";
                        (in_array($value, $current_values)) ? $selected = 'CHECKED' : $selected = '';

                        echo '<div style="display:block; float:left; width:150px; line-height:14px;">
			<input type="checkbox" name="utilitati_cazare['.$value.']" id="ucid['.$for_id.']" '.$selected.' onClick="if(this.checked) { document.getElementById(\'utilitati_cazare[0]\').disabled = true; }  if(!this.checked) { document.getElementById(\'utilitati_cazare[0]\').disabled = false;}"}><label for="utilitati_cazare['.$value.']">'.$text.'</label> 
			</div>'."\n";

                        if (/*(strlen($utilitati_info[$value]['data']) > 0) &&*/
                        in_array($value, $current_values)
                        ) {
                            if ($utilitati_info[$value]['data_input'] == '0000-00-00') {
                                $utilitati_info[$value]['data_input'] = date("Y-m-d");
                            }

                            if ($utilitati_info[$value]['data_output'] == '0000-00-00') {
                                $utilitati_info[$value]['data_output'] = date("Y-m-d");
                            }

                            echo '<span class="myblue" id="txtHint_date_'.$value.'" tip="click pe date pentru a modifica perioada"></span>'."\n";

                            ?>
                            <div style="display:block; float:left; width:400px">
                                <input type="hidden" name="curent_data_input_<?= $value ?>" value="<?= date4html($utilitati_info[$value]['data_input']) ?>"/>
                                <input type="hidden" name="curent_data_output_<?= $value ?>" value="<?= date4html($utilitati_info[$value]['data_output']) ?>"/>

                                <input type="text" class="input_100" size="15" name="data_input_<?= $value ?>" id="txtDateRet"
                                       value="<?= date4html($utilitati_info[$value]['data_input']) ?>"
                                       onclick="displayDatePicker('data_input_<?= $value ?>', false, 'dmy', '-');"
                                       onblur="if(this.value != this.form.curent_data_input_<?= $value ?>.value) document.getElementById('buton_schimba_data_<?= $value ?>').style.display = 'inline'; "
                                       tip="click pe date pentru a modifica perioada"/>

                                <input type="text" class="input_100" size="15" name="data_output_<?= $value ?>" id="txtDateRet"
                                       value="<?= date4html($utilitati_info[$value]['data_output']) ?>"
                                       onclick="displayDatePicker('data_output_<?= $value ?>', false, 'dmy', '-');"
                                       onblur="if(this.value != this.form.curent_data_output_<?= $value ?>.value) document.getElementById('buton_schimba_data_<?= $value ?>').style.display = 'inline'; "
                                       tip="click pe date pentru a modifica perioada"/>

                                <input type="button" class="okbutton" name="save_new_date" id="buton_schimba_data_<?= $value ?>" style="display:none" value="&#10004; ok"
                                       onclick="if((this.form.data_input_<?= $value ?>.value != '') && (this.form.data_output_<?= $value ?>.value != ''))
                                               {
                                                    save_perioada_utilitate(this.form.data_input_<?= $value ?>.value, this.form.data_output_<?= $value ?>.value, <?= $value ?>, this.form.id_persoana.value, this.form.id_cazare.value);
                                               }
                                               else
                                               {
                                                    alert('Completati o data noua in formatul zz-mm-aaaa');
                                               }"/>
                            </div>

                            <div style="display:block; float:left; width:50px">
                                <span class="myblue underline" tip="Vizualizare istoric utilitate" onclick="$('#sh_<?= $value; ?>_istoric').toggle();">istoric</span>
                            </div>
                            <div style="display:none; float:left; width:400px; line-height:20px;" id="sh_<?= $value; ?>_istoric">
                                <strong>ISTORIC UTILITATE <?= $text; ?></strong> (pentru cazarea curenta)
                                <br/>
                                <?php
                                $q_istoric_u = "
                                    SELECT * 
                                    FROM cs_persoane_utilitati 
                                    WHERE id_locatar = ".$s['id_persoana']." 
                                    AND id_cazare = ".$s['id']." 
                                    AND id_utilitate = $value";

                                $res_istoric_u = $db->Execute($q_istoric_u);
                                $r_istoric_u = $res_istoric_u->GetRows();

                                foreach ($r_istoric_u AS $r_istoric) {
                                    echo $r_istoric['data_input'].' => '.$r_istoric['data_output'].' status: <b>'.(($r_istoric['id_status'] == 1) ? 'activ' : 'inactiv').'</b>';
                                }
                                ?>
                            </div>


                            <?php
                        } else {
                            echo '<div style="display:block; float:left; width:400px">&nbsp;</div>';

                        }

                        echo '</div>'."\n\n";

                        $for_id++;
                    }
                    ?>
                    <br><br>
                </td>
            </tr>

            <tr>
                <td class="text" align="left" valign="top">Alte detalii:</td>
                <td class=text align="left"><textarea class="textarea200" style="width:400px; height:250px; " name="detalii_cazare"><?= $s['detalii_cazare'] ?></textarea></td>
            </tr>
            </div>
            <tr>
                <td class="text" colspan="4">&nbsp;</td>
            </tr>

            <tr>
                <td></td>
                <td align="left" colspan="3"><input type='submit' name="submit" class="button_albastru" value='Modifica'><input type='reset' class="button_albastru" value='Renunta'></td>
            </tr>
        </table>
    </form>

    <!-- some action buttons -->

    <table border="0" cellspacing="1" cellpadding="1" align="center" bgcolor='#a1c2e7' width="90%">

        <!-- link catre "Info locatar "-->
        <tr height="30">
            <td onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#a1c2e7'" bgcolor="#a1c2e7" align="center" width="40%">
                <a tip="Vizualizeaza ''Info locatar'' curent" href="<?= $link_site.'index.php?page=info_locatar&section=detalii_locatar&w='.$s['id_persoana']; ?>" class="link">INFO LOCATAR</a>
            </td>
            <td onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#a1c2e7'" bgcolor="#a1c2e7" align="center" width="40%">
                <a tip="Vizualizeaza ''Status datorii'' locatar curent" href="<?= $link_site.'index.php?page=info_locatar&section=status_datorii&w='.$s['id_persoana']; ?>" class="link">STATUS DATORII</a>
            </td>
        </tr>

        <!-- link catre "Plan general de ocupare a camerelor" + "Decazare" -->
        <tr height="30">
            <td onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#a1c2e7'" bgcolor="#a1c2e7" align="center" width="40%">
                <a tip="Vizualizeaza ''Plan general de ocupare a camerelor'' " href="<?= $link_site.'index.php?page=camere&section=ocupare_camere#'.$_GET['w']; ?>" class="link">PLAN OCUPARE CAMERE</a>
            </td>
            <td onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#a1c2e7'" bgcolor="#a1c2e7" align="center" width="40%">
                <a tip="Decazeaza locatar" href="<?= $link_site.'index.php?page=camere&section=decazare&w='.$s['id'].'-'.$s['id_persoana']; ?>" onclick="if(!confirm('Sunteti sigur ca doriti decazarea?')) return false;" class="link"><span class="red">[DECAZARE]</span></a>
            </td>
        </tr>
    </table>
    <br><br>
    <?php
}


// EDITEAZA DETALII CAZARE DO ===================================================================================================

if ($_POST['action'] == 'editeaza_detalii_cazare_do') {
    /* construim array-ul pentru evidenta modificarilor */

    //pa($_POST);

    $locatar_info = get_locatar_info($_POST['id_persoana']); //pa($locatar_info);

    $do_istoric['id_locatar'] = $_POST['id_persoana'];
    $do_istoric['id_cazare'] = $_POST['id_cazare'];
    $do_istoric['data_modificare'] = date("Y-m-d H:i:s");
    $do_istoric['tip_modificare'] = 'cazare#';
    $do_istoric['autor_modificare'] = $_SESSION['iduser'];
    $do_istoric['detalii_modificare'] = '';

    $old = get_infocazare_idpersoana($_POST['id_persoana']);
    //pa($old);
    /* pentru cazul in care il mut dintr-un pat/camera in altul */

    $id = $_POST['id_cazare'];
    $clean['id_camera'] = $_POST['id_camera'];

    $clean['id_persoana'] = $_POST['id_persoana'];

    if (count($_POST['nr_pat']) > 0) {
        $clean['nr_pat'] = implode(',', array_keys($_POST['nr_pat']));
        $clean['nr_pat'] = substr($clean['nr_pat'], 0, 1);
    }

    if ($_POST['camera_integral'] == 'on') {
        $clean['nr_pat'] = 0;
        $clean['camera_integral'] = 1;
    } else {
        $clean['camera_integral'] = 0;
    }

    $clean['data_start'] = date4sql($_POST['data_start']);
    $clean['data_end'] = date4sql($_POST['data_end']);

    if ($_POST['utilitati_cazare'][0] != 'on') {
        $clean['utilitati_cazare'] = implode("|", array_keys($_POST['utilitati_cazare']));
    } else {
        $clean['utilitati_cazare'] = '0';
    }

    $clean['detalii_cazare'] = strip_tags($_POST['detalii_cazare']);

    // default active
    $clean['status_activ'] = 1;

    // daca este cazat pe vara
    if ($_POST['cazat_pe_vara'] == 'on') {
        $clean['status_activ'] = 2;
    }

    // daca este dupa inceperea anului universitar si locul este disponibil pe perioada verii
    if ($_POST['necazat_pe_vara'] == 'on') {
        $clean['status_activ'] = 3;
    }

//
//	echo '<div class="page_title" align="center">Pentru moment modificarea datoriilor de cazare nu este posibila!</div>';
//	die;

    $changes = 0;

    /* HISTORY DETAILS ON CHANGE */
    if ($old['id_camera'] != $clean['id_camera']) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'camera#';
        $do_istoric['detalii_modificare'] .= 'ID_CAMERA din '.$old['id_camera'].' => '.$clean['id_camera'].'#';
        $regenerare_datorii['id_camera'] = true;
    }

    if (isset($clean['nr_pat']) AND ($old['nr_pat'] != $clean['nr_pat'])) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'nr_pat#';
        $do_istoric['detalii_modificare'] .= 'NUMAR_PAT din '.$old['nr_pat'].' => '.$clean['nr_pat'].'#';
    }

    if ($old['camera_integral'] != $clean['camera_integral']) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'camera_integral#';
        $do_istoric['detalii_modificare'] .= 'CAMERA_INTEGRAL din '.$old['camera_integral'].' => '.$clean['camera_integral'].'#';
        $regenerare_datorii['camera_integral'] = true;
    }

    if ($old['data_start'] != $clean['data_start']) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'data_start#';
        $do_istoric['detalii_modificare'] .= 'DATA_START_CAZARE din '.$old['data_start'].' => '.$clean['data_start'].'#';
        $regenerare_datorii['data_start'] = true;
    }

    if ($old['data_end'] != $clean['data_end']) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'data_end#';
        $do_istoric['detalii_modificare'] .= 'DATA_END_CAZARE din '.$old['data_end'].' => '.$clean['data_end'].'#';
        $regenerare_datorii['data_end'] = true;
    }

    if (isset($clean['rest_plata']) AND ($old['rest_plata'] != $clean['rest_plata'])) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'rest_plata#';
        $do_istoric['detalii_modificare'] .= 'REST_PLATA din '.$old['rest_plata'].' => '.$clean['rest_plata'].'#';
    }

    if ($old['utilitati_cazare'] != $clean['utilitati_cazare']) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'utilitati_cazare#';
        $do_istoric['detalii_modificare'] .= 'UTILITATI_CAZARE din '.$old['utilitati_cazare'].' => '.$clean['utilitati_cazare'].'#';
        $regenerare_datorii['utilitati_cazare'] = true;

        /* new version of utilitati management*/
        manage_utilitati($id, $clean['id_persoana'], $old['utilitati_cazare'], $clean['utilitati_cazare']); //function manage_utilitati($id_cazare, $id_locatar, $utilitati_old, $utilitati_new)

    }

    if ($old['detalii_cazare'] != $clean['detalii_cazare']) {
        $changes++;
        $do_istoric['tip_modificare'] .= 'detalii_cazare#';
        $do_istoric['detalii_modificare'] .= 'DETALII_CAZARE din '.$old['detalii_cazare'].' => '.$clean['detalii_cazare'].'#';
    }

    if ($old['status_activ'] != $clean['status_activ']) {
        $arr_text_status = array(1 => 'Cazat pe perioada anului', 2 => 'Cazat pe perioada verii');
        $changes++;
        $do_istoric['tip_modificare'] .= 'modificare_status#';
        $do_istoric['detalii_modificare'] .= 'DETALII_CAZARE din '.$arr_text_status[$old['detalii_cazare']].' => '.$arr_text_status[$clean['detalii_cazare']].'#';
    }


    /* do some checks and validations */

    // daca au fost facute incasari plecand de la data start aceasta nu poate fi modificata
    $db->AutoExecute('cs_persoane_camere', $clean, 'UPDATE', "id = ".$id."");


    // iau valorile dupa UPDATE
    $new = get_infocazare_idpersoana($_POST['id_persoana']);

    $do_istoric['old_new_values'] = serialize($old).'#'.serialize($new);

    //pa($do_istoric);

    if ($changes > 0) {
        // daca s-au facut schimbari inserez modificarile in istoric modificari
        $db->AutoExecute('cs_istoric_modificari', $do_istoric, 'INSERT');
    }

    /* daca s-au modificat: Perioada de cazare, Camera (cu numarul de paturi in camera), Camera integral, numarul utilitatilor de cazare
    trebuie ca datoriile locatarului sa fie regenerate */

    if (count($regenerare_datorii) > 0) {
        //echo 'DE FACUT CORECT - trebuie adaugat un camp care numara utilitatile de fiecare data cand se genereaza valoarea dataoriei';
        pa($regenerare_datorii);

        if ($regenerare_datorii['data_end'] == 1) {
            // trebuie sa adaug datoriile lunare aferente chimbarii datei de cazare
        }

        // daca modific camera si noua camera are alt numar de paturi decat cea veche, modific datoriile TAXA LUNARA DE CAZARE pentru cazarea locatarului curent.
        if ($regenerare_datorii['id_camera'] == 1) {
            $nr_paturi_cold = get_nr_paturi_camera($old['id_camera']);
            $nr_paturi_cnew = get_nr_paturi_camera($clean['id_camera']);

            if ($nr_paturi_cold != $nr_paturi_cnew) {
                $tip_camera = $nr_paturi_cnew - 1;
                if ($clean['camera_integral'] == 1) {
                    $tip_camera = $tip_camera + 3;
                }


                $locatar_info = get_locatar_info($clean['id_persoana']); //pa($locatar_info);

                $tip_persoana = $locatar_info['tip_persoana'];

                $interval[0] = $locatar_info['data_start']; // sau date("Y-m-d");
                $interval[1] = $locatar_info['data_end'];

                $c_taxa = get_taxa_corespunzatoare(1, $tip_camera, $tip_persoana, $interval);    //pa($c_taxa);


                //echo 's-a schimbat numarul de paturi la camera';
                $update_datorii_taxacazare = "
					UPDATE cs_datorii SET
						id_taxa = {$c_taxa['id_taxa']}
						suma_datorie = {$c_taxa['suma_datorie']}
						text_datorie = '{$c_taxa['text_datorie']}'
					WHERE data_start > ".date('Y-m-d')." 
					AND tip_datorie = 1
					AND status_datorie = 1
					AND id_locatar = {$clean['id_persoana']}";
                getDb()->Execute($update_datorii_taxacazare);
            }
        }

        //		if($regenerare_datorii['utilitati_cazare'] == 1)
        //		{
        //			//trebuie sa modific datoria corespunzatoare tinand cont de numarul nou de utilitati cazare
        //
        //			$cuantum =  count(@explode("|", $clean['utilitati_cazare'])) / count(@explode("|", $old['utilitati_cazare']));
        //
        //			$update_datorii_utilitati = "
        //				UPDATE cs_datorii SET
        //				suma_datorie = suma_datorie * $cuantum
        //				WHERE data_start > ".date('Y-m-d')."
        //				AND tip_datorie = 2
        //				AND status_datorie = 1
        //				AND id_locatar = ".$clean['id_persoana']." ";
        //
        //			//psql($update_datorii_utilitati);
        //
        //			getDb()->Execute($update_datorii_utilitati);
        //		}

    }
    //*
    if ($clean['id_camera'] == 999) {
        header('Location: '.$link_site.'index.php?page=camere&section=editeaza_detalii_cazare&msg=schimb de locatari');
    }


    if (($clean['id_camera'] != '') && ($clean['nr_pat'] != '')) {
        header('Location: '.$link_site.'index.php?page=camere&section=editeaza_detalii_cazare&w='.$clean['id_camera'].'-'.$clean['nr_pat'].'');
    } else {
        header('Location: '.$link_site.'index.php?page=camere&section=editeaza_detalii_cazare&w='.$id_camera.'-'.$id_pat.'');
    }
    //*/
}

//=================================================================================================
// DECAZARE
//=================================================================================================

if (($section == 'decazare') && ($_POST['step'] != 2)) {
    ?>
    <div class="page_title" align="center">Introduceti data decazarii</div>

    <form action="" method="POST">
        <input type="hidden" name="step" value="2">
        <input type="hidden" name="w" value="<?= $_GET['w'] ?>">
        <table border="0" cellspacing="5" cellpadding="1" align="center" bgcolor='#a1c2e7' width="250">
            <tr height="30">
                <td class="text" align="right">Data decazarii:</td>
                <td class=text align="left">
                    <input type="text" class="input_100" size="20" name="data_decazare" id="txtDateRet" value="<?= date("d-m-Y") ?>"> <img id="imgDateRet" src="images/edit_small.gif" style="cursor:pointer;" onclick="displayDatePicker('data_decazare', false, 'dmy', '-');"
                                                                                                                                                                   tip="Introduceti data decazarii !" valign="middle"></td>
            </tr>

            <tr height="30">
                <td colspan="2" align="center"><input type='submit' name="submit" class="button_albastru" value='Continuare'></td>
            </tr>
        </table>
    </form>

    <?php
}

if (($section == 'decazare') && ($_POST['step'] == 2)) {

    //pa($_POST);

    list($id_cazare, $id_persoana) = explode('-', $_POST['w']);

    $data_decazare = date4sql($_POST['data_decazare']);

    $q_d = "SELECT * FROM cs_persoane_camere WHERE id = $id_cazare AND id_persoana = $id_persoana AND status_activ = 1";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_d = $db->Execute($q_d);
    $result = $r_d->GetAssoc();

    $numar_utilitati_cazare = count(explode("|", $result['utilitati_cazare']));

    //	+ stergem datoriile neincasate care sunt dupa luna in care se face decazarea
    //	+ vedem datoriile curente ale locatarului

    // Taxa lunara de cazare ------------------------- //
    $q_1_update = "
        DELETE FROM cs_datorii
        WHERE id_cazare='".$id_cazare."' 
        AND id_locatar='".$id_persoana."' 
        AND tip_datorie=1 
        AND data_start >= '".date("Y-m-t", strtotime($data_decazare))."' 
        AND status_datorie = 1";
    $r_1_update = $db->Execute($q_1_update);
    //psql($q_1_update);


    // Taxa lunara utilitati (echipamente electrice)
    $q_2_update = "DELETE FROM cs_datorii
			WHERE id_cazare='".$id_cazare."' 
			AND id_locatar='".$id_persoana."' 
			AND tip_datorie=2 
			AND data_start >= '".date("Y-m-t", strtotime($data_decazare))."' 
			AND status_datorie = 1";
    $r_2_update = $db->Execute($q_2_update);
    //psql($q_2_update);

    // caut datoriile anterioare decazarii neachitate
    $q_1 = "SELECT * FROM cs_datorii
		WHERE id_cazare='".$id_cazare."' 
		AND id_locatar='".$id_persoana."' 
		AND status_datorie=1
		AND tip_datorie=1 
		ORDER BY data_start ASC"; //psql($q_1);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_1 = $db->Execute($q_1);
    $result_1 = $r_1->GetRows(); //pa($result_1);

    $q_2 = "SELECT * FROM cs_datorii
		WHERE id_cazare='".$id_cazare."' 
		AND id_locatar='".$id_persoana."' 
		AND status_datorie=1
		AND tip_datorie=2 
		ORDER BY data_start ASC"; //psql($q_2);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_2 = $db->Execute($q_2);
    $result_2 = $r_2->GetRows(); //pa($result_2);

    // Garantie camin (taxa per cazare)
    $q_3 = "SELECT * FROM cs_datorii
		WHERE id_cazare='".$id_cazare."' 
		AND id_locatar='".$id_persoana."' 
		AND status_datorie=1
		AND tip_datorie=3 
		ORDER BY data_start ASC"; //psql($q_3);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_3 = $db->Execute($q_3);
    $result_3 = $r_3->GetRows(); //pa($result_3);

    // Degradari baza materiala camin
    $q_4 = "SELECT * FROM cs_datorii
		WHERE id_cazare='".$id_cazare."' 
		AND id_locatar='".$id_persoana."' 
		AND status_datorie=1
		AND tip_datorie=4 
		ORDER BY data_start ASC"; //psql($q_4);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_4 = $db->Execute($q_4);
    $result_4 = $r_4->GetRows(); //pa($result_4);

    // Alte taxe (neprecizate)
    $q_5 = "SELECT * FROM cs_datorii
		WHERE id_cazare='".$id_cazare."' 
		AND id_locatar='".$id_persoana."' 
		AND status_datorie=1
		AND tip_datorie NOT IN (1,2,3,4) 
		ORDER BY data_start ASC"; //psql($q_5);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_5 = $db->Execute($q_5);
    $result_5 = $r_5->GetRows(); //pa($result_5);

    // daca locatarul are inca datorii din lunile anterioare le afisez si blochez procesul
    if ((count($result_1) > 0) || (count($result_2) > 0) || (count($result_3) > 0) || (count($result_3) > 0) || (count($result_4) > 0) || (count($result_5) > 0)) {
        echo '<div align="center"><span class="msg" >Exista datorii anterioare neachitate! <br/> Decazarea nu poate fi efectuata daca sunt datorii anterioare neachitate! <br></span></div>';
        /* EXISTA DATORII ANTERIOARE ALE LOCATARULUI NEACHITATE ; LE AFISEZ */

        $i1 = $i2 = $i3 = $i4 = $i5 = 1;

        ?>
        <!-- DATORII CURENTE -->
        <div class="page_title" align="center">DATORII ANTERIOARE NEACHITATE - <?= get_persoana_dupa_id($id_persoana) ?></div>

        <?php
        if (count($result_1) > 0) {
            ?>
            <!-- TAXA LUNARA DE CAZARE -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc">
                    <td class="tablehead" colspan="3"><b>TAXA LUNARA DE CAZARE</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;   text-align:left;">&nbsp;</td>
                </tr>

                <tr bgcolor="#2a87cc">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                foreach ($result_1 AS $c) { ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text" width="10" align="center"><?= $i1++; ?></td>
                        <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" width="40" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                        <td class="text" width="250" align="center"><?= $c['text_datorie'] ?></td>
                        <td class="text" width="30" align="center">
                            <?php echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>' ?>
                        </td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                            <a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                            <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <br/><br/>

            <?php
        }
        if (count($result_2) > 0) {
            ?>
            <!-- Taxa lunara utilitati (echipamente electrice) -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>TAXA LUNARA UTILITATI<br> (echipamente electrice)</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;   text-align:left;">&nbsp;</td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead" tip="Suma datorie exprimata in RON inmultita cu <br>numarul de echipamente electrice (<?= $numar_utilitati_cazare ?>)">Suma datorie (RON)<br/><span class="blue">x<?= $numar_utilitati_cazare ?></span>(ec. electr.)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php foreach ($result_2 AS $c) { ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text" width="10" align="center"><?= $i2++; ?></td>
                        <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" width="40" align="right"><span class="blue"><?= number_format($c['suma_datorie'] * $numar_utilitati_cazare, 2) ?></span></td>
                        <td class="text" width="250" align="center" tip="<?= $c['text_datorie'] ?>"><?= $c['text_datorie'] ?></td>
                        <td class="text" width="30" align="center">
                            <?php echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>' ?>
                        </td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                            <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                            <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                        </td>
                    </tr>
                    <?php
                } ?>
            </table>
            <br/><br/>


            <?php
        }
        if (count($result_3) > 0) {
            ?>
            <!-- GARANTIE CAMIN (taxa per cazare) -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>GARANTIE CAMIN<br> (taxa per cazare)</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>
                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>
                <?php

                foreach ($result_3 AS $c) { ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text" width="10" align="center"><?= $i3++; ?></td>
                        <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" width="40" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                        <td class="text" width="250" align="center"><?= $c['text_datorie'] ?></td>
                        <td class="text" width="30" align="center">
                            <?php echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>' ?>
                        </td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                            <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                            <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <br/><br/>
        <?php
        }

        if (count($result_4) > 0) { ?>
            <!-- DEGRADARI BAZA MATERIALA CAMIN -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>Degradari baza materiala camin</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333; text-align:left;">&nbsp;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>
                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                foreach ($result_4 AS $c) { ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text" width="10" align="center"><?= $i4++; ?></td>
                        <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" width="40" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                        <td class="text" width="250" align="center"><?= $c['text_datorie'] ?></td>
                        <td class="text" width="30" align="center">
                            <?php echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>' ?>
                        </td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                            <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                            <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <br/><br/>
        <?php
        }

        if (count($result_5) > 0) { ?>
            <!-- ALTE TAXE -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc">
                    <td class="tablehead" colspan="3"><b>ALTE TAXE</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333; text-align:left;">&nbsp;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>
                <tr bgcolor="#2a87cc">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php

                foreach ($result_5 AS $c) { ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff">
                        <td class="text" width="10" align="center"><?= $i5++; ?></td>
                        <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" width="40" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                        <td class="text" width="250" align="center"><?= $c['text_datorie'] ?></td>
                        <td class="text" width="30" align="center">
                            <?php echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>' ?>
                        </td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                            <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                            <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$id_persoana.'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                        </td>
                    </tr>
                <?php } ?>

            </table>

        <?php
        }

    } else {
        //pus status_activ = 9 pe userul care il decazez
        $q_update_cazare = "
		UPDATE cs_persoane_camere 
		SET 
			status_activ = 9, 
			data_end = '".$data_decazare."' 
		WHERE id = $id_cazare 
		AND id_persoana = $id_persoana 
		AND status_activ = 1";
        //psql($q_update_cazare);

        $r_update_cazare = $db->Execute($q_update_cazare);

        // DACA TOATE DATORIILE SUNT LA ZI TREBUIE SA-I RETURNEZ GARANTIA

        // Garantie camin (taxa per cazare)
        $return_garantie = "
		SELECT * FROM cs_datorii 
		WHERE id_cazare='".$id_cazare."' 
		AND id_locatar='".$id_persoana."' 
		AND status_datorie=2
		AND tip_datorie=3 
		ORDER BY data_start ASC"; //psql($return_garantie);
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $return_g = $db->Execute($return_garantie);
        $r_garantie = $return_g->GetRows(); //pa($r_garantie);

        if (count($r_garantie) > 0) {
            echo '<br><br><div class="page_subtitle" align="center">Continua: <a tip="Returneaza garantia printr-o dispozitie de plata" class="big" href="'.$link_site.'index.php?page=incasari&section=dispozitie_de_plata&plata_step2=2&id_persoana='.$id_persoana.'">RETURNEAZA GARANTIA</a></div>';

        }

        // daca s-a facut incasari in avans afisez link catre dispozitie de plata ???

        echo '<div class="page_subtitle" align="center">Emite dispozitie de plata pentru eventualele chitante achitate in avans!</div>';

    }
}

// ADAUGA DETALII CAZARE LOCATAR ================================================================================================

if (($section == 'adauga_detalii_cazare_locatar') AND ($_POST['action'] != 'adauga_detalii_cazare_locatar_do')) {

    list($id_camera, $id_pat) = explode('-', $_GET['w']);

    //$s = get_persoana_camera_pat($id_camera, $id_pat); pa($s);

    $q = "SELECT * FROM cs_camere ORDER BY etaj ASC, numar ASC";
    $r = $db->Execute($q);
    $result = $r->GetAssoc();

    $q = "SELECT MAX(etaj) AS max FROM cs_camere";
    $r = $db->Execute($q);
    $max = $r->FetchRow(0);
    $max = $max['max'];

    $locatar = intval($_GET['locatar']);

    // am de lista de locatari necazati in cazul in care nu primesc ID_LOCATAR
    $lista_necazati = get_persoane_necazate();

    $lista_locatari_necazati = array();
    foreach ($lista_necazati as $ln) {
        $lista_locatari_necazati[$ln[0]] = $ln[1].' '.$ln[2];
    }

    ?>

    <div class="page_title" align="center">- Adauga detalii cazare -<br/><?php echo '<span style="color:#000">'.(get_persoana_dupa_id($locatar)).'</span>'; ?></div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <form method='post' action="">
        <!--<input type="hidden" name="section" value="ocupare_camere">-->
        <input type="hidden" name="action" value="adauga_detalii_cazare_locatar_do">
        <input type="hidden" name="id_camera" value="<?= $id_camera ?>">
        <table border=0 cellspacing=5 cellpadding=0 align="center" bgcolor='#a1c2e7' width="66%">

            <tr>
                <td class="text" colspan="4">&nbsp;</td>
            </tr>

            <!-- selecteaza locatar -->
            <?php
            if ($locatar == 0) { ?>
                <tr>
                    <td class="text" align="left">Selecteaza locatar:</td>
                    <td class=text align="left">
                        <select name="id_persoana" class="selectx2" onchange="" readonly><?= write_select($lista_locatari_necazati) ?></select>
                    </td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td class="text" align="left">Locatar:</td>
                    <td class=text align="left">
                        <input type="hidden" name="id_persoana" value="<?= $locatar ?>"><b><?= get_persoana_dupa_id($locatar); ?></b></td>
                </tr>
                <?php
            }
            ?>

            <tr>
                <td class="text" colspan="4"><br/><b>Alege loc in camera</b><?= get_camera_dupa_id($id_camera, null) ?></td>
            </tr>


            <tr>
                <td class="text" align="left" valign="top">Numar pat:</td>
                <td class=text align="left" id="txtHint">
                    <?php
                    $nr_paturi = get_nr_paturi_camera($id_camera);

                    for ($i = 1; $i <= $nr_paturi; $i++) {
                        $q1 = "
                            SELECT * 
                            FROM camin_studentesc.cs_persoane_camere 
                            WHERE 1 
                            AND id_camera = {$id_camera} 
                            AND nr_pat = {$i} 
                            AND status_activ IN (1) 
                            ORDER BY nr_pat ASC
                        ";
                        $result1 = $db->GetRow($q1);
                        //parr($result1);

                        $display .= '<div style="display:block; height:20px;';

                        if ($s['nr_pat'] == $i) {
                            $display .= ' font-weight:bold;';
                        }

                        $display .= ' "> Pat '.$i.' ';

                        if (($result1['id_persoana'] != '') AND ($result1['nr_pat'] != '')) {
                            $nume = get_persoana_dupa_id($result1['id_persoana']);
                            $display .= ' - '.$nume;
                        } else {
                            $display .= ' - <span style="color:#666"><input style="margin: 0px;" type="checkbox" name="nr_pat['.$i.']" ';
                            if ($id_pat == $i) {
                                $display .= ' CHECKED ';
                            }
                            $display .= '>LOC NEOCUPAT</span>';
                        }
                        $display .= '</div>'."\n";
                    }
                    echo $display;
                    ?>
                </td>
            </tr>

            <tr>
                <td class="titlu" colspan="4"><br/><b>Perioada cazare</b></td>
            </tr>

            <tr>
                <td class="text" align="left">Data inceput:</td>
                <td class=text align="left"><input type="text" class="input_100" size="20" name="data_start" id="txtDateRet" value="<?= date("d-m-Y") ?>" onclick="displayDatePicker('data_start', false, 'dmy', '-');" title="Alege data de inceput"/></td>
            </tr>

            <tr>
                <td class="text" align="left">Data final:</td>
                <td class=text align="left"><input type="text" class="input_100" size="20" name="data_end" id="txtDateRet" value="<?= date("d-m-Y"); ?>" onclick="displayDatePicker('data_end', false, 'dmy', '-');" title="Alege data de sfarsit"/></td>
            </tr>

            <tr>
                <td class="titlu" colspan="4"><br/><b>Alte detalii cazare</b></td>
            </tr>

            <tr>
                <td class="text" align="left" valign="top">Echipamente electrice:</td>
                <td class=text align="left">
                    <?php
                    $utilitati_here = get_utilitati();  //pa($utilitati_here);
                    $nu = count($utilitati_here) + 1;

                    $current_values = explode("|", $s['utilitati_cazare']);

                    ?>
                    <input type="checkbox" name="utilitati_cazare[0]" id="utilitati_cazare[0]" <?php echo ($s['utilitati_cazare'] == 0) ? ' CHECKED ' : '' ?>
                           onClick="if(this.checked) var nu=1; while(nu<=<?= $nu; ?>) { document.getElementById('ucid['+nu+']').disabled = true ; nu++} var nu2=1; if(!this.checked) while(nu2<=<?= $nu; ?>) { document.getElementById('ucid['+nu2+']').disabled = false; nu2++}"><label
                            for="utilitati_cazare[0]">FARA ECHIPAMENTE ELECTRICE</label><br/>

                    <?php
                    $for_id = 1;
                    foreach ($utilitati_here AS $value => $text) {
                        (in_array($value, $current_values)) ? $selected = 'CHECKED' : $selected = '';

                        echo '<input type="checkbox" name="utilitati_cazare['.$value.']" id="ucid['.$for_id.']" '.$selected.' onClick="if(this.checked) { document.getElementById(\'utilitati_cazare[0]\').disabled = true; }  if(!this.checked) { document.getElementById(\'utilitati_cazare[0]\').disabled = false;}"}><label for="utilitati_cazare['.$value.']">'.$text.'</label><br/>'."\n";
                        $for_id++;
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td class="text" align="left" valign="top">Alte detalii:</td>
                <td class=text align="left"><textarea class="textarea200" name="detalii_cazare"><?= $s['detalii_cazare'] ?></textarea></td>
            </tr>

            <tr>
                <td class="text" colspan="4">&nbsp;</td>
            </tr>

            <tr>
                <td align=left colspan=3><input type='submit' name="submit" class="button_albastru" value=' Adauga '><input type='reset' class="button_albastru" value='Renunta'></td>
            </tr>
        </table>
    </form>

    <?php
}

// ADAUGA DETALII CAZARE LOCATAR DO =============================================================================================

if ($_POST['action'] == 'adauga_detalii_cazare_locatar_do') {
    //pa($_POST);

    $clean['id_camera'] = $_POST['id_camera'];
    $clean['id_persoana'] = $_POST['id_persoana'];
    $clean['nr_pat'] = implode(',', array_keys($_POST['nr_pat'])); // asta se activeaza in cazul in care pentru un locatar se va cere sa se adauge direct mai multe paturi pana atunci punem doar unul
    $clean['nr_pat'] = substr($clean['nr_pat'], 0, 1);
    $clean['data_start'] = date4sql($_POST['data_start']);
    $clean['data_end'] = date4sql($_POST['data_end']);

    if ($_POST['utilitati_cazare'][0] != 'on') {
        $clean['utilitati_cazare'] = implode("|", array_keys($_POST['utilitati_cazare']));
    } else {
        $clean['utilitati_cazare'] = '0';
    }

    $clean['detalii_cazare'] = strip_tags($_POST['detalii_cazare']);

    $clean['status_activ'] = 1;

    //pa($clean);

    // verificari
    $check = 0;

    if ($clean['nr_pat'] == '') {
        $check++;
        $msg .= 'Numarul patului nu este corect !';
    }

    if ($clean['id_camera'] == '') {
        $check++;
        $msg .= 'Numarul camerei nu este corect !';
    }

    if ($clean['utilitati_cazare'] == '') {
        $check++;
        $msg .= 'Echipamentele electrice nu au fost selectate corect !';
    }

    // verific daca locatarul a fost deja adaugat
    $v_sql = "
		SELECT * 
		FROM cs_persoane_camere 
		WHERE 1
		AND id_persoana = ".$clean['id_persoana']." 
		AND id_camera = ".$clean['id_camera']." 
		AND nr_pat = ".$clean['nr_pat']."
		AND status_activ = 1";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $v_r = $db->Execute($v_sql);
    $v_res = $v_r->GetRows();   //pa($v_res);

    if (count($v_res) > 0) {
        $check++;
        $msg .= 'Locatarul este deja cazat in camera / patul selectat!';
    }

    // verific daca locatarul
    if ($check == 0) {

        $db->AutoExecute('cs_persoane_camere', $clean, 'INSERT');
        $id_cazare = $db->Insert_ID();
        $mesaj = 'Adaugarea detaliilor despre cazare a fost efectuata cu succes!';

        manage_utilitati($id_cazare, $id_locatar, array(), $clean['utilitati_cazare']);
        //manage_utilitati($id_cazare, $id_locatar, $utilitati_old, $utilitati_new)

    } else {
        $mesaj = 'Adaugarea detaliilor despre cazare nu a fost efectuata!';
    }

    //header('Location: '.$link_site.'?page=camere&section=ocupare_camere#'.$clean['id_camera'].'-'.$clean['nr_pat'].'');

    ?>

    <br/><br/><br/>
    <div class="page_subtitle" align="center"><?= $mesaj ?></div>
    <div class="msg" align="center"><?= $msg; ?></div>

    <?php
    if ($id_cazare) { ?>
        <div class="page_subtitle" align="center">Continua: <a tip='Genereaza datorii locatar' class="big" href="<?= $link_site ?>index.php?page=info_locatar&section=status_datorii&w=<?= $clean['id_persoana'] ?>">GENEREAZA DATORII LOCATAR</a>
        </div>
        <?php
    }
}

?>