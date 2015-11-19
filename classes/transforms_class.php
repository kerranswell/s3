<?php
class transforms{

    private $quotesCounter = 1;

	function __construct(){
		global $dsp;
		$this->dsp = $dsp;
	}
	
	function __destruct(){;}
	
    function __set($name, $value){$this->$name = $value;}

    function __get($name){return $this->$name;}

	//xslt transform
    function transform($xslfile, $xml, $no_dbg = false) {
		if (Param('_DBG') && !$no_dbg) {
		      //print_r(debug_backtrace());
		      //setcookie('111', '222');
              //die();
			header("Content-type: text/xml");
			print trim($xml);
            die();
		}

		//$phpver = explode('.', PHP_VERSION);

        //if ($phpver[0] == 5) {

            if (extension_loaded('xslcache')) {
                $xslt = new xsltCache;
                $xslt->importStyleSheet($xslfile);
            } else {
                $xslt = new xsltProcessor;
                $xsltDoc = DomDocument::load($xslfile);
                $xslt->importStyleSheet($xsltDoc);
            }

            $doc = new DOMDocument();
            $load_succesfull = $doc->loadXML($xml);

            if ( ( !$load_succesfull || @$_GET['_BAD_XML_TEST'] ) && !Param('_DBG') )
            {
                //error
				//Redirect( '/error.php?error=xml&url='.$_SERVER['REQUEST_URI'] );
				if( SITE == 'http://dev.starhit.ru' ) die( file_get_contents( 'http://www.starhit.ru/error.php?error=xml' ) );
				die( file_get_contents( SITE.'/error.php?error=xml' ) );
            }
            else
            {
                $galleries = $doc->getElementsByTagName('gallery');
                foreach ( $galleries AS $gallery )
                {
                    $images  = $gallery->getElementsByTagName('image');

                    foreach ( $images AS $image )
                    {
                        foreach ( $this->dsp->ae->size AS $size => $params )
                        {
                            $img = $image->getElementsByTagName($size)->item(0);
                            $width = $img->getAttribute('width');
                            if ( !$width )
                            {
                                $img_sizes = $this->dsp->eis->GetSizeByURL($img->nodeValue);
                                $img->setAttribute('width',$img_sizes[0]);
                                $img->setAttribute('height',$img_sizes[1]);
                            }

                        }
                    }
                }

                $result = $xslt->transformToXML($doc);
            }

        //}
			if(@$_GET['XSLT_INFO']){
			echo
			'$xslt->hasExsltSupport(): '.$xslt->hasExsltSupport().'
			$xslfile: '.$xslfile.'
			extension_loaded("xslcache"): '.extension_loaded("xslcache").'
			XSL_CLONE_AUTO: '.XSL_CLONE_AUTO.'
			XSL_CLONE_NEVER: '.XSL_CLONE_NEVER.'
			XSL_CLONE_ALWAYS: '.XSL_CLONE_ALWAYS.'
			LIBXSLT_VERSION: '.LIBXSLT_VERSION.'
			LIBXSLT_DOTTED_VERSION: '.LIBXSLT_DOTTED_VERSION.'
			LIBEXSLT_VERSION: '.LIBEXSLT_VERSION.'
			LIBEXSLT_DOTTED_VERSION: '.LIBEXSLT_DOTTED_VERSION.'
			$xslt: '.print_r($xslt,1).'
			';
			var_dump(
			$doc->saveXML(),
			$xsltDoc->saveXML(),
			$xslt->transformToXML($doc)/*,
			$xslt->transformToDoc($doc),
			$xslt->transformToUri($doc)*/
			)
			;die;}

		return $result;
    } // Transform()

	//array2xml
    function arr2xml($arr, $xml_tag_exclusion=array(), $cdata_wrap = false) {
        $ret = '';
        $is_tag = 0;
        if (!count($arr))
            return;
        foreach ($arr as $tag => $val) {
            $is_tag = 0;
            if (array_key_exists($tag, $xml_tag_exclusion)) {
                $param = $xml_tag_exclusion[$tag];
                $call_func = $param[0];
                array_shift($param);
                array_unshift($param, $val);
                $val = call_user_func_array($call_func, $param);
                $is_tag = 1;
            } elseif (is_array($val))
                $val = $this->arr2xml($val, $xml_tag_exclusion, $cdata_wrap);

            if ($cdata_wrap && !empty($val) && !is_numeric($val)) $val = '<![CDATA[' . $val . ']]>';
            $ret .= ($tag != 'data' and !$is_tag and !is_int($tag)) ? "<{$tag}>{$val}</{$tag}>" : $val;
        }
        return $ret;
    }

    public function replaceEntity(&$string)
    {
        $search  = array('&infin;','&equiv;','&ne;','&asymp;','&le;','&ge;','&ldquo;','&bdquo;','&laquo;','&raquo;','&lt;', '&gt;', '&quot;','&apos;','&amp;', '&mdash;', '&ldquo;', '&reg;', '&copy;', '&trade;', '&sect;', '&euro;', '&yen;', '&pound;', '&nbsp;');
        $replace = array('&#8734;','&#8801;','&#8800;','&#8776;','&#8804;','&#8805;','&#8220;','&#8222;','&#171;', '&#187;', '&#60;','&#62;','&#34;', '&#39;', '&#38;', '&#8212;', '&#8220;', '&#174;', '&#169;', '&#8482;', '&#167;', '&#8364;', '&#165;', '&#163;', '&#160;');
        $string  = str_replace( $search, $replace, $string );

        return $string;
    }

    public function replaceEntityBack(&$string)
    {
        $replace = array('∞','≡','≠','≈','≤','≥','“','„','«','»','&lt;', '&gt;', '"','\'','&amp;', '—', '®', '©', '™;', '§', '€', '¥', '£');
        $search  = array('&#8734;','&#8801;','&#8800;','&#8776;','&#8804;','&#8805;','&#8220;','&#8222;','&#171;', '&#187;', '&#60;','&#62;','&#34;', '&#39;', '&#38;', '&#8212;', '&#174;', '&#169;', '&#8482;', '&#167;', '&#8364;', '&#165;', '&#163;');
        $string  = str_replace( $search, $replace, $string );
    }

    public function escape($s)
    {
        return str_replace(
            array("&",     "<",    ">",    '"',      "'"),
            array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"),
            $s
        );
    }

    public function replaceSimbols(&$string)
    {
        $search  = array('©','®','™','€','„','“','«','»','≥','≤','≈','≠','≡','§','∞','&nbsp;', '$', '—');
        $replace = array('&copy;','&reg;','&trade;','&euro;','&bdquo;','&ldquo;','&laquo;','&raquo;','&ge;','&le;','&asymp;','&ne;','&equiv;','&sect;','&infin;',' ', '', '&mdash;');
        $string  = str_replace( $search, $replace, $string );
    }

    public function replaceEntity2Simbols(&$string)
    {
        $replace = array('©','®','™','€','„','“','«','»','≥','≤','≈','≠','≡','§','∞',' ', '$', '—');
        $search  = array('&copy;','&reg;','&trade;','&euro;','&bdquo;','&ldquo;','&laquo;','&raquo;','&ge;','&le;','&asymp;','&ne;','&equiv;','&sect;','&infin;','&nbsp;', '', '&mdash;');
        $string  = str_replace( $search, $replace, $string );
        $this->replaceAMP( $string );
    }

    public function replaceForYandex( $string )
    {
        $search  = array('<','>','\'','"');
        $replace = array('&lt;','&gt;','&apos;','&quot;');
        $string  = str_replace( $search, $replace, $string );
        $this->replaceAMP( $string );
        //hotfix
        $string = str_replace( '& ', '&amp; ', $string);
        return $string;
    }

    public function replaceAMP( &$string )
    {
        mb_internal_encoding('UTF-8');
        $i = 0;
        $offset = 0;
        $pos = mb_strpos( $string, '&', $offset );
        $new_string = '';

        while ( $pos !== FALSE /*&& $i < 20*/ )
        {
            $new_string .= mb_substr( $string, $offset, $pos - $offset );
            $entity = mb_substr( $string, $pos + 1, 6 );

            if ( !preg_match( '/^[a-z]{2,5};/', $entity ) )
            {
                $new_string .= '&amp;';
            }
            else
            {
                $new_string .= '&';
            }

            $offset = $pos + 1;

            $pos = mb_strpos( $string, '&', $offset );
            $i++;
        }
        $new_string .= mb_substr( $string, $offset );
        $string = $new_string;
    }

    public function replaceBannedXMLSimbols( $string )
    {
        //$string = str_replace('&', '&amp;', $string);
        $this->replaceAMP( $string );

        $string = str_replace( array('<','>'), array('&lt;','&gt;'), $string);

        return $string;
    }
	
	//xml2dom
	function xml2dom($xml)
    {
        $this->replaceEntityBack( $xml );
        $this->replaceEntity2Simbols( $xml );
        $this->replaceAMP( $xml );

		$doc = new DOMDocument();
        if ( $xml == '' ) $xml = '<root></root>';
        $load_succesfull = $doc->loadXML( $xml );

		return $load_succesfull ? $doc : 'error';
	}
	
	//DOMElement -> xml
	function _get_inner_html( $node ) {
		$innerHTML= '';
		$children = $node->childNodes;
		foreach ($children as $child) {
			$innerHTML .= $child->ownerDocument->saveXML( $child );
		}
		return $innerHTML;
	}
	
	function _getChilds($dom, $parent_class = null){ //$parent_class использовал для отладки
		$childs = $dom->childNodes;
		$cur_node = '';

		if(!empty($childs)) foreach ( $childs as $node ) {
			$current_page = null;
			$nochilds     = false;
			$r = '';
			
			$class = get_class($node);
			
			$set_page = false;

            if( $class != 'DOMText' && $class != 'DOMCdataSection' && $class != 'DOMComment' && $node->getAttribute('data-page') != '' ){
                $current_page = $node->getAttribute('data-page');
                $set_page = true;
			}
			
			$name = $value = '';

            if( $class != 'DOMText' && $class != 'DOMCdataSection' && $class != 'DOMComment' && $node->getAttribute('data-node-name') )
            {
				//xml node name
				$name = $node->getAttribute('data-node-name');
				
				//attributes xml in string view
				$attrs = $node->getAttribute('data-attrs');
				//тип указывает, как будет преобразовываться содержимое узла
				//text - как текст (теги режутся)
				//xml - скопирует содержимое в виде xml
				//node - будет парсить
				
				$type = $node->getAttribute('data-type') ? $node->getAttribute('data-type') : 'node';
				//if($node->tagName == 'textarea') $type = 'cdata';
				
				if($type == 'text') {
                    $value = $node->nodeValue;
                    $value = $this->replaceBannedXMLSimbols( $value );
                    $value = strip_tags( $value );
                }
				
				elseif($type == 'cdata') {
                    $value = html_entity_decode($node->nodeValue, ENT_NOQUOTES, 'UTF-8');
                }
				
				elseif($type == 'xml'){
					$value = $this->_get_inner_html( $node );
					$value = $xml = preg_replace('/<\?(.*)\?>/Uxsi', '', $value);
                    //вложенные секции не допустимы
					$value = str_replace( array( '<![CDATA[', ']]>' ), '', $value );
					$value = html_entity_decode( $value, ENT_NOQUOTES, 'UTF-8' );
                    //$value = $this->replaceBannedXMLSimbols( $value );

					$value = '<![CDATA['.($value).']]>';
				}
				
				if( $type != 'node' ) $nochilds = true;
				
				if (@$node->tagName == 'input'){
					$nochilds = true;//input не имеет потомков
					//$value = html_entity_decode(html_entity_decode($node->getAttribute('value')));
					//$value = html_entity_decode(html_entity_decode($value = $node->nodeValue));
					$name = '';
				}
				
				if(@$node->tagName == 'select'){
					$nochilds = true;//select не имеет потомков
					$value = $node->getAttribute('data-value');
				}
				
				if(@$node->tagName == 'img'){
                    $value = !$node->hasAttribute('data-src') ? $node->getAttribute('src') : $node->getAttribute('data-src');
				}
			}
            elseif( @$node->tagName == 'img' )
            { //pictures
				$name = 'picture';

                $value = !$node->hasAttribute('data-src') ? $node->getAttribute('src') : $node->getAttribute('data-src');

                $sizes = $this->dsp->image->GetImageSize( $value );

                if ( !empty( $sizes[0] ) && $sizes[0] > 690 )
                {
                    $value = $this->dsp->eis->Resize( $value, 690, $this->dsp->image->GetSizeForResize($value, 'width', 690), 'resize' );
                }

                $attrs = $node->getAttribute('data-attrs');
				$attrs .= ' width="'.$node->getAttribute('data-width').'" height="'.$node->getAttribute('data-height').'" ';
				$attrs .= ' size="'.$node->getAttribute('data-size').'" ';
				$attrs .= ' preview="'.$this->dsp->eis->Resize( $value, 120, $this->dsp->image->GetSizeForResize($value, 'width', 120), 'resize' ).'" ';
			}

            if ( $name == 'REPLACE_quote' )
            {
                $select_exist = $node->getElementsByTagName('select')->item(0);
                $select_style = $node->getElementsByTagName('select')->item(1);
                $option = FALSE;
                $style = FALSE;

                if ( !empty( $select_exist ) )
                {
                    foreach ( $select_exist->getElementsByTagName('option') as $o )
                    {
                        if ($o->hasAttribute('selected') && 'selected' === $o->getAttribute('selected')) {
                            $option = $o->getAttribute('value');
                        }
                    }
                }

                if ( !empty( $select_style ) )
                {
                    foreach ( $select_style->getElementsByTagName('option') as $o )
                    {
                        if ($o->hasAttribute('selected') && 'selected' === $o->getAttribute('selected')) {
                            $style = $o->getAttribute('value');
                        }
                    }
                }

                if ( $option )
                {
                    switch ( $option )
                    {
                        case 'new':
                            foreach ( $node->getElementsByTagName('textarea') as $t )
                            {
                                if ( $t->hasAttribute('data-node-name') && $t->getAttribute('data-node-name') == 'quote' )
                                {
                                    $quote = $t->nodeValue;
                                    $quote = $this->replaceBannedXMLSimbols( $quote );
                                    $quote = strip_tags( $quote );

                                    $id = $this->dsp->quotes->AddItem( array( 'text' => $quote, 'person' => 0, 'status' => 1, 'datecreate' => date('Y-m-d H:i:s') ) );
                                    $id = $id['id'];

                                    $r .= '<'.$name.'><id>'.$id.'</id>'.( $style ? '<style>'.$style.'</style>':'' ).'</'.$name.'>';
                                }
                            }

                            break;
                        case 'exist':
                            $id = FALSE;

                            foreach ( $node->getElementsByTagName('span') as $t )
                            {
                                if ( $t->hasAttribute('data-node-name') && $t->getAttribute('data-node-name') == 'id' )
                                {
                                    $id = (int)$t->nodeValue;
                                }
                            }

                            if ( $id )
                            {
                                foreach ( $node->getElementsByTagName('textarea') as $t )
                                {
                                    if ( $t->hasAttribute('data-edit') && $t->getAttribute('data-edit') == 'true' )
                                    {
                                        $quote = $t->nodeValue;
                                        $quote = $this->replaceBannedXMLSimbols( $quote );
                                        $quote = strip_tags( $quote );

                                        $this->dsp->quotes->UpdateByKey( $id, array( 'text' => $quote ) );
                                    }
                                }
                            }

                            foreach ( $node->getElementsByTagName('div') as $d )
                            {
                                if ( $d->hasAttribute('id') && $d->getAttribute('id') == $option )
                                {
                                    $r .= '<'.$name.'>'.$this->_getChilds( $d, $class ).( $style ? '<style>'.$style.'</style>':'' ).'</'.$name.'>';
                                }
                            }
                            break;
                    }
                }

            }
			elseif ( $name && $name != 'undefined')
            {
				$attrs = str_replace('&quot;', '"', $attrs);

                if($name != 'text') $r .= '<'.$name.' '.$attrs.' >';

				if( !$nochilds ) $r .= $this->_getChilds( $node, $class ); //next node parsing

				if($value)
                {
                    $r .= $value;
                }

				if( $name != 'text' ) $r .= '</'.$name.'>';
			}
			else $r .= $this->_getChilds( $node, $class );
			
			if ( $current_page !== null && $set_page && !empty( $node->parentNode ) && $node->parentNode->hasAttribute('id') && $node->parentNode->hasAttribute('id') == 'article' )
            {
                $cur_node .= '<page num="'.$current_page.'">'.$r.'</page>';
            }
			else $cur_node .= $r;
		}

		return $cur_node;
	}
		
	//html parsing
	function html2xml( $html ){
		$xml = '<root>'.trim($html).'</root>';
		$xml = preg_replace('/[[:cntrl:]]+/', '', $xml);
		//убиваем формы
		$xml = preg_replace('/<form.*>.*<\/form>/Uxsi', '', $xml);
		//убираем скрипты
		$xml = preg_replace('/<script.*>.*<\/script>/Uxsi', '', $xml);

		//убиваем кнопы
		$xml = preg_replace('/<button.*>.*(<\/button>)?/Uxsi', '', $xml);
		//убмваем комменты
		//$xml = preg_replace('/<!--.*-->/Uxsi', '', $xml);

		//$rep1 = array('©','®','™','€','„','“','«','»','≥','≤','≈','≠','≡','§','∞','&nbsp;', '$', '—');
		//$rep2 = array('&copy;','&reg;','&trade;','&euro;','&bdquo;','&ldquo;','&laquo;','&raquo;','&ge;','&le;','&asymp;','&ne;','&equiv;','&sect;','&infin;',' ', '', '&mdash;');
		//$xml = str_replace($rep2, $rep1, $xml);
        //$this->replaceSimbols($xml);

        $match = array();
        preg_match_all( '/value[\s]*=[\s]*"([^"]*)"/', $xml, $match );

        if ( is_array( $match[1] ) && count($match[1]) > 0 )
        {
            foreach ( $match[1] AS $key => $value )
            {
                $match[2][$key] = str_replace( '<', '&lt;', $value );
                $match[2][$key] = str_replace( '>', '&gt;', $match[2][$key] );
            }

            $xml = str_replace( $match[1], $match[2], $xml );
        }

        //закрываем инпуты, имагесы и бр
        $xml = preg_replace('/<img(.*)>/Uxsi', '<img\\1/>', $xml);
        $xml = preg_replace('/<br(.*)>/Uxsi', '<br\\1/>', $xml);
        $xml = preg_replace('/<hr(.*)>/Uxsi', '<hr\\1/>', $xml);
        $xml = preg_replace('/<input(.*)>/Uxsi', '<input\\1/>', $xml);

		//это вконце
		//$xml = str_replace('&', '&amp;', $xml);

		//просмотр исходника
		/*if(@$_REQUEST['button_value_html']){
			echo str_replace('	', '&nbsp;&nbsp;&nbsp;&nbsp;', nl2br(htmlspecialchars($xml)));
		}*/
		$xml = $this->xml2dom($xml);
		$r = $this->_getChilds($xml);
		$new_xml = '<root>'.$r.'</root>';

		//слияние страниц
		$pages = array();

		//$new_xml = str_replace('&', '&amp;', $new_xml);
        //$this->replaceSimbols($new_xml);

		$new_xml = $this->xml2dom( $new_xml );

		$node_list = $new_xml->getElementsByTagName('page');

        foreach($node_list as $node){
			$page = $node->getAttribute('num');
			$inner_xml = $this->_get_inner_html($node);
			$pages[$page][] = $inner_xml;
		}
                
		foreach($pages as $i=>&$p){
			$p = '<page num="'.$i.'">'.implode('', $p).'</page>';
		}

		$new_xml = '<root>'.implode('', $pages).'</root>';
        //$this->replaceSimbols($new_xml);
        //$this->replaceEntity( $new_xml );

        //убиваем элементы с пустым id
		$new_xml = preg_replace('/<REPLACE_[a-z_-]+><id\/><\/REPLACE_[a-z_-]+>/', '', $new_xml);
        //убиваем пустые теги
		$new_xml = preg_replace('/<[a-z_-]+><\/[a-z_-]+>/', '', $new_xml);
        $new_xml = preg_replace('/<(?!br)[a-z_-]+\/>/', '', $new_xml);
		$new_xml = preg_replace('/<[a-z_-]+><\!\[CDATA\[\]\]><\/[a-z_-]+>/', '', $new_xml);

        $new_xml = preg_replace('/[\s]+/', ' ', $new_xml);

		//так не работает вставка кода, но редактор не сможет вписать тэги руками
		//return str_replace(array('<xscript', '</xscript'), array('<script', '</script'), $new_xml);
		
		//так работает вставка кода, но редактор сможет вписать тэги руками
		//return str_replace(array('<xscript', '</xscript'), array('<script', '</script'), html_entity_decode(str_replace( '&amp;', '&', $new_xml ), ENT_COMPAT ,'UTF-8'));
		//return str_replace(array('<xscript', '</xscript'), array('<script', '</script'), html_entity_decode($new_xml, ENT_COMPAT ,'UTF-8'));
		return $new_xml;
	}

	
	function externalHTMLLinks( $result, $show_time = false ){ //для ссылок вида <a href=""/>
		//external links
		global $our_domains;
		if($show_time) $st = microtime( true );
		//$our_domains = file( ROOT_DIR . 'our_domains.txt' );
		// in core/const.php now
		$all_our_domains = array();
		foreach( $our_domains as $d ){
			$all_our_domains []= trim( $d );
			$all_our_domains []= trim( 'www.'.$d );
		}
		$m = array();
		$is_match = preg_match_all ( '/<\s*a\s*href=[\'|"]?([^\'|"]+)[\'|"]?[^>]*>/i', $result, $m );
		list($tags, $links) = $m;
		foreach($tags as $i=>&$t){
			$add_external = false;
			$add_blank = true;
			$path_arr = explode( '/', $links[$i] );
			if( $path_arr[0] == '' ) unset($path_arr[0]);
			elseif( !in_array( $path_arr[2], $all_our_domains ) &&
				$path_arr[0] == 'http:' ) $add_external = true;
			elseif( !in_array( $path_arr[0], $all_our_domains ) &&
				strstr( $path_arr[0], '.' ) && $path_arr[0] != '..' ) $add_external = true;
			if( stristr( $t, 'target="_blank"' ) || stristr( $t, "target='_blank'" ) ) $add_blank = false;
			if( $add_external ){
				$new_tag = str_replace( 'href=', ( $add_blank ? 'target="_blank" ' : '' ).'rel="nofollow" href=', $t );
				$result = str_replace( $t, $new_tag, $result );
			}
		}

		if($show_time){
			$et = microtime( true );
			echo ( $et - $st ).' sec.';
		}

		return $result;
	}

	function externalXMLLinks( $result, $show_time = false ){ //для ссылок вида <link/>
		//external links
		global $our_domains;
		if($show_time) $st = microtime( true );
		//$our_domains = file( ROOT_DIR . 'our_domains.txt' );
		// in core/const.php now
		$all_our_domains = array();
		foreach( $our_domains as $d ){
			$all_our_domains []= trim( $d );
			$all_our_domains []= trim( 'www.'.$d );
		}
		$m = array();
		$is_match = preg_match_all ( '/<\s*link\s*[^>]*>([^<]*)<\s*\/\s*link\s*[^>]*>/i', $result, $m );
		list($tags, $links) = $m;
		foreach($tags as $i=>&$t){
			$add_external = false;
			$path_arr = explode( '/', $links[$i] );
			if( $path_arr[0] == '' ) unset($path_arr[0]);
			elseif( !in_array( $path_arr[2], $all_our_domains ) &&
				$path_arr[0] == 'http:' ) $add_external = true;
			elseif( !in_array( $path_arr[0], $all_our_domains ) &&
				strstr( $path_arr[0], '.' ) && $path_arr[0] != '..' ) $add_external = true;
			if( $add_external ){
				$new_tag = str_replace( '<link', '<link external="1"', $t );
				$result = str_replace( $t, $new_tag, $result );
			}
		}
		
		if($show_time){
			$et = microtime( true );
			echo ( $et - $st ).' sec.';
		}
		
		return $result;
	}

    public function getPicForFacebook($url)
    {
        $w_ = 600; $h_ = 315;
        $o = $this->dsp->eis->GetOriginalURL($url);
        list($w, $h) = $this->dsp->eis->GetSizeByURL($o);
        if ($w > $w_)
            $o = $this->dsp->eis->Resize($o, $w_, $h_, 'crop-gravity-north');
        else {
            $o = $this->dsp->eis->Resize($o, $w, round($w * ($h_ / $w_)), 'crop-gravity-north');
        }

        return $o;
//        return $this->dsp->eis->Resize($o, 1200, 630, 'crop-gravity-north');
    }

    public function getPicForTwitter($url)
    {
        $w_ = 1012; $h_ = 508;
        $o = $this->dsp->eis->GetOriginalURL($url);
        list($w, $h) = $this->dsp->eis->GetSizeByURL($o);
        if ($w > $w_)
            $o = $this->dsp->eis->Resize($o, $w_, $h_, 'crop-gravity-north');
        else {
            $o = $this->dsp->eis->Resize($o, $w, round($w * ($h_ / $w_)), 'crop-gravity-north');
        }

        return $o;
    }

    public function preg_replaceQuotes() {

        $this->quotesCounter *= -1;

        if ($this->quotesCounter < 0)
        {
            return "&laquo;";
        } else {
            return "&raquo;";
        }
    }


    public function preg_replaceQuotesSimpleHtml($ar)
    {
        $this->quotesCounter = 1;
        return '<![CDATA['.preg_replace_callback("#(\")(?![^<]*?>)#i", array(&$this, 'preg_replaceQuotes'), $ar[1]).']]>';
    }

    public function replaceQuotesSimpleHtml($xml)
    {
//        return preg_replace_callback("|<simple_html>(.*)</simple_html>|sU", array(&$this, 'preg_replaceQuotesSimpleHtml'), $xml);
        return preg_replace_callback("|<\!\[CDATA\[(.*)\]\]>|sU", array(&$this, 'preg_replaceQuotesSimpleHtml'), $xml);
    }


    public function preg_replaceQuotesSimpleHtml_left($ar)
    {
        $t = preg_replace("#(\")([0-9a-zA-Zа-яА-Я]+)(?![^<]*?>)#iu", "&laquo;$2", $ar[1]);
        $t = preg_replace("#\"<([^/])#iu", "&laquo;<$1", $t);
        return '<![CDATA['.$t.']]>';
    }

    public function preg_replaceQuotesSimpleHtml_right($ar)
    {
        return '<![CDATA['.preg_replace("#(\")(?![^<]*?>)#iu", "&raquo;", $ar[1]).']]>';
    }

    public function replaceQuotesSimpleHtml_queue($xml)
    {
//        $xml = '<![CDATA[<p>Тест "<a href="/admin/ya.ru" target="_blank">Ссылка</a>" Тест</p>]]>';
        $xml = preg_replace_callback("|<\!\[CDATA\[(.*)\]\]>|sU", array(&$this, 'preg_replaceQuotesSimpleHtml_left'), $xml);
        $xml = preg_replace_callback("|<\!\[CDATA\[(.*)\]\]>|sU", array(&$this, 'preg_replaceQuotesSimpleHtml_right'), $xml);
//        echo $xml; exit;
        return $xml;
    }

    function removeCKShit(&$s)
    {
        $s = preg_replace("|>([\s\t]*)|", ">", $s);
        $s = preg_replace("|([\s\t]*)<|", "<", $s);
    }

    function stripInvalidXml($value)
    {
        $ret = "";
        $current;
        if (empty($value))
        {
            return $ret;
        }

        $length = strlen($value);
        for ($i=0; $i < $length; $i++)
        {
            $current = ord($value{$i});
            if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||
                (($current >= 0x20) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF)))
            {
                $ret .= chr($current);
            }
            else
            {
                $ret .= " ";
            }
        }
        return $ret;
    }
}