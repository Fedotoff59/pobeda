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
define('DB_PASSWORD', 'permblago');

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
define('AUTH_KEY',         'T7mHi/s<1CS<67uoO{~K;ePJmWEl8X?hgf5 W_sZyOokvKz9}UfeCds(U/zv+bhJ');
define('SECURE_AUTH_KEY',  '%J$Q^:#%m><me+%J0wQ!y<Yc8-|vJ1]xMzn},<v3y(v`0-f$<KPv1oU|U!N4kd6F');
define('LOGGED_IN_KEY',    '?Bv~DwPzVtW?s_XrfqRfS5G~;Z#RsIGRs!h6_#6 wT<V*xppCyV G2GLK)Uw|Mw,');
define('NONCE_KEY',        'UQo#aBH%4LriFp#]Ly-G363rR>P_r;t7i7XM,y166n2V>2lJ40WKXow-l>4iY9-y');
define('AUTH_SALT',        'T6b5l.Q@2S6K$0@^h=L`:4|v/3-gFI;(vd1$Dx|KT8dD=6b!$31)Z/rsw-Td]Rfh');
define('SECURE_AUTH_SALT', 'EfF1|`PaP$HY5V}E&eK6p/>Gi!~&$oH<,M4-eVr6v%XCTwcxATzTmPKmk8^Qgl*X');
define('LOGGED_IN_SALT',   '+V%p+@9QlB@eV@9eNe@|m{7rxW~ Ef_qnG[eUBrS;<6|`dbCd:n,A4+#2u-21M|W');
define('NONCE_SALT',       '[d(b,D#ZtS7,p9K<o|NgU.h0N,<i[-<fc`2rXS@,xMN[!v+/I%sr1Z(By=a!X15;');

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
