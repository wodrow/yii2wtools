<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/4/8
 * Time: 12:09
 */

namespace wodrow\yii2wtools\tools;


use creocoder\flysystem\Filesystem;
use yii\base\Exception;

class BackUp
{
    /**
     * 备份文件
     * @param Filesystem $master
     * @param Filesystem $slave
     * @param string $log_name_pre
     */
    public static function fileSysBackup($master, $slave, $failed_log_name_pre = "failed_backup_files_", $is_show_exec_log = 1, $is_show_end = 1)
    {
        $slc = $master->listContents('', true);
        foreach ($slc as $k => $v){
            $type = $v['type'];
            $path = $v['path'];
            if ($is_show_exec_log)var_dump($path);
            if ($type != 'dir'){
                if ($slave->has($path)){
                    if ($slave->getSize($path) < $master->getSize($path)){
                        $c = $master->readStream($path);
                        $slave->updateStream($path, $c);
                        if ($is_show_exec_log)var_dump("update");
                    }elseif ($slave->getSize($path) == $master->getSize($path)){
                        if ($is_show_exec_log)var_dump("has");
                    }else{
                        Tools::log($path, $failed_log_name_pre.date("YMD"));
                        if ($is_show_exec_log)var_dump("size error");
                    }
                }else{
                    $c = $master->readStream($path);
                    $slave->writeStream($path, $c);
                    if ($is_show_exec_log)var_dump("write");
                }
            }
        }
        if ($is_show_end){
            echo 1;
        }
    }

    /**
     * @param string $backupFileRoot
     * @param \yii\db\Connection $db
     * @throws
     */
    public static function dbBackup($backupFileRoot, $db)
    {
        $exec_str = "";
//        $backupFileRoot = \Yii::getAlias("@uploads_root");
        $dsn = $db->dsn;
        $_a1 = ArrayHelper::str2arr($dsn, ":");
        $_db_type = $_a1[0];
        switch ($_db_type){
            case "mysql":
                $exec_str .= "mysqldump -u{$db->username} -p{$db->password} ";
                $exec_str_end = "";
                $_db_dsn_confs = ArrayHelper::str2arr($_a1[1], ";");
                foreach ($_db_dsn_confs as $k => $v) {
                    $_a2 = ArrayHelper::str2arr($v, "=");
                    $_k = $_a2[0];
                    $_v = $_a2[1];
                    switch ($_k){
                        case "host":
                            $exec_str .= "-h{$_v} ";
                            break;
                        case "port":
                            $exec_str .= "-P{$_v} ";
                            break;
                        case "dbname":
                            $exec_str_end .= "{$_v}>{$backupFileRoot}/{$_v}.sql";
                            break;
                        default:
                            break;
                    }
                }
                $exec_str .= $exec_str_end;
                exec($exec_str);
                break;
            default:
                throw new Exception("没有找到数据库类型:{$_db_type}");
                break;
        }
    }
}