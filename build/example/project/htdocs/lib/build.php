<?php

  error_reporting(E_ALL);
  require('../assets.php');

  $gzip = FALSE;

  if(array_key_exists('gzip', $_GET))
  {
    $gzip = TRUE;
  }

  $root = '..';

  $joined_file = array(
    'css' => array(),
    'js' => array()
  );

  $remote_files = array(
    'css' => array(),
    'js' => array()
  );

  echo "\n\nRunning assets build process".($gzip ? " (with gzip compression)" : NULL)."\n";
  echo "============================\n";

  remove_old_files($root);

  // ######################################################


  foreach($assets['css'] as $file)
  {
    if(strpos($file, 'http') === FALSE)
    {
      // replace url settings within css
      $parts = explode('/', $file);
      array_pop($parts);
      $path = $assets['root'].'/'.join('/', $parts);
      $file = getfile($root.'/'.$file);

      preg_match_all('/url\((.*?)\)/i', $file, $urls);

      foreach($urls[1] as $url)
      {
        $parts = explode('/', trim($url, '\'"'));
        $new_url = trim($path.'/'.array_pop($parts), '\'"');
        $file = str_replace('url('.$url.')', "url($new_url)", $file);
        echo "@css: set url path from [$url] to [$new_url]\n";
      }

      $joined_file['css'][] = $file;
    }
    else
    {
      $remote_files['css'][] = '<link href="'.$file.'" rel="stylesheet">';
    }
  }


  // ######################################################
  // search for js assets


  foreach($assets['js'] as $file)
  {
    if(strpos($file, 'http') === FALSE)
    {
      $file = getfile($root.'/'.$file);

      // backbone root
      $file = preg_replace("/build_root = '.*?';/", "build_root = '".$assets['root']."';", $file);

      $joined_file['js'][] = substr($file, -1) !== ';' ? $file.';' : $file;
    }
    else
    {
      $remote_files['js'][] = '<script src="'.$file.'"></script>';
    }
  }


  // ######################################################
  // write joined files & live index.html


  foreach(array('css', 'js') as $type)
  {
    $file = join("\n\n", $joined_file[$type]);

    if($gzip)
    {
      $file = gzencode($file, 9);
    }

    // file + url = path
    $file_path = "assets/${type}/application-".md5($file).".${type}".($gzip === TRUE ? ".gz" : NULL);

    $fh = fopen($root.'/'.$file_path, 'w');
    fwrite($fh, $file);
    fclose($fh);

    echo "@${type}: $file_path... written\n";

    // write latest-(css/js).bundle file for easy include
    $remote_files[$type][] = $type == 'css' ? '<link href="'.$assets['root'].'/'.$file_path.'" rel="stylesheet">' : '<script src="'.$assets['root'].'/'.$file_path.'"></script>';
    $content = join("\n", $remote_files[$type]);

    $fh = fopen($root."/assets/${type}/latest-${type}.bundle", 'w');
    fwrite($fh, $content);
    fclose($fh);

    echo "@${type}: latest-${type}.bundle file... written\n";
  }

  echo "\nDone! Bye bye\n\n";


  // ######################################################


  function getfile($path)
  {
    return trim(join('', file($path)));
  }

  function remove_old_files($root)
  {
    $dir = $root.'/assets/css';
    $dh = opendir($dir);
    while($file = readdir($dh))
    {
      if(preg_match('/application-.*?\.css(\.gz)*/', $file))
      {
        unlink($dir.'/'.$file);
        echo "@css: delete old file...$dir/$file\n";
      }
    }
    closedir($dh);

    $dir = '../assets/js';
    $dh = opendir($dir);
    while($file = readdir($dh))
    {
      if(preg_match('/application-.*?\.js(\.gz)*/', $file))
      {
        unlink($dir.'/'.$file);
        echo "@js: delete old file...$dir/$file\n";
      }
    }
    closedir($dh);
  }

  function debug($data)
  {
    print_r($data);
    exit;
  }

?>
