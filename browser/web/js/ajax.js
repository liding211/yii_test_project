function convertLike(like) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var like_id = $(like).attr('id');
    var like_parsed_data = like_id.split('_', 2);
    $.ajax({
        type: "post",
        url: "/convert_like",
        data: { 
            object_type: like_parsed_data[0], 
            object_id: like_parsed_data[1],
            _csrf : csrfToken
        },
        success: function( result ) {
            if(result){
                $( "#" + like_id ).text( result );
            }
        }
    });
    return false;
}