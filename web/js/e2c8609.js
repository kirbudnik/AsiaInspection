
    var Jobs = [];
    job_countries = new Array();

    $.ajax({
        type: "POST",
        url: "getJobs",
        async: "false",
        dataType: "text",
        success: function(data) {
            data = eval("(" + data + ")"); //Parse JSON data

            //Remove the non-AI jobs
            $.each(data.root, function(index, value) {
                jobCat = String(data.root[index].Category).toLowerCase();
                if (jobCat != "ai") {
                    data.totalAmount = data.totalAmount - 1;
                    delete data.root[index];
                }
            });
            //Reindex the Jobs
            i = 0;
            $.each(data.root, function(index, value) {
                if (value != undefined) {
                    data.root[i] = data.root[index];
                    i++;
                }
            });
            $("#totaljobs").html(0);

            for (var i = 0; i < data.totalAmount; i++) {
                flag = "";
                jobloc = String(data.root[i].Location).toLowerCase();
                if (jobloc.indexOf('china') > -1 || jobloc.indexOf('shenzhen') > -1 || jobloc.indexOf('shenzhen') > -1 || jobloc.indexOf('fuzhou') > -1 || jobloc.indexOf('taian') > -1 || jobloc.indexOf('yiwu') > -1 || jobloc.indexOf('wenzhou') > -1 || jobloc.indexOf('yongkang') > -1 || jobloc.indexOf('guangzhou') > -1 || jobloc.indexOf('jinghua') > -1 || jobloc.indexOf('hangzhou') > -1 || jobloc.indexOf('hefei') > -1 || jobloc.indexOf('quanzhou') > -1 || jobloc.indexOf('jiaxing') > -1 || jobloc.indexOf('nanjing') > -1 || jobloc.indexOf('suzhou') > -1 || jobloc.indexOf('ningbo') > -1 || jobloc.indexOf('beijing') > -1 || jobloc.indexOf('zibo') > -1 || jobloc.indexOf('anhui') > -1 || jobloc.indexOf('fujian') > -1 || jobloc.indexOf('jiangsu') > -1 || jobloc.indexOf('zhejiang') > -1 || jobloc.indexOf('hebei') > -1 || jobloc.indexOf('shandong') > -1 || jobloc.indexOf('guangdong') > -1 || jobloc.indexOf('chengdu') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_china.png";
                    Jobs.push({
                        country: "China",
                        flag: "flag_china.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('hong kong') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_hk.png";
                    Jobs.push({
                        country: "Hong Kong",
                        flag: "flag_hk.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('thailand') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_thailand.png";
                    Jobs.push({
                        country: "Thailand",
                        flag: "flag_thailand.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('india') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_india.png";
                    Jobs.push({
                        country: "India",
                        flag: "flag_india.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('italy') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_italy.png";
                    Jobs.push({
                        country: "Italy",
                        flag: "flag_italy.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('taiwan') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_taiwan.png";
                    Jobs.push({
                        country: "Taiwan",
                        flag: "flag_taiwan.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('korea') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_korea.png";
                    Jobs.push({
                        country: "Korea",
                        flag: "flag_korea.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('philippines') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_philippines.png";
                    Jobs.push({
                        country: "Philippines",
                        flag: "flag_philippines.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('africa') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_southAfrica.png";
                    Jobs.push({
                        country: "South Africa",
                        flag: "flag_southAfrica.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('spain') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_spain.png";
                    Jobs.push({
                        country: "Spain",
                        flag: "flag_spain.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('colombia') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_colombia.png";
                    Jobs.push({
                        country: "Colombia",
                        flag: "flag_colombia.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('morocco') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_morocco.png";
                    Jobs.push({
                        country: "Morocco",
                        flag: "flag_morocco.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('pakistan') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_pakistan.png";
                    Jobs.push({
                        country: "Pakistan",
                        flag: "flag_pakistan.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('poland') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_poland.png";
                    Jobs.push({
                        country: "Poland",
                        flag: "flag_poland.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('bangladesh') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_bangladesh.png";
                    Jobs.push({
                        country: "Bangladesh",
                        flag: "flag_bangladesh.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('brazil') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_brazil.png";
                    Jobs.push({
                        country: "Brazil",
                        flag: "flag_brazil.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('portugal') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_portugal.png";
                    Jobs.push({
                        country: "Portugal",
                        flag: "flag_portugal.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('turkey') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_turkey.png";
                    Jobs.push({
                        country: "Turkey",
                        flag: "flag_turkey.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('vietnam') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_vietnam.png";
                    Jobs.push({
                        country: "Vietnam",
                        flag: "flag_vietnam.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('germany') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_germany.png";
                    Jobs.push({
                        country: "Germany",
                        flag: "flag_germany.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('japan') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_japan.png";
                    Jobs.push({
                        country: "Japan",
                        flag: "flag_japan.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('belgium') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_belgium.png";
                    Jobs.push({
                        country: "Belgium",
                        flag: "flag_belgium.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('egypt') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_egypt.png";
                    Jobs.push({
                        country: "Egypt",
                        flag: "flag_egypt.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('indonesia') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_indonesia.png";
                    Jobs.push({
                        country: "Indonesia",
                        flag: "flag_indonesia.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('mexico') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_mexico.png";
                    Jobs.push({
                        country: "Mexico",
                        flag: "flag_mexico.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('botswana') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_botswana.png";
                    Jobs.push({
                        country: "Botswana",
                        flag: "flag_botswana.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('madagascar') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_madagascar.png";
                    Jobs.push({
                        country: "Madagascar",
                        flag: "flag_madagascar.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('kenya') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_kenya.png";
                    Jobs.push({
                        country: "Kenya",
                        flag: "flag_kenya.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('canada') > -1 || jobloc.indexOf('vancouver') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_canada.png";
                    Jobs.push({
                        country: "Canada",
                        flag: "flag_canada.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('usa') > -1 || jobloc.indexOf('new york') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_usa.png";
                    Jobs.push({
                        country: "USA",
                        flag: "flag_usa.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('saudi') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_saudi.png";
                    Jobs.push({
                        country: "Saudi Arabia",
                        flag: "flag_saudi.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('cambodia') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_cambodia.png";
                    Jobs.push({
                        country: "Cambodia",
                        flag: "flag_cambodia.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('argentina') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_argentina.png";
                    Jobs.push({
                        country: "Argentina",
                        flag: "flag_argentina.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('guatemala') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_guatemala.png";
                    Jobs.push({
                        country: "Guatemala",
                        flag: "flag_guatemala.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('salvador') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_el_salvador.png";
                    Jobs.push({
                        country: "El Salvador",
                        flag: "flag_el_salvador.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('haiti') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_haiti.png";
                    Jobs.push({
                        country: "Haiti",
                        flag: "flag_haiti.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('dominican') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_dominican_republic.png";
                    Jobs.push({
                        country: "Dominican Republic",
                        flag: "flag_dominican_republic.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('romania') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_romania.png";
                    Jobs.push({
                        country: "Romania",
                        flag: "flag_romania.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('macedonia') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_macedonia.png";
                    Jobs.push({
                        country: "Macedonia",
                        flag: "flag_macedonia.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('greece') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_greece.png";
                    Jobs.push({
                        country: "Greece",
                        flag: "flag_greece.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else if (jobloc.indexOf('bulgaria') > -1) {
                    flag = "https://s3.asiainspection.com/images/flags/flag_bulgeria.png";
                    Jobs.push({
                        country: "Bulgaria",
                        flag: "flag_bulgeria.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                } else {
                    flag = "https://s3.asiainspection.com/images/flags/flag_unknown.png";
                    Jobs.push({
                        country: "Unknown",
                        flag: "flag_unknown.png",
                        dept: data.root[i].Dept,
                        location: data.root[i].Location,
                        position: data.root[i].Postion,
                        unid: data.root[i].unid
                    });
                }
            }

            $.each(Jobs, function() {
                if ($.inArray($(this)[0].country, job_countries) === -1) job_countries.push($(this)[0].country);
            });

            job_countries.sort();
            $.each(job_countries, function(i, el) {
                $("#jobdrop_Countries").append("<option>" + el + "</option>");
            });

            var jobDuplicates = new Array();
            var jobDepts = new Array();
            $.each(Jobs, function(i, el) {
                if ($.inArray(String(el.dept), jobDuplicates) == -1) {
                    jobDepts.push(String(el.dept));
                    jobDuplicates.push(String(el.dept));
                }
            });
            jobDepts.sort();
            $.each(jobDepts, function(i, el) {
                $("#jobdrop_Department").append("<option>" + el + "</option>");
            });

            viewAllJobOps(); //Show all jobs by default
        }
    });

      function SearchJobs() {
       clearAllJobOps();
    jobcountry = $("#jobdrop_Countries").val();
    jobdept = $("#jobdrop_Department").val();
    jobtype = $("#jobdrop_Type").val();
    if(jobcountry == null) jobcountry = "";
    if(jobdept == null) jobdept = "";
    if(jobtype == null) jobtype = "";
    //Show "View All" link only if list is filtered
    if(jobcountry == "" && jobdept == "" && jobtype == ""){
        viewAllJobOps();
        return;
    }

    $.each(Jobs, function(i, el){
        var type="";
            thejob = String(el.position).toLowerCase();
                    if(thejob.indexOf('intern')>=0)
                         type="Internship";
                    else if(thejob.indexOf('part')>=0)
                                type="Part-time";
                    else
                        type="Full-time";
        if((el.country == jobcountry || jobcountry == "") && (el.dept == jobdept || jobdept == "")){
            thejob = String(el.position).toLowerCase();
            if($("#jobdrop_Type").val() == "full"){
                if(thejob.indexOf("part") == -1) $("#jobsTable").append("<tr class='jobRow'><td class='jobsPositionTD col-md-5 col-xs-9'><a href='/viewjob?id=" + el.unid + "&title=" + encodeURIComponent(el.position) + "&loc=" + encodeURIComponent(el.location) + "'>" + el.position + "</a><br><p class='pgrey jobsDeptTD'>" + el.dept + "</p></td><td class='jobsLocTD col-md-5 col-xs-3'><img src='https://s3.asiainspection.com/images/flags/"+el.flag+"' style='vertical-align:middle;' /> <span  class='locationText'>"+el.location+"</span></td><td class='contractType col-lg-2'>"+type+"</td></tr>");
            }else if($("#jobdrop_Type").val() == "part"){
                if(thejob.indexOf("part") != -1) $("#jobsTable").append("<tr class='jobRow'><td class='jobsPositionTD col-md-5 col-xs-9'><a href='/viewjob?id=" + el.unid + "&title=" + encodeURIComponent(el.position) + "&loc=" + encodeURIComponent(el.location) + "'>" + el.position + "</a><br><p class='pgrey jobsDeptTD'>" + el.dept + "</p></td><td class='jobsLocTD col-md-5 col-xs-3'><img src='https://s3.asiainspection.com/images/flags/"+el.flag+"' style='vertical-align:middle;' /> <span  class='locationText'>"+el.location+"</span></td><td class='contractType col-lg-2'>"+type+"</td></tr>");
            }else if($("#jobdrop_Type").val() == "intern"){
                if(thejob.indexOf("intern") != -1) $("#jobsTable").append("<tr class='jobRow'><td class='jobsPositionTD col-md-5 col-xs-9'><a href='/viewjob?id=" + el.unid + "&title=" + encodeURIComponent(el.position) + "&loc=" + encodeURIComponent(el.location) + "'>" + el.position + "</a><br><p class='pgrey jobsDeptTD'>" + el.dept + "</p></td><td class='jobsLocTD col-md-5 col-xs-3'><img src='https://s3.asiainspection.com/images/flags/"+el.flag+"' style='vertical-align:middle;' /> <span  class='locationText'>"+el.location+"</span></td><td class='contractType col-lg-2'>"+type+"</td></tr>");
            }else{
                $("#jobsTable").append("<tr class='jobRow'><td class='jobsPositionTD col-md-5 col-xs-9'><a href='/viewjob?id=" + el.unid + "&title=" + encodeURIComponent(el.position) + "&loc=" + encodeURIComponent(el.location) + "'>" + el.position + "</a><br><p class='pgrey jobsDeptTD'>" + el.dept + "</p></td><td class='jobsLocTD col-md-5 col-xs-3'><img src='https://s3.asiainspection.com/images/flags/"+el.flag+"' style='vertical-align:middle;' /> <span  class='locationText'>"+el.location+"</span></td><td class='contractType col-lg-2'>"+type+"</td></tr>")
            }
        }
    });

    $("#totaljobs").html(parseInt($("#jobsTable").find("tr").length)-1);
    }

    function viewAllJobOps() {
        clearAllJobOps();
        $("#limitedListNotice").hide();

        $.each(Jobs, function(i, el) {
             var type="";
            thejob = String(el.position).toLowerCase();
                    if(thejob.indexOf('intern')>=0)
                         type="Internship";
                    else if(thejob.indexOf('part')>=0)
                                type="Part-time";
                    else
                        type="Full-time";

            show = true;


            if (show) $("#jobsTable").append("<tr class='jobRow'><td class='jobsPositionTD col-md-5 col-xs-9'><a href='/viewjob?id=" + el.unid + "&title=" + encodeURIComponent(el.position) + "&loc=" + encodeURIComponent(el.location) + "'>" + el.position + "</a><br><p class='pgrey jobsDeptTD'>" + el.dept + "</p></td><td class='jobsLocTD col-md-5 col-xs-3'><img src='https://s3.asiainspection.com/images/flags/" + el.flag + "' style='vertical-align:middle;' /> <span class='locationText'>" + el.location + "</span></td><td class='contractType col-md-2'>"+type+"<td></tr>")
        });
        //$("#jobsTable").find("tr:even").css("background-color", "rgb(229,232,235)");
        $("#totaljobs").html(parseInt($("#jobsTable").find("tr").length) - 1);
        //Reset/Ignore the URL params now, they should only affect the first page load
        listCountry = undefined;
        listDept = undefined;
    }

    function clearAllJobOps() {
        $(".jobRow").remove();
    }

    function showJobDetails(id) {
        tb_show($(this).attr("title"), "objects/Popup.php?type=jobdesc&id=" + id + "&height=520&width=596", "locktop");
    }

if(!window.console){window.console={}
}if(typeof window.console.log!=="function"){window.console.log=function(){}
}if(typeof window.console.warn!=="function"){window.console.warn=function(){}
}(function(){var S={"bootstrapInit":+new Date()},p=document,m=(/^https?:\/\/.*?linkedin.*?\/in\.js.*?$/),b=(/async=true/),D=(/^https:\/\//),J=(/\/\*((?:.|[\s])*?)\*\//m),F=(/\r/g),j=(/[\s]/g),g=(/^[\s]*(.*?)[\s]*:[\s]*(.*)[\s]*$/),x=(/_([a-z])/gi),A=(/^[\s]+|[\s]+$/g),u=(/^[a-z]{2}(_)[A-Z]{2}$/),C=(/suppress(Warnings|_warnings):true/gi),d=(/^api(Key|_key)$/gi),k="\n",G=",",n="",I="@",o="extensions",Z="on",w="onDOMReady",ac="onOnce",aa="script",L="https://www.linkedin.com/uas/js/userspace?v=0.0.2000-RC8.48748-1428",h="https://platform.linkedin.com/js/secureAnonymousFramework?v=0.0.2000-RC8.48748-1428",H="http://platform.linkedin.com/js/nonSecureAnonymousFramework?v=0.0.2000-RC8.48748-1428",P="http://platform.linkedin.com/js/frameworkV2?v=0.0.2000-RC8.48748-1428",B=p.getElementsByTagName("head")[0],t=p.getElementsByTagName(aa),X=[],a=[],O=["lang"],R={},c=false,ad,l,W,r,K,E,ab;
if(window.IN&&IN.ENV&&IN.ENV.js){if(!IN.ENV.js.suppressWarnings){console.warn("duplicate in.js loaded, any parameters will be ignored")
}return
}window.IN=window.IN||{};
IN.ENV={};
IN.ENV.js={};
IN.ENV.js.extensions={};
statsQueue=IN.ENV.statsQueue=[];
statsQueue.push(S);
ad=IN.ENV.evtQueue=[];
IN.Event={on:function(){ad.push({type:Z,args:arguments})
},onDOMReady:function(){ad.push({type:w,args:arguments})
},onOnce:function(){ad.push({type:ac,args:arguments})
}};
IN.$extensions=function(ag){var aj,i,af,ai,ah=IN.ENV.js.extensions;
aj=ag.split(G);
for(var ae=0,e=aj.length;
ae<e;
ae++){i=V(aj[ae],I,2);
af=i[0].replace(A,n);
ai=i[1];
if(!ah[af]){ah[af]={src:(ai)?ai.replace(A,n):n,loaded:false}
}}};
function V(ag,ae,e){var ah=ag.split(ae);
if(!e){return ah
}if(ah.length<e){return ah
}var af=ah.splice(0,e-1);
var i=ah.join(ae);
af.push(i);
return af
}function v(e,i){if(e===o){IN.$extensions(i);
return null
}if(d.test(e)){i=i.replace(j,n)
}if(i===""){return null
}return i
}function N(af,ag){ag=v(af,ag);
if(ag){af=af.replace(x,function(){return arguments[1].toUpperCase()
});
if(af==="lang"&&!u.test(ag)){try{var ae=ag.replace("-","_").split("_");
ae=[ae[0].substr(0,2).toLowerCase(),ae[1].substr(0,2).toUpperCase()].join("_");
if(!u.test(ae)){throw new Error()
}else{ag=ae
}}catch(ah){if(!(ab||IN.ENV.js.suppressWarnings)&&ag){console.warn("'"+ag+"' is not a supported language, defaulting to 'en_US'")
}ag="en_US"
}}else{if(af==="noAuth"||af==="noApi"){ag=/^(?:true|yes|1)$/i.test(ag)
}}IN.ENV.js[af]=ag;
var ai=[encodeURIComponent(af),encodeURIComponent(ag)].join("=");
for(var i in O){if(O.hasOwnProperty(i)&&O[i]===af){a.push(ai);
return
}}X.push(ai)
}}l="";
for(U=0,q=t.length;
U<q;
U++){var f=t[U];
if(!m.test(f.src)){continue
}if(b.test(f.src)){c=true
}try{l=f.innerHTML.replace(A,n)
}catch(z){try{l=f.text.replace(A,n)
}catch(y){}}}l=l.replace(J,"$1").replace(A,n).replace(F,n);
ab=C.test(l.replace(j,n));
for(var U=0,T=l.split(k),q=T.length;
U<q;
U++){var s=T[U];
if(!s||s.replace(j,n).length<=0){continue
}try{W=s.match(g);
r=W[1].replace(A,n);
K=W[2].replace(A,n)
}catch(Y){if(!ab){console.warn("script tag contents must be key/value pairs separated by a colon. Source: "+Y)
}continue
}N(r,K)
}N("secure",1);
function M(e,i){return e+((/\?/.test(e))?"&":"?")+i.join("&")
}IN.init=function Q(i){var e=IN.ENV.js,ae,af;
i=i||{};
for(ae in i){if(i.hasOwnProperty(ae)){N(ae,i[ae])
}}E=p.createElement(aa);
if(e.v2){af=M(P,X)
}else{if(e.apiKey&&!e.noAuth){af=M(L,X)
}else{af=e.secure?h:H
}}E.src=M(af,a);
B.appendChild(E);
statsQueue.push({"userspaceRequested":+new Date()});
IN.init=function(){}
};
statsQueue.push({"bootstrapLoaded":+new Date()});
if(!c){IN.init()
}})();