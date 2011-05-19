===============================================
FirstChildRedirect
Authors:
    Jason Coward <jason@collabpad.com>
    Ryan Thrash <ryan@collabpad.com>
    Olivier B. Deland <olivier@conseilsweb.com>
    Shaun McCormick <shaun@collabpad.com>
License: GPL Public Domain
Version: 2.2
===============================================
 
This snippet redirects to the first child document of a folder in which this
snippet is included within the content (e.g. [[FirstChildRedirect]]).  This
allows MODx folders to emulate the behavior of real folders since MODx
usually treats folders as actual documents with their own content.

Added parameters to allow greater flexibility

&docid=`12` (optional; default: current document)
Use the docid parameter to have this snippet redirect to the
first child document of the specified document.

&default=`1` or &default=`site_start` (optional; default: site_start)
Use the default parameter to have this snippet redirect to the
document specified in cases where there is no children.
It can be a document ID or one of : site_start,site_unavailable_page,error_page,unauthorized_page

&sortBy=`menuindex` (optional; default:menuindex)
Get the first child depending on this sort order
Can be any valid modx document field name

&sortDir=`DESC` (optional; default:ASC)
Sort `ASC` for ascendant or `DESC` for descendant 