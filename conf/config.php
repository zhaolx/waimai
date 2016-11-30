<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               => 'mysql',     // 数据库类型
    'DB_HOST'               => 'adodllqf.2258lan.dnstoo.com', // 服务器地址
    'DB_NAME'               => 'zlxwaimai',          // 数据库名
    'DB_USER'               => 'zlxwaimai_f',      // 用户名
    'DB_PWD'                => 'zlx5619',          // 密码
    'DB_PORT'               => '3306',        // 端口
    'DB_PREFIX'             => 'w_',    // 数据库表前缀
    'DB_CHARSET'            => 'utf8',      // 数据库编码默认采用utf8
	'URL_MODEL'             => '0', //URL模式
	'TMPL_ACTION_ERROR'     => 'Public:jump',//默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   => 'Public:jump',//默认错误跳转对应的模板文件
	'site_name'             => '青年菜君',   //站点名字
	'site_keywords'         => '青年菜君，网上送菜',   //站点关键词
	'site_description'      => '青年菜君是一个很牛逼的卖菜的',   //站点描述
	'site_tel'              => '13800138000',   //站点电话
	'site_address'          => 'xx路1212号',   //店铺地址
	'site_longitude'        => '113.335328',   //店铺百度地图的经度         http://api.map.baidu.com/lbsapi/getpoint/
	'site_latitude'         => '23.142162',   //店铺百度地图的纬度          http://api.map.baidu.com/lbsapi/getpoint/
	'TMPL_L_DELIM'          =>  '<{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}>',            // 模板引擎普通标签结束标记
	'captcha_id'			=>	'bed0802c1a3a3bb99f8afd339dcfd52f',  //验证码id
	'private_key'			=>	'684570b6ba2bdf14a52769515ccfd7d6 ',  //验证码key
);
?>