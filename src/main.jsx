import "./assets/sass/style.scss";

/**
 * Main js file
 *
 * @since 1.0.0
 */

jQuery(document).ready(function ($) {
	/**
	 * Signup form
	 *
	 * @since 1.0.0
	 */
	$(".wp_ckn_signup-form").on("submit", function (e) {
		e.preventDefault();
		//form
		const form = $(this);
		//get button
		const button = form.find("button");
		//email
		const email = form.find("input[name='email']").val();

		//send ajax request
		$.ajax({
			url: wp_ckn_object.ajax_url,
			type: "POST",
			data: {
				action: "wp_ckn_signup",
				email,
				nonce: wp_ckn_object.nonce,
			},
			beforeSend: function (xhr) {
				//clear notice message
				$(".wp_ckn_notice-message")
					.fadeOut()
					.text("")
					.removeClass("success");
				//disable button
				button.prop("disabled", true);
				//change button text
				button.text("Signing up...");
			},
			success: function (response) {
				//enable button
				button.prop("disabled", false);
				//change button text
				button.text("Sign up");

				//check if response is success
				if (response.success) {
					$(".wp_ckn_notice-message")
						.addClass("success")
						.html(response.data.message)
						.fadeIn();
				} else {
					$(".wp_ckn_notice-message")
						.text(response.data.message)
						.fadeIn();
				}
			},
			error: function (xhr, status, error) {
				//enable button
				button.prop("disabled", false);
				//change button text
				button.text("Sign up");

				console.log(xhr, status, error);
				$(".wp_ckn_notice-message")
					.text("An error occurred. Please try again.")
					.fadeIn();
			},
		});
	});

	/**
	 * Login form
	 *
	 * @since 1.0.0
	 */
	$(".wp_ckn_login-form").on("submit", function (e) {
		e.preventDefault();
		//form
		const form = $(this);
		//get button
		const button = form.find("button");
		//email
		const email = form.find("input[name='email']").val();

		//send ajax request
		$.ajax({
			url: wp_ckn_object.ajax_url,
			type: "POST",
			data: {
				action: "wp_ckn_login",
				email,
				nonce: wp_ckn_object.nonce,
			},
			beforeSend: function (xhr) {
				//clear notice message
				$(".wp_ckn_notice-message")
					.fadeOut()
					.text("")
					.removeClass("success");
				//disable button
				button.prop("disabled", true);
				//change button text
				button.text("Logging in...");
			},
			success: function (response) {
				//enable button
				button.prop("disabled", false);
				//change button text
				button.text("Login");

				if (response.success) {
					$(".wp_ckn_notice-message")
						.addClass("success")
						.html(response.data.message)
						.fadeIn();
					//set timeout to redirect
					setTimeout(() => {
						window.location.href = response.data.redirect_url;
					}, 2000);
				} else {
					$(".wp_ckn_notice-message")
						.text(response.data.message)
						.fadeIn();
				}
			},
			error: function (xhr, status, error) {
				//enable button
				button.prop("disabled", false);
				//change button text
				button.text("Login");

				console.log(xhr, status, error);
				$(".wp_ckn_notice-message")
					.text("An error occurred. Please try again.")
					.fadeIn();
			},
		});
	});

	/**
	 * Logout button
	 *
	 * @since 1.0.0
	 */
	$(".wp_ckn_button").on("click", function () {
		//redirect to logout url
		window.location.href = wp_ckn_object.logout_url;
	});
});
