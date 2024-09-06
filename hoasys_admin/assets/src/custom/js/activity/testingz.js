function initactivity() {
	$.ajax({
		type: "POST",
		url: `${base_url}/election/save_sample`,
		// data: {
		// 	title: $("#ann_title").val(),
		// 	desc: $("#ann_description").val(),
		// },
		cache: false,
		success: function () {
			// success
		},
		complete: function () {
			// Hide loading overlay after the AJAX call is completed
			// $("#loading-overlay").hide();
		},
	});
}
$(function () {
	initactivity();
});
