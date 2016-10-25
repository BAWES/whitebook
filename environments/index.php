<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development [Khalid]' => [
        'path' => 'dev-khalid',
        'setWritable' => [
            'imageserver/runtime',
            'imageserver/web/assets',
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'imageserver/config/main-local.php',
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'admin/config/main-local.php',
        ],
    ],
	'Development [Riyas]' => [
        'path' => 'dev-riyas',
        'setWritable' => [
            'imageserver/runtime',
            'imageserver/web/assets',
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'imageserver/config/main-local.php',
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'admin/config/main-local.php',
        ],
    ],
    'Development [Technoduce]' => [
        'path' => 'dev-technoduce',
        'setWritable' => [
            'imageserver/runtime',
            'imageserver/web/assets',
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'imageserver/config/main-local.php',
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'admin/config/main-local.php',
        ],
    ],
    'Dev-Server' => [
        'path' => 'dev-server',
        'setWritable' => [
            'imageserver/runtime',
            'imageserver/web/assets',
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'imageserver/config/main-local.php',
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'admin/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'imageserver/runtime',
            'imageserver/web/assets',
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'imageserver/config/main-local.php',
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'admin/config/main-local.php',
        ],
    ],
    'Demo' => [
        'path' => 'demo',
        'setWritable' => [
            'imageserver/runtime',
            'imageserver/web/assets',
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'imageserver/config/main-local.php',
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'admin/config/main-local.php',
        ],
    ],
];
