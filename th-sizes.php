<?php
    /**
    * size: (width, height, crop, whitemargin [, array(gravity=>north, rotate=2 [,?bw?=>1])])
    *
    *  crop  whitemargin  result
    *    0            0  ресайз без изменения соотношения сторон исходной картинки
    *    0            1  дополнение предыдущего варианта белыми полями до указанных размеров
    *    1            0  обрезка до указанных размеров с выравниванием по центру
    */
    $GLOBALS['isSizes'] = array(
        0=>array('original'), // original
        1=>array(400,400,0,0),
        2=>array(200,200,1,0),
        3=>array(144,144,1,0),
        4=>array(155,98,1,0),
        5=>array(144,144,1,0),
        6=>array(877,877,0,0),
        7=>array(98,98,0,0),
    );


    function imgServiceSize($s) {
        return (isset($GLOBALS['isSizes'][$s])?$GLOBALS['isSizes'][$s]:(is_null($s)?$GLOBALS['isSizes']:false));
    }
