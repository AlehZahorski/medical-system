<?php

namespace spec\App\Services;

use App\Services\BasicPatientImportService;
use PhpSpec\ObjectBehavior;

class BasicPatientImportServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BasicPatientImportService::class);
    }

    function it_parses_valid_date_string()
    {
        $carbon = $this->parseDate('01.09.2025');
        $carbon->shouldBeAnInstanceOf(\Carbon\Carbon::class);
        $carbon->format('Y-m-d')->shouldReturn('2025-09-01');
    }
}
