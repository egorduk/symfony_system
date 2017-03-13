(function ($) {
    $(document).ready(function () {
        var bidIsClientDate = $('#bid_form_isClientDate'),
            bidDay = $('#bid_form_fieldDay'),
            bidSum = $('#bid_form_fieldSum'),
            formBid = $('#formBid');

        bidIsClientDate.change(function() {
            $(this).prop('checked') ? disableBidDay(true) : disableBidDay(false);
        });

        if (bidIsClientDate.prop('checked')) {
            disableBidDay(true);
        }

        bidSum.priceFormat({
            prefix: '',
            centsSeparator: '',
            thousandsSeparator: ' ',
            centsLimit: 0,
            clearOnEmpty: true
        });

        formBid.on('submit', function () {
            bidSum.val(parseInt(bidSum.val().replace(' ', '')));

            if (!bidDay.val()) {
                bidIsClientDate.attr('checked', true);
                disableBidDay(true);
            }
        });

        function disableBidDay(mode) {
            bidDay.prop('disabled', mode).val('');
        }
    });
})(window.jQuery);