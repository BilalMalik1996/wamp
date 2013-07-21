<?php
require 'wampserver.lib.php';
require 'config.inc.php';
$newApacheVersion = $_SERVER['argv'][1];
// on charge le fichier de configuration du php courant
require $c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/'.$wampBinConfFiles;
// on verifie que la nouvelle version de apache est compatible avec le php courant
$newApacheVersionTemp = $newApacheVersion;
while(!isset($phpConf['apache'][$newApacheVersionTemp]) && $newApacheVersionTemp != '') {
	$pos                  = strrpos($newApacheVersionTemp, '.');
	$newApacheVersionTemp = substr($newApacheVersionTemp, 0, $pos);
}
if($newApacheVersionTemp == '') {
	exit();
}
// on charge le fichier de conf de la nouvelle version
require $c_apacheVersionDir.'/apache'.$newApacheVersion.'/'.$wampBinConfFiles;
$apacheConf['apacheVersion'] = $newApacheVersion;
wampIniSet($configurationFile, $apacheConf);
$httpConf = $c_apacheVersionDir.'/apache'.$newApacheVersion.'/'.$apacheConf['apacheConfDir'].'/'.$apacheConf['apacheConfFile'];
$httpConfContents = @file_get_contents($httpConf) or die ($apacheConf['apacheConfFile']." file not found");
$httpConfContents = preg_replace('/(\r|\n|\r\n)ServerRoot([^\r\n]+)/', '$1ServerRoot "'.$c_apacheVersionDir.'/apache'.$apacheConf['apacheVersion'].'"', $httpConfContents);
$httpConfContents = preg_replace('/(\r|\n|\r\n)ErrorLog([^\r\n]+)/', '$1ErrorLog "'.$wampConf['installDir'].'/'.$logDir.'apache_error.log"', $httpConfContents);
file_put_contents($httpConf, $httpConfContents);
?>