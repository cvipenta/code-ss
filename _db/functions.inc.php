<?php
/**
 * Description:     A couple of useful functions
 * Author:          Cristian Daniel Visan <cristian.visan@gmail.com>
 * Last change:     May 14, 2007
 */

/**
 * @param $value
 * @param $key
 */
function escape(&$value, $key)
{
    $value = strip_tags(trim($value));
    $value = htmlentities($value, ENT_QUOTES);
}

/**
 * @param $str
 *
 * @return array
 */
function check_cnp($str)
{
    if (strlen($str) < 13) {
        $handler = 0;
        $err = 'CNP invalid pentru ca are mai putin de 13 caractere';
        return [$handler, $err];
    }

    if (strlen($str) > 13) {
        $handler = 0;
        $err = 'CNP invalid pentru ca are mai mult de 13 caractere';
        return [$handler, $err];
    }

    if (strlen($str) == 13) {
        $q = "SELECT cnp FROM camin_studentesc.cs_persoane WHERE cnp ='{$str}'";
        $res = getDb()->GetArray($q);

        // locatarul exista deja in baza de date
        if (count($res) > 0) {
            $handler = 0;
            $err = "CNP-ul {$str} introdus exista deja in baza de date!";
            return [$handler, $err];
        }
    }

    $handler = 1;
    $err = 'CNP-ul este valid!';

    return [$handler, $err];
}

/**
 * @param integer $id_locatar
 *
 * @return mixed
 */
function get_locatar_info($id_locatar)
{
    global $db;

    $q = "
        SELECT cs_persoane.*, cs_persoane_camere.*
        FROM cs_persoane
        INNER JOIN cs_persoane_camere ON cs_persoane.id = cs_persoane_camere.id_persoana
        WHERE cs_persoane.id = {$id_locatar}
    ";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r = $db->Execute($q);
    $res = $r->GetRows();

    // aici vreau sa adaug datoriile, incasarile, tipul camerei, tipul persoanei tot tot ce tine de un locatar.

    return $res[0];
}

/**
 * @param integer $id_camera
 * @param integer $id_pat
 *
 * @return array
 */
function get_persoana_camera_pat_fast($id_camera, $id_pat)
{
    global $db;

    /* quick query */
    $q = "
		SELECT 
			cs_persoane.nume, 
			cs_persoane.prenume, 
			cs_persoane_camere.id_persoana, 
			cs_persoane_camere.data_start, 
			cs_persoane_camere.data_end
		FROM cs_persoane_camere 
		INNER JOIN cs_persoane ON cs_persoane_camere.id_persoana = cs_persoane.id
		WHERE 1
		AND cs_persoane_camere.id_camera = {$id_camera}
		AND (cs_persoane_camere.nr_pat = {$id_pat} OR cs_persoane_camere.nr_pat_2 = {$id_pat})
		AND cs_persoane_camere.status_activ = 1";
    $r = $db->GetArray($q);

    return (count($r) > 0) ? $r[0] : [];
}

/**
 * third parameter should be 0 if it is different from 0 it will be the only used
 *
 * @param     $id_camera
 * @param     $id_pat
 * @param int $id_locatar
 *
 * @return array
 */
function get_persoana_camera_pat($id_camera, $id_pat, $id_locatar = 0)
{
    global $db;

    /* quick query */
    if ($id_locatar == 0) {
        $q = "
            SELECT 
                cs_persoane.nume, 
                cs_persoane.prenume, 
                cs_persoane_camere.*
            FROM cs_persoane_camere 
            INNER JOIN cs_persoane ON cs_persoane_camere.id_persoana = cs_persoane.id
            AND cs_persoane_camere.id_camera = {$id_camera}
            AND cs_persoane_camere.nr_pat = {$id_pat}
            AND cs_persoane_camere.status_activ IN (1, 2, 3)
		";
    } else {
        $q = "
			SELECT cs_persoane.nume, cs_persoane.prenume, cs_persoane_camere.*
			FROM cs_persoane_camere 
			INNER JOIN cs_persoane ON cs_persoane_camere.id_persoana = cs_persoane.id
			AND cs_persoane.id = {$id_locatar}
			AND cs_persoane_camere.status_activ IN (1, 2, 3)
		";
    }

    //psql($q);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $res = $db->GetArray($q);

    $result = [];
    if (count($res) > 0) {
        $result = $res[0];
    }

    return $result;
}


/**
 * @param      $etaj
 * @param null $id_camera
 *
 * @return mixed
 */
function get_camere_etaj($etaj, $id_camera = null)
{
    global $db;

    $q = "SELECT * FROM cs_camere  ORDER BY etaj ASC, numar ASC";

    if (!is_null($id_camera)) {
        $q = "SELECT * FROM cs_camere  WHERE id = " . $id_camera . " LIMIT 1";
    }

    $r = $db->Execute($q);
    $result = $r->GetRows();

    if (count($result) > 0) {
        foreach ($result as $i => $r) {
            if ($r['etaj'] == $etaj) {
                $camera[$i] = ['id' => $r['id'], 'numar' => $r['numar'], 'indicativ' => $r['etaj'] . $r['numar'], 'nr_paturi' => $r['nr_paturi'], 'status_ocupare' => $r['status_ocupare']];
            }
        }
    }

    return $camera;
}

/**
 * @param $id_camera
 *
 * @return mixed
 */
function get_nr_paturi_camera($id_camera)
{
    global $db;

    $q = "SELECT nr_paturi FROM cs_camere  WHERE id='$id_camera'";
    $r = $db->Execute($q);
    $result = $r->GetRows();
    $numar_paturi = $result[0]['nr_paturi']; //numar paturi in camera selectata

    return $numar_paturi;
}

/**
 * @param $id
 *
 * @return string
 */
function get_persoana_dupa_id($id)
{
    global $db;
    $db->debug = false;

    $q = "SELECT * FROM cs_persoane WHERE id='$id'";
    $r = $db->Execute($q);
    $result = $r->GetRowAssoc($toUpper = false);

    return $result['nume'] . ' ' . $result['prenume'];
}

/**
 * @return array
 */
function get_persoane_necazate()
{
    global $db;
    $lista_necazati = [];

    $q = "
			SELECT id_persoana 
			FROM cs_persoane_camere 
			WHERE 1
			AND id_camera != '' 
			AND (nr_pat != '' OR camera_integral != 0) 
			AND status_activ IN (1)";    //vor fi mai multe stari de cazare ale locatarului 1 -normal

    $r = $db->Execute($q);
    $rs = $r->GetArray();

    $lista = [];
    foreach ($rs as $r) {
        $lista[] = $r['id_persoana'];
    }

    $lista_cazati = @implode(',', $lista);

    if ($lista_cazati == '') {
        $lista_cazati = (1);
    } // necazati daca nu merge de mai sus.

    $q1 = "SELECT * FROM cs_persoane WHERE id NOT IN ($lista_cazati) AND activ=1 ORDER BY nume ASC, prenume ASC";
    $r1 = $db->Execute($q1);
    $rs1 = $r1->GetAssoc();

    if (count($rs1) > 0) {
        foreach ($rs1 as $s) {
            $lista_necazati[] = [$s['id'], $s['nume'], $s['prenume'], $s['nume_casatorie'], $s['init']];
        }
    }

    return $lista_necazati;
}

/**
 * O persoana este considerata cazata cand in tabela cs_persoane_camere are camera, pat si statusul de cazare
 * este in unul din cazurile de cazare valida - vor fi mai multe stari de cazare ale locatarului:
 * 1 - normal
 * 2 - cazat pe perioada verii
 * 3 - cazat incepand cu anul universitar urmator (locul este disponibil pe perioada verii)
 *
 * @return array
 */
function get_persoane_cazate()
{
    global $db;
    $lista_cazati = [];

    $q = "
        SELECT id_persoana 
        FROM cs_persoane_camere 
        WHERE 1 
        AND id_camera != '' 
        AND (nr_pat != '' OR camera_integral != 0) 
        AND status_activ IN (1, 2 ,3)
    ";
    $rs = (array)$db->GetArray($q);

    $lista = [];
    foreach ($rs as $r) {
        $lista[] = $r['id_persoana'];
    }
    $ids_cazati = @implode(', ', $lista);

    $q1 = "
        SELECT id, nume, prenume, nume_casatorie, init 
        FROM cs_persoane WHERE id IN ($ids_cazati) /*AND activ=1*/  
        ORDER BY nume ASC, prenume ASC
    ";
    $rs1 = (array)$db->GetAssoc($q1);

    if (count($rs1) > 0) {
        foreach ($rs1 as $s) {
            $lista_cazati[] = [$s['id'], $s['nume'], $s['prenume'], $s['nume_casatorie'], $s['init']];
        }
    }

    return $lista_cazati;
}

/* Returneaza etaj camera si numar paturi */
function get_camera_dupa_id($id, $extra)
{
    global $db;

    //$extra il folosim pentru formatarea rezultatului

    $q = "SELECT * FROM cs_camere WHERE id = $id";
    $r = $db->Execute($q);
    $result = $r->GetRowAssoc($toUpper = false);

    if ($result['etaj'] == 0) {
        $result['etaj'] = 'parter';
    }

    return ' (Etaj: ' . $result['etaj'] . ' - Camera: ' . $result['numar'] . ')';
}

// OLD FUNCTION	
//function get_infocazare_idpersoana($id_persoana)
//{
//	// id-ul este cel din tabela cs_persoane si cel din tabela cs_persoane camere 
//	
//	global $db;
//	
//	$q = "SELECT * FROM cs_persoane_camere WHERE id_persoana=$id_persoana";	
//	$r = $db->Execute($q); 
//	$result = $r->GetRowAssoc($toUpper=false);
//	
//	return $result;
//}

function get_infocazare_idpersoana($id_persoana, $type = 0)
{
    // id-ul este cel din tabela cs_persoane si cel din tabela cs_persoane camere

    // all natural order old > recent
    if ($type == 0) {
        $q = "SELECT * FROM cs_persoane_camere WHERE id_persoana = {$id_persoana}";
    }

    // in ordinea inversa a cazarilor recent > old
    if ($type == 1) {
        $q = "SELECT * FROM cs_persoane_camere WHERE id_persoana={$id_persoana} ORDER BY id DESC"; // in ordinea inversa a cazarilor recent > old
    }
    //getDb()->debug = true;
    $r = getDb()->Execute($q);
    $result = $r->GetRows();    //pa($result);

    return $result;
}

/**
 * Print array by cvIT
 *
 * default $just_in_source is set to 0 that means it will be displayed
 * if $just_in_source is set to 1 it will be displayed just in source code without being shown to users
 * do not use $just_in_source = 1 if the infos printed may contain security issues
 *
 * @param     $array
 * @param int $just_in_source
 */
function pa($array, $just_in_source = 0)
{
    if (sizeof($array) == 0) {
        $display = 'Array gol';
    } elseif (is_array($array)) {

        $new_array = [];

        foreach ($array as $id => $element) {
            if (is_string($element)) {
                $new_array[$id] = wordwrap($element, 80, "<br/>", true);
            } else {
                $new_array[$id] = $element;
            }
        }

        $display = '<pre style="font-size:10px; font-family:Verdana, Arial, Helvetica, sans-serif; color:#0000FF">';
        $display .= print_r($new_array, 1);
        $display .= '</pre>';
    } else {
        $display = 'Not an array!';
    }

    if ($just_in_source == 1) {
        echo "\n<!--\n	$display \n -->\n";
    } else {
        echo $display;
    }


}

/* priteaza un sql*/
function psql($string)
{
    echo '<hr>';
    @highlight_string($string);
    echo '<hr>';
    $reserved = ['SELECT', 'UPDATE', 'INSERT', 'DELETE', 'FROM', 'AS'];
}

///**
// * Gets a mysql query string and format it. Used in general for debug purposes
// *
// * @param string $string
// */
//function printSQL($string)
//{
//    require_once('../config/geshi.php');
//
//    if ($_COOKIE['cvit_debug'] == 1) {
//        echo '<hr/>';
//        geshi_highlight($string, 'mysql');
//        echo '<hr/>';
//    }
//}

//function printSQL2($string)
//{
//    require_once '../config/geshi.php';
//
//    echo '<hr/>';
//    geshi_highlight($string, 'mysql');
//    echo '<hr/>';
//}


/**
 * @param $string
 *
 * @return string
 */
function date4sql($string)
{
    $string = trim($string);

    if (strlen($string) > 7) {
        [$day, $month, $year] = explode('-', $string);

        if ($day < 10 && strlen($day) == 1) {
            $day = '0' . $day;
        }

        if ($month < 10 && strlen($month) == 1) {
            $month = '0' . $month;
        }

        $converted = implode('-', [$year, $month, $day]);
    } else {
        $converted = 'Data nu are lungimea corespunzatoare';
    }

    return $converted;
}

/**
 * @param $string
 *
 * @return string
 */
function date4html($string)
{
    $string = trim($string);

    if (strlen($string) == 10) {
        [$year, $month, $day] = explode('-', $string);
        $converted = implode('-', [$day, $month, $year]);
    } else {
        $converted = 'Data nu are lungimea corespunzatoare';
    }

    return $converted;
}

/**
 * @param $string
 * @param $days_number
 *
 * @return string
 */
function date_alarm($string, $days_number)
{
    $string = trim($string);
    $date_for_strtotime = date4sql($string);

    $days_number = intval($days_number);
    $alarm_interval = $days_number * 86400;

    if (strlen($date_for_strtotime) == 10) {
        $interval = strtotime($date_for_strtotime) - (int)date("U");
        $days_interval = ceil($interval / 86400);

        if (($interval < $alarm_interval) and ($days_interval >= 0)) {
            $result = '<span class="red" tip="Au mai ramas ' . $days_interval . ' zile<br> pana la expirarea cazarii">' . $string . '</span>';
        } elseif (($interval < $alarm_interval) and ($days_interval < 0)) {
            $result = '<span class="red" style="text-decoration: line-through;" tip="Intervalul de cazare a expirat de ' . abs($days_interval) . ' zile">' . $string . '</span>';
        } else {
            $result = $string;
        }

    } else {
        $result = 'Data nu are lungimea corespunzatoare';
    }

    return $result;

}

///**
// * @param $options_array
// * @param null $current_option
// * @return string
// */
//function write_select($options_array, $current_option = null)
//{
//    // cvit July 10, 2007
//
//    $result = "\n";
//
//    foreach ($options_array as $key => $option) {
//        ($current_option == $key) ? $status = 'SELECTED style="color:blue"' : $status = '';
//        $result .= '<option value="'.$key.'" '.$status.'>'.$option.'</option>'."\n";
//    }
//
//    return $result;
//}

///**
// * WriteRadio suite // cvit March 10, 2009
// *
// * @param string $name the name of the control
// * @param array $options_array set of values
// * @param string $current_option current option checked
// * @param string $css_style style to be applied to the input
// * @param string $separator html separator between inputs
// * @return string
// */
//function write_radio($name, $options_array, $current_option = null, $css_style = null, $separator = '')
//{
//    $result = "\n";
//
//    foreach ($options_array as $key => $option)
//    {
//        $status = ($current_option == $key) ? 'checked' : '';
//        $result .= '<input type="radio" name="' . $name . '" value="' . $key . '" ' . $css_style . ' ' . $status . '/>' . $option . '' . $separator . "\n";
//    }
//
//    return $result;
//}

/**
 * Aici aflu taxa la care se incadreaza micutul am la dispozitie
 * $id_cazare, $id_persoana, $tip_datorie si perioada de incidenta a taxei trebuie sa returneze:
 * - id taxa
 * - suma datorie
 * - text datorie
 *
 * @param $tip_datorie
 * @param $tip_camera
 * @param $tip_persoana
 * @param $perioada
 *
 * @return mixed
 */
function get_taxa_corespunzatoare($tip_datorie, $tip_camera, $tip_persoana, $perioada)
{
    global $db;
    global $arr_tip_taxa, $arr_tip_camera, $arr_tip_persoana;

    $r = [];

    $q_taxa = "
		SELECT * 
		FROM camin_studentesc.cs_taxa 
		WHERE 1
		AND tip_taxa = {$tip_datorie} 
		AND tip_camera = {$tip_camera} 
		AND tip_persoana = {$tip_persoana}
		AND data_val_min <= '" . $perioada[0] . "'
		AND data_val_max >= '" . $perioada[1] . "' 
    ";  //psql($q_taxa);// die;

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_taxa = $db->Execute($q_taxa);
    $result_taxa = $r_taxa->GetRows(); //pa($result_taxa);

    if (is_array($result_taxa) && count($result_taxa) == 0) {
        /* daca nu am taxe stabilite in intervalul de cazare propus atunci iau taxa cea mai indepartata si o pun default*/
        $q_taxa_default = "
            SELECT * 
            FROM camin_studentesc.cs_taxa 
            WHERE tip_taxa = $tip_datorie 
            AND tip_camera = $tip_camera 
            AND tip_persoana = $tip_persoana
            ORDER BY data_val_max DESC
		";  //psql($q_taxa);

        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $r_taxa_default = $db->Execute($q_taxa_default);
        $result_taxa_default = $r_taxa_default->GetRows();

        if (count($result_taxa_default) == 0) {
            $r['text_datorie'] = "<b>NU ESTE DEFINITA TAXA</b> pentru <br/>" . $arr_tip_taxa[$tip_datorie] . " | " . $arr_tip_camera[$tip_camera] . " | " . $arr_tip_persoana[$tip_persoana] . "";
        }

        $result_taxa_default = $result_taxa_default[0]; //pa($result_taxa_default);

        $r['id_taxa'] = $result_taxa_default['id'];
        $r['suma_datorie'] = $result_taxa_default['valoare_taxa'];
        $r['text_datorie'] = str_replace("#", " | ", $result_taxa_default['text_taxa']);
        $r['tip_taxa_camera_persoana'] = $tip_datorie . '|' . $tip_camera . '|' . $tip_persoana;
    }

    if (is_array($result_taxa) && count($result_taxa) > 1) {
        $r['text_datorie'] = "Taxa definita necorespunzator";
    }

    if (is_array($result_taxa) && count($result_taxa) == 1) {
        $result_taxa = $result_taxa[0];    //pa($result_taxa);

        $r['id_taxa'] = $result_taxa['id'];
        $r['suma_datorie'] = $result_taxa['valoare_taxa'];
        $r['text_datorie'] = str_replace("#", " | ", $result_taxa['text_taxa']);
        $r['tip_taxa_camera_persoana'] = $result_taxa['tip_taxa'] . '|' . $result_taxa['tip_camera'] . '|' . $result_taxa['tip_persoana'];
    }

    return $r;
}


/**
 * ia informatiile printate pe o chitanta prin intermediul id_incasare
 *
 * @param $id_incasare
 *
 * @return array|bool
 */
function get_print_incasare($id_incasare)
{
    $sql = "SELECT * FROM camin_studentesc.cs_incasari_print WHERE id_incasare = {$id_incasare} LIMIT 1";
    $res = getDb()->GetRow($sql);

    return $res;
}


/**
 * ia informatiile printate pe o plata prin intermediul id_plata
 *
 * @param $id_plata
 *
 * @return mixed
 */
function get_print_plata($id_plata)
{
    $sql = "SELECT * FROM camin_studentesc.cs_plati_print WHERE id_plata = " . $id_plata . " LIMIT 1";
    $res = getDb()->GetRow($sql);

    return $res;
}


function get_utilitati()
{
    global $db;

    $q_utilitati = "SELECT * FROM camin_studentesc.cs_utilitati ORDER BY id ASC";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_utilitati = $db->Execute($q_utilitati);
    $res_utilitati = $r_utilitati->GetAssoc();  //pa($res_utilitati);

    return $res_utilitati;
}

/**
 * @param integer $id_cazare
 * @param integer $id_locatar
 * @param string  $utilitati_old
 * @param string  $utilitati_new
 */
function manage_utilitati($id_cazare, $id_locatar, $utilitati_old, $utilitati_new)
{
    global $db;

    echo '[' . $id_cazare . '][' . $id_locatar . '][' . $utilitati_old . '][' . $utilitati_new . ']';

    $u_old = @explode("|", $utilitati_old); //pa($u_old);
    $u_new = @explode("|", $utilitati_new); //pa($u_new);

    $all_on = array_diff((array)$u_old, (array)$u_new);

    if (count($all_on) > 0) {
        $in_all_on = @implode(",", $all_on);
        $q_del = "DELETE FROM camin_studentesc.cs_persoane_utilitati WHERE id_cazare = $id_cazare AND id_locatar = $id_locatar AND id_utilitate IN ($in_all_on)";
        $db->Execute($q_del);
    }

    $all_no = array_diff((array)$u_new, (array)$u_old); //pa($all_no); echo count($all_no);

    if (count($all_no) > 0) {
        foreach ($all_no as $value) {

            $q_e = "
                SELECT * 
                FROM camin_studentesc.cs_persoane_utilitati 
                WHERE id_cazare = {$id_cazare} 
                AND id_locatar = {$id_locatar} 
                AND id_utilitate = {$value}
            ";
            $r = $db->GetRow($q_e);

            if (count($r) == 0) {
                $to_insert = [
                    'id_cazare'    => $id_cazare,
                    'id_locatar'   => $id_locatar,
                    'id_utilitate' => $value,
                    'data'         => date("Y-m-d"),
                ];
                $db->AutoExecute('cs_persoane_utilitati', $to_insert, 'INSERT');
            }
        }
    }
}

/**
 * ACTUAL COUNTER OF NUMAR_UTILITATI_CAZARE
 *
 * @param int    $id_locatar
 * @param string $data_start     luna de referinta (fata de care se calculeaza numarul de utilitati)
 * @param null   $type_of_status daca este setat specifica statustul utilitatii (activa, inactiva) default sunt cele active
 * @param bool   $returned_infos daca este setat returneaza pe langa numarul utilitatilor si informatii despre acestea
 * @param null   $id_cazare      id-ul cazarii pentru care se face numararea (in cazul in care sunt mai multe cazari)
 *
 * @return int
 */
function numar_utilitati_cazare($id_locatar, $data_start, $type_of_status = null, $returned_infos = false, $id_cazare = null)
{
    global $db;

    // if id cazare is not null
    if ($id_cazare > 0) {
        $q_cazare = "AND id_cazare = " . $id_cazare . " ";
    } else {
        $q_cazare = "";
    }

    switch ($type_of_status) {
        case null:
            $qStatus = "";
            break;

        case 0:
            $qStatus = " AND id_status = 0";
            break;

        case 1:
            $qStatus = " AND id_status = 1";
            break;

        default:
            $qStatus = "";
    }
    //$db->debug=true;
    $q = "
		SELECT * FROM camin_studentesc.cs_persoane_utilitati
        WHERE 1
        AND id_utilitate != 0
        AND id_locatar = {$id_locatar}
        AND '$data_start' BETWEEN data_input AND data_output
        {$q_cazare}
        {$qStatus}
    ";

    $r = $db->Execute($q);
    $res = $r->GetRows();
    #printSQL2($q);
    #pa($res);

    if ($returned_infos == false) {
        $to_return = count($res);
    } else {
        $to_return['counter'] = count($res);
        $to_return['infos'] = $res;
    }
    #$db->debug=false;
    return $to_return;
}

/**
 * @desc    Ia utilitatile active pentru un locatar la un moment dat.
 *            Cred ca ar trebui folosita in locul celeilalte functii pentru ca este mai light
 *
 * @param int    $id_locatar
 * @param string $data_start
 * @param int    $id_cazare
 *
 * @return array
 * @since   22.05.2011
 */
function get_utilitati_locatar($id_locatar, $data_start, $id_cazare = 0)
{
    $q = "
        SELECT 
            id, 
            id_utilitate 
        FROM camin_studentesc.cs_persoane_utilitati
        WHERE 1
        AND	id_utilitate != 0
        AND id_locatar = $id_locatar
        AND '$data_start' BETWEEN data_input AND data_output
    ";
    if ($id_cazare > 0) {
        $q .= "AND id_cazare = {$id_cazare}";
    }
    //printSQL2($q);
    $r = getDb()->Execute($q)->GetAssoc();

    return $r;
}

/**
 * @return array
 */
function get_universitati()
{
    $q_universitati = "SELECT id_universitate, universitate FROM camin_studentesc.cs_universitati";
    $res_universitati = getDb()->Execute($q_universitati)->GetAssoc();

    return $res_universitati;
}

/**
 * @param integer $id
 *
 * @return mixed
 */
function get_universitate_dupa_id($id)
{
    global $db;

    $q_univ = "SELECT DISTINCT universitate FROM camin_studentesc.cs_universitati WHERE id_universitate = {$id}";
    $r_univ = $db->Execute($q_univ);
    $res_univ = $r_univ->GetRows();  //pa($res_univ);

    return $res_univ[0]['universitate'];
}

/**
 * @param integer $id
 *
 * @return mixed
 */
function get_facultate_dupa_id($id)
{
    global $db;

    $q_fac = "SELECT SUBSTRING(facultate, 3) AS nfacultate FROM camin_studentesc.cs_universitati WHERE id = {$id}";
    $r_fac = $db->Execute($q_fac);
    $res_fac = $r_fac->GetRows();  //pa($res_fac);

    return $res_fac[0]['nfacultate'];
}

/**
 * @return string
 */
function curPageURL()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }

    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }

    return $pageURL;
}

/**
 * @param $indicativ
 *
 * @return mixed
 */
function get_cont_nt($indicativ)
{
    $result['indicativ'] = 0;
    $result['semnificatie'] = 'Cont nedefinit in aplicatie';

    if (in_array($indicativ, [50, 51, 52, 54, 55, 56, 57])) {
        $q_cont_nt = "SELECT * FROM camin_studentesc.cs_conturi_nt WHERE indicativ = " . $indicativ . " LIMIT 1";
        $res_cont_nt = getDb()->GetRow($q_cont_nt);

        $result['indicativ'] = $indicativ;
        $result['semnificatie'] = $res_cont_nt['semnificatie'];
    }

    return $result;
}

/**
 * @param $id
 *
 * @return string
 */
function get_casier($id)
{
    $result = ' nespecificat ';

    if (empty($id)) {
        return $result;
    }

    $q_casier = "SELECT username_user FROM `nt`.`USER` WHERE id_user = {$id}";
    $res = getDb()->GetRow($q_casier);

    if (count($res) > 0) {
        $result = $res['username_user'];
    }

    return $result;
}

function get_cont_info($id_taxa)
{
    //the function returns internal accout infos
    global $db;

    $q = "
        SELECT cs_taxa.*, cs_conturi.* 
		FROM camin_studentesc.cs_taxa
		INNER JOIN camin_studentesc.cs_conturi ON cs_conturi.id_taxa = cs_taxa.id
		WHERE cs_taxa.id = {$id_taxa}
		AND cs_conturi.tip_cont = 1";

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r = $db->Execute($q);
    $res = $r->GetRows();

//	pa($res);

    return $res[0];
}

// 

function show_help($arr)
{
    //pa($arr);

    if (is_array($arr)) {
        $help = base64_encode(serialize($arr));
        $to_return = '<input type="button" value="ajutor" class="okbutton" tip="Click aici pentru a citi manualul de utilizare a sectiunii" onclick="newWindow(\'view_help.php?i=' . $help . '\');">';
    } else {
        $to_return = '';
    }

    echo $to_return;
}

/**
 * @param $raw_data
 *
 * @return bool
 */
function zu($raw_data)
{
    global $db;

    $SESSION2SAVE = [];
    $SESSION2SAVE = $_SESSION;
    $SESSION2SAVE['CACHE'] = null; // do not log cache from session

    $to_insert['zu_time'] = date("Y-m-d H:i:s");
    $to_insert['zu_session'] = serialize($SESSION2SAVE);
    $to_insert['zu_globals'] = serialize($_SERVER); # instead of serialize($GLOBALS);

    if (is_array($raw_data)) {
        $to_insert['zu_infos'] = serialize($raw_data);
    } else {
        $to_insert['zu_infos'] = $raw_data;
    }

    // stop this way logging for the moment
    $db->AutoExecute('cs_zu', $to_insert, 'INSERT');

    return true;
}


/**
 * introduce in istoric incasari modificarile facute asupra incasarilor ca parametrii avem
 * id incasare si un array cu datele ce se modifica la incasarea respectiva
 * cel putin un parametru nu trebuie sa lipseasca si anume $arr_to_change[reason_change]
 *
 * @param $id_incasare
 * @param $arr_to_change
 */
function istoric_incasare($id_incasare, $arr_to_change)
{
    $q = " SELECT * FROM camin_studentesc.cs_incasari WHERE id_incasare = {$id_incasare} LIMIT 1";
    $income_info = getDb()->GetRow($q);

    // istoric incasare
    $istoric_incasare = $income_info;
    $istoric_incasare['id_changer'] = $_SESSION['iduser'];
    $istoric_incasare['data_change'] = date("Y-m-d H:i:s");
    $istoric_incasare['reason_change'] = $arr_to_change['reason_change'];

    foreach ($arr_to_change as $k => $value) {
        $istoric_incasare[$k] = $value;
    }

    getDb()->AutoExecute('cs_incasari_istoric', $istoric_incasare, 'INSERT');
}

/**
 * @param $dim_id
 * @param $dim_table
 *
 * @return string
 */
function getDimension($dim_id, $dim_table)
{
    global $db;

    $q = "SELECT d_text FROM {$dim_table} WHERE d_id = {$dim_id} LIMIT 1";
    $return = $db->GetRow($q);

    if ($return['d_text']) {
        return $return['d_text'];
    } else {
        return 'error: ' . $dim_id . ' ' . $dim_table;
    }
}

/**
 * @param $str
 * @param $start
 * @param $end
 *
 * @return bool|string
 */
//if (!function_exists('ExtractString')) {
//    function ExtractString($str, $start, $end)
//    {
//        $str_low = strtolower($str);
//        $pos_start = @strpos($str_low, $start);
//        $pos_end = @strpos($str_low, $end, ($pos_start + strlen($start)));
//        if (($pos_start !== false) && ($pos_end !== false)) {
//            $pos1 = $pos_start + strlen($start);
//            $pos2 = $pos_end - $pos1;
//
//            return substr($str, $pos1, $pos2);
//        }
//    }
//}

// clean filenames
/**
 * @param        $string_filename
 * @param string $convert_case
 *
 * @return bool|mixed|string
 */
//if (!function_exists('clean_filename2')) {
//    function clean_filename2($string_filename, $convert_case = '')
//    {
//        $string_filename = trim($string_filename);
//
//        if (strlen($string_filename) > 4) {
//            $to_return = preg_replace('/\\W+/', '_', $string_filename);
//            $to_return = str_replace(' ', '_', $to_return);
//            $to_return = str_replace('__', '_', $to_return);
//            $to_return = strtolower($to_return);
//
//        } else {
//            $to_return = false;
//        }
//
//        return $to_return;
//    }
//}

function getLaundryUnitPrice()
{
    return 5;
}

/**
 * @param $paymentMethod
 *
 * @return string
 */
function getPaymentMethodCondition($paymentMethod): string
{
    $paymentMethodCondition = '1';
    switch ($paymentMethod) {
        case 'casa':
            $paymentMethodCondition = " payment_method IN ('cash', 'card')";
            break;
        case 'cash':
            $paymentMethodCondition = " payment_method = 'CASH'";
            break;
        case 'card':
            $paymentMethodCondition = " payment_method = 'CARD'";
            break;
        case 'banca':
            $paymentMethodCondition = " payment_method = 'BANK'";
            break;
        case 'online':
            $paymentMethodCondition = " payment_method = 'ONLINE'";
            break;
    }
    return $paymentMethodCondition;
}