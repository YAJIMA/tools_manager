<?php
/**
 * 初期設定ファイルです。
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-7月-16
 * Time: 16:43
 */

/**
 * 設定はここから
 */

// インデックスチェック履歴の保存数（月）
$indexmonth = 12;

// Googleキャッシュ日からの経過日数（日）
$google_cache_days0 = 40;
$google_cache_days1 = 40;
$google_cache_days2 = 40;

// So-netインデックスチェック
$index_check0 = 0;
$index_check1 = 2;
$index_check2 = 1;

// 優先度のチェックボックス
// 優先度：高
$google_cache_days_check0 = "on";
$index_check_check0 = "off";
// 優先度：中
$google_cache_days_check1 = "off";
$index_check_check1 = "on";
// 優先度：低
$google_cache_days_check2 = "off";
$index_check_check2 = "on";

// データベース接続設定
$db_hostname = 'localhost';
$db_username = 'root';
$db_password = 'zbGMI0S2hi';
$db_database = 'tools';
$db_prefix = 'tm_';
$db_charset = 'utf8';
$db_collation = 'utf8_general_ci';

/**
 * 設定はここまで
 */


defined('INDEXMONTH') OR define('INDEXMONTH', $indexmonth);
defined('GOOGLECACHEDAYS0') OR define('GOOGLECACHEDAYS0', $google_cache_days0);
defined('GOOGLECACHEDAYS1') OR define('GOOGLECACHEDAYS1', $google_cache_days1);
defined('GOOGLECACHEDAYS2') OR define('GOOGLECACHEDAYS2', $google_cache_days2);
defined('INDEXCHECK0') OR define('INDEXCHECK0', $index_check0);
defined('INDEXCHECK1') OR define('INDEXCHECK1', $index_check1);
defined('INDEXCHECK2') OR define('INDEXCHECK2', $index_check2);
defined('GOOGLECACHEDAYSCHECK0') OR define('GOOGLECACHEDAYSCHECK0', $google_cache_days_check0);
defined('GOOGLECACHEDAYSCHECK1') OR define('GOOGLECACHEDAYSCHECK1', $google_cache_days_check1);
defined('GOOGLECACHEDAYSCHECK2') OR define('GOOGLECACHEDAYSCHECK2', $google_cache_days_check2);
defined('INDEXCHECKCHECK0') OR define('INDEXCHECKCHECK0', $index_check_check0);
defined('INDEXCHECKCHECK1') OR define('INDEXCHECKCHECK1', $index_check_check1);
defined('INDEXCHECKCHECK2') OR define('INDEXCHECKCHECK2', $index_check_check2);
defined('DB_HOSTNAME') OR define('DB_HOSTNAME',$db_hostname);
defined('DB_USERNAME') OR define('DB_USERNAME',$db_username);
defined('DB_PASSWORD') OR define('DB_PASSWORD',$db_password);
defined('DB_DATABASE') OR define('DB_DATABASE',$db_database);
defined('DB_PREFIX') OR define('DB_PREFIX',$db_prefix);
defined('DB_CHARSET') OR define('DB_CHARSET',$db_charset);
defined('DB_COLLATION') OR define('DB_COLLATION',$db_collation);

// defined('') OR define('',);