# yii2-ww-wangeditor
使用版本为3.1.1

[wangEditor 官网](http://www.wangeditor.com/)

安装
------------

```
php composer.phar require wodrow/yii2-ww-wangeditor "dev-master"
```

使用
-----

配置
```php
'modules' => [
    'wangeditor' => [
        'class' => \wodrow\yii2wwwangeditor\WangEditorModule::className(),
    ],
],
```

视图
```php
echo \wodrow\yii2wwwangeditor\widgets\WangEditorWidget::widget([
    'name' => 'content',
    'clientJs' => $clientJs
]);
```

配置
-----

默认已经配置好，可以直接使用，注意权限和上传限制

## clientJs 客户端 js 扩展

可替换变量：

 - `{name}`:editor实例
 - `{hiddenInputId}`:隐藏输入域的id

配置举例：

```php
'clientJs' => <<<JS
// 设置上传文件路径
{name}.customConfig.uploadImgServer = '/upload/wang';
// 将富文本的数据更改后加入到隐藏域，该方法默认已经配置，不需要重复写，可以覆盖写
{name}.customConfig.onchange = function (html) {
   $('#{hiddenInputId}').val(html);
}
JS
```

更多配置见[官网配置](https://www.kancloud.cn/wangfupeng/wangeditor3/332599)

预览效果
------
![wangeditor](https://i.loli.net/2019/02/13/5c63c5d3ac6dd.png)