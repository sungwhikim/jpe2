/* app is instantiated in the myApp.js file */

app.controller('ReportController', function($http, alertService, datePickerService, searchSelectService) {
    //set object to variable to prevent self reference collisions
    var ReportController = this;

    /* SET PROPERTIES */
    ReportController.baseUrl = baseUrl;
    ReportController.alerts = alertService.get();
    ReportController.criteria = reportCriteria;
    ReportController.isOpened = [];

    /* SET MEMBER METHODS */
    ReportController.submit = submit;
    ReportController.closeAlert = closeAlert;
    ReportController.sendAlert = sendAlert;
    ReportController.selectProduct = selectProduct;
    ReportController.selectClient = selectClient;
    ReportController.searchProduct = searchProduct;
    ReportController.clearProductSearch = clearProductSearch;

    /* SET SEARCH SELECT PROPERTIES */
    //only init if product data is present
    if( typeof ReportController.criteria.product_id != "undefined" ) {
        ReportController.SearchSelectProduct = searchSelectService;
        ReportController.SearchSelectProduct.items = [];
        ReportController.SearchSelectProduct.displayItems = [];
        ReportController.SearchSelectProduct.selectCallBack = selectProduct;
        ReportController.SearchSelectProduct.clear = searchSelectService.clear;
        ReportController.SearchSelectProduct.searchTerm = searchSelectService.searchTerm;
        ReportController.SearchSelectProduct.baseUrl = ReportController.baseUrl;
        ReportController.SearchSelectProduct.warehouse_id = ReportController.criteria.warehouse_id;
        ReportController.SearchSelectProduct.client_id = ReportController.criteria.client_id;

        //prefill list and select the item if product was previously selected
        // if( ReportController.criteria.product_id != null ) {
        //     //fill list
        //     ReportController.SearchSelectProduct.getData(true);
        //
        //     //change callback so we can pre-select the item
        //     ReportController.SearchSelectProduct.getDataCallBack = function(data, init) {
        //         ReportController.selectProduct(getObjectById(data, ReportController.criteria.product_id));
        //     }
        // }
    }

    /* SET DATA TO BE USED FOR SELECT LISTS */
    if( typeof warehouseClientData != "undefined" ) { ReportController.wcList = warehouseClientData; }

    /* ASSIGN SERVICES TO ALLOW DIRECT ACCESS FROM TEMPLATE TO SERVICE */
    ReportController.datePicker = datePickerService;
    //overload open function as we have multiple date controls
    ReportController.datePicker.open = function($event, instance) {
        $event.preventDefault();
        $event.stopPropagation();

        ReportController.isOpened[instance] = true;
    };

    /* INITIALIZE DATES FOR THE DATE PICKER SERVICE*/
    initializeDates();

    /* SUBMIT THE REPORT */
    function submit(form, action) {
        //set form submitted to true here since we are not using form submission to process saving of data
        //as we require different parameters to be passed in by different buttons.
        form.$submitted = true;

        //clear any previous alerts
        alertService.clear();

        //if there are any form errors, don't continue
        if( form.$valid !== true ) {
            sendAlert('danger', 'Required field(s) missing.');
            return;
        }

        //set parameter array
        var params = [];

        //build the parameter array to build the query string
        for( key in ReportController.criteria ) {
            //if it is a date, only send the date and not the date/time
            var value = ReportController.criteria[key];
            if( value instanceof Date ) {
                value = value.toISOString().slice(0,10);
            }

            /*  KLUDGE!!!! */
            /******* fix product_id - a kludge until I find out why undefined is being returned from the server ********/
            if( key == 'product_id' && value == undefined ) { value = null; }

            //add the parameter array
            params.push(key + '=' + value);
        }

        //build query string
        var queryString = encodeURI('?' + params.join('&'));

        //build url
        var url = ReportController.baseUrl + '/report/' + ReportController.criteria.name + '/' + action + '/' + queryString;

        //take action based on type
        switch( action ) {
            //display in browser
            case 'view':
                window.location.href = url;
                break;

            //print
            case 'print':
                popupWindow(url, 'Report', 1200, 800);
                break;

            //excel
            case 'excel':
                window.open(url, '_self');
                //popupWindow(url, 'Report', 1200, 800);
                break;

            //all others do nothing for now
            default:
                break;
        }
    }

    /* AFTER CLIENT SELECTION, GO GET PRODUCT DATA */
    function selectClient() {
        //make sure we do need to get product list
        if( typeof ReportController.criteria.product_id == 'undefined' ) { return; }

        //pre-fill the drop down with all products
        ReportController.SearchSelectProduct.searchTerm = '';
        ReportController.SearchSelectProduct.getData(true);
    }

    /* SEARCH PRODUCT LIST - We are wrapping the service method so we can check for valid warehouse & client id */
    function searchProduct() {
        //if warehouse and/or client is not set, then reset the data and don't look for products
        if( ReportController.criteria.warehouse_id == null || ReportController.criteria.client_id == null ) {
            //clear out the existing data
            ReportController.items.length = 0;
            ReportController.displayItems.length = 0;

            //exit
            return;
        }

        //go search since we have a valid warehouse and client id
        ReportController.SearchSelectProduct.search();
    }

    /* UPDATE PRODUCT DATA */
    function selectProduct(product) {
        //update the input box
        ReportController.SearchSelectProduct.searchTerm = product.sku;

        //set the product id criteria
        ReportController.criteria.product_id = product.id;
    }

    /* CLEAR OUT THE SEARCH TERM AND RESET THE PRODUCT ID */
    function clearProductSearch() {
        ReportController.criteria.product_id = null;
        ReportController.SearchSelectProduct.clear();
    }

    /* INITIALIZE DATE CRITERIAS AS DATE OBJECTS TO WORK WITH THE DATE PICKER SERVICE */
    function initializeDates() {
        for( key in ReportController.criteria ) {
            //if it is a date, initialize it as a date object
            var value = ReportController.criteria[key];
            if( typeof value == 'string' && key.search('_date') >= 0 ) {
                ReportController.criteria[key] = new Date(value)
            }
        }
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

    /* Helper function to get object by id value */
    function getObjectById(data, id) {
        for( var i = 0; i < data.length; i++ ) {
            if( data[i].id == id ) { return data[i]; }
        }
        return {};
    }
});