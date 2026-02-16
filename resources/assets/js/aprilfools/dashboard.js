import Matter from "matter-js";

const { Engine, Render, Runner, Bodies, Composite } = Matter;

const engine = Engine.create();

const render = Render.create({
    element: document.body,
    engine,
    options: {
        width: window.innerWidth,
        height: window.innerHeight,
        wireframes: false,
        background: "transparent",
    },
});

const floor = Bodies.rectangle(
    window.innerWidth / 2,
    window.innerHeight + 30,
    window.innerWidth,
    60,
    { isStatic: true }
);

const box = Bodies.rectangle(200, 50, 80, 80, {
    restitution: 0.6,   // bounciness
    friction: 0.2,      // sliding resistance
});

Composite.add(engine.world, [floor, box]);

Render.run(render);
Runner.run(Runner.create(), engine);


