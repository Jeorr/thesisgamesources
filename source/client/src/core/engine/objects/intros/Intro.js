import CONSTANTS from "../../../Constants";
import BaseObject from "../BaseObject";

export default class Intro extends BaseObject {
    constructor(objectType) {
        super(objectType);

        this.clips = [];
        this.clipsInProcess = [];
        this.texts = [];
        this.textsInProcess = [];
        this.sounds = [];
        this.musics = [];
        this.timers = [];
        this.introStartFunction = null;
        this.introEndFunction = null;
        this.clipEndFunction = null;
        this.soundEndFunction = null;
        this.textEndFunction = null;
        this.musicEndFunction = null;
    }

    setIntroStartFunction(callback) {
        this.introStartFunction = callback;
    }

    setIntroEndFunction(callback) {
        this.introEndFunction = callback;
    }

    setClipEndFunction(callback) {
        this.clipEndFunction = callback;
    }

    setSoundEndFunction(callback) {
        this.soundEndFunction = callback;
    }

    setTextEndFunction(callback) {
        this.textEndFunction = callback;
    }

    setMusicEndFunction(callback) {
        this.musicEndFunction = callback;
    }

    getClips() {
        return this.clips;
    }

    getTexts() {
        return this.texts;
    }

    getSounds() {
        return this.sounds;
    }

    getMusics() {
        return this.musics;
    }

    addClip(clip) {
        if (!clip.hasOwnProperty('key')) {
            throw new Error('Clip must have a key');
        }

        if (!clip.hasOwnProperty('duration')) {
            throw new Error('Clip must have a duration');
        }

        this.clips.push(clip);
    }

    addText(text) {
        if (!text.hasOwnProperty('text')) {
            throw new Error('Text must have a text');
        }

        if (!text.hasOwnProperty('duration')) {
            throw new Error('Text must have a duration');
        }

        this.texts.push(text);
    }

    addSound(sound) {
        if (!sound.hasOwnProperty('key')) {
            throw new Error('Sound must have a key');
        }

        if (!sound.hasOwnProperty('startAt')) {
            throw new Error('Sound must have a startAt');
        }

        this.sounds.push(sound);
    }

    addMusic(music) {
        if (!music.hasOwnProperty('key')) {
            throw new Error('Music must have a key');
        }

        if (!music.hasOwnProperty('startAt')) {
            throw new Error('Music must have a startAt');
        }

        this.musics.push(music);
    }

    runIntro() {
        let clips = [];
        let sounds = [];
        let musics = [];
        let loaded = 0;

        this.clips.forEach(clip => {
            let clipNode = this.objectManager.addVideoObjectOnScene({
                key: clip.key,
                x: clip.x || 0,
                y: clip.y || 0,
            });

            clipNode.setMetadataLoadedFunction(() => {
                loaded++;
                let expectedDuration = clip.duration || 0;

                // Only set play speed if duration is explicitly defined and not zero
                if (expectedDuration > 0) {
                    let originalDuration = clipNode.getDuration();
                    let playSpeed = originalDuration / expectedDuration;
                    clipNode.setPlaySpeed(playSpeed);
                }
                let data = {};
                if (clip.hideinStartAt) {
                    data.hideinStartAt = clip.hideinStartAt;
                }
                if (clip.hideoutStartAt) {
                    data.hideoutStartAt = clip.hideoutStartAt;
                }

                clipNode.setData(data);
                clipNode.setVisible(false);
                clipNode.setMuted(true);

                if (loaded === this.clips.length) {
                    loaded = -9999;
                    this.runNextClip();
                    this.runNextText();
                    this.runAllSounds();
                    this.runAllMusics();

                    if (this.introStartFunction) {
                        this.introStartFunction();
                    }
                }
            });

            clips.push(clipNode);
        });

        this.sounds.forEach(sound => {
            let soundNode = this.objectManager.addAudioObjectOnScene({
                key: sound.key,
            });

            let expectedDuration = sound.duration || 0;

            soundNode.setMetadataLoadedFunction(() => {
                if (expectedDuration > 0) {
                    let originalDuration = soundNode.getDuration();
                    let playSpeed = originalDuration / expectedDuration;
                    soundNode.setPlaySpeed(playSpeed);
                }
                soundNode.setVolume(CONSTANTS.SETTINGS_DEFAULT_SPEECH_SOUND_VOLUME);
            });

            sounds.push([
                soundNode,
                sound.startAt,
            ]);
        });

        this.musics.forEach(music => {
            let musicNode = this.objectManager.addAudioObjectOnScene({
                key: music.key,
            });

            let expectedDuration = music.duration || 0;

            musicNode.setMetadataLoadedFunction(() => {
                if (expectedDuration > 0) {
                    let originalDuration = musicNode.getDuration();
                    let playSpeed = originalDuration / expectedDuration;
                    musicNode.setPlaySpeed(playSpeed);
                }
                musicNode.setVolume(CONSTANTS.SETTINGS_DEFAULT_BACKGROUND_MUSIC_VOLUME);
            });

            musics.push([
                musicNode,
                music.startAt,
            ]);
        });

        this.clips = clips;
        this.clipsInProcess = [...this.clips];
        this.textsInProcess = [...this.texts];
        this.sounds = sounds;
        this.musics = musics;
    }

    runNextClip() {
        let nextClip = this.clipsInProcess.shift();
        if (!nextClip) {
            this.clipsInProcess = null;
            if (this.clipEndFunction) {
                this.clipEndFunction();
            }
            this.introEnd();
            return;
        }

        nextClip.setCompleteFunction(() => {
            this.objectManager.removeObjectFromScene(nextClip);
            this.runNextClip();
        });

        nextClip.setVisible(true);
        this.objectManager.objectFitToScreen(nextClip, 'cover');
        nextClip.play();

        setTimeout(() => {
            this.objectManager.objectFitToScreen(nextClip, 'cover');
        }, 500);

        console.log('nextClip', nextClip);
        let clipData = nextClip.getData();
        let hideoutStartAt = clipData.hideoutStartAt || 0;
        let hideinStartAt = clipData.hideinStartAt || null;
        console.log(hideoutStartAt);
        this.timers.push(setTimeout(() => {
            App().getEngine().getUI().updateChapterIntroFrames({
                blackmask: false,
            });
        }, hideoutStartAt * 1000));

        if (hideinStartAt) {
            this.timers.push(setTimeout(() => {
                App().getEngine().getUI().updateChapterIntroFrames({
                    blackmask: true,
                });
            }, hideinStartAt * 1000));
        }
    }

    runNextText() {
        let nextText = this.textsInProcess.shift();

        if (!nextText) {
            this.textsInProcess = null;
            if (this.textEndFunction) {
                this.textEndFunction();
            }
            this.introEnd();
            return;
        }

        let text = nextText.text || '';
        let duration = nextText.duration || 0;

        App().getEngine().getUI().updateChapterIntroFrames({
            text: text,
        });

        this.timers.push(setTimeout(() => {
            this.runNextText();
        }, duration * 1000));
    }

    runAllSounds() {
        this.sounds.forEach(soundConfig => {
            let sound = soundConfig[0];
            let startAt = soundConfig[1] || 0;
            console.log('sound startAt', sound, startAt);
            this.timers.push(setTimeout(() => {
                sound.play();
            }, startAt * 1000));
        });
    }

    runAllMusics() {
        this.musics.forEach(musicConfig => {
            let music = musicConfig[0];
            let startAt = musicConfig[1] || 0;
            console.log('music startAt', music, startAt);
            this.timers.push(setTimeout(() => {
                music.play();   
            }, startAt * 1000));
        });
    }

    introEnd(force = false) {
        // early return if any of media elements showing is not finished
        if ((this.clipsInProcess !== null || this.textsInProcess !== null) && !force) {
            return;
        }

        if (this.introEndFunction) {
            this.introEndFunction();
        }

        console.log('before remove', this);
        this.remove();
    }

    remove() {
        if (this.removeFunction) {
            this.removeFunction();
        }

        this.clips.forEach(clip => {
            this.objectManager.removeObjectFromScene(clip);
        });

        console.log(this.sounds);

        this.sounds.forEach(soundConfig => {
            let sound = soundConfig[0];
            console.log('removeSound', sound);
            this.objectManager.removeObjectFromScene(sound);
        });

        this.musics.forEach(musicConfig => {
            let music = musicConfig[0];
            console.log('removeMusic', music);
            this.objectManager.removeObjectFromScene(music);
        });

        this.timers.forEach(timer => {
            clearTimeout(timer);
        });

        this.clear();
    }
}
