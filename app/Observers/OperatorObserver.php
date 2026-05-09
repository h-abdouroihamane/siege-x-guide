<?php

namespace App\Observers;

use App\Models\Operator;
use App\Support\OperatorJsonWriter;

class OperatorObserver
{
    public function saved(Operator $operator): void
    {
        OperatorJsonWriter::write();
    }

    public function deleted(Operator $operator): void
    {
        OperatorJsonWriter::write();
    }
}
