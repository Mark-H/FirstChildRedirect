/**
 * @name FirstChildRedirect
 * @author Jason Coward <jason@opengeek.com>
 * @author Ryan Thrash <ryan@vertexworks.com>
 * @author Olivier B. Deland <olivier@conseilsweb.com> Refactored for Revo and
 * added functionnalities
 * @auhtor Mark Hamstra <business@markhamstra.nl> Refactor for Revo 2.1
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
 */

/*
 * Parameters
 */
/* parent doc */
  $docid = $modx->getOption('docid',$scriptProperties,null);
  if ($docid === null) { $parent = $modx->resource->get('id'); }
  else {
    $parent = $docid;
  }

/* default doc in case there's no children
 * can be an id or one of: site_start, site_unavailable_page, error_page,
 * unauthorized_page
 * Default is site_start
 */
  $default = $modx->getOption('default',$scriptProperties,'site_start');
  if (in_array($default,array('site_start','site_unavailable_page','error_page','unauthorized_page'))) {
    $default = $modx->getOption($default,null,1);
  } else {
    if (is_numeric($default)) { $default = (int)$default; }
    else { return 'Invalid &default property.'; }
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
    'parent' => $parent));
  $children = $modx->getObject('modResource',$c);
  if (!empty($children)) {
    $url = $modx->makeUrl($children->get('id'));
    return $modx->sendRedirect($url);
  }

  // If it got here, there obviously weren't any children resources.. redirect to default.
  return $modx->sendRedirect($modx->makeUrl($default));