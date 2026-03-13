try {
    $.validator.methods.date = function(value, element) {
        var val = moment(value).format('MM-DD-YYYY');
        return this.optional(element) || (val);
    };
} catch (e) {
    console.log(e);
}


try {
    $.fn.datepicker.defaults.language = "es";
    $.fn.datepicker.defaults.format = "mm/dd/yyyy";
    $.fn.datepicker.defaults.autoclose = true;
} catch (e) { console.log(e); }
