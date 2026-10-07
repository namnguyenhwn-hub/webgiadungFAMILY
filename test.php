<?php $html = file_get_contents('http://127.0.0.1:8000/'); preg_match_all('/proceedToRealCheckout\((.*?)\)/', $html, $matches); print_r($matches[1]); ?>
