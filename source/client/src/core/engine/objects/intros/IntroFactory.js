import IntroChapter1 from "./predefined/IntroChapter1";

export default class IntroFactory {
    static classMapIntros = {
        'introchapter1': IntroChapter1,
    };

    constructor() {
    }

    /**
     * Create intro
     *
     * @param introKey
     * @returns {*}
     */
    static createIntro(introKey) {
        let intro = null;
        if (introKey && (introKey in IntroFactory.classMapIntros)) {
            intro = new (IntroFactory.classMapIntros[introKey])(introKey);
        } else {
            throw new Error('Could not create intro: given introKey is not configured - '  + introKey)
        }

        intro.buildIntro();

        return intro;
    }
}
