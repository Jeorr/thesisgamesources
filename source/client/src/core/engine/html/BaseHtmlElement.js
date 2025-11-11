import MathUtility from "../../MathUtility";

export default class BaseHtmlElement {
    constructor() {
        this.internalId = MathUtility.generateRandomId();
        this.original = null;
        this.preserveOnSceneChange = false;
        this.removeFunction = null;
    }

    getInternalId() {
        return this.internalId;
    }

    getOriginal() {
        return this.original;
    }

    setOriginal(original) {
        this.original = original;
    } 

    getPreserveOnSceneChange() {
        return this.preserveOnSceneChange;
    }

    setPreserveOnSceneChange(preserveOnSceneChange) {
        this.preserveOnSceneChange = preserveOnSceneChange;
    }

    getRemoveFunction() {
        return this.removeFunction;
    }

    setRemoveFunction(removeFunction) {
        this.removeFunction = removeFunction;
    }

    remove() {
        if (this.removeFunction) {
            this.removeFunction();
        }

        this.clear();
    }
    
    clear() {
        Object.keys(this).forEach(key => delete this[key]);
    }
}
