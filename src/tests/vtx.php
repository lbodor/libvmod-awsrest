<?php

//
//php vtx.php [aws config] [region] [url]
//php vtx.php ~/.aws/config ap-northeast-1 /foo/bar


//non-sts
$r   = parse_ini_file($argv[1]);
$cmd = sprintf('varnishtest -Dregion=%s -Daccesskey="%s" -Dsecretkey="%s" -Durl="%s" r*.vtx',
         $argv[2],
         $r['aws_access_key_id'],
         $r['aws_secret_access_key'],
         $argv[3]
  );
echo shell_exec($cmd);

//sts
$r   = json_decode(shell_exec('aws sts get-session-token'), 1);
$i   = ceil(strlen($r['Credentials']['SessionToken'])/2);
$cmd = sprintf('varnishtest -Dregion=%s -Daccesskey="%s" -Dsecretkey="%s" -Dtoken1="%s" -Dtoken2="%s" -Durl="%s" t*.vtx',
         $argv[2],
         $r['Credentials']['AccessKeyId'],
         $r['Credentials']['SecretAccessKey'],
         substr($r['Credentials']['SessionToken'],0,$i),
         substr($r['Credentials']['SessionToken'],$i),
         $argv[3]
  );
echo shell_exec($cmd);


