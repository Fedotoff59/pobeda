<?php
/**
 * Основные параметры WordPress.
 *
 * Этот файл содержит следующие параметры: настройки MySQL, префикс таблиц,
 * секретные ключи и ABSPATH. Дополнительную информацию можно найти на странице
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Кодекса. Настройки MySQL можно узнать у хостинг-провайдера.
 *
 * Этот файл используется скриптом для создания wp-config.php в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать этот файл
 * с именем "wp-config.php" и заполнить значения вручную.
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'pobeda');

/** Имя пользователя MySQL */
define('DB_USER', 'pobeda');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'pobeda');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'T`tl+KcWuJxowh5zksY,BhW.-mIIZ<1}F@}PD1h34!?He,4S`b-nE||V`L.pARS4');
define('SECURE_AUTH_KEY',  '$hL<mALs~cRrzlQ#qIJ)!YFKn?BQ~u,3A}2y0 iM]cl+U)#Ar,^_??C: t4.b!Z}');
define('LOGGED_IN_KEY',    'lMp1&wZ3h>pve_k$#F5_V!U6K9n[XP]+@1]L`%=b|bW{fHb4HnSa+@X?XL[/d-&L');
define('NONCE_KEY',        'Y_Pt9mm28m]sa,RaNONt)q`{Oaz||rTo|6Sk+sO$|9<Vm{2[3}l$j?9:fiLLN:X9');
define('AUTH_SALT',        'LMbc;p7ENSzft)sTHGEV_8P{chW@d2;Z]/V-,f89LGK|}s[(:7uea^Qx<UCEvl`Q');
define('SECURE_AUTH_SALT', 'tI *!wmlywgF4pc!kfmPL=9Hec.knO%w.b*lI3|oxTsfaH|0$9)N_vq|==j6-!8~');
define('LOGGED_IN_SALT',   '4c}QSZCU-)|f+;-~>R5*Na.I)ka.Tvk-^D@V|6Bf|e+-$-o<zR+|*~.wdChLj{*]');
define('NONCE_SALT',       's-&v_u91iim>>Gb-?{euevKr5NoqKH:A~,ebxH$VvY3}}!y> rCv1RsetMC{Nrnj');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
