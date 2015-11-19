$.fn.hyphenate = function(dashes) {
	var RusA = "[абвгдеёжзийклмнопрстуфхцчшщъыьэюя]";
	var RusV = "[аеёиоуыэю\я]";
	var RusN = "[бвгджзклмнпрстфхцчшщ]";
	var RusX = "[йъь]";
	var RusP = "во|без|до|из|ко|на|по|от|перед|при|через|не|за|над|для|об|под|про|но|да|или|то|что|как|ни|уж|[абвикосуя]";
	var RusP2 = "бы|ли|же";

    var nbsp = "\xA0";

	var Space = " ";
	var Space2 = nbsp;
	var Hyphen = "\xAD";

	var re1 = new RegExp("("+RusX+")("+RusA+RusA+")","ig");
	var re2 = new RegExp("("+RusV+")("+RusV+RusA+")","ig");
	var re3 = new RegExp("("+RusV+RusN+")("+RusN+RusV+")","ig");
	var re4 = new RegExp("("+RusN+RusV+")("+RusN+RusV+")","ig");
	var re5 = new RegExp("("+RusV+RusN+")("+RusN+RusN+RusV+")","ig");
	var re6 = new RegExp("("+RusV+RusN+RusN+")("+RusN+RusN+RusV+")","ig");
	var re7 = new RegExp("("+Space+")("+RusP+")("+Space+")","ig");
	var re7a = new RegExp("("+Space2+")("+RusP+")("+Space+")","ig");
	var re8 = new RegExp("("+Space+")("+RusP2+")("+Space+")","ig");

	this.each(function(){
		var text=$(this).html();
        text = text.replace(re7, "$1$2"+nbsp);
        text = text.replace(re7a, "$1$2"+nbsp);
        text = text.replace(re8, nbsp+"$2$3");
        if (dashes)
        {
            text = text.replace(re1, "$1"+Hyphen+"$2");
            text = text.replace(re2, "$1"+Hyphen+"$2");
            text = text.replace(re3, "$1"+Hyphen+"$2");
            text = text.replace(re4, "$1"+Hyphen+"$2");
            text = text.replace(re5, "$1"+Hyphen+"$2");
            text = text.replace(re6, "$1"+Hyphen+"$2");
        }
		$(this).html(text);
	});
};

