var main = function() {

    // Making sortable tables active on page load
    //http://tablesorter.com/
    $(".tablesorter").tablesorter({
        cssAsc: "headerSortUp",
        cssDesc: "headerSortDown",
        cssHeader: "header"

    });


    $("#tradeshowListTypeDrop").change(function(){
        var type = $(this).val();
        $(".tradeshowRow").hide();
        if(type == "All") {
            $(".tradeshowRow").show();
        } else {
            $(".row_" + type).show();
        }
    });

    function checkExMemo() {
        day = $("#dateDrop","#withBoothForm");
        show = $("select:visible","#ShowDropDowns");
        if ((day.length && day.val() == "") || show.val() == "") {
            $("#uploadMemoSubmit").removeAttr("disabled");
            $("[name='day']").removeClass('invalidFeild');
            $("[name='tradeshow']").removeClass('invalidFeild');
            return;
        }

        data = 'show=' + show.val();
        if (day.length) {
            data += '&day=' + day.val();
            day = day.val();
        } else {
            day = '';
        }
        //Checking if a memo has already been submitted for this show on this day
        $.ajax({
            type: "POST",
            url: "/CheckIfMemoExists",
            data: data,
            dataType: "json",
            success: function(response){
                if(response.errors){
                    //Error
                    alert(response.err_msg);
                    $("#uploadMemoSubmit").removeAttr("disabled");
                    $("[name='day']").removeClass('invalidFeild');
                    $("[name='tradeshow']").removeClass('invalidFeild');
                }else{
                    //Success
                    if(response.Uploader != ""){
                        ErrorBox("day");
                        ErrorBox("tradeshow");
                        alert("The " + day + " Tradeshow Memo for " + show.val() + " has already been uploaded by " + response.Uploader);
                        $("#uploadMemoSubmit").attr("disabled","disabled");
                    } else {
                        $("#uploadMemoSubmit").removeAttr("disabled");
                        $("[name='tradeshow']").removeClass('invalidFeild');
                        $("[name='day']").removeClass('invalidFeild');
                    }
                }
            }
        });
    }

    $("select[name='tradeshow']").change(function(){
        loc = $("option:selected", this).attr("loc");
        if(loc == "SAU" || loc == "CHN" || loc == "HKG") {
            $(".sasoField").show();
        } else {
            $("input.sasoField").val(0);
            $(".resultNum").trigger("change");
            $(".sasoField").hide();
        }
        checkExMemo();
    });
    $("select[name='day']").change(checkExMemo);


    $(".leadsourceBtn").click(function(){
        var source = $(this).attr("value");
        if(source=="tradeshow"){
            $('#tradeshowListDropBySource').show();
            $('#leadsource_specify').hide();
        } else {
            $('#tradeshowListDropBySource').hide();
            $('#leadsource_specify').show();
        }
    });

    $('#competitorsDIV').hide();
    $(".is_competitors").click(function(){
        var is_competitors = $(this).attr("value");
        if(is_competitors=="yes"){
            $('#competitorsDIV').show();
        } else {
            $('#competitorsDIV').hide();
        }
    });

    $('#leadQualDIV').hide();
    $("#leadQuality").change(function(){
        var quality = $(this).val();
        quality = parseInt(quality);
        if(quality < 6){
            $('#leadQualDIV').show();
        } else {
            $('#leadQualDIV').hide();
        }
    });


    $(".boothBtn").click(function(){
        var booth = $(this).val();
        if(booth=="yes"){
            $('#withBoothForm').show();
            $('#noBoothForm').hide();
            $('#memoTable').show();
            $('#memoTableNoBooth').hide();
            $('.tradeshowdrop_divbox').show();
        } else {
            $('#withBoothForm').hide();
            $('#noBoothForm').show();
            $('#memoTable').hide();
            $('#memoTableNoBooth').show();
            $('.tradeshowdrop_divbox').hide();
        }
    });

    $(".dropdown-sales-leads").hover(
        function() {
            $('.dropdown-menu', this).stop( true, true ).fadeIn(10);
            $(this).toggleClass('open');         
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).fadeOut(10);
            $(this).toggleClass('open');          
        }
    );


    $(".hoverQuestion").mouseover(function(){
        $(".hoverDiv").remove();
        hovCargo = "#txt" + $(this).attr("cargo");
        if($(hovCargo).hasClass('notshow')){    

            $(hovCargo).removeClass('notshow');
            if($(window).width()>900){
                hovPosLeft = (parseInt($(this).position().left,10) + parseInt($(this).css("width"),10) + 20) +"px";
                hovPosTop = (parseInt($(this).position().top,10) + parseInt($(this).css("height"),10) + 10) + "px";
            } else {
                hovPosLeft = (parseInt($(this).position().left,10) + parseInt($(this).css("width"),10) - 100) +"px";
                hovPosTop = (parseInt($(this).position().top,10) + parseInt($(this).css("height"),10) + 10) + "px";
            }
            $("<div id='HoverText' class='hoverDiv'></div>").css({
                'left': hovPosLeft,
                'top': hovPosTop,
            }).html($(hovCargo).html()).insertAfter(this);
        } else {
            $("#HoverText").remove();
            $(hovCargo).addClass('notshow');
        }
    });




    $('#salesDropSelect_count').change(function(){ searchCountsbyName(); });
    $('#tradeshowDropSelect_count').change(function(){ searchCountsbyTradeshow(); });
    $('#tradeshowDropSelect_MemoSelect').change(function(){ searchMemo(); });
    $("#statusDropSelect").change(function(){ searchSalesLeads(); });
    $('#tradeshowDropSelect_overview').change(function(){ searchSalesLeadsByTradeshow(); });
    $("#salesNameDropSelect").change(function(){ searchSalesLeadsByName(); });


    // on pop up reason for why leads list rejected
    $(document).delegate("#commentsSubmit", "click", function() {

        //Show the loading Image
        $("#commentsSubmitLoadingIcon").show();
        $("#salesLeadsUploadSubmit").attr("disabled","disabled");
        $(this).attr("disabled","disabled");
        id = document.getElementById("id").value;
        comments = document.getElementById("commentsSales").value;
        $.ajax({
            type: "POST",
            url: "/sales_uploads/reject_sales_leads_submission",
            async: "false",
            data: "id="+id+"&commentsSales="+comments,
            dataType: "text",
            success: function(msg){
                $("#commentsSubmitLoadingIcon").hide();
                $('#commentsSales').hide();
                $('#commentsSubmit').hide();
                $('#successEmail0').show();
                $("#answers_"+id).text("No");
                $("#status_"+id).text("approved");
                $("#status_"+id).text("rejected");
                $("#file_"+id).hide();
                $("#status_"+id).attr('title',JSON.stringify(comments));
                //add_tooltip_to_selector('#status_'+id);
            }
        });
        return false;
    });


    $('#date').focus(function(){ $('#date').removeClass('invalidFeild'); });


    resultIndex = 0;
    $(".addButton").click(function(){
        resultIndex++;
        var $template = $('#resultTemplate'),
        $clone = $template
            .clone()
            .removeClass('hide')
            .removeAttr('id')
            .attr('data-result-index', resultIndex)
            .insertBefore($template);
    
        // Update the name attributes
        $clone
            .find('[name="name"]').attr('required', 'required')
            .attr('name', 'result_' + resultIndex + '_name').addClass("resultsAllNames").end()
            .find('[name="num"]').attr('name', 'result_' + resultIndex + '_num').end()
            .find('[name="h1"]').attr('name', 'result_' + resultIndex + '_h1').end()
            .find('[name="h2"]').attr('name', 'result_' + resultIndex + '_h2').end()
            .find('[name="h3"]').attr('name', 'result_' + resultIndex + '_h3').end()
            .find('[name="h4"]').attr('name', 'result_' + resultIndex + '_h4').end()
            .find('[name="w1"]').attr('name', 'result_' + resultIndex + '_w1').end()
            .find('[name="w2"]').attr('name', 'result_' + resultIndex + '_w2').end()
            .find('[name="w3"]').attr('name', 'result_' + resultIndex + '_w3').end()
            .find('[name="w4"]').attr('name', 'result_' + resultIndex + '_w4').end()
            .find('[name="c1"]').attr('name', 'result_' + resultIndex + '_c1').end()
            .find('[name="c2"]').attr('name', 'result_' + resultIndex + '_c2').end()
            .find('[name="c3"]').attr('name', 'result_' + resultIndex + '_c3').end()
            .find('[name="c4"]').attr('name', 'result_' + resultIndex + '_c4').end();
    });

    emailIndex = 0;
    $(".addEmailButton").click(function(){
        var $wrap  = $(this).parents('.wrap');
        var $manual = $wrap.find('#manual');
        emailIndex++;
        var checked = 0;
        if($manual.is(":checked")) checked = 1;
        
        if(checked==1){
            var $template = $wrap.find('#emailTemplate');
            $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .attr('data-email-index', emailIndex)
                .insertBefore($template);
    
            // Update the name attributes
            $clone.find('[name="email"]').attr('name', 'email_' + emailIndex).end();
        } else {
            var $template = $wrap.find('#emailDropTemplate'),
            $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .attr('data-email-index', emailIndex)
                .insertBefore($template);
    
            // Update the name attributes
            $clone.find('[name="emailDrop"]').attr('name', 'emailDrop_' + emailIndex).end();
        }
    });

    tradeshowIndex = 0;
    $(".addTradeshowButton").click(function(){
        tradeshowIndex++;
        var $template = $('#tradeshowTemplate'),
        $clone = $template
            .clone()
            .removeClass('hide')
            .removeAttr('id')
            .attr('data-tradeshow-index', tradeshowIndex)
            .insertBefore($template);
    
        // Update the name attributes
        $clone.find(".DatePicker").datepicker();
    });

    //Showing wait icon when clicking build email campaign
    $(".TradeshowEmail_BuildCampaign").click(function(){
        $(this).parent().find(".TradeshowEmail_Wait").show();
        $(this).hide();
    });

    //Leads Upload with Textarea for CSV contents
    $("#leadsform_Submit").on('click',function(){
        outputHtml = "<table border='1' id='LeadsUploadParsed' cellpadding='3' width='100%' class='tablesorter'>"; //var to hold the HTML to be output
        var csvArray = $("#parseCSVtextarea").val().split("\n"); //Build an array breaking at newlines
        $("#parseCSVtextarea").hide(); //Hide the input textarea
        i = 0;
        $.each(csvArray, function(k){ //For each Row
            strRow = csvArray[k];
            row = strRow.split(",");
            if(i == 0) outputHtml += "<thead>";
            if(i == 1) outputHtml += "<tbody>";
            if(i != 0) outputHtml += "<tr>";
            $.each(row, function(p){ //For each element
                if(i == 0){ outputHtml += "<th style='text-align:center; background-color:#dce9f9;'>"+row[p]+"</th>"; }else{ outputHtml += "<td>"+row[p]+"</td>"; }
            });
            if(i != 0) outputHtml += "</tr>";
            if(i == 0) outputHtml += "</thead>";
            i++;
        });
        outputHtml += "</tbody></table>";
        $("#leadsform_Submit").before(outputHtml);
        //Activate Table Sorter on this new table
        $("#LeadsUploadParsed").tablesorter({ cssAsc: "headerSortUp", cssDesc: "headerSortDown", cssHeader: "header" });
    });

    return false;
}; //End of Main Function
 



$(document).ready(main);

$(document).on('change', '.resultNum', function() {
    var sum = 0;
    $row  = $(this).parents('.form-group');
    //iterate through each textboxes and add the values
    $row.find('.resultNum').each(function () {
        string = this.value;
        if(string < 0 || string.indexOf("+") >= 0 || string.indexOf("-") >= 0 || string.indexOf(".") >= 0 || string.indexOf("e") >= 0 || string.indexOf("E") >= 0 ) this.value = 0;
        //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value);
        }
    });
    //.toFixed() method will roundoff the final sum to 2 decimal places
    $row.find('.resultSum').val(sum);
});

$(document).on('click', '.deleteTradeshowBtn', function() {
    tradeshow = $(this).attr("tradeshow");
    var c = window.confirm("Are you sure you want to delete this tradeshow from the list?");
    if (c == true) {
        $row  = $(this).parents('.tradeshowRow');
        $.ajax({
            type: "POST",
            url: "deleteTradeshowFromList",
            async: "false",
            data: "tradeshow="+tradeshow,
            dataType: "text",
            success: function(msg){
                $row.remove();
            }
        });
    }
    return false;
});

$(document).on('click', '.removeButton', function() {
    var $row  = $(this).parents('.form-group');
    $row.remove();
});

$(document).on('click', '.deleteEmailButton', function() {
    var $row  = $(this).parents('.email-group');
    $row.remove();
});

$(document).on('click', '.deleteEmailDropButton', function() {
    var $row  = $(this).parents('.email-dropdown-group');
    $row.remove();
});

$(document).on('click', '.deleteTradeshowButton', function() {
    var $row  = $(this).parents('.tradeshow-group');
    $row.remove();
});

$(document).on('click', '.addTradeshowBtn', function() {
    var c = window.confirm("Are you sure you want to add this tradeshow/event to the list?");
    if (c == true) {
        var $row  = $(this).parents('.tradeshow-group');
        var tradeshow = $row.find("[name=tradeshowName]").val();
        var tradeshowFriendly = $row.find("[name=tradeshowFriendly]").val();
        var tradeshowIndustry = $row.find("[name=tradeshowIndustry]").val();
        var tradeshowType = $row.find("[name=tradeshowType]").val();
        var tradeshowStart = $row.find("[name=tradeshowStart]").val();
        var tradeshowEnd = $row.find("[name=tradeshowEnd]").val();
        var tradeshowBudget = $row.find("[name=tradeshowBudget]").val();
        var event_type = $row.find("[name=event_type]").val();
        var tradeshowOrganizer = $row.find("[name=tradeshowOrganizer]").val();
        var tradeshowLocation = $row.find("[name=tradeshowLocation]").val();
        $(".tradeshow-group").html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
        $.ajax({
            type: "POST",
            url: "addTradeshowToList",
            async: "false",
            data: "show="+tradeshow+"&name="+tradeshowFriendly+"&ind="+tradeshowIndustry+"&type="+tradeshowType+"&start="+tradeshowStart+"&end="+tradeshowEnd+"&bud="+tradeshowBudget+"&event_type="+event_type+"&org="+tradeshowOrganizer+"&loc="+tradeshowLocation,
            dataType: "text",
            success: function(msg) {
                if(msg == "success") {
                    location.reload();
                } else {
                    console.log(msg);
                    alert(msg);
                }
            }
        });
    }
});


// submit csv to zoho and add to leadsCount table
// when salesUpload entry is approved
$(document).on('click', '.approveLeadsBtn', function() {
    $("#salesLeadsUploadSubmit").attr("disabled","disabled");
    status = $("#statusDropSelect").val();
    id = $(this).attr('value');
    //Show the loading Image
    $("#answers_" + id).html("<img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;vertical-align:middle;'>");
    $.ajax({
        type: "POST",
        url: "/sales_uploads/approve_upload",
        async: "false",
        data: "id="+id,
        dataType: "text",
        success: function(msg){
            if(status=="All"){
                $("#answers_"+id).text("Yes");
                $("#status_"+id).text("approved");
            }
            else{
                $("#rowID_"+id).hide();
            }
        }
    });
});

$(".rejectLeadsBtn").on('click', function() {
    id = $(this).attr('value');
    $("#TB_window").remove();
    $("body").append("<div id='TB_window'></div>");
    tb_show("Your comments for the rejected file","commentsPopup?height=370&width=450&id="+id);
    return false;
});


// Edit Tradeshows Data [Begin]
$("td","#tradeshowListTable").mouseover(function(){

    editable = false;
    if( $(this).hasClass("idFriendlyName") || $(this).hasClass("idIndustry") || $(this).hasClass("idStartDate") || $(this).hasClass("idEndDate") || $(this).hasClass("idType") || $(this).hasClass("idBudget") || $(this).hasClass("idEventType") || $(this).hasClass("idOrganizer") ) editable = true;
    if(editable) $(this).css("cursor","pointer");

}).dblclick(function(){

    editable = false;
    if( $(this).hasClass("idFriendlyName") || $(this).hasClass("idIndustry") || $(this).hasClass("idStartDate") || $(this).hasClass("idEndDate") || $(this).hasClass("idType") || $(this).hasClass("idBudget") || $(this).hasClass("idEventType") || $(this).hasClass("idOrganizer") ) editable = true;
    if(editable){
        editTradeshowCell(this);
    }

});

// Function to edit the tradeshow info and save it to the DB
function editTradeshowCell(obj){
    rowId = $(obj).parent("tr").attr("rowid");

    // Edit Friendly Name
    if( $(obj).hasClass("idFriendlyName") ) {
        origContent = $(obj).text();
        box = $('<input type="text" width="100%" value="'+origContent+'" />');
        $(obj).html(box);
        $(box).focus().on("blur", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=friendlyName&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html(valueNew);
                    } else {
                        $(obj).html(origContent);    
                    }
                },
                error: function(data){
                    $(obj).html(origContent);
                }
            });
        });
    }

    // Edit Industry
    if( $(obj).hasClass("idIndustry") ) {
        origContent = $(obj).text();
        box = $('<select width="100%" ></select>');
        $(box).append($('<option>', { value: 'All Covered', text: 'All Covered' }));
        $(box).append($('<option>', { value: 'Bodycare, Fashion & Accessories', text: 'Bodycare, Fashion & Accessories' }));
        $(box).append($('<option>', { value: 'Electrical & Electronic Products', text: 'Electrical & Electronic Products' }));
        $(box).append($('<option>', { value: 'Ethical Compliance / CSR', text: 'Ethical Compliance / CSR' }));
        $(box).append($('<option>', { value: 'Food & Food Containers', text: 'Food & Food Containers' }));
        $(box).append($('<option>', { value: 'Gifts & Premiums', text: 'Gifts & Premiums' }));
        $(box).append($('<option>', { value: 'Homeware & Gardenware', text: 'Homeware & Gardenware' }));
        $(box).append($('<option>', { value: 'Industrial, Construction & Mechanical Items', text: 'Industrial, Construction & Mechanical Items' }));
        $(box).append($('<option>', { value: 'Others', text: 'Others' }));
        $(box).append($('<option>', { value: 'Retailer', text: 'Retailer' }));
        $(box).append($('<option>', { value: 'Special Electrical & Electronic Products', text: 'Special Electrical & Electronic Products' }));
        $(box).append($('<option>', { value: 'Textile, Apparel, Footwear & Accessories', text: 'Textile, Apparel, Footwear & Accessories' }));
        $(box).append($('<option>', { value: 'Toys & Recreational Items', text: 'Toys & Recreational Items' }));
        $("option[value='"+origContent+"']", box).attr("selected",true);

        $(obj).html(box);
        $(box).focus().on("blur change", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=Industry&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html(valueNew);
                    } else {
                        $(obj).html(origContent);    
                    }
                },
                error: function(data){
                    $(obj).html(origContent);
                }
            });
        });
    }

    // Edit Start Date
    if( $(obj).hasClass("idStartDate") ) {
        origContent = $(obj).text();
        box = $('<input type="text" class="DatePicker" width="100%" style="text-align:center;" value="'+origContent+'" readonly /><span class="add-on"><i class="icon-calendar" id="date-icon"></i></span>');
        $(obj).html(box);
        $("input", obj).datepicker();
        $(box).focus().on("blur change", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=Start_Date&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html(valueNew);
                    } else {
                        $(obj).html(origContent);    
                    }
                },
                error: function(data){
                    $(obj).html(origContent);
                }
            });
        });
    }

    // Edit End Date
    if( $(obj).hasClass("idEndDate") ) {
        origContent = $(obj).text();
        box = $('<input type="text" class="DatePicker" width="100%" style="text-align:center;" value="'+origContent+'" readonly /><span class="add-on"><i class="icon-calendar" id="date-icon"></i></span>');
        $(obj).html(box);
        $("input", obj).datepicker();
        $(box).focus().on("blur change", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=End_Date&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html(valueNew);
                    } else {
                        $(obj).html(origContent);    
                    }
                },
                error: function(data){
                    $(obj).html(origContent);
                }
            });
        });
    }

    // Edit Type
    if( $(obj).hasClass("idType") ) {
        origContent = $(obj).text();
        box = $('<select width="100%" ></select>');
        $(box).append($('<option>', { value: 'All', text: 'All' }));
        $(box).append($('<option>', { value: 'AI', text: 'AI' }));
        $(box).append($('<option>', { value: 'AFI', text: 'AFI' }));
        $(box).append($('<option>', { value: 'CHB', text: 'CHB' }));
        $("option[value='"+origContent+"']", box).attr("selected",true);

        $(obj).html(box);
        $(box).focus().on("blur change", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=type&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html(valueNew);
                    } else {
                        $(obj).html(origContent);    
                    }
                },
                error: function(data){
                    $(obj).html(origContent);
                }
            });
        });
    }

    // Edit Budget
    if( $(obj).hasClass("idBudget") ) {
        origContent = $(obj).text();
        origContent = Number(origContent.replace(/[^0-9\.]+/g,""));
        box = $('<input type="number" width="100%" min="0" value="'+origContent+'" />');
        $(obj).html(box);
        $(box).focus().on("blur", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=budget&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html("$"+valueNew+" USD");
                    } else {
                        $(obj).html("$"+origContent+" USD");    
                    }
                },
                error: function(data){
                    $(obj).html("$"+origContent+" USD");
                }
            });
        });
    }

    // Edit Booth
    if( $(obj).hasClass("idEventType") ) {
        origContent = $(obj).text();
        box = $('<select width="100%" ></select>');
        $(box).append($('<option>', { value: 'tradeshow-exhibiting', text: 'Exhibiting Tradeshow or Event (with booth)' }));
        $(box).append($('<option>', { value: 'tradeshow-visiting', text: 'Visiting Tradeshow (no booth)' }));
        $(box).append($('<option>', { value: 'event-visiting', text: 'Sponsoring or visiting event (no booth)' }));
        $(box).append($('<option>', { value: 'event-speaking', text: 'Speaking Event' }));
        $(box).append($('<option>', { value: 'event-hosted', text: 'Self-hosted Event' }));
        $(box).append($('<option>', { value: 'webinar', text: 'Webinar' }));
        $("option[value='"+origContent+"']", box).attr("selected",true);
        $(obj).html(box);
        $(box).focus().on("blur change", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=event_type&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html( valueNew  );
                    } else {
                        $(obj).html(origContent);    
                    }
                },
                error: function(data){
                    $(obj).html(origContent);
                }
            });
        });
    }

    // Edit Organizer
    if( $(obj).hasClass("idOrganizer") ) {
        origContent = $(obj).text();
        box = $('<input type="text" width="100%" value="'+origContent+'" />');
        $(obj).html(box);
        $(box).focus().on("blur", function(){
            valueNew = $(this).val();
            $(obj).html("<center><img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;'></center>");
            $.ajax({
                type: "POST",
                url: "/leads/updateShowData",
                async: "false",
                data: "id="+rowId+"&field=organizer&val="+encodeURIComponent(valueNew),
                dataType: "text",
                success: function(data){
                    if(data == "true") {
                        $(obj).html(valueNew);
                    } else {
                        $(obj).html(origContent);    
                    }
                },
                error: function(data){
                    $(obj).html(origContent);
                }
            });
        });
    }

}

// Check when inputs are updated so you can update the tradeshow name
$(document).on('blur', '.tradeshowReqForNameField', function() {
    showDate = $("[name=tradeshowStart]").val();
    yearStart = showDate.substring(0, 4);
    monthStart = showDate.substring(5, 7);
    showName = $("[name=tradeshowFriendly]").val();
    $("[name=tradeshowName]").val(yearStart+" - "+monthStart+" - "+showName);
});

// Edit Tradeshows Data [End]


 function calculateSum() {
            var sum = 0;
            $row  = $(this).parents('.form-group');
            //iterate through each textboxes and add the values
            $row.find('.resultNum').each(function () {
                //add only if the value is number
                if (!isNaN(this.value) && this.value.length != 0) {
                    sum += parseFloat(this.value);
                }
            });
            
        console.log(sum);

            //.toFixed() method will roundoff the final sum to 2 decimal places
            $row.find('.resultSum').val(sum);
        }

function validateSalesLeadsForm(){
    //Disable the button
    $("#salesLeadsUploadSubmit").attr("disabled","disabled");
    FormErrors = 0;
    
    account = $("[name='account']").val();
    salesName = $("select:visible","#salesNamesDropdowns").val();
    csv = $("[name='leadsCSV']").val();
    leadSourceType = $("[name='source']:checked").val();
    //Setting the leadsource depending on what was chosen
    if(leadSourceType == "tradeshow") leadSource = $("select:visible","#tradeshowDropdowns").val();
    if(leadSourceType == "other") leadSource = $("[name='source_specify']").val();
        
    if(leadSource == "default"){
        FormErrors = FormErrors + 1;
        boxName = $("select:visible","#tradeshowDropdowns").attr("name");
        ErrorBox(boxName);
    }

    if(salesName == "default"){
        FormErrors = FormErrors + 1;
        boxName = $("select:visible","#salesNamesDropdowns").attr("name");
        ErrorBox(boxName);
    }
    
    if(csv == ""){
        FormErrors = FormErrors + 1;
        ErrorBox("leadsCSV");
    }    

    if(FormErrors != 0) $("#salesLeadsUploadSubmit").removeAttr("disabled");
    return (FormErrors != 0 ? false : true)
}

//Show a red glow around fields with an error (using 'name' attribute to target)
function ErrorBox(fieldName){
    box = $("[name='"+fieldName+"']");
    box.addClass('invalidFeild');
    box.focus(function(){
        $("[name='"+fieldName+"']").removeClass('invalidFeild');
    });
}

//Showing Loading Icons when submitting tradeshow memos
$("#noBoothForm").on("submit",function(){
    $("#uploadMemoSubmit").parent().html("<img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;vertical-align:middle;'>");
});

function searchCountsbyTradeshow(){
    tradeshow = $("#tradeshowDropSelect_count").val();
    if( tradeshow == "all" ){
        $(".leadsCountRow").hide();
        $(".leadsCountRow").each(function(){
            $(this).show();
        });
    }else{
        $(".leadsCountRow").each(function(){
            if( $(".leadsCountRow_tradeshow",this).text() == tradeshow ){
                $(this).show();
            }else{
                $(this).hide();
            }
        });        
    }
    return false;
}

function searchCountsbyName(){
    name = $("#salesDropSelect_count").val();
    if( name == "all" ){
        $(".leadsCountRow").hide();
        $(".leadsCountRow").each(function(){
            $(this).show();
        });
    }else{
        $(".leadsCountRow").each(function(){
            if( $(".leadsCountRow_name",this).text() == name ){
                $(this).show();
            } else {
                $(this).hide();
            }
        });        
    }
    return false;
}

$("#tradeshowDropSelect_MemoType").change(function(){
    $(".memoRow").hide();
    memoType = $(this).val();
    if(memoType == "all") {
        $(".memoRow").show();
    } else {
        $(".memoClass-"+memoType).show();
    }
});

$("#tradeshowMemoDropSelectType").change(function() {
    $("#tradeshowMemoLoadingTypeGif").show();
    memoType = $(this).val();
    window.location = "/tradeshow-memo/" + memoType;
});

function searchMemo() {
    $(".memoRow").hide();
    tradeshowName = $("#tradeshowDropSelect_MemoSelect").val();
    if(tradeshowName == "all") {
        $(".memoRow").show();
    } else {
        $(".memoRow").each(function(){
            rowShowName = $("td", this).eq(0).text();
            if(tradeshowName == rowShowName) $(this).show();
        });
    }
    return false;
}

function searchSalesLeads() {
    $(".salesLeadsRow").remove();
    status = $("#statusDropSelect").val();
    link = "http://staging.asiainspection.com:99/salesLeads/";
    $.ajax({
        type: "POST",
        url: "getLeadsByStatus",
        async: "false",
        data: "status="+status,
        dataType: "text",
        success: function(data) {
            data = JSON.parse(data);
            $(".salesLeadsRow").remove();
            for (index = 0; index < data.length; ++index) {
                row = data[index];
                if(row['status']=="waiting") {
                    $("#salesLeadsTable").append(" <tr class='salesLeadsRow' id='rowID_"+row['ID']+"'><td>" + row['name'] + "</td><td>" + row['account'] + "</td><td>" + row['tradeshow'] + "</td> <td><div style='display:inline-block;' id='status_"+ row['ID'] +"'>"+ row['status'] +"</div></td> <td><a href='http://staging.asiainspection.com:99/salesLeads/"+ row['fileName'] +"'>Link</a></td><td>"+ row['leadsUploaded'] +"</td> <td>"+ row['uploadDateTime'] +"</td><td style='text-align:center;'><span class='answers' id='answers_"+row['ID']+"'><button class='approveLeadsBtn btn-primary'  name='q0' id='approve_"+row['ID']+"' value='"+row['ID']+"'>Yes</button> <button  class='rejectLeadsBtn btn-negative'  name='q0' id='reject_"+row['ID']+"' value='"+row['ID']+"'>No</button></span></td> </tr>");
                } else if(row['status']=="approved") {
                    $("#salesLeadsTable").append(" <tr class='salesLeadsRow'><td>" + row['name'] + "</td><td>" + row['account'] + "</td><td>" + row['tradeshow'] + "</td> <td><div style='display:inline-block;' id='status_"+ row['ID'] +"'>"+ row['status'] +"</div></td> <td><a href='http://staging.asiainspection.com:99/salesLeads/"+ row['fileName'] +"'>Link</a></td><td>"+ row['leadsUploaded'] +"</td> <td>"+ row['uploadDateTime'] +"</td><td>Yes</td></tr>");
                } else {
                    $("#salesLeadsTable").append(" <tr class='salesLeadsRow'><td>" + row['name'] + "</td><td>" + row['account'] + "</td><td>" + row['tradeshow'] + "</td> <td><div style='display:inline-block;' id='status_"+ row['ID'] +"'>"+ row['status'] +"</div></td> <td></td><td>"+ row['leadsUploaded'] +"</td> <td>"+ row['uploadDateTime'] +"</td><td>No</td></tr>");
                }
            }
        }
    });
    return false;
}

function searchSalesLeadsByName(){
    $(".salesLeadsRow").remove();
    name = $("#salesNameDropSelect").val();
    link = "http://staging.asiainspection.com:99/salesLeads/";
    if(name=="All") name="all";
    $.ajax({
        type: "POST",
        url: "getLeadsByName",
        async: "false",
        data: "name="+name,
        dataType: "text",
        success: function(data){
            data = eval("(" + data + ")");
            $(".salesLeadsRow").remove();
            for (index = 0; index < data.length; ++index) {
                row = data[index];
                var v = row['leadsUploaded'] - row['memoLeads'];
                if(row['memoLeads']==0) row['memoLeads']="No Memo Yet";
                $("#salesLeadsTable").append(" <tr class='salesLeadsRow'><td>" + row['name'] + "</td><td>" + row['account'] + "</td><td>" + row['tradeshow'] + "</td> <td><div style='display:inline-block;' id='status_"+ row['ID'] +"'>"+ row['status'] +"</div></td><td>"+ row['memoLeads'] +"</td> <td>"+ row['leadsUploaded'] +"</td><td>"+v+"</td> <td>"+ row['uploadDateTime'] +"</td></tr>");
            }
        }
    });
    return false;
}


function searchSalesLeadsByTradeshow(){
    $(".salesLeadsRow").remove();
    tradeshow = $("#tradeshowDropSelect_overview").val();
    link = "http://staging.asiainspection.com:99/salesLeads/";
    if(tradeshow=="All Shows") tradeshow="all";
    $.ajax({
        type: "POST",
        url: "getLeadsByTradeshow",
        async: "false",
        data: "tradeshow="+tradeshow,
        dataType: "text",
        success: function(data){
            data = eval("(" + data + ")");
            $(".salesLeadsRow").remove();
            for (index = 0; index < data.length; ++index) {
                row = data[index];
                var v = row['leadsUploaded']-row['memoLeads'];
                if(row['memoLeads']==0){
                    row['memoLeads']="No Memo Yet";
                }
                $("#salesLeadsTable").append(" <tr class='salesLeadsRow'><td>" + row['name'] + "</td><td>" + row['account'] + "</td><td>" + row['tradeshow'] + "</td> <td><div style='display:inline-block;' id='status_"+ row['ID'] +"'>"+ row['status'] +"</div></td><td>"+ row['memoLeads'] +"</td> <td>"+ row['leadsUploaded'] +"</td><td>"+v+"</td> <td>"+ row['uploadDateTime'] +"</td></tr>");
            }
        }
    });
    return false;
}

/******************************************************************************************************************/
//create a validation error box using the "name" attribute to identify the target (Version 2, on Job App page) <-- Evonne copied this from, make sure it is working/needed
function validateErr(fieldName){
    if( $(window).width() < 770 ){
        var box = $("[name='"+fieldName+"']");
        var fieldWrapper = $("[name='"+fieldName+"']").parents(".wrap");
        if(fieldWrapper.length == 0) fieldWrapper = $("[name='"+fieldName+"']").parents(".upload-input");
        fieldWrapper.css("position","relative");
    }else{
        $("[name='"+fieldName+"']").addClass('invalidFeild');
        box.focus(function(){
            $("[name='"+fieldName+"']").removeClass('invalidFeild');
        });
    }
}

/*************************************/
//Functionality for editing the tradeshow on the tradeshow memo detail page
$("#changeTradeshow_Edit").click(function(){
    $("#changeTradeshow_PrimaryView").hide();
    $("#changeTradeshow_SecondaryView").show();
});

$("#changeTradeshow_Cancel").click(function(){
    $("#changeTradeshow_PrimaryView").show();
    $("#changeTradeshow_SecondaryView").hide();
});

//Saving a Tradeshow
$("#changeTradeshow_Save").click(function(){
    $("#changeTradeshow_SavText").hide();
    $("#changeTradeshow_SavWait").show();

    newShow = $("#changeTradeshow_TradeshowList").val();
    newType = $("option:selected","#changeTradeshow_TradeshowList").attr("type");
    showId = $("#changeTradeshow_ShowName").attr("showid");

    $.ajax({
        type: "POST",
        url: "/ChangeDeleteMemo",
        async: "false",
        data: 'method=change&id='+showId+'&show='+ newShow + "&type=" + newType,
        dataType: "json",
        success: function(response){
            if(response.errors){
                //Error
                $("#changeTradeshow_PrimaryView").show();
                $("#changeTradeshow_SecondaryView").hide();
                $("#changeTradeshow_SavText").show();
                $("#changeTradeshow_SavWait").hide();
                alert(response.err_msg);
            }else{
                //Success
                $("#changeTradeshow_ShowName").text(response.showName);
                $("#changeTradeshow_PrimaryView").show();
                $("#changeTradeshow_SecondaryView").hide();
                $("#changeTradeshow_SavText").show();
                $("#changeTradeshow_SavWait").hide();
            }
        }
    });
});

/*************************************/
//Functionality for deleting the tradeshow on the tradeshow memo detail page
$("#deleteTradeshow_Delete").click(function(){
    $(this).attr("disabled","disabled");
    $("#deleteTradeshow_SavWait").show();
    showId = $("#changeTradeshow_ShowName").attr("showid");

    $.ajax({
        type: "POST",
        url: "/ChangeDeleteMemo",
        async: "false",
        data: 'method=delete&id='+showId,
        dataType: "json",
        success: function(response){
            if(response.errors){
                //Error
                $(this).removeAttr("disabled");
                $("#deleteTradeshow_SavWait").hide();
                alert(response.err_msg);
            }else{
                //Success
                window.location = "/view-tradeshow-memo";
            }
        }
    });
});

/*************************************/
//Validating the tradeshow memos before submission
function validateTradeshowMemoCommon(theformid) {
    FormErrors = 0;

    photo = document.getElementById("memoPhoto")

    if (photo) {
        files = photo.files;
        //Check Selected Files
        $(files).each(function() {
            //If the filesize is 0, the file isn't ready or is invalid
            if( this.size < 1 ){
                FormErrors++;
                $("#cvUpload").addClass('invalidFeild');
                $("#memoPhototxt, #memoPhoto").focus(function(){
                    $("#cvUpload").removeClass('invalidFeild');
                });
            }
        });
    }

    //Check all the results
    $(".resultNum").each(function(){
        if( $(this).val() < 0 ){
            FormErrors++;
            ErrorBox( $(this).attr("name") );
        }
    });

    return FormErrors;
}

function validateTradeshowMemoForm() {
    p = saveScreen();
    $("#uploadMemoSubmit").attr("disabled","disabled");
    FormErrors = validateTradeshowMemoCommon("#withBoothForm");
    errors = "";

    //Create variables for values
    concurrent = $("[name='concurrent']:checked","#withBoothForm").val();
    concurrent_reasons = $("[name='concurrent_reasons']","#withBoothForm").val();
    is_competitors = $("[name='is_competitors']:checked","#withBoothForm").val();
    competitors = $("[name='competitors']","#withBoothForm").val();

    if (is_competitors === "yes") {
    //Validate Competitors
        if( competitors == ""){
            FormErrors++;
            ErrorBox("competitors");
        }
    }

    if (concurrent === "yes") {
    //Validate ConcurrentReasons
        if( concurrent_reasons == ""){
            FormErrors++;
            ErrorBox("concurrent_reasons");
        }
    }

    if(FormErrors != 0) {
        //Re-enable the submit button
        $("#uploadMemoSubmit").removeAttr("disabled");
        //Scroll to top of page
        $("html, body").stop().animate({scrollTop:0}, '500', 'swing');
    }
    // Wait for screen to be saved
    p.then(function(x) {
        if(!FormErrors) {
            document.forms[0].submit();
        }
    });
}

function validateTradeshowMemoNoBoothForm() {
    p = saveScreen();
    $("#uploadMemoSubmit").attr("disabled","disabled");
    FormErrors = validateTradeshowMemoCommon("#noBoothForm");

    is_competitors = $("[name='is_competitors']:checked","#noBoothForm").val();
    competitors = $("[name='competitors']","#noBoothForm").val();

    //Validate IsCompetitors
    if( is_competitors == undefined){
        FormErrors++;
        ErrorBox("is_competitors");
    } else if (is_competitors === "yes") {
    //Validate Competitors
        if( competitors == ""){
            FormErrors++;
            ErrorBox("competitors");
        }
    }

    //Check that a memo has not already been submitted for this show/day
    //Check for empty values
    //Check if any pics were uploaded and make sure they are ready
    //confirm session is active

    if(FormErrors != 0){
        //Re-enable the submit button
        $("#uploadMemoSubmit").removeAttr("disabled");
        //Scroll to top of page
        $("html, body").stop().animate({scrollTop:0}, '500', 'swing');
    }

    // Wait for screen to be saved
    p.then(function(x) {
        if(!FormErrors) {
            document.forms[0].submit();
        }
    });
}

function validateEventVisitingMemoForm() {
    p = saveScreen();
    $("#uploadMemoSubmit").attr("disabled","disabled");
    FormErrors = validateTradeshowMemoCommon("#uploadMemoEventVisitForm");

    is_competitors = $("[name='is_competitors']:checked","#uploadMemoEventVisitForm").val();
    competitors = $("[name='competitors']","#uploadMemoEventVisitForm").val();

    //Validate IsCompetitors
    if( is_competitors == undefined){
        FormErrors++;
        ErrorBox("is_competitors");
    } else if (is_competitors === "yes") {
    //Validate Competitors
        if( competitors == ""){
            FormErrors++;
            ErrorBox("competitors");
        }
    }

    //Check that a memo has not already been submitted for this show/day
    //Check for empty values
    //Check if any pics were uploaded and make sure they are ready
    //confirm session is active

    if(FormErrors != 0){
        //Re-enable the submit button
        $("#uploadMemoSubmit").removeAttr("disabled");
        //Scroll to top of page
        $("html, body").stop().animate({scrollTop:0}, '500', 'swing');
    }

    // Wait for screen to be saved
    p.then(function(x) {
        if(!FormErrors) {
            document.forms[0].submit();
        }
    });
}

function validateEventSpeakingMemoForm() {
    p = saveScreen();
    $("#uploadMemoSubmit").attr("disabled","disabled");
    FormErrors = validateTradeshowMemoCommon("#uploadMemoEventSpeakForm");

    is_competitors = $("[name='is_competitors']:checked","#uploadMemoEventSpeakForm").val();
    competitors = $("[name='competitors']","#uploadMemoEventSpeakForm").val();

    //Validate IsCompetitors
    if( is_competitors == undefined){
        FormErrors++;
        ErrorBox("is_competitors");
    } else if (is_competitors === "yes") {
    //Validate Competitors
        if( competitors == ""){
            FormErrors++;
            ErrorBox("competitors");
        }
    }

    //Check that a memo has not already been submitted for this show/day
    //Check for empty values
    //Check if any pics were uploaded and make sure they are ready
    //confirm session is active

    if(FormErrors != 0){
        //Re-enable the submit button
        $("#uploadMemoSubmit").removeAttr("disabled");
        //Scroll to top of page
        $("html, body").stop().animate({scrollTop:0}, '500', 'swing');
    }

    // Wait for screen to be saved
    p.then(function(x) {
        if(!FormErrors) {
            document.forms[0].submit();
        }
    });
}

function validateEventHostedMemoForm() {
    p = saveScreen();
    $("#uploadMemoSubmit").attr("disabled","disabled");
    FormErrors = validateTradeshowMemoCommon("#uploadMemoEventHostForm");

    //Check that a memo has not already been submitted for this show/day
    //Check for empty values
    //Check if any pics were uploaded and make sure they are ready
    //confirm session is active

    if(FormErrors != 0){
        //Re-enable the submit button
        $("#uploadMemoSubmit").removeAttr("disabled");
        //Scroll to top of page
        $("html, body").stop().animate({scrollTop:0}, '500', 'swing');
    }

    // Wait for screen to be saved
    p.then(function(x) {
        if(!FormErrors) {
            document.forms[0].submit();
        }
    });
}

function processMassUpdate() {
    $("#massUploadSubmitBox").html("<center>Processing <img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;vertical-align:middle;'><br /><i>Do not close this window until task is completed</i></center>");

    var package = { };
    package.file = $("#fileLocation").val();
    package.type = $("#fileUploadType").val();
    package.module = $("#fileUploadModule").val();
    package.rows = $("#fileRows").val();
    i=0;
    package.fields = { };
    $("select","#massUploadFieldAlignBoxes").each(function(){
        package.fields[i] = $(this).val();
        i++;
    });
    package = JSON.stringify(package);

    $.ajax({
        type: "POST",
        url: "/leads-tool/processmassupdate",
        async: "false",
        data: "data="+package,
        dataType: "json",
        success: function(msg){
            console.log(msg);
            $("#massUploadSubmitBox").html("<br /><div style='padding:15px;text-align:left;background-color:#d7d7c1;overflow:scroll; max-width:1000px;'><h2>Results</h2><xmp>"+msg.responseText+"</xmp></div>");
        },
        error: function(msg){
            console.log(msg);
            $("#massUploadSubmitBox").html("<br /><div style='padding:15px;text-align:left;background-color:#d7d7c1;overflow:scroll; max-width:1000px;'><h2>Results</h2><xmp>"+msg.responseText+"</xmp></div>");
        }
    });
}

// Approve Affiliate
$(document).on('click', '.approveAffiliateBtn', function() {
    id = $(this).attr('value');
    //Show the loading Image
    $("#approvebox_" + id).html("<img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;vertical-align:middle;'>");
    $.ajax({
        type: "POST",
        url: "/leads-tool/change-affiliate-status",
        async: "false",
        data: "id="+id+"&action=approve",
        dataType: "text",
        success: function(msg){
            $("#approvebox_" + id).text("Approved");
            $("#approvebox_" + id).parent().css("background-color","#bfffd9");
        },
        error: function(msg){
            console.log(msg);
        }
    });
});

// Reject Affiliate
$(document).on('click', '.rejectAffiliateBtn', function() {
    id = $(this).attr('value');
    //Show the loading Image
    $("#approvebox_" + id).html("<img src='https://s3.asiainspection.com/images/loading.gif' style='height:20px;vertical-align:middle;'>");
    $.ajax({
        type: "POST",
        url: "/leads-tool/change-affiliate-status",
        async: "false",
        data: "id="+id+"&action=reject",
        dataType: "text",
        success: function(msg){
            $("#approvebox_" + id).text("Rejected");
            $("#approvebox_" + id).parent().css("background-color","#f5bcbf");
        },
        error: function(msg){
            console.log(msg);
        }
    });
});

function saveScreen() {
    salesName = $("[name=SalesID]").val();
    salesName = salesName.replace(/ /g,"_");
    elem = document.getElementsByClassName("box-white");
    $("#tradeshowMemoLoadingTypeGif").show();
    window.scrollTo(0,0);
    var promise = new Promise(function(resolve, reject) {
        html2canvas(elem, {
            onrendered: function(canvas) {
                document.getElementById('uploadMemoSubmit').scrollIntoView();
                img = canvas.toDataURL("image/jpeg");
                data = "img="+img+"&name="+salesName;
                $.ajax({
                    type: "POST",
                    url: "/saveScreen",
                    data: data,
                    dataType: "json",
                    complete: function(response) {
                        $("#tradeshowMemoLoadingTypeGif").hide();
                        resolve(true);
                    }
                });
            }
        });
    });
   return promise;
}
