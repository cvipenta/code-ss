<?php
/**
 * @Description:    InfoLocatar: detalii fisa de cazare, detalii datorii, alte detalii
 * @Author:         Cristian Daniel Visan <cristian.visan@gmail.com>
 * @CreatedAt:	    June 22, 2007
 */

/* INCLUDES */
include "includes/functions.inc.php";

//pa($_SESSION);

/* aici ar trebui sa fac niste verificari */
if (isset($_POST['action']) AND $_POST['action'] != '') {
    $action = $_POST['action'];
}

if (isset($_GET['section']) AND $_GET['section'] != '') {
    $section = $_GET['section'];
} else {
    $section = 'detalii_locatar';
}

if ($section == 'detalii_locatar') {
    $q = "SELECT * FROM camin_studentesc.cs_persoane WHERE id = {$_GET['w']}";
    $res = $db->GetRow($q);

    if(!is_array($res) || count($res) == 0) {
        echo '<div class="page_title18" align="center">LOCATAR INEXISTENT</div>';
        echo '<span class="red">Marca locatarului nu poate fi gasita! Va rugam sa semnalati eroarea departamentului suport!</span>';
        die(__FILE__.__LINE__);
    }

    $ci = explode('#', $res['serie_nr']);
    $res['ci_serie'] = $ci[0];
    $res['ci_numar'] = $ci[1];

    [$d, $m, $y] = array(substr($res['cnp'], 5, 2), substr($res['cnp'], 3, 2), substr($res['cnp'], 1, 2));
    $birthday = mktime(0, 0, 0, $m, $d, $y);

    $res['student_facultate'] = unserialize($res['student_facultate']);
    $res['student_facultate'] = @array_keys($res['student_facultate']);

    $facultate = '';
    if (is_array($res['student_facultate']) && count($res['student_facultate']) > 0) {
        $facultate = [];
        foreach ($res['student_facultate'] as $fac) {
            $facultate[] = get_facultate_dupa_id($fac);
        }

        $facultate = implode(", ", $facultate);
    }
    //pa($res);

    ?>

    <div class="page_title" align="center"><?= $res['nume'].' '.$res['prenume'] ?><?php echo(trim($res['nume_casatorie'] != '') ? ' ('.$res['nume_casatorie'].')' : '') ?> [<?= $res['id'] ?>]</div>
    <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>

    <div class="page_title18" align="left">Detalii locatar</div>

    <!-- START DETALII GENERALE -->
    <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#a1c2e7">

        <tr height="22" bgcolor="#FFFFFF">
            <td width="5">&nbsp;</td>
            <td valign="middle" colspan="2">
                <a tip="Editeaza detalii locatar" href="<?= $link_site.'?page=locatari&section=editeaza_locatar&w='.$res['id']; ?>" class="link_list">Editeaza detalii locatar</a>
            </td>
        </tr>

        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc">Data nasterii:</td>
            <td class="text_insc"><b><?= date("d-m-Y", $birthday) ?></b></td>
        </tr>

        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc" valign="top">Adresa:</td>
            <td class="text_insc">Str. <b><?= $res['strada'].', '.$res['detalii_adresa'] ?></b>, Localitate:<b><?= $res['localitate'].' / '.$arr_judete[$res['judet']] ?></b></td>
        </tr>

        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc">Telefoane:</td>
            <td class="text_insc"><b><?= $res['telefoane'] ?></b></td>
        </tr>

        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc">Email:</td>
            <td class="text_insc"><b><?= $res['email'] ?></b></td>
        </tr>

        <tr>
            <td colspan="3">
                <hr/>
            </td>
        </tr>

        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc" valign="top">Student:</td>
            <td class="text_insc">
                <?php
                if ($res['student_status'] == 1) {
                    $fu_display = '';
                    if ($facultate != '') {
                        $fu_display .= "Facultate: <b>".$facultate."</b>;<br>";
                    }
                    if ($res['student_universitate'] != '') {
                        $fu_display .= "Universitate: <b>".get_universitate_dupa_id($res['student_universitate'])."</b>";
                    }
                } else {
                    $fu_display = ' --- ';
                }
                echo $fu_display;
                ?><br><br></td>
        </tr>

        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc" valign="top">Date suplimentare:</td>
            <td class="text_insc"><?php echo ($res['job_status'] == 1) ? "Companie: <b>".$res['job_companie']."</b>;<br> Functie: <b>".$res['job_functie']."</b>" : "<b>NU</b>"; ?><br><br></td>
        </tr>

    </table>
    <!-- END OF DETALII GENERALE -->
    <br>

    <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e6f4ff">
        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc">CNP:</td>
            <td class="text_insc"><b><?= $res['cnp'] ?></b></td>
        </tr>

        <tr>
            <td width="5">&nbsp;</td>
            <td width="120" class="text_insc">Carte de identitate:</td>
            <td class="text_insc">(S/N) <b><?= $res['ci_serie'] ?> <?= $res['ci_numar'] ?></b></td>
        </tr>
    </table>

    <?php

    $cazare_info = get_infocazare_idpersoana($res['id']);

    // afisam cazarile in ordinea inversa intamplarii lor
    $cazare_info = array_reverse($cazare_info);

    echo '.<!--';
    pa($cazare_info);
    echo ' -->.';

    $is_active_or_not = 0;

    foreach ($cazare_info AS $cinfo) {
        if (in_array($cinfo['status_activ'], array(1, 2, 3))) {
            $is_active_or_not++;
        } else {
            //$is_active_or_not = 0;
        }
    }

    //echo $is_active_or_not.'-'.count($cazare_info);

    if (count($cazare_info) >= 1 && ($is_active_or_not == 0)) {
        ?>
        <hr/>
        <!--<span class="red"><b>APLICATIE IN TESTARE. VA RUGAM NU ACESATI LINKUL DE RECAZARE!</b></span>-->
        <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e6f4ff">
            <tr height="22" bgcolor="#FFFFFF">
                <td width="5">&nbsp;</td>
                <td valign="middle" colspan="2" class="text"><span class="red"><b>Locatar necazat</b></span> &rsaquo;
                    <a title="Editeaza detalii cazare" href="<?= $link_site ?>index.php?page=camere&section=ocupare_camere&locatar=<?= $res['id'] ?>" class="link"> RECAZEAZA </a>
                    <br/><br/><span class="red">* ATENTIE accesati linkul "RECAZEAZA" doar in cazul in care doriti sa recazati locatarul curent !</span>
                </td>
            </tr>
        </table>
        <hr/>
        <?php
    }


    foreach ($cazare_info AS $info) {
        if ($info['id'] != 0 && in_array($info['status_activ'], array(1, 2, 3))) {
            $utilitati = get_utilitati();

            //pa($info);

            $extra_status_activ = '';

            if ($info['status_activ'] == 2) {
                $extra_status_activ = 'LOCATAR CAZAT PE PERIOADA VERII';
            }

            if ($info['status_activ'] == 3) {
                $extra_status_activ = 'LOCATAR CAZAT DUPA 1 OCT. (locul este ocupat pe perioada verii de alt locatar)';
            }

            ?>
            <div class="page_title16" align="left"><?= $extra_status_activ; ?></div>
            <div class="page_title18" align="left"><br/>Detalii cazare actuala (<?= date4html($info['data_start']); ?> -> <?= date_alarm(date4html($info['data_end']), 7); ?>)</div>


            <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e6f4ff">
                <tr height="22" bgcolor="#FFFFFF">
                    <td width="5">&nbsp;</td>
                    <td valign="middle" colspan="2">
                        <a title="Editeaza detalii cazare" href="<?= $link_site.'?page=camere&section=editeaza_detalii_cazare&w='.$info['id_camera'].'-'.$info['nr_pat'].'-'.$info['id_persoana']; ?>" class="link_list">Editeaza detalii cazare locatar</a>
                    </td>
                </tr>
                <!-- perioada cazare -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc">Cazat pentru perioada:</td>
                    <td class="text_insc"><b><?= date4html($info['data_start']); ?></b> -> <b><?= date_alarm(date4html($info['data_end']), 7); ?></b><br/></td>
                </tr>

                <!-- loc / camera /pat -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc" valign="top">Loc de cazare:</td>
                    <td class="text_insc">
                        <?= get_camera_dupa_id($info['id_camera'], $nimic); ?>, patul: <?= $info['nr_pat']; ?>
                        <?php if ($info['nr_pat_2'] != 0) {
                            echo ', pat extra: '.$info['nr_pat_2'];
                        } ?><br/>
                    </td>
                </tr>

                <!-- Utilitati cazare -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc" valign="top">Utilitati cazare:</td>
                    <td class="text_insc">
                        <?php
                        foreach ($arr = explode('|', $info['utilitati_cazare']) AS $item) {
                            echo '- '.$utilitati[$item].'<br/>';
                        }
                        ?><br/></td>
                </tr>

                <!-- Alte detalii cazare -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc" valign="top">Ale detalii cazare:</td>
                    <td class="text_insc"><?= nl2br($info['detalii_cazare']); ?></b><br/><br/></td>
                </tr>

            </table>
            <?php
        }

        if ($info['status_activ'] == 9) {
            ?>


            <div class="page_title18" align="left"><br/>Detalii cazari anterioare [#<?= $info['id'] ?>]</div>

            <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e6f4ff">

                <!-- perioada cazare -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc">Cazat pentru perioada:</td>
                    <td class="text_insc"><b><?= date4html($info['data_start']); ?></b> -> <b><?= date_alarm(date4html($info['data_end']), 7); ?></b><br/></td>
                </tr>

                <!-- loc / camera /pat -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc" valign="top">Loc de cazare:</td>
                    <td class="text_insc">
                        <?= get_camera_dupa_id($info['id_camera'], $nimic); ?>, patul: <?= $info['nr_pat']; ?>
                        <?php if ($info['nr_pat_2'] != 0) {
                            echo 'PAT EXTRA';
                        } ?><br/>
                    </td>
                </tr>

                <!-- Utilitati cazare -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc" valign="top">Utilitati cazare:</td>
                    <td class="text_insc">
                        <?php
                        foreach ($arr = explode('|', $info['utilitati_cazare']) AS $item) {
                            echo '- '.$utilitati[$item].'<br/>';
                        }
                        ?><br/></td>
                </tr>

                <!-- Alte detalii cazare -->
                <tr>
                    <td width="5">&nbsp;</td>
                    <td width="160" class="text_insc" valign="top">Ale detalii cazare:</td>
                    <td class="text_insc"><?= nl2br($info['detalii_cazare']); ?></b><br/><br/></td>
                </tr>

            </table>
            <?php
        }

    }
}

if ($section == 'status_datorii') {

    if (isset($_GET['w']) && is_numeric($_GET['w'])) {
        $cazare_info = get_infocazare_idpersoana($_GET['w']);
        // afisam cazarile in ordinea inversa intamplarii lor
        $cazare_info = array_reverse($cazare_info);     //pa($cazare_info);
    } else {
        echo '<div class="page_title18" align="center">Status datorii locatar indisponibil</div>';
        echo '<center><span class="red">Marca locatarului nu poate fi gasita! Va rugam sa semnalati eroarea departamentului suport!</span></center>';
        die(__FILE__.__LINE__);
    }

    foreach ($cazare_info AS $result) {

        $id_cazare = $result['id'];

        // verific daca locatarul este cazat sau daca are datorii
        if (count($cazare_info) == 0) {
            ?>
            <div class="page_title18" align="center">Status locatar indisponibil</div>
            <table width="95%" cellspacing="0" cellpadding="0" align="center" bgcolor="#DEDFFF">
                <tr height="22" bgcolor="#FFFFFF">
                    <td valign="middle" class="text" align="center"><span class="red"><b>Locatarul <b><?= strtoupper(get_persoana_dupa_id($_GET['w'])) ?></b> nu este cazat</b></span> &rsaquo;
                        <a title="Cazeaza locatar" href="<?= $link_site ?>index.php?page=camere&section=ocupare_camere&locatar=<?= $res['id'] ?>" class="link"> cazeaza </a>
                    </td>
                </tr>
            </table>
            <?php
            die(__FILE__.__LINE__);
        }

        // INFORMATII DESPRE CAZAREA ACTIVA
        if ($result['status_activ'] != 9) {
            // Taxa lunara de cazare
            $q_1 = "	
                SELECT * 
                FROM cs_datorii 
                WHERE 
                    id_cazare='".$id_cazare."' 
                AND id_locatar='".$_GET['w']."' 
                AND tip_datorie=1 
                ORDER BY 
                    data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_1 = $db->Execute($q_1);
            $result_1 = $r_1->GetRows(); //pa($result_1);

            //============================================================================================
            // Taxa lunara utilitati (echipamente electrice)
            $q_2 = "	
			SELECT 
			    cs_datorii.*
               /*,    
				cs_datorii.data_start, 
				cs_datorii.data_start, 
				cs_datorii.data_end
				cs_incasari.serie_chitanta, 
				cs_incasari.numar_chitanta, 
				cs_incasari.data_chitanta, 
				cs_incasari.valoare_chitanta, 
				cs_incasari_detalii.suma_platita, 
				cs_incasari_detalii.suma_penalizare, 
				cs_incasari.status_chitanta
            */
			FROM camin_studentesc.cs_datorii 
			LEFT JOIN camin_studentesc.cs_incasari_detalii ON cs_datorii.id = cs_incasari_detalii.id_datorie
			LEFT JOIN camin_studentesc.cs_incasari ON cs_incasari.id_incasare = cs_incasari_detalii.id_incasare
			WHERE 1 
                AND cs_datorii.id_cazare = '".$id_cazare."' 
                AND id_locatar='".$_GET['w']."' 
                AND tip_datorie = 2
			GROUP BY 
			    cs_datorii.id,
			    cs_datorii.data_start					
			ORDER BY cs_datorii.data_start ASC
			";
            //$db->debug = true;
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            //printSQL2($q_2);
            $result_2 = $db->GetArray($q_2);
            //parr($result_2);
            //$db->debug = false;

            // Garantie camin (taxa per cazare)
            $q_3 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie=3 ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_3 = $db->Execute($q_3);
            $result_3 = $r_3->GetRows(); //pa($result_3);

            // Degradari baza materiala camin
            $q_4 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie=4 ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_4 = $db->Execute($q_4);
            $result_4 = $r_4->GetRows(); //pa($result_4);

            // Alte taxe (neprecizate)
            $q_5 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie NOT IN (1,2,3,4) ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_5 = $db->Execute($q_5);
            $result_5 = $r_5->GetRows(); //pa($result_5);

            $i1 = $i2 = $i3 = $i4 = $i5 = 1;

            ?>
            <!-- DATORII CURENTE -->
            <div class="page_title" align="center">DATORII CURENTE - <?= get_persoana_dupa_id($_GET['w']) ?> [<?= $result['id_persoana'] ?>]</br></div>
            <div class="page_title16" align="center">(<?= date4html($result['data_start']); ?> -> <?= date_alarm(date4html($result['data_end']), 7); ?>)</div>
            <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>


            <!-- TAXA LUNARA DE CAZARE -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>TAXA LUNARA DE CAZARE</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="genereaza taxa" href="<?= $link_site ?>.?page=info_locatar&section=generare_datorii&w=<?= $_GET['w'].'&d='.$id_cazare.'-1'; ?>" class="link">GENERARE
                            AUTOMATA<br> (pentru lunile de cazare)</a></td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;   text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-1'; ?>" class="link">ADAUGARE TAXA</a>
                    </td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" width="10">crt.</td>
                    <td class="tablehead" width="90">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead" width="200">Detalii datorie</td>
                    <td class="tablehead" width="30">Status</td>
                    <td class="tablehead" width="40">Suma reducere (RON)</td>
                    <td class="tablehead" width="40">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                    foreach ($result_1 AS $c) {
                    //pa($c);

                    ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text" align="center"><?= $i1++; ?></td>
                        <td class="text" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                        <td class="text" align="center"><?= $c['text_datorie'] ?></td>
                        <td class="text" align="center">
                            <?php
                            if ($c['status_corelare'] == 0) {
                                echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>';
                            } else {
                                echo '<span tip="neachitat - '.$c['text_reducere'].'" class="bluebull">&bull;</span>';
                            }
                            ?>
                        </td>
                        <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <?php
                            if (($c['status_datorie'] == 2) && in_array($_SESSION['iduser'], $arr_corelare_users) || ($c['status_corelare'] > 0)) {
                                ?>
                                <a title="Corelare datorie incasata in avans" tip="Corelare datorie incasata in avans" href="<?= $link_site.'index.php?page=info_locatar&section=corelare_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"
                                   onclick="confirmare('Doriti sa modificati datoria curenta [DATORIA ESTE DEJA ACHITATA]?');return false;">Corelare</a> |

                                                                                                                                                         <!--<a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>-->

                                <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                                <?php
                            } elseif (($c['status_datorie'] == 2) && !in_array($_SESSION['iduser'], $arr_corelare_users)) {
                                ?>
                                <!--<a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |

				<a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>

				<a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>-->Datorie incasata
                                <?php
                            } else {
                                ?>
                                <a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |

                                <a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>

                                <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/><br/>

            <!-- Taxa lunara utilitati (echipamente electrice) -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>TAXA LUNARA UTILITATI<br> (echipamente electrice)</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="genereaza taxa" href="<?= $link_site ?>.?page=info_locatar&section=generare_datorii&w=<?= $_GET['w'].'&d='.$id_cazare.'-2'; ?>" class="link">GENERARE
                            AUTOMATA<br> (pentru lunile de cazare)</a></td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;   text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-2'; ?>" class="link">ADAUGARE TAXA</a>
                    </td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" width="10">crt.</td>
                    <td class="tablehead" width="90">Perioada</td>
                    <td class="tablehead" width="60" tip="Suma datorie exprimata in RON inmultita cu <br>numarul de echipamente electrice pe luna">Suma datorie (RON)<br/><span class="blue">x</span>(nr. ec. electr.)</td>
                    <td class="tablehead" width="200">Detalii datorie</td>
                    <td class="tablehead" width="30">Status</td>
                    <td class="tablehead" width="40">Suma reducere (RON)</td>
                    <td class="tablehead" width="40">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php foreach ($result_2 AS $c) {
                    //pa($c);
                    if ($c['status_chitanta'] == 20 && 0) {
                        //
                    } else {

                        // show incoming details
                        if ($c['status_datorie'] == 2) {

                            unset($incoming_details, $incoming_details_display);

                            $incoming_details[] = '- chitanta '.$c['serie_chitanta'].' '.$c['numar_chitanta'].' / '.$c['data_chitanta'];
                            $incoming_details[] = $c['valoare_chitanta'].' (suma datorie '.$c['suma_platita'].' + pen.'.$c['suma_penalizare'].')';
                            //$incoming_details[] = strip_tags($c['semnificatie_incasare']);

                            $incoming_details_display = implode("<br>", $incoming_details);
                        } else {
                            $incoming_details_display = '';
                        }


                        ?>
                        <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                            <td class="text" align="center"><?= $i2++; ?></td>
                            <td class="text" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                            <td class="text" align="right">(<?= numar_utilitati_cazare($_GET['w'], $c['data_start'], null, false, $id_cazare) ?>) <span class="blue"> <?= $c['suma_datorie'] ?></span></td>
                            <td class="text" align="center" tip="<?= $c['text_datorie'] ?>"><?= $c['text_datorie'] ?></td>
                            <td class="text" align="center">
                                <?php
                                if ($c['status_corelare'] == 0) {
                                    echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat '.$incoming_details_display.'" class="greenbull">&bull;</span>';
                                } else {
                                    echo '<span tip="'.$c['text_reducere'].'" class="bluebull">&bull;</span>';
                                }
                                ?>
                            </td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                            <td class="text" width="150" align="center">
                                <?php
                                if (($c['status_datorie'] == 2) && in_array($_SESSION['iduser'], $arr_corelare_users)) {
                                    ?>
                                    <a title="Corelare datorie incasata in avans" tip="Corelare datorie incasata in avans" href="<?= $link_site.'index.php?page=info_locatar&section=corelare_datorie_utilitati&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"
                                       onclick="confirmare('Doriti sa modificati datoria curenta [DATORIA ESTE DEJA ACHITATA]?');return false;">Corelare</a> |

                                                                                                                                                             <!--<a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>-->

                                    <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"
                                       onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                                    <?php
                                } elseif (($c['status_datorie'] == 2) && !in_array($_SESSION['iduser'], $arr_corelare_users)) {
                                    ?>
                                    <!--<a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |

				<a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>

				<a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>-->Datorie incasata
                                    <?php
                                } else {
                                    ?>
                                    <a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |

                                    <a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a> |

                                    <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"
                                       onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </table>
            <br/><br/>

            <!-- GARANTIE CAMIN (taxa per cazare) -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>GARANTIE CAMIN<br> (taxa per cazare)</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>index.php?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-3'; ?>" class="link">ADAUGARE
                            GARANTIE (per cazare)</a></td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" width="10">crt.</td>
                    <td class="tablehead" width="90">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead" width="200">Detalii datorie</td>
                    <td class="tablehead" width="30">Status</td>
                    <td class="tablehead" width="40">Suma reducere (RON)</td>
                    <td class="tablehead" width="40">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php if (count($result_3) > 0) {
                    foreach ($result_3 AS $c) { ?>
                        <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                            <td class="text" align="center"><?= $i3++; ?></td>
                            <td class="text" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                            <td class="text" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                            <td class="text" align="center"><?= $c['text_datorie'] ?></td>
                            <td class="text" align="center">
                                <?php
                                if ($c['status_corelare'] == 0) {
                                    echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>';
                                } else {
                                    echo '<span tip="neachitat - '.$c['text_reducere'].'" class="bluebull">&bull;</span>';
                                }
                                ?>

                            </td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                            <td class="text" width="150" align="center">

                                <?php
                                if (($c['status_datorie'] == 2) && in_array($_SESSION['iduser'], $arr_corelare_users)) {
                                    ?>
                                    <a title="Corelare datorie incasata in avans" tip="Corelare datorie incasata in avans" href="<?= $link_site.'index.php?page=info_locatar&section=corelare_garantie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"
                                       onclick="confirmare('Doriti sa modificati datoria curenta [DATORIA ESTE DEJA ACHITATA]?');return false;">Corelare</a> |

                                                                                                                                                             <!--<a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>-->

                                    <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"
                                       onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                                    <?php
                                } elseif (($c['status_datorie'] == 2) && !in_array($_SESSION['iduser'], $arr_corelare_users)) {
                                    ?>
                                    <!--<a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |

				<a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>

				<a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>-->Datorie incasata
                                    <?php
                                } else {
                                    ?>
                                    <a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                                    <a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a> |
                                    <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"
                                       onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                                    <?php
                                }
                                ?>
                                <!--
old before corelare
				<a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12"  onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
				<a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
				<a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>

-->
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
            <br/><br/>

            <!-- DEGRADARI BAZA MATERIALA CAMIN -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>Degradari baza materiala camin</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333; text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-4'; ?>" class="link">ADAUGARE TAXA<br/>(degradari
                            baza materiala camin)</a></td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" width="10">crt.</td>
                    <td class="tablehead" width="90">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead" width="200">Detalii datorie</td>
                    <td class="tablehead" width="30">Status</td>
                    <td class="tablehead" width="40">Suma reducere (RON)</td>
                    <td class="tablehead" width="40">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                if (count($result_4) > 0) {
                    foreach ($result_4 AS $c) { ?>
                        <tr style="background-color:#e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                            <td class="text" align="center"><?= $i4++; ?></td>
                            <td class="text" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                            <td class="text" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                            <td class="text" align="center"><?= $c['text_datorie'] ?></td>
                            <td class="text" align="center">
                                <?php
                                if ($c['status_datorie'] == 1) {
                                    echo '<span tip="neachitat" class="redbull">&bull;</span>';
                                } elseif ($c['status_datorie'] == 2) {
                                    echo '<span tip="achitat" class="greenbull">&bull;</span>';
                                } else {
                                    echo $c['status_datorie'];
                                }
                                ?>
                            </td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                            <td class="text" width="150" align="center">
                                <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                                <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                                <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            </table>
            <br/><br/>

            <!-- ALTE TAXE -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>ALTE TAXE</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333; text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-5'; ?>" class="link">ADAUGARE ALTE
                            TAXE</a></td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" width="10">crt.</td>
                    <td class="tablehead" width="90">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead" width="200">Detalii datorie</td>
                    <td class="tablehead" width="30">Status</td>
                    <td class="tablehead" width="40">Suma reducere (RON)</td>
                    <td class="tablehead" width="40">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                if (count($result_5) > 0) {
                    foreach ($result_5 AS $c) { ?>
                        <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                            <td class="text" align="center"><?= $i5++; ?></td>
                            <td class="text" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                            <td class="text" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                            <td class="text" align="center"><?= $c['text_datorie'] ?></td>
                            <td class="text" align="center">
                                <?php
                                if ($c['status_corelare'] == 0) {
                                    echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>';
                                } else {
                                    echo '<span tip="neachitat - '.$c['text_reducere'].'" class="bluebull">&bull;</span>';
                                }
                                ?>
                            </td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                            <td class="text" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                            <td class="text" width="150" align="center">
                                <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                                <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                                <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            </table>
            <br/><br/>

            <?php

        }

        // INFORMATII DESPRE CAZAREA ANTERIOARA
        if ($result['status_activ'] == 9) {

            // Taxa lunara de cazare
            $q_1 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie=1 ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_1 = $db->Execute($q_1);
            $result_1 = $r_1->GetRows(); //pa($result_1);

            // Taxa lunara utilitati (echipamente electrice)
            $q_2 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie=2 ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_2 = $db->Execute($q_2);
            $result_2 = $r_2->GetRows(); //pa($result_2);

            // Garantie camin (taxa per cazare)
            $q_3 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie=3 ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_3 = $db->Execute($q_3);
            $result_3 = $r_3->GetRows(); //pa($result_3);

            // Degradari baza materiala camin
            $q_4 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie=4 ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_4 = $db->Execute($q_4);
            $result_4 = $r_4->GetRows(); //pa($result_4);

            // Alte taxe (neprecizate)
            $q_5 = "SELECT * FROM cs_datorii WHERE id_cazare='".$id_cazare."' AND id_locatar='".$_GET['w']."' AND tip_datorie NOT IN (1,2,3,4) ORDER BY data_start ASC";
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $r_5 = $db->Execute($q_5);
            $result_5 = $r_5->GetRows(); //pa($result_5);

            $i1 = $i2 = $i3 = $i4 = $i5 = 1;


            if (count($cazare_info) > 1) {
                echo '<br/><br/><br/><hr style="border: 1px solid red; " /><hr style="border: 1px solid red; " />';
            }
            ?>
            <!-- DATORII CURENTE -->
            <div class="page_title" align="center">STATUS DATORII CAZARE ANTERIOARA - <?= get_persoana_dupa_id($_GET['w']) ?> [<?= $result['id_persoana'] ?>]</div>
            <div class="page_title16" align="center">(<?= date4html($result['data_start']); ?> -> <?= date_alarm(date4html($result['data_end']), 7); ?>)</div>
            <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>


            <!-- TAXA LUNARA DE CAZARE -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>TAXA LUNARA DE CAZARE</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;   text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-1'; ?>" class="link">ADAUGARE TAXA</a>
                    </td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                foreach ($result_1 AS $c) {
                    //pa($c);

                    ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text" width="10" align="center"><?= $i1++; ?></td>
                        <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" width="40" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                        <td class="text" width="250" align="center"><?= $c['text_datorie'] ?></td>
                        <td class="text" width="30" align="center">
                            <?php
                            if ($c['status_corelare'] == 0) {
                                echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>';
                            } else {
                                echo '<span tip="neachitat - '.$c['text_reducere'].'" class="bluebull">&bull;</span>';
                            }
                            ?>
                        </td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |

                            <a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>

                            <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/><br/>

            <!-- Taxa lunara utilitati (echipamente electrice) -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>TAXA LUNARA UTILITATI<br> (echipamente electrice)</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;&nbsp;&nbsp;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;   text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-2'; ?>" class="link">ADAUGARE TAXA</a>
                    </td>
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

                <?php foreach ($result_2 AS $c) {
                    //pa($c);
                    ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text" width="10" align="center"><?= $i2++; ?></td>
                        <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                        <td class="text" width="40" align="right">(<?= numar_utilitati_cazare($_GET['w'], $c['data_start'],null, false, $id_cazare) ?>) <span class="blue"> <?= $c['suma_datorie'] ?></span></td>
                        <td class="text" width="250" align="center" tip="<?= $c['text_datorie'] ?>"><?= $c['text_datorie'] ?></td>
                        <td class="text" width="30" align="center">
                            <?php
                            if ($c['status_corelare'] == 0) {
                                echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>';
                            } else {
                                echo '<span tip="neachitat - '.$c['text_reducere'].'" class="bluebull">&bull;</span>';
                            }
                            ?>
                        </td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                        <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                        <td class="text" width="150" align="center">
                            <a title="Editeaza datorie" href="<?= $link_site.'index.php?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |

                            <a title="Sterge datorie" href="<?= $link_site.'index.php?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>

                            <a title="Emite chitanta" href="<?= $link_site.'index.php?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <br/><br/>

            <!-- GARANTIE CAMIN (taxa per cazare) -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>GARANTIE CAMIN<br> (taxa per cazare)</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333;  text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo;</td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>
                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                if (count($result_3) > 0) {
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
                                &nbsp;
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
            <br/><br/>

            <!-- DEGRADARI BAZA MATERIALA CAMIN -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>Degradari baza materiala camin</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333; text-align:left;">&nbsp;&nbsp;&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-4'; ?>" class="link">ADAUGARE TAXA<br/>(degradari
                            baza materiala camin)</a></td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                if (count($result_4) > 0) {
                    foreach ($result_4 AS $c) { ?>
                        <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                            <td class="text" width="10" align="center"><?= $i4++; ?></td>
                            <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                            <td class="text" width="40" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                            <td class="text" width="250" align="center"><?= $c['text_datorie'] ?></td>
                            <td class="text" width="30" align="center">
                                <?php
                                if ($c['status_datorie'] == 1) {
                                    echo '<span tip="neachitat" class="redbull">&bull;</span>';
                                } elseif ($c['status_datorie'] == 2) {
                                    echo '<span tip="achitat" class="greenbull">&bull;</span>';
                                } else {
                                    echo $c['status_datorie'];
                                }
                                //=================================================================================
                                ?>
                            </td>
                            <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                            <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                            <td class="text" width="150" align="center">
                                <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                                <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                                <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            </table>
            <br/><br/>

            <!-- ALTE TAXE -->

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" colspan="3"><b>ALTE TAXE</b></td>
                    <td class="tablehead" bgcolor="white" colspan="3" style="color:#333; text-align:left;">&nbsp;&rsaquo; <a title="adauga taxa" href="<?= $link_site ?>.?page=info_locatar&section=adauga_datorie&w=<?= $_GET['w'].'&d='.$id_cazare.'-5'; ?>" class="link">ADAUGARE ALTE TAXE</a></td>
                    <td class="tablehead" bgcolor="white" colspan="2" style="color:#333;"></td>
                </tr>
                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead">crt.</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead" width="60">Suma datorie (RON)</td>
                    <td class="tablehead">Detalii datorie</td>
                    <td class="tablehead">Status</td>
                    <td class="tablehead">Suma reducere (RON)</td>
                    <td class="tablehead">Scutire penalizare (RON)</td>
                    <td class="tablehead">Actiuni</td>
                </tr>

                <?php
                if (count($result_5) > 0) {
                    foreach ($result_5 AS $c) { ?>
                        <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                            <td class="text" width="10" align="center"><?= $i5++; ?></td>
                            <td class="text" width="80" align="center"><?= date4html($c['data_start']) ?><br/><?= date4html($c['data_end']) ?></td>
                            <td class="text" width="40" align="right"><span class="blue"><?= $c['suma_datorie'] ?></span></td>
                            <td class="text" width="250" align="center"><?= $c['text_datorie'] ?></td>
                            <td class="text" width="30" align="center">
                                <?php
                                if ($c['status_corelare'] == 0) {
                                    echo ($c['status_datorie'] == 1) ? '<span tip="neachitat" class="redbull">&bull;</span>' : '<span tip="achitat" class="greenbull">&bull;</span>';
                                } else {
                                    echo '<span tip="neachitat - '.$c['text_reducere'].'" class="bluebull">&bull;</span>';
                                }
                                ?>
                            </td>
                            <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere'] ?>)"><?= $c['suma_reducere'] ?></span></td>
                            <td class="text" width="20" align="right"><span class="red" tip="(<?= $c['text_reducere_penalizare'] ?>)"><?= $c['suma_reducere_penalizare'] ?></span></td>

                            <td class="text" width="150" align="center">
                                <a title="Editeaza datorie" href="<?= $link_site.'?page=info_locatar&section=editeaza_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa modificati datoria curenta?');return false;">Editeaza</a> |
                                <a title="Sterge datorie" href="<?= $link_site.'?page=info_locatar&section=sterge_datorie&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa stergeti datoria curenta?');return false;">Sterge</a>
                                <a title="Emite chitanta" href="<?= $link_site.'?page=incasari&section=emitere_chitanta&w='.$_GET['w'].'&d='.$c['id_datorie'].'-'.$c['id']; ?>" class="link12" onclick="confirmare('Doriti sa emiteti chitanta pentru aceasta suma?');return false;">Incaseaza</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            </table>
            <br/><br/>

            <?php
        }


    }
}
// END OF STATUS DATORII ========================================================================================================

/* EDITEAZA DATORIE*/
if ($section == 'editeaza_datorie') {

    list($id_datorie, $id_curent) = explode('-', $_GET['d']);

    $sql = "SELECT * FROM cs_datorii WHERE id_datorie = {$id_datorie} AND id = {$id_curent}";
    $res = getDb()->GetRow($sql);

    //daca datoria este achitata
    in_array($res['status_datorie'], array(2)) ? $edit = 'READONLY' : $edit = '';

    //$nr_zile_cazare_interval = ceil((strtotime($res['data_end']) - strtotime($res['data_start'])) / 86400) + 1;
    echo $nr_zile_cazare_interval = round((strtotime($res['data_end'].' 23:59:59') - strtotime($res['data_start'].' 00:00:01')) / 86400);
    list($nr_zile_cazare_interval, $nothing) = explode(".", $nr_zile_cazare_interval = ((strtotime($res['data_end'].' 23:59:59') - strtotime($res['data_start'].' 00:00:01')) / 86400));

    echo ' - '.$nr_zile_cazare_interval;

    $nr_zile_luna_interval = date("d", strtotime($res['data_end']));

    $res['suma_datorie_old'] = $res['suma_datorie'];

    $suma_datorie_modificata = 0;

    ?>
    <div class="page_title" align="center">EDITEAZA DATORIE - <?= get_persoana_dupa_id($res['id_locatar']) ?></div>
    <?php
    if (in_array($res['status_datorie'], array(2, 66))) {
        echo '<div align="center" style="color:red; font-size:12px;"><b>* ATENTIE,</b> aceasta datorie este considerata <strong>ACHITATA</strong>!</div><br/>';
    }
    ?>

    <form method="POST" action="">
        <input type="hidden" name="to_do" value="editeaza_datorie_do">
        <input type="hidden" name="id" value="<?= $_GET['d']; ?>">

        <table border=0 cellspacing=5 cellpadding=0 width="100%" align="center" bgcolor='#a1c2e7'>
            <tr>
                <td class="text" align="left" width="30%">Tip datorie</td>
                <td class=text align="left">
                    <select class="selectx2" <?= $edit ?> name="tip_datorie">
                        <?= write_select($arr_tip_taxa, $res['tip_datorie']); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Perioada de plata</td>
                <td class=text align="left"><input type=text name="data_start" <?= $edit ?> class="input_100" value="<?= date4html($res['data_start']) ?>">-<input type=text name="data_end" <?= $edit ?> class="input_100" value="<?= date4html($res['data_end']); ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma datorie (RON)</td>
                <td class=text align="left">
                    <?php if ($suma_datorie_modificata == 1) {
                        echo '<span class="red">* Datorie modificata pentru un numar de ['.$nr_zile_cazare_interval.'] zile.<br>** Suma pentru o luna intreaga este '.$res['suma_datorie_old'].' RON</span>';
                    }
                    ?>
                    <input type=text name="suma_datorie" <?= $edit ?> class="input" value="<?= $res['suma_datorie'] ?>">
                </td>
            </tr>

            <tr>
                <td class="text" align="left">Text datorie</td>
                <td class=text align="left"><input type=text name="text_datorie" <?= $edit ?> class="input" style="width:500px" value="<?= $res['text_datorie'] ?>"></td>
            </tr>

            <?php
            if ($res['tip_datorie'] == 1) { ?>
                <tr>
                    <td class="text" align="left">Fise spalat+uscat (* pret curent UM <?php echo getLaundryUnitPrice() ?> RON)</td>
                    <td class=text align="left"><input type="text" name="fise" <?= $edit ?> class="input_20" value="<?= $res['fise'] ?>" maxlength="2"></td>
                </tr>
                <?php
            }
            ?>

            <tr>
                <td class="text" align="left">Suma reducere (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere" <?= $edit ?> class="input" value="<?= $res['suma_reducere'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere" <?= $edit ?> class="input" value="<?= $res['text_reducere'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere penalizare (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['suma_reducere_penalizare'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere penalizare<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['text_reducere_penalizare'] ?>"></td>
            </tr>

            <tr>
                <td align="left">&nbsp;</td>
                <td><input type='submit' name="submit" class="button_albastru" value='Modifica'><input type='reset' class="button_albastru" value='Renunta'></td>
            </tr>
        </table>
    </form>
    <?php
}

/* EDITEAZA DATORIE DO */
if ($_POST['to_do'] == 'editeaza_datorie_do') {

    list($id_datorie, $id) = explode('-', $_POST['id']);
    unset($_POST['id']);

    $clean = $_POST;

    if ($clean['tip_datorie'] == 1) {
        $sql = "SELECT * FROM cs_datorii WHERE id_datorie={$id_datorie} AND id={$id}";
        $datorie = getDb()->GetRow($sql);

        // fise nou adaugate pe datorie fara fise
        if ($clean['fise'] > 0 && $datorie['fise'] == 0) {
            $clean['suma_datorie'] = $clean['suma_datorie'] + $clean['fise'] * getLaundryUnitPrice();
            $clean['text_datorie'] = $clean['text_datorie'].' (fise:'.$clean['fise'].')';
        }

        // fise pe datorie dar modificate
        if ($clean['fise'] > 0 && $datorie['fise'] > 0 && $clean['fise'] != $datorie['fise']) {
            $clean['suma_datorie'] = $clean['suma_datorie'] - $datorie['fise'] * getLaundryUnitPrice() + $clean['fise'] * getLaundryUnitPrice();
            $clean['text_datorie'] = str_replace(sprintf(" (fise:%d)", $datorie['fise']), sprintf(" (fise:%d)", $clean['fise']), $datorie['text_datorie']);
        }

        // fise pe datorie + cerere de resetare
        if ($clean['fise'] == 0 && $datorie['fise'] > 0) {
            $clean['suma_datorie'] = $datorie['suma_datorie'] - $datorie['fise'] * getLaundryUnitPrice();
            $clean['text_datorie'] = str_replace(sprintf(" (fise:%d)", $datorie['fise']), "", $datorie['text_datorie']);
        }

    }

    $clean['data_start'] = date4sql($clean['data_start']);
    $clean['data_end'] = date4sql($clean['data_end']);

    if ($clean['suma_datorie'] == $clean['suma_reducere']) {
        $clean['status_datorie'] = 66;
        $clean['text_reducere'] = 'Taxa achitata prin reducerea totala a taxei';
    }

    if ($clean['suma_reducere'] == 0) {
        $clean['status_datorie'] = 1;
        $clean['text_reducere'] = 'Nu se acorda.';
    }

    // verificari
    $ok = 1;
    if (strtotime($clean['data_start']) >= strtotime($clean['data_end'])) {
        $msg .= '&msg=Modificare nu a fost efectuata! Perioada de plata necorespunzatoare!';
        $ok--;
    }

    if ($ok == 1) {
        $db->AutoExecute('cs_datorii', $clean, 'UPDATE', "id = ".$id."");
        $msg .= '&msg=Modificare efectuata cu succes!';
    }

    header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$_GET['w'].$msg.'');

}

/* CORELARE DATORIE TAXA CAZARE */
if ($section == 'corelare_datorie') {

    list($id_datorie, $id_curent) = explode('-', $_GET['d']); // id_datorie = id cazare  trebuie scos candva id datorie curent este al doilea parametru

    $q = "SELECT * FROM cs_datorii WHERE id_datorie=$id_datorie AND id=$id_curent";
    $r = $db->Execute($q);
    $res = $r->GetRowAssoc($toUpper = false); //pa($res);

    //daca datoria este achitata
    //in_array($res['status_datorie'], array(2))? $edit = 'READONLY' : $edit = '';

    //$nr_zile_cazare_interval = ceil((strtotime($res['data_end']) - strtotime($res['data_start'])) / 86400) + 1;
    $nr_zile_cazare_interval = round((strtotime($res['data_end'].' 23:59:59') - strtotime($res['data_start'].' 00:00:01')) / 86400);
    echo '<!-- Interval zile cazare calculat de aplicatie: '.$nr_zile_cazare_interval.' -->'."\n";
    $nr_zile_luna_interval = date("d", strtotime($res['data_end']));

    $res['suma_datorie_old'] = $res['suma_datorie'];

    $suma_datorie_modificata = 0;
    /*
	if(($nr_zile_cazare_interval < $nr_zile_luna_interval) && ($res['text_reducere'] == 'Nu se acorda.'))
	{
		$res['suma_datorie'] = number_format($nr_zile_cazare_interval / $nr_zile_luna_interval * $res['suma_datorie'], 2);
		$suma_datorie_modificata = 1;
		$res['text_reducere'] = 'taxa redusa pentru '.$nr_zile_cazare_interval.' zile' ;
	}
	*/

    /* cauta chitanta cu care s-a achitat datoria */
    $q_chitanta = "
        SELECT cs_incasari_detalii.*, cs_incasari.*
        FROM cs_incasari_detalii
        INNER JOIN cs_incasari ON cs_incasari_detalii.id_incasare = cs_incasari.id_incasare
        WHERE 1
        AND cs_incasari_detalii.id_datorie = ".$id_curent."		
        AND status_chitanta = 10";                    // chitanta incasata

    //psql($q_chitanta);

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
//	$r_chitanta = $db->Execute($q_chitanta);
    $res_chitanta = $db->GetRow($q_chitanta);

    echo '<!-- ';
    pa($res_chitanta);
    echo ' -->';

    ?>
    <div class="page_title" align="center">CORELARE DATORIE - <?= get_persoana_dupa_id($res['id_locatar']) ?></div>
    <div class="page_title" align="center">* datoria a fost achitata in avans</div>
    <?
    if (in_array($res['status_datorie'], array(2, 66))) {
        echo '<div align="center" style="color:red; font-size:12px;"><b>* ATENTIE,</b> datoria este considerata in acest moment <strong>ACHITATA</strong><br/>Dupa MODIFICARE va fi considerata <strong>NEACHITATA</strong> !</div><br/>';
    }
    ?>

    <form method="POST" action="" id="corelare" name="corelare">
        <input type="hidden" name="to_do" value="corelare_datorie_do">
        <input type="hidden" name="id" value="<?= $_GET['d']; ?>">

        <table border=0 cellspacing=5 cellpadding=0 width="100%" align="center" bgcolor='#a1c2e7'>
            <tr>
                <td class="text" align="left" width="30%">Tip datorie</td>
                <td class=text align="left">
                    <select class="selectx2" <?= $edit ?> name="tip_datorie">
                        <?= write_select($arr_tip_taxa, $res['tip_datorie']); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Perioada de plata</td>
                <td class=text align="left"><input type=text name="data_start" <?= $edit ?> class="input_100" value="<?= date4html($res['data_start']) ?>">-<input type=text name="data_end" <?= $edit ?> class="input_100" value="<?= date4html($res['data_end']); ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma datorie (RON)</td>
                <td class=text align="left">
                    <?php if ($suma_datorie_modificata == 1) {
                        echo '<span class="red">* Datorie modificata pentru un numar de ['.$nr_zile_cazare_interval.'] zile.<br>** Suma pentru o luna intreaga este '.$res['suma_datorie_old'].' RON</span>';
                    }
                    ?>
                    <input type=text name="suma_datorie" <?= $edit ?> class="input" style="border:1px solid red" value="<?= $res['suma_datorie'] ?>">
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Text datorie</td>
                <td class=text align="left"><input type=text name="text_datorie" <?= $edit ?> class="input" style="width:500px" value="<?= $res['text_datorie'] ?>"></td>
            </tr>

            <?php
            if ($res['tip_datorie'] == 1) { ?>
                <tr>
                    <td class="text" align="left">Fise spalat+uscat (* pret curent UM <?php echo getLaundryUnitPrice() ?> RON)</td>
                    <td class=text align="left"><input type="text" name="fise" <?= $edit ?> class="input_20" value="<?= $res['fise'] ?>" maxlength="2"></td>
                </tr>
                <?php
            }
            ?>


            <tr>
                <td class="text" align="left">Suma reducere (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere" <?= $edit ?> class="input" value="<?= $res_chitanta['valoare_chitanta'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere" <?= $edit ?> class="input" style="width:500px"
                                                   value="Corelare datorie achitata in avans (<?= $res_chitanta['valoare_chitanta'] ?>) | chitanta <?= $res_chitanta['serie_chitanta'].' '.$res_chitanta['numar_chitanta'].', incasata la: '.$res_chitanta['data_chitanta']; ?>"
                                                   onkeyup="document.getElementById('char_counter').innerHTML = (this.value.length) + ' caractere'; "><br/><span id="char_counter"><script> document.write(window.document.corelare.text_reducere.value.length + ' caractere');</script></span></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere penalizare (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['suma_reducere_penalizare'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere penalizare<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['text_reducere_penalizare'] ?>"></td>
            </tr>

            <tr>
                <td align="left">&nbsp;</td>
                <td>
                    <input type='submit' name="submit" class="button_albastru" value='Modifica'>
                    <input type='reset' class="button_albastru" value='Renunta' onclick="history.back();"></td>
            </tr>
        </table>
    </form>
    <?php
}

/* CORELARE DATORIE DO */
if ($_POST['to_do'] == 'corelare_datorie_do') {

    list($id_datorie, $id) = explode('-', $_POST['id']);

    unset($_POST['id']);

    $clean = $_POST;

    if ($clean['tip_datorie'] == 1) {
        $sql = "SELECT * FROM cs_datorii WHERE id_datorie={$id_datorie} AND id={$id}";
        $datorie = getDb()->GetRow($sql);

        // fise nou adaugate pe datorie fara fise
        if ($clean['fise'] > 0 && $datorie['fise'] == 0) {
            $clean['suma_datorie'] = $clean['suma_datorie'] + $clean['fise'] * getLaundryUnitPrice();
            $clean['text_datorie'] = $clean['text_datorie'].' (fise:'.$clean['fise'].')';
        }

        // fise pe datorie dar modificate
        if ($clean['fise'] > 0 && $datorie['fise'] > 0 && $clean['fise'] != $datorie['fise']) {
            $clean['suma_datorie'] = $clean['suma_datorie'] - $datorie['fise'] * getLaundryUnitPrice() + $clean['fise'] * getLaundryUnitPrice();
            $clean['text_datorie'] = str_replace(sprintf(" (fise:%d)", $datorie['fise']), sprintf(" (fise:%d)", $clean['fise']), $datorie['text_datorie']);
        }

        // fise pe datorie + cerere de resetare
        if ($clean['fise'] == 0 && $datorie['fise'] > 0) {
            $clean['suma_datorie'] = $datorie['suma_datorie'] - $datorie['fise'] * getLaundryUnitPrice();
            $clean['text_datorie'] = str_replace(sprintf(" (fise:%d)", $datorie['fise']), "", $datorie['text_datorie']);
        }

    } else {
        $clean['suma_datorie'] = $_POST['suma_datorie'];
        $clean['suma_reducere'] = $_POST['suma_reducere'];
    }

    $clean['data_start'] = date4sql($clean['data_start']);
    $clean['data_end'] = date4sql($clean['data_end']);
    $clean['text_reducere'] = $_POST['text_reducere'];
    $clean['status_datorie'] = 1;
    $clean['status_corelare'] = 1;

    //$db->debug=true;
    $db->AutoExecute('cs_datorii', $clean, 'UPDATE', "id = ".$id."");
    $msg .= '&msg=Modificare efectuata cu succes!';

    header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$_GET['w'].$msg.'');

}


/* CORELARE DATORIE UTILITATI CAZARE */
if ($section == 'corelare_datorie_utilitati') {

    list($id_datorie, $id_curent) = explode('-', $_GET['d']); // id_datorie = id cazare  trebuie scos candva id datorie curent este al doilea parametru

    $q = "SELECT * FROM cs_datorii WHERE id_datorie=$id_datorie AND id=$id_curent";
    $r = $db->Execute($q);
    $res = $r->GetRowAssoc($toUpper = false); //pa($res);

    //daca datoria este achitata
    //in_array($res['status_datorie'], array(2))? $edit = 'READONLY' : $edit = '';

    //$nr_zile_cazare_interval = ceil((strtotime($res['data_end']) - strtotime($res['data_start'])) / 86400) + 1;
    $nr_zile_cazare_interval = round((strtotime($res['data_end'].' 23:59:59') - strtotime($res['data_start'].' 00:00:01')) / 86400);
    echo '<!-- Interval zile cazare calculat de aplicatie: '.$nr_zile_cazare_interval.' -->'."\n";
    $nr_zile_luna_interval = date("d", strtotime($res['data_end']));

    $res['suma_datorie_old'] = $res['suma_datorie'];

    $suma_datorie_modificata = 0;

    /* cauta chitanta cu care s-a achitat datoria */
    $q_chitanta = "SELECT cs_incasari_detalii.*, cs_incasari.*
			FROM cs_incasari_detalii
			INNER JOIN cs_incasari ON cs_incasari_detalii.id_incasare = cs_incasari.id_incasare
			AND cs_incasari_detalii.id_datorie = ".$id_curent."		
			AND status_chitanta = 10";                    // chitanta incasata

    //psql($q_chitanta);

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
//	$r_chitanta = $db->Execute($q_chitanta);
    $res_chitanta = $db->GetRow($q_chitanta);

    echo '<!-- ';
    pa($res_chitanta);
    echo ' -->';


    /* data corespunzatoare pentru fiecare utilitate adaugata */
    $q_ud = "SELECT id_utilitate, cs_persoane_utilitati.* , cs_utilitati.utilitate
		FROM cs_persoane_utilitati 
		INNER JOIN cs_utilitati ON cs_utilitati.id = cs_persoane_utilitati.id_utilitate
		WHERE id_locatar = ".$res_chitanta['id_persoana']." AND id_cazare = ".$res_chitanta['id_cazare']." AND data > '".$res['data_start']."' ";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_ud = $db->Execute($q_ud);
    $utilitati_info = $r_ud->GetRows();

    echo '<!-- ';
    pa($utilitati_info);
    echo ' -->';


    ?>
    <div class="page_title" align="center">CORELARE DATORIE UTILITATI - <?= get_persoana_dupa_id($res['id_locatar']) ?></div>
    <div class="page_title" align="center">* datoria a fost achitata in avans</div>
    <?
    if (in_array($res['status_datorie'], array(2, 66))) {
        echo '<div align="center" style="color:red; font-size:12px;"><b>* ATENTIE,</b> datoria este considerata in acest moment <strong>ACHITATA</strong><br/>Dupa MODIFICARE va fi considerata <strong>NEACHITATA</strong> !</div><br/>';
    }

    echo '<hr>Suma achitata: <b>'.$res_chitanta['suma_platita']." RON </b><br>\n";

    echo 'Suma corespunzatoare si perioada de corelare pentru utilitatile adaugate (data adaugarii > sfarsitul lunii)<br>';
    foreach ($utilitati_info AS $key => $cu) {
        unset($nr_zile_interval);
        $nr_zile_interval[$key] = round((strtotime($res['data_end'].' 23:59:59') - strtotime($utilitati_info[$key]['data'].' 00:00:01')) / 86400);

        $datorie_per_utilitate[$key] = round(20 * $nr_zile_interval[$key] / $nr_zile_luna_interval);

        echo '<span style="color:red; font-size:12px;">'.$datorie_per_utilitate[$key].' RON </span> ';

        echo ' &nbsp; &nbsp;<b>'.$utilitati_info[$key]['utilitate'].':</b>  '.$utilitati_info[$key]['data'].' > '.$res['data_end'].' adica <b>'.$nr_zile_interval[$key].' zile</b>';
        echo "<br>\n\n";

    }

    $datorie_adaugata = @array_sum($datorie_per_utilitate);

    echo "<br><strong>TOTAL: ".$datorie_adaugata." RON</strong><hr>";

    ?>
    <br/>
    <form method="POST" action="" id="corelare" name="corelare">
        <input type="hidden" name="to_do" value="corelare_datorie_utilitati_do">
        <input type="hidden" name="id" value="<?= $_GET['d']; ?>">

        <table border=0 cellspacing=5 cellpadding=0 width="100%" align="center" bgcolor='#a1c2e7'>
            <tr>
                <td class="text" align="left" width="30%">Tip datorie</td>
                <td class=text align="left">
                    <select class="selectx2" <?= $edit ?> name="tip_datorie">
                        <?= write_select($arr_tip_taxa, $res['tip_datorie']); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Perioada de plata</td>
                <td class=text align="left"><input type=text name="data_start" <?= $edit ?> class="input_100" value="<?= date4html($res['data_start']) ?>">-<input type=text name="data_end" <?= $edit ?> class="input_100" value="<?= date4html($res['data_end']); ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma datorie (RON)</td>
                <td class=text align="left">
                    <hr>
                    <input type=text name="suma_datorie" <?= $edit ?> class="input" style="border:1px solid red" value="<?= ($res_chitanta['valoare_chitanta'] + $datorie_adaugata) ?>"><br><span class="red">suma achitata anterior + corelare datorii adaugate in luna curenta</span>
                    <hr>
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Text datorie</td>
                <td class=text align="left"><input type=text name="text_datorie" <?= $edit ?> class="input" style="width:500px" value="<?= $res['text_datorie'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere" readonly class="input" value="<?= $res_chitanta['valoare_chitanta'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere" <?= $edit ?> class="input" style="width:500px"
                                                   value="Corelare taxa utilitati (<?= $res_chitanta['valoare_chitanta'] ?>) | chitanta <?= $res_chitanta['serie_chitanta'].' '.$res_chitanta['numar_chitanta'].', incasata la: '.$res_chitanta['data_chitanta']; ?>"
                                                   onkeyup="document.getElementById('char_counter').innerHTML = (this.value.length) + ' caractere'; "><br/><span id="char_counter"><script> document.write(window.document.corelare.text_reducere.value.length + ' caractere');</script></span></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere penalizare (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['suma_reducere_penalizare'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere penalizare<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['text_reducere_penalizare'] ?>"></td>
            </tr>

            <tr>
                <td align="left">&nbsp;</td>
                <td>
                    <input type='submit' name="submit" class="button_albastru" value='Modifica'>
                    <input type='reset' class="button_albastru" value='Renunta' onclick="history.back();"></td>
            </tr>
        </table>
    </form>
    <?php
}

/* CORELARE DATORIE UTILITATI DO */
if ($_POST['to_do'] == 'corelare_datorie_utilitati_do') {

    pa($_POST);

    list($id_datorie, $id) = explode('-', $_POST['id']);

    unset($_POST['id']);

    $clean['suma_datorie'] = $_POST['suma_datorie'];
    $clean['suma_reducere'] = $_POST['suma_reducere'];
    $clean['text_reducere'] = $_POST['text_reducere'];
    $clean['status_datorie'] = 1;
    $clean['status_corelare'] = 1;

    //$db->debug=true;
    $db->AutoExecute('cs_datorii', $clean, 'UPDATE', "id = ".$id."");
    $msg .= '&msg=Modificare efectuata cu succes!';

    header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$_GET['w'].$msg.'');

}

//=================================================================================================
/* CORELARE DATORIE GARANTIE */
if ($section == 'corelare_garantie') {

    list($id_datorie, $id_curent) = explode('-', $_GET['d']); // id_datorie = id cazare  trebuie scos candva id datorie curent este al doilea parametru

    $q = "SELECT * FROM cs_datorii WHERE id_datorie=$id_datorie AND id=$id_curent";
    $r = $db->Execute($q);
    $res = $r->GetRowAssoc($toUpper = false); //pa($res);

    /*
   [id] => 20255
    [id_taxa] => 24
    [id_datorie] => 724
    [id_cazare] => 724
    [id_locatar] => 1160
    [tip_datorie] => 3
    [data_start] => 2007-11-01
    [data_end] => 2008-06-25
    [suma_datorie] => 500.00
    [text_datorie] => Garantie camin | Camera cu 3 paturi | Student/Masterand
    [suma_reducere] => 400.00
    [text_reducere] => Nu se acorda.
    [suma_reducere_penalizare] => 0.00
    [text_reducere_penalizare] => Nu se acorda.
    [status_datorie] => 2
    [tip_taxa_camera_persoana] =>
    [status_corelare] => 0
	*/

    //daca datoria este achitata
    //in_array($res['status_datorie'], array(2))? $edit = 'READONLY' : $edit = '';

    //$nr_zile_cazare_interval = ceil((strtotime($res['data_end']) - strtotime($res['data_start'])) / 86400) + 1;
//	$nr_zile_cazare_interval = round((strtotime($res['data_end'].' 23:59:59') - strtotime($res['data_start'].' 00:00:01')) / 86400);
//	echo '<!-- Interval zile cazare calculat de aplicatie: '.$nr_zile_cazare_interval.' -->'."\n";
    //$nr_zile_luna_interval = date("d", strtotime($res['data_end']));

    $res['suma_datorie_old'] = $res['suma_datorie'];

    $suma_datorie_modificata = 0;

    /* cauta chitanta cu care s-a achitat datoria */
    $q_chitanta = "SELECT cs_incasari_detalii.*, cs_incasari.*
			FROM cs_incasari_detalii
			INNER JOIN cs_incasari ON cs_incasari_detalii.id_incasare = cs_incasari.id_incasare
			AND cs_incasari_detalii.id_datorie = ".$id_curent."		
			AND status_chitanta = 10";                    // chitanta incasata

    //psql($q_chitanta);

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_chitanta = $db->Execute($q_chitanta);
    $res_chitanta = $db->GetRow($q_chitanta);

    echo '<!-- ';
    pa($res_chitanta);
    echo ' -->';


    /* data corespunzatoare pentru fiecare utilitate adaugata */
//	$q_ud = "SELECT id_utilitate, cs_persoane_utilitati.* , cs_utilitati.utilitate
//		FROM cs_persoane_utilitati
//		INNER JOIN cs_utilitati ON cs_utilitati.id = cs_persoane_utilitati.id_utilitate
//		WHERE id_locatar = ".$res_chitanta['id_persoana']." AND id_cazare = ".$res_chitanta['id_cazare']." AND data > '".$res['data_start']."' ";
    $q_ud = "SELECT id_utilitate, cs_persoane_utilitati.* , cs_utilitati.utilitate
		FROM cs_persoane_utilitati 
		INNER JOIN cs_utilitati ON cs_utilitati.id = cs_persoane_utilitati.id_utilitate
		WHERE id_locatar = ".$res['id_locatar']." AND id_cazare = ".$res['id_cazare']." AND data > '".$res['data_start']."' ";

    //echo $q_ud;
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_ud = $db->Execute($q_ud);
    $utilitati_info = $r_ud->GetRows();

    echo '<!-- ';
    pa($utilitati_info);
    echo ' -->';


    ?>
    <div class="page_title" align="center">CORELARE DATORIE GARANTIE - <?= get_persoana_dupa_id($res['id_locatar']) ?></div>
    <div class="page_title" align="center">* datoria a fost achitata</div>
    <?php
    if (in_array($res['status_datorie'], array(2, 66))) {
        echo '<div align="center" style="color:red; font-size:12px;"><b>* ATENTIE,</b> datoria este considerata in acest moment <strong>ACHITATA</strong><br/>Dupa MODIFICARE va fi considerata <strong>NEACHITATA</strong> !</div><br/>';
    }

    echo '<hr>Suma achitata: <b>'.$res_chitanta['suma_platita']." RON </b><br>\n";

//echo 'Suma corespunzatoare si perioada de corelare pentru utilitatile adaugate (data adaugarii > sfarsitul lunii)<br>';
//foreach ($utilitati_info AS $key => $cu)
//{
//	unset($nr_zile_interval);
//	$nr_zile_interval[$key] = round((strtotime($res['data_end'].' 23:59:59') - strtotime($utilitati_info[$key]['data'].' 00:00:01')) / 86400);
//
//	$datorie_per_utilitate[$key] = round(20 * $nr_zile_interval[$key] / $nr_zile_luna_interval);
//
//	echo '<span style="color:red; font-size:12px;">'. $datorie_per_utilitate[$key] .' RON </span> ';
//
//	echo ' &nbsp; &nbsp;<b>'.$utilitati_info[$key]['utilitate'].':</b>  '.$utilitati_info[$key]['data'].' > '.$res['data_end']. ' adica <b>'. $nr_zile_interval[$key] . ' zile</b>' ;
//	echo "<br>\n\n";
//
//}

//$datorie_adaugata = array_sum($datorie_per_utilitate);

//echo "<br><strong>TOTAL: ".$datorie_adaugata." RON</strong><hr>";

    ?>
    <br/>
    <form method="POST" action="" id="corelare" name="corelare">
        <input type="hidden" name="to_do" value="corelare_garantie_do">
        <input type="hidden" name="id" value="<?= $_GET['d']; ?>">

        <table border=0 cellspacing=5 cellpadding=0 width="100%" align="center" bgcolor='#a1c2e7'>
            <tr>
                <td class="text" align="left" width="30%">Tip datorie</td>
                <td class=text align="left">
                    <select class="selectx2" <?= $edit ?> name="tip_datorie">
                        <?= write_select($arr_tip_taxa, $res['tip_datorie']); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Perioada de plata</td>
                <td class=text align="left"><input type=text name="data_start" <?= $edit ?> class="input_100" value="<?= date4html($res['data_start']) ?>">-<input type=text name="data_end" <?= $edit ?> class="input_100" value="<?= date4html($res['data_end']); ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma datorie (RON)</td>
                <td class=text align="left">
                    <hr>
                    <input type=text name="suma_datorie" <?= $edit ?> class="input" style="border:1px solid red" value="<?= ($res_chitanta['valoare_chitanta'] + $datorie_adaugata) ?>"><br><span class="red">suma achitata anterior + corelare datorii adaugate in luna curenta</span>
                    <hr>
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Text datorie</td>
                <td class=text align="left"><input type=text name="text_datorie" <?= $edit ?> class="input" style="width:500px" value="<?= $res['text_datorie'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere" class="input" value="<?= $res_chitanta['valoare_chitanta'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere" <?= $edit ?> class="input" style="width:500px"
                                                   value="Corelare garantie camin (<?= $res_chitanta['valoare_chitanta'] ?>) | chitanta <?= $res_chitanta['serie_chitanta'].' '.$res_chitanta['numar_chitanta'].', incasata la: '.$res_chitanta['data_chitanta']; ?>"
                                                   onkeyup="document.getElementById('char_counter').innerHTML = (this.value.length) + ' caractere'; "><br/><span id="char_counter"><script> document.write(window.document.corelare.text_reducere.value.length + ' caractere');</script></span></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere penalizare (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['suma_reducere_penalizare'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere penalizare<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['text_reducere_penalizare'] ?>"></td>
            </tr>

            <tr>
                <td align="left">&nbsp;</td>
                <td>
                    <input type='submit' name="submit" class="button_albastru" value='Modifica'>
                    <input type='reset' class="button_albastru" value='Renunta' onclick="history.back();"></td>
            </tr>
        </table>
    </form>
    <?php
}

/* CORELARE DATORIE GARANTIE DO */
if ($_POST['to_do'] == 'corelare_garantie_do') {

    //pa($_POST);

    list($id_datorie, $id) = explode('-', $_POST['id']);

    unset($_POST['id']);

    $clean['suma_datorie'] = $_POST['suma_datorie'];
    $clean['suma_reducere'] = $_POST['suma_reducere'];
    $clean['text_reducere'] = $_POST['text_reducere'];
    $clean['status_datorie'] = 1;
    $clean['status_corelare'] = 1;

    //$db->debug=true;

    //pa($clean);

    //die;

    $db->AutoExecute('cs_datorii', $clean, 'UPDATE', "id = ".$id."");
    $msg .= '&msg=Modificare efectuata cu succes!';

    header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$_GET['w'].$msg.'');

}

//=================================================================================================


/* ADAUGARE DATORIE*/
if ($section == 'adauga_datorie') {
    //pa($_REQUEST);

    list($id_datorie, $tip_datorie) = explode('-', $_GET['d']);
    $id_locatar = intval($_GET['w']);

    $q = "SELECT * FROM cs_persoane_camere WHERE id_persoana=$id_locatar";
    $r = $db->Execute($q);
    $info_cazare = $r->GetRowAssoc($toUpper = false);

    //pa($info_cazare);

    switch ($tip_datorie) {
        case 1:

            break;

        case 3:

            $start_date = date4html($info_cazare['data_start']);
            $end_date = date4html($info_cazare['data_end']);
            break;

        case 4:
            $start_date = date("d-m-Y");
            $end_date = date("d-m-Y", strtotime("today +1month"));
            break;

        case 5:
            $start_date = date("d-m-Y");
            $end_date = date("d-m-Y", strtotime("today +1month"));
            break;
    }

    // start precompletare campuri datorii

    // aflu tipul camerei
    $q_cam = "SELECT nr_paturi FROM cs_camere WHERE id = ".$info_cazare['id_camera'].";";
    $r_cam = $db->Execute($q_cam);
    $res_cam = $r_cam->FetchRow();

    $tip_camera = $res_cam['nr_paturi'] - 1;
    if ($res1['camera_integral'] == 1) {
        $tip_camera = $tip_camera + 3;
    }

    // aflu tipul persoanei - taxat ca student sau taxat ca angajat
    $q_pers = "SELECT tip_persoana FROM cs_persoane WHERE id = ".$id_locatar.";";
    $r_pers = $db->Execute($q_pers);
    $res_pers = $r_pers->FetchRow();

    $tip_persoana = $res_pers['tip_persoana'];

    /*	Aici aflu taxa la care se incadreaza micutul am la dispozitie $id_cazare, $id_persoana si $tip_datorie */
    $q_taxa = "SELECT * FROM cs_taxa WHERE tip_taxa = $tip_datorie AND tip_camera = $tip_camera AND tip_persoana = $tip_persoana";
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_taxa = $db->Execute($q_taxa);
    $result_taxa = $r_taxa->GetRows(); //pa($result_taxa);

    if (count($result_taxa) == 0) {
        $msg = "&msg=Nu este definita taxa corespunzatoare pentru<br/>".$arr_tip_taxa[$tip_datorie]." | ".$arr_tip_camera[$tip_camera]." | ".$arr_tip_persoana[$tip_persoana];
    }

    $result_taxa = $result_taxa[0]; //pa($result_taxa);

    $id_taxa = $result_taxa['id'];
    $suma_datorie = $result_taxa['valoare_taxa'];
    $text_datorie = str_replace("#", " | ", $result_taxa['text_taxa']);

    if ($tip_datorie == 5) {
        $text_datorie = 'Degradare baza materiala camin';
    }

    // end precompletare datorii
    ?>
    <div class="page_title18" align="center">ADAUGA DATORIE - <?= get_persoana_dupa_id($id_locatar) ?><br/><?= $arr_tip_taxa[$tip_datorie]; ?></div>
    <div class="msg" align="center"><?= $msg; ?></div>
    <form method="POST" action="">
        <input type="hidden" name="to_do" value="adauga_datorie_do">
        <input type="hidden" name="id" value="<?= $_GET['d']; ?>">
        <input type="hidden" name="id_locatar" value="<?= $id_locatar; ?>">
        <input type="hidden" name="id_taxa" value="<?= $id_taxa; ?>">

        <table border=0 cellspacing=5 cellpadding=0 width="95%" align="center" bgcolor='#a1c2e7'>
            <tr>
                <td class="text" align="left" width="40%">Tip datorie</td>
                <td class=text align="left">
                    <select class="selectx2" <?= $edit ?> name="tip_datorie"><?= write_select($arr_tip_taxa, $tip_datorie) ?></select>
                </td>
            </tr>
            <tr>
                <td class="text" align="left">Perioada de plata</td>
                <td class=text align="left"><input type=text name="data_start" <?= $edit ?> class="input_100" value="<?= $start_date ?>">-<input type=text name="data_end" <?= $edit ?> class="input_100" value="<?= $end_date ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma datorie (RON)</td>
                <td class=text align="left"><input type=text name="suma_datorie" <?= $edit ?> class="input" value="<?= $suma_datorie ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Text datorie</td>
                <td class=text align="left"><input type=text name="text_datorie" <?= $edit ?> class="inputmare" value="<?= $text_datorie ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere" <?= $edit ?> class="input" value="<?= $res['suma_reducere'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere" <?= $edit ?> class="input" value="<?= $res['text_reducere'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Suma reducere penalizare (RON)</td>
                <td class=text align="left"><input type=text name="suma_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['suma_reducere_penalizare'] ?>"></td>
            </tr>
            <tr>
                <td class="text" align="left">Motiv reducere penalizare<br><span class="gri6">(* max 255 caractere)</span></td>
                <td class=text align="left"><input type=text name="text_reducere_penalizare" <?= $edit ?> class="input" value="<?= $res['text_reducere_penalizare'] ?>"></td>
            </tr>

            <tr>
                <td align="left">&nbsp;</td>
                <td><input type='submit' name="submit" class="button_albastru" value='Adauga'><input type='reset' class="button_albastru" value='Renunta'></td>
            </tr>
        </table>
    </form>
    <?php
}

/* ADAUGA DATORIE DO */
if ($_POST['to_do'] == 'adauga_datorie_do') {

    list($id_datorie, $tip_datorie) = explode('-', $_POST['id']);

    if (strlen(trim($_POST['text_datorie'])) == 0) {
        unset($_POST['text_datorie']);
    }
    if (strlen(trim($_POST['text_reducere'])) == 0) {
        unset($_POST['text_reducere']);
    }
    if (strlen(trim($_POST['text_reducere_penalizare'])) == 0) {
        unset($_POST['text_reducere_penalizare']);
    }

    $clean = $_POST;

    unset($clean['id']);

    $clean['id_taxa'] = $_POST['id_taxa'];
    $clean['id_datorie'] = $clean['id_cazare'] = $id_datorie;
    $clean['tip_datorie'] = $tip_datorie;
    $clean['data_start'] = date4sql($clean['data_start']);
    $clean['data_end'] = date4sql($clean['data_end']);
    $clean['status_datorie'] = 1;

    //pa($clean);

    // va trebui sa scriu o functie de verificare a suprapunerii datelor care sa aiba patru parametrii
    // doi pentru perioada de verificat si doi pentru perioada asupra careia se face verificare de suprapunere a datelor

    if ($clean['tip_datorie'] == 1) {
        $q = "
            SELECT data_start, data_end FROM cs_datorii 
            WHERE 1 
            AND id_datorie = $id_datorie 
            AND tip_datorie = $tip_datorie 
            AND (data_start BETWEEN '".$clean['data_start']."' and '".$clean['data_end']."' OR data_end BETWEEN '".$clean['data_start']."' and '".$clean['data_end']."' )";
        $r = $db->Execute($q);
        $res = $r->GetRows(); //pa($res);
    }

    // verificari
    $ok = 1;

    if (strtotime($clean['data_start']) >= strtotime($clean['data_end'])) {
        $msg .= '&msg=Adaugarea nu a fost efectuata! Perioada de plata necorespunzatoare!';
        $ok--;
    }

    if (count($res) > 0) {
        $msg = '&msg=Intervalul propus se suprapune cu un alt interval stabilit pentru acelasi tip de taxa!';
        $ok--;
    }


    if ($ok == 1) {
        $db->debug = true;
        $db->AutoExecute('cs_datorii', $clean, 'INSERT');
        $msg .= '&msg=Adaugare efectuata cu succes!';
    }

    header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$_GET['w'].$msg.'');
}


/* STERGE DATORIE */
if ($_GET['section'] == 'sterge_datorie') {
    list($id_datorie, $id_curent) = explode('-', $_GET['d']);
    $id_locatar = intval($_GET['w']);

    $log['FILE'] = __LINE__;
    $log['REQUEST'] = $_REQUEST;

    $q = "DELETE FROM ".$db_prefix."datorii  WHERE id_datorie=$id_datorie AND id=$id_curent AND id_locatar=$id_locatar AND status_datorie!=2 LIMIT 1";

    $log['DELETE_QUERY'] = $q;

    zu($log, $GLOBALS);

    $db->Execute($q);
    header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$_GET['w'].$msg.'');
}


/* GENEREAZA DATORIE*/
if ($section == 'generare_datorii') {

    list($id_cazare, $tip_datorie) = explode('-', $_GET['d']);
    $id_persoana = $_GET['w'];

    $q1 = "	
		SELECT * 
		FROM cs_persoane_camere 
		WHERE 1
		AND id_persoana = $id_persoana 
		AND id = $id_cazare 
		AND status_activ IN (1,2,3)
	";
    // 1 activ 2 cazat pe vara 3 cazat dupa 1 oct pe un loc ocupat pe vara

    $r1 = $db->Execute($q1);
    $res1 = $r1->GetRowAssoc($toUpper = false);

    //pa($res1);
    $numar_utilitati_cazare = count(explode("|", $res1['utilitati_cazare']));

    $cazare_data_start = $res1['data_start'];
    $cazare_data_end = $res1['data_end'];

    $q_v = "
		SELECT 
			data_start, 
			data_end 
		FROM cs_datorii 
		WHERE 
			id_datorie = $id_cazare 
		AND tip_datorie = $tip_datorie
		AND status_datorie = 1 
		AND (
			data_start BETWEEN '".$res1['data_start']."' and '".$res1['data_end']."' 
			OR 
			data_end BETWEEN '".$res1['data_start']."' and '".$res1['data_end']."' 
		)";

    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $r_v = $db->Execute($q_v);
    $res_v = $r_v->GetRows();

    //highlight_string($q_v); pa($res_v);

    //die;
    /*
	if(count($res_v) > 0)
	{
		$msg = '&msg=S-a efectuat deja o generare automata sau intervalul propus se suprapune cu un alt interval stabilit pentru acelasi tip de taxa!';
		header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$_GET['w'].$msg.'');
		die;
	}
	*/

    //echo $cazare_data_start.' - '.$cazare_data_end.'<br/>';

    $luni_cazare = array();
    $luni_cazare2 = array();

    $i = strtotime($cazare_data_start);

    $j = -1;

    if (date("d", strtotime($cazare_data_start)) != '01') {
        $j = 0;
        $luni_cazare[$j] = $cazare_data_start;
    }

    while ($i <= strtotime($cazare_data_end)) {
        if (date("d", $i) == '01') {
            $j++;
            //$luni_cazare[$j] = date("Y-m-d", $i);
            $luni_cazare2[date("Y-m-d", $i)] = date("Y-m-d", $i);
        }

        if (date("d", $i) == date("t", $i)) {
            $j++;
            //$luni_cazare[$j] = date("Y-m-d", $i);
            $luni_cazare2[date("Y-m-d", $i)] = date("Y-m-d", $i);
        }

        //echo date("Y-m-d H:i:s", $i) . ' -- ' . date("d", $i) . '.vs.' . date("t", $i).' --- '.$i.' <br/>';

        //$t++;
        //$i = $i+86400;
        $i = $i + 43200;
    }

    //echo $t;
    //pa($luni_cazare2);

    $luni_cazare = array_merge($luni_cazare, array_keys($luni_cazare2));

    $luni_cazare[] = $cazare_data_end;    // last day interval

    //$luni_cazare[$j+1] = $cazare_data_end;
    //pa($luni_cazare);

    foreach ($luni_cazare AS $key => $c) {
        if ($key % 2) {
            $new[] = array($luni_cazare[$key - 1], $luni_cazare[$key]);
        }
    }

    //pa($new);

    // aflu tipul camerei
    $q_cam = "SELECT nr_paturi FROM cs_camere WHERE id = ".$res1['id_camera'].";";
    $r_cam = $db->Execute($q_cam);
    $res_cam = $r_cam->FetchRow();

    $tip_camera = $res_cam['nr_paturi'] - 1;
    if ($res1['camera_integral'] == 1) {
        $tip_camera = $tip_camera + 3;
    }

    // aflu tipul persoanei - taxat ca student sau taxat ca angajat
    $q_pers = "SELECT tip_persoana FROM cs_persoane WHERE id = ".$res1['id_persoana'].";";
    $r_pers = $db->Execute($q_pers);
    $res_pers = $r_pers->FetchRow();

    $tip_persoana = $res_pers['tip_persoana'];

    ?>
    <div class="msg" align="center"><?= $msg ?></div>

    <?php
    if ($_POST['to_do'] != 'generare_datorii_do') {
        ?>
        <!-- construiesc formularul de validare a taxelor -->

        <div class="page_title" align="center">GENERARE DATORII (<?= $arr_tip_taxa[$tip_datorie] ?>)</div>
        <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>
        <div align="center" style="color:red; font-size:12px;">
            <b>* Atentie!</b> In acest moment datoriile NU sunt adaugate!
            <br>Verificati corectitudinea sumelor generate si apoi apasati butonul "Validare datorii"!
            <br><br><b>NU adaugati / bifati datoriile deja existente</b>
        </div>
        <br/>
        <form action="" method="POST">
            <input type="hidden" name="to_do" value="generare_datorii_do">
            <input type="hidden" name="id_datorie" value="<?= $res1['id'] ?>">
            <input type="hidden" name="id_persoana" value="<?= $res1['id_persoana'] ?>">
            <input type="hidden" name="tip_datorie" value="<?= $tip_datorie ?>">

            <?php
            // verific daca sunt mai multe paturi pentru aceasta cazare
            $q_checkpaturi = "	
                SELECT 
                    IF(nr_pat != 0, 1, 0) + IF(nr_pat_2 != 0, 1, 0) AS nr_paturi 
                FROM cs_persoane_camere 
                WHERE id = ".$id_cazare."
            ";
            $r_checkpaturi = $db->GetRow($q_checkpaturi);
            $nr_checkpaturi = $r_checkpaturi['nr_paturi'];

            if ($nr_checkpaturi > 1) {
                echo '<div class="page_title16">ATENTIE '.$nr_checkpaturi.' PATURI</div>';
            }

            ?>

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="99%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead"></td>
                    <td class="tablehead">Semnificatie datorie</td>
                    <td class="tablehead">Perioada</td>
                    <td class="tablehead">Suma (RON)</td>
                </tr>

                <?php

                foreach ($new AS $key => $interval) {

                    //=========================================================================================
                    // check if the debt is already added
                    $q_already = "
                        SELECT *
                        FROM cs_datorii 
                        WHERE 
                            id_datorie=$id_cazare 
                        AND tip_datorie=$tip_datorie
                        AND (
                        data_start BETWEEN '".$interval[0]."' AND '".$interval[1]."' 
                        OR 
                        data_end BETWEEN '".$interval[0]."' and '".$interval[1]."' 
                    )";

                    $db->SetFetchMode(ADODB_FETCH_ASSOC);
                    $r_already = $db->Execute($q_already);
                    $res_already = $r_already->GetRows();

                    // highlight_string($q_already);

                    if ((count($res_already) > 0) && (count($res_already) == $nr_checkpaturi)) {
                        $check_or_notcheck = 'DISABLED';
                    } else {
                        $check_or_notcheck = 'CHECKED';
                    }

                    //echo $check_or_notcheck;

                    //=========================================================================================

                    //list($nr_zile_cazare_interval, $nothing) = @explode(".", $nr_zile_cazare_interval = (((strtotime($interval[1].' 23:59:59') - strtotime($interval[0].' 00:00:01'))) / 86400));

                    $nr_zile_cazare_interval = ceil(((strtotime($interval[1].' 23:59:59') - strtotime($interval[0].' 00:00:01'))) / 86400);

                    $nr_zile_luna_interval = date("d", strtotime($interval[1]));

                    /* Aici aflu taxa la care se incadreaza micutul am la dispozitie $id_cazare, $id_persoana, $tip_datorie si perioada de incidenta a taxei*/
                    $c_taxa = get_taxa_corespunzatoare($tip_datorie, $tip_camera, $tip_persoana, $interval);        //pa($c_taxa);

                    if ($nr_zile_cazare_interval < $nr_zile_luna_interval) {
                        $c_taxa['suma_datorie'] = number_format($nr_zile_cazare_interval / $nr_zile_luna_interval * $c_taxa['suma_datorie'], 2);
                    }

                    ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="texttable" width="10" align="center">
                            <input type="checkbox" name="confirm[<?= $key ?>]" <?= $check_or_notcheck ?>>
                        </td>
                        <td class="text" style="padding-left:5px" width="360" align="left"><?= $c_taxa['text_datorie']; ?>
                            <input type="hidden" name="id_taxa[<?= $key ?>]" value="<?= $c_taxa['id_taxa']; ?>">
                            <input type="hidden" name="suma_datorie[<?= $key ?>]" value="<?= $c_taxa['suma_datorie']; ?>">
                            <input type="hidden" name="text_datorie[<?= $key ?>]" value="<?= $c_taxa['text_datorie']; ?>">
                            <input type="hidden" name="tip_taxa_camera_persoana[<?= $key ?>]" value="<?= $c_taxa['tip_taxa_camera_persoana']; ?>">
                        </td>
                        <td class="text" style="padding-left:5px" width="150" align="left"><span class="blue"><?= date4html($interval[0]).'</span> - <span class="blue">'.date4html($interval[1]); ?></span>
                            <input type="hidden" name="start[<?= $key ?>]" value="<?= $interval[0] ?>">
                            <input type="hidden" name="end[<?= $key ?>]" value="<?= $interval[1] ?>">
                        </td>
                        <td class="text" width="70" align="center"><span class="blue"><?= $c_taxa['suma_datorie']; ?></span></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="3"><br/></td>
                </tr>
                <tr>
                    <td width="10">&nbsp;</td>
                    <td class="text_insc" style="text-align:left"><input type="submit" class="buttonaut" value='Validare datorii'></td>
                    <td class="text_insc"></td>
                </tr>
            </table>
        </form>
        <?php
    } else {

        $id_datorie = $_POST['id_datorie'];
        $id_persoana = $_POST['id_persoana'];
        $tip_datorie = $_POST['tip_datorie'];
        $id_taxa = $_POST['id_taxa'];
        $suma_datorie = $_POST['suma_datorie'];
        $text_datorie = $_POST['text_datorie'];
        $tip_taxa_camera_persoana = $_POST['tip_taxa_camera_persoana'];

        $confirm = array_keys($_POST['confirm']);

        $start = $_POST['start'];
        $end = $_POST['end'];

        foreach ($confirm AS $ok) {
            $text_datorie_here[$ok] = $text_datorie[$ok].' '.date("m/Y", strtotime($end[$ok]));
            $sql = "INSERT IGNORE INTO cs_datorii 
                    VALUES (
                        NULL, 
                        ".$id_taxa[$ok].", 
                        ".$id_datorie.", 
                        ".$id_datorie.", 
                        ".$id_persoana.", 
                        ".$tip_datorie.", 
                        '".$start[$ok]."', 
                        '".$end[$ok]."', 
                        $suma_datorie[$ok] , 
                        '".$text_datorie_here[$ok]."', 
                        0.00, 
                        'Nu se acorda.', 
                        0.00, 
                        'Nu se acorda.', 
                        1, 
                        '".$tip_taxa_camera_persoana[$ok]."', 
                        0,
                        0)
            ";
            //echo '<br>';
            $db->debug = true;
            $r = $db->Execute($sql);

            //exit;
        }

        header('Location: '.$link_site.'?page=info_locatar&section=status_datorii&w='.$id_persoana.'');

    }

}


/* STATUS INCASARI */
if ($section == 'status_incasari') {

    $cazare_info = get_infocazare_idpersoana($_GET['w']);

    // afisam cazarile in ordinea inversa intamplarii lor
    $cazare_info = array_reverse($cazare_info);

    echo '.<!--';
    pa($cazare_info);
    echo ' -->.';

    foreach ($cazare_info AS $result) {


        $id_cazare = $result['id'];

        // verific daca locatarul este cazat sau daca are datorii
        if (empty($id_cazare)) {
            ?>
            <div class="page_title18" align="center">Status incasari / locatar indisponibil</div>
            <table width="95%" _border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#DEDFFF">
                <tr height="22" bgcolor="#FFFFFF">
                    <td valign="middle" class="text" align="center"><span class="red"><b>Locatarul <b><?= strtoupper(get_persoana_dupa_id($_GET['w'])) ?></b> nu este cazat</b></span> &rsaquo;
                        <a title="Cazeaza locatar" href="<?= $link_site ?>index.php?page=camere&section=ocupare_camere&locatar=<?= $_GET['w']; ?>" class="link"> cazeaza </a>
                    </td>
                </tr>
            </table>
            <?php
            die;
        }

        $q = "SELECT * FROM cs_incasari WHERE id_persoana=".$_GET['w']." AND id_cazare = ".$id_cazare." ORDER BY data_chitanta ASC";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $r = $db->Execute($q);
        $res = $r->GetRows(); //pa($res);


        if ($result['status_activ'] != 9) {
            ?>
            <div class="page_title" align="center">INCASARI CAZARE CURENTA - <?= get_persoana_dupa_id($_GET['w']) ?> [<?= $result['id_persoana'] ?>]</div>
            <div class="page_title16" align="center">(<?= date4html($result['data_start']); ?> -> <?= date_alarm(date4html($result['data_end']), 7); ?>)</div>
            <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>
            <?php
        }


        if ($result['status_activ'] == 9) {
            if (count($cazare_info) > 1) {
                echo '<br/><br/><br/><hr style="border: 1px solid red; " /><hr style="border: 1px solid red; " />';
            }
            ?>
            <div class="page_title" align="center">INCASARI CAZARE ANTERIOARA - <?= get_persoana_dupa_id($_GET['w']) ?> [<?= $result['id_persoana'] ?>]</div>
            <div class="page_title16" align="center">(<?= date4html($result['data_start']); ?> -> <?= date_alarm(date4html($result['data_end']), 7); ?>)</div>
            <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>
            <?php
        }

        if (count($res) == 0) {
            echo '<div class="msg" align="center">Nu s-au efectuat incasari pe numele '.get_persoana_dupa_id($_GET['w']).'!</div>';
        } else {


            ?>


            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" width="10">Nr.</td>
                    <td class="tablehead" width="20">Serie</td>
                    <td class="tablehead" width="60">Numar</td>
                    <td class="tablehead" width="60">Suma [RON]</td>
                    <td class="tablehead" width="150">Data incasarii</td>
                    <td class="tablehead">Actiuni</td>
                </tr>
                <?php
                $i = 1;

                foreach ($res AS $c) {
                    //pa($c);
                    // cautam detaliile incasarii curente in tabela cs_incasari_detalii
                    $sql_info_income = "SELECT * FROM cs_incasari_detalii WHERE id_incasare = ".$c['id_incasare'].";";
                    $db->SetFetchMode(ADODB_FETCH_ASSOC);
                    $r_info_income = $db->Execute($sql_info_income);
                    $res_info_income = $r_info_income->GetRows(); //pa($res_info_income);

                    $to_print = get_print_incasare($c['id_incasare']);
                    $to_print = base64_encode($to_print['print_incasare']);

                    //=========================================================================================
                    //pa($to_print);
                    //=========================================================================================

                    foreach ($res_info_income AS $ctip) {
                        $display_tip[$c['id_incasare']][$ctip['id']] .= $ctip['semnificatie_incasare'].''."<br />";
                        $display_tip[$c['id_incasare']][$ctip['id']] .= 'Suma platita: '.$ctip['suma_platita'].'RON'."; ";
                        $display_tip[$c['id_incasare']][$ctip['id']] .= 'Datorie: '.$ctip['suma_taxa_efectiva'].'RON'."; ";
                        $display_tip[$c['id_incasare']][$ctip['id']] .= 'Penalizare: '.$ctip['suma_penalizare'].'RON'."";
                    }
                    ?>
                    <?php if ($c['status_chitanta'] == 20) {
                        echo '<tr style="color:red; background-color:pink; text-decoration:line-through;" height="22" tip="CHITANTA ANULATA">';
                    } else {
                        echo '<tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor=\'#FFCE7F\'" onmouseout="this.style.backgroundColor=\'#E6F4FF\'" bgcolor="#e6f4ff" height="22">';
                    }
                    ?>
                    <td class="text" align="center"><?= $i++ ?></td>
                    <td class="text" style="padding-left:10px" align="left"><?= $c['serie_chitanta'] ?></td>
                    <td class="text" style="padding-left:10px" align="left"><?= $c['numar_chitanta'] ?></td>
                    <td class="text" style="padding-right:10px" align="right"><?= $c['valoare_chitanta'] ?></td>
                    <td class="text" align="center"><?= date("d-m-Y", strtotime($c['data_chitanta'])) ?></td>
                    <td class="text" align="center">
                        <a tip="<?php echo implode("<br /><br />", $display_tip[$c['id_incasare']]); ?>" name="<?= $c['id_incasare'] ?>" href="#<?= $c['id_incasare'] ?>" class="link12" onClick="javascript:show_hide(<?= $c['id_incasare'] ?>, 0);">Detalii</a>&nbsp;&nbsp;&nbsp;
                        <a tip="Anulare chitanta (momentan in lucru)" href="<?= $link_site.'index.php?page=incasari&section=anulare_incasare&w='.$c['id_incasare']; ?>" class="link12">Anulare</a>&nbsp;&nbsp;&nbsp;

                    </td>
                    </tr>
                    <!-- tr + div ascuns cu detaliile despre incasarea curenta -->
                    <tr>
                        <td colspan="6">
                            <div id="sh_<?= $c['id_incasare'] ?>_0" style="border: 1px solid #DCDCDC; margin-top:2px; float:left; padding:1px 0px 4px 0px; text-align:left; display:none; font-size: 10px;">

                                <!-- arata infomratiile despre incasarea curenta -->
                                <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">
                                    <tr bgcolor="#2A87CC" height="18">
                                        <td class="tablehead">Nr.</td>
                                        <td class="tablehead">Suma platita</td>
                                        <td class="tablehead">Suma taxa efectiva</td>
                                        <td class="tablehead">Suma penalizare</td>
                                        <td class="tablehead">Semnificatie incasare</td>
                                    </tr>
                                    <?php
                                    $j = 1;
                                    foreach ($res_info_income AS $info) {
                                        //pa($info);
                                        ?>

                                        <tr style="background-color: #FFCE7F" onmouseover="this.style.backgroundColor='#E6F4FF;'" onmouseout="this.style.backgroundColor='#FFCE7F'" bgcolor="#FFCE7F" height="18">
                                            <td class="text" width="10" align="center"><?= $j++ ?></td>
                                            <td class="text" width="20" align="right"><?= $info['suma_platita'] ?></td>
                                            <td class="text" width="20" align="right"><?= $info['suma_taxa_efectiva'] ?></td>
                                            <td class="text" width="20" align="right"><?= $info['suma_penalizare'] ?></td>
                                            <td class="text" width="350" align="left"><?= $info['semnificatie_incasare'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                                <center><input type="button" value="PRINTEAZA" class="buttonaut" onclick="newWindow('view_income.php?id_incasare=<?= $c['id_incasare'] ?>&i=<?= $to_print; ?>');"></center>
                                <a href="#<?= $c['id_incasare'] ?>" onClick="javascript:show_hide(<?= $c['id_incasare'] ?>, 0);" class="link_path" style="font-size: 10px;"><b>x</b> inchide detalii</a>

                            </div>
                        </td>
                    </tr>

                <?php } ?>
            </table>

            <?php
        }
    }

}

/* STATUS PLATI EFECTUATE CATRE UN LOCATAR */
if ($section == 'status_plati') {

    $cazare_info = get_infocazare_idpersoana($_GET['w']);

    // afisam cazarile in ordinea inversa intamplarii lor
    $cazare_info = array_reverse($cazare_info);

    echo '.<!--';
    pa($cazare_info);
    echo ' -->.';

    foreach ($cazare_info AS $result) {


        $id_cazare = $result['id'];

        // verific daca locatarul este cazat sau daca are datorii
        if (empty($id_cazare)) {
            ?>
            <div class="page_title18" align="center">Status plati / locatar indisponibil</div>
            <table width="95%" _border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#DEDFFF">
                <tr height="22" bgcolor="#FFFFFF">
                    <td valign="middle" class="text" align="center"><span class="red"><b>Locatarul <b><?= strtoupper(get_persoana_dupa_id($_GET['w'])) ?></b> nu este cazat</b></span> &rsaquo;
                        <a title="Cazeaza locatar" href="<?= $link_site ?>index.php?page=camere&section=ocupare_camere&locatar=<?= $_GET['w']; ?>" class="link"> cazeaza </a>
                    </td>
                </tr>
            </table>
            <?php
            die;
        }


        $q = "SELECT * FROM cs_plati WHERE id_locatar_beneficiar=".$_GET['w']." AND id_cazare = ".$id_cazare." ORDER BY data_emiterii ASC";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $r = $db->Execute($q);
        $res = $r->GetRows(); //pa($res);


        if (count($res) == 0) {
            echo '<div class="msg" align="center">Nu s-au efectuat plati catre locatarul '.get_persoana_dupa_id($_GET['w']).'!</div>';
        } else {


            if ($result['status_activ'] != 9) {
                ?>
                <div class="page_title" align="center">PLATI CAZARE CURENTA - <?= $res[0]['nume_beneficiar'] ?> [<?= $res[0]['id_locatar_beneficiar'] ?>]</div>
                <div class="page_subtitle" align="center">CNP: <?= $res[0]['cnp_beneficiar'] ?>, S/N: <?= $res[0]['serie_beneficiar'] ?>/<?= $res[0]['numar_beneficiar'] ?></div>
                <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>
                <?php
            }


            if ($result['status_activ'] == 9) {
                if (count($cazare_info) > 1) {
                    echo '<br/><br/><br/><hr style="border: 1px solid red; " /><hr style="border: 1px solid red; " />';
                }
                ?>
                <div class="page_title" align="center">PLATI CAZARE ANTERIOARA - <?= $res[0]['nume_beneficiar'] ?> [<?= $res[0]['id_locatar_beneficiar'] ?>]</div>
                <div class="page_subtitle" align="center">CNP: <?= $res[0]['cnp_beneficiar'] ?>, S/N: <?= $res[0]['serie_beneficiar'] ?>/<?= $res[0]['numar_beneficiar'] ?></div>
                <div class="msg" align="center"><?= $_REQUEST['msg']; ?></div>
                <?php
            }

            ?>

            <table align="center" border="0" cellpadding="0" cellspacing="1" width="100%">

                <tr bgcolor="#2a87cc" height="22">
                    <td class="tablehead" style="width: 10px">Nr.</td>
                    <td class="tablehead" style="width: 150px">Data emiterii</td>
                    <td class="tablehead" style="width: 50px">Suma platita [RON]</td>
                    <td class="tablehead" style="width: 250px">Scopul platii</td>
                    <td class="tablehead" style="width: 200px">Chitanta referinta</td>
                    <td class="tablehead" style="width: 50px">Actiuni</td>
                </tr>
                <?php
                $i = 1;
                foreach ($res AS $c) { //pa($c);

                    //$to_print = get_print_plata($c['id_plata']);
                    //$to_print = base64_encode($to_print['print_plata']);

                    ?>
                    <tr style="background-color: #e6f4ff;" onmouseover="this.style.backgroundColor='#FFCE7F'" onmouseout="this.style.backgroundColor='#E6F4FF'" bgcolor="#e6f4ff" height="22">
                        <td class="text"  align="center"><?= $i++ ?></td>
                        <td class="text" style="padding-right:10px" align="center"><?= $c['data_emiterii'] ?></td>
                        <td class="text" style="padding-right:10px" align="right"><?= $c['suma_platita'] ?></td>
                        <td class="text" style="padding-left:10px" align="left"><?= $c['scopul_platii'] ?></td>
                        <td class="text" style="padding-left:10px" align="left"><?= $c['sn_chitanta_referinta'] ?> din <?= $c['data_chitanta_referinta'] ?></td>
                        <td class="text" align="center">
                            <input type="button" title="<?= $c['id_plata'] ?>" value="PRINTEAZA" class="buttonaut" onclick="newWindow('view_payment.php?id_plata=<?=$c['id_plata']?>');">
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <?php
        }
    }
}
?>