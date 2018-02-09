<?php
// More detail in yii2-httpclient : https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide/basic-usage.md
return [
	'class' => 'yii\httpclient\Client',
	'detectMimeType' => true, // automatically transform request to data according to response Content-Type header
	'requestOptions' => [
	// see guzzle request options documentation
	],
	'requestHeaders' => [
	// specify global request headers (can be overrided with $options on making request)
	],
];