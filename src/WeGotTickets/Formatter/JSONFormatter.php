<?php

namespace WeGotTickets\Formatter;

use WeGotTickets\Formatter\Formatter;

class JSONFormatter implements \WeGotTickets\Formatter\Formatter {

    public function format($data){
        return json_encode($data);
    }

}