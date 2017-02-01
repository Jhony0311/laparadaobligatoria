
(function ($) {

    $.fn.ingStepper = function (options) {

        var settings = $.extend({
            upClass: 'default',
            downClass: 'default',
            center: true
        }, options);

        return this.each(function (e) {
            var self = $(this);
			if(self.data('ingstepper')){
				return;
			}
            var clone = self.clone();
			clone.data('ingstepper',1);
			clone.addClass('ing-form-control');

            var min = self.attr('min');
            var max = self.attr('max');

            function setText(n) {
				if(isNaN(n)){
					n = 0;
				}
                if ((min && n < min) || (max && n > max)) {
                    return false;
                }

                clone.focus().val(n);
				clone.trigger('change');
                return true;
            }

            var group = $("<div class='ing-form-group ing-stepper'></div>");
            var down = $("<button type='button'>-</button>").attr('class', 'ing-btn ing-btn-' + settings.downClass).click(function () {
                setText(parseInt(clone.val()) - 1);
            });
            var up = $("<button type='button'>+</button>").attr('class', 'ing-btn ing-btn-' + settings.upClass).click(function () {
                setText(parseInt(clone.val()) + 1);
            });
            $("<span class='ing-form-group-addon ing-stepper-minus'></span>").append(down).appendTo(group);
            clone.appendTo(group);
            $("<span class='ing-form-group-addon ing-stepper-plus'></span>").append(up).appendTo(group);

            // remove spins from original
            clone.prop('type', 'text').keydown(function (e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
					(e.keyCode == 65 && e.ctrlKey === true) ||
					(e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }

                var c = String.fromCharCode(e.which);
                var n = parseInt(clone.val() + c);

                //if ((min && n < min) || (max && n > max)) {
                //    e.preventDefault();
                //}
            });

            clone.prop('type', 'text').blur(function (e) {
                var c = String.fromCharCode(e.which);
                var n = parseInt(clone.val() + c);
                if ((min && n < min)) {
                    setText(min);
                }
                else if (max && n > max) {
                    setText(max);
                }
            });


            self.replaceWith(group);
        });
    };
}(jQuery));
