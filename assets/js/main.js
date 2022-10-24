"use strict";
(function ($) {
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
			let wrapper = $scope.find(".noobs-quiz-options"); // Find quiz options wrapper
			let nextBtn = $scope.find(".swiper-button-next"); // next button
			const form = $scope.find("#noobs-quiz-question-form"); //form inside scope
			const redirectUrl = $scope.find(".noobs-quiz-content").data("quiz");

			$(document).keypress(function (e) {
				if (e.key === "Enter") {
					e.preventDefault();
				}
			});

			$(nextBtn).fadeOut(); //next button initially fadeout
			$(wrapper).on("click", function (e) {
				let radioButton = $(this).find("input[type=radio]"); //radio button
				$(wrapper).removeClass("active"); //remove active from all options wrapper
				$(this).addClass("active"); // add active class in the on click options
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

			$(form).on("submit", submitHandler);

			async function submitHandler(e) {
				e.preventDefault(); //no need to reload
				const formData = $(this).serialize(); //form data in serialize array
				if (formData) {
					const { value: username } = await Swal.fire({
						title: 'Your Name',
						input: 'text',
						backdrop: false,
						inputPlaceholder: 'Enter your Name...'
					})

					if (username.length > 0) {
						const date = new Date();
						const validUsername = JSON.stringify(username + date.getTime()); //valid string username
						let newFormData = formData.replace("noobs_quiz_username=",`noobs_quiz_username=${validUsername}`);
						$.post(noobsQuizAjax.ajax_url, newFormData,
							function (res) {
								if (!res.success) {
									//Show when got not success request
									Swal.fire({
										title: 'Something Went Wrong!',
										text: `${res.success.data.message}`,
										icon: 'error',
										confirmButtonText: 'OK'
									}).then((res) => {
										if (res.isConfirmed) {
											window.reload();
										}
									})
								} else {
									//Show when got success request
									Swal.fire({
										title: 'Hurrah!',
										text: `${validUsername}! Your Quiz is submitted successfully!`,
										showDenyButton: true,
										confirmButtonText: 'Show Answer',
										denyButtonText: 'Play Again',
										icon: 'success',
										backdrop: false,
									}).then((result) => {
										if (result.isConfirmed) {
											location.href = `${redirectUrl}`;
										} else {
											location.reload();
										}
									})
								}
							}
						)
					}else{
						location.reload();
					}
				}
			}
		});
	});
})(jQuery)