var theWheel = new Winwheel({
        'numSegments' : 8,
        'drawText' : false,
        'outerRadius' : 170,
        'textOrientation' : 'curved',
        'textAlignment'   : 'outer',
        'outerRadius'  : 150,
        'segments'    :
        [
           {'fillStyle' : '#000000', 'text' : 'ดำ', 'textFillStyle' : '#ffffff'},
           {'fillStyle' : '#ff0000', 'text' : 'แดง', 'textFillStyle' : '#ffffff'},
           {'fillStyle' : '#000000', 'text' : 'ดำ', 'textFillStyle' : '#ffffff'},
           {'fillStyle' : '#ff0000', 'text' : 'แดง', 'textFillStyle' : '#ffffff'},
           {'fillStyle' : '#000000', 'text' : 'ดำ', 'textFillStyle' : '#ffffff'},
           {'fillStyle' : '#ff0000', 'text' : 'แดง', 'textFillStyle' : '#ffffff'},
           {'fillStyle' : '#000000', 'text' : 'ดำ', 'textFillStyle' : '#ffffff'},
           {'fillStyle' : '#ff0000', 'text' : 'แดง', 'textFillStyle' : '#ffffff'},
        ],
        'animation' :
        {
            'type'          : 'spinToStop',
            'duration'      : 120,
            'spins'         : 150,
            'callbackFinished' : alertPrize
        }
    });

// function resetWheel()
// {
// 	theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
//     theWheel.rotationAngle = 0;     // Re-set the wheel angle to 0 degrees.
//     theWheel.draw();                // Call draw to render changes to the wheel.
//
//     theWheel.startAnimation();
// }

function stopsWing(result){
	var stopAt = theWheel.getRandomForSegment(result);
    // Important thing is to set the stopAngle of the animation before stating the spin.
	theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
    theWheel.rotationAngle = 0;     // Re-set the wheel angle to 0 degrees.
    theWheel.draw(); 
	
	
	theWheel.animation.stopAngle = stopAt;	
	theWheel.animation.duration = 5;
	theWheel.animation.spins = 5;
	theWheel.draw(); 
    // Start the spin animation here.
	theWheel.startAnimation();	
}
function continueWing(){
	theWheel.animation.duration = 200;
	theWheel.animation.spins = 150;
	theWheel.stopAnimation(false);
    theWheel.rotationAngle = 0; 
	theWheel.draw(); 
    // Start the spin animation here.
	theWheel.startAnimation();
}

 
function alertPrize(indicatedSegment)
{
    var textColor = indicatedSegment.text == 'ดำ' ? 'color: black; font-weight: bold;' : 'color: red; font-weight: bold';
    swal({
        width: '50%',
        title: 'แจ้งผลดำแดง',
        html: '<div style="font-size:18px;">'+'ผลที่ออกในรอบนี้ คือ <span style="'+textColor+'">'+indicatedSegment.text+'</span> กดปุ่มยืนยันเพื่อทำการเล่นต่อไป <br>หมายเหตุ กรุณาเลือกดำแดงรอบต่อไปเพื่อทำการเล่นรอบใหม่</div>',
        type: 'info',
        showCancelButton: false,
        confirmButtonText: 'ยืนยัน'
    });
}
/*
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = ajaxStateChange;
 
    // Function called on click of spin button.
    function calculatePrizeOnServer()
    {
        // Make get request to the server-side script.
        xhr.open('GET',"calculate_prize.php", true);
        xhr.send('');
    }
 
    // Called when state of the HTTP request changes.
    function ajaxStateChange()
    {
        if (xhr.readyState < 4)
        {
            return;
        }
 
        if (xhr.status !== 200)
        {
            return;
        }
 
        // The request has completed.
        if (xhr.readyState === 4)
        {
            var segmentNumber = xhr.responseText;   // The segment number should be in response.
 
            if (segmentNumber)
            {
                // Get random angle inside specified segment of the wheel.
                var stopAt = myWheel.getRandomForSegment(segmentNumber);
 
                // Important thing is to set the stopAngle of the animation before stating the spin.
                myWheel.animation.stopAngle = stopAt;
 
                // Start the spin animation here.
                myWheel.startAnimation();
            }
        }
    }
 
    // Usual pointer drawing code.
    drawTriangle();
 
    function drawTriangle()
    {
        // Get the canvas context the wheel uses.
        var ctx = myWheel.ctx;
 
        ctx.strokeStyle = 'navy';  // Set line colour.
        ctx.fillStyle   = 'aqua';  // Set fill colour.
        ctx.lineWidth   = 2;
        ctx.beginPath();           // Begin path.
        ctx.moveTo(170, 5);        // Move to initial position.
        ctx.lineTo(230, 5);        // Draw lines to make the shape.
        ctx.lineTo(200, 40);
        ctx.lineTo(171, 5);
        ctx.stroke();              // Complete the path by stroking (draw lines).
        ctx.fill();                // Then fill.
    }
*/
