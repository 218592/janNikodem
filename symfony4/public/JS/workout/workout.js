var numberOfWorkout = 1;
var numberOfWorkoutBorder = 1;

// to z serwera
var maxNumberOfWorkout = 0;
var valueFromTimer;
var valueFromTimerCopy;


// zaladuj wstepne dane
window.onload = function () {
    document.getElementById("work" + (1).toString()).style.color = "red";
    document.getElementById("border" + (1).toString()).style.border = "1px solid red";

    var ex;
    var resBox;

    resBox = document.getElementById("workCheck" + (1).toString());
    resBox.getElementsByTagName('span')[1].innerHTML = '<i class="far fa-dot-circle" style="color:red;"></i>';

    maxNumberOfWorkoutFromId = document.getElementById("maxNumberOfWorkout").textContent;
    maxNumberOfWorkout = parseInt(maxNumberOfWorkoutFromId);

    var sum = 0;
    for (var step = 1; step < maxNumberOfWorkout + 1; step++) {
        ex = document.getElementById("work" + (step).toString() + "Srv").textContent;
        sum = sum + parseInt(ex);
        document.getElementById("work" + (step).toString()).textContent = ex;

        resBox = document.getElementById("workCheck" + step.toString());
        resBox.getElementsByTagName('span')[0].innerHTML = ex;
    }

    document.getElementById("sum").innerHTML = sum + " powtórzeń";

    var valueFromT = document.getElementById("timer").textContent;
    valueFromTimer = valueFromT;
    valueFromTimerCopy = valueFromT;

};




function addWorkout() {

    var valueFromWorkout = document.getElementById("work" + (numberOfWorkout).toString()).textContent;
    valueFromWorkout++;
    document.getElementById("work" + (numberOfWorkout).toString()).textContent = valueFromWorkout;

}

function subtractWorkout() {

    var valueFromWorkout = document.getElementById("work" + (numberOfWorkout).toString()).textContent;

    if (valueFromWorkout != 0)
        valueFromWorkout--;

    document.getElementById("work" + (numberOfWorkout).toString()).textContent = valueFromWorkout;
}


function fastStep() {

    // zatrzymaj wszystkie timeouty 
    var id = window.setTimeout(function () {}, 0);
    while (id--) {
        window.clearTimeout(id); // will do nothing if no timeout with id is present
    }
    document.getElementById("timer").innerHTML = valueFromTimerCopy;

    if (numberOfWorkoutBorder < maxNumberOfWorkout) {
        // wrzuc nastepna serie
        numberOfWorkoutBorder++;
        document.getElementById("border" + (numberOfWorkoutBorder).toString()).style.border = "1px solid red";
        document.getElementById("work" + (numberOfWorkout).toString()).style.color = "rgb(132, 131, 134)";
        document.getElementById("work" + (numberOfWorkoutBorder).toString()).style.color = "red";
        numberOfWorkout = numberOfWorkoutBorder;

        //usun poprzednia serie
        document.getElementById("border" + (numberOfWorkoutBorder - 1).toString()).style.border = "1px solid transparent";
        document.getElementById("work" + (numberOfWorkoutBorder - 1).toString()).style.color = "rgb(132, 131, 134)";


        // tutaj jeszcze motyw z aaakceptowanymi treningmai, no wiesz
        var resBox = document.getElementById("workCheck" + (numberOfWorkoutBorder).toString());
        resBox.getElementsByTagName('span')[1].innerHTML = '<i class="far fa-dot-circle" style="color:red;"></i>';
    }

    if (numberOfWorkoutBorder == maxNumberOfWorkout) {
        document.getElementById("timer").innerHTML = "Koniec treningu!";
    }

}


function playTimer() {



    if (valueFromTimer == 0) {

        valueFromTimer = valueFromTimerCopy;
        document.getElementById("timer").innerHTML = valueFromTimer;

        const sound = new Audio('../../Music/b.mp3');
        sound.play();

        fastStep();
    } else {


        if (numberOfWorkoutBorder != maxNumberOfWorkout) {

            valueFromTimer--;
            document.getElementById("timer").innerHTML = valueFromTimer;
            v = setTimeout("playTimer()", 1000);
        } else {
            document.getElementById("timer").innerHTML = "Koniec treningu!";
        }

    }

}



function rightFollow() {

    if (numberOfWorkout == maxNumberOfWorkout) {
        document.getElementById("work" + (1).toString()).style.color = "red";
        document.getElementById("work" + (maxNumberOfWorkout).toString()).style.color = "rgb(132, 131, 134)";
        numberOfWorkout = 1;
    } else {
        document.getElementById("work" + (numberOfWorkout).toString()).style.color = "rgb(132, 131, 134)";
        document.getElementById("work" + (numberOfWorkout + 1).toString()).style.color = "red";
        numberOfWorkout++;
    }

}

function leftFollow() {

    if (numberOfWorkout == 1) {
        document.getElementById("work" + (1).toString()).style.color = "rgb(132, 131, 134)";
        document.getElementById("work" + (maxNumberOfWorkout).toString()).style.color = "red";
        numberOfWorkout = maxNumberOfWorkout;
    } else {
        document.getElementById("work" + (numberOfWorkout).toString()).style.color = "rgb(132, 131, 134)";
        document.getElementById("work" + (numberOfWorkout - 1).toString()).style.color = "red";
        numberOfWorkout--;
    }


}