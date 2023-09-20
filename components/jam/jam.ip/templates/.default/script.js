
function aj(){
    console.log("ddd");
    var ip = $('.jam-input-search').val();
    console.log(ip);
    $.ajax({
        url: 'http://bxtest.elec-commerc.com/ajaxip/',
        method: 'post',
        data: {text: ip},
        success: function(data){
            if(data["error"] == false){
                $('.jam-form').parent().append(
                    '<div class="alert-message">Город '+data["data"].UF_CITY+'</div>'+
                    '<div class="alert-message">Регион '+data["data"].UF_REG+'</div>'
                );
            }
        }
    
    });

}
