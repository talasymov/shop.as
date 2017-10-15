<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/App/Config/Define.php");

foreach (ReturnListFiles(DIR_APP . "Library/Sweane/") as $path)
{
    require_once $path;
}

foreach (ReturnListFiles(DIR_MODELS . "Helpers/") as $path)
{
    require_once $path;
}

if(!BF::ClearCode("language", "str", "cookie"))
{
    setcookie("language", "ru", time() + 86400, "/");
}

require_once(DIR_APP . "Core/Controller.php");
require_once(DIR_APP . "Core/Model.php");
require_once(DIR_APP . "Core/Route.php");
require_once(DIR_APP . "Core/View.php");

function ReturnListFiles($dir)
{
    $message = [];

    if(is_dir($dir))
    {
        if($listDir = opendir($dir))
        {
            while(($file = readdir($listDir)) !== false)
            {
                if(file_exists($dir . $file))
                {
                    if(filetype($dir . $file) == "file")
                    {
                        array_push($message, $dir . $file);
                    }
                }
            }
            closedir($listDir);
        }
    }

    return $message;
}

$route = new Route();
$route->Init();