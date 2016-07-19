app.filter("rounded",function(){
    return function(val, places){
        var multiplier = ( places ) ? Math.pow(10, places) : 1;
        return Math.round(val * multiplier) / multiplier;
    }
});