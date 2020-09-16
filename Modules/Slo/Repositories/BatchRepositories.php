<?php


namespace Modules\Slo\Repositories;


use Modules\Slo\Entities\Batch;

class BatchRepositories
{
    public function generateBatchCode()
    {
        $batch_code = Batch::all()->max('batch_code');

        if ($batch_code != null) {
            $batch_code = intval($batch_code);

            $batch_code++;

            if ($batch_code < 100) {
                $batch_code = '00' . $batch_code;
            }
        } else {
            $batch_code = "001";
        }

        return $batch_code;
    }
}
