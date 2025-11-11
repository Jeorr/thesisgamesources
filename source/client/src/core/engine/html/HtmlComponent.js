import DomElement from "./DomElement";

export default class HtmlComponent extends DomElement {
    constructor(key) {
        super();

        this.key = key;
    }

    getKey() {
        return this.key;
    }
}
