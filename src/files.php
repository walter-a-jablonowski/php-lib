<?php

/*@----------------------------------------------------------------------------
| Walter A. Jablonowski                          walter-a-jablonowski.github.io
------------------------------------------------------------------------------- 
| 
| PHP file alternatives see kb
| 
| 
| **Is empty**
| 
| - fld_is_empty()
| 
| 
| **Load single folder**
| 
| - scan()                    DEV name fil_names()
| - fld_names()
| 
| 
| **Rcurse all files and folders**
| 
| - scan_all_fils()      new version cause many problems using glob
| - scan_all_sub_flds()  DEV
| 
| - or use merge for files and folders
| 
| 
| **Search**
| 
| - glob_recursive()          DEV all_fils_like()  all_sub_flds_like()
| - glob() - PHP function     DEV fil_names_like() fld_names_like() just geds names
| 
| 
| **Delete**
| 
| - rrmdir()
| - del_fld()
| 
----------------------------------------------------------------------------@*/


/*-----------------------------------------------------------------------------
| Rcurse all files and folders
-----------------------------------------------------------------------------*/

/*@

  New version cause many problems using glob

  for sort use a sort func

*/
function scan_all_fils( $dir, $types = [], $first_level = true) /*@*/
{ 
  if( ! is_array($types) )  $types = [$types];

  $dir  = str_replace( '\\', '/', trim($dir));
  $dir  = rtrim( $dir, '/' );  // unify just for this func
  $base = scandir($dir);
  
  $r = [];
  foreach( $base as $f)
  {
    if( $f === '.' || $f === '..')
      continue;
    
    elseif( is_file("$dir/$f"))
    {
      $r[] = "$dir/$f";
      continue;
    }
    
    $r = array_merge( $r, scan_all_fils( "$dir/$f", $types, $first_level ));
  } 

  // Filter types

  if( $types && $first_level )
  {
    $r = array_filter( $r, function($v) use($types) {

      $e = pathinfo( $v );
      if( ! isset( $e['extension'] ))
        return false;
      return in_array( $e['extension'], $types);
    });
  }

  return $r;
}


/*@

  New version cause many problems using glob

  for sort use a sort func

*/
function scan_all_sub_flds( $dir, $first_level = true) /*@*/
{ 
  $dir  = str_replace( '\\', '/', trim($dir));
  $dir  = rtrim( $dir, '/' );  // unify just for this func
  $base = scandir($dir);
  
  $r = [];
  foreach( $base as $d)
  {
    if( $d === '.' || $d === '..')
      continue;
    
    elseif( is_file("$dir/$d"))
      continue;

    $r[] = "$dir/$d";
    $r = array_merge( $r, scan_all_sub_flds( "$dir/$d", $first_level ));
  } 

  return $r;
}
