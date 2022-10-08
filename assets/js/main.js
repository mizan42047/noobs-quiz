"use strict";
(function ($) {
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
			let wrapper = $scope.find(".noobs-quiz-options");
			$(wrapper).on("click", function (e) {
				let radioButton = $(this).find("input[type=radio]");
				$(wrapper).removeClass("active");
				$(this).addClass("active");
				$(wrapper).find("input[type=radio]").attr("checked", false);
				$(radioButton).attr("checked", true);
			});
		} );
	});
})(jQuery)