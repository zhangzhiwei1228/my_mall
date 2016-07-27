$(function(){
	$('.buy-muns .click-nums .add').click(function(){
        var $this = $(this),
            $input = $this.parent().find('input'),

            num = parseInt($input.val());
        $input.val(num+1);

      })

    $('.buy-muns .click-nums .jian').click(function(){
        var $this = $(this),
            $input = $this.parent().find('input'),
            num = parseInt($input.val());
        if(num > 1){
            $input.val(num-1);

        }else{
            $input.val(1);

        }
    })

    $('.click-nums input').keyup(function() {
        var n = parseInt($(this).parent().find("input").val());
        if(n > 1){
            $(this).parent().find("input").val(n);

        }else{
            $(this).parent().find("input").val(1);

        }
    });
});