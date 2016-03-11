app.filter('dateToISO', function() {
    return function(input) {
        //remove the time as this is causing issues in creating a new date object.
        var dateparts = input.split(' ');

        //return the date string converted to a date object in ISO string format so Angular can recognize it as a
        //real date.
        return new Date(dateparts[0]).toISOString();
    };
});