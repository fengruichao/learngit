$contents =<<< 'TEXT'
上传文件在数据报中应该是
Content-Disposition: form-data; name="userfile"; filename="file_name"
Content-Type: 文档类型

文件内容

这样的格式
以下是服务器端代码
curl_upload_server.php
<xmp>
<?php
print_r($_FILES); //检查上传信息

echo "文件内容:\n";
$p = current($_FILES);
readfile($p['tmp_name']);//输出上传的文件

TEXT;

$varname = 'my';
$name = '3.txt';
$type = 'text/plain';

$key = "$varname\"; filename=\"$name\r\nContent-Type: $type\r\nAccept: \"";
$fields[$key] = $contents;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://localhost/curl_upload_server.php");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST" );
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$s = curl_exec ($ch);

curl_close ($ch);

echo $s;