<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); // Loads a single plugin named DebugKit
 */

/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

define('CATALOG', 'catalog');

CakeLog::config('catalog', array(
	'engine' => 'FileLog',
	'types' => array('catalog'),
	'file' => 'catalog',
));

CakePlugin::load('Upload');

// サイト名
Configure::write('App.site_name', '数理技術相談データベース');

// rmのapiキー
Configure::write('App.appid', 'imi-kyushu-u_7GdHahx2he');

// サイトトップの絶対パス
Configure::write('App.site_url', 'https://aimap.imi.kyushu-u.ac.jp/db2/');

CakePlugin::load('CakePdf', array('bootstrap' => true, 'routes' => true));

Configure::write('App.databases', array(
	'0' => '-------',
	'1' => '数学カタログ',
	'2' => '研究者データベース',
	'3' => '研究集会データベース',
	'4' => '講演課題データベース',
	'5' => '研究機関データベース',
	'6' => '研究会場データベース',
	'7' => '研究事例データベース',
));

Configure::write('App.event_type', array(
	'0' => '-------',
	'1' => '公募',
	'2' => '日本数学会',
	'3' => '九州大学',
));

Configure::write('App.event_status', array(
	'0' => '企画申請中',
	'1' => '企画検討中',
	'2' => '企画承認済み',
	'3' => '報告書受付中',
	'4' => '報告書提出済み',
	'5' => '報告書承認（HPに表示）',
	'99' => '企画不採択',
));

Configure::write('App.expense_status', array(
	'0' => '未申請',
	'1' => '未確定',
	'2' => '確定',
));

Configure::write('App.expense_type', array(
	'0' => '----',
	'1' => '国内旅費',
	'2' => '諸謝金',
	'3' => '会議費',
	'4' => 'その他',
));



Configure::write('App.researcher_detail_type', array(
	1 => '研究キーワード',
	2 => '研究分野',
	3 => '経歴',
	4 => '学歴',
	5 => '委員歴',
	6 => '受賞',
	7 => '論文',
	8 => '書籍等出版物',
	9 => '講演・口頭発表等',
	10 => '担当経験のある科目',
	11 => '所属学協会',
	12 => '競争的資金等の研究課題',
	13 => '特許',
	14 => '社会貢献活動',
	15 => 'その他'
));

Configure::write('App.researcher_detail_table', array(
	1 => 'ResearcherResearchKeyword',
	2 => 'ResearcherResearchArea',
	3 => 'ResearcherCareer',
	4 => 'ResearcherAcademicBackground',
	5 => 'ResearcherCommitteeCareer',
	6 => 'ResearcherPrize',
	7 => 'ResearcherPaper',
	8 => 'ResearcherBiblio',
	9 => 'ResearcherConference',
	10 => 'ResearcherTeachingExperience',
	11 => 'ResearcherAcademicSociety',
	12 => 'ResearcherCompetitiveFund',
	13 => 'ResearcherPatent',
	14 => 'ResearcherSocialContribution',
	15 => 'ResearcherOther'
));