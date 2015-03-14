<?php
/**
 * 极光推送-V3. PHP服务器端
 * @author 冯锐超
 * @Email 470443928@qq.com
 * @version 20150228 
 */
 

class jpush {
	private $_masterSecret = '';
	private $_appkeys = '';
	private $_authorization = '';
	private $_pushurl = '';
	/**
	 * 构造函数
	 * @param string $username
	 * @param string $password
	 * @param string $appkeys
	 */
	function __construct() {
		$this->_masterSecret = masterSecret;
		$this->_appkeys = appkeys;
		$this->_pushurl = jpush_url;
		$this->_authorization = base64_encode($this->_appkeys.':'.$this->_masterSecret);
	}
	/**
	 * 模拟post进行url请求
	 * @param string $url
	 * @param string $param
	 */
	function request_post($param = '') {
		if (empty($param)) {
			return false;
		}
		$postUrl = $this->_pushurl;
		$curlPost = json_encode($param);
		#echo $curlPost;die;
		$ch = curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
		#curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$this->_authorization));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		
		return $data;
	}
	/**
	 * 发送
	 * 参数说明请参考 http://docs.jpush.io/server/rest_api_v3_push/文档
	 *
	 */
	function send($data=array()) {
		$defined = array(
			'platform'=>'all',
			'audience'=>'all',
			'notification'=>array(
				'android'=>array(
					'alert'=>'Hi,Ttime',
					'title'=>'Hi,Title',
					'builder_id'=>1,
					'extras'=>array(),
				)

			),
			'options'=>array(
				'sendno'=>1,
				'apns_production'=>false,
			)
		);
		$param = array_merge($defined,$data);
		$res = $this->request_post($param);		
		if ($res === false) {
			return false;
		}	
		$res_arr = json_decode($res, true);	
		print_r($res_arr);die;
	    $res_arr['errmsg']= "没有错误信息";
		switch (intval($res_arr['error']['code'])) {
			case 0:
			    $res_arr['errmsg'] = '发送成功';
				//echo '发送成功';			    
				break;
			case 10:
			    $res_arr['errmsg'] = '系统内部错误';
				//echo '系统内部错误';
				break;
			case 1001:
			    $res_arr['errmsg'] = '只支持 HTTP Post 方法，不支持 Get 方法';
				//echo '只支持 HTTP Post 方法，不支持 Get 方法';
				break;
			case 1002:
				$res_arr['errmsg'] = '缺少了必须的参数';
				//echo '缺少了必须的参数';
				break;
			case 1003:
				$res_arr['errmsg'] = '参数值不合法';
				//echo '参数值不合法';
				break;
			case 1004:
				$res_arr['errmsg'] = '验证失败';
				//echo '验证失败';
				break;
			case 1005:
				$res_arr['errmsg'] = '消息体太大';
				//echo '消息体太大';
				break;
			case 1007:
				$res_arr['errmsg'] = 'receiver_value 参数 非法';
				//echo 'receiver_value 参数 非法';
				break;
			case 1008:
				$res_arr['errmsg'] = 'appkey参数非法';
				//echo 'appkey参数非法';
				break;
			case 1010:
				$res_arr['errmsg'] = 'msg_content 不合法';
				//echo 'msg_content 不合法';
				break;
			case 1011:
				$res_arr['errmsg'] = '没有满足条件的推送目标';
				//echo '没有满足条件的推送目标';
				break;
			case 1012:
				$res_arr['errmsg'] = 'iOS 不支持推送自定义消息。只有 Android 支持推送自定义消息';
				//echo 'iOS 不支持推送自定义消息。只有 Android 支持推送自定义消息。';
				break;
			default:
				//echo '调用成功';
				break;
		}		
		/*$msg_content = json_decode($msg_content,true);	
		$created = time();
		$created =date("Y-m-d H:i:s",$created);			
		$sql = "INSERT INTO  ".DB_NAME.".`".DB_TAB."` (`id` ,`sendno` ,`n_title` ,`n_content` ,`errcode` ,`errmsg` ,`total_user` ,`send_cnt` ,`created`)VALUES ( NULL ,'".$sendno."','".$msg_content['n_title']."','".$msg_content['n_content']."', '".$res_arr['errcode']."', '".$res_arr['errmsg']."', '',  '','".$created."')";	
		$query = mysql_query($sql);		*/
				
		if (intval($res_arr['error']['code'])==0){	
				$str= "<li>第".$res_arr['sendno']."条发送".$res_arr['errmsg']."！</li>";
			}else{						
				$str= "<li>第".$res_arr['sendno']."条发送失败：".$res_arr['errmsg']."</li>";
			}
	    print_r($str);
	}
	
}

?>