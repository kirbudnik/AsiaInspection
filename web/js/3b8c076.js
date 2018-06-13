$("#postSelector").on("click", "option", function() {
	var clickedOption = $(this).val();
	obj = $("#post_"+clickedOption);
	box = $("#mainEditBox");

	$(".postStoryURL", box).val( $(".post_Unique_Url", obj).val() );
	$(".postStoryTitle", box).val( $(".post_Title", obj).val() );
	CKEDITOR.instances.abstractEditor.setData( $(".post_Abstract", obj).val() );
	CKEDITOR.instances.contentEditor.setData( $(".post_Content", obj).val() );
	$(".postStoryIndustry", box).val( $(".post_Industry", obj).val() );
	$(".postStoryCountry", box).val( $(".post_Country", obj).val() );
	$(".postStoryDate", box).val( $(".post_Date", obj).val() );
	$(".postStoryMetaTitle", box).val( $(".post_Meta_Title", obj).val() );
	$(".postStoryMetaDesc", box).val( $(".post_Meta_Desc", obj).val() );
	$(".postStoryMetaKeywords", box).val( $(".post_Meta_Keywords", obj).val() );
	Tags = JSON.parse( $(".post_Tags", obj).val() );
	$(".postStoryTagCloud").html("");
	Tags.forEach(function(elm) {
		$(".postStoryTagCloud").append("<div class='postStoryTag'>"+elm+"</div>");
	});
});

$("#postStoryTags").on("keypress", function(e){
	if (e.keyCode == 13) {
		$(this).css("border-color","#cecfd1");
		tag = $.trim($(this).val());
		tag = tag.replace(/ /gi,"_");
		if (tag == "") {
			$(this).css("border-color","red");
		} else {
			existingTags = new Array();
			$(".postStoryTag",".postStoryTagCloud").each(function(){
				existingTags.push($(this).text());
			});
			if( $.inArray(tag, existingTags) == -1 ) $(".postStoryTagCloud").append("<div class='postStoryTag'>"+tag+"</div>");
		}
	}
});

$(document).on("click", ".postStoryTag", function(){
	$(this).remove();
});

$("#create_update_regulatory_story_button").on("click", function() {
	box = $("#mainEditBox");

	data = new Object();
	data.uniqueURL = $(".postStoryURL", box).val();
	data.Title = $(".postStoryTitle", box).val();
	data.Abstract = CKEDITOR.instances.abstractEditor.getData();
	data.Content = CKEDITOR.instances.contentEditor.getData();
	data.Industry = "";
	data.Country = "";
	data.postDate = $(".postStoryDate", box).val();
	data.Meta_Title = $(".postStoryMetaTitle", box).val();
	data.Meta_Desc = $(".postStoryMetaDesc", box).val();
	data.Meta_Keywords = $(".postStoryMetaKeywords", box).val();
	Tags = new Array();
	$(".postStoryTag", ".postStoryTagCloud").each(function(){
		Tags.push( $(this).text() );
	});
	data.Tags = JSON.stringify(Tags);

	console.log(data);
	$.ajax({
		type: "POST",
		url: "/internal/updatestory",
		async: "false",
		data: data,
		dataType: "json",
		success: function(msg){
			// Update in hidden elements
			// console.log(msg);
		}
	});
	
});