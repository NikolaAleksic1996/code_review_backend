<?php

namespace App\Enum;

enum MessageStatusType: string
{
    case SENT = 'sent';
    case READ = 'read';
}