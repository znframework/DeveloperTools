<?php namespace Project\Controllers;

//------------------------------------------------------------------------------------------------------------
// INITIALIZE
//------------------------------------------------------------------------------------------------------------
//
// Author   : ZN Framework
// Site     : www.znframework.com
// License  : The MIT License
// Copyright: Copyright (c) 2012-2016, znframework.com
//
//------------------------------------------------------------------------------------------------------------

use Folder, Arrays, Form, Config, Route, Validation, Session;

class Initialize extends Controller
{
    //--------------------------------------------------------------------------------------------------------
    // Main
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $params NULL
    //
    //--------------------------------------------------------------------------------------------------------
    public function main(String $params = NULL)
    {
        $ips = Config::get('Dashboard', 'ip');

        if( ! Arrays::valueExists($ips, ipv4()) )
        {
            Route::redirectInvalidRequest();
        }

        define('LANG', lang('Dashboard'));
        define('DASHBOARD_VERSION', '1.0.0');

        $projects = Folder::files(PROJECTS_DIR, 'dir');
        $projects = Arrays::deleteElement($projects, CURRENT_PROJECT);
        $projects = Arrays::combine($projects, $projects);
        $default  = PROJECTS_CONFIG['directory']['default'];

        $currentProject = Session::select('project');

        define('PROJECT_LIST', $projects);
        define('SELECT_PROJECT', ! empty($currentProject) ? $currentProject : DEFAULT_PROJECT);
        define('SELECT_PROJECT_DIR', PROJECTS_DIR . SELECT_PROJECT .DS);
        define('LANGUAGES', ['EN', 'TR']);
        define('IS_CONTAINER', PROJECTS_CONFIG['containers'][SELECT_PROJECT] ?? FALSE);

        $menus['home']          = ['icon' => 'home',       'href' => 'home/main'];
        $menus['controllers']   = ['icon' => 'database',   'href' => 'generate/model'];

        if( IS_CONTAINER === FALSE )
        {
            $menus['models']    = ['icon' => 'database',   'href' => 'generate/model'];
            $menus['migrations']= ['icon' => 'cubes',      'href' => 'generate/migration'];
        }

        $menus['sqlConverter']  = ['icon' => 'refresh',    'href' => 'system/converter'];
        $menus['documentation'] = ['icon' => 'book',       'href' => 'home/docs'];
        $menus['systemLogs']    = ['icon' => 'cogs',       'href' => 'system/log'];
        $menus['systemBackup']  = ['icon' => 'floppy-o',   'href' => 'system/backup'];
        $menus['systemInfo']    = ['icon' => 'info',       'href' => 'system/info'];
        
        if( IS_CONTAINER === FALSE )
        {
            $menus['terminal']      = ['icon' => 'terminal',   'href' => 'system/terminal'];
        }

        define('MENUS', $menus);
    }
}
