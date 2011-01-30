<?php
class PHPJoiner{
	var $file;
	var $dir;
	function PHPJoiner($file)
	{
		$this->file = realpath($file);
		$this->dir = dirname($this->file);
	}

	function join()
	{
		$source = file_get_contents($this->file);
		$source = preg_replace_callback('/include\s(.*);/',
				array($this,'real_load'),
				$source);
		return ($source);
	}

	function real_load($matches)
	{
		$file = str_replace(array('__DIR__','"'),'',$matches[1]);
		$source = file_get_contents($this->dir.DIRECTORY_SEPARATOR.$file);
		$source = preg_replace('/<\?(php)?/', '', $source, 1);
		preg_match_all('/\?>/',$source,$m,PREG_OFFSET_CAPTURE);
		$pos = end($m[0]);
		return (substr($source, 0, $pos[1]));
	}
}


// Dgx's PHP shrinker

// PHP 4 & 5 compatibility
if (!defined('T_DOC_COMMENT'))
  define ('T_DOC_COMMENT', -1);

if (!defined('T_ML_COMMENT'))
  define ('T_ML_COMMENT', -1);

function shrink($input){
$space = $output = '';
$set = '!"#$&\'()*+,-./:;<=>?@[\]^`{|}';
$set = array_flip(preg_split('//',$set));

foreach (token_get_all($input) as $token)  {
  if (!is_array($token))
    $token = array(0, $token);

  switch ($token[0]) {
    case T_COMMENT:
    case T_ML_COMMENT:
    case T_DOC_COMMENT:
    case T_WHITESPACE:
      $space = ' ';
      break;

    default:
      if (isset($set[substr($output, -1)]) ||
          isset($set[$token[1]{0}])) $space = '';
      $output .= $space . $token[1];
      $space = '';
  }
}
return $output;
}

function format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}

$join = new PHPJoiner('src/index.php');
$r = realpath('./index.php');
if(file_put_contents($r,shrink($join->join()))){
	echo "File '$r' written ".format_bytes(filesize($r));
}
?>
