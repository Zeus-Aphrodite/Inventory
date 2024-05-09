$(document).ready(function () {
	var searchOrderValue = "";
	var flag1 = false;
	var flag2 = false;
	var startDate = "";
	var endDate = "";
	var ajaxData = {};
	var des_id = {};
	var ordersForEveryUser = "";

	$(".updateOrderManager").on("input", function () {
		$(this).removeClass("is-invalid");
		let selectedValue = $(this).val();
		if (selectedValue < 0) {
			$(this).addClass("is-invalid");
			$(this).val(0);
		} else {
			let len = $("#dest_len_m").attr("colspan");
			let i = $(this).attr("k");
			var sum = 0;
			for (let j = 0; j < len; j++) {
				sum += Number($("#dest_val_m_" + i + "_" + j).val());
			}
			var goodInventory = Number($("#goodInventory_" + i).text());
			var remainGoods = Number($("#remain_inventory_m" + i).text());
			if (sum > goodInventory || remainGoods - selectedValue < 0) {
				$(this).val(0);
				$(this).addClass("is-invalid");
			} else {
				$("#order_sum_m" + i).text(sum);
				$("#remain_inventory_m" + i).text(goodInventory - sum);
			}
		}
	});

	$(".updateOrdersButtonClass").click(function () {
		var lengthOfDatas = Number($("#lengthOfDatas").val());
		var orderStatus = $("#orderStatus").val();
		var estimate_delivery_date = $("#estimate_delivery_date").val();
		var orderId = $("#lengthOfDatas").attr("orderId");
		var dest_num = Number($("#dest_len_m").attr("colspan"));
		var datas = [];

		for (let i = 0; i < lengthOfDatas; i++) {
			var dest_val_m = [];
			var dest_id_m = [];
			for (let j = 0; j < dest_num; j++) {
				dest_val_m[j] = $("#dest_val_m_" + i + "_" + j).val();
				dest_id_m[j] = $("#dest_val_m_" + i + "_" + j).attr("destinationId");
			}

			var goodId = $("#goodIdOfEachValue_" + i).val();
			var data = {
				dest_val_m: dest_val_m,
				dest_id_m: dest_id_m,
				goodId: goodId,
			};
			datas.push(data);
		}
		var ajaxData = {
			datas: datas,
			orderStatus: orderStatus,
			estimate_delivery_date: estimate_delivery_date,
			dest_num: dest_num,
		};
		$.ajax({
			url: `/orders/${orderId}`,
			method: "PUT",
			data: ajaxData,
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
			},
			success: function (res, status) {
				$("#orderToast").show();
				setTimeout(function () {
					$("#orderToast").fadeOut(1000);
				}, 2000);
				setTimeout(function () {
					location.reload();
				}, 3000);
			},
			error: function (xhr, status, error) {
				console.log("error");
			},
		});
	});

	$("#orderRequestButton").click(function () {
		var delivery_date = $("#delivery_date").val();
		if (delivery_date == "") return;
		var lengthOfDatas = $("#lengthOfDatas").val();
		var dest_len = Number($("#dest_len").attr("colspan"));
		var dest_id = [];
		var dest_Loc = [];

		for (let i = 0; i < dest_len; i++) {
			dest_id[i] = $("#des_id_" + i).val();
			dest_Loc[i] = $("#des_" + i).val();
		}

		var datas = [];
		for (let i = 0; i < lengthOfDatas; i++) {
			var dest_good_val = [];
			for (let j = 0; j < dest_len; j++) {
				dest_good_val[j] = $("#dest_val_" + i + "_" + j).val()
					? $("#dest_val_" + i + "_" + j).val()
					: 0;
			}

			var goodId = $("#goodIdOfEachValue_" + i).val();
			var data = {
				dest_good_val: dest_good_val,
				dest_id: dest_id,
				dest_Loc: dest_Loc,
				goodId: goodId,
			};
			datas.push(data);
		}
		ajaxData = {
			datas: datas,
			delivery_date: delivery_date,
			dest_id: dest_id,
		};
	});
	$("#confirmOrderRequest").click(function () {
		$("#cancelOrderRequest").click();
		$.ajax({
			url: `/orders`,
			method: "POST",
			data: ajaxData,
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
			},
			success: function (res, status) {
				// $("#cancelOrderRequest").click();
				$("#newOrderToast").addClass("bg-success");
				$("#newOrderToast").show();
				setTimeout(function () {
					$("#newOrderToast").fadeOut(1000);
				}, 2000);
				setTimeout(function () {
					location.reload();
				}, 3000);
			},
			error: function (xhr, status, error) {
				console.log("error");
			},
		});
	});

	$(".orderNumber").on("input", function () {
		$(this).removeClass("is-invalid");
		var rowNumber = $(this).attr("key");
		var value = $(this).val();
		if (value < 0) {
			$(this).addClass("is-invalid");
			$(this).val(0);
		} else {
			var remainGoods = Number($("#remainOrder_" + rowNumber).text());
			var goodsInventory = Number($("#goodsInventory_" + rowNumber).text());
			let len = $("#dest_len").attr("colspan");
			var sum = 0;
			for (let j = 0; j < len; j++) {
				sum += Number($("#dest_val_" + rowNumber + "_" + j).val());
			}
			if (remainGoods - value < 0) {
				$(this).val(0);
				$(this).addClass("is-invalid");
			} else {
				$("#orderSum_" + rowNumber).text(sum);
				$("#remainOrder_" + rowNumber).text(goodsInventory - sum);
			}
		}
	});

	$("#searchUserOrderField").on("input", function () {
		var value = $("#searchUserOrderField").val();
		searchOrderValue = value;
		$("#searchUserOrderButton").attr(
			"href",
			`/orders?value=${searchOrderValue}`
		);
	});
	$("#periodStartDate").change(function () {
		flag1 = true;
		startDate = $("#periodStartDate").val();
		var tmpLink = $("#searchWithDate").attr("href");
		if (flag2) {
			$("#searchWithDate").attr("href", tmpLink + "&startDate=" + startDate);
		} else {
			$("#searchWithDate").attr("href", tmpLink + "?startDate=" + startDate);
		}
		if (flag2) {
			var event = new MouseEvent("click", {
				bubbles: true,
				cancelable: true,
				view: window,
			});
			document.getElementById("searchWithDate").dispatchEvent(event);
		}
	});
	$("#periodEndDate").change(function () {
		flag2 = true;
		endDate = $("#periodEndDate").val();
		var tmpLink = $("#searchWithDate").attr("href");
		if (flag1) {
			$("#searchWithDate").attr("href", tmpLink + "&endDate=" + endDate);
		} else {
			$("#searchWithDate").attr("href", tmpLink + "?endDate=" + endDate);
		}
		if (flag1) {
			var event = new MouseEvent("click", {
				bubbles: true,
				cancelable: true,
				view: window,
			});
			document.getElementById("searchWithDate").dispatchEvent(event);
		}
	});
	$("#tmpOrdersUploadButton").click(function () {
		// userId = $(this).attr("userId");
		$("#ordersFormFileUpload").click();
	});
	$("#ordersFormFileUpload").change(function () {
		var form = new FormData();
		var files = $("#ordersFormFileUpload")[0].files;

		if (files.length > 0) {
			form.append("file", files[0]);
			$.ajax({
				url: `/orders/orderRequest/upload`,
				method: "POST",
				data: form,
				contentType: false,
				processData: false,
				headers: {
					"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
				},
				success: function (res) {
					if (res == "success") {
						$("#newOrderToast").addClass("bg-success");
						$("#newOrderToast").show();
						setTimeout(function () {
							$("#newOrderToast").fadeOut(1000);
						}, 2000);
						// setTimeout(function () {
						//     location.reload();
						// }, 3000);
					} else {
						$("#newOrderToast").addClass("bg-danger");
						$("#newOrderToastValue").text("登録できません。");
						$("#newOrderToast").show();
						setTimeout(function () {
							$("#newOrderToast").fadeOut(1000);
						}, 2000);
						// setTimeout(function () {
						//     location.reload();
						// }, 3000);
					}
				},
				error: function (xhr, status, error) {
					$("#newOrderToast").addClass("bg-danger");
					$("#newOrderToastValue").text("登録できません。");
					$("#newOrderToast").show();
					setTimeout(function () {
						$("#newOrderToast").fadeOut(1000);
					}, 2000);
					// setTimeout(function () {
					//     location.reload();
					// }, 3000);
				},
			});
		}
	});
	$("#ordersForEveryUserField").on("input", function () {
		var value = $(this).val();
		ordersForEveryUser = value;
		$("#searchOrdersForEveryUser").attr(
			"href",
			`/orders?ordersForEveryUser=${ordersForEveryUser}`
		);
	});
});
