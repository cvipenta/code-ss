<?php
/**
 * Description:
 * @Author:    Cristian Daniel Visan <cristian.visan@gmail.com>
 * Last change:    May 14, 2007
 *
 */

/* INCLUDES */
include "includes/functions.inc.php";
include "includes/cvit_cache.class.php";

// activ poate sa aiba urmatoarele valori:

// 0- locatar (cerere de cazare)
// 1- locatar activ
// 9- fost locatar

if (isset($_POST['action']) AND $_POST['action'] != '')
{
    $action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);
}

$section = 'lista_locatari';

if (isset($_GET['section']) AND $_GET['section'] != '')
{
    $section = filter_var($_GET['section'], FILTER_SANITIZE_STRING);
}


$cch = new cvit_cache();

//$cch->show_cache();

/* LISTA LOCATARI */

$easy_navigation = '
<tr height="22">
	<td colspan="4" align="center" style="font-size:12px;">
		<a href="#a" class="link_list">A</a>&nbsp;
		<a href="#b" class="link_list">B</a>&nbsp;
		<a href="#c" class="link_list">C</a>&nbsp;
		<a href="#d" class="link_list">D</a>&nbsp;
		<a href="#e" class="link_list">E</a>&nbsp;
		<a href="#f" class="link_list">F</a>&nbsp;
		<a href="#g" class="link_list">G</a>&nbsp;
		<a href="#h" class="link_list">H</a>&nbsp;
		<a href="#i" class="link_list">I</a>&nbsp;
		<a href="#j" class="link_list">J</a>&nbsp;
		<a href="#k" class="link_list">K</a>&nbsp;
		<a href="#l" class="link_list">L</a>&nbsp;
		<a href="#m" class="link_list">M</a>&nbsp;
		<a href="#n" class="link_list">N</a>&nbsp;
		<a href="#o" class="link_list">O</a>&nbsp;
		<a href="#p" class="link_list">P</a>&nbsp;
		<a href="#r" class="link_list">R</a>&nbsp;
		<a href="#s" class="link_list">S</a>&nbsp;
		<a href="#&#351;" class="link_list">&#350;</a>&nbsp;
		<a href="#t" class="link_list">T</a>&nbsp;
		<a href="#&#355;" class="link_list">&#354;</a>&nbsp;
		<a href="#u" class="link_list">U</a>&nbsp;
		<a href="#v" class="link_list">V</a>&nbsp;
		<a href="#y" class="link_list">Y</a>&nbsp;
		<a href="#z" class="link_list">Z</a>
	</td>				
</tr>';

if ($section == 'lista_locatari')
{

    if (in_array($_GET['s'], array('actuali', 'necazati', 'decazati')))
    {
        $s = $_GET['s'];
    }
    else
    {
        $s = 'actuali';
    }

    switch ($s)
    {
        case 'actuali':
            $s_details = '(Locatari cazati)';
            break;
        case 'necazati':
            $s_details = '(Locatari cu fisa completata dar necazati)';
            break;
        case 'decazati':
            $s_details = '(Locatari carora li s-a emis fisa de decazare)';
            break;
    }

    /* selectez locatarii care au statusul activ (nu au fost trecuti manual in lista de locatari inactivi din varii motive. in baza de date acest lucru este retinut in campul cs_persoane.activ)
    + si care au statusul de locatar activ cs_persoane_camere.status_activ
    */

    if ($cch->is_cached('lista_cazati') && 0)
    {
        $result = $cch->get_cache('QueryResult_lista_cazati');
        $lista_cazati = $cch->get_cache('lista_cazati');
    }
    else
    {

        $q = "
 			SELECT 
 				cs_persoane.id,
 				cs_persoane.id AS id,
 				cs_persoane.nume, 
 				cs_persoane.nume_casatorie, 
 				cs_persoane.prenume, 
 				cs_persoane.init, 
 				cs_persoane_camere.data_start, 
 				cs_persoane_camere.data_end, 
 				cs_persoane_camere.id_camera, 
 				cs_persoane_camere.nr_pat, 
 				cs_persoane_camere.status_activ   
	 		FROM cs_persoane 
	 		INNER JOIN cs_persoane_camere ON cs_persoane_camere.id_persoana = cs_persoane.id
	 		AND cs_persoane.activ=1 
	 		AND cs_persoane_camere.status_activ IN (1, 2, 3)
	 		ORDER BY nume ASC, prenume ASC"; //psql($q);

        $r = $db->Execute($q);
        $result = $r->GetAssoc(); //pa($result);

        $lista_cazati = array_keys($result);
        //pa($lista_cazati);

        $cch->to_cache('QueryResult_lista_cazati', $result);
        $cch->to_cache('lista_cazati', $lista_cazati);
    }

    $locatari_cazati_activi = implode(",", $lista_cazati);

    /* selectez locatarii care au statusul activ (nu au fost trecuti manual in lista de locatari inactivi din varii motive. in baza de date acest lucru este retinut in campul cs_persoane.activ)
    + si care au statusul de locatar DECAZAT cs_persoane_camere.status_activ=9
    */
    $q_diff = "
	 		SELECT 
	 			cs_persoane.id, 
	 			cs_persoane.nume, 
	 			cs_persoane.nume_casatorie, 
	 			cs_persoane.prenume, 
	 			cs_persoane.init, 
	 			cs_persoane_camere.data_start, 
	 			cs_persoane_camere.data_end  
	 		FROM cs_persoane 
	 		INNER JOIN cs_persoane_camere ON  cs_persoane_camere.id_persoana = cs_persoane.id
	 		WHERE 
	 			cs_persoane.activ=1 
	 		AND cs_persoane_camere.status_activ IN (9)
	 		AND cs_persoane_camere.id_persoana NOT IN ($locatari_cazati_activi)
	 		ORDER BY nume ASC, prenume ASC";

    $r_diff = $db->Execute($q_diff);
    $result_diff = $r_diff->GetAssoc();

    /*echo '<br>DECAZATI:'.*/
    $locatari_decazati = implode(",", array_keys($result_diff));

    /*echo '<br>NECAZATI:'.*/
    $locatari_existenti_in_sistem = $locatari_cazati_activi . ',' . $locatari_decazati;

    /* selectez locatarii care au statusul activ dar care NU AU FOST CAZATI = NECAZATI */
    $q_nec = "
        SELECT 
            cs_persoane.id, 
            cs_persoane.nume, 
            cs_persoane.nume_casatorie, 
            cs_persoane.prenume,
            cs_persoane.init
        FROM cs_persoane 
        WHERE 
            cs_persoane.activ=1 
        AND cs_persoane.id NOT IN ($locatari_existenti_in_sistem)
        ORDER BY cs_persoane.nume ASC, cs_persoane.prenume ASC";
    $r_nec = $db->Execute($q_nec);
    $result_nec = $r_nec->GetRows(); //pa($result_nec);


    $i = 1;

    ?>

    <div class="page_title" align="center">LISTA LOCATARI <?= strtoupper($s) ?></div>
    <div class="page_subtitle" align="center"><?= $s_details; ?></div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <table align="center" border="0" cellpadding="0" cellspacing="1" width="99%">

        <tr height="22">
            <td colspan="2">&bull; <a tip="Plan general de ocupare a camerelor" href="<?= $link_site . 'index.php?page=camere&section=ocupare_camere'; ?>" class="link_list">Plan general de ocupare a camerelor</a><br/>
            </td>
        </tr>

        <?= $easy_navigation; ?>
        <?php
        if ($s == 'actuali')
        { ?>

            <tr bgcolor="#2a87cc" height="22">
                <td class="tablehead">Nr.</td>
                <td class="tablehead">Nume si prenume / status</td>
                <td class="tablehead">Perioada cazare</td>
                <td class="tablehead">Actiuni</td>
            </tr>
            <?php
            foreach ($result AS $id => $c)
            {
                $c['id'] = $id;

                if ($cch->is_cached('get_infocazare_idpersoana_' . $id))
                {
                    $info = $cch->get_cache('get_infocazare_idpersoana_' . $id);
                }
                else
                {
                    $info = get_infocazare_idpersoana($id);
                    $cch->to_cache('get_infocazare_idpersoana_' . $id, $info);
                }

                ?>

                <!-- Lista locatari activi -->
                <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                    <td class="text" width="10" align="center"><?= $i++; ?></td>
                    <td class="text" style="padding-left:5px" width="345" align="left"><a href="#" name="<?= strtolower(substr($c['nume'], 0, 1)) ?>">
                            <a tip="Vizualizeaza detalii locatar - ref. int. <?= $c['id'] ?>" href="<?= $link_site . '?page=info_locatar&section=detalii_locatar&w=' . $c['id']; ?>"
                               class="link"><?= $c['nume'] . ' ' . $c['init'] . ' ' . $c['prenume'] ?><?php echo(trim($c['nume_casatorie'] != '') ? '(' . $c['nume_casatorie'] . ')' : '') ?></a> [<?= $c['id'] ?>]<?= get_camera_dupa_id($c['id_camera'], $nimic); ?>
                            <?php
                            $text_extra_cazare = ($c['status_activ'] == 2) ? ' pe vara' : '';
                            $text_extra_cazare = ($c['status_activ'] == 3) ? ' loc neocupat pe perioada verii' : '';

                            echo (in_array($c['id'], $lista_cazati)) ? ' <span class="green">[cazat' . $text_extra_cazare . ']</span>' : ' <span class="red">[necazat]</span>&nbsp; &rsaquo; <a class="link" href="' . $link_site . '?page=camere&section=ocupare_camere">cazeaza</a>' ?>
                    </td>

                    <td class="text" width="180" align="center"><?= date4html($c['data_start']); ?> -> <?= date_alarm(date4html($c['data_end']), 7); ?></td>
                    <td class="text" width="200" align="center">
                        <a tip="Editeaza detalii fisa locatar" href="<?= $link_site . '?page=locatari&section=editeaza_locatar&w=' . $c['id']; ?>" class="link12">Fisa locatar</a> |
                        <a tip="Editeaza detalii cazare locatar" href="<?= $link_site . '?page=camere&section=editeaza_detalii_cazare&w=' . $c['id_camera'] . '-' . $c['nr_pat'] . '-' . $c['id']; ?>" class="link12">Detalii cazare</a>
                    </td>
                </tr>
                <?php
                if (($i % 50) == 0) echo $easy_navigation;
            }
        }
        ?>


        <!-- Lista locatari necazati -->
        <?php
        if ($s == 'necazati')
        {
            ?>

            <tr bgcolor="#2a87cc" height="22">
                <td class="tablehead">Nr.</td>
                <td class="tablehead">Nume si prenume</td>
                <td class="tablehead">Marca</td>
                <td class="tablehead"> Status / Cazare</td>
                <td class="tablehead">Actiuni</td>
            </tr>
            <?php
            foreach ($result_nec AS $id => $c)
            { ?>
                <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                    <td class="texttable" width="10" align="center"><?= $i++; ?></td>
                    <td class="texttable" style="padding-left:5px" width="320" align="left">
                        <a tip="Vizualizeaza detalii locatar" href="<?= $link_site . 'index.php?page=info_locatar&section=detalii_locatar&w=' . $c['id']; ?>"
                           class="link"><?= $c['nume'] . ' ' . $c['init'] . ' ' . $c['prenume'] ?><?php echo(trim($c['nume_casatorie'] != '') ? '(' . $c['nume_casatorie'] . ')' : '') ?></a>
                    </td>
                    <td class="texttable" width="30" align="center"><?php echo $c['id']; ?></td>
                    <td class="text" width="200"
                        align="center"><?php echo (in_array($c['id'], $lista_cazati)) ? ' <span class="green">[cazat]</span>' : ' <span class="red">[necazat]</span>&nbsp; &rsaquo; <a class="link" href="' . $link_site . 'index.php?page=camere&section=ocupare_camere&locatar=' . $c['id'] . '">cazeaza</a>' ?></td>
                    <td class="texttable" width="100" align="center"><a tip="Editeaza detalii locatar" href="<?= $link_site . 'index.php?page=locatari&section=editeaza_locatar&w=' . $c['id']; ?>" class="link">Editeaza</a></td>
                </tr>
            <?php }
        } ?>

        <!-- Lista locatari decazati -->
        <?php
        if ($s == 'decazati')
        { ?>

            <tr bgcolor="#2a87cc" height="22">
                <td class="tablehead">Nr.</td>
                <td class="tablehead">Nume si prenume</td>
                <td class="tablehead">Data ultimei decazari</td>
                <td class="tablehead">Perioada cazarii curente</td>
                <td class="tablehead">Actiuni</td>
            </tr>
            <?php
            foreach ($result_diff AS $id => $c)
            {

                $c['id'] = $id;

                $info = get_infocazare_idpersoana($c['id']);
                //echo count($info);
                //pa($info);

                if ($info[0]['data_end'] < date("Y-m-d")):
                    $status_cazare = '';
                    $row_bgcolor = '#E6F4FF';
                else:
                    $status_cazare = 'cazat pana la: ';
                    $row_bgcolor = '#E6E6FF';
                endif;
                ?>
                <tr style="background-color: <?= $row_bgcolor; ?>" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='<?= $row_bgcolor; ?>'" bgcolor="<?= $row_bgcolor; ?>" height="22">
                    <td class="texttable" width="10" align="center"><?= $i++; ?></td>
                    <td class="texttable" style="padding-left:10px" width="300" align="left">
                        <a tip="Vizualizeaza detalii locatar" href="<?= $link_site . 'index.php?page=info_locatar&section=detalii_locatar&w=' . $c['id']; ?>"
                           class="link"><?= $c['nume'] . ' ' . $c['init'] . ' ' . $c['prenume'] ?><?php echo(trim($c['nume_casatorie'] != '') ? '(' . $c['nume_casatorie'] . ')' : '') ?> [<?= $c['id'] ?>]</a>

                    </td>

                    <td class="text" width="100" align="center"><!--<?= date4html($c['data_start']); ?> -> --><?= $status_cazare ?><?= date_alarm(date4html($c['data_end']), 7); ?></td>
                    <td class="text" width="180" align="center">
                        <?php if (count($info) > 1)
                        {
                            echo date4html($info[1]['data_start']) . ' > ' . date_alarm(date4html($info[1]['data_end']), 7);
                        }
                        ?>
                    </td>
                    <td class="texttable" width="100" align="center"><a tip="Editeaza detalii locatar" href="<?= $link_site . '?page=locatari&section=editeaza_locatar&w=' . $c['id']; ?>" class="link">Editeaza</a></td>
                </tr>

                <?php
                if (($i % 50) == 0) echo $easy_navigation;
            }
        } ?>

        <?= $easy_navigation; ?>

    </table>
    <?php
}

/* LISTA CERERI CAZARE */
if ($section == 'lista_cereri_cazare') {

    $q = "SELECT id, nume, nume_casatorie, prenume FROM cs_persoane WHERE activ=0 ORDER BY nume ASC, prenume ASC";
    $r = $db->Execute($q);
    $result = $r->GetAssoc();
    $i = 1;
    ?>

    <div class="page_title" align="center">LISTA CERERI DE CAZARE</div>
    <div class="page_subtitle" align="center">(Locatari cu fisa completata partial - necazati)</div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <table align="center" border="0" cellpadding="0" cellspacing="1" width="85%">

        <tr bgcolor="#2a87cc" height="22">
            <td class="tablehead">Nr.</td>
            <td class="tablehead">Nume si prenume</td>
            <td class="tablehead">Actiuni</td>
        </tr>

        <?php foreach ($result AS $c) { ?>
            <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                <td class="texttable" width="10" align="center"><?= $i++; ?></td>
                <td class="texttable" style="padding-left:10px" width="350" align="left">
                    <a title="Vizualizeaza detalii locatar" href="<?= $link_site.'?page=info_locatar&section=detalii_locatar&w='.$c['id']; ?>" class="link"><?= $c['nume'].' '.$c['prenume'] ?><?php echo(trim($c['nume_casatorie'] != '') ? '('.$c['nume_casatorie'].')' : '') ?></a></td>
                <td class="texttable" width="100" align="center"><a title="Editeaza detalii locatar" href="<?= $link_site.'?page=locatari&section=editeaza_locatar&w='.$c['id']; ?>" class="link">Editeaza</a></td>
            </tr>
            <?php
            if (($i % 50) == 0) {
                echo $easy_navigation;
            }
        } ?>
        <tr>
            <td colspan="3"><br/></td>
        </tr>
    </table>
    <?php
}

if (($section == 'adauga_locatar') AND ($_POST['action'] != 'adauga_locatar_do')) {

    if (($_GET['w'] != '') AND substr_count($_GET['w'], "-") == 1) {
        $idcamera_idpat = $_GET['w'];

        list($id_camera, $id_pat) = explode('-', $idcamera_idpat);

        if (!is_numeric($id_camera) OR ($id_camera < 1)) {
            $_REQUEST['msg'] = 'Numarul camerei nu este corect !';
        }
        if (!is_numeric($id_pat) OR ($id_pat < 1)) {
            $_REQUEST['msg'] = 'Numarul patului nu este corect !';
        }
    } else {
        $_REQUEST['msg'] = 'Va rugam ca pentru adaugarea locatarului sa folositi<br/> "PLAN DE CAMERE" > "ADAUGA LOCATAR"';
    }

    ?>


    <div class="page_title" align="center">Adaugare detalii locatar</div>
    <div class="page_subtitle" align="center">PAS 1: Completare fisa locatar</div>
    <div class="msg" align="center" id="msg"><?= $_REQUEST['msg']; ?></div>

    <form name="form2" id="form2" method='post' action="">
        <input type="hidden" name="action" value="adauga_locatar_do">
        <input type="hidden" name="idcamera_idpat" value="<?= $idcamera_idpat ?>">


        <table width="80%" border="0" cellspacing="5" cellpadding="0" align="center">
            <tr>
                <td width="10">&nbsp;</td>
                <td width="130" class="text_insc">Tip persoana</td>
                <td class="text_insc">
                    <select name="tip_persoana" class="selectx2" tabindex="1">
                        <<?= write_select($arr_tip_persoana, $res['tip_persoana']) ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Numele (inainte de casatorie)</td>
                <td class="text_insc"><input name="nume" type="text" class="input_insc_mare" id="nume" tabindex="2" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Initiala</td>
                <td class="text_insc">
                    <input name="init" type="text" class="input_100" id="init" size="6" maxlength="6" value="<?= $res['init'] ?>">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Prenumele</td>
                <td class="text_insc"><input name="prenume" type="text" class="input_insc_mare" id="prenume" tabindex="3" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Numele dupa casatorie</td>
                <td class="text_insc"><input name="name_casat" type="text" class="input_insc_mare" id="name_casat" tabindex="4" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Localitate</td>
                <td class="text_insc"><input name="localitate" type="text" class="input_insc_mare" id="localitate" tabindex="5" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Judet</td>
                <td class="text_insc"><select name="judet" class="select_free" id="judet" tabindex="6"><?= write_select($arr_judete); ?></select></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Strada si numar</td>
                <td class="text_insc"><input name="strada" type="text" class="input_insc_mare" id="strada" tabindex="7" value=""></td>
            </tr>


            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Detalii adresa</td>
                <td class="text_insc"><input name="detalii_adresa" type="text" class="input_insc_mare" id="detalii_adresa" tabindex="8" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Telefoane</td>
                <td class="text_insc"><input name="telefoane" type="text" class="input_insc_mare" id="telefoane" tabindex="9" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Email</td>
                <td class="text_insc"><input name="email" type="text" class="input_insc_mare" id="email" tabindex="10" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">CNP</td>
                <td class="text_insc"><input name="cnp" class="input_insc_mare" tabindex="11" value="" size="13" maxlength="13"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Serie / Numar</td>
                <td class="text_insc">
                    <input name="ci_serie" type="text" class="input_50" id="ci_serie" size="2" maxlength="2" tabindex="12" value="">
                    <input name="ci_numar" type="text" class="input_100" id="ci_numar" size="6" maxlength="6" tabindex="13" value="">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Student</td>
                <td class="text_insc">
                    Da&nbsp;<input name="student_status" value="1" type="radio">&nbsp;&nbsp;
                    Nu&nbsp;<input name="student_status" value="0" checked="checked" type="radio">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Universitatea</td>
                <td class="text_insc"><select name="student_universitate" type="text" class="selectx2" id="student_universitate" tabindex="14" onChange="showFacultate(this.value); document.getElementById('student_facultate').disabled = false ">
                        <option value="0" readonly>&rsaquo; alege universitate</option><?= write_select(get_universitati()); ?></select></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Facultatea</td>
                <td class="text_insc" id="txtHint"><input style="margin: 0px;" type="checkbox" id="student_facultate" disabled readonly> ALEGE FACULTATEA</td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Alta categorie</td>
                <td class="text_insc">
                    Da&nbsp;<input name="job_status" value="1" type="radio">&nbsp;&nbsp;
                    Nu&nbsp;<input name="job_status" value="0" checked="checked" type="radio">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Date Suplimentare</td>
                <td class="text_insc"><input name="job_companie" type="text" class="input_insc_mare" id="job_companie" tabindex="16" value=""></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc" valign="top">Status locatar</td>
                <td class="text_insc" style="text-align:left">
                    <!--<input type="radio" name="activ" value="0" checked><b>Cerere de cazare</b><br/>-->
                    <input type="radio" name="activ" value="1" checked>Locatar activ<br/>
                    <!--<input type="radio"name="activ" value="9" disabled >Fost locatar (inactiv)<br/>-->
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc"></td>
                <td class="text_insc" style="text-align:left"><input type="button" name="add_edit" id="add_edit" onclick="submit_add_edit_locatar();return false;" class="button_alb" value='Adauga'></td>
            </tr>

        </table>
    </form>

    <?php
}

/* ADAUGA LOCATAR DO */
if ($_POST['action'] == 'adauga_locatar_do')
{

    $clean['tip_persoana'] = $_POST['tip_persoana'];
    $clean['nume'] = $_POST['nume'];
    $clean['init'] = $_POST['init'];
    $clean['nume_casatorie'] = $_POST['nume_casatorie'];
    $clean['prenume'] = $_POST['prenume'];
    $clean['localitate'] = $_POST['localitate'];
    $clean['judet'] = $_POST['judet'];
    $clean['strada'] = $_POST['strada'];
    $clean['detalii_adresa'] = $_POST['detalii_adresa'];
    $clean['cnp'] = $_POST['cnp'];
    $clean['serie_nr'] = $_POST['ci_serie'] . '#' . $_POST['ci_numar'];
    $clean['telefoane'] = $_POST['telefoane'];
    $clean['email'] = $_POST['email'];
    $clean['student_status'] = $_POST['student_status'];
    $clean['student_universitate'] = $_POST['student_universitate'];
    $clean['student_facultate'] = serialize($_POST['student_facultate']);
    $clean['job_status'] = $_POST['job_status'];
    $clean['job_companie'] = $_POST['job_companie'];
    $clean['job_functie'] = $_POST['job_functie'];
    $clean['activ'] = 1; // default 1 $_POST['activ'];
    $clean['nt_student_id'] = $_POST['nt_student_id'];

    // validari inaintea INSERT-ului
    $check = 0;

    if (strlen($clean['nume']) == 0) {
        $check++;
        $msg .= 'Campul nume este invalid !';
        //header('Location: '.$link_site.'?page=locatari&section=adauga_locatar&msg=Campul nume este invalid');
    }

    if (strlen($clean['prenume']) == 0) {
        $check++;
        $msg .= 'Campul nume este invalid !';
        //header('Location: '.$link_site.'?page=locatari&section=adauga_locatar&msg=Campul prenume este invalid');
    }

    // verificam daca CNP-ul exista in baza de date si daca are 13 cifre
    $cnp_info = check_cnp($clean['cnp']);

    if ($cnp_info[0] == 0) {
        $check++;
        $msg .= $cnp_info[1];
    }

    // verificam daca la adaugarea locatarului s-a plecat de la 'Plan camere > Adaugare locatar'
    if (($_POST['idcamera_idpat'] != '') and substr_count($_POST['idcamera_idpat'], "-") == 1) {
        $idcamera_idpat = $_POST['idcamera_idpat'];
    } else {
        $msg .= 'Va rugam ca pentru adaugarea locatarului sa folositi<br/> "PLAN DE CAMERE" > "ADAUGA LOCATAR"';
    }

    //pa($clean);
    $id_locatar = 0;
    if ($check == 0) {
        $db->AutoExecute('cs_persoane', $clean, 'INSERT');
        $id_locatar = $db->Insert_ID();

        $mesaj = 'Adaugarea locatarului a fost efectuata cu succes!';
    } else {
        $mesaj = 'Adaugarea NU a fost efectuata!';
    }

    //echo $id_locatar;
    ?>

    <div class="page_subtitle" align="center"><?= $mesaj ?></div>
    <div class="msg" align="center"><?= $msg; ?></div>

    <?php if ($id_locatar) { ?>
        <br><br>
        <div class="page_subtitle" align="center">
            Continua: <a tip='Continua cazarea locatarului' class="big"
                         href="<?= $link_site ?>index.php?page=camere&section=adauga_detalii_cazare_locatar&w=<?= $idcamera_idpat; ?>&locatar=<?= $id_locatar ?>">ADAUGA
                DETALII CAZARE LOCATAR</a>
        </div>
        <?php
    }
}


if ($section == 'editeaza_locatar')
{
    $q = "SELECT * FROM cs_persoane  WHERE id='" . $_GET['w'] . "'";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $res = $db->GetRow($q);

    $ci = explode('#', $res['serie_nr']);
    $res['ci_serie'] = $ci[0];
    $res['ci_numar'] = $ci[1];

    // la ce facultati este omul nostru
    $res['student_facultate'] = unserialize($res['student_facultate']);
    if (is_array($res['student_facultate']) && count($res['student_facultate']) > 0) {
        $res['student_facultate'] = array_keys($res['student_facultate']);
    }

    /* FACULTATILE */
    $f_q = "SELECT * FROM cs_universitati WHERE id_universitate='".$res['student_universitate']."'";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $f_r = $db->Execute($f_q);
    $f_res = $f_r->GetRows();  // pa($f_res);

    if (count($f_res) > 0) {
        foreach ($f_res AS $fc) {
            if (@in_array($fc['id'], $res['student_facultate'])) {
                $fac_display .= '<div style="height:20px; "><input style="margin: 0;" type="checkbox" name="student_facultate['.$fc['id'].']" CHECKED> '.substr($fc['facultate'], 2)."</div>\n";
            } else {
                $fac_display .= '<div style="height:20px; "><input style="margin: 0;" type="checkbox" name="student_facultate['.$fc['id'].']"> '.substr($fc['facultate'], 2)."</div>\n";
            }
        }
    } else {
        $fac_display .= '<span class="red">Nu sunt introduse facultati !</span>';
    }

    ?>

    <div class="page_title" align="center">Editare detalii locatar</div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <form name="form2" method='post' action="<?= $link_site . '?page=locatari' ?>">
        <input type="hidden" name="action" value="editeaza_locatar_do">
        <input type="hidden" name="id" value="<?= $res['id']; ?>">

        <table width="80%" border="0" cellspacing="5" cellpadding="0" align="center">
            <tr>
                <td width="10">&nbsp;</td>
                <td width="130" class="text_insc" tip="Selectati cu atentie tipul persoanei pentru ca in functie de aceasta<br> alegere va fi taxat ca 'locatar student' sau ca 'locatar angajat'">Tip persoana</td>
                <td class="text_insc">
                    <select name="tip_persoana" class="selectx2" tabindex="1">
                        <?= write_select($arr_tip_persoana, $res['tip_persoana']) ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Numele (inainte de casatorie)</td>
                <td class="text_insc"><input name="nume" type="text" class="input_insc_mare" id="nume" tabindex="2" value="<?= $res['nume'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Initiala</td>
                <td class="text_insc">
                    <input name="init" type="text" class="input_100" id="init" size="6" maxlength="6" value="<?= $res['init'] ?>">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Prenumele</td>
                <td class="text_insc"><input name="prenume" type="text" class="input_insc_mare" id="prenume" tabindex="3" value="<?= $res['prenume'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Numele dupa casatorie</td>
                <td class="text_insc"><input name="nume_casatorie" type="text" class="input_insc_mare" id="nume_casatorie" tabindex="4" value="<?= $res['nume_casatorie'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Localitate</td>
                <td class="text_insc"><input name="localitate" type="text" class="input_insc_mare" id="localitate" tabindex="5" value="<?= $res['localitate'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Judet</td>
                <td class="text_insc">
                    <select name="judet" class="selectx2" id="judet" tabindex="6"><?= write_select($arr_judete, $res['judet']); ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Strada si numar</td>
                <td class="text_insc"><input name="strada" type="text" class="input_insc_mare" id="strada" tabindex="7" value="<?= $res['strada'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Detalii adresa</td>
                <td class="text_insc"><input name="detalii_adresa" type="text" class="input_insc_mare" id="detalii_adresa" tabindex="8" value="<?= $res['detalii_adresa'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Telefoane</td>
                <td class="text_insc"><input name="telefoane" type="text" class="input_insc_mare" id="telefoane" tabindex="9" value="<?= $res['telefoane'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Email</td>
                <td class="text_insc"><input name="email" type="text" class="input_insc_mare" id="email" tabindex="10" value="<?= $res['email'] ?>"></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">CNP</td>
                <td class="text_insc"><input name="cnp" class="input_insc_mare" tabindex="11" onChange="extract_cnp(document.form2.cnp.value);" onKeyUp="extract_cnp(document.form2.cnp.value);" value="<?= $res['cnp'] ?>" size="13" maxlength="13" onLostFocus="extract_cnp(document.form2.cnp.value);">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Serie / Numar</td>
                <td class="text_insc">
                    <input name="ci_serie" type="text" class="input_50" id="ci_serie" size="2" maxlength="2" tabindex="12" value="<?= $res['ci_serie'] ?>">
                    <input name="ci_numar" type="text" class="input_100" id="ci_numar" size="6" maxlength="6" tabindex="13" value="<?= $res['ci_numar'] ?>">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Student</td>
                <td class="text_insc">
                    Da&nbsp;<input name="student_status" value="1" <?php echo ($res['student_status'] == 1) ? 'checked="checked"' : '' ?> type="radio">&nbsp;&nbsp;
                    Nu&nbsp;<input name="student_status" value="0" <?php echo ($res['student_status'] == 0) ? 'checked="checked"' : '' ?> type="radio">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Universitatea</td>
                <td class="text_insc">
                    <select name="student_universitate" type="text" class="selectx2" id="student_universitate" tabindex="14" onChange="showFacultate(this.value, <?= $res['id']; ?>); document.getElementById('student_facultate').disabled = false ">
                        <?= write_select(get_universitati(), $res['student_universitate']); ?></select>
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Facultatea</td>
                <td class="text_insc" id="txtHint"><?= $fac_display; ?></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Alta categorie</td>
                <td class="text_insc">
                    Da&nbsp;<input name="job_status" value="1" <?php echo ($res['job_status'] == 1) ? 'checked="checked"' : '' ?> type="radio">&nbsp;&nbsp;
                    Nu&nbsp;<input name="job_status" value="0" <?php echo ($res['job_status'] == 0) ? 'checked="checked"' : '' ?> type="radio">
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc">Date Suplimentare</td>
                <td class="text_insc"><textarea name="job_companie" type="text" id="countable250" class="textarea200"><?= $res['job_companie'] ?></textarea>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc" valign="top">Status locatar</td>
                <td class="text_insc" style="text-align:left">
                    <input type="radio" name="activ" value="1" <?php echo ($res['activ'] == 1) ? 'CHECKED ><b>Locatar activ</b><br>' : '>Locatar activ<br>'; ?>
                    <input type="radio" name="activ" value="9" <?php echo ($res['activ'] == 9) ? 'CHECKED ><b>Fost locatar (inactiv)</b><br/>' : '>Fost locatar (inactiv)<br/>'; ?>
                </td>
            </tr>

            <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><br/></td>
            </tr>


            <tr>
                <td width="10">&nbsp;</td>
                <td class="text_insc"></td>
                <td>
                    <!-- <input type="button" class="button_alb" tabindex="43" value="Salveaza" onclick="if(submit_login()){ document.form2.fisainscr.value='Salveaza';document.form2.submit();}">&nbsp;&nbsp;&nbsp;&nbsp;
                  <input name="tip" type="button" class="button_alb" id="tip2" tabindex="46" onClick="window.open('tiparire.php?view=1&id=');" value="Tipareste">-->
                    <input type='submit' name="submit" class="button_alb" value='Modifica'></td>
            </tr>
        </table>
    </form>
    <?php
}

/* EDITEAZA LOCATAR DO */
if ($_POST['action'] == 'editeaza_locatar_do')
{
    $clean['tip_persoana'] = $_POST['tip_persoana'];
    $clean['nume'] = $_POST['nume'];
    $clean['init'] = $_POST['init'];
    $clean['nume_casatorie'] = $_POST['nume_casatorie'];
    $clean['prenume'] = $_POST['prenume'];
    $clean['localitate'] = $_POST['localitate'];
    $clean['judet'] = $_POST['judet'];
    $clean['strada'] = $_POST['strada'];
    $clean['detalii_adresa'] = $_POST['detalii_adresa'];
    $clean['cnp'] = $_POST['cnp'];
    $clean['serie_nr'] = $_POST['ci_serie'] . '#' . $_POST['ci_numar'];
    $clean['telefoane'] = $_POST['telefoane'];
    $clean['email'] = $_POST['email'];
    $clean['student_status'] = $_POST['student_status'];
    $clean['student_universitate'] = $_POST['student_universitate'];
    $clean['student_facultate'] = serialize($_POST['student_facultate']);
    $clean['job_status'] = $_POST['job_status'];
    $clean['job_companie'] = $_POST['job_companie'];
    $clean['job_functie'] = $_POST['job_functie'];
    $clean['activ'] = $_POST['activ'];
    $clean['nt_student_id'] = $_POST['nt_student_id'];

    //pa($clean);

    switch ($clean['activ'])
    {
        //case 0: $redirect = 'lista_cereri_cazare'; break;
        case 1:
            $redirect = 'lista_locatari';
            break;
        case 9:
            $redirect = 'lista_locatari_inactivi';
            break;
    }

    $db->AutoExecute('cs_persoane', $clean, 'UPDATE', "id = " . $_POST['id'] . "");
    header('Location: ' . $link_site . '?page=locatari&section=' . $redirect . '#' . $_POST['id'] . '');
}

//=================================================================================================
// LISTA LOCATARI INACTIVI 
//=================================================================================================

if ($section == 'lista_locatari_inactivi')
{

    if (isset($_REQUEST['start']))
    {
        $start_letter = $_REQUEST['start'];
    }
    else
    {
        $start_letter = str_shuffle("ABCDEFGHIJKLMNOPRSŞTŢUVYZ")[0];
    }

    $easy_navigation2 = '
<table align="center" border="0" cellpadding="0" cellspacing="1" width="85%">
<tr height="22">
	<td align="center" style="font-size:12px;">
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=all" class="link_list">Toti</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=a" class="link_list">A</a>
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=b" class="link_list">B</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=c" class="link_list">C</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=d" class="link_list">D</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=e" class="link_list">E</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=f" class="link_list">F</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=g" class="link_list">G</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=h" class="link_list">H</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=i" class="link_list">I</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=j" class="link_list">J</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=k" class="link_list">K</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=l" class="link_list">L</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=m" class="link_list">M</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=n" class="link_list">N</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=o" class="link_list">O</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=p" class="link_list">P</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=r" class="link_list">R</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=s" class="link_list">S</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=&#351;" class="link_list">&#350;</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=t" class="link_list">T</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=&#355;" class="link_list">&#354;</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=u" class="link_list">U</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=v" class="link_list">V</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=y" class="link_list">Y</a>&nbsp;
		<a href="LINK_SITE?page=locatari&section=lista_locatari_inactivi&start=z" class="link_list">Z</a>
	</td>
</tr>
</table>';

    $query_condition = '';

    if ($start_letter != 'all')
    {
        $query_condition = "AND nume LIKE '{$start_letter}%'";
    }

    $q = "SELECT id, nume, nume_casatorie, prenume
          FROM cs_persoane
          WHERE activ = 9
          {$query_condition}
          ORDER BY nume ASC, prenume ASC
    ";
    $result = getDb()->Execute($q)->GetAssoc();

    $i = 1;
    ?>

    <div class="page_title" align="center">ARHIVA LOCATARI (lista fosti locatari)</div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <?php
    echo str_replace('LINK_SITE', $link_site, $easy_navigation2);
    ?>

    <table align="center" border="0" cellpadding="0" cellspacing="1" width="85%">

        <tr bgcolor="#2a87cc" height="22">
            <td class="tablehead">Nr.</td>
            <td class="tablehead">Nume si prenume</td>
            <td class="tablehead">Actiuni</td>
        </tr>

        <?php

        foreach ($result AS $id => $c)
        {

            $c['id'] = $id;

            ?>
            <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                <td class="texttable" width="10" align="center"><?= $i++; ?><a href="#" name="<?php echo strtolower(substr($c['nume'], 0, 1)) ?>">
                </td>
                <td class="texttable" style="padding-left:10px" width="350" align="left">
                    <a title="Vizualizeaza detalii locatar"
                       href="<?= $link_site . '?page=info_locatar&section=detalii_locatar&w=' . $c['id']; ?>"
                       class="link"><?= $c['nume'] . ' ' . $c['prenume'] . ' [' . $c['id'] . ']' ?><?php echo(trim($c['nume_casatorie'] != '') ? '(' . $c['nume_casatorie'] . ')' : '') ?></a>
                </td>
                <td class="texttable" width="100" align="center">
                    <a title="Editeaza detalii locatar" href="<?= $link_site . '?page=locatari&section=editeaza_locatar&w=' . $c['id']; ?>" class="link">Editeaza</a>
                </td>
            </tr>
            <?php
        }

        ?>
    </table>
    <?php
    if ($i > 30)
    {
        echo str_replace('LINK_SITE', $link_site, $easy_navigation2);
    }

}

?>