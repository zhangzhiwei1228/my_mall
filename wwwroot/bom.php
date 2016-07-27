<?
//此文件用于快速测试UTF8编码的文件是不是加了BOM，并可自动移除
//By Bob Shen

//$basedir=".";; //修改此行为需要检测的目录，点表示当前目录
$auto=1; //是否自动移除发现的BOM信息。1为是，0为否。
//以下不用改动
session_start(); //unset($_SESSION['dirs']);

if (isset($_GET['clear'])) {
	unset($_SESSION['dirs']);
}
checkFile(isset($_GET['dir']) ? $_GET['dir'] : '.');

function checkFile($basedir) {
	if ($dh = opendir($basedir)) {
		while (($file = readdir($dh)) !== false) {
			if (stristr($file, 'upload') || stristr($file, 'image') || stristr($file, 'img')) continue;
			if ($file!='.' && $file!='..' && !is_dir($basedir."/".$file)) echo "filename: $file ".checkBOM("$basedir/$file")." <br>";
			elseif ($file!='.' && $file!='..' && is_dir($basedir."/".$file)) $_SESSION['dirs'][] = $basedir."/".$file;
		}
		closedir($dh);
		if (isset($_SESSION['dirs']) && $_SESSION['dirs']) {
			$curdir = array_shift($_SESSION['dirs']);
			echo '<script type="text/javascript">window.location = \'?dir='.$curdir.'\'</script>';
		} else {
			unset($_SESSION['dirs']);
			echo 'Done, <a href="?init=1">agin</a>';
			exit;
		}
	}
}

function checkBOM ($filename) {
	$allowTypes = array('php', 'asp', 'jsp', 'aspx', 'html', 'js', 'css', 'dwt', 'lib');
	$ext = pathinfo($filename);
	$ext = strtolower($ext['extension']);
	
	if (!in_array($ext, $allowTypes)) return ("<font color=gray>Ignore ...</font>");
	
    global $auto;
    $contents=file_get_contents($filename);
    $charset[1]=substr($contents, 0, 1);
    $charset[2]=substr($contents, 1, 1);
    $charset[3]=substr($contents, 2, 1);
    if (ord($charset[1])==239 && ord($charset[2])==187 && ord($charset[3])==191) {
        $rest=substr($contents, 3);
        rewrite ($filename, $rest);
        return ("<font color=red>BOM found, automatically removed.</font>");
    }
        else return ("<font color=green>BOM Not Found.</font>");
    }

function rewrite ($filename, $data) {
    $filenum=fopen($filename,"w");
    flock($filenum,LOCK_EX);
    fwrite($filenum,$data);
    fclose($filenum);
}
?>