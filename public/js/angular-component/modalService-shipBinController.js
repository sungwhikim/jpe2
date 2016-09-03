
app.controller('ShipBinController', ['$scope', 'close', 'modalService', 'item', function($scope, close, modalService, item) {
    //set data
    $scope.item = item;
    $scope.bins = item.bins;
    $scope.remainingQuantity = calculateQuantity();

    //assign methods
    $scope.calculateQuantity = calculateQuantity;

    //calculates the quantities entered
    function calculateQuantity() {
        var total = (0 + $scope.item.quantity); //this weird calculation is done so the values are not linked

        for( counter = 0; counter < $scope.bins.length; counter++ ) {
            var qty = ( isNaN($scope.bins[counter].quantity) ) ? 0 : $scope.bins[counter].quantity;
            total -= qty;
        }

        $scope.remainingQuantity = total;
        return total;
    };

    //close window and send back data
    $scope.close = function(result) {
        //make sure the total is correct
        calculateQuantity();

        if( $scope.remainingQuantity !== 0 ) {
            /* ADD ERROR MESSAGE HERE */
            return;
        }

        close(result, 500); // close, but give 500ms for bootstrap to animate
    };

}]);