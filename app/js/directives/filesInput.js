 (function() {
	'use strict';

	devRequestApp.directive("filesInput", function() {
	return {
		require: "ngModel",
		link: function postLink(scope, elem, attrs, ngModel) {
				elem.on("change", function() {
					ngModel.$setViewValue(elem[0].files);
				});
			}
		};
	});
})();