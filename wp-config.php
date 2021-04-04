<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'wordpress' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'root' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'root' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'RzyszUiDfrJfs>GH-@4Y%9)ixoE{[C{/e<ryIoooL/Y+q4JsUSdTyQ%PyYwRwj^c' );
define( 'SECURE_AUTH_KEY',  '3$S:&8~TT!` ,>*m(I2&tK>HCJWrVh~%F=i^+g|BGP}}NWpJ&BHTtRu-MD3@&1;g' );
define( 'LOGGED_IN_KEY',    'pF-D`x 5-q&v+Q7bf1dZhH=XiEg@Ea[qg(lsynPVE3nL nA?qz(-p,~AJ>8/~%A}' );
define( 'NONCE_KEY',        'Wi5nY`5_`(BQJ;-pv4]_=8)j&C!G#M+(B4GhYg<SkKWJ+.VC^mYQxFMDTlXGF:{T' );
define( 'AUTH_SALT',        'lsWNuS{1_vkT(Ytp-L@*|V*&Goq@*~x;Q5C&BBEf$^K$n#<| WVh>CT,OfR }SU?' );
define( 'SECURE_AUTH_SALT', 'vJlV&y`HZ%+G__kV7c_xXV!R8Od0C*>xs.[yo?LYVYOhFWZpINjB[s Eb;< ?pcE' );
define( 'LOGGED_IN_SALT',   'H.#j6{|({Ybdxkuh>.yoi^Bn#@w<JE~GiD@Qm|JD-It!^8d%1 WEx8,/-:Oyb~:V' );
define( 'NONCE_SALT',       '@m&BX.A>MYw}#3aHMK0ST Suv!(&soi){zm+Vn_J`o+h<EcY{5kW~+La$/t!}6.7' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
