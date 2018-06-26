var total = 60;
var nodes;
var gravity;
var repeller;
var canvas;

function setup() {
  canvas = createCanvas(windowWidth, windowHeight);
  canvas.parent('canvas');
  nodes = [];
  for (var i = 0; i < total; i++) {
    nodes.push(new Particle(createVector(floor(random(width)), floor(random(height)))));
  }

}

function draw() {

  background(51);
  for (var i = 0; i < nodes.length; i++) {
    nodes[i].show();
    for (var j = 0; j < nodes.length; j++) {
      var distance = dist(nodes[i].location.x, nodes[i].location.y, nodes[j].location.x, nodes[j].location.y);
      strokeWeight(0.5);
      if (distance < 200) {
        var lineAlpha = map(distance, 0, 200, 255, 20);
        stroke(100, 100, 255, lineAlpha);
        line(nodes[i].location.x, nodes[i].location.y, nodes[j].location.x, nodes[j].location.y);
      }
    }
    nodes[i].update();
  }




}

function mousePressed() {
  nodes.push(new Particle(createVector(mouseX, mouseY)));
}
