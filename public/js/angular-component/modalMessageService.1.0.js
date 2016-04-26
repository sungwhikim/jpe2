angular.module('modalMessageService', [])
    .factory('modalMessageService', function (modalService) {
        var service = {
                showModalMessage: showModalMessage
            };
        return service;

        function showModalMessage(type, message) {
            //close any existing messages


            //set message
            modalService.modalMessage = message;

            //show dialog
            modalService.showModal({
                templateUrl: "/js/angular-component/modalService-message-" + type + ".html",
                controller: "MessageController"
            }).then(function(modal) {
                modal.element.modal();
                modal.close.then(function(result) {
                });
            });
        }
    });