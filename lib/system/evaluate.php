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
        $option = $request->args->{'!'};
        
        if($option === 'console.js') {
            echo file_get_contents(__DIR__ . "/console.js");
            exit;
        }
        
        /**
         * Execute Code via Console
         */
        else if($option == 'exec') {
            if(isset($_COOKIE['__sphp_session'])) {
                $session = md5($_COOKIE['__sphp_session']);
            } else {
                $session = md5(rand().rand().rand());
                setcookie('__sphp_session', $session);
                $session = md5($session);
            }
            $file = __DIR__ . "/../../data/$session.sps";
            if(file_exists($file)) {
                $entity = unserialize(file_get_contents($file));
            } else {
                $entity = new stdClass;
                $entity->session = $session;
            }
            echo "<pre>[in] " . $request->form->{'code'} . "</pre>";
            ob_start();
            $exec = construct(CodeType);
            $exec->entity = $entity;
            $exec->code = $request->form->{'code'};
            $exec->label = "input";
            $exec->entity = $entity;
            $output = property($exec, 'run');
            $str = ob_get_clean();
            if(null !== $output) {
                dump($output, false);
            }
            echo "<pre>";
            echo htmlspecialchars($str);
            echo "</pre>";
            file_put_contents($file, serialize($entity));
            exit;
        }
        
        $path = $request->path;
        echo "<head><title>SimplifiedPHP: " . ucfirst($option) . " $path</title></head>";
        $system = construct(SystemType);
        $style = property($system, '__css__');
        $style = '<style>' . $style . '</style>';
        property($style, 'print');
        _system_inspect($option);
        echo '<div id="sphp-content">';
        switch($option) {
            case 'tests':
                echo '</div><div>';
                $test = construct(TestType);
                #$apply = property($test, '__apply_list__');
                #$apply(__DIR__ . '/tests');
                #$html = property($test, '__html__');
                $html = "<i>Testing Coming Soon</i>";
                property($html, 'print');
                break;
            case 'stack':
                $code->stack = property($code, 'parse');
                dump($code->stack, false);
                break;
            case 'code':
                $html = property($code, '__html__');
                property($html, 'print');
                break;
            case 'request':
                dump($request, false);
                break;
            case 'entity':
                $code->stack = property($code, 'parse');
                ob_start();
                $entity = new stdClass;
                _code_apply_stack($code->stack, $entity);
                ob_end_clean();
                dump($entity, false);
                break;
            case 'output':
                echo '</div><div>';
                ob_start();
                property($code, 'run');
                $str = ob_get_clean();
                echo "<pre>";
                echo htmlspecialchars($str);
                echo "</pre>";
                break;
            case 'render':
                echo '</div><div>';
                property($code, 'run');
                break;
        }
        
        echo '</div>';
        _system_time_js();
        return;
    }
    
    /**
     * Execute SimplifiedPHP file
     */
    property($code, 'run');
}

/**
 * System Inspect Switcher
 */
function _system_inspect($selected) {
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
    echo '<textarea id="sphp-console" disabled="disabled" placeholder=""></textarea>';
    echo '<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>';
    echo '<script src="?!=console.js"></script>';
}

/**
 * System Time Output JavaScript
 */
function _system_time_js() {    
    $ms = number_format(1000 * (microtime(true) - TimerStart), 1);
    $script = "<script>document.getElementById('sphp-debug-switch').innerHTML += ' &middot; $ms ms';</script>";
    property($script, 'print');
}
