(function() {
	'use strict';

	devRequestApp.directive('autoFocus', ['$timeout', function($timeout) {
		return {
			restrict: 'A',
			link : function($scope, $element) {
				$timeout(function() {
					$element[0].focus();
				});
			}
		}
	}]);
})();