import CONSTANTS from "../../../../Constants";
import TranslationsUtility from "../../../../TranslationsUtility";
import Intro from "../Intro";

export default class IntroChapter1 extends Intro {
    /** List of used videos for preloading */
    static combinedIntroVideos = ['chapter1scene1', 'chapter1scene2', 'chapter1scene3', 'chapter1scene4', 'chapter1scene5', 'chapter1scene6', 'chapter1scene7', 'chapter1scene8', 'chapter1scene9', 'chapter1scene10', 'chapter1scene11', 'chapter1scene12', 'chapter1scene13'];

    /** List of used sounds for preloading */
    static combinedIntroSounds = ['chapter1sound1', 'chapter1sound2', 'chapter1sound3', 'chapter1sound4', 'chapter1sound5', 'chapter1sound6', 'chapter1sound7', 'chapter1sound8', 'chapter1sound9', 'chapter1sound10', 'chapter1sound11', 'chapter1sound12', 'chapter1sound13', 'chapter1sound14', 'chapter1sound15'];

    /** List of used music for preloading */
    static combinedIntroMusic = ['chapter1music1'];

    constructor(objectType) {
        super(objectType);
    }

    buildIntro() {
        // videos
        this.addClip({
            hideoutStartAt: 2,
            key: 'chapter1scene1',
            duration: 8,
            hideinStartAt: 7,
        });
        this.addClip({
            key: 'chapter1scene2',
            duration: 10,
            //hideinStartAt: 9,
        });
        this.addClip({
            key: 'chapter1scene3',
            duration: 7,
            hideinStartAt: 5,
        });
        this.addClip({
            key: 'chapter1scene4',
            duration: 8,
            //hideinStartAt: 4,
        });
        this.addClip({
            key: 'chapter1scene5',
            duration: 8,
            hideinStartAt: 6,
        });
        this.addClip({
            key: 'chapter1scene6',
            duration: 8,
            hideinStartAt: 7,
        });
        this.addClip({
            key: 'chapter1scene7',
            duration: 6,
            hideinStartAt: 5,
        });
        this.addClip({
            key: 'chapter1scene8',
            duration: 3,
            hideinStartAt: 2,
        });
        this.addClip({
            key: 'chapter1scene9',
            duration: 5,
            hideinStartAt: 4,
        });
        this.addClip({
            hideoutStartAt: 2,
            key: 'chapter1scene10',
            duration: 7,
            hideinStartAt: 6,
        });
        this.addClip({
            hideoutStartAt: 2,
            key: 'chapter1scene11',
            duration: 8,
            hideinStartAt: 6,
        });
        this.addClip({
            key: 'chapter1scene12',
            hideoutStartAt: 3,
            duration: 10,
            hideinStartAt: 9,
        });
        this.addClip({
            key: 'chapter1scene13',
            duration: 8,
            hideinStartAt: 7,
        });

        // texts
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_1'),
            duration: 7,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_2'),
            duration: 8,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_3'),
            duration: 5,
        });
        this.addText({
            text: '',
            duration: 1,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_4'),
            duration: 6,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_5'),
            duration: 4,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_6'),
            duration: 5,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_7'),
            duration: 6,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_8'),
            duration: 5,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_9'),
            duration: 5,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_10'),
            duration: 5,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_11'),
            duration: 5,
        });
        this.addText({
            text:'',
            duration: 1,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_12'),
            duration: 4,
        });
        this.addText({
            text: '',
            duration: 1,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_13'),
            duration: 4,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_14'),
            duration: 3,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_15'),
            duration: 3,
        });
        this.addText({
            text: '',
            duration: 4,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_16'),
            duration: 4,
        });
        this.addText({
            text: '',
            duration: 2,
        });
        this.addText({
            text: TranslationsUtility.getTranslation('CHAPTER_1_INTRO_TEXT_17'),
            duration: 4,
        });

        // sounds
        this.addSound({
            key: 'chapter1sound1',
            startAt: 2,
        });
        this.addSound({
            key: 'chapter1sound2',
            startAt: 7,
        });
        this.addSound({
            key: 'chapter1sound3',
            startAt: 15,
        });
        this.addSound({
            key: 'chapter1sound4',
            startAt: 21,
        });
        this.addSound({
            key: 'chapter1sound5',
            startAt: 27,
        });
        this.addSound({
            key: 'chapter1sound6',
            startAt: 31,
        });
        this.addSound({
            key: 'chapter1sound7',
            startAt: 37,
        });
        this.addSound({
            key: 'chapter1sound8',
            startAt: 41,
        });
        this.addSound({
            key: 'chapter1sound9',
            startAt: 47,
        });
        this.addSound({
            key: 'chapter1sound10',
            startAt: 52,
        });
        this.addSound({
            key: 'chapter1sound11',
            startAt: 57,
        });
        this.addSound({
            key: 'chapter1sound12',
            startAt: 60,
        });
        this.addSound({
            key: 'chapter1sound13',
            startAt: 60.5,
        });
        this.addSound({
            key: 'chapter1sound14',
            startAt: 61,
        });
        this.addSound({
            key: 'chapter1sound15',
            startAt: 62,
        });
        this.addSound({
            key: 'chapter1sound16',
            startAt: 68,
        });
        this.addSound({
            key: 'chapter1sound17',
            startAt: 71,
        });
        this.addSound({
            key: 'chapter1sound18',
            startAt: 75,
        });
        this.addSound({
            key: 'chapter1sound19',
            startAt: 82,
        });


        // music
        this.addMusic({
            key: 'chapter1music1',
            startAt: 0,
        });

        this.setIntroEndFunction(() => {
            console.log('IntroChapter1 ended');
        });
    }
}
