"use strict";
(function ($) {
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
			let wrapper = $scope.find(".noobs-quiz-options"); // Find quiz options wrapper
			let nextBtn = $scope.find(".swiper-button-next"); // next button
			const form = $scope.find("#noobs-quiz-question-form"); //form inside scope
			const username = $scope.find(".noobs-quiz-username input");
			$(username).on("input", function (e) {
				if(e.target.value.length > 0){
					console.log(e.target.value);
				}
			});

			$(nextBtn).fadeOut(); //next button initially fadeout
			$(wrapper).on("click", function (e) {
				let radioButton = $(this).find("input[type=radio]"); //radio button
				$(wrapper).removeClass("active"); //remove active from all options wrapper
				$(this).addClass("active"); // add active class in the on click options
				//$(wrapper).attr("checked", false);
				$(radioButton).attr("checked", true); //add checked on click in input radio
				let oldCheck = $(radioButton).closest(".noobs-quiz-options").siblings().find("input[checked]"); // find before checked for taggle
				if (oldCheck.length > 0) {
					oldCheck.attr("checked", false); //if find toogle checked then remove without last checked
				}
				if (radioButton.attr("checked")) {
					nextBtn.fadeIn(200); //if checked then next button show
					// if all slide is done then swiper add class swiper-button-disabled in next button
					if ($(nextBtn).hasClass("swiper-button-disabled")) {
						$(nextBtn).fadeOut(); //when get swiper-button-disabled class then hide next button
						$(form).find(".noobs-quiz-button-submit").fadeIn(200); //instead of next button add submit buttton
					}
				}
			});

			$(nextBtn).click(function (e) {
				$(this).fadeOut(); //initially after every click next button it will be hide
			});

			const swiperConfig = {
				allowSlidePrev: false, //no need to slide bqack
				allowTouchMove: false, //no need to slide with touch
				pagination: {
					el: ".swiper-pagination",
					type: "fraction", //pagination type
				},
				navigation: {
					nextEl: ".swiper-button-next" //next button
				},
			}

			/**
			 * Swiper initilize with new elementor code and backward support for old elementor user
			 * @source https://developers.elementor.com/experiment-optimized-asset-loading/
			 */
			if ('undefined' === typeof Swiper) {
				const asyncSwiper = elementorFrontend.utils.swiper;

				new asyncSwiper($scope.find(".noobs-quiz"), swiperConfig).then((newSwiperInstance) => {
					const mySwiper = newSwiperInstance;
				});
			} else {
				const mySwiper = new Swiper($scope.find(".noobs-quiz"), swiperConfig);
			}

			$(form).on("submit", function (e) {
				e.preventDefault(); //no need to reload
				const formData = $(this).serialize(); //form data in serialize
				$.post(noobsQuizAjax.ajax_url, formData,
					function (res) {
						if (!res.success) {
							Swal.fire({
								title: 'Something Went Wrong!',
								text: `${res.success.data.message}`,
								icon: 'error',
								confirmButtonText: 'OK'
							})
						} else {
							Swal.fire({
								title: 'Hurrah!',
								text: 'Your Quiz is submitted!',
								showDenyButton: true,
								confirmButtonText: 'Show Answer',
								denyButtonText: 'Play Again',
								icon: 'success',
								backdrop: false,
							}).then((result) => {
								if(result.isConfirmed){
									location.href = "http://localhost/quiz/";
								}

								if(result.isDenied){
									window.reload();
								}
							})
						}
					}
				)
			});
		});
	});
})(jQuery)