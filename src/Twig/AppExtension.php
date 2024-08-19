<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use DateTime;
use DateTimeZone;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('convertToSingaporeTimezone', [$this, 'convertToSingaporeTimezone']),
        ];
    }

    public function convertToSingaporeTimezone(DateTime $dateTime)
    {
        // dd($dateTime);
        // $singaporeTimezone = new DateTimeZone('Asia/Singapore');
        // $dateTime->setTimezone($singaporeTimezone);
        // return $dateTime->format('Y-m-d H:i:s');
        dd('before here===', $dateTime);
        if ($dateTime instanceof \DateTime) {
            $dateTime = clone $dateTime; // Avoid modifying the original object
            $singaporeTimezone = new DateTimeZone('Asia/Singapore');
            $dateTime->setTimezone($singaporeTimezone);
            // dd('here===', $dateTime);
            return $dateTime->format('Y-m-d H:i:s');
        }
        return '';

        // new DateTime($dateTime, new DateTimeZone('Asia/Singapore')); // Default timezone
    }
}
