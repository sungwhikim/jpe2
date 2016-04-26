var appAMS = angular.module('angularModalService');

appAMS.controller('MessageController', ['$scope', 'close', 'modalService', function($scope, close, modalService) {

  $scope.modalMessage = modalService.modalMessage;

  $scope.close = function(result) {
 	  close(result, 500); // close, but give 500ms for bootstrap to animate
  };

}]);