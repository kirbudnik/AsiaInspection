//When the options for which video to watch are clicked
$(".demoInspectionSwitch").click(function(){
  vidtype = $(this).attr("vid");
  $(".demoInspectionSwitch").css("color","#b0b0b0");
  $(this).css("color","black");
  if(vidtype == "inspection"){
    if($("#JWvideo").length > 0){
      jwplayer("JWvideo").setup({
        file: "https://s3.asiainspection.com/files/videos/AsiaInspection-GarmentInspection.mp4",
        autostart: "autostart",
        height: 416,
        width: 733
      });
    }else{
      $("#demovid").hide();
      $("#inspectvid").show();
    }
  }
  
  if(vidtype == "demo"){
    if($("#JWvideo").length > 0){
      jwplayer("JWvideo").setup({
        file: "https://s3.asiainspection.com/files/videos/AsiaInspectionServiceDemonstrationHD.mp4",
        autostart: "autostart",
        height: 416,
        width: 733
      });
    }else{
      $("#inspectvid").hide();
      $("#demovid").show();
    }
  }
  
});

/******************************************************************************************************************/
