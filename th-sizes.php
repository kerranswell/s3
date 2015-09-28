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
        1=>array(110,110,0,0),
        2=>array(230,150,1,0), //article short annonce
        3=>array(150,150,0,0),
        4=>array(200,200,0,0),
        5=>array(50,50,1,0),
        6=>array(300,300,0,0), //export photogallery for iphone
        7=>array(600,600,0,0),
        8=>array(1024,1024,0,0),
        9=>array(2000,2000,0,0),
        10=>array(35,35,0,0),
        11=>array(120,120,1,0),
        12=>array(150,150,1,0),
        13=>array(25,25,0,0),
        14=>array(35,35,1,0),
        15=>array(50,50,0,0),
        16=>array(70,70,0,0),
        17=>array(20,20,0,0),
        18=>array(669,669,0,0), // in-article
        19=>array(100,100,1,0),
        20=>array(150,150,0,0),
        21=>array(35,35,0,0),
        22=>array(100,100,1,0),
        23=>array(128,148,0,0),
        24=>array(100,100,0,0),
        25=>array(600,600,0,0),
        26=>array(200,200,0,0),
        27=>array(400,300,0,0),
        28=>array(500,375,0,0), //iphone
        29=>array(700,525,0,0),
        30=>array(900,675,0,0),
        31=>array(60,60,1,0),
        32=>array(100,100,0,0),
        33=>array(56,56,0,0),
        34=>array(218,280,0,0),
        35=>array(100,129,0,0),
        36=>array(50,50,0,0),
        37=>array(100,100,0,0),
        38=>array(501,501,0,0,array('gravity'=>'north')),
        39=>array(100,100,0,0),
        40=>array(35,35,0,0),
        41=>array(67,67,0,0),
        42=>array(100,100,0,0),
        43=>array(60,60,0,0),
        44=>array(100,100,0,0),
        45=>array(50,50,0,0),
        46=>array(50,50,0,0),
        50=>array(30,30,0,0),
        57=>array(50,50,0,0),
        58=>array(170,170,0,0),
        59=>array(100,100,0,0),// something
        60=>array(180,144,0,0),
        61=>array(30,30,0,0),
        62=>array(100,100,0,0),
        63=>array(460,460,0,0),
        64=>array(100,100,0,0),
        65=>array(60,60,0,0),
        66=>array(226,226,1,0,array('gravity'=>'north')),
        67=>array(226,156,1,0,array('gravity'=>'north')),
        68=>array(86,86,1,0,array('gravity'=>'north')), // for ph_album_editor
        69=>array(145,145,0,0), // photo service thumbnails
        70=>array(660,600,0,0), // photo service Big photo
        71=>array(232,155,1,0),
        72=>array(1024,800,0,0), // photo service for lightbox
        84=>array(330,220,0,0),
        88=>array(100,83,0,0),
        89=>array(48,49,1,0),
        90=>array(350,350,0,0), //comment-image
        91=>array(155,135,0,0), //article-lenta-main
        92=>array(102,73,0,0), //article-anons
        93=>array(96,70,0,0), //block funtik recomend
        94=>array(146,82,0,0), //video preview
        95=>array(300,254,0,0), //video annons preview
        96=>array(428,259,0,0), // big video preview,
        97=>array(35,35,0,0),
        98=>array(640,960,0,0), // iphone export image size
        99=>array(152,170,1,0), //girlcatalogue list
        100 =>array(261,358,1,0),
        101 =>array(148,84,1,0),
        198 =>array(209,116,1,1), // tv service carousel
        199 =>array(670,210,0,0), // tourism rubric image
        200 =>array(670,180,0,0), // tourism anons background image
        201 =>array(670,3000,0,0), // tourism article background image
        202 =>array(688, 410, 0, 0), // TV carousel
        203 =>array(239, 33, 0, 0), // TV carousel title
        204 =>array(146,82,1,1), // tv service
        205 =>array(450,140,0,0), //announce background
        206 =>array(285,195,0,0), //regale
        207 =>array(120,90,0,0), // tv service carousel
        300 =>array(295,295,1,0),//mobile article
        301 =>array(155,101,1,0),//mobile article-preview
        302 =>array(480,99999,0,0),//mobile aricle gallery
        501 => array(450,3000,0,0),
        600 =>array(290,290,1,0),
        601 => array(73,73,1,0),
        602 => array(200,200,0,0),
        603 => array(170,170,0,0),
        700 =>array(100,100,1,0),
        701 =>array(300,10000,0,0),
        800 =>array(80,80,0,0), //nokia lumia 2012
        801 =>array(199,332,0,0), //nokia lumia 2012
        802 =>array(542,450,0,1, array('gravity'=>'center')),
        803 =>array(270,400,0,1, array('gravity'=>'center')), //battle images
        803 =>array(83,59,1,0), //contest comments image preview
        804 =>array(750,500,1,0, array('gravity'=>'north')), //contest comments image preview

        /*
        * new size
        */
        1001 =>array(40,40,1,0), //avatar header
        1002 =>array(100, 100, 1, 0), //avatar header
        1003 =>array(114, 135, 0, 0),
        1004 =>array(180, 130, 0, 0), // rss image
        1005 =>array(186, 237, 1, 0, array('gravity'=>'north')), // journals
        1006 =>array(114, 114, 1, 0), //avatar settings
        1007 =>array(114, 114, 1, 0), //news avatar person
        
        1008 =>array(565, 373, 1, 0), // old: 2004, contest/view/bidders 
        1009 =>array(750, 500, 1, 0), // old: 2005, contest/view/competitionsimages (slider)
        1010 =>array(150, 150, 1, 0), // old: 2006, competitions_admin slider image size 
        1011 =>array(170, 125, 1, 0), // search result list 
        1012 =>array(373, 565, 1, 0), // contest/view/bidders v2
        1013 =>array(375, 546, 1, 0), // contest/view/bidders v3
        
        1014 =>array(57, 57, 1, 0), //top friends avatar 
        1015 =>array(194, 194, 1, 0), //top friends avatar 
        1016 =>array(542, 394, 0, 1, array('gravity'=>'center')), //ellegirls content image
        1017 =>array(218, 159, 1, 0), //sidebar recomendations image
        1018 =>array(634, 341, 1, 0), //ellegirls look of day promo
        
        1019 =>array(105, 68, 1, 0), //posts editor small image
        1020 =>array(500, 500, 0, 0), //posts editor big image
        
        1021 =>array(542, 394, 0, 1), //ellegirls content list bidders

        1022 =>array(60, 60, 1, 0), //avatar header

        1023 =>array(364, 465, 0, 0), //footer magazine 1
        1024 =>array(498, 597, 0, 0), //footer magazine 2


        1100 => array(202,305, 1, 0), // iamodel thumbnail
        1101 => array(80,119, 1, 0), // iamodel thumbnail admin

        /*
        * new size for news
        * range 2000 - 2999 - view in admin 
        */
        2000 =>array(750, 600, 0, 0), //для статьи максимум
        2001 =>array(625, 374, 1, 0), //широкая
        2002 =>array(352, 391, 1, 0), //узкая в стойку
        2003 =>array(305, 391, 1, 0), //узкая в стойку в пол-ширины контейнера
        2004 =>array(1200, 1200, 0, 0), //для google play newsstand

        2016 =>array(542, 394, 0, 1, array('gravity'=>'center')), //для look-ов

        2020 =>array(585, 330, 1, 1, array('gravity'=>'center')), //для wallpaper

        3333 =>array(224, 120, 1, 0, array('gravity'=>'north')), //main page hit list
        3334 =>array(500, 400, 1, 0, array('gravity'=>'north')), //main page slider
        3335 =>array(370, 0, 0, 0), //main page list articles
        3336 =>array(144, 144, 1, 0, array('gravity'=>'north')), //question day
        3337 =>array(72, 72, 1, 0, array('gravity'=>'north')), //main page popular
        3338 =>array(374, 273, 1, 0, array('gravity'=>'north')), //test list
        3339 =>array(160, 160, 1, 0, array('gravity'=>'north')), //best on ellegirl

        3340 =>array(401, 401, 0, 0, array('gravity'=>'north')), //tag view
        3341 =>array(374, 0, 0, 0, array('gravity'=>'north')), //tag view tests
        3342 =>array(370, 0, 0, 0, array('gravity'=>'north')), //tag view others
        3343 =>array(180, 180, 0, 0, array('gravity'=>'north')), //read also
        3344 =>array(180, 96, 1, 0, array('gravity'=>'north')), //read also


        4000 =>array(80, 99, 1, 0, array('gravity'=>'north')), //new number
        4001 =>array(370, 493, 1, 0, array('gravity'=>'north')), //battles view
        4002 =>array(90, 120, 1, 0, array('gravity'=>'north')), //battles else items view
        4003 =>array(180, 240, 1, 0, array('gravity'=>'north')), //battles list
        4004 =>array(160, 160, 1, 0, array('gravity'=>'north')), //404 best
        4005 =>array(100, 100, 0, 0, array('gravity'=>'north')), //comments thumb
        4006 =>array(450, 240, 1, 0, array('gravity'=>'north')), //tests diagnos image size
        4007 =>array(90, 180, 1, 0, array('gravity'=>'north')), //battles in read also
        4008 =>array(180, 180, 1, 0, array('gravity'=>'north')), //tests answer pics

        /* my range >5000 */
        5000 =>array(750, 375, 1, 0, array('gravity'=>'north')), //competition image view
        5001 =>array(750, 500, 0, 0), //competition slider
        5002 =>array(378, 275, 1, 0, array('gravity'=>'north')), //competition section
        5003 =>array(750, 401, 1, 0, array('gravity'=>'north')), //polls view
        5004 =>array(750, 600, 1, 0, array('gravity'=>'north')), //wallpaper section

        5005 =>array(398, 249, 1, 0, array('gravity'=>'north')), //wallpaper computer
        5006 =>array(124, 220, 1, 0, array('gravity'=>'north')), //wallpaper iphone5
        5007 =>array(124, 186, 1, 0, array('gravity'=>'north')), //wallpaper iphone4
        5008 =>array(293, 221, 1, 0, array('gravity'=>'north')), //wallpaper ipad

        5009 =>array(565, 0, 0, 0), //competitions/comments images
        5014 =>array(323, 0, 0, 0), //jornals collage 1 foto
        5011 =>array(180, 240, 1, 0, array('gravity'=>'north')), //jornals achive
        5012 =>array(198, 0, 0, 0), //jornals collage 2 foto
        5013 =>array(87, 0, 0, 0), //jornals collage 3 foto
    );


    function imgServiceSize($s) {
        return (isset($GLOBALS['isSizes'][$s])?$GLOBALS['isSizes'][$s]:(is_null($s)?$GLOBALS['isSizes']:false));
    }
