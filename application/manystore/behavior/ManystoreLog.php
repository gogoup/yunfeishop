<?php

namespace app\manystore\behavior;

class ManystoreLog
{
    public function run(&$params)
    {
        if (request()->isPost() && config('fastadmin.auto_record_log')) {
            \app\manystore\model\ManystoreLog::record();
        }
    }
}
