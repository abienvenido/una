/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */


(function($) {

    $.fn.addWebForms = function() {

        $(this).addNonWebForms();

        // switchers
        $('.bx-switcher-cont', this).each(function() {
            var eSwitcher = $(this);
            var eInput = $(this).find('input');

            if (eSwitcher.hasClass('bx-form-switcher-processed'))
                return;
            eSwitcher.addClass('bx-form-switcher-processed');

            eSwitcher.on('click', function() {
                var $this = $(this);
                if ($this.hasClass('on')) {
                    $this.find('input').removeAttr('checked').trigger('change');
                    $this.removeClass('on').addClass('off');
                } else {
                    $this.removeClass('off').addClass('on');
                    $this.find('input').attr('checked', 'checked').trigger('change');
                }
                return false;
            });

            eInput.on('enable', function () {
                if ('checked' != $(this).attr('checked'))
                    $(this).attr('checked', 'checked').trigger('change');
                if (!eSwitcher.hasClass('on'))
                    eSwitcher.removeClass('off').addClass('on');
            });

            eInput.on('disable', function () {
                if (undefined != $(this).attr('checked'))
                    $(this).removeAttr('checked').trigger('change');
                if (!eSwitcher.hasClass('off'))
                    eSwitcher.removeClass('on').addClass('off');
            });
        });

        // collapsable headers 
        $('.bx-form-collapsable', this).each(function() {

            var eFormSection = $(this);

            if (eFormSection.hasClass('bx-form-js-processed'))
                return;

            eFormSection.addClass('bx-form-js-processed');

            var fCallback = function() {

                if (eFormSection.hasClass('bx-form-collapsed')) {

                    // show

                    eFormSection.removeClass('bx-form-collapsed');

                    if (eFormSection.hasClass('bx-form-section-hidden')) {
                        $('.bx-form-section-content', eFormSection).hide();
                        eFormSection.removeClass('bx-form-section-hidden');
                    }

                    $('.bx-form-section-content', eFormSection).slideDown(function () {
                        eFormSection.removeClass('bx-form-section-hidden');
                    }); 

                } else {

                    // hide

                    eFormSection.addClass('bx-form-collapsed');

                    $('.bx-form-section-content', eFormSection).slideUp(function () {                    
                        eFormSection.addClass('bx-form-collapsed bx-form-section-hidden');
                    }); 
                }

            };

            $('<u class="bx-form-section-toggler"><i class="sys-icon chevron-right"></i></u>').prependTo($('legend', eFormSection));
            $('legend', eFormSection).click(fCallback);
        });

    };

})(jQuery);


(function($) {

    $.fn.addNonWebForms = function() {

        $("input", this).each(function() {

            var onCreateRange = function (event, ui) {
                var eInput = $(this).parent().find('input');
                var iSliderWidth = eInput.innerWidth() - 160;
                $(this).css('width',  iSliderWidth + 'px').css('top', (eInput.innerHeight() - $(this).outerHeight())/2 + 1 + 'px');
                eInput.css('padding-right', (iSliderWidth + parseFloat($(this).css('margin-right')) + 10) + 'px');                        
            };

            // DoubleRange
            if (this.getAttribute("type") == 'doublerange') {

                var cur = $(this);

                if (cur.hasClass('bx-form-doublerange-processed'))
                    return;
                cur.addClass('bx-form-doublerange-processed');

                var $slider = $('<div class="bx-def-margin-right"></div>').insertAfter(cur);

                var iMin = cur.attr("min") ? parseFloat(cur.attr("min"), 10) : 0;
                var iMax = cur.attr("max") ? parseFloat(cur.attr("max"), 10) : 100;
                var sRangeDv = cur.attr("range-divider") ? cur.attr("range-divider") : '-';

                var funcGetValues = function (e) {

                    var values = e.val().split(sRangeDv, 2); // get values

                    if (typeof(values[0]) != 'undefined' && values[0].length)
                        values[0] = parseFloat(values[0]);
                    else
                        values[0] = iMin;

                    if (typeof(values[1]) != 'undefined' && values[1].length)
                        values[1] = parseFloat(values[1]);
                    else
                        values[1] = iMax;

                    return values;
                };

                var onChange = function(e, ui) {
                    values = ui.values;
                    cur.val( values[0] + sRangeDv + values[1] );
                };

                $slider.slider({
                    range: true,
                    min: iMin,
                    max: iMax,
                    step: parseFloat(cur.attr("step")) ? parseFloat(cur.attr("step")) : 1,
                    values: funcGetValues(cur),
                    change: onChange,
                    slide: onChange,
                    create: onCreateRange
                });

                cur.bind('change', function () {
                    var values = funcGetValues($(this));
                    $slider.slider("option", "values", values);
                });

            }

            // Single range or slider
            else if (this.getAttribute("type") == 'slider') {

                var cur = $(this);

                if (cur.hasClass('bx-form-range-processed'))
                    return;
                cur.addClass('bx-form-range-processed');

                var $slider = $('<div class="bx-def-margin-right"></div>').insertAfter(cur)

                $slider.css('width', ($slider.parent().innerWidth() - cur.outerWidth() - 50) + 'px');

                var iMin = cur.attr("min") ? parseFloat(cur.attr("min"), 10) : 0;
                var iMax = cur.attr("max") ? parseFloat(cur.attr("max"), 10) : 100;

                var onChange = function(e, ui) {
                    cur.val( ui.value );
                };

                $slider.slider({
                    min: iMin,
                    max: iMax,
                    step: parseFloat(cur.attr("step")) ? parseFloat(cur.attr("step")) : 1,
                    value: cur.val(),
                    change: onChange,
                    slide: onChange,
                    create: onCreateRange
                });

                cur.bind('change', function () {
                    $slider.slider("option", "value", $(this).val());
                });

            }

            // Date picker
            else if(this.getAttribute("type") == "datepicker") {

                if ($(this).hasClass('bx-form-datepicker-processed'))
                    return;
                $(this).addClass('bx-form-datepicker-processed');

                var sYearMin = '1900';
                var sYearMax = '2100';
                var m;
                if ($(this).attr('min') && (m = $(this).attr('min').match(/^(\d{4})/)))
                    sYearMin = m[1];
                if ($(this).attr('max') && (m = $(this).attr('max').match(/^(\d{4})/)))
                    sYearMax = m[1];

                $(this).datepicker({
                    changeYear: true,
                    changeMonth: true,
                    dateFormat: 'yy-mm-dd',
                    defaultDate: '-22y',
                    hidePrevNext: true,
                    //showButtonPanel: false,
                    yearRange: sYearMin + ':' + sYearMax
                });

                if( this.getAttribute("allow_input") == null) { 
                    $(this).attr('readonly', 'readonly'); 
                } 
            }

            // DateTime picker
            else if ($(this).parents().filter('.bx-form-input-wrapper-datetime').length > 0) {

                if ($(this).hasClass('bx-form-datetime-processed'))
                    return;
                $(this).addClass('bx-form-datetime-processed');

                $(this)
                .dynDateTime({
                    ifFormat: '%Y-%m-%d %H:%M:%S',
                    showsTime: true
                });

                if( this.getAttribute("allow_input") == null) { 
                    $(this).attr('readonly', 'readonly'); 
                } 
            }

            // add clear input button
            var eInput = $(this);
            var aClearInputClassSelectors = ['.bx-form-input-wrapper-datetime', '.bx-form-input-wrapper-datepicker', '.bx-form-input-wrapper-text', '.bx-form-input-wrapper-password'];
            for (var i in aClearInputClassSelectors) {
                var sClassSelector = aClearInputClassSelectors[i];

                var eWrapper = $(this).parents().filter(sClassSelector);
                if (!eWrapper.length)
                    continue;
                if (eWrapper.hasClass('bx-form-input-btn-cross-processed'))
                    return;
                eWrapper.addClass('bx-form-input-btn-cross-processed');

                if ('relative' != eWrapper.css('position') && 'absolute' != eWrapper.css('position'))
                    eWrapper.css('position', 'relative');

                eWrapper.append('<div style="display:none;" class="bx-form-input-btn-cross"><i class="sys-icon remove"></i></div>');

                eInput.css('padding-right', parseInt(eInput.css('padding-right')) + 16 + 'px');

                var eBtnCross = eWrapper.find('.bx-form-input-btn-cross');

                eInput.on('focus', function () {
                    eBtnCross.show().position({
                            of: $(this),
                            my: 'right top',
                            at: 'right top',
                            collision: 'none none',
                            offset: '-8 11'
                    });
                });

                eInput.on('blur', function (event) {
                    eBtnCross.hide();
                });
                
                eBtnCross.on('mousedown', function () {
                    eInput.val('');
                    setTimeout(function () {                        
                        var eWrapper = eInput.parents().filter('.bx-form-input-wrapper');
                        if (eWrapper.hasClass('bx-form-input-wrapper-datepicker')) 
                            $(eInput).datepicker('disable');
                        eInput.focus();
                        if (eWrapper.hasClass('bx-form-input-wrapper-datepicker'))
                            $(eInput).datepicker('enable');
                    }, 100);
                });

            }

        });
        return this;
    };

})(jQuery);

$(document).ready(function() {
    $(this).addWebForms();
});

