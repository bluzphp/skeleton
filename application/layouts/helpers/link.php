<?php

return function ($src = null, $rel = 'stylesheet') {
    if (null === $src) {
        echo $this->headLinkFiles;
    } else {
        $this->headLinkFiles .= '<link type="text/css" href="' . $this->baseUrl($src) . '" rel="' . $rel .'"/>'."\n";
    }
};