<?php
/**
 * @name FirstChildRedirect
 * @author Jason Coward <jason@opengeek.com>
 * @author Ryan Thrash <ryan@vertexworks.com>
 * @author Olivier B. Deland <olivier@conseilsweb.com> Refactored for Revo and
 * added functionnalities
 * @license Public Domain
 * @version 2.1
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
 */

/*
 * Parameters
 */
/* parent doc */
$docid = isset ( $docid ) ? $docid : $modx->resource->get('id');

/* default doc in case there's no children
 * can be an id or one of: site_start, site_unavailable_page, error_page,
 * unauthorized_page
 * Default is site_start
 */
$default = isset( $default ) ? $default : $modx->getOption('site_start',null,1);
if (in_array($default,array('site_start','site_unavailable_page','error_page','unauthorized_page'))) {
    $default = $modx->getOption($default,null,1);
} else {
    $default = intval($default);
}

/* sort list */
$sortBy = isset( $sortBy ) ? $sortBy : 'menuindex';

/* sort dir */
$sortDir = isset( $sortDir ) && $sortDir == 'DESC' ? $sortDir : 'ASC';

/*
 * Execute
 */
/* Get children sorted
 * FIXME: getActiveChildren is depecrated in 1.0
 */
$children= $modx->getActiveChildren($docid, $sortBy, $sortDir);
$targetid = isset ( $children[0]['id'] ) ? $children[0]['id'] : $default;
$url = $modx->makeUrl($targetid,'','','full');

return $modx->sendRedirect($url);