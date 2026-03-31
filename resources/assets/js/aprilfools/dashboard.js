import Matter from "matter-js";

const { Engine, Runner, Bodies, Composite, Events, Body, Constraint } = Matter;

const engine = Engine.create();
engine.gravity.y = 1.4;

Runner.run(Runner.create(), engine);

// ------------------------------------
// Overlay for physics visuals
// ------------------------------------
const overlay = document.createElement("div");
overlay.style.position = "fixed";
overlay.style.inset = "0";
overlay.style.zIndex = "999999";
overlay.style.pointerEvents = "none";
document.body.appendChild(overlay);

// ------------------------------------
// World bounds
// ------------------------------------
const wallT = 200;
let walls = [];

function addWalls() {
    const w = window.innerWidth;
    const h = window.innerHeight;

    const floor = Bodies.rectangle(w / 2, h + wallT / 2, w + wallT * 2, wallT, { isStatic: true });
    const ceil  = Bodies.rectangle(w / 2, -wallT / 2, w + wallT * 2, wallT, { isStatic: true });
    const left  = Bodies.rectangle(-wallT / 2, h / 2, wallT, h + wallT * 2, { isStatic: true });
    const right = Bodies.rectangle(w + wallT / 2, h / 2, wallT, h + wallT * 2, { isStatic: true });

    walls = [floor, ceil, left, right];
    Composite.add(engine.world, walls);
}

addWalls();

// ------------------------------------
// Dashboard boxes
// ------------------------------------
const items = [];

function setupBoxes() {
    const boxes = document.querySelectorAll(".small-box");

    boxes.forEach((el, index) => {
        const rect = el.getBoundingClientRect();

        const body = Bodies.rectangle(
            rect.left + rect.width / 2,
            rect.top + rect.height / 2 + (index * 4),
            rect.width,
            rect.height,
            {
                restitution: 0.15,
                friction: 0.9,
                frictionAir: 0.02,
                density: 0.002
            }
        );

        el.style.position = "fixed";
        el.style.left = "0";
        el.style.top = "0";
        el.style.margin = "0";
        el.style.width = `${rect.width}px`;
        el.style.zIndex = "999999";
        el.style.willChange = "transform";
        el.style.transform = `translate(${rect.left}px, ${rect.top}px)`;

        Composite.add(engine.world, body);

        items.push({
            el,
            body,
            width: rect.width,
            height: rect.height
        });
    });
}

setupBoxes();

// ------------------------------------
// Sync Matter -> DOM
// ------------------------------------
function render() {
    for (const item of items) {
        const x = item.body.position.x - item.width / 2;
        const y = item.body.position.y - item.height / 2;
        const angle = item.body.angle;

        item.el.style.transform = `translate(${x}px, ${y}px) rotate(${angle}rad)`;
    }
}

Events.on(engine, "afterUpdate", render);

// ------------------------------------
// Handle resize
// ------------------------------------
let resizeTimeout = null;

window.addEventListener("resize", () => {
    clearTimeout(resizeTimeout);

    resizeTimeout = setTimeout(() => {
        if (walls.length) {
            Composite.remove(engine.world, walls);
        }

        addWalls();
    }, 150);
});