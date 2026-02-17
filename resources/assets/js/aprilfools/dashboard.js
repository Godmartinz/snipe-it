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

window.addEventListener("resize", () => {
    Composite.remove(engine.world, walls);
    addWalls();
});

// ------------------------------------
// Visual sync
// ------------------------------------
const visuals = new Map();

Events.on(engine, "afterUpdate", () => {
    for (const { body, el, w, h } of visuals.values()) {
        const x = body.position.x - w / 2;
        const y = body.position.y - h / 2;
        el.style.transform = `translate(${x}px, ${y}px) rotate(${body.angle}rad)`;
    }
    // Clamp flipper angles
    if (leftFlipper) {
        if (leftFlipper.angle < -1.15) Body.setAngle(leftFlipper, -1.15);
        if (leftFlipper.angle > -0.25) Body.setAngle(leftFlipper, -0.25);
    }
    if (rightFlipper) {
        if (rightFlipper.angle > 1.15) Body.setAngle(rightFlipper, 1.15);
        if (rightFlipper.angle < 0.25) Body.setAngle(rightFlipper, 0.25);
    }

});

// ------------------------------------
// Pie Chart Ball
// ------------------------------------
let ballBody = null;
let ballEl = null;

function createPieBall(scale = 0.45) {
    if (ballBody) Composite.remove(engine.world, ballBody);
    if (ballEl) ballEl.remove();

    const chart = document.getElementById("statusPieChart");
    if (!chart) return;

    const rect = chart.getBoundingClientRect();
    const diameter = Math.min(rect.width, rect.height) * scale;
    const radius = diameter / 2;

    ballEl = chart.cloneNode(true);
    ballEl.removeAttribute("id");

    ballEl.style.position = "fixed";
    ballEl.style.left = "0";
    ballEl.style.top = "0";
    ballEl.style.width = `${diameter}px`;
    ballEl.style.height = `${diameter}px`;
    ballEl.style.borderRadius = "50%";
    ballEl.style.overflow = "hidden";
    ballEl.style.pointerEvents = "none";
    ballEl.style.zIndex = "999999";
    ballEl.style.boxShadow = "0 14px 20px rgba(0,0,0,.35)";

    overlay.appendChild(ballEl);

    const startX = rect.left + rect.width / 2;
    const startY = rect.top + rect.height / 2;

    ballBody = Bodies.circle(startX, startY, radius, {
        restitution: 0.7,
        friction: 0.02,
        frictionAir: 0.01,
    });

    Composite.add(engine.world, ballBody);
    visuals.set(ballBody.id, { body: ballBody, el: ballEl, w: diameter, h: diameter });
}

createPieBall();

// ------------------------------------
// Flippers
// ------------------------------------
function createPanelFlipperVisual(panelBox, flipperW, flipperH) {
    const clone = panelBox.cloneNode(true);

    // Resize to flipper size
    clone.style.position = "fixed";
    clone.style.left = "0";
    clone.style.top = "0";
    clone.style.width = `${flipperW}px`;
    clone.style.height = `${flipperH}px`;

    // FLIPPER SHAPE (pill)
    clone.style.borderRadius = "9999px";
    clone.style.overflow = "hidden";

    // Keep original look/colors; just make it look like a paddle
    clone.style.pointerEvents = "none";
    clone.style.zIndex = "999999";
    clone.style.boxSizing = "border-box";

    // Minimal polish (optional, remove if you want *zero* styling)
    clone.style.boxShadow = "0 10px 18px rgba(0,0,0,.28)";
    clone.style.outline = "1px solid rgba(255,255,255,.08)";

    // Make inner content fit in a short paddle
    const header = clone.querySelector(".box-header");
    if (header) {
        header.style.padding = "6px 12px";
    }

    const body = clone.querySelector(".box-body");
    if (body) {
        body.style.padding = "6px 12px";
        body.style.maxHeight = `${flipperH - 30}px`;
        body.style.overflow = "hidden";
    }

    // Remove collapse tools so you don't have buttons inside a paddle
    const tools = clone.querySelector(".box-tools");
    if (tools) tools.remove();

    overlay.appendChild(clone);
    return clone;
}
let leftFlipper, rightFlipper;

function createFlippers() {
    const w = window.innerWidth;
    const h = window.innerHeight;

    const flipperW = 240;
    const flipperH = 64;
    const y = h * 0.78;

    const locationsBox  = document.querySelector("#dashLocationSummary")?.closest(".box");
    const categoriesBox = document.querySelector("#dashCategorySummary")?.closest(".box"); // change if needed

    // Physics bodies (rectangles)
    leftFlipper = Bodies.rectangle(w * 0.40, y, flipperW, flipperH, {
        density: 0.004,
        friction: 0,
        restitution: 0.2,
    });

    const leftHinge = Constraint.create({
        pointA: { x: w * 0.33, y },
        bodyB: leftFlipper,
        pointB: { x: -flipperW / 2 + 12, y: 0 },
        stiffness: 1,
        length: 0,
    });

    rightFlipper = Bodies.rectangle(w * 0.60, y, flipperW, flipperH, {
        density: 0.004,
        friction: 0,
        restitution: 0.2,
    });

    const rightHinge = Constraint.create({
        pointA: { x: w * 0.67, y },
        bodyB: rightFlipper,
        pointB: { x: flipperW / 2 - 12, y: 0 },
        stiffness: 1,
        length: 0,
    });

    Composite.add(engine.world, [leftFlipper, rightFlipper, leftHinge, rightHinge]);

    // Visuals = masked panel clones
    if (locationsBox) {
        const leftEl = createPanelFlipperVisual(locationsBox, flipperW, flipperH);
        visuals.set(leftFlipper.id, { body: leftFlipper, el: leftEl, w: flipperW, h: flipperH });
    } else {
        console.warn("Locations box not found for left flipper.");
    }

    if (categoriesBox) {
        const rightEl = createPanelFlipperVisual(categoriesBox, flipperW, flipperH);
        visuals.set(rightFlipper.id, { body: rightFlipper, el: rightEl, w: flipperW, h: flipperH });
    } else {
        console.warn("Categories box not found for right flipper (check table id).");
    }

    // Rest angles (like a real table)
    Body.setAngle(leftFlipper, -0.35);
    Body.setAngle(rightFlipper, 0.35);
}
createFlippers();

// ------------------------------------
// Input
// ------------------------------------
function flickToward(px, py, strength = 0.08) {
    if (!ballBody) return;

    const dx = px - ballBody.position.x;
    const dy = py - ballBody.position.y;
    const mag = Math.max(1, Math.hypot(dx, dy));

    Body.applyForce(ballBody, ballBody.position, {
        x: (dx / mag) * strength,
        y: (dy / mag) * strength - 0.03,
    });
}
const FLIP_V = 1.2;
const RETURN_V = 0.8;

window.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft")  Body.setAngularVelocity(leftFlipper, -FLIP_V);
    if (e.key === "ArrowRight") Body.setAngularVelocity(rightFlipper, FLIP_V);
});

window.addEventListener("keyup", (e) => {
    if (e.key === "ArrowLeft")  Body.setAngularVelocity(leftFlipper, RETURN_V);
    if (e.key === "ArrowRight") Body.setAngularVelocity(rightFlipper, -RETURN_V);
});

window.addEventListener("pointerdown", (e) => {
    flickToward(e.clientX, e.clientY, 0.09);
});

window.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft")  Body.setAngularVelocity(leftFlipper, -0.8);
    if (e.key === "ArrowRight") Body.setAngularVelocity(rightFlipper, 0.8);
    if (e.key === " ") Body.applyForce(ballBody, ballBody.position, { x: 0, y: -0.15 });
    if (e.key === "r") createPieBall();
});
