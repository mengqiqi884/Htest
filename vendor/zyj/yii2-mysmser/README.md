# yii2-smser
yii2短信扩展
# usage
在组件中配置：
```php
	#网信通
	'smser'=>[
		'class'=>'zyj\smser\Wxtsms',
		'username'=>'username',
		'password'=>'password',
	]
	#云片
	'smser'=>[
		'class'=>'zyj\smser\Ypsms',
		'apikey'=>'your apikey',
	]
```
#install
	composer require zyj/yii2-mysmser	
