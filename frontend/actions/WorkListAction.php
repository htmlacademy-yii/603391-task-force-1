<?php

namespace frontend\actions;

use Yii;
use yii\base\Action;

class WorkListAction extends Action
{
    public function run()
    {
        $ds = DIRECTORY_SEPARATOR;
        $id = Yii::$app->user->getId();
        $storeFolder = 'uploads/works' . $ds . $id;
        $result = null;
        $files = scandir($storeFolder);
        if (false !== $files) {
            foreach ($files as $file) {
                if ('.' != $file && '..' != $file) {
                    $obj['name'] = $file;
                    $obj['id'] = $id;
                    $obj['size'] = filesize($storeFolder . $ds . $file);
                    $result[] = $obj;
                }
            }
        }

        return json_encode($result);
    }
}