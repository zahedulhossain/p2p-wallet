<?php

namespace App\Models\Enums;

enum PaymentStatus: string
{
    case Requested = 'requested';
    case Approved = 'approved';
    case Declined = 'declined';
}
