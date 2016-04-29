<?php
/**
 * 文件的扫描
 * 
 */
//文件的存放地址
$uploadPath = dirname(__FILE__) . DIRECTORY_SEPARATOR;
//请求的目录地址
$_GET['activepath'] = isset( $_GET['activepath'] ) ? urldecode($_GET['activepath']) : "";
$activepath = ( isset( $_GET['activepath'] ) && ( trim($_GET['activepath']) ) ) ? trim( $_GET['activepath'] ) : 'upload';

$fileList = scanDirList($uploadPath,$activepath );

//上一级目录
if( $activepath != "" && $activepath != "upload" ){ 
	$preDir = substr( dirname( $uploadPath.$activepath ),strlen( $uploadPath ) );
}else{
	$preDir = $activepath;
}
$preDir = urlencode( $preDir );


//文件列表的输出
$fileTrHtml = '';

foreach( $fileList as $k=>$v ){
	
	$fileTrHtml .= "<tr><td>".($v['isdir']==1 ? '【目录】' : '【文件】' );		
	
	if( $v['isdir']== 1){
		$v['path'] = urlencode( $v['path'] );
		$fileTrHtml .= "<a href='./select_images.php?activepath={$v['path']}'>{$v['name']}</a>";
		
	}else{
		
		$fileTrHtml .= "<a href='javascript:;' onclick=\"setImageUrl('{$v['path']}')\">{$v['name']}</a>";
		
	}
	
	
	$fileTrHtml .= "</td></tr>";
}



$html = <<<EOT
<html>
<head></head>
<body>

<div>
	<table>
		<tr>
			<td><a href="./select_images.php?activepath={$preDir}">返回上一级目录</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当前目录{$activepath}</td>
		</tr>
		{$fileTrHtml}
	</table>
</div>
<script type='text/javascript'>
	function setImageUrl( imgurl ){
		window.opener.document.getElementById('filepath').value = imgurl;
		if(document.all) window.opener=true;
  		window.close();
	}
</script>
</body>
</html> 
EOT;

echo $html;


/**
 * 
 * @param  $uploadPath 文件存储根目录
 * @param  $activepath 需要扫描的问了名称
 */
function scanDirList($uploadPath,$activepath){
	$file_array = array();
	$path = $uploadPath.$activepath;
	if ($handle = @opendir($path)) { 
		while (false !== ($file = @readdir($handle))) {
			if($file=='.' || $file=='..'){
				continue;
			} 
			$filePath = $activepath."/".$file;
			$temp['name'] = $file;
			$temp['path'] = $filePath;
			if(is_dir($filePath)){ 
				$temp['isdir'] = 1;
			}else{
				$temp['isdir'] = 0;
			}
			$file_array[] = $temp;
		}
		@closedir($handle);
	}
	return $file_array;
}


?>