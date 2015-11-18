$(function() {
    var body_width = $('body').width();
    var max_body_width = body_width;
    var body_height = $('body').height();
    var max_body_height = body_height;

    var patterns = {1:new Array(),2:new Array(),3:new Array()};

    $('.layer').height($('body').height() + 200);

    var w = 61;
    var layer1 = $('.layer.p1');
    buildLayer1();
    function buildLayer1()
    {
        layer1.css({width: body_width + 2*w});
        var cnt = Math.floor(body_width / w) + 2;
        for (var i=patterns[1].length; i < cnt; i++)
        {
            var div = document.createElement("div");
            $(div).addClass('patterns1');
            layer1.append(div)

            var p = new CPattern({obj:$(div),back_x:0,back_y:-111 + i*171});
            patterns[1].push(p);
        }
    }

    var w = 61;
    var layer2 = $('.layer.p2');
    buildLayer2();
    function buildLayer2()
    {
        layer2.css({width: body_width + 2*w});
        var cnt = Math.floor(body_width / w) + 2;
        for (var i=patterns[2].length; i < cnt; i++)
        {
            var div = document.createElement("div");
            $(div).addClass('patterns2');
            layer2.append(div)

            var p = new CPattern({obj:$(div),back_x:0,back_y:128 + i*171});
            patterns[2].push(p);
        }
    }

    var w3 = 120;
    var layer3 = $('.layer.p3');
    buildLayer3();
    function buildLayer3()
    {
        layer3.css({width: body_width + 2*w3});
        var cnt = Math.floor(body_width / (w3 / 2)) + 2;
        for (var i=patterns[3].length; i < cnt; i++)
        {
            var div = document.createElement("div");
            $(div).addClass('patterns3');
            layer3.append(div)

            var p = new CPattern({obj:$(div),back_x:0,back_y:60 + i*171});
            patterns[3].push(p);
        }
    }

    var scale = 3;
    var px_shift = 68;
    var number_steps = Math.ceil(px_shift / 3);
    var lim = px_shift / scale;
    var l2 = new CLayer({obj:layer2,x2:0,y2:-px_shift})
    var l3 = new CLayer({obj:layer3,x2:59,y2:-34})

//    var patterns1 = $('.patterns1');
//    var patterns2 = $('.patterns2');
//    var patterns3 = $('.patterns3');

    var ind = 0;
    var x; var y;
    $( window).on('mousewheel', function(event) {
//        console.log(event.deltaX, event.deltaY, event.deltaFactor);
        if (event.deltaY != 0 && event.deltaX == 0)
        {
            if (ind + event.deltaY >= 0 && ind + event.deltaY <= number_steps)
            {
                ind += event.deltaY;
                var yshift = event.deltaY * scale;

                if (ind == number_steps)
                {
                    l2.moveTo(l2.x2, l2.y2);
                    l3.moveTo(l3.x2, l3.y2);
                } else {
                    x = 0;
                    y = yshift;
                    l2.move(x, yshift);

                    x = event.deltaY * (Math.abs(l3.x2 - l3.x1) / number_steps);
                    y = event.deltaY * (Math.abs(l3.y2 - l3.y1) / number_steps);
                    l3.move(x, y);
                }
/*
            } else if (ind + event.deltaY > lim) {
                l2.moveTo(l2.x2, l2.y2);
                l3.moveTo(l3.x2, l3.y2);
*/
            }
        }

        return false;
    });

    $( window ).resize(function() {

        var new_w = $('body').width();
        if (new_w > max_body_width)
        {
            max_body_width = body_width = new_w;
            buildLayer1();
            buildLayer2();
            buildLayer3();
        }


        var new_h = $('body').height();
        if (new_h > max_body_height)
        {
            max_body_height = body_height = new_h;
            $('.layer').height(new_h + 200);
        }


    });

});

function CLayer(args)
{
    this.obj = args.obj;
    this.x = this.x1 = parseInt(this.obj.css('margin-left').replace('px',''));
    this.y = this.y1 = parseInt(this.obj.css('margin-top').replace('px',''));
    this.x2 = this.x1 + args.x2;
    this.y2 = this.y1 + args.y2;
}

/* CLayer */

CLayer.prototype.move = function (xshift,yshift)
{
    this.x += xshift;
    this.y -= yshift;
    this.setPos();
}

CLayer.prototype.moveTo = function (x,y)
{
    this.x = x;
    this.y = y;
    this.setPos();
}

CLayer.prototype.setPos = function ()
{
    this.obj.css({marginLeft: this.x});
    this.obj.css({marginTop: this.y});
}

/* CPattern */

function CPattern(args)
{
    this.obj = args.obj;
    this.back_x = this.final_x = args.back_x;
    this.back_y = this.final_y = args.back_y;

    this.setBackShifts();
}

CPattern.prototype.moveBack = function (xshift, yshift)
{
    this.back_x += xshift;
    this.back_y += yshift;

    this.setBackShifts();
}

CPattern.prototype.setBackShifts = function ()
{
    this.obj.css('background-position',this.back_x + 'px ' + this.back_y + 'px');
}