<?php
/**
 * @name FirstChildRedirect
 * @author Jason Coward <jason@opengeek.com>
 * @author Ryan Thrash <ryan@vertexworks.com>
 * @author Olivier B. Deland <olivier@conseilsweb.com> Refactored for Revo and
 * added functionnalities
 * @author Mark Hamstra <hello@markhamstra.com> Refactor for Revo 2.1
 * @license Public Domain
 * @version 2.3
 * @package firstchildredirect
 *
 * This snippet redirects to the first child document of a folder in which this
 * snippet is included within the content (e.g. [[FirstChildRedirect]]).  This
 * allows MODx folders to emulate the behavior of real folders since MODx
 * usually treats folders as actual documents with their own content.
 *
 * Added parameters to allow greater flexibility
 *
 * &docid=`12` (optional; default: current document)
 * Use the docid parameter to have this snippet redirect to the
 * first child document of the specified document.
 *
 * &default=`1` or &default=`site_start` (optional; default: site_start)
 * Use the default parameter to have this snippet redirect to the
 * document specified in cases where there is no children.
 * It can be a document ID or one of : site_start,site_unavailable_page,error_page,unauthorized_page
 *
 * &sortBy=`menuindex` (optional; default:menuindex)
 * Get the first child depending on this sort order
 * Can be any valid modx document field name
 *
 * &sortDir=`DESC` (optional; default:ASC)
 * Sort `ASC` for ascendant or `DESC` for descendant
 *  
 * &responseCode ("301", "302" or the complete response code, eg "HTTP/1.1 302 Moved Temporarily", defaults to 301)
 * The responsecode (statuscode) to use for sending the redirect.
 * 
 */
/* @var modX $modx
 * @var array $scriptProperties
 */ 

/*
 * Parameters
 * Parent doc 
 */
$docid = $modx->getOption('docid',$scriptProperties,null);
if ($docid === null) { 
    $parent = $modx->resource->get('id');
}
else {
    $parent = $docid;
}

$respcode = $modx->getOption('responseCode',$scriptProperties,'301');
$rcodes = array(
    '301' => 'HTTP/1.1 301 Moved Permanently',
    301 => 'HTTP/1.1 301 Moved Permanently',
    '302' => 'HTTP/1.1 302 Moved Temporarily',
    302 => 'HTTP/1.1 302 Moved Temporarily',
);
if (isset($rcodes[$respcode])) $respcode = $rcodes[$respcode];
$respcode = array('responseCode' => $respcode);
/* default doc in case there's no children
 * can be an id or one of: site_start, site_unavailable_page, error_page,
 * unauthorized_page
 * Default is site_start
 */
$default = $modx->getOption('default',$scriptProperties,'site_start');
if (in_array($default,array('site_start','site_unavailable_page','error_page','unauthorized_page'))) {
    $default = $modx->getOption($default,null,1);
} 
else {
    if (is_numeric($default)) { 
        $default = (int)$default;
    }
    else { 
        return 'Invalid &default property.'; 
    }
}

/* sort list */
$sortBy = $modx->getOption('sortBy',$scriptProperties,'menuindex');
/* sort dir */
$sortDir = $modx->getOption('sortDir',$scriptProperties,'ASC');

/*
* Execute
*/
$c = $modx->newQuery('modResource');
$c->limit(1);
$c->sortby($sortBy,$sortDir);
$c->where(array(
    'published' => 1,
    'parent' => $parent
));

/* @var modResource $children */
$children = $modx->getObject('modResource',$c);
if (!empty($children)) {
    $url = $modx->makeUrl($children->get('id'));
    return $modx->sendRedirect($url,$respcode);
}

// If it got here, there obviously weren't any children resources.. redirect to default.
return $modx->sendRedirect($modx->makeUrl($default),$respcode);

?>
