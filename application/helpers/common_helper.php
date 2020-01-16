<?php
/**
 * Created by PhpStorm.
 * User: 25754
 * Date: 2020/1/15
 * Time: 8:36
 */

/**
 * @Description: base16加密
 * @Author: Yang
 * @param $data
 * @return string
 */
function base16_encode($data)
{
    $result = "";
    $BASE_16_CHARS = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
    for ($i = 0; $i < strlen($data); $i++) {
        $result .= $BASE_16_CHARS[(@ord($data[$i]) & 0xf0) >> 4];
        $result .= $BASE_16_CHARS[@ord($data[$i]) & 0x0f];
    }
    return $result;
}

/**
 * @Description: base16解密
 * @Author: Yang
 * @param $data
 * @return string
 */
function base16_decode($data)
{
    $result = "";
    $len = strlen($data) / 2;
    for ($i = 0; $i < $len; $i++) {
        $result .= chr(intval(substr($data, $i * 2, 2), 16));
    }
    return $result;
}

/**
 * 生成随机字符串
 * @param $length
 * @param string $chars
 * @return string
 */
function random($length, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz')
{
    $hash = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * @Description: 将时间转换为几秒前、几分钟前、几小时前、几天前
 * @Author: Yang
 * @param $the_time 需要转换的时间
 * @return string
 */
function time_tran($the_time)
{
    $now_time = date("Y-m-d H:i:s", time());
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) { // 3天内
                        return floor($dur / 86400) . '天前';
                    } else {
                        return $the_time;
                    }
                }
            }
        }
    }
}