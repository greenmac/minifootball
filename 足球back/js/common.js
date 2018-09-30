$(function(){
var radio_1=$(".radio_1:checked").val();
    radio_2=$(".radio_2:checked").val();
    radio_3=$(".radio_3:checked").val();

function myFunction()
{
  if (radio_1==null) {
	$("#items3").removeClass("it3").prev(".fx").addClass("items_fx");
	$("#items4").removeClass("it4").prev(".fx").addClass("items_fx");
	
	$("#DiBasic_1").removeClass("it9").prev(".fx").addClass("items_fx");
	$("#DiBasic_2").removeClass("it10").prev(".fx").addClass("items_fx");
	
   } 
  if(radio_2==null){
    $("#items5").removeClass("it5").prev(".fx").addClass("items_fx");
	$("#items6").removeClass("it6").prev(".fx").addClass("items_fx");


	}
  if(radio_3==null){
    $("#items7").removeClass("it7").prev(".fx").addClass("items_fx");
	$("#items8").removeClass("it8").prev(".fx").addClass("items_fx");


	$("#DiBasic_3").removeClass("it11").prev(".fx").addClass("items_fx");
	$("#DiBasic_4").removeClass("it12").prev(".fx").addClass("items_fx");
	}

}
myFunction()

$(".radio_1").click(function(){
	myFunction()
	var radio_1=$(".radio_1:checked").val();
	if(radio_1===radio_1){
		$("#items3").addClass("it3");
	    $("#items4").addClass("it4");

	    $("#DiBasic_1").addClass("it9");
	    $("#DiBasic_2").addClass("it10");

	    $(".it3,.it4").sortable({connectWith: ".it3,.it4", });
        $(".it3,.it4").disableSelection();

         $(".it9,.it10").sortable({connectWith: ".it9,.it10",});
        $(".it9,.it10").disableSelection();


        $("#items3").prev(".fx").removeClass("items_fx");
	    $("#items4").prev(".fx").removeClass("items_fx");

	    $("#DiBasic_1").prev(".fx").removeClass("items_fx");
	    $("#DiBasic_2").prev(".fx").removeClass("items_fx");
	}

		
	
});

$(".radio_2").click(function(){
	myFunction()
	var radio_2=$(".radio_2:checked").val();
	if(radio_2===radio_2){
		$("#items5").addClass("it5");
	    $("#items6").addClass("it6");

	    

	    $(".it5,.it6").sortable({connectWith: ".it5,.it6",});
        $(".it5,.it6").disableSelection();

        $("#items5").prev(".fx").removeClass("items_fx");
	    $("#items6").prev(".fx").removeClass("items_fx");

	}

});

$(".radio_3").click(function(){
	myFunction()
	var radio_3=$(".radio_3:checked").val();
	if(radio_3===radio_3){

		$("#items7").addClass("it7");
	    $("#items8").addClass("it8");

        $("#DiBasic_3").addClass("it11");
	    $("#DiBasic_4").addClass("it12");

	    $(".it7,.it8").sortable({connectWith: ".it7,.it8",});
        $(".it7,.it8").disableSelection();


        $(".it11,.it12").sortable({connectWith: ".it11,.it12",});
        $(".it11,.it12").disableSelection();

        $("#items7").prev(".fx").removeClass("items_fx");
	    $("#items8").prev(".fx").removeClass("items_fx");

	    $("#DiBasic_3").prev(".fx").removeClass("items_fx");
	    $("#DiBasic_4").prev(".fx").removeClass("items_fx");
	}



});


$("#BasicBtn").click(function(){

	$(".DiBasic_box").slideToggle("slow");

});
$("#PersonaBtn").click(function(){
	
	$(".Persona_box").slideToggle("slow");

});
// var radio_1=$('input[name=example-radios]:checked').val();

// var radio_2=$(".radio_1[name=example-radios]:checked").val();
// if (inp2==null) {
// 	$("#items1").hide();
// }else{

// }

// $(".radio_1").click(function(){


// console.log(inp2);
	
// if(inp1===inp1){
   
// 	$("#items1").show();
// }else
// {
//     $("#items1").hide();
// }

//   });
});

