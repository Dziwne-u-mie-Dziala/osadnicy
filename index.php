<?php 
    require_once('./class/GameManager.class.php');
    session_start();
    if(!isset($_SESSION['gm'])) // jeżeli nie ma w sesji naszej wioski
    {
        $gm = new GameManager();
        $_SESSION['gm'] = $gm;
    } 
    else //mamy już wioskę w sesji - przywróć ją
    {
        $gm = $_SESSION['gm'];
    }
    $v = $gm->v; //neizależnie cyz nowa gra czy załadowana
    $gm->sync(); //przelicz surowce
        
    if(isset($_REQUEST['action'])) 
    {
        switch($_REQUEST['action'])
        {
            case 'upgradeBuilding':
                if($v->upgradeBuilding($_REQUEST['building']))
                {
                    echo "Ulepszono budynek: ".$_REQUEST['building'];
                }
                else
                {
                    echo "Nie udało się ulepszyć budynku: ".$_REQUEST['building'];
                }
                    
            break;
            default:
                echo 'Nieprawidłowa zmienna "action"';
        }
    }
    
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osadnicy - gra przeglądarkowa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <header class="row border-bottom">
            <div class="col-12 col-md-3">
                Informacje o graczu
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-12 col-md-3">
                        Drewno: <?php echo $v->showStorage("wood"); ?>
                    </div>
                    <div class="col-12 col-md-3">
                        Żelazo: <?php echo $v->showStorage("iron"); ?>
                    </div>
                    <div class="col-12 col-md-3">
                        Zasób 3
                    </div>
                    <div class="col-12 col-md-3">
                        Zasób 4
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                Guzik wyloguj
            </div>
        </header>
        <main class="row border-bottom">
            <div class="col-12 col-md-3 border-right">
                Lista budynków<br>
                Drwal, poziom <?php echo $v->buildingLVL("woodcutter"); ?> <br>
                Zysk/h: <?php echo $v->showHourGain("wood"); ?><br>
                <?php if($v->checkBuildingUpgrade("woodcutter")) : ?>
                <a href="index.php?action=upgradeBuilding&building=woodcutter">
                    <button>Rozbuduj drwala</button>
                </a><br>
                <?php else : ?>
                    <button onclick="missingResourcesPopup()">Rozbuduj drwala</button><br>
                <?php endif; ?>
                Kopalnia żelaza, poziom <?php echo $v->buildingLVL("ironMine"); ?> <br>
                Zysk/h: <?php echo $v->showHourGain("iron"); ?><br>
                <?php if($v->checkBuildingUpgrade("ironMine")) : ?>
                <a href="index.php?action=upgradeBuilding&building=ironMine">
                    <button>Rozbuduj kopalnie żelaza</button>
                </a>
                <?php else : ?>
                    <button onclick="missingResourcesPopup()">Rozbuduj kopalnie żelaza</button>
                <br>
                <?php endif; ?>
            </div>
            <div class="col-12 col-md-6">
                Widok wioski
            </div>
            <div class="col-12 col-md-3 border-left">
                Lista wojska
            </div>
        </main>
        <footer class="row">
            <div class="col-12">
            <table class="table table-bordered">
            <?php
            
            foreach ($gm->l->getLog() as $entry) {
                $timestamp = date('d.m.Y H:i:s', $entry['timestamp']);
                $sender = $entry['sender'];
                $message = $entry['message'];
                $type = $entry['type'];
                echo "<tr>";
                echo "<td>$timestamp</td>";
                echo "<td>$sender</td>";
                echo "<td>$message</td>";
                echo "<td>$type</td>";
                echo "</tr>";
            }
            
            ?>
            </table>
            </div>
        </footer>
    </div>
    <script>
        function missingResourcesPopup() {
            window.alert("Brakuje zasobów");
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>