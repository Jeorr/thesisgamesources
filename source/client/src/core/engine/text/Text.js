export default class Text {
    constructor(x, y, text) {
        this.x = x;
        this.y = y;
        this.text = text;
        this.original = null;
    }

    getOriginal() {
        return this.original;
    }

    setOriginal(original) {
        this.original = original;
    }
}
