<?php
 if(!defined('__DIR__'))define('__DIR__',dirname(__FILE__));class uDebug{private static$printed=false;public static function dump($var,$ret=false,$name=NULL,$array=false){if(!self::$printed){self::bar();}$out="";if(!$array){$out="<table>";}$out.="<tr><td class='nm' valign='top'>";if(!$name){$trace=debug_backtrace();$vLine=file($trace[0]["file"]);$fLine=$vLine[$trace[0]['line']-1];preg_match('/(\w+\:\:)?\$(\w+)(\->\w+)?/',$fLine,$match);$out.=$match[0];}else{$out.=$name;}$out.="</td><td>";if(is_bool($var)){$out.="(<span class='kw'>bool</span>) ";$out.=($var?'true':'false');}else if($var===NULL){$out.="NULL";}else if(is_int($var)){$out.="(<span class='kw'>int</span>) ";$out.=$var;}else if(is_float($var)){$out.="(<span class='kw'>float</span>) ";$out.=$var;if(strpos((string)$var,".")===false){$out.=".0";};}else if(is_string($var)){$out.="(<span class='kw'>string</span>)(";$out.=strlen($var);$out.=") <span class='qt'>\"</span>";$out.=str_replace(array("\t","\n"),array('<span class=\'sc\'>\t</span>','<span class=\'sc\'>\n</span>'),htmlspecialchars($var));$out.="<span class='qt'>\"</span>";}else if(is_array($var)){$out.="(<span class='kw'>array</span>)(";$out.=count($var);$out.=")<br />{<br /><div class='ar'><table>";foreach($var as$key=>$val){if($key===0){$key="0";}$out.=self::dump($val,true,(string)$key,true);}$out.="</table></div>}";}$out.="</td></tr>";if(!$array){$out.="</table>";}if($ret){return$out;}else{echo"<script>dbg_append('";echo base64_encode($out);echo"','dbg');</script>";return$var;}}public static function bar(){self::$printed=true;?>
<style>
		#dbg{width:400px;height:300px;overflow:scroll;font-family:Tahoma, arial, serif;z-index:10000;display:none;border:.5px solid #ddd;white-space:nowrap;background-color:#161616;}
		#dbg table{color:#fff;}
		#dbg-wrapper{position:absolute;left:0;bottom:0;background-color:#F2A73D;z-index:10001;margin:3px;padding:4px}
		.toggle-link{text-decoration:none;color:#303030}
		.kw{font-weight:700;color:#8197bf}
		.sc{color:#00F2FF;font-weight:700;margin:0 1px}
		.nm{font-weight:700;color:#fad07a}
		.ar{margin-left:10px}
		.qt{color:#c0f}
</style>
		<?php
?>
<script>
var base64chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'.split("");
var base64inv={};for(var i=0;i<base64chars.length;i++){base64inv[base64chars[i]]=i;}
function base64_decode(s)
{s=s.replace(new RegExp('[^'+base64chars.join("")+'=]','g'),"");var p=(s.charAt(s.length-1)=='='?(s.charAt(s.length-2)=='='?'AA':'A'):"");var r="";s=s.substr(0,s.length-p.length)+p;for(var c=0;c<s.length;c+=4){var n=(base64inv[s.charAt(c)]<<18)+(base64inv[s.charAt(c+1)]<<12)+
(base64inv[s.charAt(c+2)]<<6)+base64inv[s.charAt(c+3)];r+=String.fromCharCode((n>>>16)&255,(n>>>8)&255,n&255);}
return r.substring(0,r.length-p.length);}
function dbg_toggle(t,id)
{var div=t.previousSibling;while(div.nodeType!=1){div=div.previousSibling};div.style.display=(div.style.display=="block"?"none":"block");t.innerHTML=String.fromCharCode(t.innerHTML==String.fromCharCode(9650)?9660:9650);}
function dbg_append(w,id)
{var div=document.getElementById(id);div.innerHTML=div.innerHTML+base64_decode(w);}
var font_max = 18;
var font_min = 8;
function font_minus(t)
{var tables=t.parentNode.getElementsByTagName('table');for(var i=0;i<tables.length;i++){if(!tables[i].style.fontSize){var size=12;}else{var size=parseInt(tables[i].style.fontSize.replace('px',''));}
if(size>font_min){size-=1;}
tables[i].style.fontSize=size+"px";}}
function font_plus(t)
{var tables=t.parentNode.getElementsByTagName('table');for(var i=0;i<tables.length;i++){if(!tables[i].style.fontSize){var size=12;}else{var size=parseInt(tables[i].style.fontSize.replace('px',''));}
if(size<font_max){size+=1;}
tables[i].style.fontSize=size+"px";}}
</script>
		<?php
?>
<div id="dbg-wrapper">
	<div id="dbg">
		<a href="#" onclick="font_minus(this);" style="color:white;position:absolute;bottom:0px;right:2px;text-decoration:none;font-weight:bold;">-</a>
		<a href="#" onclick="font_plus(this);" style="color:white;position:absolute;bottom:0px;right:12px;text-decoration:none;font-weight:bold;">+</a>

	</div>
	<a class="toggle-link db-toggle" href="#" onclick="dbg_toggle(this);return false;">&#9650;</a>	
</div>
		<?php
}}class uD extends uDebug{}if(!defined('__DIR__'))define('__DIR__',dirname(__FILE__));class Tee{private$_REGEXES=array('/\{\%\sif\s([_a-zA-Z][_a-zA-Z0-9]*)\.([_a-zA-Z][_a-zA-Z0-9]*)\s\%\}/'=>"<?php if(@$\\1['\\2']): ?>",'/\{\%\sif\s(.*)\s\%\}/'=>"<?php if(@$\\1): ?>",'/\{\%\selse\s\%\}/'=>"<?php else: ?>",'/\{\%\sendif\s\%\}/'=>"<?php endif; ?>",'/\{\%\sfor\s([_a-zA-Z][_a-zA-Z0-9]*)\sin\s([_a-zA-Z][_a-zA-Z0-9]*)\s\%\}/'=>"<?php foreach(@$\\2 as $\\1): ?>",'/\{\%\sendfor\s\%\}/'=>"<?php endforeach; ?>",'/\{\{\s*([_a-zA-Z][_a-zA-Z0-9]*)\s*\}\}/'=>"<?php echo @$\\1; ?>",'/\{\{\s*([_a-zA-Z][_a-zA-Z0-9]*)\.([_a-zA-Z][_a-zA-Z0-9]*)\s*\}\}/'=>"<?php echo @$\\1['\\2']; ?>");private static$_tags=array();private$_source='';var$_filename='';private$_cache_dir='';private$_blocks=array();const TAG_START='{%';const TAG_END='%}';const TAG_REGEX='\s([_a-zA-Z][_a-zA-Z0-9]*)\s(.*)\s';public function __construct($file=''){$this->_cache_dir=__DIR__."/cache/";if(!is_dir($this->_cache_dir)){if(!mkdir($this->_cache_dir)){throw new Exception("Cannot create dir ".__DIR__."/cache/");}if(!is_writable($this->_cache_dir)){@chmod(0644,__DIR__."/cache/");}}$this->file($file);}public function file($filename){$this->_filename=realpath($filename);return($this->_source=@file_get_contents($this->_filename))==''?false:true;}public function render(){if($this->_filename===''){throw new Exception('No template filename assgined');}if(!$this->from_cache($this->_filename)){$this->replace();$this->write_cache($this->_filename);}$vars=get_object_vars($this);ob_start();if(count($vars)){foreach($vars as$key=>$val){if(strpos('-'.$key,'_')!=1){$$key=$val;}}}require($this->cached_file($this->_filename));$output=ob_get_contents();ob_end_clean();return$output;}private function tag_replace_callback($matches){if(array_key_exists($matches[1],self::$_tags)){return"<?php echo @".self::$_tags[$matches[1]]."('".addslashes($matches[2])."'); ?>";}else{return"";}}public function replace($source=NULL,$ret=false,$in_extend=false){if($source==NULL){$source=$this->_source;}foreach($this->_REGEXES as$regexin=>$out){$source=preg_replace($regexin,$out,$source);}if(preg_match('/\{\%\sextends\s"(.*)"\s\%\}/',$source,$matches)){$source=str_replace($matches[0],$this->load_file($matches[1],true),$source);}if(preg_match_all('/\{\%\sinclude\s"(.*)"\s\%\}/',$source,$matches)){$source=str_replace($matches[0][0],$this->load_file($matches[1][0]),$source);}if($i=preg_match_all("/\{\%\sblock\s(.*)\s\%\}(.*)\{\%\sendblock\s\%\}/sU",$source,$matches)){for(--$i;$i>=0;$i--){if(isset($this->_blocks[$matches[1][$i]])){$source=str_replace($matches[0][$i],'',$source);$source=str_replace('[['.$matches[1][$i].']]',$matches[2][$i],$source);}else{$this->_blocks[$matches[1][$i]]=$matches[2][$i];$source=str_replace($matches[0][$i],'[['.$matches[1][$i].']]',$source);}}}if(!$in_extend){$source=preg_replace('/\[\[(.*)\]\]/e','@$this->_blocks["\\1"]',$source);}$tag_regex='/'.preg_quote(self::TAG_START).self::TAG_REGEX.preg_quote(self::TAG_END).'/';$source=preg_replace_callback($tag_regex,array($this,'tag_replace_callback'),$source);if($ret){return$source;}else{$this->_source=$source;}return true;}public static function add_tag($name,$function){if(!preg_match('/([_a-zA-Z][_a-zA-Z0-9]*)/',$name)){throw new Exception("Invalid tag name '$name' ");}if(is_callable($function,true,$real_function)){self::$_tags[$name]=$function;return true;}else{throw new Exception("Uncallable function '$function' ");}}public function load_file($filename,$in_extend=false){$dir=dirname($this->_filename);$source=@file_get_contents($dir."/".$filename);return$this->replace($source,true,$in_extend);}public function clean_cache(){$files=scandir($this->_cache_dir);foreach($files as$file){if($file!="."&&$file!=".."){unlink($this->_cache_dir."/".$file);}}}private function cached_file($file){return$this->_cache_dir.str_replace("/","%",$file);}private function from_cache($file){$cached_file=$this->cached_file($file);return(file_exists($cached_file)&&(@filemtime($this->_filename)<@filemtime($this->cached_file($this->_filename))));}private function write_cache($file){$cached_file=$this->cached_file($file);return@file_put_contents($cached_file,$this->_source);}}class Router{private$name;private$route;private$function;public static$_vars=array();private$method='GET';private$template='';const ROUTE_REGEX='/<(?<name>.*?)\s*(?<regex>:?\(.*?\))?>/';public function __construct($pattern){$route=self::pattern_quote($pattern);$route=preg_replace_callback(self::ROUTE_REGEX,'self::pattern2regex',$route);$route='/^'.$route.'$/';$this->route=$route;return$this;}public function call($function){$this->name=$function;if(is_callable($function,false)){if(is_string($function)){if(strpos($function,'::')!==false){$class=explode('::',$function);$method=$class[1];$class=$class[0];if(class_exists($class)){$this->class=new$class();if(method_exists($this->class,$method)){$this->function=array($this->class,$method);return$this;}}}else{$this->function=$function;return$this;}}else{$this->function=$function;return$this;}}throw new Exception("Uncallable function '$function' passed!");}public function layout($file){$file=NR::$_views.DIRECTORY_SEPARATOR.$file;if(file_exists($file)){$this->template=$file;}else{throw new Exception("Non-existing file '$file' passed!");}return$this;}public function on($method=NULL){if($method){$this->method=$method;}NR::$_routes[]=array($this->route,$this->function,self::$_vars,$this->method,$this->name,$this->template);self::$_vars=array();}public static function pattern_quote($pattern){$output='';$len=strlen($pattern);$in_regex=False;for($i=0;$i<$len;$i++){if($pattern[$i]=='('){$in_regex=True;}else if($pattern[$i]==')'){$in_regex=False;}if($pattern[$i]=='/'){$output.='\/';}else if($pattern[$i]=='?'){if(!$in_regex){$output.='&';}else{$output.='?';}}else if($pattern[$i]=='['){$output.="(";}else if($pattern[$i]==']'){$output.=")?";}else{$output.=$pattern[$i];}}return$output;}public static function pattern2regex($matches){self::$_vars[]=$matches['name'];if(isset($matches['def'])){self::$_defaults[$matches['name']]=$matches['def'];}if(isset($matches['regex'])){return'(?<'.$matches['name'].'>'.$matches['regex'].')';}return'(?<'.$matches['name'].'>.*?)';}}class NR{static$_routes=array();static$_views="";static$_route=NULL;public static function controllers($dir){if(is_dir($dir)){$dir.="*.php";}foreach(glob($dir)as$file){include_once($file);}}public static function views($dir){if(file_exists($dir)){self::$_views=$dir;}}public static function layout($file=NULL){$tee=new Tee();$route_file=self::$_routes[self::$_route][5];if($file===NULL){$tee->file($route_file);}else{$tee->file($file);}return$tee;}public static function redirect($uri){header("Location: ".$uri,true,302);exit();}public static function route($pattern){return new Router($pattern);}public static function routes(){return self::$_routes;}public static function run(){if(empty($_SERVER["QUERY_STRING"])){$_SERVER["QUERY_STRING"]="/";}foreach(self::$_routes as$key=>$route){if(preg_match($route[0],$_SERVER["QUERY_STRING"],$matches)&&$_SERVER['REQUEST_METHOD']==$route[3]){self::$_route=$key;$args=array();foreach($route[2]as$arg){if(is_numeric(@$matches[$arg])){$args[]=(int)$matches[$arg];}else if(empty($matches[$arg])){continue;}else{$args[]=addslashes($matches[$arg]);}}return call_user_func_array($route[1],$args);}}return-1;}}if(!function_exists('spyc_load')){function spyc_load($string){return Spyc::YAMLLoadString($string);}}if(!function_exists('spyc_load_file')){function spyc_load_file($file){return Spyc::YAMLLoad($file);}}class Spyc{public$setting_dump_force_quotes=false;public$setting_use_syck_is_possible=false;private$_dumpIndent;private$_dumpWordWrap;private$_containsGroupAnchor=false;private$_containsGroupAlias=false;private$path;private$result;private$LiteralPlaceHolder='___YAML_Literal_Block___';private$SavedGroups=array();private$indent;private$delayedPath=array();public$_nodeId;public function load($input){return$this->__loadString($input);}public function loadFile($file){return$this->__load($file);}public static function YAMLLoad($input){$Spyc=new Spyc;return$Spyc->__load($input);}public static function YAMLLoadString($input){$Spyc=new Spyc;return$Spyc->__loadString($input);}public static function YAMLDump($array,$indent=false,$wordwrap=false){$spyc=new Spyc;return$spyc->dump($array,$indent,$wordwrap);}public function dump($array,$indent=false,$wordwrap=false){if($indent===false or!is_numeric($indent)){$this->_dumpIndent=2;}else{$this->_dumpIndent=$indent;}if($wordwrap===false or!is_numeric($wordwrap)){$this->_dumpWordWrap=40;}else{$this->_dumpWordWrap=$wordwrap;}$string="---\n";if($array){$array=(array)$array;$first_key=key($array);$previous_key=-1;foreach($array as$key=>$value){$string.=$this->_yamlize($key,$value,0,$previous_key,$first_key);$previous_key=$key;}}return$string;}private function _yamlize($key,$value,$indent,$previous_key=-1,$first_key=0){if(is_array($value)){if(empty($value))return$this->_dumpNode($key,array(),$indent,$previous_key,$first_key);$string=$this->_dumpNode($key,NULL,$indent,$previous_key,$first_key);$indent+=$this->_dumpIndent;$string.=$this->_yamlizeArray($value,$indent);}elseif(!is_array($value)){$string=$this->_dumpNode($key,$value,$indent,$previous_key,$first_key);}return$string;}private function _yamlizeArray($array,$indent){if(is_array($array)){$string='';$previous_key=-1;$first_key=key($array);foreach($array as$key=>$value){$string.=$this->_yamlize($key,$value,$indent,$previous_key,$first_key);$previous_key=$key;}return$string;}else{return false;}}private function _dumpNode($key,$value,$indent,$previous_key=-1,$first_key=0){if(is_string($value)&&((strpos($value,"\n")!==false||strpos($value,": ")!==false||strpos($value,"- ")!==false||strpos($value,"*")!==false||strpos($value,"#")!==false||strpos($value,"<")!==false||strpos($value,">")!==false||strpos($value,"[")!==false||strpos($value,"]")!==false||strpos($value,"{")!==false||strpos($value,"}")!==false)||substr($value,-1,1)==':')){$value=$this->_doLiteralBlock($value,$indent);}else{$value=$this->_doFolding($value,$indent);if(is_bool($value)){$value=($value)?"true":"false";}}if($value===array())$value='[ ]';$spaces=str_repeat(' ',$indent);if(is_int($key)&&$key-1==$previous_key&&$first_key===0){$string=$spaces.'- '.$value."\n";}else{if($first_key===0)throw new Exception('Keys are all screwy.  The first one was zero, now it\'s "'.$key.'"');if(strpos($key,":")!==false){$key='"'.$key.'"';}$string=$spaces.$key.': '.$value."\n";}return$string;}private function _doLiteralBlock($value,$indent){if(strpos($value,"\n")===false&&strpos($value,"'")===false){return sprintf("'%s'",$value);}if(strpos($value,"\n")===false&&strpos($value,'"')===false){return sprintf('"%s"',$value);}$exploded=explode("\n",$value);$newValue='|';$indent+=$this->_dumpIndent;$spaces=str_repeat(' ',$indent);foreach($exploded as$line){$newValue.="\n".$spaces.trim($line);}return$newValue;}private function _doFolding($value,$indent){if($this->_dumpWordWrap!==0&&is_string($value)&&strlen($value)>$this->_dumpWordWrap){$indent+=$this->_dumpIndent;$indent=str_repeat(' ',$indent);$wrapped=wordwrap($value,$this->_dumpWordWrap,"\n$indent");$value=">\n".$indent.$wrapped;}else{if($this->setting_dump_force_quotes&&is_string($value))$value='"'.$value.'"';}return$value;}private function __load($input){$Source=$this->loadFromSource($input);return$this->loadWithSource($Source);}private function __loadString($input){$Source=$this->loadFromString($input);return$this->loadWithSource($Source);}private function loadWithSource($Source){if(empty($Source))return array();if($this->setting_use_syck_is_possible&&function_exists('syck_load')){$array=syck_load(implode('',$Source));return is_array($array)?$array:array();}$this->path=array();$this->result=array();$cnt=count($Source);for($i=0;$i<$cnt;$i++){$line=$Source[$i];$this->indent=strlen($line)-strlen(ltrim($line));$tempPath=$this->getParentPathByIndent($this->indent);$line=self::stripIndent($line,$this->indent);if(self::isComment($line))continue;if(self::isEmpty($line))continue;$this->path=$tempPath;$literalBlockStyle=self::startsLiteralBlock($line);if($literalBlockStyle){$line=rtrim($line,$literalBlockStyle." \n");$literalBlock='';$line.=$this->LiteralPlaceHolder;while(++$i<$cnt&&$this->literalBlockContinues($Source[$i],$this->indent)){$literalBlock=$this->addLiteralLine($literalBlock,$Source[$i],$literalBlockStyle);}$i--;}while(++$i<$cnt&&self::greedilyNeedNextLine($line)){$line=rtrim($line," \n\t\r").' '.ltrim($Source[$i]," \t");}$i--;if(strpos($line,'#')){if(strpos($line,'"')===false&&strpos($line,"'")===false)$line=preg_replace('/\s+#(.+)$/','',$line);}$lineArray=$this->_parseLine($line);if($literalBlockStyle)$lineArray=$this->revertLiteralPlaceHolder($lineArray,$literalBlock);$this->addArray($lineArray,$this->indent);foreach($this->delayedPath as$indent=>$delayedPath)$this->path[$indent]=$delayedPath;$this->delayedPath=array();}return$this->result;}private function loadFromSource($input){if(!empty($input)&&strpos($input,"\n")===false&&file_exists($input))return file($input);return$this->loadFromString($input);}private function loadFromString($input){$lines=explode("\n",$input);foreach($lines as$k=>$_){$lines[$k]=rtrim($_,"\r");}return$lines;}private function _parseLine($line){if(!$line)return array();$line=trim($line);if(!$line)return array();$array=array();$group=$this->nodeContainsGroup($line);if($group){$this->addGroup($line,$group);$line=$this->stripGroup($line,$group);}if($this->startsMappedSequence($line))return$this->returnMappedSequence($line);if($this->startsMappedValue($line))return$this->returnMappedValue($line);if($this->isArrayElement($line))return$this->returnArrayElement($line);if($this->isPlainArray($line))return$this->returnPlainArray($line);return$this->returnKeyValuePair($line);}private function _toType($value){if($value==='')return null;$first_character=$value[0];$last_character=substr($value,-1,1);$is_quoted=false;do{if(!$value)break;if($first_character!='"'&&$first_character!="'")break;if($last_character!='"'&&$last_character!="'")break;$is_quoted=true;}while(0);if($is_quoted)return strtr(substr($value,1,-1),array('\\"'=>'"','\'\''=>'\'','\\\''=>'\''));if(strpos($value,' #')!==false)$value=preg_replace('/\s+#(.+)$/','',$value);if($first_character=='['&&$last_character==']'){$innerValue=trim(substr($value,1,-1));if($innerValue==='')return array();$explode=$this->_inlineEscape($innerValue);$value=array();foreach($explode as$v){$value[]=$this->_toType($v);}return$value;}if(strpos($value,': ')!==false&&$first_character!='{'){$array=explode(': ',$value);$key=trim($array[0]);array_shift($array);$value=trim(implode(': ',$array));$value=$this->_toType($value);return array($key=>$value);}if($first_character=='{'&&$last_character=='}'){$innerValue=trim(substr($value,1,-1));if($innerValue==='')return array();$explode=$this->_inlineEscape($innerValue);$array=array();foreach($explode as$v){$SubArr=$this->_toType($v);if(empty($SubArr))continue;if(is_array($SubArr)){$array[key($SubArr)]=$SubArr[key($SubArr)];continue;}$array[]=$SubArr;}return$array;}if($value=='null'||$value=='NULL'||$value=='Null'||$value==''||$value=='~'){return null;}if(intval($first_character)>0&&preg_match('/^[1-9]+[0-9]*$/',$value)){$intvalue=(int)$value;if($intvalue!=PHP_INT_MAX)$value=$intvalue;return$value;}if(in_array($value,array('true','on','+','yes','y','True','TRUE','On','ON','YES','Yes','Y'))){return true;}if(in_array(strtolower($value),array('false','off','-','no','n'))){return false;}if(is_numeric($value)){if($value==='0')return 0;if(trim($value,0)===$value)$value=(float)$value;return$value;}return$value;}private function _inlineEscape($inline){$seqs=array();$maps=array();$saved_strings=array();$regex='/(?:(")|(?:\'))((?(1)[^"]+|[^\']+))(?(1)"|\')/';if(preg_match_all($regex,$inline,$strings)){$saved_strings=$strings[0];$inline=preg_replace($regex,'YAMLString',$inline);}unset($regex);$i=0;do{while(preg_match('/\[([^{}\[\]]+)\]/U',$inline,$matchseqs)){$seqs[]=$matchseqs[0];$inline=preg_replace('/\[([^{}\[\]]+)\]/U',('YAMLSeq'.(count($seqs)-1).'s'),$inline,1);}while(preg_match('/{([^\[\]{}]+)}/U',$inline,$matchmaps)){$maps[]=$matchmaps[0];$inline=preg_replace('/{([^\[\]{}]+)}/U',('YAMLMap'.(count($maps)-1).'s'),$inline,1);}if($i++>=10)break;}while(strpos($inline,'[')!==false||strpos($inline,'{')!==false);$explode=explode(', ',$inline);$stringi=0;$i=0;while(1){if(!empty($seqs)){foreach($explode as$key=>$value){if(strpos($value,'YAMLSeq')!==false){foreach($seqs as$seqk=>$seq){$explode[$key]=str_replace(('YAMLSeq'.$seqk.'s'),$seq,$value);$value=$explode[$key];}}}}if(!empty($maps)){foreach($explode as$key=>$value){if(strpos($value,'YAMLMap')!==false){foreach($maps as$mapk=>$map){$explode[$key]=str_replace(('YAMLMap'.$mapk.'s'),$map,$value);$value=$explode[$key];}}}}if(!empty($saved_strings)){foreach($explode as$key=>$value){while(strpos($value,'YAMLString')!==false){$explode[$key]=preg_replace('/YAMLString/',$saved_strings[$stringi],$value,1);unset($saved_strings[$stringi]);++$stringi;$value=$explode[$key];}}}$finished=true;foreach($explode as$key=>$value){if(strpos($value,'YAMLSeq')!==false){$finished=false;break;}if(strpos($value,'YAMLMap')!==false){$finished=false;break;}if(strpos($value,'YAMLString')!==false){$finished=false;break;}}if($finished)break;$i++;if($i>10)break;}return$explode;}private function literalBlockContinues($line,$lineIndent){if(!trim($line))return true;if(strlen($line)-strlen(ltrim($line))>$lineIndent)return true;return false;}private function referenceContentsByAlias($alias){do{if(!isset($this->SavedGroups[$alias])){echo"Bad group name: $alias.";break;}$groupPath=$this->SavedGroups[$alias];$value=$this->result;foreach($groupPath as$k){$value=$value[$k];}}while(false);return$value;}private function addArrayInline($array,$indent){$CommonGroupPath=$this->path;if(empty($array))return false;foreach($array as$k=>$_){$this->addArray(array($k=>$_),$indent);$this->path=$CommonGroupPath;}return true;}private function addArray($incoming_data,$incoming_indent){if(count($incoming_data)>1)return$this->addArrayInline($incoming_data,$incoming_indent);$key=key($incoming_data);$value=isset($incoming_data[$key])?$incoming_data[$key]:null;if($key==='__!YAMLZero')$key='0';if($incoming_indent==0&&!$this->_containsGroupAlias&&!$this->_containsGroupAnchor){if($key||$key===''||$key==='0'){$this->result[$key]=$value;}else{$this->result[]=$value;end($this->result);$key=key($this->result);}$this->path[$incoming_indent]=$key;return;}$history=array();$history[]=$_arr=$this->result;foreach($this->path as$k){$history[]=$_arr=$_arr[$k];}if($this->_containsGroupAlias){$value=$this->referenceContentsByAlias($this->_containsGroupAlias);$this->_containsGroupAlias=false;}if(is_string($key)&&$key=='<<'){if(!is_array($_arr)){$_arr=array();}$_arr=array_merge($_arr,$value);}else if($key||$key===''||$key==='0'){$_arr[$key]=$value;}else{if(!is_array($_arr)){$_arr=array($value);$key=0;}else{$_arr[]=$value;end($_arr);$key=key($_arr);}}$reverse_path=array_reverse($this->path);$reverse_history=array_reverse($history);$reverse_history[0]=$_arr;$cnt=count($reverse_history)-1;for($i=0;$i<$cnt;$i++){$reverse_history[$i+1][$reverse_path[$i]]=$reverse_history[$i];}$this->result=$reverse_history[$cnt];$this->path[$incoming_indent]=$key;if($this->_containsGroupAnchor){$this->SavedGroups[$this->_containsGroupAnchor]=$this->path;if(is_array($value)){$k=key($value);if(!is_int($k)){$this->SavedGroups[$this->_containsGroupAnchor][$incoming_indent+2]=$k;}}$this->_containsGroupAnchor=false;}}private static function startsLiteralBlock($line){$lastChar=substr(trim($line),-1);if($lastChar!='>'&&$lastChar!='|')return false;if($lastChar=='|')return$lastChar;if(preg_match('~<.*?>$~',$line))return false;return$lastChar;}private static function greedilyNeedNextLine($line){$line=trim($line);if(!strlen($line))return false;if(substr($line,-1,1)==']')return false;if($line[0]=='[')return true;if(preg_match('#^[^:]+?:\s*\[#',$line))return true;return false;}private function addLiteralLine($literalBlock,$line,$literalBlockStyle){$line=self::stripIndent($line);$line=rtrim($line,"\r\n\t ")."\n";if($literalBlockStyle=='|'){return$literalBlock.$line;}if(strlen($line)==0)return rtrim($literalBlock,' ')."\n";if($line=="\n"&&$literalBlockStyle=='>'){return rtrim($literalBlock," \t")."\n";}if($line!="\n")$line=trim($line,"\r\n ")." ";return$literalBlock.$line;}function revertLiteralPlaceHolder($lineArray,$literalBlock){foreach($lineArray as$k=>$_){if(is_array($_))$lineArray[$k]=$this->revertLiteralPlaceHolder($_,$literalBlock);else if(substr($_,-1*strlen($this->LiteralPlaceHolder))==$this->LiteralPlaceHolder)$lineArray[$k]=rtrim($literalBlock," \r\n");}return$lineArray;}private static function stripIndent($line,$indent=-1){if($indent==-1)$indent=strlen($line)-strlen(ltrim($line));return substr($line,$indent);}private function getParentPathByIndent($indent){if($indent==0)return array();$linePath=$this->path;do{end($linePath);$lastIndentInParentPath=key($linePath);if($indent<=$lastIndentInParentPath)array_pop($linePath);}while($indent<=$lastIndentInParentPath);return$linePath;}private function clearBiggerPathValues($indent){if($indent==0)$this->path=array();if(empty($this->path))return true;foreach($this->path as$k=>$_){if($k>$indent)unset($this->path[$k]);}return true;}private static function isComment($line){if(!$line)return false;if($line[0]=='#')return true;if(trim($line," \r\n\t")=='---')return true;return false;}private static function isEmpty($line){return(trim($line)==='');}private function isArrayElement($line){if(!$line)return false;if($line[0]!='-')return false;if(strlen($line)>3)if(substr($line,0,3)=='---')return false;return true;}private function isHashElement($line){return strpos($line,':');}private function isLiteral($line){if($this->isArrayElement($line))return false;if($this->isHashElement($line))return false;return true;}private static function unquote($value){if(!$value)return$value;if(!is_string($value))return$value;if($value[0]=='\'')return trim($value,'\'');if($value[0]=='"')return trim($value,'"');return$value;}private function startsMappedSequence($line){return($line[0]=='-'&&substr($line,-1,1)==':');}private function returnMappedSequence($line){$array=array();$key=self::unquote(trim(substr($line,1,-1)));$array[$key]=array();$this->delayedPath=array(strpos($line,$key)+$this->indent=>$key);return array($array);}private function returnMappedValue($line){$array=array();$key=self::unquote(trim(substr($line,0,-1)));$array[$key]='';return$array;}private function startsMappedValue($line){return(substr($line,-1,1)==':');}private function isPlainArray($line){return($line[0]=='['&&substr($line,-1,1)==']');}private function returnPlainArray($line){return$this->_toType($line);}private function returnKeyValuePair($line){$array=array();$key='';if(strpos($line,':')){if(($line[0]=='"'||$line[0]=="'")&&preg_match('/^(["\'](.*)["\'](\s)*:)/',$line,$matches)){$value=trim(str_replace($matches[1],'',$line));$key=$matches[2];}else{$explode=explode(':',$line);$key=trim($explode[0]);array_shift($explode);$value=trim(implode(':',$explode));}$value=$this->_toType($value);if($key==='0')$key='__!YAMLZero';$array[$key]=$value;}else{$array=array($line);}return$array;}private function returnArrayElement($line){if(strlen($line)<=1)return array(array());$array=array();$value=trim(substr($line,1));$value=$this->_toType($value);$array[]=$value;return$array;}private function nodeContainsGroup($line){$symbolsForReference='A-z0-9_\-';if(strpos($line,'&')===false&&strpos($line,'*')===false)return false;if($line[0]=='&'&&preg_match('/^(&['.$symbolsForReference.']+)/',$line,$matches))return$matches[1];if($line[0]=='*'&&preg_match('/^(\*['.$symbolsForReference.']+)/',$line,$matches))return$matches[1];if(preg_match('/(&['.$symbolsForReference.']+)$/',$line,$matches))return$matches[1];if(preg_match('/(\*['.$symbolsForReference.']+$)/',$line,$matches))return$matches[1];if(preg_match('#^\s*<<\s*:\s*(\*[^\s]+).*$#',$line,$matches))return$matches[1];return false;}private function addGroup($line,$group){if($group[0]=='&')$this->_containsGroupAnchor=substr($group,1);if($group[0]=='*')$this->_containsGroupAlias=substr($group,1);}private function stripGroup($line,$group){$line=trim(str_replace($group,'',$line));return$line;}}define('SPYC_FROM_COMMAND_LINE',false);do{if(!SPYC_FROM_COMMAND_LINE)break;if(empty($_SERVER['argc'])||$_SERVER['argc']<2)break;if(empty($_SERVER['PHP_SELF'])||$_SERVER['PHP_SELF']!='spyc.php')break;$file=$argv[1];printf("Spyc loading file: %s\n",$file);print_r(spyc_load_file($file));}while(0);define('MARKDOWN_VERSION',"1.0.1n");define('MARKDOWNEXTRA_VERSION',"1.2.4");@define('MARKDOWN_EMPTY_ELEMENT_SUFFIX'," />");@define('MARKDOWN_TAB_WIDTH',4);@define('MARKDOWN_FN_LINK_TITLE',"");@define('MARKDOWN_FN_BACKLINK_TITLE',"");@define('MARKDOWN_FN_LINK_CLASS',"");@define('MARKDOWN_FN_BACKLINK_CLASS',"");@define('MARKDOWN_WP_POSTS',true);@define('MARKDOWN_WP_COMMENTS',true);@define('MARKDOWN_PARSER_CLASS','MarkdownExtra_Parser');function Markdown($text){static$parser;if(!isset($parser)){$parser_class=MARKDOWN_PARSER_CLASS;$parser=new$parser_class;}return$parser->transform($text);}if(isset($wp_version)){if(MARKDOWN_WP_POSTS){remove_filter('the_content','wpautop');remove_filter('the_content_rss','wpautop');remove_filter('the_excerpt','wpautop');add_filter('the_content','mdwp_MarkdownPost',6);add_filter('the_content_rss','mdwp_MarkdownPost',6);add_filter('get_the_excerpt','mdwp_MarkdownPost',6);add_filter('get_the_excerpt','trim',7);add_filter('the_excerpt','mdwp_add_p');add_filter('the_excerpt_rss','mdwp_strip_p');remove_filter('content_save_pre','balanceTags',50);remove_filter('excerpt_save_pre','balanceTags',50);add_filter('the_content','balanceTags',50);add_filter('get_the_excerpt','balanceTags',9);}function mdwp_MarkdownPost($text){static$parser;if(!$parser){$parser_class=MARKDOWN_PARSER_CLASS;$parser=new$parser_class;}if(is_single()||is_page()||is_feed()){$parser->fn_id_prefix="";}else{$parser->fn_id_prefix=get_the_ID().".";}return$parser->transform($text);}if(MARKDOWN_WP_COMMENTS){remove_filter('comment_text','wpautop',30);remove_filter('comment_text','make_clickable');add_filter('pre_comment_content','Markdown',6);add_filter('pre_comment_content','mdwp_hide_tags',8);add_filter('pre_comment_content','mdwp_show_tags',12);add_filter('get_comment_text','Markdown',6);add_filter('get_comment_excerpt','Markdown',6);add_filter('get_comment_excerpt','mdwp_strip_p',7);global$mdwp_hidden_tags,$mdwp_placeholders;$mdwp_hidden_tags=explode(' ','<p> </p> <pre> </pre> <ol> </ol> <ul> </ul> <li> </li>');$mdwp_placeholders=explode(' ',str_rot13('pEj07ZbbBZ U1kqgh4w4p pre2zmeN6K QTi31t9pre ol0MP1jzJR '.'ML5IjmbRol ulANi1NsGY J7zRLJqPul liA8ctl16T K9nhooUHli'));}function mdwp_add_p($text){if(!preg_match('{^$|^<(p|ul|ol|dl|pre|blockquote)>}i',$text)){$text='<p>'.$text.'</p>';$text=preg_replace('{\n{2,}}',"</p>\n\n<p>",$text);}return$text;}function mdwp_strip_p($t){return preg_replace('{</?p>}i','',$t);}function mdwp_hide_tags($text){global$mdwp_hidden_tags,$mdwp_placeholders;return str_replace($mdwp_hidden_tags,$mdwp_placeholders,$text);}function mdwp_show_tags($text){global$mdwp_hidden_tags,$mdwp_placeholders;return str_replace($mdwp_placeholders,$mdwp_hidden_tags,$text);}}function identify_modifier_markdown(){return array('name'=>'markdown','type'=>'modifier','nicename'=>'PHP Markdown Extra','description'=>'A text-to-HTML conversion tool for web writers','authors'=>'Michel Fortin and John Gruber','licence'=>'GPL','version'=>MARKDOWNEXTRA_VERSION,'help'=>'<a href="http://daringfireball.net/projects/markdown/syntax">Markdown syntax</a> allows you to write using an easy-to-read, easy-to-write plain text format. Based on the original Perl version by <a href="http://daringfireball.net/">John Gruber</a>. <a href="http://michelf.com/projects/php-markdown/">More...</a>',);}function smarty_modifier_markdown($text){return Markdown($text);}if(strcasecmp(substr(__FILE__,-16),"classTextile.php")==0){@include_once'smartypants.php';class Textile{function TextileThis($text,$lite='',$encode=''){if($lite==''&&$encode=='')$text=Markdown($text);if(function_exists('SmartyPants'))$text=SmartyPants($text);return$text;}function TextileRestricted($text,$lite='',$noimage=''){return$this->TextileThis($text,$lite);}function blockLite($text){return$text;}}}class Markdown_Parser{var$nested_brackets_depth=6;var$nested_brackets_re;var$nested_url_parenthesis_depth=4;var$nested_url_parenthesis_re;var$escape_chars='\`*_{}[]()>#+-.!';var$escape_chars_re;var$empty_element_suffix=MARKDOWN_EMPTY_ELEMENT_SUFFIX;var$tab_width=MARKDOWN_TAB_WIDTH;var$no_markup=false;var$no_entities=false;var$predef_urls=array();var$predef_titles=array();function Markdown_Parser(){$this->_initDetab();$this->prepareItalicsAndBold();$this->nested_brackets_re=str_repeat('(?>[^\[\]]+|\[',$this->nested_brackets_depth).str_repeat('\])*',$this->nested_brackets_depth);$this->nested_url_parenthesis_re=str_repeat('(?>[^()\s]+|\(',$this->nested_url_parenthesis_depth).str_repeat('(?>\)))*',$this->nested_url_parenthesis_depth);$this->escape_chars_re='['.preg_quote($this->escape_chars).']';asort($this->document_gamut);asort($this->block_gamut);asort($this->span_gamut);}var$urls=array();var$titles=array();var$html_hashes=array();var$in_anchor=false;function setup(){$this->urls=$this->predef_urls;$this->titles=$this->predef_titles;$this->html_hashes=array();$in_anchor=false;}function teardown(){$this->urls=array();$this->titles=array();$this->html_hashes=array();}function transform($text){$this->setup();$text=preg_replace('{^\xEF\xBB\xBF|\x1A}','',$text);$text=preg_replace('{\r\n?}',"\n",$text);$text.="\n\n";$text=$this->detab($text);$text=$this->hashHTMLBlocks($text);$text=preg_replace('/^[ ]+$/m','',$text);foreach($this->document_gamut as$method=>$priority){$text=$this->$method($text);}$this->teardown();return$text."\n";}var$document_gamut=array("stripLinkDefinitions"=>20,"runBasicBlockGamut"=>30,);function stripLinkDefinitions($text){$less_than_tab=$this->tab_width-1;$text=preg_replace_callback('{
							^[ ]{0,'.$less_than_tab.'}\[(.+)\][ ]?:	# id = $1
							  [ ]*
							  \n?				# maybe *one* newline
							  [ ]*
							(?:
							  <(.+?)>			# url = $2
							|
							  (\S+?)			# url = $3
							)
							  [ ]*
							  \n?				# maybe one newline
							  [ ]*
							(?:
								(?<=\s)			# lookbehind for whitespace
								["(]
								(.*?)			# title = $4
								[")]
								[ ]*
							)?	# title is optional
							(?:\n+|\Z)
			}xm',array(&$this,'_stripLinkDefinitions_callback'),$text);return$text;}function _stripLinkDefinitions_callback($matches){$link_id=strtolower($matches[1]);$url=$matches[2]==''?$matches[3]:$matches[2];$this->urls[$link_id]=$url;$this->titles[$link_id]=&$matches[4];return'';}function hashHTMLBlocks($text){if($this->no_markup)return$text;$less_than_tab=$this->tab_width-1;$block_tags_a_re='ins|del';$block_tags_b_re='p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|'.'script|noscript|form|fieldset|iframe|math';$nested_tags_level=4;$attr='
			(?>				# optional tag attributes
			  \s			# starts with whitespace
			  (?>
				[^>"/]+		# text outside quotes
			  |
				/+(?!>)		# slash not followed by ">"
			  |
				"[^"]*"		# text inside double quotes (tolerate ">")
			  |
				\'[^\']*\'	# text inside single quotes (tolerate ">")
			  )*
			)?	
			';$content=str_repeat('
				(?>
				  [^<]+			# content without tag
				|
				  <\2			# nested opening tag
					'.$attr.'	# attributes
					(?>
					  />
					|
					  >',$nested_tags_level).'.*?'.str_repeat('
					  </\2\s*>	# closing nested tag
					)
				  |				
					<(?!/\2\s*>	# other tags with a different name
				  )
				)*',$nested_tags_level);$content2=str_replace('\2','\3',$content);$text=preg_replace_callback('{(?>
			(?>
				(?<=\n\n)		# Starting after a blank line
				|				# or
				\A\n?			# the beginning of the doc
			)
			(						# save in $1

			  # Match from `\n<tag>` to `</tag>\n`, handling nested tags 
			  # in between.
					
						[ ]{0,'.$less_than_tab.'}
						<('.$block_tags_b_re.')# start tag = $2
						'.$attr.'>			# attributes followed by > and \n
						'.$content.'		# content, support nesting
						</\2>				# the matching end tag
						[ ]*				# trailing spaces/tabs
						(?=\n+|\Z)	# followed by a newline or end of document

			| # Special version for tags of group a.

						[ ]{0,'.$less_than_tab.'}
						<('.$block_tags_a_re.')# start tag = $3
						'.$attr.'>[ ]*\n	# attributes followed by >
						'.$content2.'		# content, support nesting
						</\3>				# the matching end tag
						[ ]*				# trailing spaces/tabs
						(?=\n+|\Z)	# followed by a newline or end of document
					
			| # Special case just for <hr />. It was easier to make a special 
			  # case than to make the other regex more complicated.
			
						[ ]{0,'.$less_than_tab.'}
						<(hr)				# start tag = $2
						'.$attr.'			# attributes
						/?>					# the matching end tag
						[ ]*
						(?=\n{2,}|\Z)		# followed by a blank line or end of document
			
			| # Special case for standalone HTML comments:
			
					[ ]{0,'.$less_than_tab.'}
					(?s:
						<!-- .*? -->
					)
					[ ]*
					(?=\n{2,}|\Z)		# followed by a blank line or end of document
			
			| # PHP and ASP-style processor instructions (<? and <%)
			
					[ ]{0,'.$less_than_tab.'}
					(?s:
						<([?%])			# $2
						.*?
						\2>
					)
					[ ]*
					(?=\n{2,}|\Z)		# followed by a blank line or end of document
					
			)
			)}Sxmi',array(&$this,'_hashHTMLBlocks_callback'),$text);return$text;}function _hashHTMLBlocks_callback($matches){$text=$matches[1];$key=$this->hashBlock($text);return"\n\n$key\n\n";}function hashPart($text,$boundary='X'){$text=$this->unhash($text);static$i=0;$key="$boundary\x1A".++$i.$boundary;$this->html_hashes[$key]=$text;return$key;}function hashBlock($text){return$this->hashPart($text,'B');}var$block_gamut=array("doHeaders"=>10,"doHorizontalRules"=>20,"doLists"=>40,"doCodeBlocks"=>50,"doBlockQuotes"=>60,);function runBlockGamut($text){$text=$this->hashHTMLBlocks($text);return$this->runBasicBlockGamut($text);}function runBasicBlockGamut($text){foreach($this->block_gamut as$method=>$priority){$text=$this->$method($text);}$text=$this->formParagraphs($text);return$text;}function doHorizontalRules($text){return preg_replace('{
				^[ ]{0,3}	# Leading space
				([-*_])		# $1: First marker
				(?>			# Repeated marker group
					[ ]{0,2}	# Zero, one, or two spaces.
					\1			# Marker character
				){2,}		# Group repeated at least twice
				[ ]*		# Tailing spaces
				$			# End of line.
			}mx',"\n".$this->hashBlock("<hr$this->empty_element_suffix")."\n",$text);}var$span_gamut=array("parseSpan"=>-30,"doImages"=>10,"doAnchors"=>20,"doAutoLinks"=>30,"encodeAmpsAndAngles"=>40,"doItalicsAndBold"=>50,"doHardBreaks"=>60,);function runSpanGamut($text){foreach($this->span_gamut as$method=>$priority){$text=$this->$method($text);}return$text;}function doHardBreaks($text){return preg_replace_callback('/ {2,}\n/',array(&$this,'_doHardBreaks_callback'),$text);}function _doHardBreaks_callback($matches){return$this->hashPart("<br$this->empty_element_suffix\n");}function doAnchors($text){if($this->in_anchor)return$text;$this->in_anchor=true;$text=preg_replace_callback('{
			(					# wrap whole match in $1
			  \[
				('.$this->nested_brackets_re.')	# link text = $2
			  \]

			  [ ]?				# one optional space
			  (?:\n[ ]*)?		# one optional newline followed by spaces

			  \[
				(.*?)		# id = $3
			  \]
			)
			}xs',array(&$this,'_doAnchors_reference_callback'),$text);$text=preg_replace_callback('{
			(				# wrap whole match in $1
			  \[
				('.$this->nested_brackets_re.')	# link text = $2
			  \]
			  \(			# literal paren
				[ \n]*
				(?:
					<(.+?)>	# href = $3
				|
					('.$this->nested_url_parenthesis_re.')	# href = $4
				)
				[ \n]*
				(			# $5
				  ([\'"])	# quote char = $6
				  (.*?)		# Title = $7
				  \6		# matching quote
				  [ \n]*	# ignore any spaces/tabs between closing quote and )
				)?			# title is optional
			  \)
			)
			}xs',array(&$this,'_doAnchors_inline_callback'),$text);$text=preg_replace_callback('{
			(					# wrap whole match in $1
			  \[
				([^\[\]]+)		# link text = $2; can\'t contain [ or ]
			  \]
			)
			}xs',array(&$this,'_doAnchors_reference_callback'),$text);$this->in_anchor=false;return$text;}function _doAnchors_reference_callback($matches){$whole_match=$matches[1];$link_text=$matches[2];$link_id=&$matches[3];if($link_id==""){$link_id=$link_text;}$link_id=strtolower($link_id);$link_id=preg_replace('{[ ]?\n}',' ',$link_id);if(isset($this->urls[$link_id])){$url=$this->urls[$link_id];$url=$this->encodeAttribute($url);$result="<a href=\"$url\"";if(isset($this->titles[$link_id])){$title=$this->titles[$link_id];$title=$this->encodeAttribute($title);$result.=" title=\"$title\"";}$link_text=$this->runSpanGamut($link_text);$result.=">$link_text</a>";$result=$this->hashPart($result);}else{$result=$whole_match;}return$result;}function _doAnchors_inline_callback($matches){$whole_match=$matches[1];$link_text=$this->runSpanGamut($matches[2]);$url=$matches[3]==''?$matches[4]:$matches[3];$title=&$matches[7];$url=$this->encodeAttribute($url);$result="<a href=\"$url\"";if(isset($title)){$title=$this->encodeAttribute($title);$result.=" title=\"$title\"";}$link_text=$this->runSpanGamut($link_text);$result.=">$link_text</a>";return$this->hashPart($result);}function doImages($text){$text=preg_replace_callback('{
			(				# wrap whole match in $1
			  !\[
				('.$this->nested_brackets_re.')		# alt text = $2
			  \]

			  [ ]?				# one optional space
			  (?:\n[ ]*)?		# one optional newline followed by spaces

			  \[
				(.*?)		# id = $3
			  \]

			)
			}xs',array(&$this,'_doImages_reference_callback'),$text);$text=preg_replace_callback('{
			(				# wrap whole match in $1
			  !\[
				('.$this->nested_brackets_re.')		# alt text = $2
			  \]
			  \s?			# One optional whitespace character
			  \(			# literal paren
				[ \n]*
				(?:
					<(\S*)>	# src url = $3
				|
					('.$this->nested_url_parenthesis_re.')	# src url = $4
				)
				[ \n]*
				(			# $5
				  ([\'"])	# quote char = $6
				  (.*?)		# title = $7
				  \6		# matching quote
				  [ \n]*
				)?			# title is optional
			  \)
			)
			}xs',array(&$this,'_doImages_inline_callback'),$text);return$text;}function _doImages_reference_callback($matches){$whole_match=$matches[1];$alt_text=$matches[2];$link_id=strtolower($matches[3]);if($link_id==""){$link_id=strtolower($alt_text);}$alt_text=$this->encodeAttribute($alt_text);if(isset($this->urls[$link_id])){$url=$this->encodeAttribute($this->urls[$link_id]);$result="<img src=\"$url\" alt=\"$alt_text\"";if(isset($this->titles[$link_id])){$title=$this->titles[$link_id];$title=$this->encodeAttribute($title);$result.=" title=\"$title\"";}$result.=$this->empty_element_suffix;$result=$this->hashPart($result);}else{$result=$whole_match;}return$result;}function _doImages_inline_callback($matches){$whole_match=$matches[1];$alt_text=$matches[2];$url=$matches[3]==''?$matches[4]:$matches[3];$title=&$matches[7];$alt_text=$this->encodeAttribute($alt_text);$url=$this->encodeAttribute($url);$result="<img src=\"$url\" alt=\"$alt_text\"";if(isset($title)){$title=$this->encodeAttribute($title);$result.=" title=\"$title\"";}$result.=$this->empty_element_suffix;return$this->hashPart($result);}function doHeaders($text){$text=preg_replace_callback('{ ^(.+?)[ ]*\n(=+|-+)[ ]*\n+ }mx',array(&$this,'_doHeaders_callback_setext'),$text);$text=preg_replace_callback('{
				^(\#{1,6})	# $1 = string of #\'s
				[ ]*
				(.+?)		# $2 = Header text
				[ ]*
				\#*			# optional closing #\'s (not counted)
				\n+
			}xm',array(&$this,'_doHeaders_callback_atx'),$text);return$text;}function _doHeaders_callback_setext($matches){if($matches[2]=='-'&&preg_match('{^-(?: |$)}',$matches[1]))return$matches[0];$level=$matches[2]{0}=='='?1:2;$block="<h$level>".$this->runSpanGamut($matches[1])."</h$level>";return"\n".$this->hashBlock($block)."\n\n";}function _doHeaders_callback_atx($matches){$level=strlen($matches[1]);$block="<h$level>".$this->runSpanGamut($matches[2])."</h$level>";return"\n".$this->hashBlock($block)."\n\n";}function doLists($text){$less_than_tab=$this->tab_width-1;$marker_ul_re='[*+-]';$marker_ol_re='\d+[.]';$marker_any_re="(?:$marker_ul_re|$marker_ol_re)";$markers_relist=array($marker_ul_re=>$marker_ol_re,$marker_ol_re=>$marker_ul_re,);foreach($markers_relist as$marker_re=>$other_marker_re){$whole_list_re='
				(								# $1 = whole list
				  (								# $2
					([ ]{0,'.$less_than_tab.'})	# $3 = number of spaces
					('.$marker_re.')			# $4 = first list item marker
					[ ]+
				  )
				  (?s:.+?)
				  (								# $5
					  \z
					|
					  \n{2,}
					  (?=\S)
					  (?!						# Negative lookahead for another list item marker
						[ ]*
						'.$marker_re.'[ ]+
					  )
					|
					  (?=						# Lookahead for another kind of list
					    \n
						\3						# Must have the same indentation
						'.$other_marker_re.'[ ]+
					  )
				  )
				)
			';if($this->list_level){$text=preg_replace_callback('{
						^
						'.$whole_list_re.'
					}mx',array(&$this,'_doLists_callback'),$text);}else{$text=preg_replace_callback('{
						(?:(?<=\n)\n|\A\n?) # Must eat the newline
						'.$whole_list_re.'
					}mx',array(&$this,'_doLists_callback'),$text);}}return$text;}function _doLists_callback($matches){$marker_ul_re='[*+-]';$marker_ol_re='\d+[.]';$marker_any_re="(?:$marker_ul_re|$marker_ol_re)";$list=$matches[1];$list_type=preg_match("/$marker_ul_re/",$matches[4])?"ul":"ol";$marker_any_re=($list_type=="ul"?$marker_ul_re:$marker_ol_re);$list.="\n";$result=$this->processListItems($list,$marker_any_re);$result=$this->hashBlock("<$list_type>\n".$result."</$list_type>");return"\n".$result."\n\n";}var$list_level=0;function processListItems($list_str,$marker_any_re){$this->list_level++;$list_str=preg_replace("/\n{2,}\\z/","\n",$list_str);$list_str=preg_replace_callback('{
			(\n)?							# leading line = $1
			(^[ ]*)							# leading whitespace = $2
			('.$marker_any_re.'				# list marker and space = $3
				(?:[ ]+|(?=\n))	# space only required if item is not empty
			)
			((?s:.*?))						# list item text   = $4
			(?:(\n+(?=\n))|\n)				# tailing blank line = $5
			(?= \n* (\z | \2 ('.$marker_any_re.') (?:[ ]+|(?=\n))))
			}xm',array(&$this,'_processListItems_callback'),$list_str);$this->list_level--;return$list_str;}function _processListItems_callback($matches){$item=$matches[4];$leading_line=&$matches[1];$leading_space=&$matches[2];$marker_space=$matches[3];$tailing_blank_line=&$matches[5];if($leading_line||$tailing_blank_line||preg_match('/\n{2,}/',$item)){$item=$leading_space.str_repeat(' ',strlen($marker_space)).$item;$item=$this->runBlockGamut($this->outdent($item)."\n");}else{$item=$this->doLists($this->outdent($item));$item=preg_replace('/\n+$/','',$item);$item=$this->runSpanGamut($item);}return"<li>".$item."</li>\n";}function doCodeBlocks($text){$text=preg_replace_callback('{
				(?:\n\n|\A\n?)
				(	            # $1 = the code block -- one or more lines, starting with a space/tab
				  (?>
					[ ]{'.$this->tab_width.'}  # Lines must start with a tab or a tab-width of spaces
					.*\n+
				  )+
				)
				((?=^[ ]{0,'.$this->tab_width.'}\S)|\Z)	# Lookahead for non-space at line-start, or end of doc
			}xm',array(&$this,'_doCodeBlocks_callback'),$text);return$text;}function _doCodeBlocks_callback($matches){$codeblock=$matches[1];$codeblock=$this->outdent($codeblock);$codeblock=htmlspecialchars($codeblock,ENT_NOQUOTES);$codeblock=preg_replace('/\A\n+|\n+\z/','',$codeblock);$codeblock="<pre><code>$codeblock\n</code></pre>";return"\n\n".$this->hashBlock($codeblock)."\n\n";}function makeCodeSpan($code){$code=htmlspecialchars(trim($code),ENT_NOQUOTES);return$this->hashPart("<code>$code</code>");}var$em_relist=array(''=>'(?:(?<!\*)\*(?!\*)|(?<!_)_(?!_))(?=\S|$)(?![.,:;]\s)','*'=>'(?<=\S|^)(?<!\*)\*(?!\*)','_'=>'(?<=\S|^)(?<!_)_(?!_)',);var$strong_relist=array(''=>'(?:(?<!\*)\*\*(?!\*)|(?<!_)__(?!_))(?=\S|$)(?![.,:;]\s)','**'=>'(?<=\S|^)(?<!\*)\*\*(?!\*)','__'=>'(?<=\S|^)(?<!_)__(?!_)',);var$em_strong_relist=array(''=>'(?:(?<!\*)\*\*\*(?!\*)|(?<!_)___(?!_))(?=\S|$)(?![.,:;]\s)','***'=>'(?<=\S|^)(?<!\*)\*\*\*(?!\*)','___'=>'(?<=\S|^)(?<!_)___(?!_)',);var$em_strong_prepared_relist;function prepareItalicsAndBold(){foreach($this->em_relist as$em=>$em_re){foreach($this->strong_relist as$strong=>$strong_re){$token_relist=array();if(isset($this->em_strong_relist["$em$strong"])){$token_relist[]=$this->em_strong_relist["$em$strong"];}$token_relist[]=$em_re;$token_relist[]=$strong_re;$token_re='{('.implode('|',$token_relist).')}';$this->em_strong_prepared_relist["$em$strong"]=$token_re;}}}function doItalicsAndBold($text){$token_stack=array('');$text_stack=array('');$em='';$strong='';$tree_char_em=false;while(1){$token_re=$this->em_strong_prepared_relist["$em$strong"];$parts=preg_split($token_re,$text,2,PREG_SPLIT_DELIM_CAPTURE);$text_stack[0].=$parts[0];$token=&$parts[1];$text=&$parts[2];if(empty($token)){while($token_stack[0]){$text_stack[1].=array_shift($token_stack);$text_stack[0].=array_shift($text_stack);}break;}$token_len=strlen($token);if($tree_char_em){if($token_len==3){array_shift($token_stack);$span=array_shift($text_stack);$span=$this->runSpanGamut($span);$span="<strong><em>$span</em></strong>";$text_stack[0].=$this->hashPart($span);$em='';$strong='';}else{$token_stack[0]=str_repeat($token{0},3-$token_len);$tag=$token_len==2?"strong":"em";$span=$text_stack[0];$span=$this->runSpanGamut($span);$span="<$tag>$span</$tag>";$text_stack[0]=$this->hashPart($span);$$tag='';}$tree_char_em=false;}else if($token_len==3){if($em){for($i=0;$i<2;++$i){$shifted_token=array_shift($token_stack);$tag=strlen($shifted_token)==2?"strong":"em";$span=array_shift($text_stack);$span=$this->runSpanGamut($span);$span="<$tag>$span</$tag>";$text_stack[0].=$this->hashPart($span);$$tag='';}}else{$em=$token{0};$strong="$em$em";array_unshift($token_stack,$token);array_unshift($text_stack,'');$tree_char_em=true;}}else if($token_len==2){if($strong){if(strlen($token_stack[0])==1){$text_stack[1].=array_shift($token_stack);$text_stack[0].=array_shift($text_stack);}array_shift($token_stack);$span=array_shift($text_stack);$span=$this->runSpanGamut($span);$span="<strong>$span</strong>";$text_stack[0].=$this->hashPart($span);$strong='';}else{array_unshift($token_stack,$token);array_unshift($text_stack,'');$strong=$token;}}else{if($em){if(strlen($token_stack[0])==1){array_shift($token_stack);$span=array_shift($text_stack);$span=$this->runSpanGamut($span);$span="<em>$span</em>";$text_stack[0].=$this->hashPart($span);$em='';}else{$text_stack[0].=$token;}}else{array_unshift($token_stack,$token);array_unshift($text_stack,'');$em=$token;}}}return$text_stack[0];}function doBlockQuotes($text){$text=preg_replace_callback('/
			  (								# Wrap whole match in $1
				(?>
				  ^[ ]*>[ ]?			# ">" at the start of a line
					.+\n					# rest of the first line
				  (.+\n)*					# subsequent consecutive lines
				  \n*						# blanks
				)+
			  )
			/xm',array(&$this,'_doBlockQuotes_callback'),$text);return$text;}function _doBlockQuotes_callback($matches){$bq=$matches[1];$bq=preg_replace('/^[ ]*>[ ]?|^[ ]+$/m','',$bq);$bq=$this->runBlockGamut($bq);$bq=preg_replace('/^/m',"  ",$bq);$bq=preg_replace_callback('{(\s*<pre>.+?</pre>)}sx',array(&$this,'_doBlockQuotes_callback2'),$bq);return"\n".$this->hashBlock("<blockquote>\n$bq\n</blockquote>")."\n\n";}function _doBlockQuotes_callback2($matches){$pre=$matches[1];$pre=preg_replace('/^  /m','',$pre);return$pre;}function formParagraphs($text){$text=preg_replace('/\A\n+|\n+\z/','',$text);$grafs=preg_split('/\n{2,}/',$text,-1,PREG_SPLIT_NO_EMPTY);foreach($grafs as$key=>$value){if(!preg_match('/^B\x1A[0-9]+B$/',$value)){$value=$this->runSpanGamut($value);$value=preg_replace('/^([ ]*)/',"<p>",$value);$value.="</p>";$grafs[$key]=$this->unhash($value);}else{$graf=$value;$block=$this->html_hashes[$graf];$graf=$block;$grafs[$key]=$graf;}}return implode("\n\n",$grafs);}function encodeAttribute($text){$text=$this->encodeAmpsAndAngles($text);$text=str_replace('"','&quot;',$text);return$text;}function encodeAmpsAndAngles($text){if($this->no_entities){$text=str_replace('&','&amp;',$text);}else{$text=preg_replace('/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/','&amp;',$text);;}$text=str_replace('<','&lt;',$text);return$text;}function doAutoLinks($text){$text=preg_replace_callback('{<((https?|ftp|dict):[^\'">\s]+)>}i',array(&$this,'_doAutoLinks_url_callback'),$text);$text=preg_replace_callback('{
			<
			(?:mailto:)?
			(
				(?:
					[-!#$%&\'*+/=?^_`.{|}~\w\x80-\xFF]+
				|
					".*?"
				)
				\@
				(?:
					[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
				|
					\[[\d.a-fA-F:]+\]	# IPv4 & IPv6
				)
			)
			>
			}xi',array(&$this,'_doAutoLinks_email_callback'),$text);return$text;}function _doAutoLinks_url_callback($matches){$url=$this->encodeAttribute($matches[1]);$link="<a href=\"$url\">$url</a>";return$this->hashPart($link);}function _doAutoLinks_email_callback($matches){$address=$matches[1];$link=$this->encodeEmailAddress($address);return$this->hashPart($link);}function encodeEmailAddress($addr){$addr="mailto:".$addr;$chars=preg_split('/(?<!^)(?!$)/',$addr);$seed=(int)abs(crc32($addr)/strlen($addr));foreach($chars as$key=>$char){$ord=ord($char);if($ord<128){$r=($seed*(1+$key))% 100;if($r>90&&$char!='@');else if($r<45)$chars[$key]='&#x'.dechex($ord).';';else$chars[$key]='&#'.$ord.';';}}$addr=implode('',$chars);$text=implode('',array_slice($chars,7));$addr="<a href=\"$addr\">$text</a>";return$addr;}function parseSpan($str){$output='';$span_re='{
				(
					\\\\'.$this->escape_chars_re.'
				|
					(?<![`\\\\])
					`+						# code span marker
			'.($this->no_markup?'':'
				|
					<!--    .*?     -->		# comment
				|
					<\?.*?\?> | <%.*?%>		# processing instruction
				|
					<[/!$]?[-a-zA-Z0-9:_]+	# regular tags
					(?>
						\s
						(?>[^"\'>]+|"[^"]*"|\'[^\']*\')*
					)?
					>
			').'
				)
				}xs';while(1){$parts=preg_split($span_re,$str,2,PREG_SPLIT_DELIM_CAPTURE);if($parts[0]!=""){$output.=$parts[0];}if(isset($parts[1])){$output.=$this->handleSpanToken($parts[1],$parts[2]);$str=$parts[2];}else{break;}}return$output;}function handleSpanToken($token,&$str){switch($token{0}){case"\\":return$this->hashPart("&#".ord($token{1}).";");case"`":if(preg_match('/^(.*?[^`])'.preg_quote($token).'(?!`)(.*)$/sm',$str,$matches)){$str=$matches[2];$codespan=$this->makeCodeSpan($matches[1]);return$this->hashPart($codespan);}return$token;default:return$this->hashPart($token);}}function outdent($text){return preg_replace('/^(\t|[ ]{1,'.$this->tab_width.'})/m','',$text);}var$utf8_strlen='mb_strlen';function detab($text){$text=preg_replace_callback('/^.*\t.*$/m',array(&$this,'_detab_callback'),$text);return$text;}function _detab_callback($matches){$line=$matches[0];$strlen=$this->utf8_strlen;$blocks=explode("\t",$line);$line=$blocks[0];unset($blocks[0]);foreach($blocks as$block){$amount=$this->tab_width-$strlen($line,'UTF-8')%$this->tab_width;$line.=str_repeat(" ",$amount).$block;}return$line;}function _initDetab(){if(function_exists($this->utf8_strlen))return;$this->utf8_strlen=create_function('$text','return preg_match_all(
			"/[\\\\x00-\\\\xBF]|[\\\\xC0-\\\\xFF][\\\\x80-\\\\xBF]*/", 
			$text, $m);');}function unhash($text){return preg_replace_callback('/(.)\x1A[0-9]+\1/',array(&$this,'_unhash_callback'),$text);}function _unhash_callback($matches){return$this->html_hashes[$matches[0]];}}class MarkdownExtra_Parser extends Markdown_Parser{var$fn_id_prefix="";var$fn_link_title=MARKDOWN_FN_LINK_TITLE;var$fn_backlink_title=MARKDOWN_FN_BACKLINK_TITLE;var$fn_link_class=MARKDOWN_FN_LINK_CLASS;var$fn_backlink_class=MARKDOWN_FN_BACKLINK_CLASS;var$predef_abbr=array();function MarkdownExtra_Parser(){$this->escape_chars.=':|';$this->document_gamut+=array("doFencedCodeBlocks"=>5,"stripFootnotes"=>15,"stripAbbreviations"=>25,"appendFootnotes"=>50,);$this->block_gamut+=array("doFencedCodeBlocks"=>5,"doTables"=>15,"doDefLists"=>45,);$this->span_gamut+=array("doFootnotes"=>5,"doAbbreviations"=>70,);parent::Markdown_Parser();}var$footnotes=array();var$footnotes_ordered=array();var$abbr_desciptions=array();var$abbr_word_re='';var$footnote_counter=1;function setup(){parent::setup();$this->footnotes=array();$this->footnotes_ordered=array();$this->abbr_desciptions=array();$this->abbr_word_re='';$this->footnote_counter=1;foreach($this->predef_abbr as$abbr_word=>$abbr_desc){if($this->abbr_word_re)$this->abbr_word_re.='|';$this->abbr_word_re.=preg_quote($abbr_word);$this->abbr_desciptions[$abbr_word]=trim($abbr_desc);}}function teardown(){$this->footnotes=array();$this->footnotes_ordered=array();$this->abbr_desciptions=array();$this->abbr_word_re='';parent::teardown();}var$block_tags_re='p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|form|fieldset|iframe|hr|legend';var$context_block_tags_re='script|noscript|math|ins|del';var$contain_span_tags_re='p|h[1-6]|li|dd|dt|td|th|legend|address';var$clean_tags_re='script|math';var$auto_close_tags_re='hr|img';function hashHTMLBlocks($text){list($text,)=$this->_hashHTMLBlocks_inMarkdown($text);return$text;}function _hashHTMLBlocks_inMarkdown($text,$indent=0,$enclosing_tag_re='',$span=false){if($text==='')return array('','');$newline_before_re='/(?:^\n?|\n\n)*$/';$newline_after_re='{
				^						# Start of text following the tag.
				(?>[ ]*<!--.*?-->)?		# Optional comment.
				[ ]*\n					# Must be followed by newline.
			}xs';$block_tag_re='{
				(					# $2: Capture hole tag.
					</?					# Any opening or closing tag.
						(?>				# Tag name.
							'.$this->block_tags_re.'			|
							'.$this->context_block_tags_re.'	|
							'.$this->clean_tags_re.'        	|
							(?!\s)'.$enclosing_tag_re.'
						)
						(?:
							(?=[\s"\'/a-zA-Z0-9])	# Allowed characters after tag name.
							(?>
								".*?"		|	# Double quotes (can contain `>`)
								\'.*?\'   	|	# Single quotes (can contain `>`)
								.+?				# Anything but quotes and `>`.
							)*?
						)?
					>					# End of tag.
				|
					<!--    .*?     -->	# HTML Comment
				|
					<\?.*?\?> | <%.*?%>	# Processing instruction
				|
					<!\[CDATA\[.*?\]\]>	# CData Block
				|
					# Code span marker
					`+
				'.(!$span?' # If not in span.
				|
					# Indented code block
					(?: ^[ ]*\n | ^ | \n[ ]*\n )
					[ ]{'.($indent+4).'}[^\n]* \n
					(?>
						(?: [ ]{'.($indent+4).'}[^\n]* | [ ]* ) \n
					)*
				|
					# Fenced code block marker
					(?> ^ | \n )
					[ ]{'.($indent).'}~~~+[ ]*\n
				':'').' # End (if not is span).
				)
			}xs';$depth=0;$parsed="";do{$parts=preg_split($block_tag_re,$text,2,PREG_SPLIT_DELIM_CAPTURE);if($span){$void=$this->hashPart("",':');$newline="$void\n";$parts[0]=$void.str_replace("\n",$newline,$parts[0]).$void;}$parsed.=$parts[0];if(count($parts)<3){$text="";break;}$tag=$parts[1];$text=$parts[2];$tag_re=preg_quote($tag);if($tag{0}=="`"){$tag_re=preg_quote($tag);if(preg_match('{^(?>.+?|\n(?!\n))*?(?<!`)'.$tag_re.'(?!`)}',$text,$matches)){$parsed.=$tag.$matches[0];$text=substr($text,strlen($matches[0]));}else{$parsed.=$tag;}}else if($tag{0}=="\n"||$tag{0}==" "){$parsed.=$tag;}else if($tag{0}=="~"){$tag_re=preg_quote(trim($tag));if(preg_match('{^(?>.*\n)+?'.$tag_re.' *\n}',$text,$matches)){$parsed.=$tag.$matches[0];$text=substr($text,strlen($matches[0]));}else{$parsed.=$tag;}}else if(preg_match('{^<(?:'.$this->block_tags_re.')\b}',$tag)||(preg_match('{^<(?:'.$this->context_block_tags_re.')\b}',$tag)&&preg_match($newline_before_re,$parsed)&&preg_match($newline_after_re,$text))){list($block_text,$text)=$this->_hashHTMLBlocks_inHTML($tag.$text,"hashBlock",true);$parsed.="\n\n$block_text\n\n";}else if(preg_match('{^<(?:'.$this->clean_tags_re.')\b}',$tag)||$tag{1}=='!'||$tag{1}=='?'){list($block_text,$text)=$this->_hashHTMLBlocks_inHTML($tag.$text,"hashClean",false);$parsed.=$block_text;}else if($enclosing_tag_re!==''&&preg_match('{^</?(?:'.$enclosing_tag_re.')\b}',$tag)){if($tag{1}=='/')$depth--;else if($tag{strlen($tag)-2}!='/')$depth++;if($depth<0){$text=$tag.$text;break;}$parsed.=$tag;}else{$parsed.=$tag;}}while($depth>=0);return array($parsed,$text);}function _hashHTMLBlocks_inHTML($text,$hash_method,$md_attr){if($text==='')return array('','');$markdown_attr_re='
			{
				\s*			# Eat whitespace before the `markdown` attribute
				markdown
				\s*=\s*
				(?>
					(["\'])		# $1: quote delimiter		
					(.*?)		# $2: attribute value
					\1			# matching delimiter	
				|
					([^\s>]*)	# $3: unquoted attribute value
				)
				()				# $4: make $3 always defined (avoid warnings)
			}xs';$tag_re='{
				(					# $2: Capture hole tag.
					</?					# Any opening or closing tag.
						[\w:$]+			# Tag name.
						(?:
							(?=[\s"\'/a-zA-Z0-9])	# Allowed characters after tag name.
							(?>
								".*?"		|	# Double quotes (can contain `>`)
								\'.*?\'   	|	# Single quotes (can contain `>`)
								.+?				# Anything but quotes and `>`.
							)*?
						)?
					>					# End of tag.
				|
					<!--    .*?     -->	# HTML Comment
				|
					<\?.*?\?> | <%.*?%>	# Processing instruction
				|
					<!\[CDATA\[.*?\]\]>	# CData Block
				)
			}xs';$original_text=$text;$depth=0;$block_text="";$parsed="";if(preg_match('/^<([\w:$]*)\b/',$text,$matches))$base_tag_name_re=$matches[1];do{$parts=preg_split($tag_re,$text,2,PREG_SPLIT_DELIM_CAPTURE);if(count($parts)<3){return array($original_text{0},substr($original_text,1));}$block_text.=$parts[0];$tag=$parts[1];$text=$parts[2];if(preg_match('{^</?(?:'.$this->auto_close_tags_re.')\b}',$tag)||$tag{1}=='!'||$tag{1}=='?'){$block_text.=$tag;}else{if(preg_match('{^</?'.$base_tag_name_re.'\b}',$tag)){if($tag{1}=='/')$depth--;else if($tag{strlen($tag)-2}!='/')$depth++;}if($md_attr&&preg_match($markdown_attr_re,$tag,$attr_m)&&preg_match('/^1|block|span$/',$attr_m[2].$attr_m[3])){$tag=preg_replace($markdown_attr_re,'',$tag);$this->mode=$attr_m[2].$attr_m[3];$span_mode=$this->mode=='span'||$this->mode!='block'&&preg_match('{^<(?:'.$this->contain_span_tags_re.')\b}',$tag);if(preg_match('/(?:^|\n)( *?)(?! ).*?$/',$block_text,$matches)){$strlen=$this->utf8_strlen;$indent=$strlen($matches[1],'UTF-8');}else{$indent=0;}$block_text.=$tag;$parsed.=$this->$hash_method($block_text);preg_match('/^<([\w:$]*)\b/',$tag,$matches);$tag_name_re=$matches[1];list($block_text,$text)=$this->_hashHTMLBlocks_inMarkdown($text,$indent,$tag_name_re,$span_mode);if($indent>0){$block_text=preg_replace("/^[ ]{1,$indent}/m","",$block_text);}if(!$span_mode)$parsed.="\n\n$block_text\n\n";else$parsed.="$block_text";$block_text="";}else$block_text.=$tag;}}while($depth>0);$parsed.=$this->$hash_method($block_text);return array($parsed,$text);}function hashClean($text){return$this->hashPart($text,'C');}function doHeaders($text){$text=preg_replace_callback('{
				(^.+?)								# $1: Header text
				(?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})?	# $2: Id attribute
				[ ]*\n(=+|-+)[ ]*\n+				# $3: Header footer
			}mx',array(&$this,'_doHeaders_callback_setext'),$text);$text=preg_replace_callback('{
				^(\#{1,6})	# $1 = string of #\'s
				[ ]*
				(.+?)		# $2 = Header text
				[ ]*
				\#*			# optional closing #\'s (not counted)
				(?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})? # id attribute
				[ ]*
				\n+
			}xm',array(&$this,'_doHeaders_callback_atx'),$text);return$text;}function _doHeaders_attr($attr){if(empty($attr))return"";return" id=\"$attr\"";}function _doHeaders_callback_setext($matches){if($matches[3]=='-'&&preg_match('{^- }',$matches[1]))return$matches[0];$level=$matches[3]{0}=='='?1:2;$attr=$this->_doHeaders_attr($id=&$matches[2]);$block="<h$level$attr>".$this->runSpanGamut($matches[1])."</h$level>";return"\n".$this->hashBlock($block)."\n\n";}function _doHeaders_callback_atx($matches){$level=strlen($matches[1]);$attr=$this->_doHeaders_attr($id=&$matches[3]);$block="<h$level$attr>".$this->runSpanGamut($matches[2])."</h$level>";return"\n".$this->hashBlock($block)."\n\n";}function doTables($text){$less_than_tab=$this->tab_width-1;$text=preg_replace_callback('
			{
				^							# Start of a line
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				[|]							# Optional leading pipe (present)
				(.+) \n						# $1: Header row (at least one pipe)
				
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				[|] ([ ]*[-:]+[-| :]*) \n	# $2: Header underline
				
				(							# $3: Cells
					(?>
						[ ]*				# Allowed whitespace.
						[|] .* \n			# Row content.
					)*
				)
				(?=\n|\Z)					# Stop at final double newline.
			}xm',array(&$this,'_doTable_leadingPipe_callback'),$text);$text=preg_replace_callback('
			{
				^							# Start of a line
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				(\S.*[|].*) \n				# $1: Header row (at least one pipe)
				
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				([-:]+[ ]*[|][-| :]*) \n	# $2: Header underline
				
				(							# $3: Cells
					(?>
						.* [|] .* \n		# Row content
					)*
				)
				(?=\n|\Z)					# Stop at final double newline.
			}xm',array(&$this,'_DoTable_callback'),$text);return$text;}function _doTable_leadingPipe_callback($matches){$head=$matches[1];$underline=$matches[2];$content=$matches[3];$content=preg_replace('/^ *[|]/m','',$content);return$this->_doTable_callback(array($matches[0],$head,$underline,$content));}function _doTable_callback($matches){$head=$matches[1];$underline=$matches[2];$content=$matches[3];$head=preg_replace('/[|] *$/m','',$head);$underline=preg_replace('/[|] *$/m','',$underline);$content=preg_replace('/[|] *$/m','',$content);$separators=preg_split('/ *[|] */',$underline);foreach($separators as$n=>$s){if(preg_match('/^ *-+: *$/',$s))$attr[$n]=' align="right"';else if(preg_match('/^ *:-+: *$/',$s))$attr[$n]=' align="center"';else if(preg_match('/^ *:-+ *$/',$s))$attr[$n]=' align="left"';else$attr[$n]='';}$head=$this->parseSpan($head);$headers=preg_split('/ *[|] */',$head);$col_count=count($headers);$text="<table>\n";$text.="<thead>\n";$text.="<tr>\n";foreach($headers as$n=>$header)$text.="  <th$attr[$n]>".$this->runSpanGamut(trim($header))."</th>\n";$text.="</tr>\n";$text.="</thead>\n";$rows=explode("\n",trim($content,"\n"));$text.="<tbody>\n";foreach($rows as$row){$row=$this->parseSpan($row);$row_cells=preg_split('/ *[|] */',$row,$col_count);$row_cells=array_pad($row_cells,$col_count,'');$text.="<tr>\n";foreach($row_cells as$n=>$cell)$text.="  <td$attr[$n]>".$this->runSpanGamut(trim($cell))."</td>\n";$text.="</tr>\n";}$text.="</tbody>\n";$text.="</table>";return$this->hashBlock($text)."\n";}function doDefLists($text){$less_than_tab=$this->tab_width-1;$whole_list_re='(?>
			(								# $1 = whole list
			  (								# $2
				[ ]{0,'.$less_than_tab.'}
				((?>.*\S.*\n)+)				# $3 = defined term
				\n?
				[ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
			  )
			  (?s:.+?)
			  (								# $4
				  \z
				|
				  \n{2,}
				  (?=\S)
				  (?!						# Negative lookahead for another term
					[ ]{0,'.$less_than_tab.'}
					(?: \S.*\n )+?			# defined term
					\n?
					[ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
				  )
				  (?!						# Negative lookahead for another definition
					[ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
				  )
			  )
			)
		)';$text=preg_replace_callback('{
				(?>\A\n?|(?<=\n\n))
				'.$whole_list_re.'
			}mx',array(&$this,'_doDefLists_callback'),$text);return$text;}function _doDefLists_callback($matches){$list=$matches[1];$result=trim($this->processDefListItems($list));$result="<dl>\n".$result."\n</dl>";return$this->hashBlock($result)."\n\n";}function processDefListItems($list_str){$less_than_tab=$this->tab_width-1;$list_str=preg_replace("/\n{2,}\\z/","\n",$list_str);$list_str=preg_replace_callback('{
			(?>\A\n?|\n\n+)					# leading line
			(								# definition terms = $1
				[ ]{0,'.$less_than_tab.'}	# leading whitespace
				(?![:][ ]|[ ])				# negative lookahead for a definition 
											#   mark (colon) or more whitespace.
				(?> \S.* \n)+?				# actual term (not whitespace).	
			)			
			(?=\n?[ ]{0,3}:[ ])				# lookahead for following line feed 
											#   with a definition mark.
			}xm',array(&$this,'_processDefListItems_callback_dt'),$list_str);$list_str=preg_replace_callback('{
			\n(\n+)?						# leading line = $1
			(								# marker space = $2
				[ ]{0,'.$less_than_tab.'}	# whitespace before colon
				[:][ ]+						# definition mark (colon)
			)
			((?s:.+?))						# definition text = $3
			(?= \n+ 						# stop at next definition mark,
				(?:							# next term or end of text
					[ ]{0,'.$less_than_tab.'} [:][ ]	|
					<dt> | \z
				)						
			)					
			}xm',array(&$this,'_processDefListItems_callback_dd'),$list_str);return$list_str;}function _processDefListItems_callback_dt($matches){$terms=explode("\n",trim($matches[1]));$text='';foreach($terms as$term){$term=$this->runSpanGamut(trim($term));$text.="\n<dt>".$term."</dt>";}return$text."\n";}function _processDefListItems_callback_dd($matches){$leading_line=$matches[1];$marker_space=$matches[2];$def=$matches[3];if($leading_line||preg_match('/\n{2,}/',$def)){$def=str_repeat(' ',strlen($marker_space)).$def;$def=$this->runBlockGamut($this->outdent($def."\n\n"));$def="\n".$def."\n";}else{$def=rtrim($def);$def=$this->runSpanGamut($this->outdent($def));}return"\n<dd>".$def."</dd>\n";}function doFencedCodeBlocks($text){$less_than_tab=$this->tab_width;$text=preg_replace_callback('{
				(?:\n|\A)
				# 1: Opening marker
				(
					~{3,} # Marker: three tilde or more.
				)
				[ ]* \n # Whitespace and newline following marker.
				
				# 2: Content
				(
					(?>
						(?!\1 [ ]* \n)	# Not a closing marker.
						.*\n+
					)+
				)
				
				# Closing marker.
				\1 [ ]* \n
			}xm',array(&$this,'_doFencedCodeBlocks_callback'),$text);return$text;}function _doFencedCodeBlocks_callback($matches){$codeblock=$matches[2];$codeblock=htmlspecialchars($codeblock,ENT_NOQUOTES);$codeblock=preg_replace_callback('/^\n+/',array(&$this,'_doFencedCodeBlocks_newlines'),$codeblock);$codeblock="<pre><code>$codeblock</code></pre>";return"\n\n".$this->hashBlock($codeblock)."\n\n";}function _doFencedCodeBlocks_newlines($matches){return str_repeat("<br$this->empty_element_suffix",strlen($matches[0]));}var$em_relist=array(''=>'(?:(?<!\*)\*(?!\*)|(?<![a-zA-Z0-9_])_(?!_))(?=\S|$)(?![.,:;]\s)','*'=>'(?<=\S|^)(?<!\*)\*(?!\*)','_'=>'(?<=\S|^)(?<!_)_(?![a-zA-Z0-9_])',);var$strong_relist=array(''=>'(?:(?<!\*)\*\*(?!\*)|(?<![a-zA-Z0-9_])__(?!_))(?=\S|$)(?![.,:;]\s)','**'=>'(?<=\S|^)(?<!\*)\*\*(?!\*)','__'=>'(?<=\S|^)(?<!_)__(?![a-zA-Z0-9_])',);var$em_strong_relist=array(''=>'(?:(?<!\*)\*\*\*(?!\*)|(?<![a-zA-Z0-9_])___(?!_))(?=\S|$)(?![.,:;]\s)','***'=>'(?<=\S|^)(?<!\*)\*\*\*(?!\*)','___'=>'(?<=\S|^)(?<!_)___(?![a-zA-Z0-9_])',);function formParagraphs($text){$text=preg_replace('/\A\n+|\n+\z/','',$text);$grafs=preg_split('/\n{2,}/',$text,-1,PREG_SPLIT_NO_EMPTY);foreach($grafs as$key=>$value){$value=trim($this->runSpanGamut($value));$is_p=!preg_match('/^B\x1A[0-9]+B|^C\x1A[0-9]+C$/',$value);if($is_p){$value="<p>$value</p>";}$grafs[$key]=$value;}$text=implode("\n\n",$grafs);$text=$this->unhash($text);return$text;}function stripFootnotes($text){$less_than_tab=$this->tab_width-1;$text=preg_replace_callback('{
			^[ ]{0,'.$less_than_tab.'}\[\^(.+?)\][ ]?:	# note_id = $1
			  [ ]*
			  \n?					# maybe *one* newline
			(						# text = $2 (no blank lines allowed)
				(?:					
					.+				# actual text
				|
					\n				# newlines but 
					(?!\[\^.+?\]:\s)# negative lookahead for footnote marker.
					(?!\n+[ ]{0,3}\S)# ensure line is not blank and followed 
									# by non-indented content
				)*
			)		
			}xm',array(&$this,'_stripFootnotes_callback'),$text);return$text;}function _stripFootnotes_callback($matches){$note_id=$this->fn_id_prefix.$matches[1];$this->footnotes[$note_id]=$this->outdent($matches[2]);return'';}function doFootnotes($text){if(!$this->in_anchor){$text=preg_replace('{\[\^(.+?)\]}',"F\x1Afn:\\1\x1A:",$text);}return$text;}function appendFootnotes($text){$text=preg_replace_callback('{F\x1Afn:(.*?)\x1A:}',array(&$this,'_appendFootnotes_callback'),$text);if(!empty($this->footnotes_ordered)){$text.="\n\n";$text.="<div class=\"footnotes\">\n";$text.="<hr".$this->empty_element_suffix."\n";$text.="<ol>\n\n";$attr=" rev=\"footnote\"";if($this->fn_backlink_class!=""){$class=$this->fn_backlink_class;$class=$this->encodeAttribute($class);$attr.=" class=\"$class\"";}if($this->fn_backlink_title!=""){$title=$this->fn_backlink_title;$title=$this->encodeAttribute($title);$attr.=" title=\"$title\"";}$num=0;while(!empty($this->footnotes_ordered)){$footnote=reset($this->footnotes_ordered);$note_id=key($this->footnotes_ordered);unset($this->footnotes_ordered[$note_id]);$footnote.="\n";$footnote=$this->runBlockGamut("$footnote\n");$footnote=preg_replace_callback('{F\x1Afn:(.*?)\x1A:}',array(&$this,'_appendFootnotes_callback'),$footnote);$attr=str_replace("%%",++$num,$attr);$note_id=$this->encodeAttribute($note_id);$backlink="<a href=\"#fnref:$note_id\"$attr>&#8617;</a>";if(preg_match('{</p>$}',$footnote)){$footnote=substr($footnote,0,-4)."&#160;$backlink</p>";}else{$footnote.="\n\n<p>$backlink</p>";}$text.="<li id=\"fn:$note_id\">\n";$text.=$footnote."\n";$text.="</li>\n\n";}$text.="</ol>\n";$text.="</div>";}return$text;}function _appendFootnotes_callback($matches){$node_id=$this->fn_id_prefix.$matches[1];if(isset($this->footnotes[$node_id])){$this->footnotes_ordered[$node_id]=$this->footnotes[$node_id];unset($this->footnotes[$node_id]);$num=$this->footnote_counter++;$attr=" rel=\"footnote\"";if($this->fn_link_class!=""){$class=$this->fn_link_class;$class=$this->encodeAttribute($class);$attr.=" class=\"$class\"";}if($this->fn_link_title!=""){$title=$this->fn_link_title;$title=$this->encodeAttribute($title);$attr.=" title=\"$title\"";}$attr=str_replace("%%",$num,$attr);$node_id=$this->encodeAttribute($node_id);return"<sup id=\"fnref:$node_id\">"."<a href=\"#fn:$node_id\"$attr>$num</a>"."</sup>";}return"[^".$matches[1]."]";}function stripAbbreviations($text){$less_than_tab=$this->tab_width-1;$text=preg_replace_callback('{
			^[ ]{0,'.$less_than_tab.'}\*\[(.+?)\][ ]?:	# abbr_id = $1
			(.*)					# text = $2 (no blank lines allowed)	
			}xm',array(&$this,'_stripAbbreviations_callback'),$text);return$text;}function _stripAbbreviations_callback($matches){$abbr_word=$matches[1];$abbr_desc=$matches[2];if($this->abbr_word_re)$this->abbr_word_re.='|';$this->abbr_word_re.=preg_quote($abbr_word);$this->abbr_desciptions[$abbr_word]=trim($abbr_desc);return'';}function doAbbreviations($text){if($this->abbr_word_re){$text=preg_replace_callback('{'.'(?<![\w\x1A])'.'(?:'.$this->abbr_word_re.')'.'(?![\w\x1A])'.'}',array(&$this,'_doAbbreviations_callback'),$text);}return$text;}function _doAbbreviations_callback($matches){$abbr=$matches[0];if(isset($this->abbr_desciptions[$abbr])){$desc=$this->abbr_desciptions[$abbr];if(empty($desc)){return$this->hashPart("<abbr>$abbr</abbr>");}else{$desc=$this->encodeAttribute($desc);return$this->hashPart("<abbr title=\"$desc\">$abbr</abbr>");}}else{return$matches[0];}}}class Blog{function page($page){$tee=NR::layout();$data=$this->parse_metadata($page);switch(@$data['meta']['type']){case"log":case"page":$tee->content=Markdown($data['body']);break;case"html":$tee->content=$data['body'];break;case"generated-html":$this->update();$data=$this->parse_metadata($page);$tee->content=$data['body'];break;default:$tee->content=Markdown('#Page not found#');break;}echo$tee->render();}function parse_metadata($file){$contents=@file_get_contents(__DIR__.'/content/'.$file.".bmd");$tmp=@explode("\n\n",$contents,2);return array('meta'=>spyc_load($tmp[0]),'body'=>@$tmp[1]);}function update(){if(rfilemtime("./content/")>filemtime(__FILE__)){$articles=array();$files=rglob("*.bmd","./content/");foreach($files as$file){$url=str_replace(array('./content/','.bmd'),'',$file);$data=$this->parse_metadata($url);if($data['meta']['type']=="log"){$articles[]=array_merge($data['meta'],array('body'=>$data['body'],'url'=>'/'.$url));}}$tee=NR::layout(__DIR__.'/templates/listing.phtml');$tee->articles=$articles;$output="type:generated-html\n\n";$output.=$tee->render();file_put_contents("./content/index.bmd",$output);@touch(__FILE__);}}function index(){return$this->page('index');}}function rfilemtime($file){$max=filemtime($file);if(is_dir($file)){$dirs=glob($file."*",2);foreach($dirs as$k=>$dir){if($dir[strlen($dir)-1]=="/"){if(rfilemtime($dir)>$max){$max=rfilemtime($dir);}}else{if(filemtime($dir)>$max){$max=filemtime($dir);}}}}return$max;}function rglob($pattern,$dir){$dirs=glob($dir."*",2);foreach($dirs as$k=>$dir){if($dir[strlen($dir)-1]=="/"){$dirs=array_merge($dirs,rglob($pattern,$dir));unset($dirs[$k]);}}return$dirs;}NR::views(__DIR__."/templates/");NR::route("/")->call('Blog::index')->layout("page.phtml")->on();NR::route("/<page>[/]")->call("Blog::page")->layout("page.phtml")->on();NR::run();?>
