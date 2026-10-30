
    var lang = null;
    var tester_datatable = agent_datatable = topupMgm_datatable = withdrawMgm_datatable = vipBuyMgm_datatable = memberList_datatable = grabList_datatable = onlineGrab_datatable = productList_datatable = memberAction_datatable = noticeType_datatable = msgMgm_datatable = memberLevelConfig_datatable = taskConfig_datatable = bannerConfig_datatable = adminList_datatable = authMgm_datatable = settingMgm_datatable = menuMgm_datatable = null;

    $(document).ready(function() {
        lang = "{{Session::get('locale')}}"; 

        initPusher();

        tester_datatable = $('#tester_list_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            "order": [
                [1, "asc"]
            ],
        });

        agent_datatable = $('#agent_list_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            "order": [
                [1, "asc"]
            ],
            processing: true,
            serverSide: true,
            paging: true,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getAgentListJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        search: $('#agent_list_datatable_filter input').val(),
                        records_start: data.start,
                        page_size: data.length,
                        sort_column: s.column,
                        sort_order: s.dir,
                        member_type: 3,
                        isActive: 1,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    'targets': [0],
                    'orderable': false,
                    "render": function(data, type, row) {
                        return r = '<input type="checkbox" class="chk_select_agent" name="select_user[]" value="' + row.id + '">';
                    }
                },
                {
                    data: "id"
                },
                {
                    data: "contact_no"
                },
                {
                    data: "username"
                },
                {
                    data: "full_name"
                },
                {
                    data: "register_at"
                },
                {
                    data: "login_at"
                },
                {
                    data: "total_downline"
                },
                {
                    data: "balance"
                },
                {
                    data: "available_withdraw_amt"
                },
                {
                    data: "frozenCapital"
                },
                {
                    data: "rank"
                },
                {
                    data: "dailyGrabLimit"
                },
                {
                    data: "totalGrabToday"
                },
                {
                    data: "referral"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "<button type='button' class='btn btn-primary btn-min-80 margin-5' onclick='openEditUserModal(\"" + row.member_code + "\", \"agent_list_datatable\")'>@lang('lang.edit')</button>";

                        r += "<button type='button' class='btn btn-danger btn-min-80 margin-5' onclick='deleteAgent(\"" + row.member_code + "\")'>@lang('lang.delete')</button>";

                        r += "<button type='button' class='btn btn-success btn-min-80 margin-5' onclick='openMemberTopupModal(\"" + row.member_code + "\")'>@lang('lang.topup')</button>";

                        r += "<button type='button' class='btn btn-info btn-min-80 margin-5' onclick='openCapitalHistoryModal(\"" + row.member_code + "\")'>@lang('lang.capitalHistory')</button>";

                        r += "<button type='button' class='btn btn-danger btn-min-80 margin-5' onclick='openMemberAdjAmtModal(\"" + row.member_code + "\")'>@lang('lang.adjAmt')</button>";

                        return r;
                    }
                },
            ]
        });

        topupMgm_datatable = $('#topupMgm_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            processing: true,
            serverSide: true,
            paging: true,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getTopupListJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        status: $('#search_topup_status').val(),
                        search: $('#topupMgm_datatable_filter input').val(),
                        records_start: data.start,
                        page_size: data.length,
                        sort_column: s.column,
                        sort_order: s.dir,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    data: "id"
                },
                {
                    data: "contact_no"
                },
                {
                    data: "bank_acc_no"
                },
                {
                    data: "topup_type"
                },
                {
                    data: "balance"
                },
                {
                    data: "topup_amt"
                },
                {
                    "mRender": function(data, type, row) {
                        if (row.receipt != '')
                            var r = '<img src="' + row.receipt + '" style="max-width: 200px; max-height: 200px;" />';
                        else
                            var r = '-';

                        return r;
                    }
                },
                {
                    data: "created_at"
                },
                {
                    data: "topup_status"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = '';

                        return r;
                    }
                },
            ]
        });

        withdrawMgm_datatable = $('#withdrawMgm_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            processing: true,
            serverSide: true,
            paging: true,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getWithdrawListJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        status: $('#search_withdraw_status').val(),
                        search: $('#withdrawMgm_datatable input').val(),
                        records_start: data.start,
                        page_size: data.length,
                        sort_column: s.column,
                        sort_order: s.dir,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    data: "contact_no"
                },
                {
                    data: "withdraw_amt"
                },
                {
                    data: "bank_holder_name"
                },
                {
                    data: "bank_name"
                },
                {
                    data: "bank_acc_no"
                },
                {
                    data: "created_at"
                },
                {
                    data: "status"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = '';

                        if (row.wth_status == 0) {
                            r += "<button type='button' class='btn btn-success btn-min-80 margin-5' onclick='openApproveWthModal(\"" + row.wth_ref + "\")'>@lang('lang.passToRemitAmt')</button>";

                            r += "<button type='button' class='btn btn-danger btn-min-80 margin-5' onclick='openRejectWthModal(\"" + row.wth_ref + "\")'>@lang('lang.notPass')</button>";
                        }
                        return r;
                    }
                },
            ]
        });

        vipBuyMgm_datatable = $('#vipBuyMgm_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
        });

        memberList_datatable = $('#memberList_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            "order": [
                [1, "asc"]
            ],
            processing: true,
            serverSide: true,
            paging: true,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getMemberListJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        search: $('#memberList_datatable_filter input').val(),
                        records_start: data.start,
                        page_size: data.length,
                        sort_column: s.column,
                        sort_order: s.dir,
                        isActive: $('#search_member_status').val(),
                        member_type: 1,
                        // member_type: $('#search_member_char').val(),: $('#search_member_char').val(),
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    'targets': [0],
                    'orderable': false,
                    "render": function(data, type, row) {
                        return r = '<input type="checkbox" class="chk_select_member" name="select_user[]" value="' + row.id + '">';
                    }
                },
                {
                    data: "id"
                },
                {
                    data: "member_type"
                },
                {
                    data: "contact_no"
                },
                {
                    data: "full_name"
                },
                {
                    data: "register_at"
                },
                {
                    data: "login_at"
                },
                {
                    data: "total_downline"
                },
                {
                    data: "balance"
                },
                {
                    data: "available_withdraw_amt"
                },
                {
                    data: "frozenCapital"
                },
                {
                    data: "rank"
                },
                {
                    data: "dailyGrabLimit"
                },
                {
                    data: "totalGrabToday"
                },
                {
                    data: "todayCommIncome"
                },
                {
                    data: "todayCommReceived"
                },
                {
                    data: "referral"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "<button type='button' class='btn btn-primary btn-min-80 margin-5' onclick='openEditUserModal(\"" + row.member_code + "\", \"memberList_datatable\")'>@lang('lang.edit')</button>";

                        r += "<button type='button' class='btn btn-success btn-min-80 margin-5' onclick='openMemberTopupModal(\"" + row.member_code + "\")'>@lang('lang.topup')</button>";

                        r += "<button type='button' class='btn btn-info btn-min-80 margin-5' onclick='openCapitalHistoryModal(\"" + row.member_code + "\")'>@lang('lang.capitalHistory')</button>";

                        r += "<button type='button' class='btn btn-danger btn-min-80 margin-5' onclick='openMemberAdjAmtModal(\"" + row.member_code + "\")'>@lang('lang.adjAmt')</button>";

                        r += "<button type='button' class='btn btn-boostrap-info btn-min-80 margin-5' onclick='openMemberSetReferralModal(\"" + row.member_code + "\", "+row.referral+")'>@lang('lang.setReferral')</button>";

                        return r;
                    }
                },
            ],
        });

        grabList_datatable = $('#grabList_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            processing: true,
            serverSide: true,
            paging: true,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                var status = $('#search_grab_task_by_status').val();
                if (status == '')
                    status = 2;

                $.ajax({
                    url: '/getTaskListJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        search: $('#grabList_datatable_filter input').val(),
                        records_start: data.start,
                        page_size: data.length,
                        sort_column: s.column,
                        sort_order: s.dir,
                        cat: $('#search_grab_task_by_cat').val(),
                        status: status,
                        // member_type: $('#search_member_char').val(),: $('#search_member_char').val(),
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    data: "id"
                },
                {
                    data: "full_name"
                },
                {
                    data: "contact_no"
                },
                {
                    data: "balance"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "";

                        r += row.bank_name + "<br>" + row.bank_acc_no + "<br>" + row.bank_holder_name;

                        return r;
                    }
                },
                {
                    data: "task_cat"
                },
                {
                    data: "product_name"
                },
                {
                    data: "product_price"
                },
                {
                    data: "tl_task_cat_comm"
                },
                {
                    data: "tl_get_comm_amt"
                },
                {
                    data: "tl_1st_downline_comm_amt"
                },
                {
                    data: "tl_2nd_downline_comm_amt"
                },
                {
                    data: "tl_3rd_downline_comm_amt"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "";

                        r += '<span style="color: ' + row.color + '">' + row.status + '</span>';

                        return r;
                    }
                },
                {
                    data: "created_at"
                },
                {
                    data: "updated_at"
                },
            ],
        });

        onlineGrab_datatable = $('#onlineGrab_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            "order": [
                [1, "asc"]
            ],
            processing: true,
            serverSide: true,
            paging: true,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getTaskListJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        search: $('#onlineGrab_datatable_filter input').val(),
                        records_start: data.start,
                        page_size: data.length,
                        sort_column: s.column,
                        sort_order: s.dir,
                        status: 0,
                        // member_type: $('#search_member_char').val(),: $('#search_member_char').val(),
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    'targets': [0],
                    'orderable': false,
                    "render": function(data, type, row) {
                        return r = '<input type="checkbox" class="chk_select_task" name="select_task[]" value="' + row.tl_id + '">';
                    }
                },
                {
                    data: "id"
                },
                {
                    data: "member_type"
                },
                {
                    data: "contact_no"
                },
                {
                    data: "full_name"
                },
                {
                    data: "created_at"
                },
                {
                    data: "register_at"
                },
                {
                    data: "login_at"
                },
                {
                    data: "balance"
                },
                {
                    data: "available_withdraw_amt"
                },
                {
                    data: "frozenCapital"
                },
                {
                    data: "rank"
                },
                {
                    data: "dailyGrabLimit"
                },
                {
                    data: "totalGrabToday"
                },
                {
                    data: "todayCommIncome"
                },
                {
                    data: "todayCommReceived"
                },
                {
                    data: "referral"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "";

                        return r;
                    }
                },
            ],
        });

        productList_datatable = $('#productList_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterProductName')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            "order": [
                [1, "asc"]
            ],
            processing: true,
            serverSide: true,
            paging: true,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getProductConfigJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        search: $('#productList_datatable_filter input').val(),
                        records_start: data.start,
                        page_size: data.length,
                        sort_column: s.column,
                        sort_order: s.dir,
                        status: 1,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    'targets': [0],
                    'orderable': false,
                    "render": function(data, type, row) {
                        return r = '<input type="checkbox" class="chk_select_product" name="select_product[]" value="' + row.product_id + '">';
                    }
                },
                {
                    data: "product_id"
                },
                {
                    data: "product_name"
                },
                {
                    data: "product_cat"
                },
                {
                    data: "product_price"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = '<img src="' + row.product_img + '" style="max-width: 150px; max-height: 150px;" />';
                        return r;
                    }
                },
                {
                    data: "cat_task_comm"
                },
                {
                    data: "cat_task_min_amt"
                },
                {
                    data: "total_task"
                },
                {
                    data: "totak_accpeted_task"
                },
                {
                    data: "created_at"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "<button type='button' class='btn btn-primary btn-min-80 btn-edit-product' onclick='openEditProductModal(\"" + row.product_code + "\")'>@lang('lang.edit')</button>";

                        r += "&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-min-80 btn-edit-product' onclick='deleteProduct(\"" + row.product_code + "\")'>@lang('lang.delete')</button>";

                        return r;
                    }
                },
            ],
        });

        memberAction_datatable = $('#memberAction_datatable').DataTable({
            "bLengthChange": false,
            "pageLength": 20,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterNoticeName')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "paginate": {
                    "previous": "{{Lang::get('lang.previousPage')}}",
                    "next": "{{Lang::get('lang.nextPage')}}",
                },
            },
            "order": [
                [1, "asc"]
            ],
        });

        noticeType_datatable = $('#noticeType_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "filter": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
            "order": [
                [1, "asc"]
            ],
            processing: true,
            serverSide: true,
            paging: false,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getNoticeConfigJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        sort_column: s.column,
                        sort_order: s.dir,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    data: "notice_id"
                },
                {
                    data: "notice_title"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "<button type='button' class='btn btn-primary' onclick='openEditNoticeModal(\"" + row.notice_code + "\")'>@lang('lang.edit')</button>";
                        return r;
                    }
                },
            ],
        });

        msgMgm_datatable = $('#msgMgm_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "filter": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
        });

        memberLevelConfig_datatable = $('#memberLevelConfig_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "filter": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
            processing: true,
            serverSide: true,
            paging: false,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getMemberLevelConfigJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        sort_column: s.column,
                        sort_order: s.dir,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    "render": function(data, type, full, meta) {
                        return meta.row + 1;
                    }
                }, {
                    data: "mrc_name"
                },
                {
                    data: "daily_comm_max_percent"
                },
                {
                    data: "daily_max_snatch_order"
                },
                {
                    data: "withdraw_fees_percent"
                },
                {
                    data: "member_price"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "<button type='button' class='btn btn-primary' onclick='openEditMemberLevelModal(" + row.mrc_id + ")'>@lang('lang.edit')</button>";
                        return r;
                    }
                },
            ],

        });

        taskConfig_datatable = $('#taskConfig_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "filter": false,
            "language": {
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
            },
            processing: true,
            serverSide: true,
            paging: false,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getTaskConfigJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        sort_column: s.column,
                        sort_order: s.dir,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    "render": function(data, type, full, meta) {
                        return meta.row + 1;
                    }
                }, {
                    data: "cat_name"
                },
                {
                    data: "cat_task_comm"
                },
                {
                    data: "cat_task_min_amt"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "<button type='button' class='btn btn-primary' onclick='openEditTaskModal(\"" + row.cat_code + "\")'>@lang('lang.edit')</button>";
                        return r;
                    }
                },
            ],
        });

        bannerConfig_datatable = $('#bannerConfig_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "filter": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
            "order": [
                [1, "asc"]
            ],
            processing: true,
            serverSide: true,
            paging: false,
            ajax: function(data, callback, settings) {
                let s = data['order'][0];
                $.ajax({
                    url: '/getBannerConfigJs',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        sort_column: s.column,
                        sort_order: s.dir,
                    },
                    success: function(data, textStatus, jQxhr) {
                        callback({
                            // draw: data.draw,
                            data: data.Data,
                            recordsTotal: data.TotalRecords,
                            recordsFiltered: data.RecordsFiltered
                        });
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            },
            columns: [{
                    'targets': [0],
                    'orderable': false,
                    "render": function(data, type, row) {
                        return r = '<input type="checkbox" class="chk_select_banner" name="select_banner[]" value="' + row.banner_id + '">';
                    }
                },
                {
                    data: "banner_id"
                },
                {
                    "render": function(data, type, row) {
                        return r = '<img src="' + row.banner_img + '" style="width: 400px;">';
                    }
                },
                {
                    data: "created_at"
                },
                {
                    "mRender": function(data, type, row) {
                        var r = "<button type='button' class='btn btn-primary' onclick='openEditBannerModal(\"" + row.banner_code + "\")'>@lang('lang.edit')</button>";
                        return r;
                    }
                },
            ],
        });

        adminList_datatable = $('#adminList_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterContactNo')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
            "order": [
                [1, "asc"]
            ],
        });

        authMgm_datatable = $('#authMgm_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "filter": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
            "order": [
                [1, "asc"]
            ],
        });

        settingMgm_datatable = $('#settingMgm_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterSettingName')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
        });

        menuMgm_datatable = $('#menuMgm_datatable').DataTable({
            "bLengthChange": false,
            "bInfo": false,
            "language": {
                "processing": '<div class="loader-box"><div class="loader-2"></div></div>',
                "sSearch": "{{Lang::get('lang.search')}}",
                "searchPlaceholder": "{{Lang::get('lang.plsEnterMenuName')}}",
                "zeroRecords": "{{Lang::get('lang.noRecordFound')}}",
            },
        });
    });

    //-----------------------------------------------------------------------------------

    $('.btn-add-product').click(function() {
        $.ajax({
            url: '/getProductCatJs',
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if (data.success != null) {
                    if (data.result.length > 0) {
                        var content = '';
                        $.each(data.result, function(index, c) {
                            content += '<option value="' + c.cat_id + '">' + c.cat_name + '</option>';
                        })
                        $('#addProductModal_product_cat').empty().append(content);
                    }
                }
            },
            error: function(xhr, err, errorThrown) {
                console.log(xhr, err, errorThrown, xhr.responseText);
            }
        });
    });

    //----------------------------- Delete Banner ------------------------------------------

    $('#select_all_banner').click(function() {
        if ($(this).prop("checked") == true) {
            $('.chk_select_banner').prop('checked', true);

        } else if ($(this).prop("checked") == false) {
            $('.chk_select_banner').prop('checked', false);

        }
    });

    $('.btn-delete-banner').click(function() {
        $('#deleteBannerForm').submit();
    });

    //----------------------------- Delete Product ------------------------------------------

    $('#select_all_product').click(function() {
        if ($(this).prop("checked") == true) {
            $('.chk_select_product').prop('checked', true);

        } else if ($(this).prop("checked") == false) {
            $('.chk_select_product').prop('checked', false);

        }
    });

    $('.btn-delete-product').click(function() {
        $('#deleteProductForm').submit();
    });

    function deleteProduct(product_code) {
        if (product_code != null) {
            $.ajax({
                url: '/product/delete-one',
                type: 'post',
                dataType: 'json',
                data: {
                    product_code: product_code,
                    datatable: 'productList_datatable',
                    form: 'deleteProductForm',
                },
                success: function(data) {
                    if (data.success != null) {
                        $('.modal').modal('hide');
                        $('#modal_success_msg').empty().html(data.msg);
                        $('#successModal').modal('toggle');
                    } else {
                        $('.modal').modal('hide');
                        $('#failedModal').modal('toggle');
                        $('#modal_failed_msg').empty().html(data.msg);
                    }

                    $('#' + data.datatable).DataTable().ajax.reload();
                },
                error: function(xhr, err, errorThrown) {
                    console.log(xhr, err, errorThrown, xhr.responseText);
                }
            });
        }
    }

    //----------------------------- Delete Agent ------------------------------------------

    $('#select_all_agent').click(function() {
        if ($(this).prop("checked") == true) {
            $('.chk_select_agent').prop('checked', true);

        } else if ($(this).prop("checked") == false) {
            $('.chk_select_agent').prop('checked', false);

        }
    });

    $('.btn-delete-agent').click(function() {
        $('#deleteAgentForm').submit();
    });

    $('.btn-member-excel').click(function() {
        memberListExcel();
    });

    $('#search_topup_status').on('change', function() {
        topupMgm_datatable.draw();
    });

    $('#search_withdraw_status').on('change', function() {
        withdrawMgm_datatable.draw();
    });

    $('#search_member_status').on('change', function() {
        memberList_datatable.draw();
    });

    // $('#search_member_char').on('change', function() {
    //     memberList_datatable.draw(); 
    // });

    $('#search_grab_task_by_cat').on('change', function() {
        grabList_datatable.draw();
    });

    $('#search_grab_task_by_status').on('change', function() {
        grabList_datatable.draw();
    });

    function deleteAgent(member_code) {
        if (member_code != null) {
            $.ajax({
                url: '/agent-delete-one',
                type: 'post',
                dataType: 'json',
                data: {
                    member_code: member_code,
                    datatable: 'agent_list_datatable',
                    form: 'deleteAgentForm',
                },
                success: function(data) {
                    if (data.success != null) {
                        $('.modal').modal('hide');
                        $('#modal_success_msg').empty().html(data.msg);
                        $('#successModal').modal('toggle');
                    } else {
                        $('.modal').modal('hide');
                        $('#failedModal').modal('toggle');
                        $('#modal_failed_msg').empty().html(data.msg);
                    }

                    $('#' + data.datatable).DataTable().ajax.reload();
                },
                error: function(xhr, err, errorThrown) {
                    console.log(xhr, err, errorThrown, xhr.responseText);
                }
            });
        }
    }

    function reloadStas() {
        $.ajax({
            url: '/reloadStasJs',
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if (data.success != null) {

                    $('#stas_platformUserTotalBalance').text(data.result.platformUserTotalBalance);
                    $('#stas_platformUserAvailableWithdrawBalance').text(data.result.platformUserAvailableWithdrawBalance);
                    $('#stas_platformUserFreezeTotalBalance').text(data.result.platformUserFreezeTotalBalance);
                    $('#stas_totalTopup').text(data.result.totalTopup);
                    $('#stas_totalWithdraw').text(data.result.totalWithdraw);
                    // $('#stas_poolRemaining').text(data.result.poolRemaining);
                    $('#stas_plaftformExpectGainLoss').text(data.result.plaftformExpectGainLoss);

                    $('#stas_todayTotalTopup').text(data.result.todayTotalTopup);
                    $('#stas_todayTotalWithdraw').text(data.result.todayTotalWithdraw);
                    $('#stas_todayTotalGainLoss').text(data.result.todayTotalGainLoss);
                    $('#stas_todayTotalGrabOrder').text(data.result.todayTotalGrabOrder);

                    $('#stas_lazadaOrderAccumulatedComm').text(data.result.lazadaOrderAccumulatedComm);
                    $('#stas_shopeeOrderAccumulatedComm').text(data.result.shopeeOrderAccumulatedComm);
                    $('#stas_amazonOrderAccumulatedComm').text(data.result.amazonOrderAccumulatedComm);

                    $('#stas_accumulatedCommPayout').text(data.result.accumulatedCommPayout);
                }
            },
            error: function(xhr, err, errorThrown) {
                console.log(xhr, err, errorThrown, xhr.responseText);
            }
        });
    }

    function memberListExcel() {
        $.ajax({
            url: '/memberListExcelJs',
            type: 'post',
            dataType: 'json',
            success: function(data) {},
            error: function(xhr, err, errorThrown) {
                console.log(xhr, err, errorThrown, xhr.responseText);
            }
        });
    }

    function initPusher() {
        var pusher = new Pusher('{{ config("app.pusher_key") }}', {
            cluster: '{{ config("app.pusher_cluster") }}'
        });

        var task_pending_channel = pusher.subscribe('task-pending');
        task_pending_channel.bind('task-event', function(data) {
            if (data != undefined && data != null) {
                let v = data[0];
                //action when pusher is trigger. So if have new transaction, this part will receive the data
                grabList_datatable.draw();
            }
        });

        var task_list_channel = pusher.subscribe('task-list');
        task_list_channel.bind('task-event', function(data) {
            if (data != undefined && data != null) {
                let v = data[0];
                //action when pusher is trigger. So if have new transaction, this part will receive the data
                onlineGrab_datatable.draw();
            }
        });

        var member_list_channel = pusher.subscribe('member-list');
        member_list_channel.bind('member-event', function(data) {
            if (data != undefined && data != null) {
                let v = data[0];
                //action when pusher is trigger. So if have new transaction, this part will receive the data

                $('#curr_total_member').empty().html(v.curr_total_member);
                $('#today_total_member').empty().html(v.today_total_member);

                memberList_datatable.draw();
            }
        });

        var topup_channel = pusher.subscribe('handle-topup');
        topup_channel.bind('topup-event', function(data) {
            if (data != undefined && data != null) {
                let v = data[0];
                //action when pusher is trigger. So if have new transaction, this part will receive the data 

                topupMgm_datatable.draw();
            }
        });

        var withdrawal_channel = pusher.subscribe('handle-withdraw');
        withdrawal_channel.bind('withdrawal-event', function(data) {
            if (data != undefined && data != null) {
                let v = data[0];
                //action when pusher is trigger. So if have new transaction, this part will receive the data 

                withdrawMgm_datatable.draw();
            }
        });
    }
 
