<?php

namespace WeGotTickets\Writer;

use WeGotTickets\Writer\Writer;

class Stdout implements Writer {

    public function write($data)
    {
        $fh = fopen("php://stdout", "wb");
        fwrite($fh, $data, sizeof($data));
        fclose($fh);
    }
}