(function ($) {
    $(document).ready(function () {
        const gotlandsboendenForm = $('.gotlandsboenden-form');

        gotlandsboendenForm.on('click', '.form-group', function (e) {
            e.stopPropagation();
            $('#guest_menu').find('.guestWrapper').removeClass('active');
            $(this).find('.form-control').focus();
            let select = $(this).find('.ui-selectmenu-button');
            if (select.length > 0) {
                jQuery("#accommodationAreaWrapper").selectmenu('open');
            }
        });
        function adjustChildrenAgeElement(newCount) {
            const parent = $('#children-age-block');
            const currentChildren = parent.children('.search-step-filter-children-ages');

            if (currentChildren.length > newCount) {
                for (let i = newCount; i < currentChildren.length; i++) {
                    currentChildren.eq(i).remove();
                }
            } else if (currentChildren.length < newCount) {
                for (let i = currentChildren.length; i < newCount; i++) {
                    let data = {
                        'action': 'renderChildAgeItem',
                        'key': i+1,
                    };
                    $.ajax({
                        url: register_params.ajaxurl,
                        data: data,
                        dataType: 'json',
                        type: 'GET',
                        success: function (response) {
                            parent.append(response.data);
                        },
                        error: function (e) {
                            console.log(e);
                            console.log('error', data.action);
                        }
                    });
                }
            }
        }

        function updateGuestCount() {
            let amount = parseInt($('#gc-0-adults').val()) + parseInt($('#gc-0-children').val());
            let prefix = (amount < 2) ? 'person' : 'personer';
            $('#guestCounter_mobile').text(amount);
            $('#guestCounter_desktop').text(amount);
            $('#guestCounter_prefix').text(prefix);
            $('#guestCounter_prefix_desktop').text(prefix);
        }

        gotlandsboendenForm.on('click', '.btn-guest', function (e) {
            let type = $(this).data('type');
            let selector = $('#gc-0-'+type);
            let value = parseInt(selector.val());
            let minPerson = 1;
            if (type === 'children') {
                minPerson = 0;
            }
            if ($(this).hasClass('btn-minus')) {
                if (value > minPerson) {
                    value--;
                    if (type === 'children') adjustChildrenAgeElement(value);
                    if (type === 'children' && value > 0) {
                        $('#gc-0-children-counter').addClass('selected');
                    } else {
                        $('#gc-0-children-counter').removeClass('selected');
                    }

                }
            } else {
                if (value < 9) {
                    value++;
                    if (type === 'children') adjustChildrenAgeElement(value);
                    if (type === 'children' && value > 0) {
                        $('#gc-0-children-counter').addClass('selected');
                    } else {
                        $('#gc-0-children-counter').removeClass('selected');
                    }
                }
            }
            selector.val(value);
            $(this).parent().find('#gc-0-'+type+'-counter').text(value);
            if (type === 'children') {

                if (value > 0) {
                    $('.child-explanation').removeClass('hidden');
                } else {
                    $('.child-explanation').addClass('hidden');
                }
            }
            updateGuestCount();
        });


        gotlandsboendenForm.on('click', 'button[type=submit]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('submit');
            const parent = $('#children-age-block');
            const currentChildren = parent.children('.search-step-filter-children-ages');

            for (let i = 0; i < currentChildren.length; i++) {
                currentChildren.eq(i).remove();
            }
            gotlandsboendenForm.submit();
        });
        gotlandsboendenForm.on('click', 'button:not([type=submit])', function (e) {
            e.preventDefault();
        });

        gotlandsboendenForm.on('click', '#guest_menu', function (e) {
            e.stopPropagation();
            if (!$(e.target).hasClass('btn-confirm-guest')) $(this).find('.guestWrapper').addClass('active');
        });
        gotlandsboendenForm.on('change', '.select-gc-age', function (e) {
           $(document).trigger('caltulateChildrenAge');
        });
        $(document).on('caltulateChildrenAge', function (e) {
            const childrenAgeAttr = $('#gc-0-age');
            let childrenAges = [];
            $('.select-gc-age').each(function (index, element) {
                childrenAges.push($(element).val());
            });
            childrenAgeAttr.val(childrenAges.join(','));
        });

        $(document).on('click', function (e) {
            if (!document.getElementById('guest_menu').contains(e.target)) {
                $('#guest_menu').find('.guestWrapper').removeClass('active');
            }
        });

        gotlandsboendenForm.on('click', '.btn-confirm-guest', function (e) {
            e.preventDefault();
            $('.guestWrapper').removeClass('active');
        });

        gotlandsboendenForm.on('click', '#btn-from-back', function (e) {
            gotlandsboendenForm.removeClass('active');
        });

        $('.gotlandsboenden-form-mobile').on('click', '.gotlandsboenden-form-display__date, .gotlandsboenden-form-display__person', function (e) {
            gotlandsboendenForm.addClass('active');
        });

        $(document).on('click', '#gotlandsboenden-form-display__submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            gotlandsboendenForm.submit();
        });


    });
})(jQuery);

