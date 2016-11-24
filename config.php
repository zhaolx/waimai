<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               => 'mysql',     // 数据库类型
    'DB_HOST'               => 'localhost', // 服务器地址
    'DB_NAME'               => 'waimai',          // 数据库名
    'DB_USER'               => 'root',      // 用户名
    'DB_PWD'                => 'zlx5619',          // 密码
    'DB_PORT'               => '3306',        // 端口
    'DB_PREFIX'             => 'gg_',    // 数据库表前缀
    'DB_CHARSET'            => 'utf8',      // 数据库编码默认采用utf8
	'URL_MODEL'             => '0', //URL模式
	'TMPL_ACTION_ERROR'     => 'Public:jump',//默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   => 'Public:jump',//默认错误跳转对应的模板文件
	'site_name'             => '青年菜君',   //站点名字
	'site_keywords'         => '青年菜君，网上送菜',   //站点关键词
	'site_description'      => '青年菜君是一个很牛逼的卖菜的',   //站点描述
	'site_tel'              => '18765927429',   //站点电话
	'site_address'          => '香江路1212号',   //店铺地址
	'site_longitude'        => '120.172954',   //店铺百度地图的经度         http://api.map.baidu.com/lbsapi/getpoint/
	'site_latitude'         => '35.975251',   //店铺百度地图的纬度          http://api.map.baidu.com/lbsapi/getpoint/
	'TMPL_L_DELIM'          =>  '<{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}>',            // 模板引擎普通标签结束标记
);
?>