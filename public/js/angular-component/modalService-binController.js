//var appAMS = angular.module('angularModalService');

app.controller('BinController', ['$scope', 'close', 'modalService', 'item', function($scope, close, modalService, item) {
    //set data
    $scope.item = item;
    $scope.bins = item.bins;
    $scope.remainingQuantity = calculateQuantity();
    $scope.newItem = {};

    //assign methods
    $scope.calculateQuantity = calculateQuantity;

    //add new bin
    $scope.addBin = function() {
        //set properties to complete object
        $scope.newItem.id = null;
        $scope.newItem.inventory = 0;
        $scope.newItem.aisle = $scope.newItem.aisle.toUpperCase();

        //add to list
        $scope.bins.splice(0, 0, angular.copy($scope.newItem));

        //recalculate remaining quantity
        calculateQuantity();
    };

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

    //calculate the remaining quantity after new item quantity is entered
    $scope.calculateQuantityNewBin = function() {
        //recalculate remaining quantity
        calculateQuantity();

        //subtract the new bin quantity
        if( !isNaN($scope.newItem.quantity) ) { $scope.remainingQuantity -= $scope.newItem.quantity; }
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