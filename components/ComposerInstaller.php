<?php
namespace app\components;

use yii\composer\Installer;

class ComposerInstaller extends Installer
{
    public static function initProject($event)
    {
        foreach (['formatAdminLTE'] as $method) {
            call_user_func_array([__CLASS__, $method], [$event]);
        }
    }

    /**
     * 去除AmdinLTE模板的google api, 原因嘛....
     */
    public static function formatAdminLTE($event)
    {
        $composer = $event->getComposer();
        $extra = $composer->getPackage()->getExtra();
        if (isset($extra['asset-installer-paths']['bower-asset-library'])){
            $bowerAssetDir = $extra['asset-installer-paths']['bower-asset-library'];
            $cssFile = rtrim($bowerAssetDir, '/') . '/adminlte/css/AdminLTE.css';
            if (file_exists($cssFile)) {
                $content = file_get_contents($cssFile);
                $regexp = '/(@import) (url)\(([^>]*?)\);/';
                if (preg_match($regexp, $content)) {
                    $content = preg_replace($regexp, '', $content);
                    file_put_contents($cssFile, $content);
                    echo "'AdminLTE.css' google api replace success.\n";
                }
            } else {
                echo "'{$cssFile}' file is not exists.\n";
            }
        } else {
            echo "'npm-asset-library' is not set.\n";
        }
    }
}