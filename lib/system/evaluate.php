<?php

function _system_evaluate() {
    $code = construct(CodeType);
    $code->code = ob_get_clean();
    $code->label = $_SERVER['SCRIPT_FILENAME'];
    
    /**
     * Debug params
     */
    $request = construct('Request');
    if(isset($request->args->{'!'})) {
        switch($request->args->{'!'}) {
            case 'tests':
                $test = construct(TestType);
                #$apply = property($test, '__apply_list__');
                #$apply(__DIR__ . '/tests');
                #$html = property($test, '__html__');
                $html = "<i>Testing Coming Soon</i>";
                $system = construct(SystemType);
                $style = property($system, '__css__');
                $style = '<style>' . $style . '</style>';
                _system_inspect('stack');
                property($style, 'print');
                property($html, 'print');
                return;
            case 'stack':
                _system_inspect('stack');
                $code->stack = property($code, 'parse');
                dump($code->stack);
                return;
            case 'code':
                _system_inspect('code');
                $system = construct(SystemType);
                $style = property($system, '__css__');
                $style = '<style>' . $style . '</style>';
                $html = property($code, '__html__');
                property($style, 'print');
                property($html, 'print');
                return;
            case 'request':
                _system_inspect('request');
                dump($request);
                return;
            case 'entity':
                _system_inspect('entity');
                $entity = new stdClass;
                $code->stack = property($code, 'parse');
                ob_start();
                _code_apply_stack($code->stack, $entity);
                ob_end_clean();
                dump($entity);
                return;
            case 'output':
                _system_inspect('output');
                ob_start();
                property($code, 'run');
                $str = ob_get_clean();
                echo "<pre>";
                echo htmlspecialchars($str);
                echo "</pre>";
                return;
            case 'render':
                _system_inspect('render');
                property($code, 'run');
                return;
        }
    }
    
    property($code, 'run');
}

/**
 * System Inspect Switcher
 */
function _system_inspect($selected) {
    echo "<style>#sphp-debug-switch {position: relative; top: -1px; right: -1px;
        padding: 8px 8px 9px; margin: -8px -1px 9px -1px; background: #eee;
        border: 1px solid #ccc; font-size: 11px;
        box-shadow: inset 0 -0.25em 1em #ccc;
        font-family: Verdana, Tahoma, 'Lucida Grande', Arial, Ubuntu, sans-serif;
        color: #bbb;
    }
    #sphp-debug-switch a {color: #666; text-decoration: none; padding: 2px 4px 3px;
        border-radius: 3px;}
    #sphp-debug-switch a:hover {background: #ccc; color: #444;}
    #sphp-debug-switch a.sphp-active {background: #333; color: #fff;}
    </style>";
    $modes = explode(" ", "request code stack entity output render tests close");
    $url = $_SERVER['REQUEST_URI'];
    $a = array();
    $re = ';\!\=[a-z0-9]+;';
    foreach($modes as $mode) {
        $c = strpos($url, '!=' . $mode) > -1 ? 'sphp-active' : '';
        $x = '<a href="' .
            preg_replace($re, $mode == 'close' ? '' : '!=' . $mode, $url) . '" class="'.$c.'">';
        $x .= ucfirst($mode) . '</a>';
        $a[] = $x;
    }
    echo '<div id="sphp-debug-switch">SimplifiedPHP ' . Version . '&nbsp; &middot; ';
    echo implode(' &middot; ', $a);
    echo '</div>';
}

