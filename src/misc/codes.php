<?php

// see also kb

// or use `md5( uniqid( rand(), true))` stuff
function rndBase32($length = 5)
{
  $chars = '123456789ABCDEFGHJKMNPQRSTUVWXYZ';  // base 32, no 0 (null), O (upper o), I (upper i) and l (lower L) - cant be confused
  
  $r = '';
  
  for ($i = 0; $i < $length; $i++)
    $r .= $chars[rand(0, strlen($chars) - 1)];

  return $r;
}


/*
$used = [
  '1000'
];
echo newCode($used, 4);
*/
function newCode($used, $digits)
{
  do {

    $new = rndBase32($digits);

  } while( in_array($new, $used) );

  return $new;
}

?>