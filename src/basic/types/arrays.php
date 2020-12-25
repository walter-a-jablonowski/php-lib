<?php

/*@----------------------------------------------------------------------------
| Walter A. Jablonowski                          walter-a-jablonowski.github.io
-------------------------------------------------------------------------------
| 
| - Get, Set by identifier like 'key.key2 ...'
| - Rename
| - Nest, unnest
| 
----------------------------------------------------------------------------@*/


/*@----------------------------------------------------------------------------
| Get, Set by identifier
----------------------------------------------------------------------------@*/

define('ARR_IDENT_POINT_DELIM', '.');
define('ARR_IDENT_SLASH_DELIM', '/');


/*@

USAGE: arr_by_str('a.b', $arr)

ARGS:

  - $ident is 'key.key2 ...'
  - §delim = ARR_IDENT_POINT_DELIM | ARR_IDENT_SLASH_DELIM | misc
  - $delim could also be -> or ][

https://stackoverflow.com/questions/7508352/dynamic-array-keys

Old names: array_from_key(), array_val_by_str()
*/
function arr_get($ident, $arr, $default = null, $delim = '.') /*@*/
{
  if( $delim == '][')  $ident = trim($ident, '[]');

  $ident = trim($ident);

  $keys = explode($delim, $ident);
  $e = &$arr;
  foreach($keys as $key)
  {
    if( ! isset($e[$key]) )
      return $default;
    
    $e = &$e[$key];  // shift idx
  }

  return $e;
}


/*@

USAGE: arr_by_str('a.b', $arr, $val)

See arr_get()

*/
function arr_set($ident, $arr, $val, $delim = '.') /*@*/
{
  if( $delim == '][')  $ident = trim($ident, '[]');
  
  $ident = trim($ident);

  $keys = explode($delim, $ident);
  $e = &$arr;
  foreach($keys as $key)
  {
    if( ! isset($e[$key]) )  // add missing keys
      $e[$key] = null;
    
    $e = &$e[$key];          // shift idx
  }

  $e = $val;

  return $arr;
}


/*@----------------------------------------------------------------------------
| Rename
----------------------------------------------------------------------------@*/

/*@

USAGE: arr_rename_keys([ 'old/key' => 'new' ], $arr, '.')

Alternative: arr_set() verwenden

Old code: similar func available modify_keys( $a, $func)
*/
function arr_rename_key($rename, $arr, $delim = '/') /*@*/
{
  if( $delim == '][')  $ident = trim($ident, '[]');
  
  $ident = trim( array_keys($rename)[0] );
  $new   = trim( array_keys($rename)[1] );

  $keys = explode($delim, $ident);
  $last_key = array_pop($keys);

  $e = &$arr;
  foreach($keys as $key)
  {
    $e = &$e[$key];  // shift idx
  }

  $e[$new] = $e[$last_key];
  unset($e[$last_key]);

  return $arr;
}

function arr_rename_keys($keys, $arr, $delim = '/', $level = '')  // level ist first/key/ ...
{
  if( $level )  $level .= $delim;
  
  $r = [];

  foreach($arr as $name => $v)
  {
    $l = $level . $name;

    if( is_array($v))
    {
      if( in_array( $l, $keys ))
        $r[ trim($keys[$l]) ] = arr_rename_keys($keys, $v, $delim, $l);
      else
        $r[$name] = arr_rename_keys($keys, $v, $delim, $l);
    }
    else
    {
      if( in_array( $l, $keys ))
        $r[ trim($keys[$l]) ] = $v;
      else
        $r[$name] = $v;
    }
  }

  return $r;
}


/*@----------------------------------------------------------------------------
| Nest, unnest
----------------------------------------------------------------------------@*/

function arr_nest( $arr, $delim = '.', $key = '')  // key is for rcur
{
  $r = [];
  foreach( $arr as $name => $v)
    if( strpos( $name, $delim) === false)
      $r[$name] = $v;
    else
    {
      $s = '$r["'
         . str_replace( $delim, '"]["', $name)
         . '"] = $v;';
      
      eval( $s );
    }

  return $r;
}


function arr_unnest( $arr, $delim = '.', $key_prefix = '', $key = '')  // key is for rcur
{
  if( $key)  $key .= $delim;

  $r = [];
  foreach( $arr as $name => $v)
    if( ! is_array( $v))
      $r[ "$key_prefix$key$name" ] = $v;
    else
      $r += arr_unnest( $v, $delim, $key_prefix, "$key$name");

  return $r;
/*
  $a = [];
  foreach( $arr as $k => $v)
    arr_unnest_int( $a, explode( $sep, $k), $v);

  return $a;
*/
}

/*
function arr_unnest_int(&$ary, $keys, $val)
{
  $keys ? 
    ins($ary[array_shift($keys)], $keys, $val) :
    $ary = $val;
}
*/

?>