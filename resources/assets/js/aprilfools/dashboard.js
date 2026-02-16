import Matter from "matter-js";

const tiles = Array.from(document.querySelectorAll('dashboard.small-box'))

if (tiles.length === 0) return;

const{ Engine,Runner, Bodies, Composite, Events, Body } = Matter;

const engine = Engine.create();
engine.gravity.y = 1.2;

const runner = Runner.create();
Runner.run(runner, engine);

const overlay = document.createElement("div");
overlay.id = "dom-overlay";
overlay.style.position = "fixed";
overlay.style.inset = "0";
overlay.style.zIndex = "999999";
overlay.style.pointerEvents = "none";
document.body.appendChild(overlay);

const wallT = 200;
let walls = [];

function addWalls() {
    const h = window.innerHeight;
    const w = window.innerWidth;

    const floor = Bodies.rectangle(w / 2, h + wallT / 2, w + wallT * 2, wallT, { isStatic: true });
    const ceil  = Bodies.rectangle(w / 2, -wallT / 2, w + wallT * 2, wallT, { isStatic: true });
    const left  = Bodies.rectangle(-wallT / 2, h / 2, wallT, h + wallT * 2, { isStatic: true });
    const right = Bodies.rectangle(w + wallT / 2, h / 2, wallT, h + wallT * 2, { isStatic: true });

    walls = [floor, ceil, left, right];
    Composite.add(engine.world, walls);
}

addWalls();