<?
if(!defined('__DIR__'))	define('__DIR__',dirname(__FILE__));


class uDebug{
	private static $printed = false;

	public static function dump($var, $ret = false, $name = NULL,$array = false)
	{
		if(!self::$printed){
			self::bar();
		}

		$out = "";
		
		if(!$array){
			$out = "<table>";
		}

		$out .= "<tr><td class='nm' valign='top'>";
		if(!$name){
			$trace = debug_backtrace();
       			$vLine = file( $trace[0]["file"]);
	    		$fLine = $vLine[ $trace[0]['line'] - 1 ];
	        	preg_match( '/(\w+\:\:)?\$(\w+)(\->\w+)?/', $fLine, $match );
			$out .= $match[0];
		}else{
			$out .= $name;
		}

		$out .= "</td><td>";
		
		if(is_bool($var)){
			$out .= "(<span class='kw'>bool</span>) ";
			$out .= ($var ? 'true' : 'false' );
		}else if($var === NULL){
			$out .= "NULL";
		}else if(is_int($var)){
			$out .= "(<span class='kw'>int</span>) ";
			$out .= $var;
		}else if(is_float($var)){
			$out .= "(<span class='kw'>float</span>) ";
			$out .= $var;
			// float value always with dot zero
			if(strpos((string)$var,".") === false){$out .= ".0";};
		}else if(is_string($var)){
			$out .= "(<span class='kw'>string</span>)(";
			$out .= strlen($var);
			$out .= ") <span class='qt'>\"</span>";
			$out .= str_replace(array("\t","\n"),
					    array('<span class=\'sc\'>\t</span>','<span class=\'sc\'>\n</span>'),
					    htmlspecialchars($var));
			$out .= "<span class='qt'>\"</span>";
		}else if(is_array($var)){
			$out .= "(<span class='kw'>array</span>)(";
			$out .= count($var);
			$out .= ")<br />{<br /><div class='ar'><table>";
			foreach($var as $key=>$val){
				if($key === 0){ $key = "0";}
				$out .= self::dump($val,true,(string)$key,true);
				//TODO: 0 key;
			}
			$out .="</table></div>}";
		}

		$out .= "</td></tr>";
		
		if(!$array){
			$out .= "</table>";
		}
		

		if($ret){
			return $out;
		}else{
			// echo it as base64 encoded and pass to javascript 
			// function which will insert it into #dbg div
			echo "<script>dbg_append('";
			echo base64_encode($out);
			echo "','dbg');</script>";
			return $var;
		}
	}
	public static function bar()
	{
		// print debug bar just once
		self::$printed = true;
		// printing CSS styles
		?>
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
		// now javascript functions needed
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
		// and finally HTML
		?>
<div id="dbg-wrapper">
	<div id="dbg">
		<a href="#" onclick="font_minus(this);" style="color:white;position:absolute;bottom:0px;right:2px;text-decoration:none;font-weight:bold;">-</a>
		<a href="#" onclick="font_plus(this);" style="color:white;position:absolute;bottom:0px;right:12px;text-decoration:none;font-weight:bold;">+</a>

	</div>
	<a class="toggle-link db-toggle" href="#" onclick="dbg_toggle(this);return false;">&#9650;</a>	
</div>
		<?php
	}
}

class uD extends uDebug{}


if(!defined('__DIR__'))   define('__DIR__',dirname(__FILE__));

class Tee{
	
	private $_REGEXES = 
	array( /* '/\{\%\sextends\s"(.*)"\s\%\}/e' => 'eval(return $this->load_file("\\1"));', */
		'/\{\%\sif\s([_a-zA-Z][_a-zA-Z0-9]*)\.([_a-zA-Z][_a-zA-Z0-9]*)\s\%\}/' => "<?php if(@$\\1['\\2']): ?>",
		'/\{\%\sif\s(.*)\s\%\}/' => "<?php if(@$\\1): ?>",
		'/\{\%\selse\s\%\}/' => "<?php else: ?>",
		'/\{\%\sendif\s\%\}/' => "<?php endif; ?>",
		'/\{\%\sfor\s([_a-zA-Z][_a-zA-Z0-9]*)\sin\s([_a-zA-Z][_a-zA-Z0-9]*)\s\%\}/' => "<?php foreach(@$\\2 as $\\1): ?>",
		'/\{\%\sendfor\s\%\}/' => "<?php endforeach; ?>",
		'/\{\{\s*([_a-zA-Z][_a-zA-Z0-9]*)\s*\}\}/' => "<?php echo @$\\1; ?>",
		'/\{\{\s*([_a-zA-Z][_a-zA-Z0-9]*)\.([_a-zA-Z][_a-zA-Z0-9]*)\s*\}\}/' => "<?php echo @$\\1['\\2']; ?>");
	

	private static $_tags = array();
	
	private $_source = '';
	/*private*/var $_filename = '';
	private $_cache_dir = '';
	
	private $_blocks = array();

	const TAG_START = '{%';
	const TAG_END   = '%}';
	
	const TAG_REGEX = '\s([_a-zA-Z][_a-zA-Z0-9]*)\s(.*)\s';
	
	public function __construct($file = '')
	{
		$this->_cache_dir = __DIR__."/cache/";
		if(!is_dir($this->_cache_dir)){
			if(!mkdir($this->_cache_dir)){
				throw new Exception("Cannot create dir ".__DIR__."/cache/");
			}
			if(!is_writable($this->_cache_dir)){
				@chmod(0644,__DIR__."/cache/");
			}

		}
		$this->file($file);
	
	}

	public function file($filename)
	{
		$this->_filename = realpath($filename);
		return ($this->_source = @file_get_contents($this->_filename)) == '' ? false : true;
	}

	public function render()
	{
		
		if($this->_filename === ''){
			throw new Exception('No template filename assgined');
		}

		if(!$this->from_cache($this->_filename)){
			$this->replace();
			$this->write_cache($this->_filename);
		}

		$vars = get_object_vars($this);
		ob_start();
		if(count($vars)){
			foreach($vars as $key => $val){
				// because O == false we had to add one char before key name
				if(strpos('-'.$key,'_') != 1){
					$$key = $val;
				}
			}
		}
		require($this->cached_file($this->_filename));
		$output = ob_get_contents();
		ob_end_clean();

		
		return $output;
	}

	private function tag_replace_callback($matches)
	{
		if(array_key_exists($matches[1],self::$_tags)){
				return "<?php echo @".
					self::$_tags[$matches[1]].
					"('".
					 addslashes($matches[2])
					."'); ?>";
		}else{
			return "";
		}
	}

	public function replace($source = NULL,$ret = false,$in_extend = false)
	{
		if($source == NULL){
			$source = $this->_source;
		}
	
		// simple replace regexes
		foreach($this->_REGEXES as $regexin => $out){
			$source = preg_replace(
					$regexin,
					$out,
					$source);
		}
		
		// extends tag
		if(preg_match('/\{\%\sextends\s"(.*)"\s\%\}/',$source,$matches)){
			$source = str_replace(
					$matches[0],
					$this->load_file($matches[1],true),
					$source);
		}
	
		// include tag
		if(preg_match_all('/\{\%\sinclude\s"(.*)"\s\%\}/',
					$source,
					$matches)){
			$source = str_replace(
					$matches[0][0],
					$this->load_file($matches[1][0]),
					$source);
		}
		

		if($i = preg_match_all("/\{\%\sblock\s(.*)\s\%\}(.*)\{\%\sendblock\s\%\}/sU",
					$source,
					$matches)){
	
			for(--$i;$i>=0;$i--){
				// just know about them
				if(isset($this->_blocks[$matches[1][$i]])){
					$source = str_replace($matches[0][$i],'',$source);
					$source = str_replace('[['.$matches[1][$i].']]',
						$matches[2][$i],
						$source);

				}else{
					$this->_blocks[$matches[1][$i]] = $matches[2][$i];	
					$source = str_replace($matches[0][$i],'[['.$matches[1][$i].']]',$source);
				}
			}
		
		}
		
		if(!$in_extend){
			$source = preg_replace('/\[\[(.*)\]\]/e','@$this->_blocks["\\1"]',$source);
		}

		// other tags
		$tag_regex = '/'.preg_quote(self::TAG_START).
			self::TAG_REGEX.
			preg_quote(self::TAG_END).'/';

		$source = preg_replace_callback($tag_regex,
				array($this,'tag_replace_callback'),
				$source);	

		if($ret){
			return $source;
		}else{
			$this->_source = $source;
		}

		return true;
	}

	public static function add_tag($name, $function)
	{
		if(!preg_match('/([_a-zA-Z][_a-zA-Z0-9]*)/',$name)){
			throw new Exception("Invalid tag name '$name' ");
		}

		if(is_callable($function,true,$real_function)){
			self::$_tags[$name] = $function /*$real_function*/;
			return true;
		}else{
			throw new Exception("Uncallable function '$function' ");
		}
	}

	public function load_file($filename, $in_extend = false)
	{
		$dir = dirname($this->_filename);
		$source = @file_get_contents($dir."/".$filename);
		return $this->replace($source,true,$in_extend);
	}

	public function clean_cache()
	{
		$files = scandir($this->_cache_dir); 
		foreach ($files as $file) { 
			if ($file != "." && $file != "..") { 
				unlink($this->_cache_dir."/".$file);  
			} 
		}
	}


	private function cached_file($file)
	{
		return $this->_cache_dir.str_replace("/","%",$file);
	}

	private function from_cache($file)
	{
		$cached_file = $this->cached_file($file); 		
		// TODO: included file edited
		return (file_exists($cached_file) && 
				(@filemtime($this->_filename) <
				 @filemtime($this->cached_file($this->_filename))
				)
		       );
	}
	private function write_cache($file)
	{
		$cached_file = $this->cached_file($file);
		return @file_put_contents($cached_file,$this->_source);	
	}
}


class Router{
	private $name;
	private $route;
	private $function;
	public static $_vars = array();
	private $method = 'GET';
	private $template = '';

	const ROUTE_REGEX = '/<(?<name>.*?)\s*(?<regex>:?\(.*?\))?>/';

	public function __construct($pattern)
	{
		$route = self::pattern_quote($pattern);
		
		$route = preg_replace_callback(
				self::ROUTE_REGEX,
				'self::pattern2regex',
				$route);

		$route = '/^'.$route.'$/';

		$this->route = $route;
		
		return $this;
	}

	public function call($function)
	{
		// real name
		$this->name = $function;

		if(is_callable($function,false)){
			if(is_string($function)){
				if(strpos($function,'::') !== false){
					$class = explode('::',$function);
					$method = $class[1];
					$class = $class[0];
					
					if(class_exists($class)){
						$this->class = new $class();
						if(method_exists($this->class,$method)){
							$this->function = array($this->class,
										$method);
							return $this;
							
						}
					}
				}else{
					$this->function = $function;
					return $this;
				}
			}else{
				$this->function = $function;
				return $this;
			}
		}
		
		throw new Exception("Uncallable function '$function' passed!");
	}

	public function layout($file)
	{
		$file = NR::$_views.DIRECTORY_SEPARATOR.$file;
		if(file_exists($file)){
			$this->template = $file;
		}else{
			throw new Exception("Non-existing file '$file' passed!");
		}
		
		return $this;
	}

	public function on($method = NULL)
	{
		if($method){
			$this->method = $method;
		}

		NR::$_routes[] /* $r */ = array($this->route,
				$this->function,
				self::$_vars,
				$this->method,
				$this->name,
				$this->template);
		
		self::$_vars = array();
	}

	public static function pattern_quote($pattern)
	{
		$output = '';
		$len = strlen($pattern);
		$in_regex = False;

		for($i = 0;$i < $len;$i++){
			if($pattern[$i] == '('){
				$in_regex = True;
			}else if($pattern[$i] == ')'){
				$in_regex = False;
			}
		
			if($pattern[$i] == '/'){
				$output .= '\/';
			}else if($pattern[$i] == '?'){
				if(!$in_regex){
					$output .= '&';
				}else{
					$output .= '?';
				}
			// optional parts of regex
			}else if($pattern[$i] == '['){
				$output .= "(";
			}else if($pattern[$i] == ']'){
				$output .= ")?";
			}else{
				$output .= $pattern[$i];
			}
		}
		
		return $output;

	}

	public static function pattern2regex($matches)
	{
		self::$_vars[] = $matches['name'];
		
		if(isset($matches['def'])){
			self::$_defaults[$matches['name']] = $matches['def'];
		}

		if(isset($matches['regex'])){
			return '(?<'.$matches['name'].'>'.
				$matches['regex'].')';
		}

		return '(?<'.$matches['name'].'>.*?)';
		
	}


}




class NR{
	static $_routes = array();
	static $_views = "";
	static $_route = NULL;

	public static function controllers($dir)
	{
		if(is_dir($dir)){
			$dir .= "*.php";
		}

		foreach(glob($dir) as $file){
			include_once($file);
		}
	}
	
	public static function views($dir)
	{
		if(file_exists($dir)){
			self::$_views = $dir;	
		}	
	}

	public static function layout($file = NULL)
	{
		$tee = new Tee();
		$route_file = self::$_routes[self::$_route][5];
		if($file === NULL){
			$tee->file($route_file);
		}else{
			$tee->file($file);
		}

		return $tee;
	}

	public static function redirect($uri)
	{
		// redirecting with 302 HTTP response code
		header("Location: ".$uri, true, 302);
		exit();
	}
	
	public static function route($pattern)
	{
		return new Router($pattern);
	}
	
	public static function routes()
	{
		return self::$_routes;
	}
	
	public static function run()
	{
		// QUERY_STRING is empty with rewriting enabled 
		if(empty($_SERVER["QUERY_STRING"])){
			$_SERVER["QUERY_STRING"] = "/";
		}
		foreach(self::$_routes as $key=>$route){
			if(preg_match($route[0],$_SERVER["QUERY_STRING"],$matches) && 
				$_SERVER['REQUEST_METHOD'] == $route[3] ){
				
				self::$_route = $key;
				
				$args = array();
				foreach($route[2] as $arg){
					if(is_numeric(@$matches[$arg])){
						$args[] = (int)$matches[$arg];
					}else if(empty($matches[$arg])){
						continue;
					}else{
						$args[] = addslashes($matches[$arg]);
					}
				}
				
				return call_user_func_array($route[1],$args);
			}
		}
		return -1;
	}	
}

?>
