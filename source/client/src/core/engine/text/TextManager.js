export default class TextManager {
    constructor(adapter) {
        this.adapter = adapter;
    }

    /**
     *
     * @param textData
     * @param scene
     * @returns {Text}
     */
    addTextOnScene(textData, scene = null) {
        let textValue = textData.value || null;

        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        if (!textValue) {
            throw new Error('Value must be defined for the textData object!');
        }

        let text = this.adapter.addTextOnScene(textData, scene);

        return text;
    }
}
