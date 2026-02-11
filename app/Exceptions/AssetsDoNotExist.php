<?php

namespace App\Exceptions;

class AssetsDoNotExist extends \Exception
{
    public function __construct(public array $missingIds)
    {
        parent::__construct(trans('admin/hardware/message.does_not_exist'));
    }
}