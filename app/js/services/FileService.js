(function() {
    'use strict';

	devRequestApp.factory('FileService', ['$http', function($http) {
		var factory = {};

		factory.uploadFile = function(files) {
			var fd = new FormData();
			var files = document.getElementById('added-file').files[0];
			fd.append('file',files);

			// AJAX request
			return $http({
				method: 'post',
				url: 'http://vz26824.iservices.db.de/dev-requests/public/api/controllers/upload.php',
				data: fd,
				headers: {'Content-Type': undefined},
			});
		}

		return factory;
	}]);
})();