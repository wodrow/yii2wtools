<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-7-11
 * Time: 下午5:14
 */

namespace wodrow\yii2wtools\tools;


use yii\log\FileTarget;
use yii\log\Logger;

class Tools
{
    /**
     * 调试输出|调试模式下
     * @param  mixed $test 调试变量
     * @param  int $style 模式
     * @param  int $stop 是否停止
     * @return void       浏览器输出
     * @author wodrow <wodrow451611cv@gmail.com | 1173957281@qq.com>
     */
    public static function _vp($test, $stop = 0, $style = 0)
    {
        $outDir = \Yii::getAlias('@runtime');
        switch ($style) {
            case 0:
                echo "<pre>";
                echo "<br><hr>";
                var_dump($test);
                echo "</pre>";
                break;
            case 1:
                echo "<pre>";
                echo "<br><hr>";
                var_dump($test);
                echo "<hr/>";
                for ($i = 0; $i < 100; $i++) {
                    echo $i . "<hr/>";
                }
                echo "</pre>";
                break;
            case 2:
                file_put_contents($outDir . '/_vp.log', "\r" . var_export($test, true));
                break;
            case 3:
                file_put_contents($outDir . '/_vp.log', "\r\r" . var_export($test, true), FILE_APPEND);
                break;
        }
        if ($stop != 0) {
            exit("<hr/>");
        }
    }

    /**
     * @param $msg
     * @param string $log_name
     * @throws
     */
    public static function log($msg, $log_name = "app")
    {
        $log = New FileTarget();
        $log->logFile = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "{$log_name}.log";
        $log->messages[] = [$msg, Logger::LEVEL_INFO, 'tool-log', time()];
        $log->export();
    }

    public static function curlPost($url, $param, $post_file = false)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    public static function getOutCache($data)
    {
        ob_start();
        var_dump($data);
        $x = ob_get_contents();
        ob_end_clean();
        return $x;
    }

    /**
     * 由身份证获取性别和出生年月日
     * @param $card
     * @return array
     */
    public static function getSexBirthFromIDCard($card)
    {
        $birth = substr($card, 6, 8);
        if (15 == strlen($card)) {
            $sex = substr($card, 15, 1) % 2 == 0 ? '女' : '男';
        } else {
            $sex = substr($card, 16, 1) % 2 == 0 ? '女' : '男';
        }
        return array(
            'birth' => $birth,
            'sex' => $sex
        );
    }

    /**
     * 检测网络是否连接
     * @param $url
     * @return bool
     */
    public static function varifyUrl($url)
    {
        $check = @fopen($url, "r");
        if ($check) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }


    /**
     * 阿拉伯数字金额转汉字大写
     *@param String Int $num 要转换的小写数字或小写字符串
     *@returnreturn 大写汉字
     *小数位为两位
     */
    public static function num2rmb($num){
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num)-1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int)$num;
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j-3;
                $slen = $slen-3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c)-3, 3) == '零') {
            $c = substr($c, 0, strlen($c)-3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零元整";
        }else{
            return $c . "整";
        }
    }
    /**
     * 打印任意多参数并停止
     */
    public static function stop()
    {
        $arr = func_get_args();
        foreach ($arr as $k => $v) {
            var_dump($v);
        }
        exit;
    }

    /**
     * @param $msg
     * @param null $log_name
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\log\LogRuntimeException
     */
    public static function yiiLog($msg, $log_name = null)
    {
        if (!$log_name){
            $log_name = \Yii::$app->controller->route;
        }else{
            $log_name = \Yii::$app->controller->route.DIRECTORY_SEPARATOR.$log_name;
        }
//        \wodrow\yii2wtools\tools\Tools::log($msg, $log_name);
        $log = New FileTarget();
        $log->logFile = \Yii::getAlias('@common/runtime/logs/') . \Yii::$app->id . DIRECTORY_SEPARATOR . "{$log_name}.log";
        $dir = dirname($log->logFile);
        if (!is_dir($dir))FileHelper::createDirectory($dir);
        $log->messages[] = [$msg, Logger::LEVEL_INFO, 'tool-log', time()];
        $log->export();
    }

    /**
     * 获取文件列表
     * @param string $dir
     * @param bool $recursive
     * @return array
     */
    public static function listDir($dir, $recursive = true)
    {
        $result = array();
        if (is_dir($dir)) {
            $file_dir = scandir($dir);
            foreach ($file_dir as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                } else {
                    $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                    array_push($result, $filePath);
                    if (is_dir($filePath) && $recursive){
                        $result = array_merge($result, self::listDir($filePath, $recursive));
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 判断是否为时间戳
     * @param int $timestamp
     * @return bool
     */
    public static function isTimestamp($timestamp)
    {
        if (!is_numeric($timestamp)){
            return false;
        }else{
            $timestamp = intval($timestamp);
        }
        if (strtotime(date('Y-m-d H:i:s', $timestamp)) === $timestamp) {
            return true;
        } else return false;
    }

    /**
     * 删除目录下的所有文件和文件夹
     * @param string $path
     */
    public static function deldir($path){
        //如果是目录则继续
        if(is_dir($path)){
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            foreach($p as $val){
                //排除目录中的.和..
                if($val !="." && $val !=".."){
                    $pv = $path . DIRECTORY_SEPARATOR . $val;
                    //如果是目录则递归子目录，继续操作
                    if(is_dir($pv)){
                        //子目录中操作删除文件夹和文件
                        self::deldir($pv);
                        //目录清空后删除空文件夹
                        rmdir($pv);
                    }else{
                        //如果是文件直接删除
                        unlink($pv);
                    }
                }
            }
        }
    }

    /**
     * 删除目录
     * @param string $path
     */
    public static function removeDir($path)
    {
        self::deldir($path);
        rmdir($path);
    }

    /**
     * 生成不带横杠的UUID
     * @return string
     */
    public static function genuuid(){
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @param $str
     * @return bool
     */
    public static function checkMobile($str)
    {
//        $pattern = "/^(13|15)d{9}$/";
        $pattern = "/^1[345678]{1}\d{9}$/";
        if (preg_match($pattern, $str))
        {
            Return true;
        }
        else
        {
            Return false;
        }
    }

    /**
     * 获取文件夹大小
     *
     * @param string $dir 根文件夹路径
     * @return int
     */
    public static function getDirSize($dir)
    {
        $handle = opendir($dir);
        $sizeResult = 0;
        while (false !== ($folderOrFile = readdir($handle))) {
            if ($folderOrFile != "." && $folderOrFile != "..") {
                if (is_dir("$dir/$folderOrFile")) {
                    $sizeResult += self::getDirSize("$dir/$folderOrFile");
                } else {
                    $sizeResult += filesize("$dir/$folderOrFile");
                }
            }
        }
        closedir($handle);
        return $sizeResult;
    }

    /**
     * 判断字符串是否为json，若是输出json
     * @param string $str Json字符串
     * @param bool $assoc 是否返回关联数组。默认返回对象
     * @return array|bool|object 成功返回转换后的对象或数组，失败返回 false
     */
    public static function isJson($str, $assoc = true)
    {
        $data = json_decode($str, $assoc);
        if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
            return $data;
        }
        return false;
    }

    /**
     * 转json
     * @param $data
     * @return string Json字符串
     */
    public static function toJson($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 判断字符串是否为query，若是输出query数组
     * @param string $str Json字符串
     * @param bool $assoc 是否返回关联数组。默认返回对象
     * @return array|bool|object 成功返回转换后的对象或数组，失败返回 false
     */
    public static function isQuery($str)
    {
        parse_str($str, $data);
        if (is_array($data) && !empty($data)) {
            return $data;
        }
        return false;
    }

    /**
     * 转query字符串
     * @param $data
     * @return string query字符串
     */
    public static function toQuery($data)
    {
        return urldecode(http_build_query($data));
    }

    /**
     * 处理数组值的外层
     * @param $arr
     * @param string $type
     * @param string $outStr
     * @return mixed
     */
    public static function outerStr($arr, $type = "+", $outStr = "()")
    {
        foreach ($arr as $k => &$v) {
            $v = "({$v})";
        }
        return $arr;
    }

    /**
     * @return string
     */
    public static function getDeviceType()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type ='other';
        //分别进行判断
        if(strpos($agent,'iphone') || strpos($agent,'ipad'))
        {
            $type ='ios';
        }
        if(strpos($agent,'android'))
        {
            $type ='android';
        }
        return$type;
    }

    /**
     * 判断是否命令行
     * @return bool
     */
    public static function isCli()
    {
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }

    /**
     * unicode编码转汉字
     * @param $str
     * @return null|string|string[]
     */
    public static function decodeUnicode($str)
    {
        $reg = <<<REGEXP
/\\\\u([0-9a-f]{4})/i
REGEXP;
        $r = preg_replace_callback($reg, function ($matches){
            $x = mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");
            return $x;
        }, $str);
        return $r;
    }

    /**
     * 时间戳转化为日期格式
     * @param $timestamp
     * @return false|string
     */
    public static function toDate($timestamp = YII_BT_TIME, $format = "Y-m-d H:i:s")
    {
        $t10 = substr($timestamp, 0, 10);
        $mt = str_replace($t10, "", $timestamp);
        $mt = str_replace(".", "", $mt);
        $d10 = date($format, $t10);
        if ($mt){
            $mt = substr($mt, 0, 3);
            $d = $d10.".{$mt}";
        }else{
            $d = $d10;
        }
        return $d;
    }

    /**
     * @param int $len 必须大于等于27
     * @return string
     * @throws \yii\base\Exception
     */
    public static function generateHasDateUniqueString($len = 32)
    {
        $_len = $len - 27;
        if ($_len < 0){
            throw new \Exception("长度异常");
        }
        $str = Tools::toDate(YII_BT_TIME, "YmdHis").uniqid().\Yii::$app->security->generateRandomString($_len);
        return $str;
    }

    /**
    　　  * 下划线转驼峰
    　　  * 思路:
    　　  * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
    　　  * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
    　　  */
    public static function camelize($uncamelized_words, $separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     * @param $camelCaps
     * @param string $separator
     * @return string
     */
    public static function uncamelize($camelCaps,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}