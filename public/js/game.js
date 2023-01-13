/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

// UNUSED EXPORTS: createArray, request

;// CONCATENATED MODULE: ./node_modules/stagnate/lib/jsx-runtime.js
function _collect(data, accumulator) {
    for (let i = 0; i < data.length; i += 1) {
        const value = data[i];
        if (Array.isArray(value)) {
            _collect(value, accumulator);
        }
        else if (value) {
            accumulator.push(value);
        }
    }
}
function collect(data) {
    if (Array.isArray(data)) {
        const accumulator = [];
        _collect(data, accumulator);
        return accumulator;
    }
    return data ? [data] : [];
}
function jsx(type, props) {
    const children = collect(props.children);
    if (type == "text") {
        const element = document.createTextNode(children.length ? children.join() : (props.value || ""));
        if (props.ref) {
            props.ref(element);
        }
        return element;
    }
    else if (typeof type == "string") {
        let element;
        if (type == "svg" || type == "path") {
            element = document.createElementNS("http://www.w3.org/2000/svg", type);
        }
        else {
            element = document.createElement(type);
        }
        for (const key in props) {
            if (key == "children") {
                continue;
            }
            else if (key.startsWith("on")) {
                element.addEventListener(key.slice(2).toLowerCase(), props[key]);
            }
            else if (key == "innerHTML") {
                element.innerHTML = props.innerHTML;
            }
            else if (key == "ref") {
                props.ref(element);
            }
            else if (key == "class") {
                const value = props.class;
                element.setAttribute("class", typeof value == "string" ? value : collect(value).join(" "));
            }
            else if (key == "style") {
                Object.assign(element.style, props.style);
            }
            else {
                const value = props[key];
                if (value || value == "") {
                    element.setAttribute(key == "viewBox" ? key : key.toLowerCase(), value);
                }
            }
        }
        children.forEach(x => element.appendChild(x instanceof Node ? x : document.createTextNode(x.toString())));
        return element;
    }
    else if ("prototype" in type && type.prototype._jsx) {
        props.children = children;
        const component = new type(props);
        const element = component._jsx();
        if (props.ref) {
            props.ref(component);
        }
        return element;
    }
    else {
        props.children = children;
        const element = type(props);
        if (props.ref) {
            props.ref(element);
        }
        return element;
    }
}
function createElement(type, props, ...children) {
    props.children = children;
    return jsx(type, props);
}
function Fragment(props) {
    return props.children;
}

//# sourceMappingURL=jsx-runtime.js.map
;// CONCATENATED MODULE: ./node_modules/kiss-jss/lib/Jss.js
function stringProduct(a, b) {
    const result = [];
    if (!a) {
        return b;
    }
    for (let i = 0; i < a.length; i += 1) {
        for (let j = 0; j < b.length; j += 1) {
            result.push(a[i] + b[j]);
        }
    }
    return result;
}
class Jss {
    constructor(options) {
        this.idGen = options.idGen;
        this.prefixedKeys = new Set(options.prefixedKeys);
        this.defaultUnits = new Map();
        for (const unit in options.defaultUnits) {
            options.defaultUnits[unit].forEach(x => this.defaultUnits.set(x, unit));
        }
    }
    processFontFace(data) {
        return (Array.isArray(data) ? data : [data]).map(x => "@font-face" + this.processRule("normal", x)).join("");
    }
    processRule(mode, data, path) {
        const buffer = [];
        const items = [];
        for (const key in data) {
            const item = data[key];
            if (key[0] == "&") {
                buffer.push(this.processRule("normal", item, stringProduct(path, key.slice(1).replace(/\$/g, ".$").split(/,/g))));
            }
            else if (key[0] == "@") {
                const match = key.match(/^@[^\s]*/);
                switch (match[0]) {
                    case "@keyframes":
                        buffer.push(key + "{" + this.processRule("object", item) + "}");
                        break;
                    case "@media":
                        buffer.push(key + "{" + this.processRule(path ? "object-resolve" : "object", item, path) + "}");
                        break;
                    case "@font-face":
                        buffer.push(this.processFontFace(item));
                        break;
                    default:
                        buffer.push(key + " " + item + ";");
                }
            }
            else if (mode != "normal") {
                buffer.push(this.processRule("normal", item, stringProduct(path, [mode == "object-resolve" ? ".$" + key : key])));
            }
            else if (key != "composes") {
                const keyName = key.replace(/[A-Z]/g, x => "-" + x.toLocaleLowerCase());
                const value = typeof item == "string" ? item : item + (this.defaultUnits.get(keyName) || "");
                items.push(keyName + ":" + value + ";");
                if (this.prefixedKeys.has(keyName)) {
                    items.push("-ms-" + keyName + ":" + value + ";");
                    items.push("-moz-" + keyName + ":" + value + ";");
                    items.push("-webkit-" + keyName + ":" + value + ";");
                }
            }
        }
        return items.length ? (path ? path.join(", ") : "") + "{" + items.join("") + "}" + buffer.join("") : buffer.join("");
    }
    compile(data, sheet) {
        const buffer = [];
        const classMap = new Map();
        const idMap = new Map();
        for (const key in data) {
            const item = data[key];
            const match = key.match(/^(@[^\s]+)(?:\s+([^\s].*))?/);
            if (match) {
                switch (match[1]) {
                    case "@keyframes": {
                        const id = this.idGen("keyframes-" + match[2], sheet);
                        idMap.set(match[2], id);
                        buffer.push("@keyframes " + id + "{" + this.processRule("object", item) + "}");
                        break;
                    }
                    case "@media":
                        buffer.push(key + "{" + this.processRule("object-resolve", item) + "}");
                        break;
                    case "@font-face":
                        buffer.push(this.processFontFace(item));
                        break;
                    case "@global":
                        buffer.push(this.processRule("object", item));
                        break;
                    default:
                        buffer.push(key + " " + item + ";");
                }
            }
            else {
                const id = this.idGen(key, sheet);
                idMap.set(key, id);
                classMap.set(key, item.composes ? id + " " + item.composes : id);
                buffer.push(this.processRule("normal", item, ["." + id]));
            }
        }
        const source = buffer.join("").replace(/\$([A-Za-z0-9_-]+)/g, (_, x) => idMap.get(x) || x);
        const classes = {};
        classMap.forEach((value, key) => classes[key] = value.replace(/\$([A-Za-z0-9_-]+)/g, (_, x) => idMap.get(x) || x));
        return { classes, source };
    }
    inject(target, data, sheet) {
        const { classes, source } = this.compile(data, sheet);
        const textNode = document.createTextNode(source);
        if (!target) {
            const style = document.createElement("style");
            style.appendChild(textNode);
            document.head.appendChild(style);
        }
        else {
            target.appendChild(textNode);
        }
        return classes;
    }
}
/* harmony default export */ const lib_Jss = ((/* unused pure expression or super */ null && (Jss)));
//# sourceMappingURL=Jss.js.map
;// CONCATENATED MODULE: ./node_modules/kiss-jss/lib/UniqueIdGen.js
class UniqueIdGen {
    constructor() {
        this.map = new Map();
    }
    static create() {
        return (new this()).generator;
    }
    get generator() {
        return (rule, sheet) => this.get(rule, sheet);
    }
    reset() {
        this.map.clear();
    }
    get(rule, sheet) {
        const id = sheet ? sheet + "-" + rule : rule;
        const n = this.map.get(id);
        this.map.set(id, n ? n + 1 : 1);
        return id + (n || "");
    }
}
//# sourceMappingURL=UniqueIdGen.js.map
;// CONCATENATED MODULE: ./node_modules/stagnate/lib/Component.js
class Component {
    constructor(props) {
        this._components = [];
        this._attached = false;
        this.refs = {};
        this.props = props;
    }
    render() {
        return null;
    }
    ref(key) {
        return (x) => {
            if (x instanceof Component) {
                x.bind(this);
            }
            if (key) {
                this.refs[key] = x;
            }
        };
    }
    _jsx() {
        this.onBeforeRender();
        const html = this.render();
        if (!html) {
            throw new Error("can not render component: render function returned null");
        }
        this.root = html;
        this.onRender();
        return html;
    }
    replace(parent, target) {
        if (this.root) {
            throw new Error("can not create a already created item");
        }
        if (!target) {
            target = parent.root;
        }
        this.onBeforeRender();
        const html = this.render();
        if (!html) {
            throw new Error("can not replace component: render function returned null");
        }
        this.root = html;
        this.onRender();
        this.parent = parent;
        if (target instanceof Component) {
            target.root.replaceWith(html);
            target.destroy();
        }
        else {
            target.replaceWith(html);
        }
        this.parent._components.push(this);
        if (parent._attached) {
            this.attach();
        }
    }
    create(parent, target, before) {
        if (this.root) {
            throw new Error("can not create a already created item");
        }
        if (!target) {
            target = parent.root;
        }
        else if (target instanceof Component) {
            target = target.root;
        }
        if (typeof before === "number") {
            before = target.children[Math.min(Math.max(0, before), target.children.length - 1)];
        }
        else if (before instanceof Component) {
            before = before.root;
        }
        this.onBeforeRender();
        const html = this.render();
        if (!html) {
            throw new Error("can not create component: render function returned null");
        }
        this.root = html;
        this.onRender();
        target.insertBefore(html, before === undefined ? null : before);
        this.parent = parent;
        this.parent._components.push(this);
        if (parent._attached) {
            this.attach();
        }
    }
    createOrphanized(target) {
        if (this.root) {
            throw new Error("can not create a already created item");
        }
        this.onBeforeRender();
        const html = this.render();
        if (!html) {
            throw new Error("can not create component: render function returned null");
        }
        this.root = html;
        this.onRender();
        target === null || target === void 0 ? void 0 : target.appendChild(html);
        this.attach();
    }
    bind(parent, target) {
        if (target) {
            if (this.root) {
                throw new Error("can not bind a already bound item");
            }
            this.root = target;
        }
        else if (!this.root) {
            throw new Error("can not bind not rendered item to itself");
        }
        this.parent = parent;
        parent._components.push(this);
        if (parent._attached) {
            this.attach();
        }
    }
    destroy() {
        this.detach();
        this.refs = {};
        this.root.remove();
        this.parent._components.splice(this.parent._components.indexOf(this), 1);
        this.parent = null;
        this.root = null;
    }
    attach() {
        for (let i = 0; i < this._components.length; i += 1) {
            const component = this._components[i];
            if (!component._attached) {
                component.attach();
            }
        }
        this._attached = true;
        this.onAttach();
    }
    detach() {
        this._components.forEach(x => x.detach());
        this._components = [];
        this._attached = false;
        this.onDetach();
    }
    get attached() {
        return this._attached;
    }
    get htmlRoot() {
        return this.root;
    }
    get components() {
        return this._components;
    }
    onBeforeRender() {
    }
    onRender() {
    }
    onAttach() {
    }
    onDetach() {
    }
}
//# sourceMappingURL=Component.js.map
;// CONCATENATED MODULE: ./src/index.tsx
var __awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};




const classes = new Jss({ idGen: UniqueIdGen.create(), defaultUnits: {}, prefixedKeys: [] }).inject(null, {
    "@global": {
        html: {
            fontSize: "0.8px",
            minHeight: "100%"
        },
        [`@media (max-width: 1000px)`]: {
            html: {
                fontSize: (100 / 1200) + "vw"
            }
        },
        body: {
            overflowX: "hidden",
            margin: 0,
            padding: 0,
            width: "100vw",
            userSelect: "none",
            fontFamily: "Trebuchet MS, Arial, sans-serif",
            backgroundColor: "gray",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            minHeight: "100vh"
        }
    },
    board: {
        display: "flex",
        flexDirection: "row",
        flexShrink: 0,
        flexWrap: "wrap",
        width: "950rem",
        height: "950rem",
    },
    tile: {
        width: "180rem",
        height: "180rem",
        margin: "5rem",
        backgroundColor: "white",
        cursor: "pointer",
        "& > img": {
            display: "none",
            width: "100%",
            height: "100%",
            objectFit: "cover",
            objectPosition: "center center"
        }
    },
    tileVisible: {
        backgroundColor: "transparent",
        cursor: "default",
        "& > img": {
            display: "block"
        }
    },
    popup: {
        position: "fixed",
        width: "100vw",
        height: "100vh",
        backgroundColor: "rgba(0,0,0,0.75)",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        top: 0,
        left: 0,
        opacity: 0,
        transition: "opacity 1s",
        "& > div": {
            width: "600rem",
            height: "300rem",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            fontSize: "24rem",
            backgroundColor: "white",
            border: "1rem solid black",
            color: "black"
        }
    }
}, "thunderbite");
function createArray(size, callback) {
    return Array.from({ length: size }, (_, i) => callback(i));
}
function request(method, url, data) {
    return new Promise((resolve, reject) => {
        const request = new XMLHttpRequest();
        request.responseType = "json";
        request.onload = _ => (request.status >= 200 && request.status < 300) ? resolve(request.response) : reject(new Error(request.statusText));
        request.onerror = _ => reject(new Error(request.statusText));
        request.open(method, url);
        request.setRequestHeader("Content-Type", "application/json");
        request.setRequestHeader("Accept", "application/json");
        request.send(JSON.stringify(data));
    }).then(data => {
        if (!data) {
            throw new Error("Invalid server response");
        }
        return data;
    });
}
class TileComponent extends Component {
    render() {
        return jsx("div", Object.assign({ onClick: this.props.onClick, class: classes.tile }, { children: jsx("img", { ref: this.ref("image") }) }));
    }
    setImage(src) {
        this.refs.image.src = src;
        this.root.classList.toggle(classes.tileVisible, true);
    }
}
class PopupComponent extends Component {
    render() {
        return jsx("div", Object.assign({ class: classes.popup }, { children: jsx("div", { children: jsx("div", { ref: this.ref("message") }) }) }));
    }
    setMessage(src) {
        this.refs.message.innerHTML = src;
    }
    show(animation) {
        this.root.style.removeProperty("display");
        if (animation) {
            this.root.clientHeight;
        }
        this.root.style.opacity = "1";
    }
    hide() {
        this.root.style.display = "none";
    }
}
class MainComponent extends Component {
    constructor(config) {
        super();
        this._interactive = true;
        this._config = config;
        this._tiles = [];
    }
    render() {
        return jsx("div", { children: [jsx("div", Object.assign({ class: classes.board }, { children: createArray(25, i => jsx(TileComponent, { onClick: () => this.handleTileClick(i), ref: x => this._tiles.push(x) })) })), jsx(PopupComponent, { ref: this.ref("popup") })] });
    }
    handleTileClick(index) {
        return __awaiter(this, void 0, void 0, function* () {
            if (!this._interactive) {
                return;
            }
            this._interactive = false;
            const response = yield request("POST", this._config.apiPath, { gameId: this._config.gameId, tileIndex: index });
            this._tiles[index].setImage(response.tileImage);
            if (response.message) {
                this.refs.popup.setMessage(response.message);
                this.refs.popup.show(true);
            }
            else {
                this._interactive = true;
            }
        });
    }
    onAttach() {
        if (this._config.reveledTiles) {
            this._config.reveledTiles.forEach(x => this._tiles[x.index].setImage(x.image));
        }
        if (this._config.message) {
            this.refs.popup.setMessage(this._config.message);
            this.refs.popup.show();
        }
        else {
            this.refs.popup.hide();
        }
    }
}
const mainComponent = new MainComponent(window.config);
mainComponent.createOrphanized(document.body);

/******/ })()
;