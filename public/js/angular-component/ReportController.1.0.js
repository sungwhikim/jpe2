/* app is instantiated in the myApp.js file */

app.controller('ReportController', function($http, alertService, datePickerService) {
    //set object to variable to prevent self reference collisions
    var ReportController = this;

    /* SET PROPERTIES */
    ReportController.baseUrl = baseUrl;
    ReportController.alerts = alertService.get();
    ReportController.criteria = reportCriteria;

    /* SET MEMBER METHODS */
    ReportController.submit = submit;
    ReportController.getProductList = getProductList;
    ReportController.closeAlert = closeAlert;
    ReportController.sendAlert = sendAlert;

    /* SET DATA TO BE USED FOR SELECT LISTS */
    if( typeof warehouseClientData != "undefined" ) { ReportController.wcList = warehouseClientData; }
    if( typeof productData != "undefined" ) { ReportController.products = productData; }

    /* ASSIGN SERVICES TO ALLOW DIRECT ACCESS FROM TEMPLATE TO SERVICE */
    ReportController.datePicker = datePickerService;

    /* GET THE LIST OF PRODUCTS FOR THE WAREHOUSE/CLIENT */
    function getProductList() {
        //only get list if the control exists
        if( ReportController.criteria.product_id ) {

        }
    }

    /* SUBMIT THE REPORT */
    function submit(form, action) {
        //set form submitted to true here since we are not using form submission to process saving of data
        //as we require different parameters to be passed in by different buttons.
        form.$submitted = true;

        //clear any previous alerts
        alertService.clear();

        //set parameter array
        var params = [];

        /* THE FIELD NAMES DON'T MATCH UP.  REVERT TO FORM VALIDATION AFTER IT GETS WORKING */
        //validate data
        for( key in ReportController.criteria ) {
            //check to see if values were set
            if( ReportController.criteria[key] == 'undefined' || ReportController.criteria[key] == null ) {
                alertService.add('danger', key + ' is a required field');
            }

            //since there is a valid value, build the parameter array
            else {
                //if it is a date, only send the date
                var value = ReportController.criteria[key];
                if( value instanceof Date ) {
                    value = value.toISOString().slice(0,10);
                }

                //add the parameter array
                params.push(key + '=' + value);
            }
        }

        //exit if error was found
        if( ReportController.alerts.length > 0 ) { return false; }

        //build query string
        var queryString = encodeURI('?' + params.join('&'));

        //build url
        var url = ReportController.baseUrl + '/report/' + ReportController.criteria.name + '/' + action + '/' + queryString;

        //refresh page if just a submit
        if( action == 'view' ) {
            window.location.href = url;
        }

        //otherwise open a popup
        else {
            popupWindow(url, 'Report', 1200, 800);
        }

        console.log(queryString);

        console.log( ReportController.criteria);
    }

    /* INITIALIZE THE ALERT SERVICE PASS THROUGH ASSIGNMENTS */
    function closeAlert(index) {
        alertService.closeAlert(index);
    }

    /* SENDS A SINGLE RESPONSE ALERT TO USER AND CLEARS OUT OLD MESSAGE */
    function sendAlert(type, message) {
        alertService.clear();
        alertService.add(type, message);
    }
});