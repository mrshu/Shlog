
Shlog
=====

Small, simple and sh(ort b)log engine implemented in PHP.

intro
-----

Shlog is a minimalistic one-file blog engine for those who don't need interface
for writing. It is inspired by [timeless](http://timeless.judofyr.net/) and
cloudhead's [toto](http://github.com/cloudhead/toto). They are both written in
Ruby so I have decided to create something simmilar in PHP.


contents
--------
- Articles are stored as _.bmd_ files with embeded metadata in yaml format (see
metadata section)
- Contents of articles are converted from Markdown to HTML by [PHP
  Markdown](http://michelf.com/projects/php-markdown/)
- Templating is done by __Tee__, templates are stored in _templates/_ folder
- It is all build on top of __nanoFramework__ 


building a blog
---------------

All you have to do to set up your blog is just to upload 
	
	index.php
	templates/
	contents/
	.htaccess

on your FTP server.

After that, you can create new _.bmd_ files in _content/_ directory to post new
articles.

metadata
--------

Metadata are informations about file readable for Shlog engine. They are in yaml
format.

Example of a file with metadata:


	title: Example article
	type: log
	time: 30th January 2011
	desc: |
	  Description of example article.


	Example article
	===============

	Where was there was an example article. And so it was.


The most important piece of information here is `type`. There are these types of
`type` 

1. **log** - An article. There should be also `title`, `time` and `desc`
mentioned in metadata section. Informations from this file will be used for
generating article index.
2. **page** - A page. Contents of this page will be processed by Markdown
engine.
3. **html** - A bare html page. Contents of this page will not be processed
though Markdown engine.
4. **generated-html** - A file generated by Shlog engine.



(c) 2010 - 2011 mr.Shu. Shlog is licenced under the BSD Open Source Licence. See
LICENSE file for more informations.







