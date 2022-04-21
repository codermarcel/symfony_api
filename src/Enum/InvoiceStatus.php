<?php

enum InvoiceStatus
{
    case Created;
    case Paid;
    case Refunded;
    case Canceled;

//    public function toString() : string {
//        return match ($this)
//        {
//            self::Created => "created",
//            self::Paid => "paid",
//            self::Refunded => "refunded",
//            self::Canceled => "canceled"
//        };
//    }
}
