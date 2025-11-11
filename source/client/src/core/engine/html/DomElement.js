import BaseHtmlElement from "./BaseHtmlElement";

export default class DomElement extends BaseHtmlElement {
    constructor() {
        super();

        this.node = null;
    }

    getNode() {
        return this.node;
    }

    setNode(node) {
        this.node = node;
    }
}
