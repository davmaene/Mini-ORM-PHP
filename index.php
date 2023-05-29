<?php 
// session_start();
date_default_timezone_set('UTC');
require_once("pont.php"); // don't delete or modify this line
// ----------------------------------------------------------
// ----------------  include classes here -------------------
// ----------------------------------------------------------
include_once("models/cl.user.php");
include_once("models/cl.rubriques.php");
include_once("models/cl.admin.php");
include_once("models/cl.member.php");
include_once("models/cl.accounts.php");
include_once("models/cl.parts.php");
include_once("models/cl.typecredit.php");
include_once("models/cl.credits.php");

function _listRubriques($where = null){
    $rbqs = new Rubriques();
    $rbqs = $rbqs->getAll($where ? $where : null);
    return $rbqs;
}

function _numDaysInMonth($month){
    $number = cal_days_in_month(CAL_GREGORIAN, $month, date("Y")); 
    return $number;
}

function _randomString($length = 6){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return ucwords($randomString);
}

function _fillPhoneNumber($string){
    if(strlen($string) === 9) return $string = "0".$string;
    if(strlen($string) === 10) return $string;
    if(strlen($string) > 10 && strlen($string) <= 14) return $string = substr($string, 4);
    else return "()()()()()()()";
}

$valuepart = 1320;
$valuepartsocial = 5;
$date = date("d/m/Y, H:i:s");

if($_GET['curl']){
    $curl = $_GET['curl'];
    switch ($curl) {
        case 'adduser':
            $admin = new Admins();
            $admin->__constructor(null, $_POST['nom'], $_POST['postnom'], md5($_POST['password']), $_POST['phone']);
            $admin = $admin->save();

            echo($admin->print());
            break;
        case 'rembourssement':
            $credit = new Credits();

            $credi = $credit->getOne(
                array(
                    "idaccount" => (int) $_POST['numcarnet']
            ), null, null, null);

            $b = $credi->body;
            if(count((array) $b) && $credi->status === 200){
                $montd = $b->montantdu - $_POST['montant'];
                $montp = $b->montantpaye + $_POST['montant'];

                $creditS = new Credits();
                $creditS = $creditS->edit(
                    array(
                        "idaccount" => (int) $_POST['numcarnet']
                    ),
                    array(
                        "montantdu" =>  $montd,
                        "updatedon" => $date,
                        "montantpaye" => $montp
                    )
                );
                echo($creditS->print());
            }else{
                $res = new Response(404, "le numéro du membre est erroné !");
                echo($res->print());
            }
            break;
        case 'loadmembres':
            $members = new Membres();
            $members = $members->getAll();

            echo($members->print());
            break;
        case 'typecredit':
            $typecredit = new Typecredits();
            $typecredit = $typecredit->getAll();

            echo($typecredit->print());
            break;
        case 'paiementcredit':
            break;
        case 'octroitcredit':
            $credit = new Credits();
            $credit->__constructor(null, $_POST['numcarnet'], $_POST['parts'], $_POST['monatantremb'], 0, "USD", $_POST['typecredit'], $date, $date, 1);

            $acc = new Accounts();
            $acc = $acc->getOne(array(
                "id"=> (int) $_POST['numcarnet']
            ), null, null, null);
            $b = (array) $acc->body;

            if(count($b) && 1){
                $credit = $credit->save();
                echo($credit->print());
            }else{
                $res = new Response(404, "le  numero du membre est erroné !");
                echo($res->print());
            }

            break;
        case 'contribution':
            // $parts = new Parts();
            $acc = new Accounts();
            $account = $acc->getOne(array(
                    "id" => (int) $_POST['numcarnet']
                ),null,null, null);

            $b = (array) $account->body;

            if(count($b) && $account->status === 200){
                $acc = $acc->edit(array(
                    "id" => (int) $_POST['numcarnet']
                ), array(
                    "socials" => (int) $b['socials'] + (int) $_POST['parts']
                ));

                echo($acc->print());
                // $parts->__constructor(null, (int) $b['id'], (int) $_POST['parts'], date("d/m/Y, H:i:s"), date("d/m/Y, H:i:s"), $_POST['valeupart']);
                // $parts = $parts->save();
            }else{
                $res = new Response(404, "le  numero du membre est erroné !");
                echo($res->print());
            }
            break;
        case 'addpart':
            // $parts = new Parts();
            $acc = new Accounts();
            $account = $acc->getOne(array(
                    "id" => (int) $_POST['numcarnet']
                ),null,null, null);

            $b = (array) $account->body;

            if(count($b) && $account->status === 200){
                $acc = $acc->edit(array(
                    "id" => (int) $_POST['numcarnet']
                ), array(
                    "parts" => (int) $b['parts'] + (int) $_POST['parts']
                ));

                echo($acc->print());
                // $parts->__constructor(null, (int) $b['id'], (int) $_POST['parts'], date("d/m/Y, H:i:s"), date("d/m/Y, H:i:s"), $_POST['valeupart']);
                // $parts = $parts->save();
            }else{
                $res = new Response(404, "le  numero du membre est erroné !");
                echo($res->print());
            }
            break;
        case 'connexion':
            $admin = new Admins();
            $admin = $admin->getOne(
                    array(
                        "phone" => _fillPhoneNumber($_POST['phone']),
                        "password" => md5($_POST['password'])
                ), null, null, "AND"
            );

            try {
                $a = (array) $admin->body;
                if($admin->status === 200){
                    if(1 && count($a) > 0){
                        $_SESSION['_bigUser'] = base64_encode(json_encode($a));
                        $_SESSION['token'] = base64_encode($a['id']);
                        echo($admin->print());
                    } else {
                        $res = new Response(404, "No Item found !");
                        echo($res->print());
                    }
                }else{
                    $res = new Response(500, "Something went wrong !");
                    echo($res->print());
                }
            } catch (\Throwable $th) {
                $res = new Response(500, $th);
                echo($res->print());
            }
            break;
        case 'addmember':

            if(isset($_POST['checked'])){
                $member1 = new Membres();
                $member2 = new Membres();

                $acoount = new Accounts();
                // $acoount->__constructor(null, _randomString(6), 1, 0, $valuepart, 0,  $valuepartsocial,  1, 0, date("d/m/Y, H:i:s"));
                $acoount->__constructor(null, 0, 0, 0, $valuepart, $valuepartsocial,  1, 0, date("d/m/Y, H:i:s"), _randomString(6));


                $account = $acoount->save();
                $b = $account->body;
                
                if($account->status === 200 && 1){
                    $member1->__constructor(
                        null, 
                        strtolower($_POST['nom1']),
                        strtolower($_POST['postnom1']),
                        $_POST['phone1'],
                        0,
                        $b->id,
                        1,
                        date("d/m/Y, H:i:s")
                    );
                    $member1 = $member1->save();
                    // save seconde member
                    $member2->__constructor(
                        null, 
                        strtolower($_POST['nom2']),
                        strtolower($_POST['postnom2']),
                        $_POST['phone2'],
                        0,
                        $b->id,
                        1,
                        date("d/m/Y, H:i:s")
                    );
                    $member2 = $member2->save();
                    if($member1->status === 200 && $member2->status === 200){
                        $res = new Response(200, [ $member1->body, $member2->body ]);
                        echo($res->print());
                    }else{
                        echo($member1->print());
                    }
                } else {
                    echo($account->print());
                }
            }else{

                // var_dump($_POST);
                // return false;
                // var_dump(_randomString(6));
                // return false;
                $member = new Membres();
                $acoount = new Accounts();
                $acoount->__constructor(null, 0, 0, 0, $valuepart, $valuepartsocial,  1, 0, date("d/m/Y, H:i:s"), _randomString(6));

                $account = $acoount->save();
                $b = $account->body;
                if($account->status === 200){
                    $member->__constructor(
                        null, 
                        strtolower($_POST['nom1']),
                        strtolower($_POST['postnom1']),
                        $_POST['phone1'],
                        0,
                        $b->id,
                        1,
                        date("d/m/Y, H:i:s")
                    );
                    $member = $member->save();
                    echo($member->print());
                } else {
                    echo($account->print());
                }
            }
            break;
        case 'editmember':
            $member = new Membres();
            $m = $member->edit(
                array(
                    "id" => $_POST['idmember']
                ),
                array(
                    "nom" => $_POST['nom1'],
                    "postnom" => $_POST['postnom1'],
                    "phone" => $_POST['phone1']
                )
            );
            echo($m->print());
            break;
        default:
            $res = new Response(404, "Aucune route trouvée avec comme clé ");
            echo($res->print()); 
            break;
    }

}else{
    $res = new Response(404, "Aucune route trouvée avec comme clé ");
    echo($res->print());
}

?>
