var Spinner = new function() {
    this.selector = '#ajax-loader';

    this.show = function () {
        $(this.selector).show();
    };

    this.hide = function () {
        $(this.selector).hide();
    };
};
