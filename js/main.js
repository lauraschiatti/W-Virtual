$(document).ready(function(){
    //Handles menu drop down
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
    });
});

// datePicker
var datePickerSettings = {
    autoclose: true,
    language: 'es',
    weekStart: 1,
    todayBtn: true,
    todayHighlight: true
};

ko.bindingHandlers.datePicker = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        var options = allBindingsAccessor.get('dateOptions') || {};
        $.extend(datePickerSettings, options);
        $(element).datepicker(datePickerSettings);
        ko.bindingHandlers.value.init(element, valueAccessor, allBindingsAccessor);
    },
    update : function (element, valueAccessor, allBindingsAccessor) {
        var value = ko.unwrap(valueAccessor());
        $(element).datepicker("setDate", value);
        ko.bindingHandlers.value.update(element, valueAccessor, allBindingsAccessor);
    }
}; 

// modal votacion
$(document).ready(function() {
    var panels = $('.vote-results');
    var panelsButton = $('.dropdown-results');
    panels.hide();

    //Click dropdown
    panelsButton.click(function() {
        //get data-for attribute
        var dataFor = $(this).attr('data-for');
        var idFor = $(dataFor);

        //current button
        var currentButton = $(this);
        idFor.slideToggle(400, function() {
            //Completed slidetoggle
            if(idFor.is(':visible'))
            {
                currentButton.html('Esconder resultados');
            }
            else
            {
                currentButton.html('Ver resultados');
            }
        })
    });
});


// mensaje contactanos
(function($) {
    
$.fn.charCounter = function (max, settings) {
    max = max || 100;
    settings = $.extend({
        container: "<span></span>",
        classname: "charcounter",
        format: "(%1 characters remaining)",
        pulse: true,
        delay: 0
    }, settings);
    var p, timeout;
    
    function count(el, container) {
        el = $(el);
        if (el.val().length > max) {
            el.val(el.val().substring(0, max));
            if (settings.pulse && !p) {
                pulse(container, true);
            };
        };
        if (settings.delay > 0) {
            if (timeout) {
                window.clearTimeout(timeout);
            }
            timeout = window.setTimeout(function () {
                container.html(settings.format.replace(/%1/, (max - el.val().length)));
            }, settings.delay);
        } else {
            container.html(settings.format.replace(/%1/, (max - el.val().length)));
        }
    };
    
    function pulse(el, again) {
        if (p) {
            window.clearTimeout(p);
            p = null;
        };
        el.animate({ opacity: 0.1 }, 100, function () {
            $(this).animate({ opacity: 1.0 }, 100);
        });
        if (again) {
            p = window.setTimeout(function () { pulse(el) }, 200);
        };
    };
    
    return this.each(function () {
        var container;
        if (!settings.container.match(/^<.+>$/)) {
            // use existing element to hold counter message
            container = $(settings.container);
        } else {
            // append element to hold counter message (clean up old element first)
            $(this).next("." + settings.classname).remove();
            container = $(settings.container)
                            .insertAfter(this)
                            .addClass(settings.classname);
        }
        $(this)
            .unbind(".charCounter")
            .bind("keydown.charCounter", function () { count(this, container); })
            .bind("keypress.charCounter", function () { count(this, container); })
            .bind("keyup.charCounter", function () { count(this, container); })
            .bind("focus.charCounter", function () { count(this, container); })
            .bind("mouseover.charCounter", function () { count(this, container); })
            .bind("mouseout.charCounter", function () { count(this, container); })
            .bind("paste.charCounter", function () { 
                var me = this;
                setTimeout(function () { count(me, container); }, 10);
            });
        if (this.addEventListener) {
            this.addEventListener('input', function () { count(this, container); }, false);
        };
        count(this, container);
    });
};

})(jQuery);

$(function() {
    $(".counted").charCounter(320,{container: "#counter"});
});


