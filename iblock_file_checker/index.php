<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require(__DIR__.'/b_file_d7.php');
use Bitrix\Main\Localization\Loc;
if(toLower(SITE_CHARSET) == 'utf-8'){
	Loc::loadMessages(__DIR__.'/index_utf.php');
}else{
	Loc::loadMessages(__DIR__.'/index_cp.php');
}

global $USER;
if(!$USER->IsAdmin()) die();

global $startTime;
$startTime = getmicrotime();
function checkTime($step=5){
	global $startTime;
	return ($step <= 0) || (getmicrotime()-$startTime <= $step);
}

if(!$_REQUEST['action']){

$res = FilenTable::getList(
	array(
		'select'=>array('SUBDIR','ID','FILE_NAME'),
		'filter'=>array('SUBDIR'=>'iblock/%')
	)
);
$allFiles = array();
while($d = $res->fetch()){
	$filePath = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$d['SUBDIR'].'/'.$d['FILE_NAME'];
	$allFiles[md5($filePath)] = $d['ID'];
}
$tempfl = $allFiles;
$temp = array();
$temp2 = array();

$ts[] = glob($_SERVER['DOCUMENT_ROOT'].'/upload/iblock/*/*/*.*');
$ts[] = glob($_SERVER['DOCUMENT_ROOT'].'/upload/iblock/*/*/*/*.*');
$ts[] = glob($_SERVER['DOCUMENT_ROOT'].'/upload/iblock/*/*.*');
$fin = 0;
$countFile = 0;
$finSize = 0;
foreach($ts as $f){
	foreach($f as $file){
		if(!isset($allFiles[md5($file)])){
			$temp[] = $file;
			$finSize += filesize($file);
			$fin++;
		}else{
			$temp2[] = $file;
			$countFile++;
			unset($tempfl[md5($file)]);
		}
	}
}

file_put_contents(__DIR__.'/all_correct_files.txt',print_r(implode("\n",$temp2),true));
file_put_contents(__DIR__.'/not_exists_bfile.txt',print_r(implode("\n",$temp),true));
file_put_contents(__DIR__.'/not_exists_disk.txt',print_r(implode("\n",$tempfl),true));

echo Loc::getMessage('FL_CHECKER_MESS2').$fin.' - <a target="_blank" href="not_exists_bfile.txt">not_exists_bfile.txt</a><br>';
echo Loc::getMessage('FL_CHECKER_MESS3').$countFile.' - <a target="_blank" href="all_correct_files.txt">all_correct_files.txt</a><br>';
echo Loc::getMessage('FL_CHECKER_MESS4').count($allFiles).'<br>';
echo Loc::getMessage('FL_CHECKER_MESS5').count($tempfl).' - <a target="_blank" href="not_exists_disk.txt">not_exists_disk.txt</a><br><br>';

echo Loc::getMessage('FL_CHECKER_MESS6').round(($finSize/1024)/1024).'MB<br>';

$urlDelete = $APPLICATION->GetCurPage(false).'?action=delete_not_exists'.'&enigue='.time();
$fileOb = new \Bitrix\Main\IO\File(__DIR__.'/all_correct_files.txt');
?>
<?if($fileSave->isExists()){?>
<br> <a href="<?=$urlDelete?>"><?=Loc::getMessage('FL_CHECKER_MESS1')?></a><br><br>
<?}else{?>
<br><?=Loc::getMessage('FL_CHECKER_MESS7')?><br><br>
<?}?>
<?

}elseif($_REQUEST['action'] == 'delete_not_exists'){
	
	$allFiles = file_get_contents(__DIR__.'/not_exists_bfile.txt');
	$allFiles = explode("\n",$allFiles);
	
	$allFilesFinalize = $allFiles;
	
	$lastStep = false;
	
	if(!empty($allFiles)){
		foreach($allFiles as $key=>$file){
			if($file){
				$fileOb = new \Bitrix\Main\IO\File($file);
				if($fileOb->isExists()){
					$fileSave = new \Bitrix\Main\IO\File(__DIR__.'/backup'.$file);
					if(!$fileSave->isExists()){
						$fileSave->putContents($fileOb->getContents());
					}
					
					$fileSave = new \Bitrix\Main\IO\File(__DIR__.'/backup'.$file);
					if($fileSave->isExists()){
						$fileOb->delete();
						unset($allFilesFinalize[$key]);
					}
				}else{
					unset($allFilesFinalize[$key]);
				}
			}else{
				unset($allFilesFinalize[$key]);
			}
			if(!checkTime()) break;
		}
		if(!empty($allFilesFinalize)){
			file_put_contents(__DIR__.'/not_exists_bfile.txt',print_r(implode("\n",$allFilesFinalize),true));
			$lastStep = true;
		}
	}
	if($lastStep){
		$url = $APPLICATION->GetCurPage(false).'?action=delete_not_exists'.'&enigue='.time();
		?>
		<br><br>
		<a href="<?=$url?>"><?=Loc::getMessage('FL_CHECKER_MESS8')?></a>
		<script>
		setTimeout(function(){
			window.location = '<?=$url?>';
		},2000);
		</script>
		<br><br>
		<?
	}else{
		?>
		<br><br>
		<p><?=Loc::getMessage('FL_CHECKER_MESS9')?></p>
		<br><br>
		<a href="<?=$APPLICATION->GetCurPage(false)?>"><?=Loc::getMessage('FL_CHECKER_MESS10')?></a>
		<br><br>
		<?
	}
	
}