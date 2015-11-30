var scene;
$(function() {

    if ($('body').hasClass('intro'))
    {

        var top_margin = -134;
        scene = new CScene();

        scene.body_width = $('body').width();
        scene.max_body_width = scene.body_width;
        scene.body_height = $('body').height();
        scene.start_body_height = scene.body_height;
        scene.max_body_height = scene.body_height;

        scene.patterns = {1:new Array(),2:new Array(),3:new Array()};


        var layers = $('.layer');
        layers.height(scene.body_height + 400);
        scene.layers_top = -Math.round((scene.body_height)/2);
        layers.css('top', scene.layers_top + 'px');

        var w = 60;
        scene.layer1 = $('.layer.p1');
        function buildLayer1()
        {
            scene.layer1.css({width: scene.body_width + 4*w});
            var cnt = Math.floor(scene.body_width / w) + 4;
            var cnt_left = 0;
            for (var i=scene.patterns[1].length; i < cnt; i++)
            {
                var div = document.createElement("div");
                $(div).addClass('patterns1');
                if (i%2 != 0) scene.layer1.append(div);
                else scene.layer1.prepend(div);
                if (i%2 == 0) cnt_left++;

                var p = new CPattern({obj:$(div),back_x:0,back_y:-111+top_margin -scene.layers_top + (i%2 ? 1 : -1) * Math.ceil(i/2)*171});
                scene.patterns[1].push(p);
            }
            return cnt_left;
        }
        var left_cnt = buildLayer1();

        scene.layer2 = $('.layer.p2');
        function buildLayer2()
        {
            scene.layer2.css({width: scene.body_width + 4*w});
            var cnt = Math.floor(scene.body_width / w) + 4;
            var cnt_left = 0;
            for (var i=scene.patterns[2].length; i < cnt; i++)
            {
                var div = document.createElement("div");
                $(div).addClass('patterns2');
                if (i%2 != 0) scene.layer2.append(div);
                else scene.layer2.prepend(div);
                if (!i%2) cnt_left++;

                var p = new CPattern({obj:$(div),back_x:0,back_y:129+top_margin -scene.layers_top+ (i%2 ? 1 : -1) * Math.ceil(i/2)*171});
                scene.patterns[2].push(p);
            }

            return cnt_left * w;
        }
        buildLayer2();

        var w3 = 120;
        scene.layer3 = $('.layer.p3');
        function buildLayer3()
        {
            scene.layer3.css({width: scene.body_width + 4*w3});
            var cnt = Math.floor(scene.body_width / (w3 / 2)) + 4;
            var cnt_left = 0;
            for (var i=scene.patterns[3].length; i < cnt; i++)
            {
                var div = document.createElement("div");
                $(div).addClass('patterns3');
                if (i%2 != 0) scene.layer3.append(div);
                else scene.layer3.prepend(div);

                if (!i%2) cnt_left++;

                var p = new CPattern({obj:$(div),back_x:0,back_y:61+top_margin -scene.layers_top + (i%2 ? 1 : -1) * Math.ceil(i/2)*171});
                scene.patterns[3].push(p);
            }

            return cnt_left * w3;
        }
        buildLayer3();

        scene.layers_left += -left_cnt*60;
        $('.layer').css('left', scene.layers_left + 'px');


    //    var patterns1 = $('.patterns1');
    //    var patterns2 = $('.patterns2');
    //    var patterns3 = $('.patterns3');

        var ind = 0;
        var x; var y;

        scene.init();
    //    scene.animate(true);

        $('.intro_arrow').click(function () {
            if (scene.busy == true) return;
            scene.direction = 1;
            scene.animate(true);
        });

        $(window).bind('keyup', function(e) {
            if (scene.busy == true) return;

            if (!$('body').hasClass('intro')) return false;
            var code = e.keyCode || e.which;

            switch (code)
            {
                case 38 :
                    scene.direction = -1;
                    scene.animate(false);
                    break;
                case 40 :
                    scene.direction = 1;
                    scene.animate(false);
                    break;
            }
        });

        $( window).on('mousewheel', function(event) {
            if (!$('body').hasClass('intro')) return false;
            if (event.deltaY != 0 && event.deltaX == 0)
            {
                if (scene.busy == false)
                {
                    scene.direction = - (event.deltaY > 0 ? 1 : -1);
    //                scene.animate_parts = true;
                    scene.animate(false);
                }
    /*
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
                }
    */

            }

            return false;
        });

        $( window ).resize(function() {
            if (!$('body').hasClass('intro')) return false;

            var new_w = $('body').width();
            var new_h = $('body').height();
/*
            if (new_h < 540)
            {
                new_h = 540;
                $('body').height(new_h);
            }
*/
    /*
            var left = Math.round((new_w - scene.body_width)/2);
            var top = Math.round((new_h - scene.body_height)/2);
            layers.css({left: left,top: top});
            console.log(left);
    */

            if (new_w > scene.max_body_width)
            {
                scene.max_body_width = scene.body_width = new_w;
                var added = buildLayer1();
                buildLayer2();
                buildLayer3();

                scene.layers_left += -added*60;
                $('.layer').css('left', scene.layers_left + 'px');
            }


            if (new_h > scene.max_body_height)
            {
                scene.max_body_height = new_h;
                var newtop = -Math.round((scene.body_height)/2);
                var shift = scene.layers_top - newtop;
                scene.layers_top = newtop;
                layers.css('top', scene.layers_top+'px');
                $('.patterns1, .patterns2, .patterns3').css('background-position-y', '+=' + shift + 'px');

    //            layers.css('top', -Math.round((new_h + 200)/2)+'px');
                scene.body_height = new_h;
                layers.height(new_h + 400);
            }


        });
    }
});

function CScene()
{
    this.layers_left = 122;
    this.layers_top = 0;
    this.scale = 3;
    this.px_shift = 68;
    this.number_steps = Math.ceil(this.px_shift / 3);
    this.lim = this.px_shift / this.scale;

    this.time = 600;
    this.time_auto = 1000;
    this.time_control = 600;
//    this.easing = 'easeOutCubic';
    this.easing = 'easeOutSine';
    this.step = 1;
    this.prev_step = 0;
    this.direction = 1;
    this.step_count = 8;
    this.busy = false;
    this.part_count = 1;
    this.part_count_steps = 0;
    this.animate_parts = false;
    this.steps = {
        5: {part_count: 2}
    };
}

CScene.prototype.init = function ()
{
    scene.l2 = new CLayer({obj:scene.layer2,x2:0,y2:-scene.px_shift})
    scene.l3 = new CLayer({obj:scene.layer3,x2:60,y2:-35})

    this.objects = new Array();

    this.objects.push({obj:this.layer1,
        steps: {
            1: {css:{opacity: 0}},
            2: {css:{opacity: 1}},
            5: {css:{opacity: 1}},
            6: {css:{opacity: 0}}
        }
    });

    this.objects.push({obj:this.layer2,
        steps: {
            2: {css:{opacity: 0}},
            3: {css:{opacity: 1}},
            4: {css:{marginTop: this.l2.y}},
            5: {css:{opacity: 1, marginTop: this.l2.y2}},
            6: {css:{opacity: 0}}
        }
    });

    this.objects.push({obj:this.layer3,
        steps: {
            3: {css:{opacity: 0}},
            4: {css:{opacity: 1, marginLeft: this.l3.x, marginTop: this.l3.y}},
            5: {css:{opacity: 1, marginLeft: this.l3.x2, marginTop: this.l3.y2}},
            6: {css:{opacity: 0}}
        }
    });

    this.objects.push({obj:$('.layer2 .logo'),
        steps: {
            5: {css_trig_backward_out:{display: 'none'}, css_trig_forward_out:{display: 'block'}}
        }
    });

    this.objects.push({obj:$('.layer2 .logo .logo_pattern4, .layer2 .title1'),
        steps: {
            6: {css:{opacity:0}},
            7: {css:{opacity:1}}
        }
    });

    this.objects.push({obj:$('.layer2 .title2'),
        steps: {
            7: {css:{opacity:0}},
            8: {css:{opacity:1}}
        }
    });

    this.objects.push({obj:$('.layer0'),
        steps: {
            1: {css:{opacity:1}},
            2: {css:{opacity:0}}
        }
    });

    this.objects.push({obj:$('.intro_arrow'),
        steps: {
            1: {css:{opacity:1}},
            2: {css:{opacity:0}, css_trig_backward_out:{display: 'block'}, css_trig_forward_in:{display: 'none'}}
        }
    });

    this.objects.push({obj:$('#header_burger'),
        steps: {
            1: {css:{opacity:1}},
            2: {css:{opacity:0}, css_trig_backward_out:{display: 'block'}, css_trig_forward_in:{display: 'none'}}
        }
    });

    for (var i in this.objects)
    {
        var o = this.objects[i];
        o.parts_steps = {};
        var ps_ind = 1;
        var time_sum = 0;
        for (var j=1; j <= this.step_count; j++)
        {
//            j = parseInt(j);
            var cnt = this.steps[j] && this.steps[j].part_count ? this.steps[j].part_count : this.part_count;
            var step_time = (o.steps[j] && o.steps[j].time) ? o.steps[j].time : this.time;
            var v = {};

            for (var k = 0; k <= cnt; k++)
            {
                if (!o.parts_steps[ps_ind]) o.parts_steps[ps_ind] = {};

                if (o.steps[j])
                {
                    o.parts_steps[ps_ind] = JSON.parse(JSON.stringify(o.steps[j]));

                    if (o.steps[j+1])
                    {
                        var css1 = o.steps[j].css;
                        var css2 = o.steps[j+1].css;

                        o.parts_steps[ps_ind].css = {};
                        for (var c in css1)
                        {
                            if (!v[c]) v[c] = css1[c];
                            var offset = ((css2[c] ? css2[c] : css1[c]) - css1[c])/cnt;
                            o.parts_steps[ps_ind].css[c] = v[c];
                            v[c] += offset;
                        }

                    }
                }

                var t =  Math.round(step_time / cnt);
                o.parts_steps[ps_ind].time = t;
                if (k == 0 && o.steps[j-1] && o.steps[j-1].time && time_sum > 0)
                {
                    var t_time = (o.steps[j-1] && o.steps[j-1].time) ? o.steps[j-1].time : this.time;
                    o.parts_steps[ps_ind].time = t_time - time_sum;
                }

                if (k > 0) time_sum += t;

                ps_ind++;
            }

            o.parts_steps[ps_ind-1].delete = 1;

            if (o.steps[j] && o.steps[j+1])
            {
                var css1 = o.steps[j].css;
                var css2 = o.steps[j+1].css;
                for (var c in css1)
                {
                    if (o.parts_steps[ps_ind-1].css[c] && css2[c] && o.parts_steps[ps_ind-1].css[c] != css2[c]) o.parts_steps[ps_ind-1].css[c] = css2[c];
                }
            }

        }

        if (o.parts_steps[ps_ind-1]) o.parts_steps[ps_ind-1].delete = 1;
    }

//    for (var i in this.objects) for (var j in this.objects[i].parts_steps) if (this.objects[i].parts_steps[j].delete) delete this.objects[i].parts_steps[j];
    for (var i in this.objects)
    {
        var o = this.objects[i];
        o.psteps = {};
        var ind = 0;
        for (var j in o.parts_steps)
            if (!o.parts_steps[j].delete) o.psteps[ind++] = o.parts_steps[j];

        this.part_count_steps = ind;
    }


}

CScene.prototype.animate = function (auto)
{
    var self = this;
    if (auto) this.time = this.time_auto;
    else this.time = this.time_control;
    var cnt = this.animate_parts ? this.part_count_steps : this.step_count;
    if (self.step + self.direction > cnt) {
        if (pager && this.busy == false)
        {
            pager.pageJump($('.main_menu li:first').data('id'));
        }
        return;
    }
    if (self.step + self.direction < 1) return;
    this.busy = true;
    var max_time = this.animateStep();

    setTimeout(function () {
        self.triggersIn();
        self.busy = false;
        if (self.step > 0 && self.step <= cnt && auto) self.animate(auto);
    }, max_time);
}

CScene.prototype.triggersIn = function ()
{
    var self = this;
    for (var j in this.objects)
    {
        var o = this.objects[j];
        var steps = !this.animate_parts ? o.steps : o.psteps;
        if (!steps[this.step]) continue;
        if (steps[this.step].css_trig_forward_in && self.direction > 0) o.obj.css(steps[this.step].css_trig_forward_in);
        if (steps[this.step].css_trig_backward_in && self.direction < 0) o.obj.css(steps[this.step].css_trig_backward_in);
    }
}

CScene.prototype.animateStep = function ()
{
    var self = this;
    var max_time = 0;

    self.prev_step = self.step;
    self.step += self.direction;

    for (var j in this.objects)
    {
        var o = this.objects[j];
        var steps = !this.animate_parts ? o.steps : o.psteps;

        if (steps[this.prev_step])
        {
            if (steps[this.prev_step].css_trig_forward_out && self.direction > 0)
                o.obj.css(steps[this.prev_step].css_trig_forward_out);
            if (steps[this.prev_step].css_trig_backward_out && self.direction < 0)
                o.obj.css(steps[this.prev_step].css_trig_backward_out);
        }

        if (!steps[this.step]) continue;

        if (max_time < steps[this.step].time) max_time = steps[this.step].time;

        if (steps[this.prev_step] && steps[this.prev_step].css) o.obj.css(steps[this.prev_step].css);
        if (steps[this.step].css)
        o.obj.animate(steps[this.step].css, steps[this.step].time ? steps[this.step].time : this.time, steps[this.step].easing ? steps[this.step].easing : this.easing);

    }

    if (max_time == 0) max_time = this.time;

    return max_time;
}

var l1, l2, l3;

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