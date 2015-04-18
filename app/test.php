<?php

class MailService
{
    public function fire($job, $data)
    {
        Log::info('Mail job executed. data: '.print_r($data, 1));
        $job->delete();
    }
}
