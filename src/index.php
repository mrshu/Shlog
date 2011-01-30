<?php
include __DIR__."/lib/nr.min.php";
include __DIR__."/lib/spyc.php";
include __DIR__."/lib/markdown.php";

class Blog{

	function page($page)
	{
		$tee = NR::layout();
		$data = $this->parse_metadata($page);
		switch(@$data['meta']['type']){
			case "log":
			case "page":
				$tee->content = Markdown($data['body']);
				break;
			case "html":
				$tee->content = $data['body'];
				break;
			case "generated-html":
				$this->update();
				$data = $this->parse_metadata($page);
				$tee->content = $data['body'];
				break;
			default:
				$tee->content = Markdown('#Page not found#');
				break;
		}

		echo $tee->render();
	}

	function parse_metadata($file)
	{
		$contents = @file_get_contents(__DIR__.'/content/'.$file.".bmd");
		$tmp = @explode("\n\n", $contents, 2);
		return array('meta' => spyc_load($tmp[0]),
			     'body' => @$tmp[1]);
	}
	
	function update()
	{
		if(rfilemtime("./content/") > filemtime(__FILE__)){
			$articles = array();
			$files = rglob("*.bmd", "./content/");
			foreach($files as $file){
				$url = str_replace(array('./content/','.bmd'),'',$file);
				$data = $this->parse_metadata($url);
				if($data['meta']['type'] == "log"){
					$articles[] = array_merge($data['meta'],
						array('body' => $data['body'],
						      'url' => '/'.$url));
				}
			}
			$tee = NR::layout(__DIR__.'/templates/listing.phtml');
			$tee->articles = $articles;
			$output = "type:generated-html\n\n";
			$output .= $tee->render();
			file_put_contents("./content/index.bmd", $output);
			@touch(__FILE__);
		}
	}

	function index()
	{
		return $this->page('index');
	}

}

function rfilemtime($file)
{
	$max = filemtime($file);
	if(is_dir($file)){
		$dirs = glob($file."*",2);
		foreach($dirs as $k=>$dir){
			if($dir[strlen($dir)-1] == "/"){
				if(rfilemtime($dir) > $max){
					$max = rfilemtime($dir);	
				}
			}else{
				if(filemtime($dir) > $max){
					$max = filemtime($dir);	
				}
			}
		}	
	}

	return $max;

}

function rglob($pattern, $dir)
{
	$dirs = glob($dir."*",2);
	foreach($dirs as $k=>$dir){
		if($dir[strlen($dir)-1] == "/"){
			$dirs = array_merge($dirs, rglob($pattern, $dir));
			unset($dirs[$k]);
		}
	}
	return $dirs;
}


NR::views(__DIR__."/templates/");
NR::route("/")->call('Blog::index')->layout("page.phtml")->on();
NR::route("/<page>[/]")->call("Blog::page")->layout("page.phtml")->on();

NR::run();

?>
