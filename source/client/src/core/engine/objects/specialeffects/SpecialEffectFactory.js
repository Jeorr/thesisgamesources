import SolarBeamSpecialEffect from "./predefined/SolarBeamSpecialEffect";
import StunEffectSpecialEffect from "./predefined/StunEffectSpecialEffect";
import ShadowGeneralSpecialEffect from "./predefined/ShadowGeneralSpecialEffect";
import ShadowHighlightSpecialEffect from "./predefined/ShadowHighlightSpecialEffect";
import ShadowBackgroundSpecialEffect from "./predefined/ShadowBackgroundSpecialEffect";
import SimpleGlowSpecialEffect from "./predefined/SimpleGlowSpecialEffect";
import HeroBlockSpecialEffect from "./predefined/HeroBlockSpecialEffect";

export default class SpecialEffectFactory {
    static preloadKeys = [
        'solarbeam',
        'stuneffect',
        'shadowgeneral',
        'shadowhighlight',
        'shadowbackground',
        'simpleglow',
        'heroblock',
    ];
    
    static classMapSpecialEffects = {
        'solarbeam': SolarBeamSpecialEffect,
        'stuneffect': StunEffectSpecialEffect,
        'shadowgeneral': ShadowGeneralSpecialEffect,
        'shadowhighlight': ShadowHighlightSpecialEffect,
        'shadowbackground': ShadowBackgroundSpecialEffect,
        'simpleglow': SimpleGlowSpecialEffect,
        'heroblock': HeroBlockSpecialEffect,
    };

    constructor() {
    }

    /**
     * Create special effect on unit
     *
     * @param specialEffectKey
     * @param unit
     * @param attachedSkeletonPointKey
     * @param additionalData
     * @returns {*}
     */
    static createSpecialEffectOnUnit(specialEffectKey, unit, attachedSkeletonPointKey = null, additionalData = {}) {
        let specialEffect = this.createSpecialEffect(specialEffectKey, additionalData);
        specialEffect.setAttachToUnit(unit);
        specialEffect.setAttachedSkeletonPointKey(attachedSkeletonPointKey);

        return specialEffect;
    }

    /**
     * Create special effect
     *
     * @param specialEffectKey
     * @param additionalData
     * @returns {*}
     */
    static createSpecialEffect(specialEffectKey, additionalData = {}) {
        let specialEffect = null;
        if (specialEffectKey && (specialEffectKey in SpecialEffectFactory.classMapSpecialEffects)) {
            specialEffect = new (SpecialEffectFactory.classMapSpecialEffects[specialEffectKey])(specialEffectKey);
        } else {
            throw new Error('Could not create special effect: given specialEffectKey is not configured - '  + specialEffectKey)
        }

        specialEffect.buildEffect(additionalData);

        return specialEffect;
    }
}
