<?php

return function ($script = null) {
    if (null === $script) {
        echo $this->headScriptFiles;
        if ($this->headScriptContent) {
            ?>
            <script type="text/javascript">
            <!--
                <?php echo $this->headScriptContent;?>
            //-->
            </script>
            <?php
        }
    } elseif ('.js' == substr($script, -3, 3)) {
        $this->headScriptFiles .= '<script type="text/javascript" src="' . $this->baseUrl($script) .'"></script>'."\n";
    } else {
        $this->headScriptContent .= $script . ';';
    }
};