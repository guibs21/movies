// slider
$("#ex2").slider({});

// slider date min max
$("#filterSlider").on("submit", function(e){
    // e.preventDefault();
    var valeur = document.getElementById("slider").innerHTML; 
    a = valeur.split(' : ');
    $("#min").val(a[0]);
    $("#max").val(a[1]);
});


