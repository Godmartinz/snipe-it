import Matter from "matter-js";

const {Engine, Runner, Bodies, Composite, Events, Body} = Matter;

const engine = Engine.create();
engine.gravity.y = 1.4;

Runner.run(Runner.create(), engine);

// ------------------------------------
// Overlay for falling clones
// ------------------------------------
const overlay = document.createElement("div");
overlay.style.position = "fixed";
overlay.style.inset = "0";
overlay.style.zIndex = "999999";
overlay.style.pointerEvents = "none";
document.body.appendChild(overlay);

// ------------------------------------
// Banner
// ------------------------------------
const banner = document.createElement("div");
banner.style.position = "fixed";
banner.style.top = "20px";
banner.style.left = "50%";
banner.style.transform = "translateX(-50%)";
banner.style.zIndex = "1000000";
banner.style.background = "#111";
banner.style.color = "#fff";
banner.style.padding = "12px 16px";
banner.style.borderRadius = "8px";
banner.style.boxShadow = "0 4px 12px rgba(0,0,0,0.3)";
banner.style.fontSize = "14px";
banner.style.display = "none";
banner.style.alignItems = "center";
banner.style.gap = "12px";

// animation
banner.style.opacity = "0";
banner.style.transition = "opacity 0.3s ease";

banner.innerHTML = `
    <span>The Dashboard appears to be unstable.</span>
    <a href="https://demo.snipeitapp.com/login" style="color:#4da3ff; text-decoration:underline;">
        View real demo
    </a>
    <button id="banner-reset" style="
        background:#444;
        color:white;
        border:none;
        padding:4px 8px;
        border-radius:4px;
        cursor:pointer;
    ">
        Reset
    </button>
`;

document.body.appendChild(banner);

function showBanner() {
    if (banner.style.display === "none") {
        banner.style.display = "flex";

        requestAnimationFrame(() => {
            banner.style.opacity = "1";
        });
    }
}

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
// Falling clone registry
// ------------------------------------
const fallingItems = [];

// ------------------------------------
// Create falling clone on click
// ------------------------------------
function makeBoxFall(originalEl, event) {
    if (originalEl.dataset.falling === "true") return;

    const rect = originalEl.getBoundingClientRect();
    const clone = originalEl.cloneNode(true);

    originalEl.dataset.falling = "true";
    originalEl.style.visibility = "hidden";

    clone.style.position = "fixed";
    clone.style.left = "0";
    clone.style.top = "0";
    clone.style.margin = "0";
    clone.style.width = `${rect.width}px`;
    clone.style.height = `${rect.height}px`;
    clone.style.zIndex = "999999";
    clone.style.pointerEvents = "none";
    clone.style.willChange = "transform";
    clone.style.transformOrigin = "center center";
    clone.style.transform = `translate(${rect.left}px, ${rect.top}px)`;

    overlay.appendChild(clone);

    const body = Bodies.rectangle(
        rect.left + rect.width / 2,
        rect.top + rect.height / 2,
        rect.width,
        rect.height,
        {
            restitution: 0.12,
            friction: 0.9,
            frictionAir: 0.02,
            density: 0.002
        }
    );

    Composite.add(engine.world, body);

    fallingItems.push({
        originalEl,
        cloneEl: clone,
        body,
        width: rect.width,
        height: rect.height
    });

    const clickX = event.clientX - rect.left;
    const dir = clickX < rect.width / 2 ? -1 : 1;

    Body.applyForce(body, body.position, {
        x: 0.015 * dir,
        y: -0.008
    });

    Body.setAngularVelocity(body, 0.12 * dir);

    showBanner();
}

// ------------------------------------
// Click bindings
// ------------------------------------
function setupBoxClicks() {
    const boxes = document.querySelectorAll(".small-box, .physics-box");

    boxes.forEach((el) => {
        el.style.cursor = "pointer";

        const link = el.closest("a");
        if (link) {
            link.addEventListener("click", (e) => e.preventDefault());
        }

        el.addEventListener("click", (event) => {
            event.preventDefault();
            event.stopPropagation();
            makeBoxFall(el, event);
        });
    });
}

setupBoxClicks();

// ------------------------------------
// Render loop
// ------------------------------------
Events.on(engine, "afterUpdate", () => {
    for (const item of fallingItems) {
        const x = item.body.position.x - item.width / 2;
        const y = item.body.position.y - item.height / 2;
        const angle = item.body.angle;

        item.cloneEl.style.transform =
            `translate(${x}px, ${y}px) rotate(${angle}rad)`;
    }
});

// ------------------------------------
// Reset
// ------------------------------------
function resetDashboard() {
    for (const item of fallingItems) {
        Composite.remove(engine.world, item.body);

        item.cloneEl?.remove();

        if (item.originalEl) {
            item.originalEl.style.visibility = "visible";
            delete item.originalEl.dataset.falling;
        }
    }

    fallingItems.length = 0;
}

document.addEventListener("click", (e) => {
    if (e.target?.id === "banner-reset") {
        resetDashboard();

        banner.style.opacity = "0";

        setTimeout(() => {
            banner.style.display = "none";
        }, 300);
    }
});

// ------------------------------------
// Resize handling
// ------------------------------------
let resizeTimeout = null;

window.addEventListener("resize", () => {
    clearTimeout(resizeTimeout);

    resizeTimeout = setTimeout(() => {
        Composite.remove(engine.world, walls);
        addWalls();
    }, 150);
});